<template>
  <div class="image-3d">
    <h1>3D Viewer</h1>
<div id="threejs-container"></div>
  </div>
</template>

<script>
import axios from 'axios'
import * as THREE from 'three';


export default {
  name: 'Image3D',
  data() {
    return {
      imageId: null,
      imagePath: '',
      // Remove scene, camera, renderer, cube, animationId from reactive state.
    }
  },
  methods: {
    imageUrl(path) {
      return `http://localhost:8000/storage/${path}`;
    },
    init3D() {
      const container = document.getElementById('threejs-container');
      const width = container.clientWidth;
      const height = container.clientHeight || window.innerHeight;

      // Create scene and assign directly to 'this'
      this.scene = new THREE.Scene();

      // Create camera
      this.camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
      this.camera.position.z = 3;

      // Create renderer
      this.renderer = new THREE.WebGLRenderer({ antialias: true });
      this.renderer.setSize(width, height);
      container.appendChild(this.renderer.domElement);

      // Load texture from uploaded image
      const textureUrl = `http://localhost:8000/storage/${this.imagePath}`;
      const texture = new THREE.TextureLoader().load(textureUrl);

      // Create cube with texture
      const geometry = new THREE.BoxGeometry(2, 2, 2);
      const material = new THREE.MeshBasicMaterial({ map: texture });
      this.cube = new THREE.Mesh(geometry, material);
      this.scene.add(this.cube);

      // Start animation
      this.animate();
    },
    animate() {
      this.animationId = requestAnimationFrame(() => this.animate());
      this.cube.rotation.x += 0.01;
      this.cube.rotation.y += 0.01;
      this.renderer.render(this.scene, this.camera);
    },
    cleanup() {
      cancelAnimationFrame(this.animationId);
      if (this.renderer) {
        this.renderer.dispose();
      }
    },
    async fetchImageDetails() {
      try {
        const response = await axios.get(`http://localhost:8000/api/images/${this.imageId}`, {
          headers: { 'Accept': 'application/json' }
        });
        this.imagePath = response.data.path;
        this.init3D();
      } catch (err) {
        console.error("Error fetching image details", err);
      }
    }
  },
  mounted() {
    this.imageId = this.$route.params.id;
    this.fetchImageDetails();
  },
  beforeUnmount() {
    this.cleanup();
  }
}



</script>



<style scoped>
.image-3d {
  height: 100vh;
  width: 100vw;
  overflow: hidden;
  background-color: #000;
  color: #fff;
}
#threejs-container {
  height: 100%;
  width: 100%;
}
</style>
