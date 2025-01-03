<template>
    <div>
      <form @submit.prevent="uploadFile">
        <input type="file" @change="handleFileUpload" accept="image/*" />
        
        <button type="submit">Upload</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        file: null,
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
        formData.append('file', this.file);
        formData.append('file', this.file);
        formData.append('file', this.file);
  
        try {
          const response = await axios.post('http://127.0.0.1:8000/api/upload-image', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });
          console.log('File uploaded successfully:', response.data);
        } catch (error) {
          console.error('Error uploading file:', error.response?.data || error.message);
        }
      },
    },
  };
  </script>
  