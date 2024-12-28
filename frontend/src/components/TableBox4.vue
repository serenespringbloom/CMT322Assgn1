<template>
    <div class="container mx-auto p-6">
      <!-- Box Container -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <!-- Header -->
         <div class=" bg-pink-500 flex item-center justify-center">
        <div class="bg-pink-500 text-white p-4 text-xl font-bold">
          Merchandise Purchase Information
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
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in tableData" :key="item.ticketID" class="hover:bg-gray-50">
                <td class="py-2 px-4 border-b">{{ item.ticketID }}</td>
                <td class="py-2 px-4 border-b">{{ item.entrance }}</td>
                <td class="py-2 px-4 border-b">{{ item.email }}</td>
                <td class="py-2 px-4 border-b">{{ item.phone }}</td>
                <td class="py-2 px-4 border-b">
                  <button class="bg-green-500 text-white px-3 py-1 rounded-md hover:bg-green-600 mb-2">
                    Download
                  </button>
                  <button class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 ml-2">
                    Delete
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
  const tableData = [
    { ticketID: '162838', entrance: '5 April 2024', email: 'RM450',phone:'0134980680' },
    { ticketID: '162930', entrance: '6 April 2024', email: 'RM100',phone:'0125639493' },
    { ticketID: '182793', entrance: '7 April 2024', email: 'RM200',phone:'01392339293' },
  ];
  </script>
  
  <style scoped>
  @import "tailwindcss/base";
  @import "tailwindcss/components";
  @import "tailwindcss/utilities";
  </style>