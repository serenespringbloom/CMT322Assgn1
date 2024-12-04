<template>
  <main class="feedback-page">
    <!-- Header Section -->
    <section class="feedback-header">
      <h1>FEEDBACK</h1>
      <h2>OUR PARTICIPANT SAY</h2>
    </section>

    <!-- Main Content Section -->
    <section class="feedback-content">
      <!-- Left Section: Testimonial and Form -->
      <div class="left-content">
        <!-- Main Testimonial -->
        <transition name="fade">
  <div class="testimonial-card" v-if="activeItem">
    <img :src="activeItem.image" alt="Profile" class="profile-image" />
    <div class="testimonial-content">
      <h3>{{ activeItem.name }}</h3>

      <p class="subtitle">{{ activeItem.programme }}</p>
      <div class="testimonial-stars">
        <span
          v-for="star in 5"
          :key="star"
          :class="{ filled: star <= activeItem.rating }"
        >
          ★
        </span>
      </div>
      <p class="testimonial-text">{{ activeItem.text }}</p>

    </div>
  </div>
</transition>

        <!-- Feedback Form -->
        <section class="feedback-form">
          <h3>Give Your Feedback!</h3>
          <!-- Interactive Stars -->
          <div class="stars">
  <span
    v-for="star in 5"
    :key="star"
    @click="selectRating(star)"
    @mouseover="hoverRating(star)"
    @mouseleave="resetHover"
    :class="{ filled: star <= (hover || rating) }"
  >
    ★
  </span>
</div>   <form @submit.prevent="handleSubmit">
  <div class="form-group">
  <!-- Ticket ID -->
  <div class="form-item">
    <label for="ticket-id">Ticket ID</label>
    <input id="ticket-id" type="text" placeholder="Enter your Ticket ID" />
  </div>

  <!-- Full Name -->
  <div class="form-item">
    <label for="full-name">Full Name</label>
    <input id="full-name" type="text" placeholder="Enter your Full Name" />
  </div>

  <!-- Programme -->
  <div class="form-item">
    <label for="programme">Programme</label>
    <select id="programme">
      <option disabled selected>Choose Programme</option>
      <option>Programme A</option>
      <option>Programme B</option>
    </select>
  </div>
</div>

<!-- Feedback Text -->
<div class="form-item">
  <label for="feedback-text">Feedback</label>
  <textarea id="feedback-text" placeholder="Write your feedback here!"></textarea>
</div>

<!-- Submit Button -->
<button type="submit" class="btn-submit">SUBMIT</button>
          </form>
        </section>
      </div>

      <!-- Right Section: Participant Names -->
      <div class="right-content">
        <div
          class="participant-card"
          v-for="item in feedbackData"
          :key="item.name"
          @click="setActiveItem(item)"
        >
          <img :src="item.image" alt="Profile" />
          <p>{{ item.name }}</p>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import Papa from 'papaparse';
import { ref, onMounted } from 'vue';

// Reactive references
const feedbackData = ref([]);
const activeItem = ref(null);

// Form fields
const ticketId = ref('');
const fullName = ref('');
const programme = ref('');
const feedbackText = ref('');
const rating = ref(0); // Stores the selected rating
const hover = ref(0); // Tracks hover state for stars

onMounted(() => {
  // Load and parse the CSV file
  fetch("/src/assets/feedback.csv")
    .then((response) => response.text())
    .then((csvText) => {
      Papa.parse(csvText, {
        header: true,
        skipEmptyLines: true,
        complete: (results) => {
          feedbackData.value = results.data.map((item) => {
            // Replace placeholder with local avatar path
            if (item.image === "https://via.placeholder.com/80") {
              item.image = "/src/assets/images/avatar.jpg"; // Use your local avatar image
            }
            return item;
          });
          activeItem.value = feedbackData.value[0]; // Set the first item as active by default
        },
      });
    })
    .catch((error) => console.error("Error loading CSV:", error));
});

const setActiveItem = (item) => {
  activeItem.value = item;
};

const handleSubmit = () => {
  // Validate form inputs
  if (!ticketId.value || !fullName.value || !feedbackText.value || rating.value === 0) {
    alert('Please fill in all fields and select a rating');
    return;
  }

  const newFeedback = {
    'Ticket ID': ticketId.value || 'N/A', // Optional ticket ID
    name: fullName.value,
    programme: programme.value || 'N/A', // Optional Programme,
    text: feedbackText.value,
    image: '/src/assets/images/avatar.jpg', // Default placeholder image
    rating: rating.value,
  };

  // Add new feedback to the data array
  feedbackData.value.push(newFeedback);

  // Reset form fields
  ticketId.value = '';
  fullName.value = '';
  programme.value = '';
  feedbackText.value = '';
  rating.value = 0;
  hover.value = 0;

  alert('Thank you for your feedback!');
};

// Functions to handle interactive stars
const selectRating = (star) => {
  rating.value = star;
};

const hoverRating = (star) => {
  hover.value = star;
};

const resetHover = () => {
  hover.value = 0;
};

// Optional: Download CSV functionality
const downloadCSV = () => {
  // Convert feedback data to CSV
  const csvContent = [
    '"name","programme","text","image","rating"',
    ...feedbackData.value.map(item =>
      `"${item.name}","${item.programme}","${item.text}","${item.rating}","${item.image}"`
    )
  ].join('\n');

  // Create download link
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
  const link = document.createElement('a');
  const url = URL.createObjectURL(blob);
  link.setAttribute('href', url);
  link.setAttribute('download', 'feedback.csv');
  link.style.visibility = 'hidden';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};
</script>

<style scoped>
.feedback-page {
  font-family: "Arial", sans-serif;
  padding: 2rem;
  background-color: #ffc8dd;
  color: #333;
}

/* Header Section */
.feedback-header {
  text-align: center;
  margin-bottom: 2rem;
}

.feedback-header h1 {
  font-size: 2.5rem;
  font-weight: bold;
}

.feedback-header h2 {
  font-size: 1.5rem;
  font-weight: lighter;
  letter-spacing: 0.1rem;
}

/* Main Content Section */
.feedback-testimonial {
  display: flex;
  justify-content: space-between;
  gap: 2rem;
  align-items: flex-start;
}

.testimonial-card {
  flex: 1;
  max-width: 60%;
  background-color: #fff;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  display: flex;
  gap: 1rem;
  align-items: center;
  transition: opacity 0.3s ease-in-out;
}

.participants {
  flex: 0.4;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.participant-card {
  text-align: center;
  cursor: pointer;
  transition: transform 0.3s ease;
  background-color: #fff;
  padding: 1rem;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.participant-card:hover {
  transform: scale(1.05);
}
</style>
<style scoped>
/* General Styles */
.feedback-page {
  font-family: "Arial", sans-serif;
  padding: 2rem;
  background-color: #ffc8dd;
  color: #333;
}

/* Header Section */
.feedback-header {
  text-align: center;
  margin-bottom: 2rem;
}

.feedback-header h1 {
  font-size: 2.5rem;
  font-weight: bold;
}

.feedback-header h2 {
  font-size: 1.5rem;
  font-weight: lighter;
  letter-spacing: 0.1rem;
}

/* Main Content Section */
.feedback-content {
  display: flex;
  justify-content: space-between;
  gap: 2rem;
}

/* Left Section: Testimonial and Form */
.left-content {
  flex: 2;
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.testimonial-card {
  background-color: rgba(85, 65, 73, .08);;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  display: flex;
  gap: 1rem;
  align-items: center;
  max-width: 100%;
  width: 100%;
  min-height: 150px; /* Ensure consistent height */
  max-height: 200px; /* Prevent excessive growth */
  overflow: hidden; /* Hide overflowing content */
  transition: opacity 0.3s ease-in-out, height 0.3s ease-in-out;
}

.testimonial-content {
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex: 1; /* Prevent uneven stretching */
}

.testimonial-text {
  font-size: 1rem;
  color: #555;
  overflow: hidden; /* Prevent overflowing text */
  text-overflow: ellipsis; /* Add ellipsis for long text */
  display: -webkit-box;
  -webkit-line-clamp: 3; /* Limit to 3 lines */
  -webkit-box-orient: vertical;
}

.profile-image {
  width: 80px; /* Set a fixed width */
  height: 80px; /* Set a fixed height */
  border-radius: 50%; /* Keep it circular */
  object-fit: cover; /* Ensure the image scales and crops nicely */
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional styling for a polished look */
  margin-right: 1rem; /* Add spacing to the right of the image */
}

.testimonial-content h3 {
  font-size: 1.2rem;
  margin-bottom: -0.3rem;
}

.testimonial-content .subtitle {
  font-size: 0.8rem;
  color: #777;
  margin-bottom: -1rem;
}

/* Feedback Form */
.feedback-form {
  text-align: center;
  background-color: #fff;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  font-family: "Arial", sans-serif; /* Apply a consistent font */
}

.feedback-form h3 {
  font-size: 1.5rem;
  margin-bottom: -1.6rem;
}

.stars {
  display: flex;
  justify-content: center;
  margin-top: 1rem;
  margin-bottom: -0.5rem;
  font-size: 3rem;
  cursor: pointer;
}

.stars span {
  color: #ccc;
  transition: color 0.3s;
}

.stars span.filled {
  color: #EACA14; /* Highlighted color */
}
.form-group {
  display: flex;
  flex-wrap: wrap; /* Adjust layout for smaller screens */
  gap: 1rem; /* Add spacing between form items */
}

.form-group {
  display: flex;
  flex-wrap: wrap; /* Adjust layout for smaller screens */
  gap: 1rem; /* Add spacing between form items */
}

.form-item {
  display: flex;
  flex-direction: column; /* Stack label and input vertically */
  align-items: flex-start; /* Align content to the left */
  flex: 1; /* Allow items to scale evenly */
}

label {
  font-size: 1rem;
  font-weight: bold;
  margin-top: 0.4rem;
  margin-bottom: 0.2rem; /* Add spacing between label and input */
  color: #333;
  text-align: left; /* Align label text to the left */
}

/* Stars in Main Testimonial */
.testimonial-stars {
  display: flex;
  margin-top: 0.5rem;
}

.testimonial-stars span {
  font-size: 1.5rem;
  color: #fff; /* Default star color */
  transition: color 0.3s;
  text-shadow: 0 0 1px #535252, 0 0 2px #3e3e3e;

}

.testimonial-stars span.filled {
  color: #f3d114; /* Highlighted star color */
  text-shadow: 0 0 1px #535252, 0 0 2px #3e3e3e;
}

input,
select,
textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1rem;
  outline: none;
  font-family: inherit;
}

textarea {
  resize: vertical; /* Allow resizing only vertically */
}

input:focus,
select:focus,
textarea:focus {
  border-color: #ffc8dd; /* Highlight border on focus */
  box-shadow: 0 0 4px rgba(255, 200, 221, 0.5);
}

.btn-submit {
  background-color: #ffc8dd;
  color: #333;
  padding: 0.75rem 1.5rem;
  margin-top: 0.4rem;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-weight: bold;
}

.btn-submit:hover {
  background-color: #ffb3c6;
}

/* Right Section: Participants */
.right-content {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.participant-card {
  text-align: center;
  cursor: pointer;
  transition: transform 0.3s ease;
  background-color: rgba(85, 65, 73, .08);
  padding: 1rem;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.participant-card:hover {
  transform: scale(1.05);
}

.participant-card img {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  margin-bottom: 0.5rem;
}

.participant-card p {
  font-size: 1rem;
  font-weight: bold;
}
</style>
