<template>
  <div class="admin-orders">
    <div class="header">
      <h1>Merchandise Management</h1>
      <div class="tabs">
        <button 
          :class="['tab-btn', { active: activeTab === 'orders' }]"
          @click="activeTab = 'orders'"
        >
          Orders
          <span class="badge">{{ orderCount }}</span>
        </button>
        <button 
          :class="['tab-btn', { active: activeTab === 'refunds' }]"
          @click="activeTab = 'refunds'"
        >
          Refunds
          <span class="badge">{{ refundCount }}</span>
        </button>
      </div>
    </div>

    <!-- Orders Tab -->
    <div v-if="activeTab === 'orders'" class="content-section">
      <div class="orders-list">
        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="order in orders" :key="order.id">
                <td>#{{ order.id }}</td>
                <td>
                  <div class="customer-info">
                    <span>{{ order.customer_name }}</span>
                    <small>{{ order.customer_email }}</small>
                  </div>
                </td>
                <td>{{ order.merchandise?.name }}</td>
                <td>{{ order.size }}</td>
                <td>{{ order.quantity }}</td>
                <td>RM {{ order.total_amount }}</td>
                <td>
                  <span :class="['status-badge', order.status.toLowerCase()]">
                    {{ order.status }}
                  </span>
                </td>
                <td>{{ new Date(order.created_at).toLocaleDateString() }}</td>
                <td>
                  <button 
                    v-if="order.status === 'PENDING'"
                    @click="processOrder(order.id, 'COMPLETED')" 
                    class="action-btn complete"
                  >
                    Complete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Refunds Tab -->
    <div v-if="activeTab === 'refunds'" class="content-section">
      <div class="refunds-list">
        <div v-if="refunds.length === 0" class="no-data">
          No refund requests found.
        </div>
        <div v-else class="table-container">
          <table>
            <thead>
              <tr>
                <th>Refund ID</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="refund in refunds" :key="refund.id">
                <td>#{{ refund.id }}</td>
                <td>#{{ refund.order_id }}</td>
                <td>
                  <div class="customer-info">
                    <span>{{ refund.order?.customer_name }}</span>
                    <small>{{ refund.order?.customer_email }}</small>
                  </div>
                </td>
                <td>RM {{ refund.refund_amount }}</td>
                <td>{{ refund.reason }}</td>
                <td>
                  <span :class="['status-badge', refund.status.toLowerCase()]">
                    {{ refund.status }}
                  </span>
                </td>
                <td>{{ new Date(refund.created_at).toLocaleDateString() }}</td>
                <td>
                  <div class="action-buttons" v-if="refund.status === 'PENDING'">
                    <button 
                      @click="processRefund(refund)" 
                      class="action-btn approve"
                    >
                      Approve
                    </button>
                    <button 
                      @click="rejectRefund(refund.id)" 
                      class="action-btn reject"
                    >
                      Reject
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Refund Process Modal -->
    <div v-if="showRefundModal" class="modal">
      <div class="modal-content">
        <h2>Process Refund</h2>
        <div class="refund-details">
          <p><strong>Order ID:</strong> #{{ selectedRefund?.order_id }}</p>
          <p><strong>Customer:</strong> {{ selectedRefund?.order?.customer_name }}</p>
          <p><strong>Amount:</strong> RM {{ selectedRefund?.refund_amount }}</p>
          <p><strong>Reason:</strong> {{ selectedRefund?.reason }}</p>
        </div>
        <div class="modal-actions">
          <button @click="confirmRefund" class="action-btn approve">Confirm Refund</button>
          <button @click="showRefundModal = false" class="action-btn reject">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const activeTab = ref('orders');
const orders = ref([]);
const refunds = ref([]);
const showRefundModal = ref(false);
const selectedRefund = ref(null);
const orderCount = ref(0);
const refundCount = ref(0);

const fetchOrders = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/merchandise/orders`);
    const data = await response.json();
    if (data.success) {
      orders.value = data.data.orders;
      orderCount.value = data.data.orders.length;
    }
  } catch (error) {
    console.error('Error fetching orders:', error);
  }
};

const fetchRefunds = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds`);
    const data = await response.json();
    
    if (data.success) {
      refunds.value = data.data || [];
      refundCount.value = data.data ? data.data.length : 0;
    } else {
      console.error('Failed to fetch refunds:', data.message);
      refunds.value = [];
      refundCount.value = 0;
    }
  } catch (error) {
    console.error('Error fetching refunds:', error);
    refunds.value = [];
    refundCount.value = 0;
  }
};

const processOrder = async (orderId, status) => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/merchandise/orders/${orderId}/process`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ status })
    });
    
    if (response.ok) {
      await fetchOrders();
    }
  } catch (error) {
    console.error('Error processing order:', error);
  }
};

const processRefund = (refund) => {
  selectedRefund.value = refund;
  showRefundModal.value = true;
};

const confirmRefund = async () => {
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds/${selectedRefund.value.id}/process`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          action: 'APPROVE',
          adminId: 1
        })
      }
    );

    if (response.ok) {
      showRefundModal.value = false;
      selectedRefund.value = null;
      await fetchRefunds();
      await fetchOrders();
    }
  } catch (error) {
    console.error('Error processing refund:', error);
  }
};

const rejectRefund = async (refundId) => {
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds/${refundId}/process`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          action: 'REJECT',
          adminId: 1
        })
      }
    );

    if (response.ok) {
      await fetchRefunds();
      await fetchOrders();
    }
  } catch (error) {
    console.error('Error rejecting refund:', error);
  }
};

onMounted(() => {
  fetchOrders();
  fetchRefunds();
});
</script>

<style scoped>
.admin-orders {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.header {
  margin-bottom: 2rem;
}

h1 {
  color: #2c3e50;
  margin-bottom: 1rem;
}

.tabs {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.tab-btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  cursor: pointer;
  background: #f8f9fa;
  color: #6c757d;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.tab-btn.active {
  background: #a48e69;
  color: white;
}

.badge {
  background: rgba(0, 0, 0, 0.1);
  padding: 0.2rem 0.6rem;
  border-radius: 12px;
  font-size: 0.8rem;
}

.table-container {
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

th {
  background: #f8f9fa;
  color: #2c3e50;
  font-weight: 600;
}

.customer-info {
  display: flex;
  flex-direction: column;
}

.customer-info small {
  color: #6c757d;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
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

.action-btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.875rem;
  transition: all 0.3s ease;
}

.action-btn.complete {
  background: #28a745;
  color: white;
}

.action-btn.approve {
  background: #28a745;
  color: white;
}

.action-btn.reject {
  background: #dc3545;
  color: white;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
}

.modal-content h2 {
  margin-bottom: 1rem;
}

.refund-details {
  margin-bottom: 1.5rem;
}

.modal-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

@media (max-width: 768px) {
  .admin-orders {
    padding: 1rem;
  }
  
  .table-container {
    overflow-x: auto;
  }
  
  .action-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
}

.no-data {
  text-align: center;
  padding: 2rem;
  color: #666;
  font-style: italic;
}
</style> 