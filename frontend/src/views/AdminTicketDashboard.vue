<template>
  <div class="admin-dashboard">
    <div class="dashboard-tabs">
      <button 
        :class="['tab-btn', { active: activeTab === 'tickets' }]"
        @click="activeTab = 'tickets'"
      >
        Ticket Sales
      </button>
      <button 
        :class="['tab-btn', { active: activeTab === 'refunds' }]"
        @click="activeTab = 'refunds'"
      >
        Refund Requests
      </button>
    </div>

    <!-- Tickets Tab -->
    <div v-if="activeTab === 'tickets'" class="tab-content">
      <h2>Ticket Sales Overview</h2>
      <!-- Summary Cards -->
      <div class="summary-cards">
        <div class="card">
          <h3>Total Sales</h3>
          <p>RM {{ summary.total_sales.toFixed(2) }}</p>
        </div>
        <div class="card">
          <h3>Total Tickets</h3>
          <p>{{ summary.total_tickets }}</p>
        </div>
        <div class="card">
          <h3>Pending Payments</h3>
          <p>{{ summary.pending_payments }}</p>
        </div>
        <div class="card">
          <h3>Completed Payments</h3>
          <p>{{ summary.completed_payments }}</p>
        </div>
      </div>

      <!-- Transactions Table -->
      <div class="transactions-table">
        <table>
          <thead>
            <tr>
              <th>Ticket ID</th>
              <th>Customer</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Amount</th>
              <th>Seats</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="transaction in transactions" :key="transaction.transaction_id">
              <td>TIX{{ transaction.transaction_id.toString().padStart(6, '0') }}</td>
              <td>{{ transaction.cust_name }}</td>
              <td>{{ transaction.cust_email }}</td>
              <td>{{ transaction.cust_phone }}</td>
              <td>RM {{ transaction.total_price }}</td>
              <td>{{ transaction.total_seats }}</td>
              <td>
                <span :class="['status-badge', transaction.payment_status.toLowerCase()]">
                  {{ transaction.payment_status }}
                </span>
              </td>
              <td>{{ new Date(transaction.created_at).toLocaleDateString() }}</td>
              <td>
                <button @click="viewDetails(transaction)" class="btn view-btn">
                  View
                </button>
                <button 
                  v-if="transaction.payment_status === 'PENDING'"
                  @click="updateStatus(transaction.transaction_id, 'COMPLETED')" 
                  class="btn complete-btn"
                >
                  Complete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Refunds Tab -->
    <div v-if="activeTab === 'refunds'" class="tab-content">
      <h2>Refund Management</h2>
      <div class="summary-cards">
        <div class="card">
          <h3>Pending Requests</h3>
          <p>{{ getPendingCount }}</p>
        </div>
        <div class="card">
          <h3>Approved Refunds</h3>
          <p>{{ getApprovedCount }}</p>
        </div>
        <div class="card">
          <h3>Total Refunded</h3>
          <p>RM {{ getTotalRefunded.toFixed(2) }}</p>
        </div>
      </div>

      <!-- Refund Requests Table -->
      <div class="refund-table">
        <table>
          <thead>
            <tr>
              <th>Request ID</th>
              <th>Ticket ID</th>
              <th>Customer</th>
              <th>Amount</th>
              <th>Reason</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="request in refundRequests" :key="request.id">
              <td>REF#{{ request.id.toString().padStart(6, '0') }}</td>
              <td>TIX{{ request.transaction_id.toString().padStart(6, '0') }}</td>
              <td>{{ request.transaction.cust_name }}</td>
              <td>RM {{ request.transaction.total_price }}</td>
              <td>
                <span class="reason-text" :title="request.reason">
                  {{ truncateText(request.reason, 50) }}
                </span>
              </td>
              <td>
                <span :class="['status-badge', request.status.toLowerCase()]">
                  {{ request.status }}
                </span>
              </td>
              <td>{{ formatDate(request.created_at) }}</td>
              <td>
                <div class="action-buttons" v-if="request.status === 'PENDING'">
                  <button @click="showApproveModal(request)" class="btn approve-btn">
                    Approve
                  </button>
                  <button @click="showRejectModal(request)" class="btn reject-btn">
                    Reject
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Detail Modal -->
    <div v-if="showDetailModal" class="modal">
      <div class="modal-content">
        <h2>Ticket Details</h2>
        <div v-if="selectedTicket" class="ticket-details">
          <div class="detail-group">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ selectedTicket.cust_name }}</p>
            <p><strong>Email:</strong> {{ selectedTicket.cust_email }}</p>
            <p><strong>Phone:</strong> {{ selectedTicket.cust_phone }}</p>
          </div>

          <div class="detail-group">
            <h3>Payment Information</h3>
            <p><strong>Amount:</strong> RM {{ selectedTicket.total_price }}</p>
            <p><strong>Method:</strong> {{ selectedTicket.payment_method }}</p>
            <p><strong>Status:</strong> {{ selectedTicket.payment_status }}</p>
          </div>

          <div class="detail-group">
            <h3>Seat Information</h3>
            <div class="seat-list">
              <span v-for="seat in selectedTicketSeats" :key="seat.seat_id" class="seat-tag">
                {{ seat.seat_id }}
              </span>
            </div>
          </div>
        </div>
        <button @click="showDetailModal = false" class="btn close-btn">Close</button>
      </div>
    </div>

    <!-- Approve Modal -->
    <div v-if="showApproveConfirm" class="modal">
      <div class="modal-content">
        <h2>Confirm Refund</h2>
        <p>Are you sure you want to approve this refund request?</p>
        <div class="refund-details">
          <p><strong>Ticket ID:</strong> TIX{{ selectedRequest?.transaction_id.toString().padStart(6, '0') }}</p>
          <p><strong>Amount:</strong> RM {{ selectedRequest?.transaction.total_price }}</p>
          <p><strong>Customer:</strong> {{ selectedRequest?.transaction.cust_name }}</p>
        </div>
        <div class="modal-actions">
          <button @click="processRefund" class="btn approve-btn">Confirm Refund</button>
          <button @click="showApproveConfirm = false" class="btn cancel-btn">Cancel</button>
        </div>
      </div>
    </div>

    <!-- Reject Modal -->
    <div v-if="showRejectConfirm" class="modal">
      <div class="modal-content">
        <h2>Reject Refund Request</h2>
        <div class="form-group">
          <label>Rejection Reason:</label>
          <textarea 
            v-model="rejectionReason" 
            rows="4" 
            placeholder="Please provide a reason for rejection"
          ></textarea>
        </div>
        <div class="modal-actions">
          <button @click="rejectRefund" class="btn reject-btn">Confirm Rejection</button>
          <button @click="closeRejectModal" class="btn cancel-btn">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

// Existing ticket refs
const transactions = ref([]);
const summary = ref({
  total_sales: 0,
  total_tickets: 0,
  pending_payments: 0,
  completed_payments: 0
});
const showDetailModal = ref(false);
const selectedTicket = ref(null);
const selectedTicketSeats = ref([]);

// New refund refs
const activeTab = ref('tickets');
const refundRequests = ref([]);
const showApproveConfirm = ref(false);
const showRejectConfirm = ref(false);
const selectedRequest = ref(null);
const rejectionReason = ref('');

// Computed properties for refund summary
const getPendingCount = computed(() => 
  refundRequests.value.filter(r => r.status === 'PENDING').length
);

const getApprovedCount = computed(() => 
  refundRequests.value.filter(r => r.status === 'APPROVED').length
);

const getTotalRefunded = computed(() => 
  refundRequests.value
    .filter(r => r.status === 'APPROVED')
    .reduce((sum, r) => sum + parseFloat(r.transaction.total_price), 0)
);

onMounted(async () => {
  await fetchTicketSales();
  await fetchRefundRequests();
});

// Existing ticket methods
const fetchTicketSales = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/tickets`, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    });
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const result = await response.json();
    
    if (result.success) {
      transactions.value = result.data.transactions;
      summary.value = result.data.summary;
    }
  } catch (error) {
    console.error('Error fetching ticket sales:', error);
  }
};

const viewDetails = async (transaction) => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/tickets/${transaction.transaction_id}`);
    const result = await response.json();
    
    if (result.success) {
      selectedTicket.value = result.data.transaction;
      selectedTicketSeats.value = result.data.seats;
      showDetailModal.value = true;
    }
  } catch (error) {
    console.error('Error fetching ticket details:', error);
  }
};

const updateStatus = async (transactionId, status) => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/tickets/${transactionId}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        payment_status: status,
        status: status
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      await fetchTicketSales();
    }
  } catch (error) {
    console.error('Error updating ticket status:', error);
  }
};

// New refund methods
const fetchRefundRequests = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/refunds`);
    const result = await response.json();
    
    if (result.success) {
      refundRequests.value = result.data;
    }
  } catch (error) {
    console.error('Error fetching refund requests:', error);
  }
};

const showApproveModal = (request) => {
  selectedRequest.value = request;
  showApproveConfirm.value = true;
};

const showRejectModal = (request) => {
  selectedRequest.value = request;
  showRejectConfirm.value = true;
};

const processRefund = async () => {
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/refunds/${selectedRequest.value.transaction_id}/process`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          admin_id: 1
        })
      }
    );
    
    const result = await response.json();
    
    if (result.success) {
      await fetchRefundRequests();
      showApproveConfirm.value = false;
    }
  } catch (error) {
    console.error('Error processing refund:', error);
  }
};

const rejectRefund = async () => {
  if (!rejectionReason.value) {
    alert('Please provide a rejection reason');
    return;
  }

  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/refunds/${selectedRequest.value.transaction_id}/reject`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          admin_id: 1,
          rejection_reason: rejectionReason.value
        })
      }
    );
    
    const result = await response.json();
    
    if (result.success) {
      await fetchRefundRequests();
      closeRejectModal();
    }
  } catch (error) {
    console.error('Error rejecting refund:', error);
  }
};

const closeRejectModal = () => {
  showRejectConfirm.value = false;
  rejectionReason.value = '';
  selectedRequest.value = null;
};

const truncateText = (text, length) => {
  return text.length > length ? text.substring(0, length) + '...' : text;
};

const formatDate = (date) => {
  if (!date) return '';
  return new Date(date).toLocaleDateString('en-MY', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const fetchFeedbacks = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/feedback`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const result = await response.json();
    if (result.success) {
      feedbacks.value = result.data;
    } else {
      throw new Error(result.message || 'Failed to fetch feedbacks');
    }
  } catch (error) {
    console.error('Error fetching feedbacks:', error);
  }
};

// Update delete function
const deleteFeedback = async (id) => {
  if (!confirm('Are you sure you want to delete this feedback?')) return;
  
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/feedback/${id}`,
      { method: 'DELETE' }
    );
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();
    
    if (result.success) {
      await fetchFeedbacks();
      alert('Feedback deleted successfully');
    } else {
      throw new Error(result.message || 'Failed to delete feedback');
    }
  } catch (error) {
    console.error('Error deleting feedback:', error);
    alert('Failed to delete feedback');
  }
};
</script>

<style scoped>
.admin-dashboard {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
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
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.card h3 {
  color: #666;
  margin-bottom: 0.5rem;
  font-size: 1rem;
}

.card p {
  font-size: 1.5rem;
  font-weight: bold;
  color: #2c3e50;
  margin: 0;
}

.transactions-table {
  background: white;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
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
  font-weight: 600;
  color: #666;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.85rem;
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

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.3s ease;
}

.view-btn {
  background: #e9ecef;
  color: #495057;
  margin-right: 0.5rem;
}

.complete-btn {
  background: #28a745;
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
  color: #666;
  margin-bottom: 0.5rem;
}

.seat-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.seat-tag {
  background: #e9ecef;
  padding: 0.25rem 0.5rem;
  border-radius: 5px;
  font-family: monospace;
}

.close-btn {
  background: #dc3545;
  color: white;
  margin-top: 1rem;
}

@media (max-width: 768px) {
  .admin-dashboard {
    padding: 1rem;
  }
  
  .card {
    padding: 1rem;
  }
  
  th, td {
    padding: 0.5rem;
  }
}

/* New styles for tabs */
.dashboard-tabs {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.tab-btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  background: #f8f9fa;
  color: #6c757d;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.3s ease;
}

.tab-btn.active {
  background: #DCC39C;
  color: #554149;
}

.tab-btn:hover {
  transform: translateY(-2px);
}

.tab-content {
  animation: fadeIn 0.3s ease;
}

/* Additional refund styles */
.reason-text {
  display: inline-block;
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.status-badge.rejected {
  background: #f8d7da;
  color: #721c24;
}

.modal-actions {
  margin-top: 1.5rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.form-group {
  margin: 1rem 0;
}

textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ddd;
  border-radius: 5px;
  margin-top: 0.5rem;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .dashboard-tabs {
    flex-direction: column;
  }
  
  .tab-btn {
    width: 100%;
  }
}
</style> 