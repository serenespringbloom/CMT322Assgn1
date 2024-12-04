<script setup>
import { ref, computed, onMounted } from "vue";

const cart = ref([]); // Cart items
const isCheckoutFailed = ref(false); // Track checkout status

// Load cart from localStorage on page load
onMounted(() => {
  const savedCart = JSON.parse(localStorage.getItem("cart")) || [];
  cart.value = savedCart;
});

// Save cart to localStorage
const saveCartToLocalStorage = () => {
  localStorage.setItem("cart", JSON.stringify(cart.value));
};

// Remove item from cart
const removeFromCart = (itemId) => {
  cart.value = cart.value.filter((item) => item.id !== itemId);
  saveCartToLocalStorage();
};

// Calculate total price
const totalPrice = computed(() =>
  cart.value.reduce((total, item) => total + item.price * item.quantity, 0)
);

// Simulate checkout process
const checkout = () => {
  if (cart.value.length === 0) {
    alert("Your cart is empty!");
    return;
  }

  // Simulated payment gateway response
  const paymentSuccess = Math.random() > 0.5; // 50% chance of success for demo

  if (paymentSuccess) {
    alert("Payment successful! Thank you for your purchase.");
    cart.value = []; // Clear the cart
    saveCartToLocalStorage();
  } else {
    alert("Payment failed. Please try again.");
    isCheckoutFailed.value = true; // Retain cart data
  }
};
</script>

<template>
  <div class="cart-page">
    <h1>Your Cart</h1>
    <!-- Show cart items -->
    <div v-if="cart.length">
      <div v-for="item in cart" :key="item.id" class="cart-item">
        <img :src="item.image" :alt="item.name" />
        <h2>{{ item.name }}</h2>
        <p>Price: RM {{ item.price }}</p>
        <p>Quantity: {{ item.quantity }}</p>
        <p>Total: RM {{ item.price * item.quantity }}</p>
        <button @click="removeFromCart(item.id)">Remove</button>
      </div>
      <div class="cart-summary">
        <h2>Cart Summary</h2>
        <p>Total Price: RM {{ totalPrice }}</p>
        <button class="checkout-button" @click="checkout">Checkout</button>
      </div>
      <p v-if="isCheckoutFailed" class="checkout-error">
        Payment failed. Your cart data has been retained.
      </p>
    </div>
    <!-- Show empty cart message -->
    <div v-else>
      <p>Your cart is empty.</p>
    </div>
  </div>
</template>

<style scoped>
.cart-page {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.cart-item {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #ccc;
  padding: 10px 0;
}

.cart-item img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  margin-right: 20px;
}

.cart-item h2 {
  flex: 1;
  font-size: 1.2rem;
  margin: 0;
}

.cart-summary {
  margin-top: 20px;
  font-size: 1.2rem;
  font-weight: bold;
}

.checkout-button {
  background-color: #28a745;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.checkout-button:hover {
  background-color: #218838;
}

.checkout-error {
  color: red;
  margin-top: 10px;
}
</style>
