<template>
  <div class="min-h-screen bg-gray-50 p-6">
    <!-- Header Section -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">Merchandise Management</h1>
      <p class="text-gray-600">Manage orders and refunds</p>
    </div>

    <!-- Tab Navigation -->
    <div class="flex space-x-4 mb-8">
      <button 
        @click="activeTab = 'orders'"
        :class="[
          'px-6 py-3 rounded-lg font-medium transition-all duration-200',
          activeTab === 'orders' 
            ? 'bg-[#a48e69] text-white shadow-md' 
            : 'bg-white text-gray-600 hover:bg-gray-50'
        ]"
      >
        Orders
        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-opacity-20 bg-black">
          {{ orderCount }}
        </span>
      </button>
      <button 
        @click="activeTab = 'refunds'"
        :class="[
          'px-6 py-3 rounded-lg font-medium transition-all duration-200',
          activeTab === 'refunds' 
            ? 'bg-[#a48e69] text-white shadow-md' 
            : 'bg-white text-gray-600 hover:bg-gray-50'
        ]"
      >
        Refunds
        <span class="ml-2 px-2 py-1 text-xs rounded-full bg-opacity-20 bg-black">
          {{ refundCount }}
        </span>
      </button>
    </div>

    <!-- Orders Tab -->
    <div v-if="activeTab === 'orders'" class="bg-white rounded-lg shadow">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="order in orders" :key="order.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #{{ order.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ order.customer_name }}</div>
                <div class="text-sm text-gray-500">{{ order.customer_email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ order.merchandise?.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ order.size?.toUpperCase() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ order.quantity }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                RM {{ order.total_amount }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="{
                  'px-2 py-1 text-xs font-medium rounded-full': true,
                  'bg-amber-100 text-amber-800': order.status === 'PENDING',
                  'bg-green-100 text-green-800': order.status === 'COMPLETED',
                  'bg-blue-100 text-blue-800': order.status === 'REFUNDED'
                }">
                  {{ order.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ new Date(order.created_at).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <button 
                  v-if="order.status === 'PENDING'"
                  @click="processOrder(order.id, 'COMPLETED')" 
                  class="text-green-600 hover:text-green-900 font-medium"
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
    <div v-if="activeTab === 'refunds'" class="bg-white rounded-lg shadow">
      <div v-if="refunds.length === 0" class="p-8 text-center text-gray-500">
        No refund requests found.
      </div>
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Refund ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="refund in refunds" :key="refund.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                #{{ refund.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                #{{ refund.order_id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ refund.order?.customer_name }}</div>
                <div class="text-sm text-gray-500">{{ refund.order?.customer_email }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                RM {{ refund.refund_amount }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ refund.reason }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="{
                  'px-2 py-1 text-xs font-medium rounded-full': true,
                  'bg-amber-100 text-amber-800': refund.status === 'PENDING',
                  'bg-green-100 text-green-800': refund.status === 'COMPLETED',
                  'bg-red-100 text-red-800': refund.status === 'REJECTED'
                }">
                  {{ refund.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ new Date(refund.created_at).toLocaleDateString() }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="refund.status === 'PENDING'" class="flex space-x-2">
                  <button 
                    @click="processRefund(refund)" 
                    class="text-green-600 hover:text-green-900 font-medium text-sm"
                  >
                    Approve
                  </button>
                  <button 
                    @click="rejectRefund(refund.id)" 
                    class="text-red-600 hover:text-red-900 font-medium text-sm"
                  >
                    Reject
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Refund Process Modal -->
    <div v-if="showRefundModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg max-w-md w-full p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Process Refund</h2>
        <div class="space-y-3 mb-6">
          <p><span class="font-medium">Order ID:</span> #{{ selectedRefund?.order_id }}</p>
          <p><span class="font-medium">Customer:</span> {{ selectedRefund?.order?.customer_name }}</p>
          <p><span class="font-medium">Size:</span> {{ selectedRefund?.order?.size?.toUpperCase() }}</p>
          <p><span class="font-medium">Amount:</span> RM {{ selectedRefund?.refund_amount }}</p>
          <p><span class="font-medium">Reason:</span> {{ selectedRefund?.reason }}</p>
        </div>
        <div class="flex justify-end space-x-3">
          <button 
            @click="showRefundModal = false"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium"
          >
            Cancel
          </button>
          <button 
            @click="confirmRefund"
            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium"
          >
            Confirm Refund
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const activeTab = ref('orders');
const orders = ref([]);
const refunds = ref([]);
const showRefundModal = ref(false);
const selectedRefund = ref(null);
const orderCount = ref(0);
const refundCount = ref(0);

const fetchOrders = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/merchandise/orders`);
    const data = await response.json();
    if (data.success) {
      orders.value = data.data.orders;
      orderCount.value = data.data.orders.length;
    }
  } catch (error) {
    console.error('Error fetching orders:', error);
  }
};

const fetchRefunds = async () => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds`);
    const data = await response.json();
    
    if (data.success) {
      refunds.value = data.data || [];
      refundCount.value = data.data ? data.data.length : 0;
    } else {
      console.error('Failed to fetch refunds:', data.message);
      refunds.value = [];
      refundCount.value = 0;
    }
  } catch (error) {
    console.error('Error fetching refunds:', error);
    refunds.value = [];
    refundCount.value = 0;
  }
};

const processOrder = async (orderId, status) => {
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/api/admin/merchandise/orders/${orderId}/process`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ status })
    });
    
    if (response.ok) {
      await fetchOrders();
    }
  } catch (error) {
    console.error('Error processing order:', error);
  }
};

const processRefund = (refund) => {
  selectedRefund.value = refund;
  showRefundModal.value = true;
};

const confirmRefund = async () => {
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds/${selectedRefund.value.id}/process`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          action: 'APPROVE',
          adminId: 1
        })
      }
    );

    if (response.ok) {
      showRefundModal.value = false;
      selectedRefund.value = null;
      await fetchRefunds();
      await fetchOrders();
    }
  } catch (error) {
    console.error('Error processing refund:', error);
  }
};

const rejectRefund = async (refundId) => {
  try {
    const response = await fetch(
      `${import.meta.env.VITE_API_URL}/api/admin/merchandise/refunds/${refundId}/process`,
      {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          action: 'REJECT',
          adminId: 1
        })
      }
    );

    if (response.ok) {
      await fetchRefunds();
      await fetchOrders();
    }
  } catch (error) {
    console.error('Error rejecting refund:', error);
  }
};

onMounted(() => {
  fetchOrders();
  fetchRefunds();
});
</script>

<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>