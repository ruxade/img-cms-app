<template>
  <div class="image-show">
    <h1> Image Details</h1>

    <div v-if="image">
      <img :src="imageUrl(image.path)" alt="Image" />

      <div class="info">

        <p><strong>Caption:</strong> {{ image.caption }}</p>
        <p><strong>Visibility:</strong> {{ image.visibility }}</p>
        <!-- <p><strong>Uploaded On:</strong> {{ formatDate(image.created_at) }}</p> -->
      </div>

      <button @click="goBack" class="btn-back">
        <i class="fa-solid fa-arrow-left"></i> Back
      </button>
    </div>

    <div v-else>
      <p>Loading image details...</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ImageShow',
  data() {
    return {
      image: null,
      imageId: null,
    }
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
    }
  },
  mounted() {
    // Retrieve the image ID from route parameters
    this.imageId = this.$route.params.id;
    this.fetchImage();
  }
}
</script>

<style scoped>
.image-show {
  max-width: 600px;
  margin: 2rem auto;
  padding: 1rem;
  text-align: center;
  gap: 20px;
}
.info {
  text-align: left;
  margin: 2rem auto;
}
.image-show img {
  width: 100%;
  height: auto;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-bottom: 1rem;
}
</style>
