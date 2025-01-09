<template>
    <div>
      <h2>Refund Management</h2>
      <table>
        <thead>
          <tr>
            
            <th>Transaction ID</th>
           
            <th>Status</th>
            <th>Reason</th>
            <th>Processing Comments</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="refund in refunds" :key="refund.refund_id">
    
            <td>{{ refund.transaction_id }}</td>
           
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
                v-model="refund.processing_comments"
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
          const response = await axios.get('http://127.0.0.1:8000/api/admin/refunds');
          this.refunds = response.data;
        } catch (error) {
          console.error('Error fetching refunds:', error);
        }
      },
      async updateRefund(refund) {
        try {
          await axios.put(`http://127.0.0.1:8000/api/admin/refunds/${refund.refund_id}`, {
            status: refund.status,
            processing_comments: refund.processing_comments,
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
  