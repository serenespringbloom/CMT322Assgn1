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
const totalPrice = computed(() =>
  cart.value.reduce((total, item) => total + item.price * item.quantity, 0).toFixed(2)
);

// Refund dropdown state
const isRefundMenuOpen = ref(false);
const toggleRefundMenu = () => {
  isRefundMenuOpen.value = !isRefundMenuOpen.value;
};

// Load cart data and handle clicks
onMounted(() => {
  updateCartFromLocalStorage();
  document.addEventListener('click', (e) => {
    const target = e.target;
    const isHamburger = target.closest('.hamburger-button');
    const isMenu = target.closest('.mobile-menu');
    
    if (!isHamburger && !isMenu && isMenuOpen.value) {
      isMenuOpen.value = false;
    }
  });
});

const updateCartFromLocalStorage = () => {
  cart.value = JSON.parse(localStorage.getItem("cart")) || [];
};

window.addEventListener("cartUpdated", updateCartFromLocalStorage);
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <div class="backgroundImage" v-if="hideHomeLinks"></div>
    
    <!-- Navbar -->
    <nav v-if="!hideHeader" class="bg-gradient-to-r from-[#f4d0d6] to-[#DCC39C] sticky top-0 z-50 shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo -->
          <RouterLink to="/" class="flex-shrink-0">
            <img :src="logo" alt="MCB Logo" class="h-12 w-auto hover:scale-105 transition-transform duration-200">
          </RouterLink>

          <!-- Hamburger Menu Button -->
          <button 
            @click.stop="toggleMenu"
            class="hamburger-button p-2 rounded-md text-[#554149] hover:bg-[#e8c5ca] transition-colors duration-200"
          >
            <svg 
              class="h-6 w-6" 
              :class="{ 'hidden': isMenuOpen }"
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg 
              class="h-6 w-6" 
              :class="{ 'hidden': !isMenuOpen }"
              xmlns="http://www.w3.org/2000/svg" 
              fill="none" 
              viewBox="0 0 24 24" 
              stroke="currentColor"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- Navigation Links -->
          <div 
            class="hidden lg:flex lg:items-center lg:space-x-4 nav-links"
          >
            <RouterLink 
              to="/" 
              class="nav-link"
            >
              <span class="text-xl">🏠</span>
              <span>HOME</span>
            </RouterLink>

            <a 
              v-if="!hideHomeLinks" 
              href="#about" 
              class="nav-link"
            >
              <span class="text-xl">🎉</span>
              <span>EVENT</span>
            </a>

            <a 
              v-if="!hideHomeLinks" 
              href="#location" 
              class="nav-link"
            >
              <span class="text-xl">📞</span>
              <span>CONTACT</span>
            </a>

            <RouterLink 
              to="/ticket" 
              class="nav-link"
            >
              <span class="text-xl">🎫</span>
              <span>TICKET</span>
            </RouterLink>

            <RouterLink 
              to="/merchandise" 
              class="nav-link"
            >
              <span class="text-xl">🛍️</span>
              <span>MERCHANDISE</span>
            </RouterLink>

            <RouterLink 
              to="/feedback" 
              class="nav-link"
            >
              <span class="text-xl">🗣️</span>
              <span>FEEDBACK</span>
            </RouterLink>

            <!-- Refund Dropdown -->
            <div class="relative dropdown">
              <button 
                @click.prevent="toggleRefundMenu"
                class="nav-link"
              >
                <span class="text-xl">💸</span>
                <span>REFUND</span>
                <span 
                  class="ml-1 transition-transform duration-200"
                  :class="{ 'rotate-180': isRefundMenuOpen }"
                >
                  ▼
                </span>
              </button>

              <div 
                v-show="isRefundMenuOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2"
              >
                <RouterLink 
                  to="/merchandise-refund" 
                  class="block px-4 py-2 text-[#554149] hover:bg-[#f4d0d6] transition-colors duration-200"
                >
                  🛍️ Merchandise Refund
                </RouterLink>
                <RouterLink 
                  to="/ticket-refund" 
                  class="block px-4 py-2 text-[#554149] hover:bg-[#f4d0d6] transition-colors duration-200"
                >
                  🎫 Ticket Refund
                </RouterLink>
              </div>
            </div>

            <RouterLink 
              to="/admin/dashboard" 
              class="nav-link"
            >
              <span class="text-xl">🛠️</span>
              <span>ADMIN</span>
            </RouterLink>
          </div>

          <!-- Cart -->
          <RouterLink 
            to="/cart"
            class="hidden lg:flex items-center px-4 py-2 bg-[#DCC39C] rounded-lg text-[#554149] 
                   hover:bg-[#c4ad89] transition-colors duration-200"
          >
            <span class="text-xl mr-2">🛒</span>
            <span class="font-medium">RM {{ totalPrice }}</span>
          </RouterLink>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div 
        v-show="isMenuOpen"
        class="mobile-menu absolute top-16 right-0 w-64 bg-white shadow-lg rounded-lg mt-2 py-2 z-50"
      >
        <div class="px-2 pt-2 pb-3 space-y-1">
          <RouterLink 
            to="/" 
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">🏠</span>
            <span>HOME</span>
          </RouterLink>

          <a 
            v-if="!hideHomeLinks" 
            href="#about" 
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">🎉</span>
            <span>EVENT</span>
          </a>

          <a 
            v-if="!hideHomeLinks" 
            href="#location" 
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">📞</span>
            <span>CONTACT</span>
          </a>

          <RouterLink 
            to="/ticket" 
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">🎫</span>
            <span>TICKET</span>
          </RouterLink>

          <RouterLink 
            to="/merchandise" 
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">🛍️</span>
            <span>MERCHANDISE</span>
          </RouterLink>

          <RouterLink 
            to="/feedback" 
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">🗣️</span>
            <span>FEEDBACK</span>
          </RouterLink>

          <div class="relative">
            <button 
              @click="toggleRefundMenu"
              class="mobile-nav-link w-full text-left"
            >
              <span class="text-xl">💸</span>
              <span>REFUND</span>
              <span 
                class="ml-1 transition-transform duration-200"
                :class="{ 'rotate-180': isRefundMenuOpen }"
              >
                ▼
              </span>
            </button>

            <div 
              v-show="isRefundMenuOpen"
              class="pl-8 space-y-1"
            >
              <RouterLink 
                to="/merchandise-refund" 
                class="mobile-nav-link"
                @click="isMenuOpen = false"
              >
                <span class="text-xl">🛍️</span>
                <span>Merchandise Refund</span>
              </RouterLink>
              <RouterLink 
                to="/ticket-refund" 
                class="mobile-nav-link"
                @click="isMenuOpen = false"
              >
                <span class="text-xl">🎫</span>
                <span>Ticket Refund</span>
              </RouterLink>
            </div>
          </div>

          <RouterLink 
            to="/admin/dashboard" 
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">🛠️</span>
            <span>ADMIN</span>
          </RouterLink>

          <RouterLink 
            to="/cart"
            class="mobile-nav-link"
            @click="isMenuOpen = false"
          >
            <span class="text-xl">🛒</span>
            <span>CART - RM {{ totalPrice }}</span>
          </RouterLink>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
      <RouterView />
    </main>

    <!-- Footer -->
    <footer v-if="!hideFooter">
      <Footer />
    </footer>
  </div>
</template>

<style>
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';

@layer components {
  .nav-link {
    @apply flex items-center px-4 py-2 text-[#554149] rounded-lg hover:bg-[#e8c5ca] 
           transition-colors duration-200 font-medium space-x-2;
  }

  .mobile-nav-link {
    @apply flex items-center px-4 py-2 text-[#554149] rounded-lg hover:bg-[#e8c5ca] 
           transition-colors duration-200 font-medium space-x-2 w-full;
  }
}

.mobile-menu {
  @apply transition-all duration-200 ease-in-out transform;
}
</style>
