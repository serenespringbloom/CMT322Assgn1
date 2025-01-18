<template>
  <div class="min-h-screen bg-[#FFC8DD] py-16">
    <div class="max-w-2xl mx-auto px-4">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-[#554149] tracking-[1rem] font-['Plus_Jakarta_Sans'] uppercase mb-4">
          Request a Refund
        </h1>
        <div class="w-24 h-1 bg-[#dcc39c] mx-auto mb-6"></div>
        <p class="text-gray-600">
          Please enter your merchandise order details to request a refund
        </p>
      </div>

      <!-- Refund Form -->
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <!-- Order ID Field -->
        <div class="mb-6">
          <label 
            for="orderId" 
            class="block text-[#554149] font-medium mb-2"
          >
            Order ID
          </label>
          <input 
            type="text" 
            id="orderId" 
            v-model="orderId"
            placeholder="Enter your Purchase ID (e.g., 000123)"
            @input="validateOrderId"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#dcc39c] 
                   focus:ring-2 focus:ring-[#dcc39c] focus:ring-opacity-50 transition-colors
                   placeholder-gray-400"
            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': orderIdError }"
          />
          <p v-if="orderIdError" class="mt-2 text-red-500 text-sm">
            {{ orderIdError }}
          </p>
        </div>

        <!-- Reason Field -->
        <div class="mb-8">
          <label 
            for="reason" 
            class="block text-[#554149] font-medium mb-2"
          >
            Reason for Refund
          </label>
          <textarea 
            id="reason" 
            v-model="reason"
            rows="4"
            placeholder="Please explain why you're requesting a refund"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-[#dcc39c] 
                   focus:ring-2 focus:ring-[#dcc39c] focus:ring-opacity-50 transition-colors
                   placeholder-gray-400 resize-none"
            :class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': reasonError }"
          ></textarea>
          <p v-if="reasonError" class="mt-2 text-red-500 text-sm">
            {{ reasonError }}
          </p>
        </div>

        <!-- Submit Button -->
        <button 
          @click="submitRefund"
          :disabled="!isFormValid || isSubmitting"
          class="w-full py-4 bg-gradient-to-r from-[#a48e69] to-[#dcc39c] text-white rounded-lg
                 font-medium transition-all duration-300 transform hover:-translate-y-0.5
                 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed
                 disabled:hover:translate-y-0"
        >
          <span v-if="isSubmitting" class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
          </span>
          <span v-else>
            Submit Refund Request
          </span>
        </button>

        <!-- Success Message -->
        <div 
          v-if="successMessage" 
          class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg"
        >
          <div class="flex items-center gap-3">
            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="text-green-700">
              {{ successMessage }}
            </p>
          </div>
        </div>
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