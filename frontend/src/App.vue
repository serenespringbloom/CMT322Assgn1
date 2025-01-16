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
const hideHomeLinks = computed(() => route.meta.home === false);

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

// Add this to your existing script setup
const isRefundMenuOpen = ref(false);

const toggleRefundMenu = () => {
  isRefundMenuOpen.value = !isRefundMenuOpen.value;
};

// Close dropdown when clicking outside
onMounted(() => {
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.dropdown')) {
      isRefundMenuOpen.value = false;
    }
  });
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
    <div class="backgroundImage" v-if="hideHomeLinks"></div>
    <nav v-if="!hideHeader" id="navTop">
      <!-- Brand/Logo -->
      <div class="brand">
        <RouterLink to="/">
          <img :src="logo" alt="Malam Citra Bayu Logo" />
        </RouterLink>
      </div>

      <!-- Hamburger Icon -->
      <button class="nav-toggle" @click="toggleMenu" aria-label="Toggle Menu">
        <span :class="{ open: isMenuOpen }"></span>
        <span :class="{ open: isMenuOpen }"></span>
        <span :class="{ open: isMenuOpen }"></span>
      </button>

      <!-- Navigation Links -->
      <ul class="nav-links" :class="{ open: isMenuOpen }">
        <li>
          <RouterLink to="/" class="nav-item">
            <span class="icon">🏠</span>
            <span class="text">HOME</span>
          </RouterLink>
        </li>
        <li v-if="!hideHomeLinks">
          <a href="#about" class="nav-item">
            <span class="icon">🎉</span>
            <span class="text">EVENT</span>
          </a>
        </li>
        <li v-if="!hideHomeLinks">
          <a href="#location" class="nav-item">
            <span class="icon">📞</span>
            <span class="text">CONTACT</span>
          </a>
        </li>
        <li>
          <RouterLink to="/ticket" class="nav-item">
            <span class="icon">🎫</span>
            <span class="text">TICKET</span>
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/merchandise" class="nav-item">
            <span class="icon">🛍️</span>
            <span class="text">MERCHANDISE</span>
          </RouterLink>
        </li>
        <li>
          <RouterLink to="/feedback" class="nav-item">
            <span class="icon">🗣️</span>
            <span class="text">FEEDBACK</span>
          </RouterLink>
        </li>
        <li class="dropdown">
          <a href="#" @click.prevent="toggleRefundMenu" class="nav-item dropdown-trigger">
            <span class="icon">💸</span>
            <span class="text">REFUND</span>
            <span class="dropdown-arrow" :class="{ 'open': isRefundMenuOpen }">▼</span>
          </a>
          <ul class="dropdown-menu" :class="{ 'show': isRefundMenuOpen }">
            <li>
              <RouterLink to="/merchandise-refund" class="dropdown-item">
                <span class="icon">🛍️</span>
                <span class="text">Merchandise Refund</span>
              </RouterLink>
            </li>
            <li>
              <RouterLink to="/ticket-refund" class="dropdown-item">
                <span class="icon">🎫</span>
                <span class="text">Ticket Refund</span>
              </RouterLink>
            </li>
          </ul>
        </li>
        <li>
          <RouterLink to="/admin/dashboard" class="nav-item">
            <span class="icon">🛠️</span>
            <span class="text">ADMIN</span>
          </RouterLink>
        </li>
      </ul>

      <!-- Cart -->
      <RouterLink to="/cart" class="cart">
        <span class="cart-icon">🛒</span>
        <span class="cart-text">CART</span>
        <span class="cart-amount">RM {{ totalPrice }}</span>
      </RouterLink>
    </nav>
    <main><RouterView /></main>
    <footer v-if="!hideFooter" class="footer">
      <Footer />
    </footer>
  </body>
</template>

<style scoped>
/* Base styles */
template, body {
  height: 100%;
  margin: 0;
  display: flex;
  flex-direction: column;
}

/* Navigation */
#navTop {
  background: linear-gradient(to right, #f4d0d6, #DCC39C);
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 1000;
}

/* Brand/Logo */
.brand img {
  height: 50px;
  transition: transform 0.3s ease;
}

.brand img:hover {
  transform: scale(1.05);
}

/* Navigation Links */
.nav-links {
  display: flex;
  gap: 1.5rem;
  list-style: none;
  margin: 0;
  padding: 0;
  align-items: center;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  color: #554149;
  text-decoration: none;
  font-weight: 500;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.nav-item:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
}

.nav-item .icon {
  font-size: 1.2rem;
}

/* Dropdown */
.dropdown {
  position: relative;
}

.dropdown-trigger {
  padding-right: 2rem;
}

.dropdown-arrow {
  position: absolute;
  right: 0.75rem;
  transition: transform 0.3s ease;
}

.dropdown-arrow.open {
  transform: rotate(180deg);
}

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  min-width: 200px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  opacity: 0;
  visibility: hidden;
  transform: translateY(-10px);
  transition: all 0.3s ease;
  padding: 0.5rem;
  list-style: none;
}

.dropdown-menu.show {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

.dropdown-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1rem;
  color: #554149;
  text-decoration: none;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.dropdown-item:hover {
  background: #f8f9fa;
}

/* Cart */
.cart {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  background: #DCC39C;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  color: #554149;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.cart:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.cart-icon {
  font-size: 1.2rem;
}

.cart-amount {
  font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
  .nav-toggle {
    display: block;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
  }

  .nav-toggle span {
    display: block;
    width: 25px;
    height: 2px;
    background: #554149;
    margin: 5px 0;
    transition: all 0.3s ease;
  }

  .nav-toggle span.open:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
  }

  .nav-toggle span.open:nth-child(2) {
    opacity: 0;
  }

  .nav-toggle span.open:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
  }

  .nav-links {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    flex-direction: column;
    background: #f4d0d6;
    padding: 1rem;
    gap: 0.5rem;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s ease;
  }

  .nav-links.open {
    max-height: 500px;
  }

  .dropdown-menu {
    position: static;
    box-shadow: none;
    padding-left: 1rem;
  }

  .cart {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
  }
}
</style>
