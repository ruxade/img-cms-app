<template>
  <div class="image-list">
    <h1>Your Images</h1>
    <div v-if="images.length">
      <div v-for="image in images" :key="image.id" class="image-item">
        <img :src="imageUrl(image.path)" alt="Uploaded Image" />
        <p>{{ image.caption }}</p>
        <button @click="deleteImage(image.id)">Delete</button>
      </div>
    </div>
    <div v-else>
      <p>No images uploaded yet.</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ImageList',
  data() {
    return {
      images: []
    }
  },
  methods: {
    imageUrl(path) {
      // Assuming you have set up a storage link (php artisan storage:link)
      return `http://localhost:8000/storage/${path}`
    },
    async fetchImages() {
      try {
        const token = localStorage.getItem('access_token')
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        const response = await axios.get('http://localhost:8000/api/images', {
          headers: { 'Accept': 'application/json' }
        })
        this.images = response.data
      } catch (err) {
        console.error("Error fetching images", err)
      }
    },
    async deleteImage(id) {
      try {
        const token = localStorage.getItem('access_token')
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        await axios.delete(`http://localhost:8000/api/images/${id}`, {
          headers: { 'Accept': 'application/json' }
        })
        this.fetchImages()
      } catch (err) {
        console.error("Error deleting image", err)
      }
    }
  },
  mounted() {
    this.fetchImages()
  }
}
</script>

<style scoped>
.image-list {
  max-width: 800px;
  margin: 2rem auto;
}
.image-item {
  margin-bottom: 1rem;
  border-bottom: 1px solid #ddd;
  padding-bottom: 1rem;
}
.image-item img {
  max-width: 100%;
  height: auto;
}
</style>
