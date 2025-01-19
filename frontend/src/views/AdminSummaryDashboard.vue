<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-[#554149] mb-2">Dashboard Summary</h2>
        <p class="text-gray-600">Overview of Sales and Customer Feedback</p>
      </div>

      <!-- Main Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ticket Sales Card -->
        <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Ticket Sales</p>
              <p class="text-2xl font-bold text-[#554149]">
                RM {{ Number(ticketSales || 0).toFixed(2) }}
              </p>
              <p class="text-xs text-green-600">{{ ticketCount || 0 }} tickets sold</p>
            </div>
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Merchandise Sales Card -->
        <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Merchandise Sales</p>
              <p class="text-2xl font-bold text-[#554149]">RM {{ merchandiseSales.toFixed(2) }}</p>
              <p class="text-xs text-green-600">{{ merchandiseCount }} items sold</p>
            </div>
            <div class="p-3 bg-purple-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Total Sales Card -->
        <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Sales</p>
              <p class="text-2xl font-bold text-[#554149]">RM {{ totalSales.toFixed(2) }}</p>
              <p class="text-xs text-green-600">Combined revenue</p>
            </div>
            <div class="p-3 bg-green-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- New Feedback Card -->
        <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">New Feedback</p>
              <p class="text-2xl font-bold text-[#554149]">{{ newFeedbackCount }}</p>
              <p class="text-xs text-green-600">Avg Rating: {{ averageRating }} ⭐</p>
            </div>
            <div class="p-3 bg-yellow-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Charts Section -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      
      </div>

      <!-- Recent Activity -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-2">
        <h3 class="text-lg font-semibold text-[#554149] mb-4">Recent Ticket Purchase Activity</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="activity in recentActivities" :key="activity.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(activity.date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getActivityTypeClass(activity.type)" class="px-2 py-1 text-xs rounded-full">
                    {{ activity.type }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ activity.details }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  RM {{ activity.amount.toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-md p-6">
        <h3 class="text-lg font-semibold text-[#554149] mb-4">Recent Merchandise Purchase Activity</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer Phone</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr v-for="activity in merchActivities" :key="activity.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(activity.date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span  class="px-2 py-1 text-xs rounded-full">
                    {{ activity.phone }}
                  </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ activity.details }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  RM {{ activity.amount.toFixed(2) }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { formatDate } from '@/utils/formatters';
// import DashboardCharts from '@/components/DashboardCharts.vue';

// State variables with default values
const ticketSales = ref(0);
const merchandiseSales = ref(0);
const ticketCount = ref(0);
const merchandiseCount = ref(0);
const newFeedbackCount = ref(0);
const averageRating = ref(0);
const recentActivities = ref([]);
const merchActivities = ref([]);
const feedbackCount = ref([]);

// Computed properties with number conversion
const totalSales = computed(() => {
  return Number(ticketSales.value || 0) + Number(merchandiseSales.value || 0);
});

// Helper functions
const getActivityTypeClass = (type) => {
  const classes = {
    'Ticket': 'bg-blue-100 text-blue-800',
    'Merchandise': 'bg-purple-100 text-purple-800',
    'Feedback': 'bg-yellow-100 text-yellow-800'
  };
  return classes[type] || 'bg-gray-100 text-gray-800';
};

// Fetch data with proper error handling and debugging
const fetchSummaryData = async () => {
  try {
    console.log('Fetching summary data...');
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/summary`);
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    const data = await response.json();
    console.log('Raw API Response:', data);
    
    if (data.success) {
      // Ensure we're getting numbers
      ticketSales.value = parseFloat(data.ticketSales) || 0;
      merchandiseSales.value = parseFloat(data.merchandiseSales) || 0;
      ticketCount.value = parseInt(data.ticketCount) || 0;
      merchandiseCount.value = parseInt(data.merchandiseCount) || 0;
      newFeedbackCount.value = parseInt(data.newFeedbackCount) || 0;
      averageRating.value = parseFloat(data.averageRating) || 0;
      recentActivities.value = data.recentActivities || [];
      merchActivities.value = data.merchActivities || [];
      feedbackCount.value = data.feedbackCount || [];
      
      console.log('Processed Values:', {
        ticketSales: ticketSales.value,
        merchandiseSales: merchandiseSales.value,
        ticketCount: ticketCount.value,
        merchandiseCount: merchandiseCount.value,
        debug: data.debug
      });
    } else {
      console.error('Failed to fetch summary data:', data.message);
    }
  } catch (error) {
    console.error('Error fetching summary data:', error);
  }
};

// Initialize
onMounted(() => {
  fetchSummaryData();
});
</script> 
<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>