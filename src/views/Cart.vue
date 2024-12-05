<script setup>
import { ref, computed, onMounted } from "vue";

const cart = ref([]); // Cart items
const email = ref(""); // User email
const phone = ref(""); // User phone number
const isEmailValid = ref(false);
const isPhoneValid = ref(false);

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
const removeFromCart = (itemId, size) => {
  cart.value = cart.value.filter(
    (item) => !(item.id === itemId && item.size === size)
  );
  saveCartToLocalStorage();
};

// Decrease item quantity
const decreaseQuantity = (itemId, size) => {
  const item = cart.value.find((item) => item.id === itemId && item.size === size);

  if (item) {
    if (item.quantity > 1) {
      item.quantity -= 1;
    } else {
      const confirmRemove = confirm("Remove this item from the cart?");
      if (confirmRemove) removeFromCart(itemId, size);
    }

    saveCartToLocalStorage();
  }
};

// Increase item quantity
const increaseQuantity = (itemId, size) => {
  const item = cart.value.find((item) => item.id === itemId && item.size === size);
  if (item) {
    item.quantity += 1;
    saveCartToLocalStorage();
  }
};

// Group cart items by vendor (dummy example for grouping, can be customized)
const groupCartByVendor = () => {
  const grouped = {};
  cart.value.forEach((item) => {
    const vendor = item.vendor || "Default Vendor"; // Use a vendor field or fallback to default
    if (!grouped[vendor]) grouped[vendor] = [];
    grouped[vendor].push(item);
  });
  return grouped;
};

// Calculate total price
const totalPrice = computed(() =>
  cart.value.reduce((total, item) => total + item.price * item.quantity, 0)
);

// Validate email
const validateEmail = (email) => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  isEmailValid.value = emailRegex.test(email);
};

// Validate Malaysian phone number
const validatePhone = (phone) => {
  const phoneRegex = /^(?:\+601|601|01)\d{7,9}$/;
  isPhoneValid.value = phoneRegex.test(phone);
};

// Simulate checkout process
const checkout = () => {
  if (!cart.value.length) {
    alert("Your cart is empty!");
    return;
  }

  if (!isEmailValid.value || !isPhoneValid.value) {
    alert("Please enter valid email and phone number.");
    return;
  }

  alert("Checkout successful! Thank you for your purchase.");
  cart.value = [];
  saveCartToLocalStorage();
};
</script>

<template>
  <div class="cart-page">
    <h1>YOUR CART</h1>
    <div class="cart-container">
      <!-- Cart Items Section -->
      <div class="cart-items">
        <div v-if="cart.length">
          <div class="cart-group" v-for="(items, vendor) in groupCartByVendor()" :key="vendor">
            <h2>{{ vendor }}</h2>
            <div class="cart-item" v-for="item in items" :key="item.id + item.size">
              <img :src="item.image" :alt="item.name" class="cart-item-image" />
              <div class="cart-item-details">
                <h2>{{ item.name }}</h2>
                <p>Size: {{ item.size.toUpperCase() }}</p>
                <p>Price: RM {{ item.price }}</p>
                <p>Total: RM {{ item.price * item.quantity }}</p>
                <div class="quantity-controls">
                  <button @click="decreaseQuantity(item.id, item.size)" class="quantity-btn">-</button>
                  <span>{{ item.quantity }}</span>
                  <button @click="increaseQuantity(item.id, item.size)" class="quantity-btn">+</button>
                </div>
              </div>
              <button @click="removeFromCart(item.id, item.size)" class="remove-button">Remove</button>
            </div>
          </div>
        </div>
        <div v-else class="empty-cart">
          <p>Your cart is empty.</p>
        </div>
      </div>

      <!-- Order Summary Section -->
      <div class="cart-summary">
        <h2>Order Summary</h2>
        <p>Total: RM {{ totalPrice }}</p>
        <div class="checkout-inputs">
          <input
          type="email"
          placeholder="Enter your email"
          v-model="email"
          @input="validateEmail(email)"
          :class="{ invalid: !isEmailValid && email }"
          />
          <span v-if="email && !isEmailValid" class="error-message">
            Please enter a valid email address.
          </span>

          <input
            type="text"
            placeholder="Enter your phone number"
            v-model="phone"
            @input="validatePhone(phone)"
            :class="{ invalid: !isPhoneValid && phone }"
          />
          <span v-if="phone && !isPhoneValid" class="error-message">
            Please enter a valid Malaysian phone number (e.g., +60123456789).
          </span>
        </div>
        <button
          @click="checkout"
          class="checkout-button"
          :disabled="!isEmailValid || !isPhoneValid"
        >
          Proceed to Checkout
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.cart-page {
  font-family: Arial, sans-serif;
  padding: 20px;
  background: #ffe4e9;
  padding-bottom: 10%;
  margin: 3%;
}

h1 {
  top: 20;
  min-height: 10vh;
  display: flex;
  justify-content: center;
  align-items: center;
  letter-spacing: .9rem;
  font-family: 'Plus Jakarta Sans', serif;
  font-size: 2.0rem;
  margin-bottom: 30px;
}

.cart-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  padding-top: 2%;
  width: 80%;
  margin-left: 9%;
}

.cart-items {
  flex: 2;
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.cart-group {
  margin-bottom: 20px;
}

.cart-group h2 {
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.cart-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #ccc;
  padding: 10px 0;
}

.cart-item-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 5px;
}

.cart-item-details {
  flex: 1;
  margin-left: 20px;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 10px;
}

.quantity-btn {
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 5px 10px;
  cursor: pointer;
}

.quantity-btn:hover {
  background-color: #0056b3;
}

.remove-button {
  background-color: #ff4d4f;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 8px 16px;
  cursor: pointer;
}

.remove-button:hover {
  background-color: #d43f3f;
}

.cart-summary {
  flex: 1;
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.cart-summary h2 {
  font-size: 1.5rem;
  margin-bottom: 10px;
}

.cart-summary p {
  font-size: 1.2rem;
  margin: 10px 0;
}

.checkout-inputs {
  margin: 20px 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.checkout-inputs input {
  padding: 10px;
  border-radius: 5px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

.checkout-inputs input.invalid {
  border-color: red;
}

.checkout-button {
  background-color: #28a745;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  cursor: pointer;
}

.checkout-button:disabled {
  background-color: #a5d6a7;
  cursor: not-allowed;
}

.checkout-button:hover:enabled {
  background-color: #218838;
}

.empty-cart {
  text-align: center;
  margin-top: 30px;
  color: #555;
  font-size: 1.2rem;
}
</style>
