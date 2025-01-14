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
const selectedBank = ref("");
const fpxIcon = "/src/assets/images/fpx-icon.png";

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

// Banks Data
const banks = ref([
  { name: "Maybank", icon: "/src/assets/images/maybank-icon.png" },
  { name: "CIMB Bank", icon: "/src/assets/images/cimb-icon.png" },
  { name: "RHB Bank", icon: "/src/assets/images/rhb-icon.png" },
  { name: "Public Bank", icon: "/src/assets/images/publicbank-icon.png" },
  { name: "Bank Rakyat", icon: "/src/assets/images/bankrakyat-icon.png" }
]);

// Computed for Selected Bank Icon
const selectedBankIcon = computed(() => {
  if (!selectedBank.value) {
    // Show the FPX icon when no bank is selected
    return fpxIcon;
  }
  const bank = banks.value.find((b) => b.name === selectedBank.value);
  return bank ? bank.icon : fpxIcon; // Fallback to FPX icon
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
    selectedBank.value = ''; // Reset selected bank
    alert(`Purchase complete!`);
  } else {
    alert('Please provide valid email and phone number and select a bank.');
  }
};

// Load data when the component is mounted
loadFromSessionStorage();
</script>

<template>
  <div class="title">
    <h1>SEAT SELECTION</h1>
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

    <div class="ticket-section">
  <h1 class="section-title">Theatre Seat Booking</h1>

  <!-- Summary -->
  <div class="summary">
    <h2 class="summary-title">Selected Seats</h2>
    <p class="selected-seats">
      {{ selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None' }}
    </p>
  </div>

  <!-- Ticket Options -->
  <div class="ticket-options">
    <h2 class="options-title">Ticket Selection</h2>

    <!-- Student Tickets -->
    <div class="ticket-input">
      <label for="student-tickets">Student:</label>
      <input
        id="student-tickets"
        type="number"
        min="0"
        :max="selectedSeats.length"
        v-model.number="studentTickets"
        @input="adjustTickets('student', $event.target.value)"
      />
    </div>

    <!-- Public Tickets -->
    <div class="ticket-input">
      <label for="public-tickets">Public:</label>
      <input
        id="public-tickets"
        type="number"
        min="0"
        :max="selectedSeats.length"
        v-model.number="publicTickets"
        @input="adjustTickets('public', $event.target.value)"
      />
    </div>
  </div>

  <!-- Total Price -->
  <div class="price-summary">
    <p class="total-price">
      Total Price: <strong>RM {{ totalPrice }}</strong>
    </p>
  </div>

  <!-- Checkout Button -->
  <button class="checkout-btn" @click="showCheckoutPopup = true">
    Proceed to Checkout
  </button>
    </div>
  </div>
    <!-- Popup Overlay -->
  <div v-if="showCheckoutPopup" class="popup-overlay"></div>

  <!-- Enhanced Checkout Popup -->
  <div v-if="showCheckoutPopup" class="checkout-popup">
    <div class="popup-content">
      <!-- Header -->
      <h2>Checkout Summary</h2>

      <!-- Summary Section -->
      <p><strong>Selected Seats:</strong> <span>{{ selectedSeats.join(', ') || 'None' }}</span></p>
      <p><strong>Number of Student Tickets:</strong> <span>{{ studentTickets }}</span></p>
      <p><strong>Number of Public Tickets:</strong> <span>{{ publicTickets }}</span></p>
      <p class="total-price">Total Price: RM {{ totalPrice }}</p>

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

        <!-- FPX Bank Selection -->
      <div class="checkout-inputs">
        <label for="bank-select">Choose Your Bank (FPX):</label>
        <div class="bank-dropdown">
          <select id="bank-select" v-model="selectedBank">
            <option disabled value="">Select a Bank</option>
            <option
              v-for="bank in banks"
              :key="bank.name"
              :value="bank.name"
            >
              {{ bank.name }}
            </option>
          </select>
          <div class="bank-icon">
            <img
              v-if="selectedBankIcon"
              :src="selectedBankIcon"
              alt="Bank Icon"
            />
          </div>
        </div>
      </div>

      <!-- Complete Purchase Button -->
      <button
        class="complete-btn"
        :disabled="!isEmailValid || !isPhoneValid || !selectedBank"
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
  letter-spacing: .9rem;
  font-family: "Plus Jakarta Sans", serif;
  margin-top: 2rem;
}

.title{
  font-size: 1.2rem;
  letter-spacing: 2rem;
  margin-bottom: 1.5rem;
  color: #554149;
  text-transform: uppercase;
}

.page-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  min-width: 1400px;
  max-width: 1400px;
  margin: 50px auto;
  padding: 20px;
  font-family: Arial, sans-serif;
  background: none;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Enhanced Seat Section */
.seat-section {
  flex: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 20px;
  background: linear-gradient(135deg, rgba(255, 240, 245, 0.8), rgba(255, 200, 221, 0.8));
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

/* Seat Layout */
.seat-layout {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  margin-top: 20px;
}

/* Seat Column */
.seat-column {
  flex: 1;
  text-align: center;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.seat-column h2 {
  font-size: 1.2rem;
  font-weight: bold;
  color: #6b4f57;
  margin-bottom: 10px;
}

/* Seat Grid */
.seat-grid {
  display: grid;
  grid-template-columns: repeat(8, 35px); /* Adjust for improved seat size */
  gap: 3px;
  margin-top: 10px;
  justify-content: center;
  align-items: center;
  position: relative;
}

/* Individual Seat */
.seat {
  width: 35px;
  height: 35px;
  border: 1px solid #ddd;
  text-align: center;
  line-height: 35px;
  font-size: 12px;
  cursor: pointer;
  background-color: #fff;
  border-radius: 5px;
  transition: all 0.3s ease-in-out;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.seat:hover {
  background-color: #e4f7e8;
  transform: scale(1.1);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

/* Booked Seat */
.seat.booked {
  background-color: #ff4d4f;
  color: white;
  cursor: not-allowed;
  border: 1px solid #d43f3a;
}

.seat.booked:hover {
  transform: none;
  box-shadow: none;
}

/* Selected Seat */
.seat.selected {
  background-color: #4caf50;
  color: white;
  border: 1px solid #3e8e41;
}

/* Row Labels */
.row-labels {
  display: grid;
  grid-template-rows: repeat(15, 35px); /* Match seat height */
  gap: 3px;
  margin-right: 10px;
  padding-top: 40px;
  align-items: center;
}

.row-label {
  font-size: 12px;
  text-align: center;
  font-weight: bold;
  color: #6b4f57;
}

/* Seat Sections Header */
.seat-section h2 {
  text-transform: uppercase;
  font-size: 1.5rem;
  font-weight: bold;
  color: #6b4f57;
  margin-bottom: 20px;
}

/* Subtle Background for Hovered Column */
.seat-column:hover {
  background: rgba(255, 200, 221, 0.1);
  border-radius: 10px;
}

/* Center Section Divider */
.seat-layout .seat-column:not(:last-child)::after {
  content: '';
  width: 2px;
  height: 100%;
  background: rgba(107, 79, 87, 0.2);
  position: absolute;
  top: 0;
  right: 0;
}

/* Ticket Section */
.ticket-section {
  display: inline-block;
  flex: 1;
  padding: 40px;
  padding-top: 110px;
  max-width: 350px;
  background: linear-gradient(135deg, rgba(255, 224, 229, 0.9), rgba(255, 200, 221, 0.7));
  border-radius: 15px; /* Add rounded corners */
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* More prominent shadow */
  text-align: center;
  font-family: 'Plus Jakarta Sans', sans-serif;
  position: relative; /* For decorative elements */
  overflow: hidden;
}

/* Decorative Overlay */
.ticket-section::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent 80%);
  transform: rotate(45deg);
  pointer-events: none;
}

/* Heading */
.ticket-section h1 {
  font-size: 1.8rem;
  color: #554149;
  font-weight: 700;
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.1rem;
}

/* Subheading */
.ticket-section h2 {
  font-size: 1.2rem;
  color: #6b4f57;
  font-weight: 500;
  margin-bottom: 1rem;
  text-transform: uppercase;
}

/* Selected Seats */
.selected-seats {
  font-size: 1rem;
  color: #6b4f57;
  font-weight: bold;
  margin-bottom: 1rem;
}

/* Ticket Options */
.ticket-options {
  margin: 20px 0;
  font-size: 1rem;
  color: #333;
  display: flex;
  justify-content: space-between;
  gap: 1rem;
}

.options-title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #554149;
  margin-bottom: 1.2rem; /* Add extra spacing below the title */
  margin-top: 0.5rem; /* Optional: Add some spacing above the title */
  margin-left: -1rem;
  text-align: center; /* Center the title horizontally */
}

.ticket-options label {
  flex: 1;
  text-align: left;
  font-weight: 500;
  color: #554149;
}

.ticket-options input {
  flex: 0.5;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
  text-align: center;
}

/* Total Price */
.total-price {
  font-size: 1.5rem;
  font-weight: 800;
  color: #554149;
  margin-top: 1rem;
  text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
}

/* Checkout Button */
.checkout-btn {
  background: linear-gradient(135deg, #a48e69, #dcc39c);
  color: #2a2a2a;
  font-size: 1rem;
  font-weight: bold;
  border: none;
  border-radius: 10px;
  padding: 1rem 2rem;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.3s ease;
  margin-top: 1.5rem;
}

.checkout-btn:hover {
  background: linear-gradient(135deg, #dcc39c, #a48e69);
  transform: translateY(-3px);
  color: #2a2a2a;
}

/* Additional Enhancements for Mobile View */
@media (max-width: 768px) {

  .ticket-section {
    max-width: 90%;
    padding: 20px;
  }

  .checkout-btn {
    width: 100%;
  }
}

/* Enhanced Checkout Popup */
.checkout-popup {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: linear-gradient(135deg, #fff1f5, #ffe5ed);
  padding: 25px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  z-index: 1000;
  width: 450px;
  text-align: center;
  animation: fadeIn 0.3s ease-out;
  font-family: "Plus Jakarta Sans", sans-serif;
}


/* Popup Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translate(-50%, -45%);
  }
  to {
    opacity: 1;
    transform: translate(-50%, -50%);
  }
}

/* Popup Header */
.checkout-popup h2 {
  font-size: 1.8rem;
  font-weight: bold;
  color: #554149;
  margin-bottom: 0.1rem;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

/* Summary Section */
.checkout-popup p {
  font-size: 1rem;
  color: #6b4f57;
  font-weight: 500;
  margin-bottom: 0.3rem;
}

.checkout-popup p span {
  font-weight: bold;
  color: #a48e69;
}

/* Highlighted Section (Total Price) */
.checkout-popup .total-price {
  font-size: 1.6rem;
  font-weight: bold;
  color: #554149;
  margin: 1rem 0;
  padding: 0.5rem 0;
  background: linear-gradient(135deg, #ffe8e8, #ffc8dd);
  border-radius: 10px;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

/* Content Layout */
.popup-content {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

/* FPX Bank Selection Styling */
.bank-dropdown {
  display: flex;
  align-items: center;
  gap: 1rem;
  justify-content: center;
  margin-top: -0.3rem;
}

.bank-dropdown select {
  padding: 0.75rem;
  font-size: 1rem;
  font-weight: bold;
  border: 2px solid #ccc;
  border-radius: 10px;
  background-color: #fff;
  cursor: pointer;
  transition: all 0.3s ease;
}

.bank-dropdown select:hover {
  border-color: #ffc8dd;
  background-color: #fff6f8;
}

.bank-dropdown .bank-icon img {
  width: 40px;
  height: 40px;
  object-fit: contain;
  display: inline-block;
}

/* Error Message */
.error-message {
  color: red;
  font-size: 0.9rem;
}

/* Buttons */
.complete-btn {
  background: linear-gradient(135deg, #6bcf6b, #4caf50);
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
  transition: background 0.3s ease, transform 0.3s ease;
}

.complete-btn:hover {
  background: linear-gradient(135deg, #4caf50, #6bcf6b);
  transform: translateY(-3px);
}

.complete-btn:disabled {
  background-color: #ccc;
  cursor: not-allowed;
  box-shadow: none;
}

.close-btn {
  background: linear-gradient(135deg, #ff5f57, #d9534f);
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  box-shadow: 0 5px 15px rgba(217, 83, 79, 0.3);
  transition: background 0.3s ease, transform 0.3s ease;
}

.close-btn:hover {
  background: linear-gradient(135deg, #d9534f, #ff5f57);
  transform: translateY(-3px);
}

/* Overlay for Dimmed Background */
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.6);
  z-index: 999;
  animation: fadeOverlay 0.3s ease-out;
}

/* Overlay Animation */
@keyframes fadeOverlay {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Inputs */
.checkout-inputs {
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding: 10px;
}

input {
  padding: 12px;
  margin: 5px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 1rem;
  width: 100%;
}

input:focus {
  outline: none;
  border-color: #ffc8dd;
  box-shadow: 0 0 5px rgba(255, 200, 221, 0.5);
}

</style>