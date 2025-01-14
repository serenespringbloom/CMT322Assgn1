<template>
    <div class="container mx-auto p-6">
      <!-- Box Container -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <!-- Header -->
         <div class=" bg-pink-500 flex item-center justify-center">
        <div class="bg-pink-500 text-white p-4 text-xl font-bold">
          Ticket Purchase Information
        </div>
    </div>
        <!-- Table Section -->
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
              <tr>
                <th class="py-2 px-4 border-b text-left">Ticket ID</th>
                <th class="py-2 px-4 border-b text-left">Customer's Entrance</th>
                <th class="py-2 px-4 border-b text-left">Email Address</th>
                <th class="py-2 px-4 border-b text-left">Phone Number</th>
                <th class="py-2 px-4 border-b text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in tableData" :key="item.transaction_id" class="hover:bg-gray-50">
                <td class="py-2 px-4 border-b">{{ item.transaction_id }}</td>
                <td class="py-2 px-4 border-b">{{ item.ticket_category }}</td>
                <td class="py-2 px-4 border-b">{{ item.cust_email }}</td>
                <td class="py-2 px-4 border-b">{{ item.cust_phone }}</td>
                <td class="py-2 px-4 border-b">
                  <button class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 mb-2">
                    Update
                  </button>
                 
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from 'axios';
  
  const tableData = ref([]);
  
  const fetchData = async () => {
    try {
      const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/admin/billing2`);
      tableData.value = response.data;
    } catch (error) {
      console.error('Error fetching data:', error);
      // You might want to handle the error appropriately (show error message, etc.)
    }
  };
  
  onMounted(() => {
    fetchData();
  });
  </script>
   <style scoped>
   @import "tailwindcss/base";
   @import "tailwindcss/components";
   @import "tailwindcss/utilities";
   </style>