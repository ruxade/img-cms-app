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

          <div class="info">
            <div class="btn-3d" @click="view3D(image)">
              <i class="fa-solid fa-cube"></i>
            </div>
            <p class="caption">{{ image.caption }}</p>
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

export default {
  name: 'Home',
  data() {
    return {
      images: [],
    }
  },
  methods: {
    imageUrl(path) {
      return this.$imageUrl(path);
    },

    async fetchImages() {
      if (navigator.onLine) {
        try {
          const response = await this.$axios.get('/public-images');
          this.images = response.data.reverse();
          // Save images in IndexedDB for offline use
          await saveImages(this.images);
        } catch (err) {
          console.error("Error fetching public images", err);
          // Fallback to offline images if API call fails
          this.images = await getOfflineImages();
        }
      } else {
        // Offline: load images from IndexedDB
        this.images = await getOfflineImages();
      }
    },
    view3D(image) {
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

.image-item {
  position: relative;

  margin-bottom: 3rem;
  padding: 0;
  border: 1px solid #8c8c8c92;
  box-shadow: 5px 5px 2px #89a8b292 ;
  border-radius: 15px;
  width: 100%;
  height: 350px;
}
.image-item img {
  width: 100%;
  height: 100%;
  padding: 0;
}
.info {
  opacity: 0.1;
  border-radius: 15px;
  height: 100%;
  width: 100%;
  padding: 45px;
  position: absolute;
}

.btn-3d{
  display: flex;
  justify-content: flex-end;
}

p {
  height: 100%;
  display: flex;
  justify-content: center;
  color: #000000f5;
  align-items: center;

}

.info:hover{
  background-color: #fffffff5;
  opacity: 0.7;
  color: #000000f5;
  font-size: x-large;
}

</style>
