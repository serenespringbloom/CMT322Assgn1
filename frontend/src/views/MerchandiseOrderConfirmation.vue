<template>
  <div class="order-confirmation">
    <div class="confirmation-container">
      <h1>Order Confirmation</h1>
      
      <div class="order-details" v-if="orderNumber">
        <div class="confirmation-header">
          <h2>Thank you for your order!</h2>
          <p>Your order has been successfully placed.</p>
        </div>

        <div class="order-info">
          <div class="info-section">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> #{{ orderNumber }}</p>
            <p><strong>Order Date:</strong> {{ new Date(orderDate).toLocaleDateString() }}</p>
            <p><strong>Order Status:</strong> {{ orderStatus }}</p>
            <p><strong>Payment Status:</strong> {{ paymentStatus }}</p>
          </div>

          <div class="info-section">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ customerName }}</p>
            <p><strong>Email:</strong> {{ customerEmail }}</p>
            <p><strong>Phone:</strong> {{ customerPhone }}</p>
          </div>
        </div>

        <div class="order-items">
          <h3>Order Items</h3>
          <div class="items-list">
            <div v-for="item in orderItems" :key="item.id" class="item">
              <div class="item-details">
                <p class="item-name">{{ item.merchandise?.name || 'Product' }}</p>
                <p>Size: {{ item.size }}</p>
                <p>Quantity: {{ item.quantity }}</p>
                <p>Price: RM {{ item.unit_price }}</p>
                <p>Total: RM {{ item.total_amount }}</p>
              </div>
            </div>
          </div>
        </div>

        <div class="order-summary">
          <h3>Order Summary</h3>
          <p class="total"><strong>Total Amount:</strong> RM {{ totalAmount }}</p>
        </div>
      </div>

      <div v-else class="loading">
        Loading order details...
      </div>

      <div class="actions">
        <button @click="router.push('/')" class="continue-shopping">
          Continue Shopping
        </button>
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
const totalAmount = ref(0);
const orderItems = ref([]);
const customerEmail = ref('');
const customerPhone = ref('');
const customerName = ref('');
const orderStatus = ref('');
const paymentStatus = ref('');

onMounted(async () => {
  try {
    // Get order ID from route params or localStorage
    const orderId = route.params.id || localStorage.getItem('lastOrderId');
    
    if (!orderId) {
      console.error('No order ID found');
      router.push('/');
      return;
    }

    console.log('Fetching order with ID:', orderId);

    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/merchandise/orders/${orderId}`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (result.success) {
      const order = result.data.order;
      orderNumber.value = order.id;
      orderDate.value = new Date(order.created_at);
      totalAmount.value = Number(order.total_amount);
      orderItems.value = result.data.items || [];
      customerName.value = order.customer_name;
      customerEmail.value = order.customer_email;
      customerPhone.value = order.customer_phone;
      orderStatus.value = order.status;
      paymentStatus.value = 'PAID'; // Or get from order if you have payment status
    } else {
      throw new Error(result.message || 'Failed to fetch order details');
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
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  font-family: "Plus Jakarta Sans", sans-serif;
}

.confirmation-container {
  background: linear-gradient(135deg, #fff1f5, #ffe5ed);
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h1 {
  text-align: center;
  color: #554149;
  margin-bottom: 2rem;
  font-size: 2rem;
  letter-spacing: 0.1em;
}

.confirmation-header {
  text-align: center;
  margin-bottom: 2rem;
}

.confirmation-header h2 {
  color: #4CAF50;
  margin-bottom: 0.5rem;
}

.order-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 2rem;
  margin-bottom: 2rem;
}

.info-section {
  background: white;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.info-section h3 {
  color: #554149;
  margin-bottom: 1rem;
  border-bottom: 2px solid #ffc8dd;
  padding-bottom: 0.5rem;
}

.order-items {
  margin-bottom: 2rem;
}

.items-list {
  display: grid;
  gap: 1rem;
}

.item {
  background: white;
  padding: 1rem;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.item-name {
  font-weight: bold;
  color: #554149;
}

.order-summary {
  background: white;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  margin-bottom: 2rem;
}

.total {
  font-size: 1.2rem;
  color: #554149;
}

.actions {
  text-align: center;
}

.continue-shopping {
  background: linear-gradient(135deg, #a48e69, #dcc39c);
  color: white;
  border: none;
  padding: 1rem 2rem;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.continue-shopping:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.loading {
  text-align: center;
  padding: 2rem;
  color: #554149;
}

@media (max-width: 768px) {
  .order-confirmation {
    padding: 1rem;
  }

  .confirmation-container {
    padding: 1rem;
  }

  .order-info {
    grid-template-columns: 1fr;
  }
}
</style> 