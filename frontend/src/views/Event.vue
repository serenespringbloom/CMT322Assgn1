
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
          const response = await axios.post(`${import.meta.env.VITE_API_URL}/api/upload-image`, formData, {
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
  <div class="container mx-auto px-4 py-6 flex-col">
    <form @submit.prevent="uploadFile" class="space-y-6 bg-white shadow rounded-lg p-6">
      <div>
        <label class="block font-medium text-gray-700">Headline:</label>
        <input
          v-model="headline"
          type="text"
          placeholder="Enter headline"
          required
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Details:</label>
        <textarea
          v-model="details"
          placeholder="Enter details"
          required
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
        ></textarea>
      </div>

      <div>
        <label class="block font-medium text-gray-700">Tags (comma-separated):</label>
        <input
          v-model="tags"
          type="text"
          placeholder="e.g., nature, travel, sunset"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Image:</label>
        <input
          type="file"
          @change="handleFileUpload"
          accept="image/*"
          required
          class="mt-1 block w-full text-sm text-gray-500 border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-green-500 text-white py-2 px-4 rounded-md shadow hover:bg-green-600"
      >
        Upload
      </button>
    </form>

    <div v-if="uploadedImageUrl" class="mt-6 text-center">
      <h3 class="text-lg font-medium text-gray-700">Uploaded Image:</h3>
      <img
        :src="uploadedImageUrl"
        alt="Uploaded Image"
        class="max-w-xs mx-auto mt-4 rounded-lg shadow"
      />
    </div>

    <div class="mt-10 border border-yellow-500 rounded-lg bg-white shadow p-6">
      <div class="text-lg font-medium bg-gradient-to-r from-yellow-600 to-yellow-300 text-white p-3 rounded-t-md text-center">
        Event Carousel Album
      </div>
      <div class="p-4 text-gray-700 text-center">
        Fill up the form above to add a new event carousel.
      </div>
    </div>

    <div class="mt-10 flex justify-start">
      <div class="w-full lg:w-7/12">
        <EventCard />
      </div>
    </div>
  </div>
</template>

<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>



