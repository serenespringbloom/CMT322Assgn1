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

  <body class="body">
    <div id="admin">
        <div class="sidebar ">
        <div class="side-logo">Admin Dashboard</div>
        <ul class="side-nav-links">
      <li><RouterLink to="/home">Home</RouterLink></li>
      <li><RouterLink to="/event">Event</RouterLink></li>
      <li><RouterLink to="/billing">Billing</RouterLink></li>
      <li><RouterLink to="/feedback-admin">Feedback</RouterLink></li>
      <li><RouterLink to="/">Back To<br> Main Page</RouterLink></li>
      <button @click="handleLogout">Logout</button>
    </ul>

      </div>
    
      <div class="admin-content">
        <RouterView />
      </div>

    </div>
    
  </body>
</template>

<style >
  #admin {
    display: flex;
    height: 100vh;
    font-family: 'Plus Jakarta Sans', sans-serif;
  }
  
  .admin-content {
    flex: 1;
    padding: 1rem;
            background: rgba(255, 200, 221, 0.8);
            background-color: ;
    overflow-y: auto;
  }

  .sidebar {
    width: 300px;
    background-color: #d058d0;
    color: white;
    padding: 1rem;
    border-color:#9c0f9c;
    border-width: 12px;
    
  }
  
  .side-logo {
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
    margin-bottom: 2rem;
  }
  
  .side-nav-links {
    list-style: none;
    padding-left: 50px;
  }
  
  .side-nav-links li {
    margin: 2rem 0;
  }
  
  .side-nav-links a {
    color: white;
    text-decoration: none;
    font-weight: 800; 
    font-size: 16px;
  }
  
  .side-nav-links a.router-link-active {
    color: black;
  }
</style>