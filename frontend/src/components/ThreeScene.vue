<template>
  <!-- The canvas element will be rendered here -->
  <canvas class="webgl"></canvas>
</template>

<script>
import * as THREE from 'three';
import gsap from 'gsap';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js';

export default {
  name: "ThreeScene",
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
    };
  },
  mounted() {
    // Initialize the scene when the component is mounted
    this.initThree();
    window.addEventListener("resize", this.onResize);
    window.addEventListener("dblclick", this.onDoubleClick);
    window.addEventListener("mousemove", this.onMouseMove);
    console.log("hello three js");
  },
  beforeUnmount() {
    // Clean up event listeners
    window.removeEventListener("resize", this.onResize);
    window.removeEventListener("dblclick", this.onDoubleClick);
    window.removeEventListener("mousemove", this.onMouseMove);
  },
  methods: {
    initThree() {
      // Create the scene
      this.scene = new THREE.Scene();

      // Create a wireframe cube
      const geometry = new THREE.BoxGeometry(1, 1, 1);
      const material = new THREE.MeshBasicMaterial({
        color: "#ff0000",
        wireframe: true,
      });
      this.mesh = new THREE.Mesh(geometry, material);
      // Position the cube (example position)
      this.mesh.position.set(1.5, -0.6, 1);
      this.scene.add(this.mesh);

      // Add an axes helper to the scene (for debugging)
      const axesHelper = new THREE.AxesHelper(3);
      this.scene.add(axesHelper);

      // Create the camera
      this.camera = new THREE.PerspectiveCamera(
        75,
        this.sizes.width / this.sizes.height,
        0.1,
        100
      );
      this.camera.position.z = 2;
      this.scene.add(this.camera);

      // Get the canvas from the component template
      const canvas = this.$el;
      // Create the renderer
      this.renderer = new THREE.WebGLRenderer({ canvas });
      this.renderer.setSize(this.sizes.width, this.sizes.height);
      this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

      // Set up OrbitControls
      this.controls = new OrbitControls(this.camera, canvas);
      this.controls.enableDamping = true;

      // Start the animation loop
      this.animate();
    },
    animate() {
      // Update controls for damping effect
      this.controls.update();
      // Render the scene
      this.renderer.render(this.scene, this.camera);
      // Request the next frame; note the arrow function to preserve 'this'
      requestAnimationFrame(() => this.animate());
    },
    onResize() {
      // Update sizes
      this.sizes.width = window.innerWidth;
      this.sizes.height = window.innerHeight;
      // Update camera aspect ratio and projection matrix
      this.camera.aspect = this.sizes.width / this.sizes.height;
      this.camera.updateProjectionMatrix();
      // Update renderer
      this.renderer.setSize(this.sizes.width, this.sizes.height);
      this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    },
    onDoubleClick() {
      // Toggle fullscreen on double-click
      const canvas = this.$el;
      if (!document.fullscreenElement) {
        canvas.requestFullscreen();
      } else {
        document.exitFullscreen();
      }
    },
    onMouseMove(event) {
      // Example: log cursor coordinates relative to the viewport
      // You can implement more complex behavior if needed.
      // For instance, you could update a cursor object:
      // this.cursor.x = event.clientX / this.sizes.width - 0.5;
      // this.cursor.y = - (event.clientY / this.sizes.height - 0.5);
    },
  },
};
</script>

<style scoped>
canvas.webgl {
  display: block;
  /* Optionally, force full viewport size */
  width: 100vw;
  height: 100vh;
}
</style>
