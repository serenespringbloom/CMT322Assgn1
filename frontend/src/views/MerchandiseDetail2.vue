<script setup>
import { ref } from 'vue';

const merchandise = ref([
  { id: 2, name: "White Cotton Tee", price: 38, image: "../assets/images/merchant2.png" }
]);

const selectedSize = ref("");
const quantity = ref(1);

const increment = () => {
  quantity.value++;
};

const decrement = () => {
  if (quantity.value > 1) quantity.value--;
};

const addToCart = (item) => {
  if (!selectedSize.value) {
    alert("Please select a size.");
    return;
  }

  const currentCart = JSON.parse(localStorage.getItem("cart")) || [];
  const existingItem = currentCart.find(
    (cartItem) => cartItem.id === item.id && cartItem.size === selectedSize.value
  );

  if (existingItem) {
    existingItem.quantity += quantity.value;
  } else {
    currentCart.push({
      ...item,
      size: selectedSize.value,
      quantity: quantity.value,
    });
  }

  localStorage.setItem("cart", JSON.stringify(currentCart));
  window.dispatchEvent(new Event("cartUpdated"));

  alert(`${item.name} in size ${selectedSize.value.toUpperCase()} has been added to the cart!`);

  selectedSize.value = "";
  quantity.value = 1;
};
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-[#554149] tracking-[2rem] font-['Plus_Jakarta_Sans']">
        {{ merchandise[0].name.toUpperCase() }}
      </h1>
    </div>

    <!-- Product Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        <!-- Product Image -->
        <div class="bg-white rounded-2xl shadow-lg p-8 transition-transform hover:scale-[1.02]">
          <img 
            src="../assets/images/merchant2.png" 
            :alt="merchandise[0].name"
            class="w-full h-auto object-cover rounded-xl"
          />
        </div>

        <!-- Product Details -->
        <div class="space-y-8">
          <!-- Brand and Name -->
          <div>
            <p class="text-gray-500 text-lg mb-2">Malam Citra Bayu</p>
            <h2 class="text-3xl font-bold text-[#554149] mb-4">
              {{ merchandise[0].name }}
            </h2>
            <p class="text-2xl font-semibold text-[#927e5b]">
              RM {{ merchandise[0].price }}
            </p>
          </div>

          <!-- Size Selection -->
          <div class="space-y-4">
            <label class="block text-lg font-medium text-[#554149]">
              Shirt Size:
            </label>
            <div class="flex gap-4">
              <div v-for="size in ['S', 'M', 'L', 'XL']" :key="size" class="relative">
                <input
                  type="radio"
                  :id="'size' + size"
                  name="size"
                  :value="size.toLowerCase()"
                  v-model="selectedSize"
                  class="sr-only peer"
                />
                <label
                  :for="'size' + size"
                  class="flex items-center justify-center w-14 h-14 rounded-full border-2 border-[#dcc39c] 
                         cursor-pointer transition-all duration-200 
                         peer-checked:bg-gradient-to-r peer-checked:from-[#a48e69] peer-checked:to-[#dcc39c] 
                         peer-checked:text-white hover:shadow-md"
                >
                  {{ size }}
                </label>
              </div>
            </div>
          </div>

          <!-- Quantity Selection -->
          <div class="space-y-4">
            <label class="block text-lg font-medium text-[#554149]">
              Quantity:
            </label>
            <div class="flex items-center gap-4">
              <button 
                @click="decrement"
                class="w-12 h-12 rounded-lg bg-[#dcc39c] text-[#554149] text-2xl font-bold
                       hover:bg-[#e0b987] transition-colors duration-200 flex items-center justify-center"
              >
                -
              </button>
              <input 
                type="text" 
                v-model="quantity" 
                readonly
                class="w-16 h-12 text-center border border-gray-300 rounded-lg text-lg"
              />
              <button 
                @click="increment"
                class="w-12 h-12 rounded-lg bg-[#dcc39c] text-[#554149] text-2xl font-bold
                       hover:bg-[#e0b987] transition-colors duration-200 flex items-center justify-center"
              >
                +
              </button>
            </div>
          </div>

          <!-- Product Description -->
          <div class="space-y-4">
            <h3 class="text-lg font-medium text-[#554149]">Description:</h3>
            <p class="text-gray-600 leading-relaxed">
              Experience comfort and style with our White Cotton Tee. Made from premium cotton material, 
              this t-shirt features a classic design with the MCB logo. Perfect for any casual occasion 
              while showing your support for Malam Citra Bayu.
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
              <li>100% Premium Cotton</li>
              <li>Comfortable fit</li>
              <li>Machine washable</li>
              <li>MCB logo print</li>
            </ul>
          </div>

          <!-- Add to Cart Button -->
          <button 
            @click="addToCart(merchandise[0])"
            class="w-full py-4 px-8 bg-gradient-to-r from-[#a48e69] to-[#dcc39c] text-[#554149]
                   rounded-full text-lg font-bold hover:shadow-lg transform hover:-translate-y-1
                   transition-all duration-200 flex items-center justify-center gap-2"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Add to Cart
          </button>
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

