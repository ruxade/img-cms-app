<template>
  <div class="image-show">

    <div v-if="image">
      <img :src="imageUrl(image.path)" alt="Image" />
    </div>

    <div v-else>
      <p>Loading image details...</p>
    </div>
  </div>

  <div class="scene">
    <!-- <button @click="enableMotion" v-if="showButton" class="motion-button">Enable Motion Controls</button> -->
    <canvas ref="canvas" class="webgl"></canvas>
  </div>


</template>

<script>
import axios from 'axios'
import {
  WebGLRenderer,
  PerspectiveCamera,
  Scene,
  SphereGeometry,
  MeshBasicMaterial,
  Mesh,
  TextureLoader
} from 'three';
import { OrbitControls } from "three/examples/jsm/controls/OrbitControls.js";




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
      const geometry = new SphereGeometry(50, 32, 32);
      const material = new MeshBasicMaterial({
         color: "#ff0000",
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

      // start render loop
      this.animate();
    },


    imageUrl(path) {
      return `http://localhost:8000/storage/${path}`;
    },
    async fetchImage() {
      try {
        const response = await axios.get(`http://localhost:8000/api/images/${this.imageId}`, {
          headers: { 'Accept': 'application/json' }
        });
        this.image = response.data;
        this.loadTexture(); //Load image as texture
      } catch (error) {
        console.error("Error fetching image", error);
      }
    },


    loadTexture() {
      if (!this.image || !this.image.path) return;

      const textureUrl = this.imageUrl(this.image.path);
      axios
        .get(textureUrl, { responseType: 'blob' })
        .then((response) => {
          const blob = response.data;
          const objectUrl = URL.createObjectURL(blob);
          const textureLoader = new TextureLoader();
          textureLoader.crossOrigin = 'anonymous';
          textureLoader.load(
            objectUrl,
            (texture) => {
              this.mesh.material.map = texture;
              this.mesh.material.needsUpdate = true;
              // Optionally revoke the object URL after the texture is loaded
              URL.revokeObjectURL(objectUrl);
            },
            undefined,
            (err) => {
              console.error("Error loading texture", err);
            }
          );
        })
        .catch((err) => {
          console.error("Error fetching image as blob", err);
        });
    },

    animate() {
      // animation loop
      const renderLoop = () => {
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
.image-show {
  max-width: 600px;
  margin: 2rem auto;
  padding: 1rem;
  text-align: center;
  gap: 20px;
}
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
