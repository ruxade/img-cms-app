<template>
  <div class="gallery">
    <h1>360 Images</h1>
    <div class="image-list">
      <template v-if="images.length">
        <div v-for="image in images" :key="image.id" class="image-item">
          <img :src="imageUrl(image.path)" alt="Uploaded Image" />
          <p class="caption">{{ image.caption }}</p>

        </div>
      </template>
      <div v-else class="no-images">
        <p>No public images uploaded yet.</p>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'Home',
  data() {
    return {
      images: [],
    }
  },
  methods: {
    imageUrl(path) {
      return `http://localhost:8000/storage/${path}`;
    },
    async fetchImages() {
      try {
        // Fetch public images without needing an Authorization header.
        const response = await axios.get('http://localhost:8000/api/public-images', {
          headers: { 'Accept': 'application/json' }
        });
        this.images = response.data;
      } catch (err) {
        console.error("Error fetching public images", err);
      }
    },
    view3D(image) {
      // Navigate to the 3D projection page, for example:
      this.$router.push({ name: 'Image3D', params: { id: image.id } });
    }
  },
  mounted() {
    this.fetchImages();
  },
}
</script>

<style scoped>



</style>
