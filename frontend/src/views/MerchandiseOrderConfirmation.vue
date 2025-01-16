<template>
  <div class="order-confirmation">
    <div class="confirmation-card">
      <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
          <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
      </div>

      <h1>Order Confirmed!</h1>
      <p class="thank-you">Thank you for your purchase</p>

      <div class="order-details">
        <h2>Order Details</h2>
        <div class="detail-row">
          <span>Order Number:</span>
          <span class="value">#{{ orderNumber }}</span>
        </div>
        <div class="detail-row">
          <span>Order Date:</span>
          <span class="value">{{ formatDate(orderDate) }}</span>
        </div>
        <div class="detail-row">
          <span>Order Status:</span>
          <span :class="['value', 'status', orderStatus.toLowerCase()]">
            {{ orderStatus }}
          </span>
        </div>
        <div class="detail-row">
          <span>Payment Status:</span>
          <span :class="['value', 'status', paymentStatus.toLowerCase()]">
            {{ paymentStatus }}
          </span>
        </div>
        <div class="detail-row total">
          <span>Total Amount:</span>
          <span class="value">RM {{ Number(totalAmount).toFixed(2) }}</span>
        </div>
      </div>

      <div class="items-section">
        <h3>Items Ordered</h3>
        <div class="items-list">
          <div v-for="item in orderItems" :key="item.id" class="item-card">
            <img :src="item.merchandise.image_url" :alt="item.merchandise.name">
            <div class="item-details">
              <h4>{{ item.merchandise.name }}</h4>
              <p class="description">{{ item.merchandise.description }}</p>
              <p>Size: {{ item.size }}</p>
              <p>Quantity: {{ item.quantity }}</p>
              <p>Unit Price: RM {{ Number(item.unit_price).toFixed(2) }}</p>
              <p class="subtotal">Subtotal: RM {{ (Number(item.unit_price) * Number(item.quantity)).toFixed(2) }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="customer-info">
        <h3>Customer Information</h3>
        <p><strong>Name:</strong> {{ customerName }}</p>
        <p><strong>Email:</strong> {{ customerEmail }}</p>
        <p><strong>Phone:</strong> {{ customerPhone }}</p>
      </div>

      <div class="actions">
        <button @click="downloadReceipt" class="download-btn">
          Download Receipt
        </button>
        <button @click="goToHome" class="home-btn">
          Return to Home
        </button>
      </div>

      <div class="support-info">
        <p>Having issues with your order?</p>
        <router-link to="/refund" class="refund-link">Request a Refund</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';

const router = useRouter();
const route = useRoute();

// Initialize all refs with proper types
const orderNumber = ref('');
const orderDate = ref(new Date());
const totalAmount = ref(0); // Initialize as number
const orderItems = ref([]);
const customerEmail = ref('');
const customerPhone = ref('');
const customerName = ref('');
const orderStatus = ref('');
const paymentStatus = ref('');

onMounted(async () => {
  try {
    const orderId = route.params.orderId || localStorage.getItem('lastOrderId');
    
    if (!orderId) {
      router.push('/');
      return;
    }

    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/merchandise/orders/${orderId}`);
    const result = await response.json();

    if (result.success) {
      const order = result.data;
      orderNumber.value = order.order_number;
      orderDate.value = new Date(order.created_at);
      totalAmount.value = Number(order.total_amount); // Ensure it's a number
      orderItems.value = order.items.map(item => ({
        ...item,
        unit_price: Number(item.unit_price), // Ensure unit_price is a number
        quantity: Number(item.quantity) // Ensure quantity is a number
      }));
      customerName.value = order.customer_name;
      customerEmail.value = order.customer_email;
      customerPhone.value = order.customer_phone;
      orderStatus.value = order.order_status;
      paymentStatus.value = order.payment_status;
    } else {
      throw new Error('Failed to fetch order details');
    }
  } catch (error) {
    console.error('Error fetching order:', error);
    alert('Failed to load order details');
    router.push('/');
  }
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-MY', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const downloadReceipt = async () => {
  try {
    const orderId = route.params.orderId || localStorage.getItem('lastOrderId');
    
    if (!orderId) {
      throw new Error('Order ID not found');
    }

    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/merchandise/orders/${orderId}/receipt`,
      {
        method: 'GET',
        headers: {
          'Accept': 'application/pdf'
        }
      }
    );

    if (!response.ok) {
      throw new Error('Failed to download receipt');
    }

    // Create blob from response
    const blob = await response.blob();
    const url = window.URL.createObjectURL(blob);
    
    // Create temporary link and trigger download
    const a = document.createElement('a');
    a.href = url;
    a.download = `receipt-${orderNumber.value}.pdf`;
    document.body.appendChild(a);
    a.click();
    
    // Cleanup
    window.URL.revokeObjectURL(url);
    document.body.removeChild(a);
  } catch (error) {
    console.error('Error downloading receipt:', error);
    alert('Failed to download receipt: ' + error.message);
  }
};

const goToHome = () => {
  router.push('/');
};
</script>

<style scoped>
.order-confirmation {
  max-width: 800px;
  margin: 2rem auto;
  padding: 1rem;
}

.confirmation-card {
  background: white;
  border-radius: 15px;
  padding: 2rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.success-icon {
  width: 60px;
  height: 60px;
  margin: 0 auto 1.5rem;
  color: #28a745;
}

h1 {
  color: #554149;
  text-align: center;
  margin-bottom: 0.5rem;
}

.thank-you {
  text-align: center;
  color: #6c757d;
  margin-bottom: 2rem;
}

.order-details {
  background: #f8f9fa;
  padding: 1.5rem;
  border-radius: 10px;
  margin-bottom: 2rem;
}

.detail-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.5rem;
}

.value {
  font-weight: 500;
  color: #554149;
}

.items-section {
  margin-bottom: 2rem;
}

.items-list {
  display: grid;
  gap: 1rem;
}

.item-card {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  border: 1px solid #dee2e6;
  border-radius: 8px;
}

.item-card img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 4px;
}

.item-details h4 {
  margin: 0 0 0.5rem 0;
  color: #554149;
}

.item-details p {
  margin: 0.25rem 0;
  color: #6c757d;
}

.price {
  font-weight: 500;
  color: #554149 !important;
}

.customer-info {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: #f8f9fa;
  border-radius: 10px;
}

.actions {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.download-btn, .home-btn {
  flex: 1;
  padding: 1rem;
  border: none;
  border-radius: 5px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.download-btn {
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: white;
}

.home-btn {
  background: #f8f9fa;
  color: #554149;
  border: 1px solid #dee2e6;
}

.download-btn:hover, .home-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.support-info {
  text-align: center;
  color: #6c757d;
}

.refund-link {
  color: #a48e69;
  text-decoration: none;
  font-weight: 500;
}

.refund-link:hover {
  text-decoration: underline;
}

@media (max-width: 768px) {
  .order-confirmation {
    margin: 1rem;
  }

  .actions {
    flex-direction: column;
  }

  .item-card {
    flex-direction: column;
  }

  .item-card img {
    width: 100%;
    height: 200px;
  }
}
.status {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.875rem;
}

.status.pending {
  background: #fff3cd;
  color: #856404;
}

.status.completed {
  background: #d4edda;
  color: #155724;
}

.status.paid {
  background: #cce5ff;
  color: #004085;
}

.description {
  color: #6c757d;
  font-size: 0.875rem;
  margin: 0.5rem 0;
}

.subtotal {
  font-weight: 600;
  color: #554149 !important;
  margin-top: 0.5rem !important;
}

.total {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 2px solid #dee2e6;
}

</style> 