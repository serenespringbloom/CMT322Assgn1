<template>
  <vue-countdown :time="time" :interval="100" v-slot="{ days, hours, minutes, seconds }">
    {{ days }} days, {{ hours }} hours, {{ minutes }} minutes, {{ seconds }} seconds.
  </vue-countdown>
</template>

<script setup lang="ts">
import { ref } from 'vue';
// Calculate the countdown target time
const now = new Date().getTime(); // Convert current date to timestamp
const targetDate = new Date(2024, 5, 1).getTime(); // June 1, 2024 (Months are 0-indexed)
const time = ref(targetDate - now); // Difference in milliseconds
</script>
