<script setup>
import { RouterLink, RouterView, useRouter, useRoute } from 'vue-router';
import { ref, watch, onMounted, onUnmounted } from 'vue';
import apiClient from '../api.js';

const router = useRouter();
const route = useRoute();
const isSidebarOpen = ref(window.innerWidth >= 1024); // Open by default on desktop

// Toggle sidebar
const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};

// Close sidebar on route change for mobile
watch(route, () => {
  if (window.innerWidth < 1024) {
    isSidebarOpen.value = false;
  }
});

// Handle window resize
onMounted(() => {
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      isSidebarOpen.value = true;
    }
  });
});

onUnmounted(() => {
  window.removeEventListener('resize', () => {});
});

// Navigation Links with Icons
const navLinks = [
  {
    name: 'Dashboard',
    path: '/admin/dashboard',
    icon: 'svg-dashboard',
  },
  {
    name: 'Event',
    path: '/admin/event',
    icon: 'svg-event',
  },
  {
    name: 'Feedback',
    path: '/admin/feedback',
    icon: 'svg-feedback',
  },
  {
    name: 'Tickets',
    path: '/admin/tickets',
    icon: 'svg-tickets',
  },
  {
    name: 'Merchandise',
    path: '/admin/merchandise',
    icon: 'svg-merchandise',
  }
];

// Check if route is active
const isActiveRoute = (path) => {
  return route.path === path;
};

const handleLogout = async () => {
  try {
    const token = sessionStorage.getItem('token');

    if (token) {
      // Call the logout API
      await apiClient.post('/admin/logout', {}, {
        headers: { Authorization: `Bearer ${token}` },
      });
    }

    // Remove token from session storage
    sessionStorage.removeItem('token');

    // Redirect to login page
    router.push('/login');
  } catch (err) {
    console.error('Logout Error:', err);
    alert('An error occurred during logout. Please try again.');
  }
};
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Toggle Button for Mobile -->
    <button 
      @click="toggleSidebar"
      class="lg:hidden fixed top-4 left-4 z-20 p-2 rounded-lg bg-[#2c3e50] text-white hover:bg-[#34495e]"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path v-if="!isSidebarOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <!-- Sidebar -->
    <div 
      :class="{
        'fixed inset-y-0 left-0 transform lg:relative lg:translate-x-0 transition duration-200 ease-in-out z-10': true,
        '-translate-x-full': !isSidebarOpen,
        'translate-x-0': isSidebarOpen
      }"
      class="w-64 bg-gradient-to-b from-[#2c3e50] to-[#34495e] shadow-xl"
    >
      <!-- Logo Section -->
      <div class="p-4 border-b border-gray-600">
        <div class="flex flex-col items-center space-y-3">
          <img src="@/assets/MCBLogo.png" class="h-16 w-auto" alt="MCB Logo" />
          <h1 class="text-xl font-bold text-white tracking-wide">Admin Panel</h1>
        </div>
      </div>

      <!-- Navigation Links -->
      <nav class="mt-6 px-4">
        <div class="space-y-3">
          <RouterLink 
            v-for="link in navLinks" 
            :key="link.path"
            :to="link.path"
            class="flex items-center px-4 py-3 text-gray-300 hover:bg-[#3d566e] rounded-lg
                   transition-colors duration-200"
            :class="{ 'bg-[#3d566e] text-white': isActiveRoute(link.path) }"
          >
            <component :is="link.icon" class="w-5 h-5 mr-3" />
            {{ link.name }}
          </RouterLink>

          <!-- Back to Main -->
          <RouterLink 
            to="/"
            class="flex items-center px-4 py-3 text-gray-300 hover:bg-[#3d566e] rounded-lg
                   transition-colors duration-200 mt-6"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            Back to Main
          </RouterLink>

          <!-- Logout Button -->
          <button 
            @click="handleLogout"
            class="w-full flex items-center px-4 py-3 text-gray-300 hover:bg-red-600/20 hover:text-red-400
                   rounded-lg transition-colors duration-200 mt-6"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
          </button>
        </div>
      </nav>
    </div>

    <!-- Overlay for mobile -->
    <div 
      v-if="isSidebarOpen" 
      @click="toggleSidebar"
      class="fixed inset-0 bg-black bg-opacity-50 lg:hidden z-0"
    ></div>

    <!-- Main Content -->
    <div class="flex-1 overflow-hidden">
      <div class="h-full overflow-y-auto bg-gray-100">
        <RouterView />
      </div>
    </div>
  </div>
</template>

<script>
// SVG Icons Components
export default {
  components: {
    'svg-dashboard': {
      template: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
      `
    },
    'svg-event': {
      template: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
      `
    },
    'svg-feedback': {
      template: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
        </svg>
      `
    },
    'svg-tickets': {
      template: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
        </svg>
      `
    },
    'svg-merchandise': {
      template: `
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
      `
    }
  }
}
</script>

<style scoped>
#admin {
  display: flex;
  height: 100vh;
  font-family: Arial, sans-serif;
}

.admin-content {
  flex: 1;
  padding: 1rem;
  background-color: #f5f7fa;
  overflow-y: auto;
}

.sidebar {
  width: 250px;
  background-color: #2c3e50;
  color: white;
  padding: 1rem;
}

.side-logo {
  font-size: 1.5rem;
  font-weight: bold;
  text-align: center;
  margin-bottom: 2rem;
}

.side-nav-links {
  list-style: none;
  padding: 0;
}

.side-nav-links li {
  margin: 1rem 0;
}

.side-nav-links a {
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.side-nav-links a.router-link-active {
  color: #3498db;
}
</style>
