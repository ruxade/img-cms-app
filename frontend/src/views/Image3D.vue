<template>
  <div class="scene">
    <!-- <button @click="enableMotion" v-if="showButton" class="motion-button">Enable Motion Controls</button> -->
    <canvas ref="canvas" class="webgl"></canvas>

  </div>
</template>

<script>
import axios from 'axios'
import * as THREE from "three";
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
    };
  },

  mounted() {
    this.imageId = this.$route.params.id;
    this.fetchImage();

    const canvas = this.$refs.canvas; // Use Vue's ref instead of querySelector

    // sizes
    const sizes = {
      width: window.innerWidth * 0.8,
      height: window.innerHeight * 0.8,
    };

    // Cursor object
    const cursor = { x: 0, y: 0 };

    // Cursor movement event listener
    window.addEventListener("mousemove", (event) => {
      cursor.x = event.clientX / sizes.width - 0.5;
      cursor.y = -(event.clientY / sizes.height - 0.5);
    });

    // scene
    const scene = new THREE.Scene();

    // texture
    const textureLoader = new THREE.TextureLoader();
    const textureUrl = this.imageUrl(`images/${this.imageId}.png`);
    const texture = textureLoader.load(textureUrl)

    // texture.mapping = THREE.EquirectangularReflectionMapping;


    // geometry
    const geometry = new THREE.SphereGeometry(500,32, 32);
    geometry.scale(-1, 1, 1);
    const material = new THREE.MeshBasicMaterial({ map: texture });
    const mesh = new THREE.Mesh(geometry, material);
    scene.add(mesh);

    const cube = new THREE.BoxGeometry(1, 1, 1)
const cubeMaterial = new THREE.MeshBasicMaterial({
    color: '#ff0000'
})
const cubeMesh = new THREE.Mesh(cube, cubeMaterial)

scene.add(cubeMesh)

    // perspective camera
    const camera = new THREE.PerspectiveCamera(
      75,
      sizes.width / sizes.height,
      0.1,
      1000
    );
    camera.position.z = 3;
    scene.add(camera);

    // renderer
    const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
    renderer.setSize(sizes.width, sizes.height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    // Controls
    const controls = new OrbitControls(camera, canvas);
    controls.enableDamping = true;
    controls.enableZoom = false;

    // Handle resizing
    window.addEventListener("resize", () => {
      sizes.width = window.innerWidth * 0.8;
      sizes.height = window.innerHeight * 0.8;

      camera.aspect = sizes.width / sizes.height;
      camera.updateProjectionMatrix();

      renderer.setSize(sizes.width, sizes.height);
    });



    // Animation loop
    const tick = () => {
      // Update camera based on cursor movement

      camera.lookAt(mesh.position);

      controls.update();
      renderer.render(scene, camera);
      window.requestAnimationFrame(tick);
    };

    tick();
  },
  methods: {
    imageUrl(path) {
      // Construct the full URL to the stored image
      return `http://localhost:8000/storage/${path}`;
    },
    async fetchImage() {
      try {
          const response = await axios.get(`http://localhost:8000/api/images/${this.imageId}`, {
            headers: { 'Accept': 'application/json' }
          });
          this.image = response.data;
          this.loadTexture(); // Ensure texture is only loaded after fetching the image
        } catch (error) {
          console.error("Error fetching image", error);
        }
      },

    formatDate(dateString) {
      if (!dateString) return "Unknown date";
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString(undefined, options);
    },
    goBack() {
      this.$router.go(-1); // Navigate back to the previous page
    },
    loadTexture() {
  const textureLoader = new THREE.TextureLoader();
  const textureUrl = this.imageUrl(`images/${this.imageId}.png`);
  const texture = textureLoader.load(textureUrl);
  this.mesh.material.map = texture;
  this.mesh.material.needsUpdate = true; // Ensure material updates with new texture
}

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
