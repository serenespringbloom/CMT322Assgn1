<template>
  <div class="min-h-screen bg-[#FFC8DD] py-16">
    <div class="max-w-2xl mx-auto px-4">
      <!-- Header -->
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-[#554149] tracking-[1rem] font-['Plus_Jakarta_Sans'] uppercase mb-4">
          Ticket Refund
        </h1>
        <div class="w-24 h-1 bg-[#dcc39c] mx-auto mb-6"></div>
        <p class="text-gray-600">
          Please fill in the details below to request a ticket refund
        </p>
      </div>

      <!-- Refund Form -->
      <div class="bg-white rounded-2xl shadow-lg p-8">
        <form @submit.prevent="submitRefund" class="space-y-6">
          <!-- Purchase ID Field -->
          <div>
            <label 
              for="pid" 
              class="block text-[#554149] font-medium mb-2"
            >
              Purchase ID
            </label>
            <input
              type="text"
              id="pid"
              v-model="refundRequest.pid"
              placeholder="Enter your purchase ID"
              class="w-full px-4 py-3 rounded-lg border focus:ring-2 focus:ring-[#dcc39c] 
                     focus:border-transparent transition-colors duration-200"
              required
            />
          </div>

          <!-- Reason Field -->
          <div>
            <label 
              for="reason" 
              class="block text-[#554149] font-medium mb-2"
            >
              Reason for Refund
            </label>
            <textarea
              id="reason"
              v-model="refundRequest.reason"
              rows="4"
              placeholder="Please explain why you're requesting a refund"
              class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 
                     focus:ring-[#dcc39c] focus:border-transparent transition-colors 
                     duration-200 resize-none"
              required
            ></textarea>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="!refundRequest.pid || !refundRequest.reason"
            class="w-full py-4 bg-gradient-to-r from-[#a48e69] to-[#dcc39c] text-white 
                   rounded-lg font-medium transition-all duration-300 transform 
                   hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 
                   disabled:cursor-not-allowed disabled:hover:translate-y-0"
          >
            Submit Refund Request
          </button>

          <!-- Status Message -->
          <div 
            v-if="message" 
            :class="[
              'p-4 rounded-lg text-center',
              success ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'
            ]"
          >
            <div class="flex items-center justify-center gap-2">
              <svg 
                v-if="success"
                class="h-5 w-5" 
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 20 20" 
                fill="currentColor"
              >
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              <svg 
                v-else
                class="h-5 w-5" 
                xmlns="http://www.w3.org/2000/svg" 
                viewBox="0 0 20 20" 
                fill="currentColor"
              >
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
              {{ message }}
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const refundRequest = ref({
  pid: "",
  reason: "",
});

const message = ref("");
const success = ref(false);

const submitRefund = async () => {
  if (!refundRequest.value.pid || !refundRequest.value.reason) {
    message.value = "All fields are required!";
    success.value = false;
    return;
  }

  try {
    const formData = new FormData();
    formData.append('transaction_id', refundRequest.value.pid);
    formData.append('reason', refundRequest.value.reason);
    
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/user/refunds`, {
      method: 'POST',
      body: formData
    });

    const data = await response.json();

    if (response.ok) {
      message.value = `Refund request for Purchase ID ${refundRequest.value.pid} submitted successfully!`;
      success.value = true;
      // Clear the form
      refundRequest.value.pid = "";
      refundRequest.value.reason = "";
    } else {
      throw new Error(data.message || 'Failed to submit refund request');
    }
  } catch (error) {
    console.error('Error submitting refund:', error);
    message.value = "Failed to submit refund request. Please try again.";
    success.value = false;
  }
};
</script>
  