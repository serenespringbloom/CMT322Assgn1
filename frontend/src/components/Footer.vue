<script setup>
import { RouterLink } from 'vue-router';
</script>

<template>
  <footer class="bg-[#665758] text-white mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
        <!-- Left Logo -->
        <div class="flex justify-center md:justify-start">
          <img 
            src="../assets/images/persis-logo.png" 
            alt="PERSIS Logo" 
            class="h-32 w-auto object-contain"
          />
        </div>

        <!-- Center Navigation -->
        <div class="text-center space-y-6">
          <h2 class="text-2xl font-bold mb-4">MCB</h2>
          
          <!-- Navigation Links -->
          <nav>
            <ul class="flex flex-wrap justify-center gap-6 text-sm">
              <li>
                <RouterLink 
                  to="/" 
                  class="hover:text-[#ffc8dd] transition-colors duration-200"
                >
                  HOME
                </RouterLink>
              </li>
              <li>
                <RouterLink 
                  to="/ticket" 
                  class="hover:text-[#ffc8dd] transition-colors duration-200"
                >
                  TICKET
                </RouterLink>
              </li>
              <li>
                <RouterLink 
                  to="/merchandise" 
                  class="hover:text-[#ffc8dd] transition-colors duration-200"
                >
                  MERCHANDISE
                </RouterLink>
              </li>
              <li>
                <RouterLink 
                  to="/feedback" 
                  class="hover:text-[#ffc8dd] transition-colors duration-200"
                >
                  FEEDBACK
                </RouterLink>
              </li>
              <li>
                <RouterLink 
                  to="/refund" 
                  class="hover:text-[#ffc8dd] transition-colors duration-200"
                >
                  REFUND
                </RouterLink>
              </li>
              <li>
                <RouterLink 
                  to="/dashboard" 
                  class="hover:text-[#ffc8dd] transition-colors duration-200"
                >
                  ADMIN
                </RouterLink>
              </li>
            </ul>
          </nav>

          <!-- Social Media Links -->
          <div class="flex justify-center items-center gap-6 mt-6">
            <a 
              href="https://www.instagram.com/persis_usm/" 
              target="_blank" 
              aria-label="Instagram"
              class="text-white hover:text-[#ffc8dd] transition-colors duration-200"
            >
              <i class="fa fa-instagram text-2xl"></i>
            </a>
            <a 
              href="https://www.facebook.com/people/Persis-Usm/pfbid0itJdVSwedqY1HNc1W9jEgVnyDFxR5gLCCKafzMYoxLobiVET2mZWVGfFXiLWGBm7l/?mibextid=ZbWKwL" 
              target="_blank" 
              aria-label="Facebook"
              class="text-white hover:text-[#ffc8dd] transition-colors duration-200"
            >
              <i class="fa fa-facebook text-2xl"></i>
            </a>
            <a 
              href="https://www.youtube.com/@persisusm4596" 
              target="_blank" 
              aria-label="Youtube"
              class="text-white hover:text-[#ffc8dd] transition-colors duration-200"
            >
              <i class="fa fa-youtube text-2xl"></i>
            </a>
            <a 
              href="https://linktr.ee/persisusm" 
              target="_blank" 
              aria-label="Linktree"
              class="text-white hover:text-[#ffc8dd] transition-colors duration-200"
            >
              <svg 
                xmlns="http://www.w3.org/2000/svg" 
                width="24" 
                height="24" 
                viewBox="0 0 24 24"
                class="fill-current"
              >
                <path d="m13.736 5.853l4.005-4.117l2.325 2.38l-4.2 4.005h5.908v3.305h-5.937l4.229 4.108l-2.325 2.334l-5.74-5.769l-5.741 5.769l-2.325-2.325l4.229-4.108H2.226V8.121h5.909l-4.2-4.004l2.324-2.381l4.005 4.117V0h3.472zm-3.472 10.306h3.472V24h-3.472z"/>
              </svg>
            </a>
          </div>
        </div>

        <!-- Right Logo -->
        <div class="flex justify-center md:justify-end">
          <img 
            src="../assets/images/usm-logo.png" 
            alt="USM Logo" 
            class="h-32 w-auto object-contain"
          />
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
/* Import Font Awesome */
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
</style>