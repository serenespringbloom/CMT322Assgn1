<template>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Sales Trend Chart -->
    <div class="bg-white rounded-xl shadow-md p-6">
      <h3 class="text-lg font-semibold text-[#554149] mb-4">Sales Trend</h3>
      <canvas ref="salesChart"></canvas>
    </div>

    <!-- Feedback Distribution Chart -->
    <div class="bg-white rounded-xl shadow-md p-6">
      <h3 class="text-lg font-semibold text-[#554149] mb-4">Feedback Distribution</h3>
      <canvas ref="feedbackChart"></canvas>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';

const props = defineProps({
  recentActivities: {
    type: Array,
    required: true
  },
  feedbackData: {
    type: Object,
    required: true
  }
});

const salesChart = ref(null);
const feedbackChart = ref(null);

const initSalesChart = () => {
  const ctx = salesChart.value.getContext('2d');
  const salesData = processRecentActivities(props.recentActivities);
  
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: salesData.labels,
      datasets: [{
        label: 'Ticket Sales',
        data: salesData.ticketSales,
        borderColor: '#554149',
        tension: 0.4
      }, {
        label: 'Merchandise Sales',
        data: salesData.merchandiseSales,
        borderColor: '#a48e69',
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
};

const initFeedbackChart = () => {
  const ctx = feedbackChart.value.getContext('2d');
  
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['5★', '4★', '3★', '2★', '1★'],
      datasets: [{
        data: [
          props.feedbackData.fiveStar || 0,
          props.feedbackData.fourStar || 0,
          props.feedbackData.threeStar || 0,
          props.feedbackData.twoStar || 0,
          props.feedbackData.oneStar || 0
        ],
        backgroundColor: [
          '#554149',
          '#6b525a',
          '#82636c',
          '#98747d',
          '#ae858f'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom'
        }
      }
    }
  });
};

const processRecentActivities = (activities) => {
  const dates = [...new Set(activities.map(a => 
    new Date(a.date).toLocaleDateString()))];
  
  const ticketSales = dates.map(date => 
    activities
      .filter(a => new Date(a.date).toLocaleDateString() === date && a.type === 'TICKET')
      .reduce((sum, a) => sum + a.amount, 0)
  );
  
  const merchandiseSales = dates.map(date => 
    activities
      .filter(a => new Date(a.date).toLocaleDateString() === date && a.type === 'MERCHANDISE')
      .reduce((sum, a) => sum + a.amount, 0)
  );

  return {
    labels: dates,
    ticketSales,
    merchandiseSales
  };
};

onMounted(() => {
  initSalesChart();
  initFeedbackChart();
});

watch(() => props.recentActivities, () => {
  initSalesChart();
}, { deep: true });

watch(() => props.feedbackData, () => {
  initFeedbackChart();
}, { deep: true });
</script> 