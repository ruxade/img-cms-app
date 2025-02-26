<template>
  <div class="image-upload">
    <h1>Upload Image</h1>
    <form @submit.prevent="uploadImage">
      <div class="file-button">
        <input id="file-upload-btn" type="file" @change="handleFileChange" accept="image/*" required hidden />
        <label for="file-upload-btn" class="custom-file-button">Choose File</label>
        <span v-if="fileName">{{ fileName }}</span>
      </div>

      <input type="text" v-model="caption" placeholder="Caption" required />

      <!-- New select for visibility -->
      <select v-model="visibility" required>
        <option disabled value="">Select visibility</option>
        <option value="public">Public</option>
        <option value="private">Private</option>
      </select>

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
      visibility: '',
      message: '',
      error: ''
    }
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0]
      this.fileName = this.file ? this.file.name : '';
    },
    async uploadImage() {
      if (!this.file) {
        this.error = 'Please select an image file and choose visibility.';
        return;
      }

      const formData = new FormData()
      formData.append('image', this.file)
      formData.append('caption', this.caption)
      formData.append('visibility', this.visibility);

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
        this.visibility = '';
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
  /* min-width: 350px; */
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
  background-color: #497174;
  color: #fff;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  margin: 8px 0;
}

select {
  /* display: block; */
  padding: 0.5rem;
  margin: 0.5rem 0;
  color: #497174;
  background-color: #fff;
  border-radius: 4px;
  font-weight: 800;
}



</style>
