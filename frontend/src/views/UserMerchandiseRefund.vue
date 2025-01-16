<template>
  <div class="user-refunds">
    <h1>My Orders & Refunds</h1>

    <!-- Orders List -->
    <div class="orders-section">
      <h2>My Orders</h2>
      <div class="orders-list">
        <div v-for="order in orders" :key="order.order_id" class="order-card">
          <div class="order-header">
            <div>
              <h3>Order #{{ order.order_id.toString().padStart(6, '0') }}</h3>
              <span class="date">{{ formatDate(order.created_at) }}</span>
            </div>
            <span :class="['status-badge', order.order_status.toLowerCase()]">
              {{ order.order_status }}
            </span>
          </div>

          <div class="order-items">
            <div v-for="item in order.items" :key="item.id" class="order-item">
              <img :src="item.merchandise.image_url" :alt="item.merchandise.name" />
              <div class="item-details">
                <h4>{{ item.merchandise.name }}</h4>
                <p>Size: {{ item.size }}</p>
                <p>Quantity: {{ item.quantity }}</p>
                <p>Price: RM {{ item.unit_price }}</p>
              </div>
            </div>
          </div>

          <div class="order-footer">
            <p class="total">Total: RM {{ order.total_amount }}</p>
            <button 
              v-if="canRequestRefund(order)" 
              @click="initiateRefund(order)"
              class="refund-btn"
            >
              Request Refund
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Refunds List -->
    <div class="refunds-section">
      <h2>My Refund Requests</h2>
      <div class="refunds-list">
        <div v-for="refund in refunds" :key="refund.id" class="refund-card">
          <div class="refund-header">
            <div>
              <h3>Refund #RF{{ refund.id.toString().padStart(6, '0') }}</h3>
              <p>For Order #{{ refund.order_id.toString().padStart(6, '0') }}</p>
              <span class="date">{{ formatDate(refund.created_at) }}</span>
            </div>
            <span :class="['status-badge', refund.status.toLowerCase()]">
              {{ refund.status }}
            </span>
          </div>
          <div class="refund-details">
            <p><strong>Amount:</strong> RM {{ refund.refund_amount }}</p>
            <p><strong>Reason:</strong> {{ refund.reason }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Refund Request Modal -->
    <div v-if="showRefundModal" class="modal">
      <div class="modal-content">
        <h2>Request Refund</h2>
        <div class="refund-form">
          <div class="form-group">
            <label>Reason for Refund</label>
            <textarea 
              v-model="refundReason" 
              rows="4" 
              placeholder="Please explain why you're requesting a refund"
            ></textarea>
          </div>
          <div class="modal-actions">
            <button @click="submitRefund" class="submit-btn">Submit Request</button>
            <button @click="showRefundModal = false" class="cancel-btn">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const orders = ref([]);
const refunds = ref([]);
const showRefundModal = ref(false);
const refundReason = ref('');
const selectedOrder = ref(null);

const fetchOrders = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/user/merchandise/orders`, {
      headers: {
        'Accept': 'application/json'
      }
    });
    
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    
    const result = await response.json();
    if (result.success) {
      orders.value = result.data;
    }
  } catch (error) {
    console.error('Error fetching orders:', error);
  }
};

const fetchRefunds = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/user/merchandise/refunds`, {
      headers: {
        'Accept': 'application/json'
      }
    });
    
    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
    
    const result = await response.json();
    if (result.success) {
      refunds.value = result.data;
    }
  } catch (error) {
    console.error('Error fetching refunds:', error);
  }
};

const canRequestRefund = (order) => {
  // Check if order is completed and within refund window (e.g., 7 days)
  const orderDate = new Date(order.created_at);
  const now = new Date();
  const daysDifference = (now - orderDate) / (1000 * 60 * 60 * 24);
  
  return order.order_status === 'COMPLETED' && 
         order.payment_status === 'PAID' && 
         daysDifference <= 7;
};

const initiateRefund = (order) => {
  selectedOrder.value = order;
  refundReason.value = '';
  showRefundModal.value = true;
};

const submitRefund = async () => {
  if (!refundReason.value.trim()) {
    alert('Please provide a reason for the refund');
    return;
  }

  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/user/merchandise/refunds`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        order_id: selectedOrder.value.order_id,
        reason: refundReason.value,
        refund_amount: selectedOrder.value.total_amount
      })
    });

    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

    const result = await response.json();
    if (result.success) {
      alert('Refund request submitted successfully');
      showRefundModal.value = false;
      fetchRefunds();
    }
  } catch (error) {
    console.error('Error submitting refund:', error);
    alert('Failed to submit refund request');
  }
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString();
};

onMounted(() => {
  fetchOrders();
  fetchRefunds();
});
</script>

<style scoped>
.user-refunds {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

h1 {
  color: #554149;
  text-align: center;
  margin-bottom: 2rem;
}

h2 {
  color: #554149;
  margin-bottom: 1rem;
}

.orders-section, .refunds-section {
  margin-bottom: 3rem;
}

.order-card, .refund-card {
  background: white;
  border-radius: 10px;
  padding: 1.5rem;
  margin-bottom: 1rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.order-header, .refund-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.date {
  color: #6c757d;
  font-size: 0.875rem;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.875rem;
  font-weight: 500;
}

.status-badge.pending {
  background: #fff3cd;
  color: #856404;
}

.status-badge.completed {
  background: #d4edda;
  color: #155724;
}

.status-badge.refunded {
  background: #cce5ff;
  color: #004085;
}

.order-items {
  border-top: 1px solid #dee2e6;
  border-bottom: 1px solid #dee2e6;
  padding: 1rem 0;
  margin: 1rem 0;
}

.order-item {
  display: flex;
  gap: 1rem;
  margin-bottom: 1rem;
}

.order-item img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 5px;
}

.item-details h4 {
  margin: 0;
  color: #554149;
}

.item-details p {
  margin: 0.25rem 0;
  color: #6c757d;
}

.order-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.total {
  font-weight: bold;
  color: #554149;
}

.refund-btn {
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.refund-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  width: 90%;
  max-width: 500px;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #554149;
}

textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #dcc39c;
  border-radius: 5px;
  resize: vertical;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  margin-top: 1.5rem;
}

.submit-btn, .cancel-btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.submit-btn {
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: white;
  flex: 2;
}

.cancel-btn {
  background: #f8f9fa;
  color: #554149;
  flex: 1;
}

@media (max-width: 768px) {
  .user-refunds {
    padding: 1rem;
  }

  .order-item {
    flex-direction: column;
  }

  .order-item img {
    width: 100%;
    height: 200px;
  }

  .order-footer {
    flex-direction: column;
    gap: 1rem;
  }
}
</style> 