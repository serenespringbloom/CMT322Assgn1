<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import { useRouter } from 'vue-router';

const router = useRouter();

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

  <body>
    <div id="admin">
      <div class="sidebar">
        <div class="side-logo">Admin Dashboard</div>
        <ul class="side-nav-links">
          <li><RouterLink to="/dashboard">Home</RouterLink></li>
          <li><RouterLink to="/form">Form</RouterLink></li>
          <li><RouterLink to="/table">Table</RouterLink></li>
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