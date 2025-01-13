<template>
    <div>
      <h1>Bookings</h1>
      <table border="1">
        <thead>
          <tr>
            <th>ID</th>
            <th>Customer Details</th>
            <th>Created At</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="booking in bookings" :key="booking.id">
            <td>{{ booking.id }}</td>
            <td>{{ booking.customer_name }}</td>
            <td>{{ booking.created_at }}</td>
            <td>{{ booking.refunded_at ? 'Refunded' : 'Active' }}</td>
            <td>
              <button
                v-if="!booking.refunded_at"
                @click="refundBooking(booking.id)"
                :disabled="loading[booking.id]"
              >
                {{ loading[booking.id] ? 'Processing...' : 'Refund' }}
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from "axios";
  
  export default {
    data() {
      return {
        bookings: [], // List of bookings
        loading: {}, // Tracks loading state for each refund request
      };
    },
    methods: {
      async fetchBookings() {
        try {
          const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/bookings`); // Adjust endpoint as needed
          this.bookings = response.data;
        } catch (error) {
          console.error("Error fetching bookings:", error);
        }
      },
      async refundBooking(bookingId) {
        if (!confirm("Are you sure you want to refund this booking?")) {
          return;
        }
  
       
  
        try {
          const response=await axios.post(`${import.meta.env.VITE_API_URL}/api/refbook/${bookingId}`);
          // Update the refunded status for the booking locally
          const booking = this.bookings.find((b) => b.id === bookingId);
          if (booking) {
            booking.refunded_at = new Date().toISOString(); // Update refunded_at
          }
          alert("The booking has been successfully refunded!");
        } catch (error) {
          console.error("Error processing refund:", error);
          alert("Failed to refund the booking. Please try again.");
        } finally {
          this.$set(this.loading, bookingId, false); // Reset loading state
        }
      },
    },
    mounted() {
      this.fetchBookings(); // Fetch bookings when the component is mounted
    },
  };
  </script>
  
  <style scoped>
  /* Add some basic styling */
  button[disabled] {
    opacity: 0.6;
    cursor: not-allowed;
  }
  </style>
  