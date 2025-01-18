<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import TicketDisplay from '../components/TicketDisplay.vue';

const rows = 15; // Number of rows
const columns = 24; // Number of seats per row
const seatsPerSection = 8;
const bookedSeats = ref([]); // Pre-booked seats

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
const customerName = ref('');
const showTicket = ref(false);
const ticketData = ref(null);

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
const completePurchase = async () => {
  if (isEmailValid.value && isPhoneValid.value && selectedBank.value && customerName.value) {
    try {
      // First verify if seats are still available
      const verifyResponse = await fetch(`${import.meta.env.VITE_API_URL}/api/seats/verify`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          seats: selectedSeats.value,
          event_id: 1
        })
      });
      
      const verifyResult = await verifyResponse.json();
      
      if (!verifyResult.available) {
        alert(`Sorry, some seats are no longer available: ${verifyResult.unavailableSeats.join(', ')}`);
        return;
      }

      const requestData = {
        email: email.value,
        phone: phone.value,
        selectedSeats: selectedSeats.value,
        studentTickets: studentTickets.value,
        publicTickets: publicTickets.value,
        totalAmount: totalPrice.value,
        bankName: selectedBank.value,
        event_id: 1,
        customerName: customerName.value
      };

      console.log('Sending request with data:', requestData); // Debug log

      // Proceed with booking
      const response = await fetch(`${import.meta.env.VITE_API_URL}/api/seats/book`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(requestData)
      });

      
      const result = await response.json();
      
      if (result.success) {
        // Fetch ticket details
        const ticketResponse = await fetch(`${import.meta.env.VITE_API_URL}/api/ticket/${result.transaction_id}`);
        const ticketResult = await ticketResponse.json();
        
        if (ticketResult.success) {
          ticketData.value = ticketResult.data;
          showTicket.value = true;
          showCheckoutPopup.value = false;
        }
        
        // Reset form fields
        selectedSeats.value = [];
        studentTickets.value = 0;
        publicTickets.value = 0;
        email.value = '';
        phone.value = '';
        customerName.value = '';
        selectedBank.value = '';
      } else {
        throw new Error(result.message || 'Booking failed');
      }
    } catch (error) {
      alert('An error occurred during booking. Please try again.');
      console.error('Booking error:', error);
    }
  } else {
    alert('Please provide all required information (name, email, phone number, and bank).');
  }
};

// Load data when the component is mounted
loadFromSessionStorage();

// Add this to your mounted/created hook
onMounted(async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/seats?event_id=1`); // Replace with your actual event ID
    const result = await response.json();
    if (result.success) {
      bookedSeats.value = result.data
        .filter(seat => seat.is_booked)
        .map(seat => seat.seat_id);
    }
  } catch (error) {
    console.error('Failed to fetch seats:', error);
  }
});
</script>

<template>
  <div class="min-h-screen bg-[#FFC8DD] py-8">
    <!-- Header -->
    <div class="text-center mb-12">
      <h1 class="text-4xl mb-[20px] font-bold text-gray-800 tracking-[0.9em] font-['Plus_Jakarta_Sans']">
        SEAT SELECTION
      </h1>
      <div class="w-24 h-1 bg-[#dcc39c] mx-auto"></div>
    </div>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Seat Grid Section -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
          <!-- Row Labels -->
          <div class="flex">
            <div class="w-8 mt-[52px]">
              <div v-for="row in rows" :key="row" class="h-8 mt-[15px] mb-[40px] flex items-center justify-center text-gray-600 font-medium">
                {{ String.fromCharCode(65 + row - 1) }}
              </div>
            </div>

            <!-- Seat Sections -->
            <div class="flex-1 grid grid-cols-3 gap-4">
              <!-- Left Section -->
              <div>
                <h3 class="text-center text-gray-700 font-semibold mb-4">Left Section</h3>
                <div class="grid grid-cols-4 gap-1">
                  <div
                    v-for="seat in seats.left"
                    :key="seat"
                    @click="toggleSeat(seat)"
                    :class="[
                      'h-8 flex items-center justify-center text-xs cursor-pointer rounded transition-all duration-200',
                      {
                        'bg-red-400 text-white cursor-not-allowed': bookedSeats.includes(seat),
                        'bg-[#a48e69] text-white': selectedSeats.includes(seat),
                        'bg-gray-100 hover:bg-gray-200 text-gray-700': !bookedSeats.includes(seat) && !selectedSeats.includes(seat)
                      }
                    ]"
                  >
                    {{ seat }}
                  </div>
                </div>
              </div>

              <!-- Center Section -->
              <div>
                <h3 class="text-center text-gray-700 font-semibold mb-4">Center Section</h3>
                <div class="grid grid-cols-4 gap-1">
                  <div
                    v-for="seat in seats.center"
                    :key="seat"
                    @click="toggleSeat(seat)"
                    :class="[
                      'h-8 flex items-center justify-center text-xs cursor-pointer rounded transition-all duration-200',
                      {
                        'bg-red-400 text-white cursor-not-allowed': bookedSeats.includes(seat),
                        'bg-[#a48e69] text-white': selectedSeats.includes(seat),
                        'bg-gray-100 hover:bg-gray-200 text-gray-700': !bookedSeats.includes(seat) && !selectedSeats.includes(seat)
                      }
                    ]"
                  >
                    {{ seat }}
                  </div>
                </div>
              </div>

              <!-- Right Section -->
              <div>
                <h3 class="text-center text-gray-700 font-semibold mb-4">Right Section</h3>
                <div class="grid grid-cols-4 gap-1">
                  <div
                    v-for="seat in seats.right"
                    :key="seat"
                    @click="toggleSeat(seat)"
                    :class="[
                      'h-8 flex items-center justify-center text-xs cursor-pointer rounded transition-all duration-200',
                      {
                        'bg-red-400 text-white cursor-not-allowed': bookedSeats.includes(seat),
                        'bg-[#a48e69] text-white': selectedSeats.includes(seat),
                        'bg-gray-100 hover:bg-gray-200 text-gray-700': !bookedSeats.includes(seat) && !selectedSeats.includes(seat)
                      }
                    ]"
                  >
                    {{ seat }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Legend -->
          <div class="mt-8 flex justify-center gap-6">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-gray-100 rounded"></div>
              <span class="text-sm text-gray-600">Available</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-[#a48e69] rounded"></div>
              <span class="text-sm text-gray-600">Selected</span>
            </div>
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 bg-red-400 rounded"></div>
              <span class="text-sm text-gray-600">Booked</span>
            </div>
          </div>
        </div>

        <!-- Ticket Selection Section -->
        <div class="bg-white rounded-xl shadow-lg p-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-6">Theatre Seat Booking</h2>

          <!-- Selected Seats -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Selected Seats</h3>
            <div class="bg-gray-50 p-3 rounded">
              <p class="text-gray-600">
                {{ selectedSeats.length > 0 ? selectedSeats.join(', ') : 'None' }}
              </p>
            </div>
          </div>

          <!-- Ticket Options -->
          <div class="space-y-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-700">Ticket Selection</h3>
            
            <!-- Student Tickets -->
            <div class="flex items-center justify-between">
              <label for="student-tickets" class="text-gray-600">Student:</label>
              <input
                id="student-tickets"
                type="number"
                min="0"
                :max="selectedSeats.length"
                v-model.number="studentTickets"
                @input="adjustTickets('student', $event.target.value)"
                class="w-20 px-3 py-2 border rounded focus:ring-2 focus:ring-[#a48e69] focus:border-transparent"
              />
            </div>

            <!-- Public Tickets -->
            <div class="flex items-center justify-between">
              <label for="public-tickets" class="text-gray-600">Public:</label>
              <input
                id="public-tickets"
                type="number"
                min="0"
                :max="selectedSeats.length"
                v-model.number="publicTickets"
                @input="adjustTickets('public', $event.target.value)"
                class="w-20 px-3 py-2 border rounded focus:ring-2 focus:ring-[#a48e69] focus:border-transparent"
              />
            </div>
          </div>

          <!-- Total Price -->
          <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <p class="text-xl font-bold text-gray-800">
              Total Price: <span class="text-[#a48e69]">RM {{ totalPrice }}</span>
            </p>
          </div>

          <!-- Checkout Button -->
          <button 
            @click="showCheckoutPopup = true"
            class="w-full bg-[#a48e69] text-white py-3 rounded-lg font-medium hover:bg-[#8a7557] transition-colors duration-200"
          >
            Proceed to Checkout
          </button>
        </div>
      </div>
    </div>

    <!-- Checkout Popup -->
    <div v-if="showCheckoutPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white rounded-xl max-w-md w-full p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Checkout Summary</h2>

        <!-- Summary Section -->
        <div class="space-y-3 mb-6">
          <p class="flex justify-between">
            <span class="text-gray-600">Selected Seats:</span>
            <span class="font-medium">{{ selectedSeats.join(', ') || 'None' }}</span>
          </p>
          <p class="flex justify-between">
            <span class="text-gray-600">Student Tickets:</span>
            <span class="font-medium">{{ studentTickets }}</span>
          </p>
          <p class="flex justify-between">
            <span class="text-gray-600">Public Tickets:</span>
            <span class="font-medium">{{ publicTickets }}</span>
          </p>
          <p class="flex justify-between text-lg font-bold">
            <span>Total Price:</span>
            <span class="text-[#a48e69]">RM {{ totalPrice }}</span>
          </p>
        </div>

        <!-- Customer Information -->
        <div class="space-y-4 mb-6">
          <input
            type="text"
            v-model="customerName"
            placeholder="Enter your name"
            class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-[#a48e69] focus:border-transparent"
            :class="{ 'border-red-500': !customerName && showValidation }"
          />
          <input
            type="email"
            v-model="email"
            placeholder="Enter your email"
            class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-[#a48e69] focus:border-transparent"
            :class="{ 'border-red-500': !isEmailValid && email }"
          />
          <input
            type="text"
            v-model="phone"
            placeholder="Enter your phone number"
            class="w-full px-4 py-2 border rounded focus:ring-2 focus:ring-[#a48e69] focus:border-transparent"
            :class="{ 'border-red-500': !isPhoneValid && phone }"
          />
        </div>

        <!-- Bank Selection -->
        <div class="mb-6">
          <label class="block text-gray-700 mb-2">Choose Your Bank (FPX):</label>
          <div class="relative">
            <select 
              v-model="selectedBank"
              class="w-full px-4 py-2 border rounded appearance-none bg-white focus:ring-2 focus:ring-[#a48e69] focus:border-transparent"
            >
              <option disabled value="">Select a Bank</option>
              <option
                v-for="bank in banks"
                :key="bank.name"
                :value="bank.name"
              >
                {{ bank.name }}
              </option>
            </select>
            <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
              <img
                :src="selectedBankIcon"
                alt="Bank Icon"
                class="h-6 w-6 object-contain"
              />
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4">
          <button
            @click="showCheckoutPopup = false"
            class="flex-1 px-4 py-2 border border-gray-300 rounded font-medium hover:bg-gray-50 transition-colors duration-200"
          >
            Cancel
          </button>
          <button
            @click="completePurchase"
            :disabled="!isEmailValid || !isPhoneValid || !selectedBank || !customerName"
            class="flex-1 px-4 py-2 bg-[#a48e69] text-white rounded font-medium hover:bg-[#8a7557] transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Complete Purchase
          </button>
        </div>
      </div>
    </div>

    <!-- Ticket Display -->
    <TicketDisplay 
      v-if="showTicket"
      :ticketData="ticketData"
      @close="showTicket = false"
      class="fixed inset-0 z-50"
    />
  </div>
</template>

<style scoped>
/* Any additional custom styles if needed */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';
</style>