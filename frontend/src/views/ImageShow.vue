<template>
  <div class="image-show">
    <h3> Image Details</h3>

    <div v-if="image">
      <img :src="imageUrl(image.path)" alt="Image" />

      <div class="info">
        <div class="buttons-show">
            <!--  Delete button -->
            <div class="btn-delete btn" @click="deleteImage(image.id)">
              <i class="fa-solid fa-trash"></i>
            </div>

            <div class="btn-toggle btn" @click="toggleVisibility(image)">
              <i v-if="image.visibility === 'public'" class="fa-solid fa-eye"></i>
              <i v-else class="fa-solid fa-eye-slash"></i>
            </div>

            <!--  3D projection button -->
            <div class="btn-3d btn" @click="view3D(image)">
              <i class="fa-solid fa-cube"></i>
            </div>
          </div>

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
import { saveImages, getOfflineImages } from '../utils/db'
import Swal from 'sweetalert2'

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

    goBack() {
      this.$router.go(-1); // Navigate back to the previous page
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

      Swal.fire('Deleted!', 'Your image has been deleted.', 'success')
      .then(() => {
        this.$router.push({ name: 'Images' });
      });
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
    this.image = response.data;
  } catch (err) {
    console.error("Error toggling visibility", err);
  }
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
.buttons-show{

  display: flex;
  justify-content: space-around;
  align-items: center;
  margin: 30px 0;
}

.btn{
color: #497174;
border: 1px solid #497174;
padding: 5px 30px;
border-radius: 20px;
}
</style>
