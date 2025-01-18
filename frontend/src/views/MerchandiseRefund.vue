<template>
  <div class="refund-container">
    <div class="refund-form">
      <h1>Request a Refund</h1>
      <p class="instruction">Please enter your purchase ID to request a refund</p>

      <div class="form-group">
        <label for="orderId">Purchase ID</label>
        <input 
          type="text" 
          id="orderId" 
          v-model="orderId"
          placeholder="Enter your Purchase ID (e.g., 000123)"
          @input="validateOrderId"
        />
        <span class="error-message" v-if="orderIdError">{{ orderIdError }}</span>
      </div>

      <div class="form-group">
        <label for="reason">Reason for Refund</label>
        <textarea 
          id="reason" 
          v-model="reason"
          rows="4"
          placeholder="Please explain why you're requesting a refund"
        ></textarea>
        <span class="error-message" v-if="reasonError">{{ reasonError }}</span>
      </div>

      <button 
        class="submit-btn" 
        @click="submitRefund"
        :disabled="!isFormValid || isSubmitting"
      >
        {{ isSubmitting ? 'Submitting...' : 'Submit Refund Request' }}
      </button>

      <!-- Success Message -->
      <div v-if="successMessage" class="success-message">
        {{ successMessage }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const orderId = ref('');
const reason = ref('');
const orderIdError = ref('');
const reasonError = ref('');
const isSubmitting = ref(false);
const successMessage = ref('');

const validateOrderId = () => {
  orderIdError.value = '';
  if (!orderId.value) {
    orderIdError.value = 'Purchase ID is required';
  } else if (!/^\d+$/.test(orderId.value)) {
    orderIdError.value = 'Purchase ID must contain only numbers';
  }
};

const validateReason = () => {
  reasonError.value = '';
  if (!reason.value.trim()) {
    reasonError.value = 'Please provide a reason for the refund';
    return false;
  }
  if (reason.value.length < 10) {
    reasonError.value = 'Please provide a more detailed reason';
    return false;
  }
  return true;
};

const isFormValid = computed(() => {
  return orderId.value && 
         reason.value.trim().length >= 10 && 
         !orderIdError.value;
});

const submitRefund = async () => {
  if (!validateReason()) return;
  
  isSubmitting.value = true;
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/merchandise/refunds`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        orderId: orderId.value,
        reason: reason.value
      })
    });

    const data = await response.json();

    if (data.success) {
      successMessage.value = 'Your refund request has been submitted successfully!';
      // Reset form
      orderId.value = '';
      reason.value = '';
      // Redirect after 3 seconds
      setTimeout(() => {
        router.push('/');
      }, 3000);
    } else {
      throw new Error(data.message || 'Failed to submit refund request');
    }
  } catch (error) {
    alert(error.message);
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped>
.refund-container {
  max-width: 600px;
  margin: 2rem auto;
  padding: 2rem;
}

.refund-form {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #554149;
  text-align: center;
  margin-bottom: 1rem;
}

.instruction {
  text-align: center;
  color: #6c757d;
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  color: #554149;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

input, textarea {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #dcc39c;
  border-radius: 5px;
  font-size: 1rem;
  transition: border-color 0.3s ease;
}

input:focus, textarea:focus {
  outline: none;
  border-color: #a48e69;
}

textarea {
  resize: vertical;
  min-height: 100px;
}

.error-message {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
  display: block;
}

.submit-btn {
  width: 100%;
  padding: 1rem;
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: white;
  border: none;
  border-radius: 5px;
  font-size: 1rem;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.submit-btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.success-message {
  margin-top: 1rem;
  padding: 1rem;
  background-color: #d4edda;
  color: #155724;
  border-radius: 5px;
  text-align: center;
}

@media (max-width: 768px) {
  .refund-container {
    margin: 1rem;
    padding: 1rem;
  }
}
</style> 