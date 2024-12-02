<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import Header from './components/Header.vue'
</script>

<template>
  <head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malam Citra Bayu</title>
  </head>
  <header>
    <body>
      <nav id="navTop">
        <ul class="nav-links">
          <li><RouterLink to="/">HOME</RouterLink></li>
          <li><a href="#event">EVENT</a></li>
          <li><RouterLink to="/ticket">TICKET</RouterLink></li>
          <li><a href="#merchandise">MERCHANDISE</a></li>
          <li><a href="#contact">CONTACT</a></li>
          <li><a href="#feedback">FEEDBACK</a></li>
        </ul>
        <div class="cart">RM 0.00 🛒</div>
      </nav>
    </body>
  </header> 
  <RouterView />
</template>

<style scoped>
  header{
    width: 100%;
    position: absolute;
    top: -46%;
    left: 0;
  }
  #navTop {
    background: #f4d0d6; /* Background color for the header */
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between; /* Ensures items are spaced left and right */
    align-items: center; /* Vertically centers items */
    width: 100%; /* Full horizontal width */
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
  }

  .nav-links {
    display: flex;
    gap: 1.5rem; /* Evenly spaced links */
    list-style: none; /* Removes bullet points from the list */
    padding: 0;
    margin: 0;
  }
  
  .nav-links li a {
    text-decoration: none;
    font-weight: 500;
    color: #333; /* Dark text for links */
    padding: 0.5rem 1rem; /* Space around links */
    transition: color 0.3s, background-color 0.3s;
  }
  
  .nav-links li a:hover {
    color: #007bff; /* Change color on hover */
    background-color: rgba(0, 123, 255, 0.1); /* Subtle hover background */
    border-radius: 4px; /* Rounded corners for hover effect */
  }
  
  .cart {
    font-weight: bold;
    color: #333; /* Dark text for the cart */
    padding: 0.5rem 1rem;
  }
  
  .cart:hover {
    color: #007bff; /* Change color on hover */
    cursor: pointer; /* Pointer cursor to indicate it's interactive */
  }
  
  @media (max-width: 100%) {
    .navTop {
      flex-direction: column; /* Stack content on smaller screens */
      text-align: center;
    }
  
    .nav-links {
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
    }
  
    .cart {
      margin-top: 1rem; /* Space below navigation links */
    }
  }
</style>