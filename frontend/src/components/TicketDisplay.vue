<template>
  <!-- Overlay -->
  <div 
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 sm:p-6"
    @click="closeModal"
  >
    <!-- Modal -->
    <div 
      class="max-w-4xl w-full bg-white rounded-2xl shadow-2xl overflow-y-auto max-h-[90vh] relative"
      @click.stop
      ref="ticketContainer"
    >
      <!-- Close Button -->
      <button 
        @click="closeModal"
        class="absolute top-4 right-4 z-10 p-2 rounded-full hover:bg-gray-100 transition-colors"
      >
        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <!-- Ticket Content -->
      <div class="ticket bg-gradient-to-br from-white to-gray-50">
        <!-- Ticket Header -->
        <div class="relative px-6 py-8 text-center border-b border-gray-200">
          <h1 class="text-3xl sm:text-4xl font-bold text-[#2c3e50]">E-TICKET</h1>
          <div class="absolute top-4 left-4 bg-gray-50 px-3 py-1.5 rounded-lg">
            <span class="font-mono text-sm sm:text-base text-[#2c3e50]">
              {{ ticketData.ticketId }}
            </span>
          </div>
        </div>

        <!-- Ticket Body -->
        <div class="p-6 space-y-8">
          <!-- Customer Info -->
          <div class="bg-gray-50/50 rounded-xl p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-600">Name:</label>
                <p class="text-[#2c3e50]">{{ ticketData.transaction.cust_name }}</p>
              </div>
              <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-600">Email:</label>
                <p class="text-[#2c3e50] break-all">{{ ticketData.transaction.cust_email }}</p>
              </div>
              <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-600">Phone:</label>
                <p class="text-[#2c3e50]">{{ ticketData.transaction.cust_phone }}</p>
              </div>
            </div>
          </div>

          <!-- Seat Details -->
          <div class="bg-gray-50/50 rounded-xl p-4 sm:p-6">
            <h2 class="text-xl font-semibold text-[#2c3e50] mb-4">Seat Details</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
              <div v-if="ticketData.seats.left?.length" class="space-y-2">
                <h3 class="font-semibold text-[#2c3e50]">Left Section</h3>
                <div class="bg-white p-3 rounded-lg font-mono text-sm">
                  {{ ticketData.seats.left.join(', ') }}
                </div>
              </div>
              <div v-if="ticketData.seats.center?.length" class="space-y-2">
                <h3 class="font-semibold text-[#2c3e50]">Center Section</h3>
                <div class="bg-white p-3 rounded-lg font-mono text-sm">
                  {{ ticketData.seats.center.join(', ') }}
                </div>
              </div>
              <div v-if="ticketData.seats.right?.length" class="space-y-2">
                <h3 class="font-semibold text-[#2c3e50]">Right Section</h3>
                <div class="bg-white p-3 rounded-lg font-mono text-sm">
                  {{ ticketData.seats.right.join(', ') }}
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Info -->
          <div class="bg-gray-50/50 rounded-xl p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-600">Total Amount:</label>
                <p class="text-[#2c3e50]">RM {{ ticketData.transaction.total_price }}</p>
              </div>
              <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-600">Payment Method:</label>
                <p class="text-[#2c3e50]">{{ ticketData.transaction.payment_method }}</p>
              </div>
              <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-600">Status:</label>
                <span :class="[
                  'px-3 py-1 rounded-full text-sm font-semibold',
                  ticketData.transaction.status.toLowerCase() === 'pending' 
                    ? 'bg-yellow-100 text-yellow-800' 
                    : 'bg-green-100 text-green-800'
                ]">
                  {{ ticketData.transaction.status }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Ticket Footer -->
        <div class="px-6 py-4 bg-gray-50 text-center space-y-2">
          <div class="qr-code">
            <!-- QR code placeholder -->
          </div>
          <div class="text-sm text-gray-500">
            Generated on: {{ new Date().toLocaleString() }}
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-center gap-4 p-6 bg-gray-50 border-t border-gray-200">
          <button 
            @click="downloadTicket"
            class="px-6 py-2.5 bg-gradient-to-r from-[#a48e69] to-[#dcc39c] text-white 
                   font-semibold rounded-lg hover:opacity-90 transition-opacity"
          >
            Download Ticket
          </button>
          <button 
            @click="printTicket"
            class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg 
                   hover:bg-gray-200 transition-colors"
          >
            Print Ticket
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import html2canvas from 'html2canvas';
import { useRouter } from 'vue-router';

const router = useRouter();
const ticketContainer = ref(null);

const props = defineProps({
  ticketData: {
    type: Object,
    required: true
  },
  onClose: {
    type: Function,
    required: true
  }
});

// Close modal when clicking escape key
const handleEscape = (e) => {
  if (e.key === 'Escape') {
    closeModal();
  }
};

onMounted(() => {
  document.addEventListener('keydown', handleEscape);
  // Prevent body scroll when modal is open
  document.body.style.overflow = 'hidden';
});

onBeforeUnmount(() => {
  document.removeEventListener('keydown', handleEscape);
  // Restore body scroll when modal is closed
  document.body.style.overflow = 'auto';
});

const closeModal = () => {
  props.onClose();
};

const downloadTicket = async () => {
  try {
    const ticketElement = ticketContainer.value.querySelector('.ticket');
    const canvas = await html2canvas(ticketElement);
    const link = document.createElement('a');
    link.download = `ticket-${props.ticketData.ticketId}.png`;
    link.href = canvas.toDataURL();
    link.click();
  } catch (error) {
    console.error('Error downloading ticket:', error);
  }
};

const printTicket = () => {
  window.print();
};
</script>

<style scoped>
@media print {
  .ticket-actions {
    display: none;
  }
}

/* Add smooth entrance animation */
.fixed {
  animation: modalFade 0.3s ease-out;
}

@keyframes modalFade {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
  