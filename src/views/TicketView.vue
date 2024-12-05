<script setup>
import { ref, computed, watch } from 'vue';

const rows = 15; // Number of rows
const columns = 24; // Number of seats per row
const seatsPerSection = 8;
const bookedSeats = ref(['A1', 'B3', 'C5']); // Pre-booked seats

// Divide seats into three sections
const seats = computed(() => {
  const grid = { left: [], center: [], right: [] };
  const splitPoint = Math.floor(columns / 3);

  for (let i = 0; i < rows; i++) {
    const row = String.fromCharCode(65 + i); // A, B, C...
    for (let j = 1; j <= columns; j++) {
      const seat = `${row}${j}`;
      if (j <= splitPoint) {
        grid.left.push(seat);
      } else if (j <= splitPoint * 2) {
        grid.center.push(seat);
      } else {
        grid.right.push(seat);
      }
    }
  }
  return grid;
});

// Reactive refs for selected seats and ticket counts
const selectedSeats = ref([]);
const studentTickets = ref(0);
const publicTickets = ref(0);
const showCheckoutPopup = ref(false); // State for showing the popup
const email = ref(''); // User email
const phone = ref(''); // User phone number

// Validation for email
const isEmailValid = computed(() => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email.value);
});

// Validation for Malaysian phone number
const isPhoneValid = computed(() => {
  const phoneRegex = /^(\+60|0)[1-9][0-9]{7,9}$/;
  return phoneRegex.test(phone.value);
});

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
  value = Math.max(0, Math.min(value, selectedSeats.value.length)); // Prevent invalid values
  const otherType = type === 'student' ? publicTickets : studentTickets;

  const remainingSeats = selectedSeats.value.length - value;
  otherType.value = Math.max(0, Math.min(remainingSeats, otherType.value));

  if (type === 'student') {
    studentTickets.value = value;
  } else {
    publicTickets.value = value;
  }
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
  if (isEmailValid.value && isPhoneValid.value) {
    bookedSeats.value.push(...selectedSeats.value);
    selectedSeats.value = [];
    studentTickets.value = 0;
    publicTickets.value = 0;
    email.value = '';
    phone.value = '';
    showCheckoutPopup.value = false;
    alert('Purchase complete!');
  } else {
    alert('Please provide valid email and phone number.');
  }
};

// Load data when the component is mounted
loadFromSessionStorage();
</script>

<template>
  <div class="title">
    <h1>Seat Selection</h1>
  </div>
  <div class="page-container">
    <!-- Seat Grid Section -->
    <div class="seat-section">
      <div class="seat-layout">
        <div class="row-labels">
          <div v-for="row in rows" :key="row" class="row-label">
            {{ String.fromCharCode(65 + row - 1) }}
          </div>
        </div>
        <div class="seat-column">
          <h3>Left Section</h3>
          <div class="seat-grid">
            <div
              v-for="seat in seats.left"
              :key="seat"
              :class="[ 'seat', { booked: bookedSeats.includes(seat), selected: selectedSeats.includes(seat) } ]"
              @click="toggleSeat(seat)"
            >
              {{ seat }}
            </div>
          </div>
        </div>
        <div class="seat-column">
          <h3>Centre Section</h3>
          <div class="seat-grid">
            <div
              v-for="seat in seats.center"
              :key="seat"
              :class="[ 'seat', { booked: bookedSeats.includes(seat), selected: selectedSeats.includes(seat) } ]"
              @click="toggleSeat(seat)"
            >
              {{ seat }}
            </div>
          </div>
        </div>
        <div class="seat-column">
          <h3>Right Section</h3>
          <div class="seat-grid">
            <div
              v-for="seat in seats.right"
              :key="seat"
              :class="[ 'seat', { booked: bookedSeats.includes(seat), selected: selectedSeats.includes(seat) } ]"
              @click="toggleSeat(seat)"
            >
              {{ seat }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Ticket Options Section -->
    <div class="ticket-section">
      <h1>Theatre Seat Booking</h1>

      <!-- Summary -->
      <div class="summary">
        <h2>Selected Seats:</h2>
        <p class="selected-seats">{{ selectedSeats.join(', ') || 'None' }}</p>
      </div>

      <!-- Ticket Options -->
      <div class="ticket-options">
        <h2>Ticket Selection</h2>
        <div class="ticket-input">
          <label>Student Tickets:</label>
          <input
            type="number"
            min="0"
            :max="selectedSeats.length"
            v-model.number="studentTickets"
            @input="adjustTickets('student', $event.target.value)"
          />
        </div>
        <div class="ticket-input">
          <label>Public Tickets:</label>
          <input
            type="number"
            min="0"
            :max="selectedSeats.length"
            v-model.number="publicTickets"
            @input="adjustTickets('public', $event.target.value)"
          />
        </div>
      </div>

      <!-- Total Price -->
      <p class="total-price">Total Price: RM {{ totalPrice }}</p>

      <!-- Complete Purchase Button -->
      <button class="checkout-btn" @click="showCheckoutPopup = true">Proceed to Checkout</button>
    </div>
  </div>
  <!-- Checkout Popup -->
  <div v-if="showCheckoutPopup" class="checkout-popup">
    <div class="popup-content">
      <h2>Checkout Summary</h2>
      <p><strong>Selected Seats:</strong> {{ selectedSeats.join(', ') || 'None' }}</p>
      <p><strong>Number of Student Tickets:</strong> {{ studentTickets }}</p>
      <p><strong>Number of Public Tickets:</strong> {{ publicTickets }}</p>
      <p><strong>Total Price:</strong> RM {{ totalPrice }}</p>

      <!-- Email and Phone Inputs -->
      <div class="checkout-inputs">
        <input
          type="email"
          v-model="email"
          placeholder="Enter your email"
          :class="{ invalid: !isEmailValid && email }"
        />
        <span v-if="email && !isEmailValid" class="error-message">
          Please enter a valid email address.
        </span>
        <input
          type="text"
          v-model="phone"
          placeholder="Enter your phone number"
          :class="{ invalid: !isPhoneValid && phone }"
        />
        <span v-if="phone && !isPhoneValid" class="error-message">
          Please enter a valid Malaysian phone number.
        </span>
      </div>

      <!-- Complete Purchase Button -->
      <button
        class="complete-btn"
        :disabled="!isEmailValid || !isPhoneValid"
        @click="completePurchase"
      >
        Complete Purchase
      </button>
      <button class="close-btn" @click="showCheckoutPopup = false">Close</button>
    </div>
  </div>
</template>

<style scoped>
/* Layout */
.title{
  text-align: center;
  padding-top: 1.5%;
  height: 25px;
}

.page-container {
  display: flex;
  gap: 20px;
  min-width: 1600px;
  max-width: 1600px;
  margin: auto;
  padding: 20px;
  font-family: Arial, sans-serif;
  background: none;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Seat Section */
.seat-section {
  flex: 2;
  align-items: center;
}

.seat-layout {
  display: flex;
  justify-content: space-between;
  flex-direction: row;
}

.seat-column {
  flex: 1;
  text-align: center;
}

.seat-grid {
  display: grid;
  grid-template-columns: repeat(8, 25px); /* Adjust for smaller seat size */
  gap: 5px;
  margin-top: 10px;
  align-items: center;
  justify-content: center;
}

.seat {
  width: 30px;
  height: 30px;
  border: 1px solid #ccc;
  text-align: center;
  line-height: 20px;
  font-size: 10px;
  cursor: pointer;
  background-color: #fff;
  border-radius: 3px;
  transition: all 0.3s ease;
}

.seat.booked {
  background-color: #ff4d4f;
  color: white;
  cursor: not-allowed;
}

.seat.selected {
  background-color: #4caf50;
  color: white;
}

.row-labels {
  display: grid;
  grid-template-rows: repeat(15, 30px); /* Adjust height for rows */
  gap: 5px;
  margin-right: 10px;
  padding-top: 35px;
  padding-left: 15px;
  align-items: center;
}

.row-label {
  font-size: 12px;
  text-align: center;
  font-weight: bold;
}

/* Ticket Section */
.ticket-section {
  display: inline-block;
  flex: 1;
  padding: 20px;
  max-width: 400px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
  text-align: center;
}

/* Additional Styles */
.ticket-options {
  margin: 20px 0;
}

.total-price {
  font-size: 1.5rem;
  font-weight: bold;
}

.checkout-btn {
  background-color: #dcc39c;
  padding: 16px 30px;
  margin-top: 20px;
  border: none;
  color: black;
}

.checkout-btn:hover {
  background-color: #f0f0f0;
  border-color: #b5b5b5;
  cursor: pointer;
}

.checkout-popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  width: 400px;
  text-align: center;
}

.popup-content {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.error-message {
  color: red;
  font-size: 0.9rem;
}

.complete-btn {
  background-color: #28a745;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
}

.complete-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.close-btn {
  background-color: #d9534f;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1rem;
}

/* Overlay to ensure background is dimmed */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 999;
}

.checkout-inputs{
  display: flex;
  flex-direction: column;
  gap:10px;
  padding:5px;
}

input{
  padding:8px;
  margin:4px;
}
</style>