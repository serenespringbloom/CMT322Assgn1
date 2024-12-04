<script setup>
import { ref, computed, watch } from 'vue';

const rows = 5; // Number of rows in the theatre
const columns = 8; // Number of seats per row
const bookedSeats = ref(['A1', 'B3', 'C5']); // Pre-booked seats

// Generate the seat grid
const seats = computed(() => {
  const grid = [];
  for (let i = 0; i < rows; i++) {
    const row = String.fromCharCode(65 + i); // A, B, C...
    for (let j = 1; j <= columns; j++) {
      grid.push(`${row}${j}`);
    }
  }
  return grid;
});

// Reactive refs for selected seats and ticket counts
const selectedSeats = ref([]);
const studentTickets = ref(0);
const publicTickets = ref(0);

// Load data from sessionStorage
const loadFromSessionStorage = () => {
  const savedSeats = JSON.parse(sessionStorage.getItem('selectedSeats')) || [];
  const savedStudentTickets = Number(sessionStorage.getItem('studentTickets')) || 0;
  const savedPublicTickets = Number(sessionStorage.getItem('publicTickets')) || 0;

  selectedSeats.value = savedSeats;
  studentTickets.value = savedStudentTickets;
  publicTickets.value = savedPublicTickets;
};

// Save data to sessionStorage whenever changes occur
const saveToSessionStorage = () => {
  sessionStorage.setItem('selectedSeats', JSON.stringify(selectedSeats.value));
  sessionStorage.setItem('studentTickets', studentTickets.value);
  sessionStorage.setItem('publicTickets', publicTickets.value);
};

// Watch for changes to synchronize with sessionStorage
watch([selectedSeats, studentTickets, publicTickets], saveToSessionStorage, {
  deep: true,
});

// Toggle seat selection
const toggleSeat = (seat) => {
  if (bookedSeats.value.includes(seat)) return; // Ignore booked seats

  if (selectedSeats.value.includes(seat)) {
    // Remove the seat if already selected
    selectedSeats.value = selectedSeats.value.filter((s) => s !== seat);

    // Decrease ticket count, prioritize public tickets first
    if (publicTickets.value > 0) {
      publicTickets.value -= 1;
    } else if (studentTickets.value > 0) {
      studentTickets.value -= 1;
    }
  } else {
    // Add the seat to selected seats
    selectedSeats.value.push(seat);

    // Increase ticket count, prioritize public tickets by default
    const totalSeats = selectedSeats.value.length;
    const currentTotal = studentTickets.value + publicTickets.value;

    if (currentTotal < totalSeats) {
      publicTickets.value += 1;
    }
  }
};

// Adjust ticket counts to maintain total equal to selectedSeats
const adjustTickets = (type, value) => {
  const totalSeats = selectedSeats.value.length;
  const otherType = type === 'student' ? publicTickets : studentTickets;

  const updatedType = type === 'student' ? studentTickets : publicTickets;
  const diff = totalSeats - value - otherType.value;

  if (diff > 0) {
    // Add remaining tickets to the other type
    otherType.value += diff;
  } else if (diff < 0) {
    // Reduce tickets from the other type
    otherType.value = Math.max(0, otherType.value + diff);
  }

  // Update the current type
  updatedType.value = value;
};

// Ticket pricing logic
const prices = {
  student: 15,
  public: 20,
};

// Compute total price based on ticket counts
const totalPrice = computed(() => {
  return studentTickets.value * prices.student + publicTickets.value * prices.public;
});

// Complete the purchase and update booked seats
const completePurchase = () => {
  bookedSeats.value.push(...selectedSeats.value); // Add selected seats to booked seats
  selectedSeats.value = []; // Clear selected seats
  studentTickets.value = 0; // Clear student tickets
  publicTickets.value = 0; // Clear public tickets
  alert('Purchase complete!');
};

// Load data when the component is mounted
loadFromSessionStorage();
</script>

<template>
  <!-- Seat Grid -->
  <div class="seat-grid">
    <div
      v-for="seat in seats"
      :key="seat"
      :class="[ 'seat', { booked: bookedSeats.includes(seat), selected: selectedSeats.includes(seat) } ]"
      @click="toggleSeat(seat)"
    >
      {{ seat }}
    </div>
  </div>

  <!-- Summary of Selected Seats -->
  <div class="summary">
    <h3>Selected Seats:</h3>
    <p>{{ selectedSeats.join(', ') || 'None' }}</p>
  </div>

  <!-- Ticket Options -->
  <div class="ticket-options">
    <label>
      Student Tickets:
      <input
        type="number"
        min="0"
        :max="selectedSeats.length"
        v-model.number="studentTickets"
        @input="adjustTickets('student', $event.target.value)"
      />
    </label>
    <label>
      Public Tickets:
      <input
        type="number"
        min="0"
        :max="selectedSeats.length"
        v-model.number="publicTickets"
        @input="adjustTickets('public', $event.target.value)"
      />
    </label>
  </div>

  <!-- Display Total Price -->
  <p>Total Price: RM {{ totalPrice }}</p>

  <!-- Complete Purchase Button -->
  <button @click="completePurchase">Complete Purchase</button>
</template>

<style scoped>
.seat-grid {
  display: grid;
  grid-template-columns: repeat(8, 50px);
  gap: 10px;
}

.seat {
  width: 50px;
  height: 50px;
  border: 1px solid #ccc;
  text-align: center;
  line-height: 50px;
  cursor: pointer;
  user-select: none;
}

.seat.booked {
  background-color: #ff4d4f;
  cursor: not-allowed;
}

.seat.selected {
  background-color: #4caf50;
  color: white;
}
</style>
