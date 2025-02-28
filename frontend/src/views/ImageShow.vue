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

// import { saveImages, getOfflineImages } from '../utils/db'
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
    view3D(image) {
      this.$router.push({ name: 'Image3D', params: { id: image.id } });
    },
    imageUrl(path) {
      return this.$imageUrl(path);
    },
    async fetchImage() {
      try {
        const response = await this.$axios.get(`/images/${this.imageId}`);
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
        await this.$axios.delete(`/images/${id}`);
        Swal.fire('Deleted!', 'Your image has been deleted.', 'success')
          .then(() => {
            this.$router.push({ name: 'Images' });
          });
      } catch (err) {
        console.error("Error deleting image", err);
      }
    },

    async toggleVisibility(image) {
      try {
        const response = await this.$axios.patch(`/images/${image.id}/toggle-visibility`);
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
  max-width: 60%;
  margin: 2rem auto;
  padding: 1rem;
  text-align: center;

}
.info {
  text-align: left;
  margin: 2rem auto;
}
.image-show img {
  width: 100%;
  height: auto;
  border: 1px solid #ddd;
  border-radius: 15px;
  margin: 3rem 0;
  box-shadow: 5px 5px 2px #89a8b292 ;
}


.buttons-show{
  display: flex;
  justify-content: space-around;
  align-items: center;
  margin: 30px 0;
}
button{
  width: 120px;
}
.btn{
color: #497174;
border: 1px solid #497174;
padding: 5px 30px;
border-radius: 20px;
}
</style>
