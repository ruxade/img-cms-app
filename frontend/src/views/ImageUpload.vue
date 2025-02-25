<template>
  <div class="image-upload">
    <h1>Upload Image</h1>
    <form @submit.prevent="uploadImage">
      <input id="file-upload-btn" type="file" @change="handleFileChange" accept="image/*" required />
      <input type="text" v-model="caption" placeholder="Caption" required/>
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
  min-width: 350px;
  width: 80%;
  min-height: 300px;
  display: flex;
  justify-content: space-around;
  flex-direction: column;
    align-items: center;

  margin: 5rem auto;
  padding: 1rem;
  border: 1px solid #cecece;
  border-radius: 8px;
  background-color: #E5E1DA;
  box-shadow: 5px 5px 2px #89a8b279;
}
.custom-file-button {
  display: inline-block;
  background-color: #3490dc; 
  color: #fff;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}
</style>
