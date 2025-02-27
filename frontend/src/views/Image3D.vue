<template>
  <div class="scene">
    <!-- <button @click="enableMotion" v-if="showButton" class="motion-button">Enable Motion Controls</button> -->
    <canvas ref="canvas" class="webgl"></canvas>

  </div>
</template>

<script>
import { nextTick } from "vue";
import axios from 'axios'
import * as THREE from "three";
import { ref, onMounted, onUnmounted } from "vue";
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";

export default {
  name: "Image3D",

  data() {
    return {
      scene: null,
      camera: null,
      renderer: null,
      mesh: null,
      controls: null,
      sizes: {
        width: window.innerWidth,
        height: window.innerHeight,
      },
      image:null,
      imageId: null,
      cursor: { x: 0, y: 0 },
      animationFrameId: null,
    };
  },
  async mounted() {
    this.imageId = this.$route.params.id;
    await nextTick(); // Ensure DOM is ready before accessing refs

    this.initScene();
    this.initCamera();
    this.initRenderer();
    this.initControls();
    this.initGeometry();
    this.initEventListeners();
    this.animate();

    this.fetchImage();
  },
  beforeUnmount() {
    // Cleanup event listeners and animations
    window.removeEventListener("mousemove", this.handleMouseMove);
    window.removeEventListener("resize", this.handleResize);

    if (this.animationFrameId) {
      cancelAnimationFrame(this.animationFrameId);
    }

    if (this.renderer) {
      this.renderer.dispose();
    }
  },

  methods: {
    async fetchImage() {
      try {
        const response = await axios.get(`http://localhost:8000/api/images/${this.imageId}`, {
          headers: { Accept: "application/json" },
        });
        this.image = response.data;
        this.loadTexture();
      } catch (error) {
        console.error("Error fetching image", error);
      }
    },

    imageUrl(path) {
      return `http://localhost:8000/storage/${path}`;
    },

    initScene() {
      this.scene = new THREE.Scene();
    },

    initCamera() {
      this.camera = new THREE.PerspectiveCamera(75, this.sizes.width / this.sizes.height, 0.1, 1000);
      this.camera.position.set(0, 0, 3);
      this.scene.add(this.camera);
    },

    initRenderer() {
      const canvas = this.$refs.canvas;
      this.renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
      this.renderer.setSize(this.sizes.width, this.sizes.height);
      this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    },

    initControls() {
      this.controls = new OrbitControls(this.camera, this.$refs.canvas);
      this.controls.enableDamping = true;
      this.controls.enableZoom = false;
    },

    initGeometry() {
      // Sphere Geometry (For Panorama)
      const geometry = new THREE.SphereGeometry(500, 32, 32);
      geometry.scale(-1, 1, 1);

      const material = new THREE.MeshBasicMaterial({ color: 0x222222 }); // Placeholder color
      this.mesh = new THREE.Mesh(geometry, material);
      this.scene.add(this.mesh);

      // Red Cube (For Reference)
      const cubeGeometry = new THREE.BoxGeometry(1, 1, 1);
      const cubeMaterial = new THREE.MeshBasicMaterial({ color: "#ff0000" });
      const cubeMesh = new THREE.Mesh(cubeGeometry, cubeMaterial);
      cubeMesh.position.set(2, 2, 2);
      this.scene.add(cubeMesh);
    },

    loadTexture() {
      if (!this.image) return;

      const textureLoader = new THREE.TextureLoader();
      const textureUrl = this.imageUrl(`images/${this.imageId}.png`);
      textureLoader.load(textureUrl, (texture) => {
        this.mesh.material.map = texture;
        this.mesh.material.needsUpdate = true;
      });
    },

    animate() {
      const tick = () => {
        this.camera.lookAt(this.mesh.position);
        this.controls.update();
        this.renderer.render(this.scene, this.camera);
        this.animationFrameId = requestAnimationFrame(tick);
      };
      tick();
    },

    initEventListeners() {
      // Handle Mouse Move
      window.addEventListener("mousemove", this.handleMouseMove);

      // Handle Window Resize
      window.addEventListener("resize", this.handleResize);
    },

    handleMouseMove(event) {
      this.cursor.x = event.clientX / this.sizes.width - 0.5;
      this.cursor.y = -(event.clientY / this.sizes.height - 0.5);
    },

    handleResize() {
      this.sizes.width = window.innerWidth * 0.8;
      this.sizes.height = window.innerHeight * 0.8;

      this.camera.aspect = this.sizes.width / this.sizes.height;
      this.camera.updateProjectionMatrix();

      this.renderer.setSize(this.sizes.width, this.sizes.height);
    },
  },
};
</script>


<style scoped>
.scene {
  width: 100%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: black;
  z-index: 0;
}
</style>
