<template>
  <div class="refund-dashboard">
    <h1>Refund Management Dashboard</h1>

    <!-- Summary Cards -->
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

const refundRequests = ref([]);
const showApproveConfirm = ref(false);
const showRejectConfirm = ref(false);
const selectedRequest = ref(null);
const rejectionReason = ref('');

// Computed properties for summary cards
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
  await fetchRefundRequests();
});

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
          admin_id: 1 // Replace with actual admin ID from auth
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
          admin_id: 1, // Replace with actual admin ID from auth
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
  return new Date(date).toLocaleDateString();
};
</script>

<style scoped>
.refund-dashboard {
  padding: 2rem;
  max-width: 1400px;
  margin: 0 auto;
}

.summary-cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.card {
  background: white;
  padding: 1.5rem;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.refund-table {
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

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.85rem;
}

.status-badge.pending {
  background: #fff3cd;
  color: #856404;
}

.status-badge.approved {
  background: #d4edda;
  color: #155724;
}

.status-badge.rejected {
  background: #f8d7da;
  color: #721c24;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-weight: 500;
  margin: 0 0.25rem;
}

.approve-btn {
  background: #28a745;
  color: white;
}

.reject-btn {
  background: #dc3545;
  color: white;
}

.cancel-btn {
  background: #6c757d;
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
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  max-width: 500px;
  width: 90%;
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

.modal-actions {
  margin-top: 1.5rem;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
}

.reason-text {
  display: inline-block;
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

@media (max-width: 768px) {
  .refund-dashboard {
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