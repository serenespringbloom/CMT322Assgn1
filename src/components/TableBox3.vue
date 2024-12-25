<template>
    <div class="container mx-auto p-6">
      <!-- Box Container -->
      <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
        <!-- Header -->
         <div class=" bg-pink-500 flex item-center justify-center">
        <div class="bg-pink-500 text-white p-4 text-xl font-bold">
          Merchandise Invoice
        </div>
    </div>
        <!-- Table Section -->
        <div class="overflow-x-auto">
          <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-100">
              <tr>
                <th class="py-2 px-4 border-b text-left">Date</th>
                <th class="py-2 px-4 border-b text-left">Ticket ID</th>
                <th class="py-2 px-4 border-b text-left">Amount</th>
                <th class="py-2 px-4 border-b text-left">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in tableData" :key="item.ticketID" class="hover:bg-gray-50">
                <td class="py-2 px-4 border-b">{{ item.date }}</td>
                <td class="py-2 px-4 border-b">{{ item.ticketID }}</td>
                <td class="py-2 px-4 border-b">{{ item.amount }}</td>
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
    { ticketID: '159256', date: '5 April 2024', amount: 'RM450' },
    { ticketID: '145756', date: '6 April 2024', amount: 'RM100' },
    { ticketID: '232445', date: '7 April 2024', amount: 'RM200' },
  ];
  </script>
  