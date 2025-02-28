<template>
  <div class="image-upload">

    <h1>Upload Image</h1>
    <form @submit.prevent="uploadImage">
      <div class="upload-btn">

      <div class="file-button">
        <input id="file-upload-btn" type="file" @change="handleFileChange" accept="image/*" required hidden />
        <label for="file-upload-btn" class="custom-file-button"><i class="fa-solid fa-upload"> </i> Choose File</label>
        <span v-if="fileName">{{ fileName }}</span>
      </div>

      <!-- Camera Capture Button -->
      <button type="button" class="custom-file-button" @click="openCamera"><i class="fa-solid fa-camera"> </i> Take a Photo </button>
    </div>

      <!-- Video Preview - Camera -->
      <div v-if="showCamera">
        <video ref="video" autoplay></video>
        <button type="button" @click="captureImage">Capture</button>
      </div>

      <!--  Preview -->
      <img v-if="previewImage" :src="previewImage" class="preview" />

      <input
          type="text"
          v-model="caption"
          placeholder="Caption"
          maxlength="100"
          @input="validateCaption"
          required
        />
        <p class="char-counter">{{ caption.length }}/50</p>

      <select v-model="visibility" required>
        <option disabled value="">Select visibility</option>
        <option value="public">Public</option>
        <option value="private">Private</option>
      </select>

      <button type="submit">UPLOAD</button>
    </form>

    <p v-if="message">{{ message }}</p>
    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script>
// import axios from "axios";
import { saveImages, getOfflineImages } from '../utils/db'
import Swal from 'sweetalert2'

export default {
  name: "ImageUpload",
  data() {
    return {
      file: null,
      fileName: "",
      caption: "",
      visibility: "",
      message: "",
      error: "",
      showCamera: false,
      previewImage: null,
      stream: null,
      captionMaxLength: 50,
    };
  },
  methods: {
    handleFileChange(event) {
      this.file = event.target.files[0];
      this.fileName = this.file ? this.file.name : "";
      this.previewImage = URL.createObjectURL(this.file);
    },
    async uploadImage() {
      if (!this.file) {
        this.error = "Please select or capture an image.";
        return;
      }
      if (this.caption.length > this.captionMaxLength) {
        this.error = "Caption is too long.";
        return;
      }

      const formData = new FormData();
      formData.append("image", this.file);
      formData.append("caption", this.caption);
      formData.append("visibility", this.visibility);


      console.log("Uploading image with data:", {
        file: this.file,
        caption: this.caption,
        visibility: this.visibility
      });

      try {
        const response = await this.$axios.post("/images", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        });

        Swal.fire("Added!", "Image has been uploaded.", "success")
          .then(() => {
            this.$router.push({ name: "Images" });
          });
      } catch (err) {
        console.error("Upload failed:", err.response?.data);
        this.error = err.response?.data?.message || "Upload failed.";
      }
    },
    openCamera() {
      this.showCamera = true;
      navigator.mediaDevices
        .getUserMedia({ video: true })
        .then((stream) => {
          this.stream = stream;
          this.$refs.video.srcObject = stream;
        })
        .catch((err) => {
          console.error("Error accessing camera", err);
          this.error = "Unable to access camera.";
        });
    },
    captureImage() {
      const video = this.$refs.video;
      const canvas = document.createElement("canvas");
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext("2d");
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      canvas.toBlob((blob) => {
        this.file = new File([blob], "captured-image.jpg", { type: "image/jpeg" });
        this.previewImage = URL.createObjectURL(this.file);
        this.fileName = "captured-image.jpg";
        this.showCamera = false;
        this.stopCamera();
      }, "image/jpeg");
    },
    stopCamera() {
      if (this.stream) {
        this.stream.getTracks().forEach((track) => track.stop());
      }
    },
    resetForm() {
      this.file = null;
      this.fileName = "";
      this.caption = "";
      this.visibility = "";
      this.previewImage = null;
      this.showCamera = false;
      this.stopCamera();
    },
  },
};
</script>

<style scoped>
.image-upload {
  width: 80%;
  min-height: 300px;
  display: flex;
  justify-content: space-around;
  flex-direction: column;
  align-items: center;
  margin: 5rem auto;
  padding: 4rem;
  border: 1px solid #cecece;
  border-radius: 8px;
  background-color: #E5E1DA;
  box-shadow: 5px 5px 2px #89a8b279;
}

.custom-file-button {
  display: inline-block;
  background-color: #497174;
  color: #fff;
  width: 100%;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  text-align: center;
  font-weight: 200;
  font-family: Arial, Helvetica, sans-serif;
}

.upload-btn{
  display: block;

}
select, input {
  width: 100%;
  padding: 8px;
  margin: 20px 0;
}
.preview {
  max-width: 100%;
  height: auto;
  margin: 10px 0;
  border-radius: 4px;
  border: 2px solid #497174;
}
form{
  width: 100%;
}
video {
  width: 100%;
  max-width: 400px;
  border-radius: 8px;
  border: 2px solid #497174;
}
</style>
