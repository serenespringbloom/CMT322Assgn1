<script setup lang="ts">
import { RouterLink, RouterView } from 'vue-router'
import logo from '@/assets/MCBLogo.png'

import { ref } from 'vue';

const isMenuOpen = ref(false);

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

</script>

<template>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Malam Citra Bayu</title>
  </head>

  <body>
    <nav id="navTop">
      <!-- <div class="nav-container">  -->
      <!-- Brand/Logo -->
      <div class="brand">
        <img :src="logo" alt="Malam Citra Bayu Logo" />
      </div>

      <!-- Hamburger Icon -->
      <button class="nav-toggle" @click="toggleMenu">
        <span :class="{ open: isMenuOpen }"></span>
      </button>

      <!--Navigation Links-->
      <ul class="nav-links" :class="{ open: isMenuOpen }">
        <li><RouterLink to="/">HOME</RouterLink></li>
        <li><RouterLink to="/event">EVENT</RouterLink></li>
        <li><RouterLink to="/ticket">TICKET</RouterLink></li>
        <li><RouterLink to="/merchandise">MERCHANDISE</RouterLink></li>
        <li><RouterLink to="/contact">CONTACT</RouterLink></li>
        <li><RouterLink to="/feedback">FEEDBACK</RouterLink></li>
      </ul>
      <div class="cart">RM 0.00 🛒</div>
    <!-- </div> -->
    </nav>
    <RouterView />
  </body>
</template>

<style scoped>
  #navTop {
    position: fixed;
    top: 0;
    left: 0;
    background: #f4d0d6; /* Background color for the header */
    z-index: 1000; /* Ensure navbar is above all other elements */
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between; /* Ensures items are spaced left and right */
    align-items: center; /* Vertically centers items */
    width: 100%; /* Full horizontal width */
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
  }


  .brand img {
  height: 50px; /* Adjust logo size */
  }

   /* body {
    padding-top: 3rem; /* This ensures spacing between navbar and the body. 6 percent.*/
   /* align-items: center;
  } */

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

  /* Hamburger button */
.nav-toggle {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  padding: 0.5rem;
}

.nav-toggle span {
  display: block;
  width: 30px;
  height: 3px;
  background: #333;
  margin: 6px 0;
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.nav-toggle span.open {
  transform: rotate(45deg) translate(5px, 5px);
}

.nav-toggle span.open + span {
  opacity: 0;
}

.nav-toggle span.open + span + span {
  transform: rotate(-45deg) translate(5px, -5px);
}

/* When windows is maximised */
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

 /* Responsive design, when windows is minimised */
@media (max-width: 768px) {
  .nav-links {
  flex-direction: column; /* Vertical layout */
  background: #f4d0d6;
  position: fixed; /* Stays on top */
  top: 60px;
  right: 0;
  width: 100%;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.3s ease-in-out;
  z-index: 999;
  padding: 0.8rem 0; /* Add padding around the list */
}

.nav-links.open {
  max-height: 400px; /* Adjust dropdown height */
}

.nav-links li {
  margin: 0.1rem 0; /* Add vertical spacing between list items */
}

.nav-links li a {
  display: block; /* Ensures clickable area spans the full width */
  text-decoration: none;
  font-weight: 500;
  color: #333; /* Dark text for links */
  padding: 0.5rem 1.5rem; /* Add padding for better spacing */
  border-radius: 4px; /* Rounded corners for hover effect */
  transition: color 0.3s, background-color 0.3s;
}

.nav-links li a:hover {
  color: #007bff;
  background-color: rgba(0, 123, 255, 0.1);
}
  .nav-toggle {
    display: block;
  }
}
</style>
