<script setup>
import { RouterLink, RouterView } from 'vue-router';
import { useRouter } from 'vue-router';
import { onMounted, ref } from 'vue';
import apiClient from '../api.js';

const router = useRouter();

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
