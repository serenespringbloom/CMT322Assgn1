<template>
  <div class="admin-merchandise">
    <h1>Merchandise Orders Management</h1>

    <!-- Summary Cards -->
    <div class="summary-cards">
      <div class="card">
        <h3>Total Orders</h3>
        <p>{{ summary.totalOrders }}</p>
      </div>
      <div class="card">
        <h3>Pending Orders</h3>
        <p>{{ summary.pendingOrders }}</p>
      </div>
      <div class="card">
        <h3>Total Sales</h3>
        <p>RM {{ summary.totalSales.toFixed(2) }}</p>
      </div>
      <div class="card">
        <h3>Pending Refunds</h3>
        <p>{{ summary.pendingRefunds }}</p>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <button 
        :class="['tab-btn', { active: activeTab === 'orders' }]"
        @click="activeTab = 'orders'"
      >
        Orders
      </button>
      <button 
        :class="['tab-btn', { active: activeTab === 'refunds' }]"
        @click="activeTab = 'refunds'"
      >
        Refunds
      </button>
    </div>

    <!-- Orders Table -->
    <div v-if="activeTab === 'orders'" class="table-container">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Items</th>
            <th>Total</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="order in orders" :key="order.order_id">
            <td>#{{ order.order_id.toString().padStart(6, '0') }}</td>
            <td>
              <div class="customer-info">
                <span>{{ order.customer_name }}</span>
                <small>{{ order.customer_email }}</small>
              </div>
            </td>
            <td>{{ order.items.length }} items</td>
            <td>RM {{ order.total_amount }}</td>
            <td>
              <span :class="['status-badge', order.order_status.toLowerCase()]">
                {{ order.order_status }}
              </span>
            </td>
            <td>{{ formatDate(order.created_at) }}</td>
            <td>
              <button @click="viewOrderDetails(order)" class="btn view-btn">
                View
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Refunds Table -->
    <div v-if="activeTab === 'refunds'" class="table-container">
      <table>
        <thead>
          <tr>
            <th>Refund ID</th>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="refund in refunds" :key="refund.id">
            <td>#RF{{ refund.id.toString().padStart(6, '0') }}</td>
            <td>#{{ refund.order_id.toString().padStart(6, '0') }}</td>
            <td>{{ refund.order.customer_name }}</td>
            <td>RM {{ refund.refund_amount }}</td>
            <td>
              <span :class="['status-badge', refund.status.toLowerCase()]">
                {{ refund.status }}
              </span>
            </td>
            <td>{{ formatDate(refund.created_at) }}</td>
            <td>
              <div class="action-buttons" v-if="refund.status === 'PENDING'">
                <button @click="processRefund(refund)" class="btn approve-btn">
                  Approve
                </button>
                <button @click="rejectRefund(refund)" class="btn reject-btn">
                  Reject
                </button>
              </div>
              <button v-else @click="viewRefundDetails(refund)" class="btn view-btn">
                View
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Order Details Modal -->
    <div v-if="showOrderModal" class="modal">
      <div class="modal-content">
        <h2>Order Details</h2>
        <div class="order-details">
          <div class="detail-group">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ selectedOrder.customer_name }}</p>
            <p><strong>Email:</strong> {{ selectedOrder.customer_email }}</p>
            <p><strong>Phone:</strong> {{ selectedOrder.customer_phone }}</p>
          </div>

          <div class="detail-group">
            <h3>Shipping Address</h3>
            <p>{{ selectedOrder.shipping_address }}</p>
          </div>

          <div class="detail-group">
            <h3>Items</h3>
            <div class="items-list">
              <div v-for="item in selectedOrder.items" :key="item.id" class="item">
                <span>{{ item.merchandise.name }}</span>
                <span>Size: {{ item.size.toUpperCase() }}</span>
                <span>Qty: {{ item.quantity }}</span>
                <span>RM {{ item.unit_price }}</span>
              </div>
            </div>
          </div>

          <div class="detail-group">
            <h3>Order Summary</h3>
            <p><strong>Total Amount:</strong> RM {{ selectedOrder.total_amount }}</p>
            <p><strong>Status:</strong> {{ selectedOrder.order_status }}</p>
            <p><strong>Payment Status:</strong> {{ selectedOrder.payment_status }}</p>
          </div>
        </div>
        <div class="modal-actions">
          <button @click="showOrderModal = false" class="btn close-btn">Close</button>
        </div>
      </div>
    </div>

    <!-- Refund Process Modal -->
    <div v-if="showRefundModal" class="modal">
      <div class="modal-content">
        <h2>Process Refund</h2>
        <div class="refund-details">
          <p><strong>Order ID:</strong> #{{ selectedRefund?.order_id }}</p>
          <p><strong>Customer:</strong> {{ selectedRefund?.order.customer_name }}</p>
          <p><strong>Amount:</strong> RM {{ selectedRefund?.refund_amount }}</p>
          <p><strong>Reason:</strong> {{ selectedRefund?.reason }}</p>
        </div>
        <div class="modal-actions">
          <button @click="confirmRefund" class="btn approve-btn">Confirm Refund</button>
          <button @click="showRefundModal = false" class="btn cancel-btn">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  
</template>

<script>
export default {
  data() {
    return {
      activeTab: 'orders',
      orders: [],
      refunds: [],
      summary: {
        totalOrders: 0,
        pendingOrders: 0,
        totalSales: 0,
        pendingRefunds: 0
      },
      showOrderModal: false,
      showRefundModal: false,
      selectedOrder: null,
      selectedRefund: null
    }
  },
  methods: {
    async fetchOrders() {
      try {
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/merchandise/orders`, {
          headers: {
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        if (result.success) {
          this.orders = result.data;
        } else {
          throw new Error(result.message);
        }
      } catch (error) {
        console.error('Error fetching orders:', error);
      }
    },
    async fetchRefunds() {
      try {
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds`, {
          headers: {
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        if (result.success) {
          this.refunds = result.data;
        } else {
          throw new Error(result.message);
        }
      } catch (error) {
        console.error('Error fetching refunds:', error);
      }
    },
    async fetchSummary() {
      try {
        console.log('Fetching merchandise summary...');
        const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/merchandise/summary`, {
          headers: {
            'Accept': 'application/json'
          }
        });
        
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Merchandise summary response:', result);
        
        if (result.success) {
          this.summary = {
            totalOrders: parseInt(result.data.totalOrders) || 0,
            pendingOrders: parseInt(result.data.pendingOrders) || 0,
            totalSales: parseFloat(result.data.totalSales) || 0,
            pendingRefunds: parseInt(result.data.pendingRefunds) || 0
          };
          console.log('Updated summary:', this.summary);
        }
      } catch (error) {
        console.error('Error fetching merchandise summary:', error);
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString();
    },
    viewOrderDetails(order) {
      this.selectedOrder = order;
      this.showOrderModal = true;
    },
    processRefund(refund) {
      this.selectedRefund = refund;
      this.showRefundModal = true;
    },
    async confirmRefund() {
      try {
        const response = await fetch(
          `${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds/${this.selectedRefund.id}/process`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            }
          }
        );
        const data = await response.json();
        if (data.success) {
          this.showRefundModal = false;
          this.fetchRefunds();
          this.fetchOrders();
        }
      } catch (error) {
        console.error('Error processing refund:', error);
      }
    },
    async rejectRefund(refund) {
      if (!confirm('Are you sure you want to reject this refund request?')) {
        return;
      }

      try {
        const response = await fetch(
          `${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds/${refund.id}/reject`,
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json'
            }
          }
        );

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
          alert('Refund rejected successfully');
          this.fetchRefunds(); // Refresh the refunds list
          this.selectedRefund = null;
        } else {
          throw new Error(result.message || 'Failed to reject refund');
        }
      } catch (error) {
        console.error('Error rejecting refund:', error);
        alert('Failed to reject refund: ' + error.message);
      }
    }
  },
  mounted() {
    this.fetchOrders();
    this.fetchRefunds();
    this.fetchSummary();
  }
}
</script>

<style scoped>
.admin-merchandise {
  padding: 2rem;
}

h1 {
  color: #554149;
  margin-bottom: 2rem;
  text-align: center;
}

.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.card {
  background: white;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.card h3 {
  color: #554149;
  margin-bottom: 0.5rem;
  font-size: 1rem;
}

.card p {
  color: #927e5b;
  font-size: 1.5rem;
  font-weight: bold;
}

.tabs {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.tab-btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 5px;
  background: #f8f9fa;
  color: #554149;
  cursor: pointer;
  transition: all 0.3s ease;
}

.tab-btn.active {
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: white;
}

.table-container {
  background: white;
  border-radius: 10px;
  padding: 1rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  padding: 1rem;
  text-align: left;
  border-bottom: 1px solid #dee2e6;
}

th {
  background: #f8f9fa;
  color: #554149;
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
  padding: 0.25rem 0.5rem;
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

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.view-btn {
  background: #f8f9fa;
  color: #554149;
}

.approve-btn {
  background: #28a745;
  color: white;
}

.reject-btn {
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
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  max-width: 600px;
  width: 90%;
  max-height: 90vh;
  overflow-y: auto;
}

.detail-group {
  margin-bottom: 1.5rem;
}

.detail-group h3 {
  color: #554149;
  margin-bottom: 0.5rem;
}

.items-list {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem;
  background: #f8f9fa;
  border-radius: 5px;
}

.modal-actions {
  margin-top: 2rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

@media (max-width: 768px) {
  .admin-merchandise {
    padding: 1rem;
  }
  
  .card {
    padding: 1rem;
  }
  
  th, td {
    padding: 0.5rem;
  }
}
</style> 