<template>
    <div class="ticket-container" ref="ticketContainer">
      <div class="ticket">
        <div class="ticket-header">
          <h1>E-TICKET</h1>
          <div class="ticket-id">{{ ticketData.ticketId }}</div>
        </div>
  
        <div class="ticket-body">
          <div class="customer-info">
            <div class="info-group">
              <label>Name:</label>
              <span>{{ ticketData.transaction.cust_name }}</span>
            </div>
            <div class="info-group">
              <label>Email:</label>
              <span>{{ ticketData.transaction.cust_email }}</span>
            </div>
            <div class="info-group">
              <label>Phone:</label>
              <span>{{ ticketData.transaction.cust_phone }}</span>
            </div>
          </div>
  
          <div class="seat-info">
            <h2>Seat Details</h2>
            <div class="seat-sections">
              <div v-if="ticketData.seats.left?.length" class="section">
                <h3>Left Section</h3>
                <div class="seat-list">{{ ticketData.seats.left.join(', ') }}</div>
              </div>
              <div v-if="ticketData.seats.center?.length" class="section">
                <h3>Center Section</h3>
                <div class="seat-list">{{ ticketData.seats.center.join(', ') }}</div>
              </div>
              <div v-if="ticketData.seats.right?.length" class="section">
                <h3>Right Section</h3>
                <div class="seat-list">{{ ticketData.seats.right.join(', ') }}</div>
              </div>
            </div>
          </div>
  
          <div class="payment-info">
            <div class="info-group">
              <label>Total Amount:</label>
              <span>RM {{ ticketData.transaction.total_price }}</span>
            </div>
            <div class="info-group">
              <label>Payment Method:</label>
              <span>{{ ticketData.transaction.payment_method }}</span>
            </div>
            <div class="info-group">
              <label>Status:</label>
              <span :class="['status', ticketData.transaction.status.toLowerCase()]">
                {{ ticketData.transaction.status }}
              </span>
            </div>
          </div>
        </div>
  
        <div class="ticket-footer">
          <div class="qr-code">
            <!-- You can add a QR code library to generate one here -->
          </div>
          <div class="timestamp">
            Generated on: {{ new Date().toLocaleString() }}
          </div>
        </div>
      </div>
  
      <div class="ticket-actions">
        <button @click="downloadTicket" class="download-btn">
          Download Ticket
        </button>
        <button @click="printTicket" class="print-btn">
          Print Ticket
        </button>
        <button @click="close" class="print-btn">
          Close
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import html2canvas from 'html2canvas';
  import { useRouter } from 'vue-router';
  const router = useRouter();
  const props = defineProps({
    ticketData: {
      type: Object,
      required: true
    }
  });
  
  const ticketContainer = ref(null);
  
  const downloadTicket = async () => {
    try {
      const canvas = await html2canvas(ticketContainer.value.querySelector('.ticket'));
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
  const close = () => {
    router.push('/');
  };
  </script>
  
  <style scoped>
  .ticket-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 1rem;
  }
  
  .ticket {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    position: relative;
    overflow: hidden;
  }
  
  .ticket::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #a48e69 0%, #dcc39c 100%);
  }
  
  .ticket-header {
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
  }
  
  .ticket-header h1 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin: 0;
    font-weight: 700;
    letter-spacing: 2px;
  }
  
  .ticket-id {
    position: absolute;
    top: 0;
    right: 0;
    background: #f8f9fa;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    font-family: monospace;
    font-size: 1.1rem;
    color: #2c3e50;
  }
  
  .ticket-body {
    display: grid;
    gap: 2rem;
  }
  
  .customer-info, .seat-info, .payment-info {
    background: rgba(248, 249, 250, 0.5);
    padding: 1.5rem;
    border-radius: 10px;
  }
  
  .info-group {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
  }
  
  .info-group label {
    font-weight: 600;
    color: #6c757d;
  }
  
  .seat-sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
  }
  
  .section h3 {
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
  }
  
  .seat-list {
    background: white;
    padding: 0.5rem;
    border-radius: 5px;
    font-family: monospace;
  }
  
  .status {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
  }
  
  .status.pending {
    background: #fff3cd;
    color: #856404;
  }
  
  .status.completed {
    background: #d4edda;
    color: #155724;
  }
  
  .ticket-footer {
    margin-top: 2rem;
    text-align: center;
    color: #6c757d;
    font-size: 0.9rem;
  }
  
  .ticket-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
  }
  
  .download-btn, .print-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
    transition: transform 0.2s;
  }
  
  .download-btn {
    background: linear-gradient(135deg, #a48e69 0%, #dcc39c 100%);
    color: white;
  }
  
  .print-btn {
    background: #f8f9fa;
    color: #2c3e50;
    border: 1px solid #dee2e6;
  }
  
  .download-btn:hover, .print-btn:hover {
    transform: translateY(-2px);
  }
  
  @media print {
    .ticket-actions {
      display: none;
    }
  }
  </style>
  