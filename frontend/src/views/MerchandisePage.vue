<script setup>
import { RouterLink } from 'vue-router';
</script>

<template>
  <div class="min-h-screen bg-[#FFC8DD] py-16">
    <!-- Header Section -->
    <div class="text-center mb-16">
      <h1 class="text-4xl font-bold text-[#554149] tracking-[1.5rem] font-['Plus_Jakarta_Sans'] uppercase mb-4">
        Merchandise
      </h1>
      <div class="w-24 h-1 bg-[#dcc39c] mx-auto"></div>
    </div>

    <!-- Merchandise Grid Section -->
    <div class="max-w-7xl mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
        
        <!-- Merchandise Card 1 -->
        <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
          <div class="relative overflow-hidden">
            <img 
              src="@/assets/images/merchant1.png" 
              alt="Black Pink Cotton Tee" 
              class="w-full h-[400px] object-cover transform group-hover:scale-105 transition-transform duration-500"
            />
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="p-6">
            <h2 class="text-xl font-semibold text-[#554149] mb-2">
              Black Pink Cotton Tee
            </h2>
            <p class="text-lg font-medium text-[#927e5b] mb-4">
              RM 38
            </p>
            <RouterLink to="/merchandise-detail" class="block">
              <button class="w-full py-3 bg-[#dcc39c] text-[#554149] rounded-lg font-medium
                           hover:bg-[#c4ad89] transition-colors duration-300 flex items-center justify-center gap-2">
                <span>View Details</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </RouterLink>
          </div>
        </div>

        <!-- Merchandise Card 2 -->
        <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
          <div class="relative overflow-hidden">
            <img 
              src="@/assets/images/merchant2.png" 
              alt="White Pink Cotton Tee" 
              class="w-full h-[400px] object-cover transform group-hover:scale-105 transition-transform duration-500"
            />
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="p-6">
            <h2 class="text-xl font-semibold text-[#554149] mb-2">
              White Pink Cotton Tee
            </h2>
            <p class="text-lg font-medium text-[#927e5b] mb-4">
              RM 38
            </p>
            <RouterLink to="/merchandise-detail2" class="block">
              <button class="w-full py-3 bg-[#dcc39c] text-[#554149] rounded-lg font-medium
                           hover:bg-[#c4ad89] transition-colors duration-300 flex items-center justify-center gap-2">
                <span>View Details</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </RouterLink>
          </div>
        </div>

        <!-- Merchandise Card 3 -->
        <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300">
          <div class="relative overflow-hidden">
            <img 
              src="@/assets/images/merchant3.png" 
              alt="White Blue Cotton Tee" 
              class="w-full h-[400px] object-cover transform group-hover:scale-105 transition-transform duration-500"
            />
            <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </div>
          
          <div class="p-6">
            <h2 class="text-xl font-semibold text-[#554149] mb-2">
              White Blue Cotton Tee
            </h2>
            <p class="text-lg font-medium text-[#927e5b] mb-4">
              RM 38
            </p>
            <RouterLink to="/merchandise-detail3" class="block">
              <button class="w-full py-3 bg-[#dcc39c] text-[#554149] rounded-lg font-medium
                           hover:bg-[#c4ad89] transition-colors duration-300 flex items-center justify-center gap-2">
                <span>View Details</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
            </RouterLink>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<style scoped>
@import 'tailwindcss/base';
@import 'tailwindcss/components';
@import 'tailwindcss/utilities';
</style>