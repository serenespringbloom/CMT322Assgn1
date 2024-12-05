<script setup>
import { ref, computed, onMounted } from "vue";
import { useRoute } from "vue-router";
import logo from "@/assets/MCBLogo.png";
import Footer from "./components/Footer.vue";

// Navigation state
const isMenuOpen = ref(false);
const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

// Route handling
const route = useRoute();
const hideHeader = computed(() => route.meta.showHeader === false);
const hideFooter = computed(() => route.meta.showFooter === false);

// Cart-related logic
const cart = ref([]);

// Calculate total price
const totalPrice = computed(() =>
  cart.value.reduce((total, item) => total + item.price * item.quantity, 0).toFixed(2)
);

// Load cart data from localStorage
const updateCartFromLocalStorage = () => {
  cart.value = JSON.parse(localStorage.getItem("cart")) || [];
};

// Listen for cart updates
window.addEventListener("cartUpdated", updateCartFromLocalStorage);

// Load cart on component mount
onMounted(() => {
  updateCartFromLocalStorage();
});
</script>

<template>
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Malam Citra Bayu</title>
  </head>

  <body>
    <nav v-if="!hideHeader" id="navTop">
      <!-- Brand/Logo -->
      <div class="brand">
        <img :src="logo" alt="Malam Citra Bayu Logo" />
      </div>

      <!-- Hamburger Icon -->
      <button class="nav-toggle" @click="toggleMenu">
        <span :class="{ open: isMenuOpen }"></span>
      </button>

      <!-- Navigation Links -->
      <ul class="nav-links" :class="{ open: isMenuOpen }">
        <li><RouterLink to="/">HOME</RouterLink></li>
        <li><a href="#about">EVENT</a></li>
        <li><RouterLink to="/ticket">TICKET</RouterLink></li>
        <li><RouterLink to="/merchandise">MERCHANDISE</RouterLink></li>
        <li><a href="#location">CONTACT</a></li>
        <li><RouterLink to="/feedback">FEEDBACK</RouterLink></li>
        <li><RouterLink to="/refund">REFUND</RouterLink></li>
        <span><RouterLink to="/dashboard">ADMIN</RouterLink></span>
      </ul>

      <!-- Cart Total -->
      <div class="cart">
        <RouterLink to="/cart">{{ totalPrice }} 🛒</RouterLink>
      </div>
    </nav>
    <main><RouterView /></main>
    <footer v-if="!hideFooter" class="footer">
      <Footer />
    </footer>
  </body>
</template>


<style scoped>
  template, body {
    height: 100%; /* Ensure the html and body stretch to fill the viewport */
    margin: 0; /* Remove default margin */
    display: flex;
    flex-direction: column; /* Use a column layout */
  }

  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Make the body fill the viewport height */
    margin: 0; /* Remove default body margin */
  }

  main {
    flex-grow: 1;
    display: inline-block;
  }

  #navTop {
    display: inline-block;
    top: 0;
    left: 0;
    background: #f4d0d6;
    z-index: 1000;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    flex-shrink: 0;
    align-items: center;
    width: 100%;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
  }

  .brand img {
  height: 50px; /* Adjust logo size */
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
      flex-direction: column;
      text-align: center;
    }

    .nav-links {
      justify-content: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .cart {
      margin-top: 1rem;
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
