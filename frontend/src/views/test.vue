<template>
  <div class="max-w-7xl mx-auto bg-white shadow-lg rounded-lg p-8">
    <h1 class="text-3xl font-bold text-center text-blue-600 mb-8">
      Seat Booking System
    </h1>

    <div v-if="loading" class="text-center text-gray-500">
      Loading seats...
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Left Section (L1-L30) -->
      <div class="flex flex-col space-y-6">
        <div
          v-for="(row, rowIndex) in chunkedSeats.left"
          :key="'left-' + rowIndex"
          class="grid grid-cols-5 gap-4"
        >
          <div
            v-for="seat in row"
            :key="seat.id"
            class="p-4 border rounded-lg flex flex-col items-center cursor-pointer"
            :class="getSeatClass(seat)"
            @click="toggleSeatSelection(seat)"
          >
            <span class="font-semibold text-lg mb-4">
              {{ seat.seat_number }}
            </span>

          </div>
        </div>
      </div>

      <!-- Center Section (C1-C30) -->
      <div class="flex flex-col space-y-6">
        <div
          v-for="(row, rowIndex) in chunkedSeats.center"
          :key="'center-' + rowIndex"
          class="grid grid-cols-5 gap-4"
        >
          <div
            v-for="seat in row"
            :key="seat.id"
            class="p-4 border rounded-lg flex flex-col items-center cursor-pointer"
            :class="getSeatClass(seat)"
            @click="toggleSeatSelection(seat)"
          >
            <span class="font-semibold text-lg mb-4">
              {{ seat.seat_number }}
            </span>

          </div>
        </div>
      </div>

      <!-- Right Section (R1-R30) -->
      <div class="flex flex-col space-y-6">
        <div
          v-for="(row, rowIndex) in chunkedSeats.right"
          :key="'right-' + rowIndex"
          class="grid grid-cols-5 gap-4"
        >
          <div
            v-for="seat in row"
            :key="seat.id"
            class="p-4 border rounded-lg flex flex-col items-center cursor-pointer"
            :class="getSeatClass(seat)"
            @click="toggleSeatSelection(seat)"
          >
            <span class="font-semibold text-lg mb-4">
              {{ seat.seat_number }}
            </span>

          </div>
        </div>
      </div>
    </div>

    <!-- Checkout Summary -->
    <div v-if="selectedSeats.length > 0" class="mt-8 border-t pt-8">
      <div class="bg-gray-50 rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Booking Summary</h2>
        
        <!-- Ticket Category Selection -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Ticket Category
          </label>
          <div class="flex gap-4">
            <button 
              @click="formData.ticket_category = 'student'"
              class="flex-1 py-3 px-4 rounded-lg border-2 transition-colors"
              :class="formData.ticket_category === 'student' 
                ? 'border-blue-500 bg-blue-50' 
                : 'border-gray-200 hover:border-gray-300'"
            >
              <div class="font-semibold">Student</div>
              <div class="text-sm text-gray-600">$16 per seat</div>
            </button>
            <button 
              @click="formData.ticket_category = 'public'"
              class="flex-1 py-3 px-4 rounded-lg border-2 transition-colors"
              :class="formData.ticket_category === 'public' 
                ? 'border-blue-500 bg-blue-50' 
                : 'border-gray-200 hover:border-gray-300'"
            >
              <div class="font-semibold">Public</div>
              <div class="text-sm text-gray-600">$20 per seat</div>
            </button>
          </div>
        </div>

        <!-- Selected Seats Summary -->
        <div class="space-y-4 mb-6">
          <div class="flex justify-between">
            <span>Selected Seats ({{ selectedSeats.length }}):</span>
            <span>{{ selectedSeats.map(seat => seat.seat_number).join(', ') }}</span>
          </div>
          
          <div class="flex justify-between font-bold text-lg border-t pt-4">
            <span>Total Amount:</span>
            <span>${{ totalAmount }}</span>
          </div>
        </div>

        <!-- Customer Information Form -->
        <form @submit.prevent="submitBooking" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Full Name
            </label>
            <input
              v-model="formData.customer_name"
              type="text"
              required
              class="w-full px-4 py-2 border rounded-lg"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Email
            </label>
            <input
              v-model="formData.email"
              type="email"
              required
              class="w-full px-4 py-2 border rounded-lg"
            >
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Phone
            </label>
            <input
              v-model="formData.phone"
              type="tel"
              required
              class="w-full px-4 py-2 border rounded-lg"
            >
          </div>

          <button
  type="button"
  @click="showPaymentDialog = true"
  class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg font-bold hover:bg-blue-700 transition"
  :disabled="processing || !formData.ticket_category"
>
  Proceed to Payment
</button>
        </form>

         <!-- Payment Dialog -->
    <!-- Payment Dialog -->
   <!-- Payment Dialog -->
   <div v-show="showPaymentDialog" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

      <!-- Dialog Position -->
      <div class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <!-- Dialog Panel -->
          <div class="relative transform overflow-hidden rounded-lg bg-white px-6 py-8 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <!-- Close Button -->
            <button 
              @click="closePaymentDialog"
              class="absolute right-4 top-4 text-gray-400 hover:text-gray-500"
              type="button"
            >
              <span class="sr-only">Close</span>
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Dialog Content -->
            <div class="text-center mb-6">
              <h3 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Payment</h3>
              <p class="text-gray-500">Total Amount: ${{ totalAmount }}</p>
            </div>

            <!-- Payment Method Selection -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Select Payment Method</label>
              <div class="grid grid-cols-3 gap-4">
                <button
                  v-for="method in paymentMethods"
                  :key="method.id"
                  @click="selectPaymentMethod(method.id)"
                  class="p-4 border rounded-lg text-center transition-colors"
                  :class="paymentData.method === method.id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'"
                >
                  <img :src="method.icon" :alt="method.name" class="h-8 w-auto mx-auto mb-2">
                  <div class="font-medium">{{ method.name }}</div>
                </button>
              </div>
            </div>

            <!-- Payment Forms -->
            <form @submit.prevent="submitBooking" class="space-y-6">
              <!-- Credit Card Form -->
              <div v-if="paymentData.method === 'credit-card'" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                  <input
                    v-model="paymentData.cardNumber"
                    type="text"
                    maxlength="19"
                    placeholder="1234 5678 9012 3456"
                    class="w-full px-4 py-2 border rounded-lg"
                    @input="formatCardNumber"
                  >
                  <span v-if="validationErrors.cardNumber" class="text-red-500 text-sm">{{ validationErrors.cardNumber }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input
                      v-model="paymentData.expiryDate"
                      type="text"
                      placeholder="MM/YY"
                      maxlength="5"
                      class="w-full px-4 py-2 border rounded-lg"
                      @input="formatExpiryDate"
                    >
                    <span v-if="validationErrors.expiryDate" class="text-red-500 text-sm">{{ validationErrors.expiryDate }}</span>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                    <input
                      v-model="paymentData.cvv"
                      type="password"
                      maxlength="3"
                      placeholder="123"
                      class="w-full px-4 py-2 border rounded-lg"
                    >
                    <span v-if="validationErrors.cvv" class="text-red-500 text-sm">{{ validationErrors.cvv }}</span>
                  </div>
                </div>
              </div>

              <!-- PayPal Form -->
              <div v-if="paymentData.method === 'paypal'" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">PayPal Email</label>
                  <input
                    v-model="paymentData.paypalEmail"
                    type="email"
                    placeholder="your@email.com"
                    class="w-full px-4 py-2 border rounded-lg"
                  >
                  <span v-if="validationErrors.paypalEmail" class="text-red-500 text-sm">{{ validationErrors.paypalEmail }}</span>
                </div>
              </div>

              <!-- Bank Transfer Form -->
              <div v-if="paymentData.method === 'bank-transfer'" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                  <input
                    v-model="paymentData.accountNumber"
                    type="text"
                    placeholder="0123456789"
                    class="w-full px-4 py-2 border rounded-lg"
                  >
                  <span v-if="validationErrors.accountNumber" class="text-red-500 text-sm">{{ validationErrors.accountNumber }}</span>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name</label>
                  <input
                    v-model="paymentData.bankName"
                    type="text"
                    placeholder="Your Bank Name"
                    class="w-full px-4 py-2 border rounded-lg"
                  >
                  <span v-if="validationErrors.bankName" class="text-red-500 text-sm">{{ validationErrors.bankName }}</span>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-4 mt-8">
                <button
                  type="button"
                  @click="closePaymentDialog"
                  class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-semibold transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="processing || !paymentData.method"
                  class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors disabled:opacity-50"
                >
                  <span v-if="processing">Processing...</span>
                  <span v-else>Pay ${{ totalAmount }}</span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <div 
      v-if="toast.show" 
      class="fixed bottom-4 right-4 z-50 min-w-[300px] transform transition-transform duration-300"
      :class="{
        'translate-y-0': toast.show,
        'translate-y-full': !toast.show
      }"
    >
      <div 
        class="rounded-lg shadow-lg p-4 flex items-center space-x-4"
        :class="{
          'bg-green-500 text-white': toast.type === 'success',
          'bg-red-500 text-white': toast.type === 'error'
        }"
      >
        <!-- Success/Error Icon -->
        <div v-if="toast.type === 'success'">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <div v-else>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>
        <p>{{ toast.message }}</p>
      </div>
    </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      seats: [],
      loading: true,
      selectedSeats: [],
      processing: false,
      formData: {
        customer_name: '',
        email: '',
        phone: '',
        ticket_category: '',
      },
      showPaymentDialog: false,
      paymentData: {
        method: '',
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        paypalEmail: '',
        accountNumber: '',
        bankName: ''
      },
      validationErrors: {},
      toast: {
        show: false,
        message: '',
        type: 'success'
      },
      paymentMethods: [
        {
          id: 'credit-card',
          name: 'Credit Card',
          icon: '/api/placeholder/32/32'
        },
        {
          id: 'paypal',
          name: 'PayPal',
          icon: '/api/placeholder/32/32'
        },
        {
          id: 'bank-transfer',
          name: 'Bank Transfer',
          icon: '/api/placeholder/32/32'
        }
      ],
      paymentData: {
        method: '',
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        paypalEmail: '',
        accountNumber: '',
        bankName: ''
      },
      validationErrors: {},
      processing: false
    };
    
  },
  computed: {
    totalAmount() {
      const pricePerSeat = this.formData.ticket_category === 'student' ? 16 : 20;
      return this.selectedSeats.length * pricePerSeat;
    },
    chunkedSeats() {
      const sections = {
        left: [],
        center: [],
        right: []
      };

      this.seats.forEach(seat => {
        const seatNumber = seat.seat_number;
        if (seatNumber.startsWith('L')) {
          sections.left.push(seat);
        } else if (seatNumber.startsWith('C')) {
          sections.center.push(seat);
        } else if (seatNumber.startsWith('R')) {
          sections.right.push(seat);
        }
      });

      return {
        left: this.chunkArray(sections.left, 5),
        center: this.chunkArray(sections.center, 5),
        right: this.chunkArray(sections.right, 5)
      };
    }
  },
  methods: {
    async fetchSeats() {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/seats`);
        this.seats = response.data.map((seat, index) => {
          let seatNumber;
          if (index < 30) {
            seatNumber = `L${index + 1}`;
          } else if (index < 60) {
            seatNumber = `C${index - 29}`;
          } else {
            seatNumber = `R${index - 59}`;
          }
          return {
            ...seat,
            seat_number: seatNumber
          };
        });
        this.loading = false;
      } catch (error) {
        this.showToast('Failed to fetch seats. Please refresh the page.', 'error');
        console.error('Error fetching seats:', error);
      }
    },
    getSeatClass(seat) {
      if (seat.is_booked) {
        return 'bg-red-300 border-red-400';
      }
      if (this.selectedSeats.includes(seat)) {
        return 'bg-blue-300 border-blue-400';
      }
      return 'bg-green-100 border-green-400';
    },
    getButtonClass(seat) {
      if (seat.is_booked) {
        return 'bg-red-500';
      }
      if (this.selectedSeats.includes(seat)) {
        return 'bg-blue-500';
      }
      return 'bg-green-500 hover:bg-green-600';
    },
    getSeatStatus(seat) {
      if (seat.is_booked) {
        return 'Booked';
      }
      if (this.selectedSeats.includes(seat)) {
        return 'Selected';
      }
      return 'Available';
    },
    toggleSeatSelection(seat) {
      // Don't allow selection of booked seats
      if (seat.is_booked) return;
      
      // Find if seat is already selected
      const index = this.selectedSeats.findIndex(selectedSeat => selectedSeat.seat_number === seat.seat_number);
      
      if (index === -1) {
        // Seat is not selected, add it to selections
        this.selectedSeats.push(seat);
      } else {
        // Seat is already selected, remove it
        this.selectedSeats.splice(index, 1);
      }
    },
    chunkArray(array, size) {
      const chunked = [];
      for (let i = 0; i < array.length; i += size) {
        chunked.push(array.slice(i, i + size));
      }
      return chunked;
    },
    async submitBooking() {
      if (this.processing || !this.formData.ticket_category) return;

      this.processing = true;
      try {
        const response = await axios.post(`${import.meta.env.VITE_API_URL}/api/bookings`, {
          ...this.formData,
          seat_ids: this.selectedSeats.map(seat => seat.id)
        });

        alert('Booking successful!');
        
        // Reset form and selection
        this.selectedSeats = [];
        this.formData = {
          customer_name: '',
          email: '',
          phone: '',
          ticket_category: ''
        };

        // Refresh seats data
        this.fetchSeats();
      } catch (error) {
        alert(error.response?.data?.message || 'An error occurred during booking');
      } finally {
        this.processing = false;
      }
    },
    formatCardNumber(e) {
      let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
      let formattedValue = '';
      for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
          formattedValue += ' ';
        }
        formattedValue += value[i];
      }
      this.paymentData.cardNumber = formattedValue;
    },
    formatExpiryDate(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2);
      }
      this.paymentData.expiryDate = value;
    },
    showToast(message, type = 'success') {
      this.toast = {
        show: true,
        message,
        type
      };
      setTimeout(() => {
        this.toast.show = false;
      }, 3000);
    },
    showPaymentModal() {
      this.showPaymentDialog = true;
      document.body.style.overflow = 'hidden'; // Prevent background scrolling
    },
    closePaymentDialog() {
      this.showPaymentDialog = false;
      document.body.style.overflow = ''; // Restore scrolling
      this.paymentData = {
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        nameOnCard: ''
      };
    },
    async submitPayment() {
      if (this.processing || !this.validatePayment()) return;

      this.processing = true;
      try {
        // First, create the booking
        const bookingData = {
          customer_name: this.formData.customer_name,
          email: this.formData.email,
          phone: this.formData.phone,
          ticket_category: this.formData.ticket_category,
          seat_ids: this.selectedSeats.map(seat => seat.id),
          payment_method: this.paymentData.method,
          amount: this.totalAmount,
          payment_details: this.getPaymentDetails()
        };

        // Make the booking API call
        const response = await axios.post(
          `${import.meta.env.VITE_API_URL}/api/bookings`, 
          bookingData
        );

        if (response.data.success) {
          // Update the local seats data to reflect the booking
          this.updateBookedSeats();
          this.showToast('Booking successful! Check your email for confirmation.', 'success');
          this.resetForm();
        } else {
          throw new Error(response.data.message || 'Booking failed');
        }
      } catch (error) {
        this.showToast(error.message || 'Booking failed. Please try again.', 'error');
        console.error('Booking error:', error);
      } finally {
        this.processing = false;
        this.closePaymentDialog();
      }
    },

    getPaymentDetails() {
      switch (this.paymentData.method) {
        case 'credit-card':
          return {
            card_number: this.paymentData.cardNumber.replace(/\s/g, ''),
            expiry_date: this.paymentData.expiryDate,
            cvv: this.paymentData.cvv
          };
        case 'paypal':
          return {
            email: this.paymentData.paypalEmail
          };
        case 'bank-transfer':
          return {
            account_number: this.paymentData.accountNumber,
            bank_name: this.paymentData.bankName
          };
        default:
          return {};
      }
    },

    validatePaymentData() {
      const { cardNumber, expiryDate, cvv, nameOnCard } = this.paymentData;
      
      if (!cardNumber || cardNumber.replace(/\s/g, '').length !== 16) {
        this.showToast('Please enter a valid card number', 'error');
        return false;
      }

      if (!expiryDate || !expiryDate.includes('/')) {
        this.showToast('Please enter a valid expiry date', 'error');
        return false;
      }

      if (!cvv || cvv.length !== 3) {
        this.showToast('Please enter a valid CVV', 'error');
        return false;
      }

      if (!nameOnCard || nameOnCard.length < 3) {
        this.showToast('Please enter the name on card', 'error');
        return false;
      }

      return true;
    },
    selectPaymentMethod(methodId) {
      this.paymentData.method = methodId;
      this.validationErrors = {};
    },

    validatePayment() {
      this.validationErrors = {};
      let isValid = true;

      switch (this.paymentData.method) {
        case 'credit-card':
          if (!this.paymentData.cardNumber || this.paymentData.cardNumber.replace(/\s/g, '').length !== 16) {
            this.validationErrors.cardNumber = 'Please enter a valid card number';
            isValid = false;
          }
          if (!this.paymentData.expiryDate || !this.paymentData.expiryDate.includes('/')) {
            this.validationErrors.expiryDate = 'Please enter a valid expiry date';
            isValid = false;
          }
          if (!this.paymentData.cvv || this.paymentData.cvv.length !== 3) {
            this.validationErrors.cvv = 'Please enter a valid CVV';
            isValid = false;
          }
          break;

        case 'paypal':
          if (!this.paymentData.paypalEmail || !this.paymentData.paypalEmail.includes('@')) {
            this.validationErrors.paypalEmail = 'Please enter a valid email address';
            isValid = false;
          }
          break;

        case 'bank-transfer':
          if (!this.paymentData.accountNumber || this.paymentData.accountNumber.length < 8) {
            this.validationErrors.accountNumber = 'Please enter a valid account number';
            isValid = false;
          }
          if (!this.paymentData.bankName) {
            this.validationErrors.bankName = 'Please enter your bank name';
            isValid = false;
          }
          break;

        default:
          this.validationErrors.method = 'Please select a payment method';
          isValid = false;
      }

      return isValid;
    },

    async submitPayment() {
      if (this.processing) return;
      if (!this.validatePayment()) return;

      this.processing = true;
      try {
        // Simulate payment processing
        await new Promise(resolve => setTimeout(resolve, 1500));

        // Mock successful booking
        const selectedSeatIds = this.selectedSeats.map(seat => seat.id);
        
        // Update local seats data to show as booked
        this.seats = this.seats.map(seat => {
          if (selectedSeatIds.includes(seat.id)) {
            return { ...seat, is_booked: true };
          }
          return seat;
        });

        this.showToast('Payment successful! Your seats have been booked.', 'success');
        this.closePaymentDialog();
        
        // Reset selections and form data
        this.selectedSeats = [];
        this.formData = {
          customer_name: '',
          email: '',
          phone: '',
          ticket_category: ''
        };
      } catch (error) {
        this.showToast('Payment failed. Please try again.', 'error');
      } finally {
        this.processing = false;
      }
    },
    updateBookedSeats() {
      const bookedSeatIds = this.selectedSeats.map(seat => seat.id);
      this.seats = this.seats.map(seat => ({
        ...seat,
        is_booked: seat.is_booked || bookedSeatIds.includes(seat.id)
      }));
    },

    formatCardNumber(e) {
      let value = e.target.value.replace(/\s/g, '').replace(/\D/g, '');
      let formattedValue = '';
      for (let i = 0; i < value.length; i++) {
        if (i > 0 && i % 4 === 0) {
          formattedValue += ' ';
        }
        formattedValue += value[i];
      }
      this.paymentData.cardNumber = formattedValue;
    },

    formatExpiryDate(e) {
      let value = e.target.value.replace(/\D/g, '');
      if (value.length >= 2) {
        value = value.slice(0, 2) + '/' + value.slice(2);
      }
      this.paymentData.expiryDate = value;
    },

    closePaymentDialog() {
      this.showPaymentDialog = false;
      this.paymentData = {
        method: '',
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        paypalEmail: '',
        accountNumber: '',
        bankName: ''
      };
      this.validationErrors = {};
      document.body.style.overflow = '';
    },
    resetForm() {
      this.selectedSeats = [];
      this.formData = {
        customer_name: '',
        email: '',
        phone: '',
        ticket_category: ''
      };
      this.paymentData = {
        method: '',
        cardNumber: '',
        expiryDate: '',
        cvv: '',
        paypalEmail: '',
        accountNumber: '',
        bankName: ''
      };
    },

  },
  created() {
    this.fetchSeats();
  }
  
};
</script>

<style scoped>
.fixed {
  position: fixed;
}

.inset-0 {
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

/* Add transition classes */
.dialog-enter-active,
.dialog-leave-active {
  transition: opacity 0.3s ease;
}

.dialog-enter-from,
.dialog-leave-to {
  opacity: 0;
}

.toast-enter-active,
.toast-leave-active {
  transition: transform 0.3s ease;
}

.toast-enter-from,
.toast-leave-to {
  transform: translateY(100%);
}
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>