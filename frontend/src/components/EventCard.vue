<template>
  <div>
    <h2>Uploaded Images</h2>
    <div class="image-gallery">
      <div v-for="image in images" :key="image.id" class="card">
        <img :src="image.url" alt="Uploaded Image" />
        <div class="card-body">
          <h4>{{ image.headline }}</h4>
          <p>{{ image.details }}</p>
          <p><strong>Tags:</strong> {{ image.tags }}</p>
          <button @click="editImage(image)">Update</button>
          <button @click="deleteImage(image.id)">Delete</button>
        </div>
      </div>
    </div>

    <!-- Update Modal -->
    <div v-if="showUpdateModal" class="modal">
      <div class="modal-content">
        <h3>Update Image Details</h3>
        <input v-model="updateForm.headline" placeholder="Headline" />
        <textarea v-model="updateForm.details" placeholder="Details"></textarea>
        <input v-model="updateForm.tags" placeholder="Tags" />
        <button @click="submitUpdate">Save</button>
        <button @click="showUpdateModal = false">Cancel</button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      images: [],
      showUpdateModal: false,
      updateForm: {
        id: null,
        headline: '',
        details: '',
        tags: ''
      }
    };
  },
  methods: {
    async fetchImages() {
      try {
        const response = await axios.get('http://127.0.0.1:8003/api/images');
        this.images = response.data.map(image => ({
          ...image,
          url: `http://127.0.0.1:8003/images/${image.filename}`
        }));
      } catch (error) {
        console.error('Error fetching images:', error.response?.data || error.message);
      }
    },
    async deleteImage(id) {
      if (!confirm('Are you sure you want to delete this image?')) return;
      try {
        await axios.delete(`http://127.0.0.1:8003/api/images/${id}`);
        this.images = this.images.filter(image => image.id !== id);
      } catch (error) {
        console.error('Error deleting image:', error.response?.data || error.message);
      }
    },
    editImage(image) {
      this.updateForm = { ...image };
      this.showUpdateModal = true;
    },
    async submitUpdate() {
      try {
        await axios.put(`http://127.0.0.1:8003/api/images/${this.updateForm.id}`, {
          headline: this.updateForm.headline,
          details: this.updateForm.details,
          tags: this.updateForm.tags,
        });
        this.images = this.images.map(image =>
          image.id === this.updateForm.id ? { ...image, ...this.updateForm } : image
        );
        this.showUpdateModal = false;
      } catch (error) {
        console.error('Error updating image:', error.response?.data || error.message);
      }
    }
  },
  mounted() {
    this.fetchImages();
  }
};
</script>

<style>
.image-gallery {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 16px;
}

.card {
  border: 1px solid #ccc;
  border-radius: 8px;
  overflow: hidden;
  text-align: center;
  padding: 10px;
}

.card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.card-body {
  padding: 10px;
}

.card-body button {
  margin: 5px;
  padding: 6px 12px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.card-body button:nth-child(1) {
  background-color: #4caf50;
  color: white;
}

.card-body button:nth-child(2) {
  background-color: #f44336;
  color: white;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 8px;
  width: 400px;
}

.modal-content input,
.modal-content textarea {
  width: 100%;
  margin: 8px 0;
  padding: 8px;
}
</style>
