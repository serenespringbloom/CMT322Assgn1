<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios'; // Assuming you're using Axios for API requests

// Reactive data for feedback items
const feedbackList = ref([]);

// Fetch feedback from the database
const fetchFeedback = async () => {
  try {
    const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/admin/feedback`); // Replace with your actual API endpoint
    feedbackList.value = response.data; // Assume the response is an array of feedback objects
  } catch (error) {
    console.error('Error fetching feedback:', error);
  }
};

// Fetch feedback on component mount
onMounted(fetchFeedback);
</script>

<template>
  <div class="py-1 container-fluid">
    <div
      style="background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1)); margin-top: 5px;"
    >
      <div
        class="text-white p-4 text-xl font-bold flex items-center justify-center"
        style="background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));"
      >
        <h2>Feedback</h2>
      </div>
    </div>

    <div class="container mx-auto p-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <!-- Loop through feedbackList -->
        <div
          v-for="(feedback, index) in feedbackList"
          :key="index"
          class="bg-white border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
        >
          <img
            src="../assets/images/profile/profileman.png"
            alt="Card Image"
            class="w-full h-80 w-30 object-cover"
          />
          <div class="p-4">
            <h2 class="text-xl font-bold mb-2">{{ feedback.name }}</h2>
            <p class="text-gray-600">{{ feedback.program }}</p>
            <h2 class="text-xl font-bold mb-2">Comment</h2>
            <!-- Two-way binding for comment -->
            <p  class="w-full border rounded-md p-2 mb-2">{{ feedback.feedback_text }}</p>
              
            
            <div class="row">
              <h2 class="text-xl font-bold mt-2">Rating</h2>
              <div>
    <!-- Conditional Rendering for Image Source -->
    <img 
      v-if="feedback.rating === 5" 
      src="../assets/images/rating/5star.png" 
      alt="5 Star Rating" 
      style="max-width: 200px;"
    />
    <img 
      v-else-if="feedback.rating === 4" 
      src="../assets/images/rating/4star.png" 
      alt="4 Star Rating"
      style="max-width: 200px;" 
    />
    <img 
      v-else 
      src="../assets/images/rating/default.png" 
      alt="Default Rating" 
    />
  </div>
            </div>
            <h2 class="text-xl font-bold mb-2">Ticket ID</h2>
            <h2 class="text-gray-600 mb-2">{{ feedback.ticket_id }}</h2>
          </div>
          <div class="p-4">
            <button
              class="w-full bg-pink-500 text-white py-2 rounded-md hover:bg-blue-600"
              @click="viewDetails(feedback)"
            >
              Learn More
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>