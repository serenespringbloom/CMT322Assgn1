<template>
    <div class="refund-page">
      <h1>Submit a Refund Request</h1>
  
      <!-- Refund Request Form -->
      <form @submit.prevent="submitRefund">
        <label>Ticket ID / Purchase Order ID:</label>
        <input
          v-model="refundRequest.ticketId"
          type="text"
          placeholder="Enter Ticket ID"
          required
        />
  
        <label>Reason for Refund:</label>
        <textarea
          v-model="refundRequest.reason"
          placeholder="Enter reason for refund"
          required
        ></textarea>
  
        <button type="submit">Submit Refund Request</button>
      </form>
  
      <p v-if="message" :class="{ success: success, error: !success }">{{ message }}</p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        refundRequest: {
          ticketId: "",
          reason: "",
        },
        message: "",
        success: false,
      };
    },
    methods: {
      // Submit Refund Request
      submitRefund() {
        const { ticketId, reason } = this.refundRequest;
  
        if (!ticketId || !reason) {
          this.message = "All fields are required!";
          this.success = false;
          return;
        }
  
        // Send refund request to the backend (dummy simulation for now)
        this.message = `Refund request for Ticket ID ${ticketId} submitted successfully!`;
        this.success = true;
  
        // Clear the form
        this.refundRequest.ticketId = "";
        this.refundRequest.reason = "";
      },
    },
  };
  </script>
  
  <style scoped>
  .refund-page {
    max-width: 500px;
    margin: 50px auto;
    font-family: Arial, sans-serif;
  }
  
  form {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
  }
  
  input,
  textarea {
    margin-bottom: 10px;
    padding: 8px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
  }
  
  button {
    background-color: #007bff;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 5px;
  }
  
  button:hover {
    background-color: #0056b3;
  }
  
  .success {
    color: green;
    font-weight: bold;
  }
  
  .error {
    color: red;
    font-weight: bold;
  }
  </style>
  