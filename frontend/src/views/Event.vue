
<script setup>
import EventCard from "../components/EventCard.vue";

</script>
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
<template>
  <div class="container-fluid">
 
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

  <div style="margin-top: 20px;margin-left: 20px;" class="box">
        <div class="box-title">Event Carousel Album</div>
        <div class="box-content">
            Fill up the form above to add new event carousel.
        </div>
    </div>
    <div style="margin-bottom:700px;margin-top: 10px;" class="row">
      <div class="col-md-7">
        <EventCard />
      </div>
   
    </div>
  </div>






  
</template>

<style>

.box {
    width: 1000px;
    border: 2px solid #aa9758;
    border-radius: 8px;
    background-color: #ffffff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.box-title {
  background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));
    color: #ffffff;
    padding: 10px;
    font-size: 18px;
    text-align: center;
}

.box-content {
    padding: 15px;
    color: #333;
    font-size: 16px;
    text-align: center;
}



.roundedbut{

background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));
                 
                 color: white;
                 padding: 10px 20px;
                 border: none;
                 border-radius: 20px;
                 font-size: 16px;
                 cursor: pointer;
                 box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                 transition: all 0.2s ease-in-out;
                 margin-top: 20px;
                 margin-left: 25px;



}

/* Form styles */
.form-container {
  padding: 20px;
  border: 2px solid #4caf50;
  border-radius: 8px;
  background-color: #ffffff;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}

input[type="text"] {
  width: 100%;
  padding: 8px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  background-color: #4caf50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}

/* Modal styles */
.modal {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
}

.close-button {
  color: red;
  font-size: 24px;
  cursor: pointer;
}


</style>



