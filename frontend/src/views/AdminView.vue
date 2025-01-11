<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import { useRouter } from 'vue-router';
import { onMounted, ref } from 'vue';
const router = useRouter();
const dashboardData = ref(null);
const error = ref('');
const loading = ref(true);

const fetchDashboard = async () => {
  try {
    const token = localStorage.getItem('admin_token');
    if (!token) {
      router.push('/login');
      return;
    }

    const response = await fetch('http://127.0.0.1:8000/api/admin/dashboard', {
      headers: {
        'Authorization': token
      }
    });
    
    if (response.ok) {
      const data = await response.json();
      dashboardData.value = data;
    } else {
      if (response.status === 401) {
        localStorage.removeItem('admin_token');
        router.push('/login');
      }
      throw new Error('Failed to fetch dashboard data');
    }
  } catch (err) {
    error.value = 'Error loading dashboard data';
    console.error('Dashboard error:', err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDashboard();
});

const handleLogout = () => {
  sessionStorage.removeItem('isAuthenticated');
  router.push('/login'); // Redirect to login page
};
</script>

<template>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malam Citra Bayu Admin</title>
  </head>

  <body class="body">
    <div id="admin">
      <div class="sidebar">
        <div class="side-logo">Admin Dashboard</div>
        <ul class="side-nav-links">
          <li><RouterLink to="/dashboard">Home</RouterLink></li>
          <li><RouterLink to="/event">Event</RouterLink></li>
          <li><RouterLink to="/feedback-admin">Feedback</RouterLink></li>
          <li><RouterLink to="/billing">Billing</RouterLink></li>
          <li><RouterLink to="/refunding">Refund</RouterLink></li>
          <li><RouterLink to="/">Back To Main Page</RouterLink></li>
          <button @click="handleLogout">Logout</button>
        </ul>
      </div>
      <div class="admin-content">
        <RouterView />
      </div>
    </div>
  </body>
</template>

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