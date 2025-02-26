<template>
  <div class="gallery">
    <h1>My Images</h1>
    <div class="image-list">
      <template v-if="images.length">
        <div v-for="image in images" :key="image.id" class="image-item">
          <img :src="imageUrl(image.path)" alt="Uploaded Image" />
          <p class="caption">{{ image.caption }}</p>

          <div class="buttons">
            <!--  Delete button -->
            <div class="btn-delete" @click="deleteImage(image.id)">
              <i class="fa-solid fa-trash"></i>
            </div>
            
            <div class="btn-toggle" @click="toggleVisibility(image)">
              <i v-if="image.visibility === 'public'" class="fa-solid fa-eye"></i>
              <i v-else class="fa-solid fa-eye-slash"></i>
            </div>

            <!--  3D projection button -->
            <div class="btn-3d" @click="view3D(image)">
              <i class="fa-solid fa-cube"></i>
            </div>
          </div>
        </div>
      </template>
      <div v-else class="no-images">
        <p>No images uploaded yet.</p>
      </div>
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
      // php artisan storage:link
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
    },

  async toggleVisibility(image) {
      try {
        const token = localStorage.getItem('access_token');
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const response = await axios.patch(`http://localhost:8000/api/images/${image.id}/toggle-visibility`, {}, {
          headers: { 'Accept': 'application/json' }
        });
        // Update the local image with new visibility status
        const index = this.images.findIndex(img => img.id === image.id);
        if (index !== -1) {
          this.images.splice(index, 1, response.data);
        }
      } catch (err) {
        console.error("Error toggling visibility", err);
      }
    }
  },
  mounted() {
    this.fetchImages()
  }
}
</script>

<style scoped>

.buttons {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: space-around;
}

.caption {
  font-style: italic;
  padding: 10px;
}



</style>
