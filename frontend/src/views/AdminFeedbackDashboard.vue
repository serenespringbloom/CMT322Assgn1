<script setup>
import { ref, onMounted ,computed} from 'vue';
import { formatDate, truncateText } from '@/utils/formatters';
const feedbacks = ref([]);


const averageRating = computed(() => {
  if (!feedbacks.value.length) return 0;
  const sum = feedbacks.value.reduce((acc, curr) => acc + curr.rating, 0);
  return (sum / feedbacks.value.length).toFixed(1);
});

const getLatestFeedbackDate = computed(() => {
  if (!feedbacks.value.length) return 'No feedback yet';
  const latest = new Date(Math.max(...feedbacks.value.map(f => new Date(f.created_at))));
  return formatDate(latest);
});

// Fetch feedbacks
const fetchFeedbacks = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/feedback`);
    const result = await response.json();
    if (result.success) {
      feedbacks.value = result.data;
    }
  } catch (error) {
    console.error('Error fetching feedbacks:', error);
  }
};

// Delete feedback
const deleteFeedback = async (id) => {
  if (!confirm('Are you sure you want to delete this feedback?')) return;
  
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/feedbacks/${id}`,
      { method: 'DELETE' }
    );
    const result = await response.json();
    
    if (result.success) {
      await fetchFeedbacks();
      alert('Feedback deleted successfully');
    }
  } catch (error) {
    console.error('Error deleting feedback:', error);
    alert('Failed to delete feedback');
  }
};

// Rating distribution helpers
const getRatingCount = (rating) => {
  return feedbacks.value.filter(f => f.rating === rating).length;
};

const getRatingPercentage = (rating) => {
  if (!feedbacks.value.length) return 0;
  return (getRatingCount(rating) / feedbacks.value.length) * 100;
};

// Fetch data on mount
onMounted(async () => {
  await Promise.all([
    fetchFeedbacks(),
    // ... existing fetch calls ...
  ]);
});
</script>

<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Main Container with max-width and center alignment -->
    <div class="max-w-7xl mx-auto">
      <!-- Header -->
      <div class="mb-8 text-center">
        <h2 class="text-3xl font-bold text-[#554149] mb-2">Customer Feedback</h2>
        <p class="text-gray-600">Manage and monitor customer feedback</p>
      </div>

      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Feedback Card -->
        <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Feedback</p>
              <p class="text-2xl font-bold text-[#554149]">{{ feedbacks.length }}</p>
            </div>
            <div class="p-3 bg-[#f4d0d6] rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#554149]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Average Rating Card -->
        <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Average Rating</p>
              <div class="flex items-center">
                <p class="text-2xl font-bold text-[#554149] mr-2">{{ averageRating }}</p>
                <span class="text-yellow-400 text-xl">★</span>
              </div>
            </div>
            <div class="p-3 bg-yellow-100 rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Latest Feedback Card -->
        <div class="bg-white rounded-xl shadow-md p-6 transition-transform hover:scale-105">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Latest Feedback</p>
              <p class="text-2xl font-bold text-[#554149]">{{ getLatestFeedbackDate }}</p>
            </div>
            <div class="p-3 bg-[#DCC39C] rounded-lg">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#554149]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Rating Distribution -->
      <div class="bg-white rounded-xl shadow-md p-6 mb-8">
        <h3 class="text-xl font-bold text-[#554149] mb-6">Rating Distribution</h3>
        <div class="space-y-4">
          <div v-for="i in 5" :key="i" class="flex items-center gap-4">
            <span class="w-16 text-right font-medium">{{ i }} ★</span>
            <div class="flex-1 bg-gray-100 rounded-full h-6 overflow-hidden">
              <div 
                class="h-full bg-gradient-to-r from-[#DCC39C] to-[#a48e69] transition-all duration-500"
                :style="{ width: `${getRatingPercentage(i)}%` }"
              ></div>
            </div>
            <span class="w-16 text-gray-600">{{ getRatingCount(i) }}</span>
          </div>
        </div>
      </div>

      <!-- Feedback Table -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Programme</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Feedback</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="feedback in feedbacks" :key="feedback.feedback_id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ feedback.ticket_id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ feedback.name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ feedback.programme }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex text-yellow-400">
                    <span v-for="star in 5" :key="star" 
                      :class="{ 'text-gray-300': star > feedback.rating }">★</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <p class="text-sm text-gray-500 max-w-xs truncate" :title="feedback.feedback_text">
                    {{ feedback.feedback_text }}
                  </p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(feedback.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <button 
                    @click="deleteFeedback(feedback.feedback_id)"
                    class="text-red-600 hover:text-red-900 transition-colors duration-200"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
.rating-distribution {
  background: white;
  padding: 1.5rem;
  border-radius: 10px;
  margin: 1.5rem 0;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.rating-bars {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-top: 1rem;
}

.rating-bar {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.star-label {
  width: 60px;
  text-align: right;
}

.bar-container {
  flex: 1;
  background: #f0f0f0;
  height: 20px;
  border-radius: 10px;
  overflow: hidden;
}

.bar {
  height: 100%;
  background: linear-gradient(135deg, #DCC39C, #a48e69);
  transition: width 0.3s ease;
}

.count {
  width: 40px;
  text-align: left;
}

.star-rating {
  color: #ccc;
  font-size: 1.2rem;
}

.star-rating .filled {
  color: #EACA14;
}

.feedback-text {
  display: inline-block;
  max-width: 300px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.delete-btn {
  background: #dc3545;
  color: white;
  border: none;
  padding: 0.25rem 0.75rem;
  border-radius: 5px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.delete-btn:hover {
  background: #c82333;
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  .rating-distribution {
    padding: 1rem;
  }
  
  .star-label {
    width: 40px;
  }
  
  .feedback-text {
    max-width: 150px;
  }
}
</style>