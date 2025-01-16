<template>
  <div class="min-h-screen bg-gradient-to-b from-[#f4d0d6] to-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-[#554149] mb-2">FEEDBACK</h1>
        <h2 class="text-2xl text-[#a48e69]">OUR PARTICIPANTS SAY</h2>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Section: Testimonial and Form -->
        <div class="lg:col-span-2 space-y-8">
          <!-- Main Testimonial -->
          <transition name="fade">
            <div v-if="activeItem" 
                 class="bg-white rounded-2xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl">
              <div class="flex items-start space-x-6">
                <div class="w-20 h-20 rounded-full border-4 border-[#DCC39C] overflow-hidden bg-gradient-to-br" 
                     :style="{ background: getRandomGradient(activeItem.name) }">
                  <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-white/80">
                    <text x="50%" y="50%" 
                          dominant-baseline="middle" 
                          text-anchor="middle"
                          class="text-2xl font-bold fill-current">
                      {{ getInitials(activeItem.name) }}
                    </text>
                  </svg>
                </div>
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-[#554149] mb-1">{{ activeItem.name }}</h3>
                  <p class="text-[#a48e69] mb-2">{{ activeItem.programme }}</p>
                  <div class="flex items-center mb-3">
                    <span v-for="star in 5" 
                          :key="star" 
                          :class="{ 'text-yellow-400': star <= activeItem.rating, 'text-gray-300': star > activeItem.rating }"
                          class="text-2xl">★</span>
                  </div>
                  <p class="text-gray-600 italic">{{ activeItem.feedback_text }}</p>
                </div>
              </div>
            </div>
          </transition>

          <!-- Feedback Form -->
          <div class="bg-white rounded-2xl shadow-lg p-8 ">
            <h3 class="text-2xl font-bold text-[#554149] mb-6 text-center">Give Your Feedback!</h3>
            
            <!-- Rating Stars -->
            <div class="flex justify-center space-x-2 mb-8">
              <span v-for="star in 5" 
                    :key="star"
                    @click="selectRating(star)"
                    @mouseover="hoverRating(star)"
                    @mouseleave="resetHover"
                    :class="{ 'text-yellow-400': star <= (hover || rating), 'text-gray-300': star > (hover || rating) }"
                    class="text-3xl cursor-pointer transition-colors duration-200 hover:scale-110">
                ★
              </span>
            </div>

            <!-- Form -->
            <form @submit.prevent="handleSubmit" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label for="ticket-id" class="block text-sm font-medium text-gray-700">Ticket ID</label>
                  <input id="ticket-id" 
                         type="text" 
                         v-model="ticketId"
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#DCC39C] focus:border-transparent" 
                         placeholder="Enter your Ticket ID" />
                </div>

                <div class="space-y-2">
                  <label for="full-name" class="block text-sm font-medium text-gray-700">Full Name</label>
                  <input id="full-name" 
                         type="text" 
                         v-model="fullName"
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#DCC39C] focus:border-transparent" 
                         placeholder="Enter your Full Name" />
                </div>

                <div class="space-y-2">
                  <label for="programme" class="block text-sm font-medium text-gray-700">Programme</label>
                  <select id="programme" 
                          v-model="programme"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#DCC39C] focus:border-transparent">
                    <option value="" disabled selected>Choose Programme</option>
                    
                    <!-- Arts & Humanities -->
                    <optgroup label="Arts & Humanities">
                      <option>Bachelor of Arts (BA)</option>
                      <option>Bachelor of Fine Arts (BFA)</option>
                      <option>Bachelor of Music (BMus)</option>
                      <option>Bachelor of Design (BDes)</option>
                    </optgroup>

                    <!-- Science & Technology -->
                    <optgroup label="Science & Technology">
                      <option>Bachelor of Science (BSc)</option>
                      <option>Bachelor of Computer Science (BCS)</option>
                      <option>Bachelor of Information Technology (BIT)</option>
                      <option>Bachelor of Engineering (BEng)</option>
                    </optgroup>

                    <!-- Business & Management -->
                    <optgroup label="Business & Management">
                      <option>Bachelor of Business Administration (BBA)</option>
                      <option>Bachelor of Commerce (BCom)</option>
                      <option>Bachelor of Economics (BEcon)</option>
                      <option>Bachelor of Accountancy (BAcc)</option>
                    </optgroup>

                    <!-- Social Sciences -->
                    <optgroup label="Social Sciences">
                      <option>Bachelor of Social Science (BSocSc)</option>
                      <option>Bachelor of Psychology (BPsych)</option>
                      <option>Bachelor of Education (BEd)</option>
                      <option>Bachelor of Communication (BCom)</option>
                    </optgroup>

                    <!-- Health Sciences -->
                    <optgroup label="Health Sciences">
                      <option>Bachelor of Medicine (MBBS)</option>
                      <option>Bachelor of Pharmacy (BPharm)</option>
                      <option>Bachelor of Nursing (BNurs)</option>
                      <option>Bachelor of Dentistry (BDS)</option>
                    </optgroup>

                    <!-- Law -->
                    <optgroup label="Law">
                      <option>Bachelor of Laws (LLB)</option>
                      <option>Bachelor of Jurisprudence (BJuris)</option>
                    </optgroup>

                    <!-- Public & Others -->
                    <optgroup label="Public & Others">
                      <option>Public Participant</option>
                      <option>Staff/Faculty Member</option>
                      <option>Event Organizer</option>
                      <option>Guest Speaker</option>
                      <option>Others</option>
                    </optgroup>
                  </select>
                </div>
              </div>

              <div class="space-y-2">
                <label for="feedback-text" class="block text-sm font-medium text-gray-700">Feedback</label>
                <textarea id="feedback-text" 
                          v-model="feedbackText"
                          rows="4"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#DCC39C] focus:border-transparent"
                          placeholder="Write your feedback here!"></textarea>
              </div>

              <button type="submit" 
                      class="w-full bg-gradient-to-r from-[#DCC39C] to-[#a48e69] text-white font-bold py-3 px-6 rounded-lg 
                             transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                SUBMIT
              </button>
            </form>
          </div>
        </div>

        <!-- Right Section: Participant Names -->
        <div class="lg:col-span-1">
          <div class="grid gap-4">
            <div v-for="(item, index) in feedbackData" 
                 :key="index"
                 @click="setActiveItem(item)"
                 class="bg-white rounded-xl p-4 shadow cursor-pointer transform transition-all duration-300 
                        hover:shadow-lg hover:scale-105 hover:bg-[#f8f4f0]">
              <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full border-2 border-[#DCC39C] overflow-hidden bg-gradient-to-br" 
                     :style="{ background: getRandomGradient(item.name) }">
                  <svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" class="w-full h-full text-white/80">
                    <text x="50%" y="50%" 
                          dominant-baseline="middle" 
                          text-anchor="middle"
                          class="text-lg font-bold fill-current">
                      {{ getInitials(item.name) }}
                    </text>
                  </svg>
                </div>
                <div>
                  <p class="font-semibold text-[#554149]">{{ item.name }}</p>
                  <p class="text-sm text-[#a48e69]">{{ item.programme }}</p>
                  <p class="font-semibold text-[#554149]">{{ item.feedback_text }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup >

import { ref, onMounted } from "vue";
import Papa from "papaparse";
import axios from "axios";

// Reactive variables
const feedbackData = ref([]);
const activeItem = ref(null);

// Form fields
const ticketId = ref("");
const fullName = ref("");
const programme = ref("");
const feedbackText = ref("");
const rating = ref(0);
const hover = ref(0);
const setActiveItem = (item) => {
  activeItem.value = item;
};

// On component mount, load feedback data
onMounted(() => {
  fetchFeedbacks();
});

const fetchFeedbacks = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/feedback`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    if (data.success) {
      feedbackData.value = data.data;
      if (feedbackData.value.length > 0) {
        activeItem.value = feedbackData.value[0];
      }
    } else {
      throw new Error(data.message || 'Failed to fetch feedbacks');
    }
  } catch (error) {
    console.error('Error fetching feedbacks:', error);
  }
};

// Handle form submission
const handleSubmit = async (event) => {
  event.preventDefault();
  
  if (!rating.value) {
    alert('Please select a rating');
    return;
  }

  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/feedback`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        ticket_id: ticketId.value,
        name: fullName.value,
        programme: programme.value,
        feedback_text: feedbackText.value,
        rating: rating.value
      })
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();
    
    if (result.success) {
      alert('Feedback submitted successfully!');
      // Reset form
      ticketId.value = '';
      fullName.value = '';
      programme.value = '';
      feedbackText.value = '';
      rating.value = 0;
      hover.value = 0;
      // Refresh feedbacks
      await fetchFeedbacks();
    } else {
      throw new Error(result.message || 'Failed to submit feedback');
    }
  } catch (error) {
    console.error('Error submitting feedback:', error);
    alert(error.message || 'Failed to submit feedback');
  }
};

// Star rating handlers
const selectRating = (star) => {
  rating.value = star;
};

const hoverRating = (star) => {
  hover.value = star;
};

const resetHover = () => {
  hover.value = 0;
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-MY', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const getInitials = (name) => {
  if (!name) return '??';
  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2);
};

const getRandomGradient = (seed) => {
  // List of gradient pairs
  const gradients = [
    ['#FF6B6B', '#4ECDC4'],
    ['#A8E6CF', '#DCEDC1'],
    ['#FFD93D', '#FF6B6B'],
    ['#95E1D3', '#EAFFD0'],
    ['#DCC39C', '#a48e69'],
    ['#f4d0d6', '#FF6B6B'],
    ['#6B48FF', '#1EE3CF'],
    ['#FFB6B9', '#FAE3D9'],
    ['#BBDED6', '#61C0BF'],
    ['#FFB6B9', '#FAE3D9']
  ];

  // Use the name string to generate a consistent index
  const index = seed.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0) % gradients.length;
  const [color1, color2] = gradients[index];
  
  return `linear-gradient(135deg, ${color1}, ${color2})`;
};
</script>

<style>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>


