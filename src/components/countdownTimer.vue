<template>
  <div class="timer-container">
    <vue-countdown :time="time" v-slot="{ days, hours, minutes, seconds }">
      <div class="timer">
    <div class="timer-item">
      <span class="timer-value">{{ days }}</span>
      <span class="timer-label"> days</span>
    </div>
    <div class="divider">:</div>
    <div class="timer-item">
      <span class="timer-value">{{ hours }}</span>
      <span class="timer-label"> hours</span>
    </div>
    <div class="divider">:</div>
    <div class="timer-item">
      <span class="timer-value">{{ minutes }}</span>
      <span class="timer-label"> minutes</span>
    </div>
    <div class="divider">:</div>
    <div class="timer-item">
      <span class="timer-value">{{ seconds }}</span>
      <span class="timer-label"> seconds</span>
    </div>
  </div>
    </vue-countdown>
  </div>
</template>


<script setup lang="ts">
import { ref } from 'vue';
// Calculate the countdown target time
const now = new Date().getTime(); // Convert current date to timestamp
const targetDate = new Date(2025, 5, 1).getTime(); // June 1, 2024 (Months are 0-indexed)
const time = ref(targetDate - now); // Difference in milliseconds
</script>

<style scoped>
.timer-container {
  background: rgba(0, 0, 0, 0.1); /* Background color for the timer */
  padding: 1rem;
  display: inline-block;
  text-align: center;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.timer {
  display: flex; /* Change to flex layout */
  flex-direction: row; /* Set items to display horizontally */
  justify-content: center; /* Center items horizontally */
  align-items: center; /* Align items vertically */
  gap: 1.5rem; /* Add space between timer items */
  font-weight: bold;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Timer Item */
.timer-item {
  display: flex;
  flex-direction: column; /* Stack value and label vertically */
  align-items: center; /* Center align each item */
  justify-content: center;
  width: 80px; /* Consistent width for items */
}

/* Timer Value */
.timer-value {
  font-size: 2.5rem; /* Larger font size for emphasis */
  font-weight: bold;
  color: #554149;
}

/* Timer Label */
.timer-label {
  font-size: 1rem;
  text-transform: uppercase; /* Make labels uppercase */
  /* color: #777; Muted color for labels */
  margin-top: 0.3rem;
}

/* Divider Styling */
.divider {
  font-size: 2.5rem;
  font-weight: bold;
  color: #554149;
  display: flex;
  align-items: center;
  justify-content: center;
}

</style>
