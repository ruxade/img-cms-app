<template>
  <div class="gallery">
    <div class="title-frame">
      <img id="frame" src="@/assets/frame.png" alt="360_Logo"  />
      <!-- <h5>All Images</h5> -->
      <h4 >My Images</h4>
    </div>
    <div class="image-list">
      <template v-if="images.length">
        <div v-for="image in images" :key="image.id" class="image-item">
          <div class="buttons">
            <!--  Delete button -->
            <!-- <div class="btn-delete" @click="deleteImage(image.id)">
              <i class="fa-solid fa-trash"></i>
            </div> -->

            <div class="btn-toggle" @click="toggleVisibility(image)">
              <i v-if="image.visibility === 'public'" class="fa-solid fa-eye"></i>
              <i v-else class="fa-solid fa-eye-slash"></i>
            </div>

            <div class="btn-show" @click="viewShow(image)">
              <i class="fa-solid fa-info-circle"></i>
            </div>

            <!--  3D projection button -->
            <div class="btn-3d" @click="view3D(image)">
              <i class="fa-solid fa-cube"></i>
            </div>
          </div>


          <img :src="imageUrl(image.path)" alt="Uploaded Image" />

            <p class="caption">{{ image.caption }}</p>
            <!-- <p><strong>Caption:</strong> {{ image.caption }}</p> -->
            <p id="visibility">Visibility : {{ image.visibility }} image </p>




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
import { saveImages, getOfflineImages } from '../utils/db'
import Swal from 'sweetalert2'



export default {
  name: 'ImageList',
  data() {
    return {
      images: []
    }
  },
  methods: {

  view3D(image) {
    this.$router.push({ name: 'Image3D', params: { id: image.id } });
  },

  viewShow(image) {
     this.$router.push({ name: 'ImageShow', params: { id: image.id } });
  },

  imageUrl(path) {
      // php artisan storage:link
      return `http://localhost:8000/storage/${path}`
    },
    async fetchImages() {
      if (navigator.onLine) {
        try {
          // Fetch public images
          const response = await axios.get('http://localhost:8000/api/images', {
            headers: { 'Accept': 'application/json' }
          });
          this.images = response.data.reverse();
          // Save images in IndexedDB for offline use
          await saveImages(this.images);
        } catch (err) {
          console.error("Error fetching images from API:", err);
          // Fallback to offline images if API fails
          this.images = await getOfflineImages();
        }
      } else {
        // Offline: load images from IndexedDB
        this.images = await getOfflineImages();
      }
    },

    async deleteImage(id) {

      const result = await Swal.fire({
        title: 'Are you sure?',
        text: 'Do you really want to delete this image?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#960000',
        cancelButtonColor: '#497174',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
      })
      if (!result.isConfirmed) {
        // User canceled deletion
        return;
      }
      try {
        const token = localStorage.getItem('access_token')
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
        await axios.delete(`http://localhost:8000/api/images/${id}`, {
          headers: { 'Accept': 'application/json' }
        })
        // Refresh images
        this.fetchImages()
        Swal.fire('Deleted!', 'Your image has been deleted.', 'success');
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

#visibility {
  position: absolute;
  font-size: small;
  font-style: oblique;
  bottom: 25px;
  /* color: #497174; */
  font-variant-caps: all-small-caps;
  font-stretch: ultra-expanded;
}

.private {
  border: 2px solid #497174;
}

.title-frame {
  min-height: 200px;
  display: flex;
  align-items: center;
  justify-content: center;
  /* position: relative; */
  border-bottom: 2px solid #497174;
  padding-bottom: 50px;
  margin-bottom: 50px;
  position: relative;
}

@keyframes anim {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
  100% {
    opacity: 1;
  }
}

#frame {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 200px;
  position: absolute;
  animation: anim 3s ease-in-out infinite;
}
#frame:hover{
}

h4{
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60%;
  position: absolute;
  color: #497174;

}

</style>
