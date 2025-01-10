<template>
    <div class="refund-page">
      <div id="title"><h1>Submit a Refund Request</h1></div>
  
      <!-- Order Summary Section -->
      <div class="refund-form">
        <h2>REFUND FORM</h2>
        <p class="prompt">Enter Details Below:</p>
        <div class="refund-inputs">
          <input
            type="text"
            placeholder="Enter your purchase ID"
            v-model="refundRequest.pid"
            required
            @input="validatePid"
            :class="{ invalid: !isPidValid && refundRequest.pid }"
          />
          <span v-if="refundRequest.pid && !isPidValid" class="error-message">
            Please enter a valid purchase ID.
          </span>
  
          <input
            type="text"
            placeholder="Enter reason for refund"
            v-model="refundRequest.reason"
            required
          />
        </div>
  
        <button
          @click="submitRefund"
          class="submit-button"
          :disabled="!isPidValid || !refundRequest.reason"
        >
          Submit Refund Request
        </button>
  
        <p v-if="message" :class="{ success: success, error: !success }">{{ message }}</p>
      </div>
    </div>
  </template>
  
  <script>
    import axios from 'axios';
  export default {
    data() {
      return {
        refundRequest: {
          pid: "",
          reason: "",
        },
        isPidValid: false,
        message: "",
        success: false,
      };
    },
    methods: {
      async validatePid() {
  try {
    const response = await axios.get(`http://127.0.0.1:8000/api/validate-pid/${this.refundRequest.pid}`);
    this.isPidValid = response.data.isValid;
    if (this.isPidValid) {
      alert('PID is valid!');
    } else {
      alert('PID is invalid!');
    }
  } catch (error) {
    console.error('Error validating PID:', error);
    alert('An error occurred while validating the PID.');
  }
},
      async submitRefund() {
        if (!this.refundRequest.pid || !this.refundRequest.reason) {
          this.message = "All fields are required!";
          this.success = false;
          return;
        }
  
        if (!this.isPidValid) {
          this.message = "Invalid purchase ID.";
          this.success = false;
          return;
        }
        const formData = new FormData();
        formData.append('transaction_id', this.refundRequest.pid);
        formData.append('reason', this.refundRequest.reason);
        
        const response = await axios.post(`${import.meta.env.VITE_API_URL}/api/user/refunds`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          });
          console.log('File uploaded successfully:', response.data);
        // Simulate sending refund request to the backend
        this.message = `Refund request for Purchase ID ${this.refundRequest.pid} submitted successfully!`;
        this.success = true;
  
        // Clear the form
        this.refundRequest.pid = "";
        this.refundRequest.reason = "";
        this.isPidValid = false;
      },
    },
  };
  </script>
  
  <style scoped>
  #title {
    text-align: center;
    letter-spacing: .9rem;
    font-family: "Plus Jakarta Sans", serif;
    margin-top: 2rem;
  }

  #title h1 {
    font-size: 2.3rem;
    letter-spacing: 2rem;
    margin-bottom: 1.5rem;
    color: #554149;
    text-transform: uppercase;
  }

  .refund-page {
    margin: 50px 20%;
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

  .refund-form {
  flex: 1;
  background: linear-gradient(135deg, #fff1f5, #ffe5ed);
  padding: 25px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  text-align: center;
  max-height: 450px;
  animation: fadeIn 0.3s ease-in-out;
  font-family: "Plus Jakarta Sans", sans-serif;
}

.refund-form h2 {
  font-size: 1.8rem;
  font-weight: bold;
  color: #554149;
  margin-bottom: 1rem;
  text-transform: uppercase;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.refund-form p {
  font-size: 1.2rem;
  margin: 10px 0;
  color: #6b4f57;
  font-weight: 500;
}

.refund-form p span {
  font-weight: bold;
  color: #a48e69;
}

.refund-form .prompt {
  font-size: 1.6rem;
  font-weight: bold;
  color: #554149;
  margin: 1rem 0;
  padding: 0.5rem 0;
  background: linear-gradient(135deg, #ffe8e8, #ffc8dd);
  border-radius: 10px;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.refund-inputs {
  font-family: "Plus Jakarta Sans", sans-serif;
  margin: 20px 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.refund-inputs input {
  padding: 12px;
  border-radius: 10px;
  border: 2px solid #ccc;
  font-size: 1rem;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

.refund-inputs input:focus {
  border-color: #ffc8dd;
  box-shadow: 0 0 8px rgba(255, 200, 221, 0.5);
}

.refund-inputs input.invalid {
  border-color: red;
}

.submit-button {
  background: linear-gradient(135deg, #6bcf6b, #4caf50);
  color: white;
  border: none;
  border-radius: 25px;
  padding: 12px 20px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
  transition: all 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.submit-button:disabled {
  background-color: #a5d6a7;
  cursor: not-allowed;
  box-shadow: none;
}

.submit-button:hover:enabled {
  background: linear-gradient(135deg, #4caf50, #6bcf6b);
  transform: translateY(-3px);
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
  </style>
  