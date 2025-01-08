<template>
    <div>
      <h2>Refund Management</h2>
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Order ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Reason</th>
            <th>Admin Notes</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="refund in refunds" :key="refund.id">
            <td>{{ refund.user.name }}</td>
            <td>{{ refund.order_id }}</td>
            <td>${{ refund.amount }}</td>
            <td>
              <select v-model="refund.status">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
              </select>
            </td>
            <td>{{ refund.reason }}</td>
            <td>
              <input
                v-model="refund.admin_notes"
                placeholder="Add notes"
              />
            </td>
            <td>
              <button @click="updateRefund(refund)">Update</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        refunds: []
      };
    },
    methods: {
      async fetchRefunds() {
        try {
          const response = await axios.get('/api/admin/refunds');
          this.refunds = response.data;
        } catch (error) {
          console.error('Error fetching refunds:', error);
        }
      },
      async updateRefund(refund) {
        try {
          await axios.put(`/api/admin/refunds/${refund.id}`, {
            status: refund.status,
            admin_notes: refund.admin_notes
          });
          alert('Refund updated successfully!');
          this.fetchRefunds();
        } catch (error) {
          console.error('Error updating refund:', error);
        }
      }
    },
    mounted() {
      this.fetchRefunds();
    }
  };
  </script>
  
  <style>
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  
  th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
  }
  
  th {
    background-color: #f4f4f4;
  }
  
  select, input {
    width: 90%;
    padding: 4px;
    margin-top: 4px;
  }
  
  button {
    padding: 5px 10px;
    margin-top: 5px;
    cursor: pointer;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 4px;
  }
  
  button:hover {
    background-color: #45a049;
  }
  </style>
  