<template>
  <div class="image-upload">
    <h1>Upload Image</h1>
    <form @submit.prevent="uploadImage">
      <input type="file" @change="handleFileChange" accept="image/*" required />
      <input type="text" v-model="caption" placeholder="Caption (optional)" />
      <button type="submit">Upload</button>
    </form>
    <p v-if="message">{{ message }}</p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  name: 'ImageUpload',
  data() {
    return {
      file: null,
      caption: '',
      message: '',
      error: ''
    }
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0]
    },
    async uploadImage() {
      if (!this.file) {
        this.error = 'Please select an image file.'
        return
      }

      const formData = new FormData()
      formData.append('image', this.file)
      formData.append('caption', this.caption)

      try {
        // Get the token from localStorage and set the header
        const token = localStorage.getItem('access_token')
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`

        const response = await axios.post('http://localhost:8000/api/images', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
            'Accept': 'application/json'
          }
        })

        this.message = 'Image uploaded successfully!'
        this.error = ''
        //  clear the form:
        this.file = null
        this.caption = ''
      } catch (err) {
        this.error = err.response?.data?.message || 'Upload failed.'
        this.message = ''
      }
    }
  }
}
</script>

<style scoped>
.image-upload {
  max-width: 500px;
  margin: 2rem auto;
  padding: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}
.error {
  color: red;
}
</style>
