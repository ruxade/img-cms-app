<template>

  <!-- <div class="image-show">
    <h3>Preview</h3>
    <div v-if="image">
      <img :src="imageUrl(image.path)" alt="Image" />
    </div>

    <div v-else>
      <p>Loading image ...</p>
    </div>
  </div> -->

  <div class="scene">
    <!-- <button @click="enableMotion" v-if="showButton" class="motion-button">Enable Motion Controls</button> -->
    <canvas ref="canvas" class="webgl"></canvas>
  </div>


</template>

<script>

import {
  WebGLRenderer,
  PerspectiveCamera,
  Scene,
  SphereGeometry,
  MeshBasicMaterial,
  Mesh,
  TextureLoader,
  EquirectangularReflectionMapping,
  LinearFilter,
  // DeviceOrientationControls
} from 'three';
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";
// import { saveImages, getOfflineImages }  from '../utils/db';


export default {
  name: 'ImageShow',
  data() {
    return {
      image: null,
      imageId: null,
    };
  },
  methods: {

    initThreeJS() {
      // Canvas
      const canvas = this.$refs.canvas;

      // Scene
      this.scene = new Scene();
      this.camera = new PerspectiveCamera(
        75,
        window.innerWidth / window.innerHeight,
        0.1,
        1000
      );
      this.camera.position.z = 3;

      this.renderer = new WebGLRenderer({ canvas, antialias: true });
      this.renderer.setSize(window.innerWidth, window.innerHeight);

      // GEOMETRY
      const geometry = new SphereGeometry(500, 60, 40);
      const material = new MeshBasicMaterial({
         color: "#ffffff",
        //  wireframe: true
         });
        //  const imageMaterial = new MeshBasicMaterial({ map: texture });
      this.mesh = new Mesh(geometry, material);
      geometry.scale(-1, 1, 1);
      this.scene.add(this.mesh);

      // CONTROLS
      this.controls = new OrbitControls(this.camera, canvas);
      this.controls.enableDamping = true;
      this.controls.enableZoom = false;
      this.controls.enablePan = false;
      // this.controls.minPolarAngle = Math.PI / 2;
      // this.controls.maxPolarAngle = Math.PI / 2;

      // Initialize DeviceOrientationControls
      // this.deviceControls = new DeviceOrientationControls(this.camera);


      // start render loop
      this.animate();
    },


    imageUrl(path) {
      return `http://localhost:8000/storage/${path}`;
      // return `/storage/${path}`;
    },
    async fetchImage() {
      try {
        const response = await this.$axios.get(`/images/${this.imageId}`);
        this.image = response.data;
        this.loadTexture();
      } catch (error) {
        console.error("Error fetching image", error);
      }
    },


    loadTexture() {
      if (!this.image || !this.image.path) return;

      // const textureUrl = this.image.path;
      const textureUrl = `/storage/${this.image.path}`; // Vite proxy for storage access

      const textureLoader = new TextureLoader();
      textureLoader.load(
        textureUrl,
        (texture) => {
          texture.flipY = false;
          texture.mapping = EquirectangularReflectionMapping;
          texture.minFilter = LinearFilter;

          this.mesh.material.envMap = texture;
          this.mesh.material.color.set(0xffffff);
          this.mesh.material.needsUpdate = true;
        },
        undefined,
        (err) => {
          console.error("Error loading texture", err);
        }
      );
    },
    animate() {
      // animation loop
      const renderLoop = () => {
      //   if (this.deviceControls) {
      //   // Check if device orientation exists
      // this.deviceControls.update();
      // } else if (this.controls) {
      //   //otherwise use OrbitControls
      //   this.controls.update();
      // }
      this.controls.update();
        this.renderer.render(this.scene, this.camera);
        this.animationFrameId = requestAnimationFrame(renderLoop);
      };
      renderLoop();
    },
    handleResize() {
      this.camera.aspect = window.innerWidth / window.innerHeight;
      this.camera.updateProjectionMatrix();
      this.renderer.setSize(window.innerWidth, window.innerHeight);
    },
  },
  mounted() {
    // Getting image ID from the route
    this.imageId = this.$route.params.id;

    this.initThreeJS();

    this.fetchImage();
    window.addEventListener('resize', this.handleResize);
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.handleResize);
    if (this.animationFrameId) {
      cancelAnimationFrame(this.animationFrameId);
    }
    if (this.renderer) {
      this.renderer.dispose();
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
