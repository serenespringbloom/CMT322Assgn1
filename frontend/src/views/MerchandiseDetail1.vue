<script setup>
import { ref } from 'vue';

const merchandise = ref([
  { id: 1, name: "Black Pink Cotton Tee", price: 38, image: "../assets/images/merchant1.png" }
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

  // Get the current cart from localStorage or initialize it
  const currentCart = JSON.parse(localStorage.getItem("cart")) || [];

  // Check if item exists in cart
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

  // Save updated cart
  localStorage.setItem("cart", JSON.stringify(currentCart));
  window.dispatchEvent(new Event("cartUpdated"));

  // Show feedback
  alert(`${item.name} in size ${selectedSize.value.toUpperCase()} has been added to the cart!`);

  // Reset form
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
            src="../assets/images/merchant1.png" 
            alt="Black Pink Cotton Tee"
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
              Introducing our White Blue Cotton Tee, a perfect blend of style and comfort. 
              This unique design features a striking blue accent on premium white cotton, 
              making it a standout piece in your MCB collection.
            </p>
            <ul class="list-disc list-inside text-gray-600 space-y-2">
              <li>Premium cotton blend</li>
              <li>Blue accent design</li>
              <li>Durable stitching</li>
              <li>Limited edition MCB print</li>
            </ul>
          </div>

          <!-- Add to Cart Button -->
          <button 
            @click="addToCart(merchandise[0])"
            class="w-full py-4 px-8 bg-gradient-to-r from-[#a48e69] to-[#dcc39c] text-[#554149]
                   rounded-full text-lg font-bold hover:shadow-lg transform hover:-translate-y-1
                   transition-all duration-200"
          >
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