<template>
  <div class="gallery" id="home">
    <h1>
      <img src="@/assets/360logo_l.png" alt="360_Logo" style="max-height: 300px; width: 100%;  object-fit: contain;" />
      <!-- <h5>All Images</h5> -->
    </h1>


    <div class="image-list">
      <template v-if="images.length">
        <div v-for="image in images" :key="image.id" class="image-item">
          <img :src="imageUrl(image.path)" alt="Uploaded Image" />
          <p class="caption">{{ image.caption }}</p>
                    <div class="btn-3d" @click="view3D(image)">
              <i class="fa-solid fa-cube"></i>
            </div>

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
        this.images = response.data.reverse();
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
#home{
  background-color: #e5e1da67;
  height: 100%;
}

</style>
