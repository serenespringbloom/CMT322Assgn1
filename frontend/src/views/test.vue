<template>
     <div>
    <form @submit.prevent="uploadFile">
      <div>
        <label>Headline:</label>
        <input v-model="headline" type="text" placeholder="Enter headline" required />
      </div>
      
      <div>
        <label>Details:</label>
        <textarea v-model="details" placeholder="Enter details" required></textarea>
      </div>

      <div>
        <label>Tags (comma-separated):</label>
        <input v-model="tags" type="text" placeholder="e.g., nature, travel, sunset" />
      </div>

      <div>
        <label>Image:</label>
        <input type="file" @change="handleFileUpload" accept="image/*" required />
      </div>

      <button type="submit">Upload</button>
    </form>

    <div v-if="uploadedImageUrl">
      <h3>Uploaded Image:</h3>
      <img :src="uploadedImageUrl" alt="Uploaded Image" style="max-width: 300px; margin-top: 10px;" />
    </div>
  </div>

  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        headline: '',
        details: '',
        tags: '',
        file: null,
        uploadedImageUrl: null,
      };
    },
    methods: {
      handleFileUpload(event) {
        this.file = event.target.files[0]; // Capture the selected file
      },
      async uploadFile() {
        if (!this.file) {
          alert('Please select a file');
          return;
        }
  
        const formData = new FormData();
        formData.append('file', this.file);
        formData.append('headline', this.headline);
        formData.append('details', this.details);
        formData.append('tags', this.tags);
  
        try {
          const response = await axios.post('http://127.0.0.1:8000/api/upload-image', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });
  
          console.log('File uploaded successfully:', response.data);
          this.uploadedImageUrl = response.data.url;
        } catch (error) {
          console.error('Error uploading file:', error.response?.data || error.message);
        }
      },
    },
  };
  </script>

<style>
form div {
  margin-bottom: 10px;
}

label {
  font-weight: bold;
}

input, textarea {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
}
</style>