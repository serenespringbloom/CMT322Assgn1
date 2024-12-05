<template>
  <main class="feedback-page">
    <!-- Header Section -->
    <section class="header">
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
  </div>
  <!-- Form -->
  <form @submit.prevent="handleSubmit">
    <div class="form-group">
      <!-- Ticket ID -->
      <div class="form-item">
        <label for="ticket-id">Ticket ID</label>
        <input
          id="ticket-id"
          type="text"
          v-model="ticketId"
          placeholder="Enter your Ticket ID"
        />
      </div>

      <!-- Full Name -->
      <div class="form-item">
        <label for="full-name">Full Name</label>
        <input
          id="full-name"
          type="text"
          v-model="fullName"
          placeholder="Enter your Full Name"
        />
      </div>

      <!-- Programme -->
      <div class="form-item">
        <label for="programme">Programme</label>
        <select id="programme" v-model="programme">
          <option disabled selected>Choose Programme</option>
          <option>Programme A</option>
          <option>Programme B</option>
        </select>
      </div>
    </div>

    <!-- Feedback Text -->
    <div class="form-item">
      <label for="feedback-text">Feedback</label>
      <textarea
        id="feedback-text"
        v-model="feedbackText"
        placeholder="Write your feedback here!"
      ></textarea>
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
    v-for="(item, index) in feedbackData"
    :key="index"
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
import { ref, onMounted } from "vue";
import Papa from "papaparse";

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
  fetch("/src/assets/feedback.csv")
    .then((response) => response.text())
    .then((csvText) => {
      Papa.parse(csvText, {
        header: true,
        skipEmptyLines: true,
        complete: (results) => {
          feedbackData.value = results.data.map((item) => {
            if (item.image === "https://via.placeholder.com/80") {
              item.image = "/src/assets/images/avatar.jpg"; // Default avatar image
            }
            return item;
          });
          activeItem.value = feedbackData.value[0]; // Default first item
        },
      });
    })
    .catch((error) => console.error("Error loading CSV:", error));
});

// Handle form submission
const handleSubmit = () => {
  // Validation
  if (!ticketId.value || !fullName.value || !feedbackText.value || rating.value === 0) {
    alert("Please fill in all fields and select a rating.");
    return;
  }

  // Create new feedback entry
  const newFeedback = {
    "Ticket ID": ticketId.value,
    name: fullName.value,
    programme: programme.value || "N/A", // Default to "N/A" if not selected
    text: feedbackText.value,
    image: "/src/assets/images/avatar.jpg", // Default avatar
    rating: rating.value,
  };

  // Add to feedback list
  feedbackData.value.push(newFeedback);

  // Reset form fields
  ticketId.value = "";
  fullName.value = "";
  programme.value = "";
  feedbackText.value = "";
  rating.value = 0;
  hover.value = 0;

  alert("Thank you for your feedback!");
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
</script>

<style scoped>
.feedback-page {
  font-family: "Plus Jakarta Sans", serif;
  padding: 2rem;
  background-color: #ffc8dd;
  color: #333;
}

/* Header Section */
.header {
  text-align: center;
  letter-spacing: .9rem;
  font-family: "Plus Jakarta Sans", serif;
  margin-top: 1rem;
}

.header h1 {
  font-size: 2.3rem;
  letter-spacing: 2rem;
  margin-bottom: 1.5rem;
  color: #554149;
  text-transform: uppercase;
}

.header h2 {
  font-size: 1.5rem;
  font-weight: lighter;
  letter-spacing: 0.1rem;
  margin: 20px;
  font-weight: bold;
}
/* General Styles */
.feedback-page {
  font-family: "Arial", sans-serif;
  padding: 2rem;
  background-color: #ffc8dd;
  color: #333;
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
  width: 900px;
  min-height: 200px; /* Ensure consistent height */
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
  background: linear-gradient(135deg, #fff1f5, #ffe5ed);
  padding: 1.5rem;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  font-family: "Plus Jakarta Sans", sans-serif;
  animation: fadeIn 0.4s ease-out;
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

/* Submit Button */
.btn-submit {
  background: linear-gradient(135deg, #a48e69 -50%, #dcc39c 100%);
  color: #554149;
  padding: 0.75rem 1.5rem;
  margin-top: 1rem;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  transition: all 0.3s ease;
}

.btn-submit:hover {
  background: linear-gradient(135deg, #dcc39c, #a48e69);
  transform: translateY(-3px);
}

/* Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive Design */
@media (max-width: 768px) {
  .form-group {
    flex-direction: column;
    gap: 1rem;
  }

  .form-item {
    min-width: 100%;
  }
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
  width: 500px;
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


