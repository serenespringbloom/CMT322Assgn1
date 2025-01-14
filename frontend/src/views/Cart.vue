<script setup>
import { ref, computed, onMounted } from "vue";

const cart = ref([]); // Cart items
const email = ref(""); // User email
const phone = ref(""); // User phone number
const isEmailValid = ref(false);
const isPhoneValid = ref(false);
const selectedBank = ref("");
const fpxIcon = "/src/assets/images/fpx-icon.png";

// Load cart from localStorage on page load
onMounted(() => {
  const savedCart = JSON.parse(localStorage.getItem("cart")) || [];
  cart.value = savedCart;
});

// Save cart to localStorage
const saveCartToLocalStorage = () => {
  localStorage.setItem("cart", JSON.stringify(cart.value));
  dispatchCartUpdate(); // Notify other components about cart updates
};

// Emit an event to notify other components
const dispatchCartUpdate = () => {
  window.dispatchEvent(new Event("cartUpdated"));
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
    const vendor = item.vendor || "Malam Citra Bayu Tees"; // Use a vendor field or fallback to default
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

const banks = ref([
  { name: "Maybank", icon: "/src/assets/images/maybank-icon.png" },
  { name: "CIMB Bank", icon: "/src/assets/images/cimb-icon.png" },
  { name: "RHB Bank", icon: "/src/assets/images/rhb-icon.png" },
  { name: "Public Bank", icon: "/src/assets/images/publicbank-icon.png" },
  { name: "Bank Rakyat", icon: "/src/assets/images/bankrakyat-icon.png" }
]);

// Computed for Selected Bank Icon
const selectedBankIcon = computed(() => {
  if (!selectedBank.value) {
    // Show the FPX icon when no bank is selected
    return fpxIcon;
  }
  const bank = banks.value.find((b) => b.name === selectedBank.value);
  return bank ? bank.icon : fpxIcon; // Fallback to FPX icon
});


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
  selectedBank.value = ''; // Reset selected bank
  saveCartToLocalStorage();
};
</script>

<template>
  <div class="cart-page">
    <div id="cart-title">
      <h1>YOUR CART</h1>
    </div>
    <div class="cart-container">
      <!-- Cart Items Section -->
      <div class="cart-items">
        <div v-if="cart.length">
          <div class="cart-group" v-for="(items, vendor) in groupCartByVendor()" :key="vendor">
            <h2>{{ vendor }}</h2>
            <div class="cart-item" v-for="item in items" :key="item.id + item.size">
              <img v-if="(item.id === 1)" src="../assets/images/merchant1.png" :alt="item.name" class="cart-item-image" />
              <img v-if="(item.id === 2)" src="../assets/images/merchant2.png" :alt="item.name" class="cart-item-image" />
              <img v-if="(item.id === 3)" src="../assets/images/merchant3.png" :alt="item.name" class="cart-item-image" />
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
        <p class="total-price">Total Price: RM {{ totalPrice }}</p>
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

          <label for="bank-select">Choose Your Bank (FPX):</label>
          <div class="bank-dropdown">
            <select id="bank-select" v-model="selectedBank">
              <option disabled value="">Select a Bank</option>
              <option
                v-for="bank in banks"
                :key="bank.name"
                :value="bank.name"
              >
                {{ bank.name }}
              </option>
            </select>
            <div class="bank-icon">
              <img
                v-if="selectedBankIcon"
                :src="selectedBankIcon"
                alt="Bank Icon"
              />
            </div>
          </div>
        </div>
        
        <button
          @click="checkout"
          class="checkout-button"
          :disabled="!isEmailValid || !isPhoneValid || !selectedBank" 
        >
          Proceed to Checkout
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>

.cart-page {
  font-family: "Plus Jakarta Sans", serif;
  padding: 0 20px 20px 20px;
  padding-bottom: 5%;
  margin: 0 3% 3% 3%;
  border-radius: 10px;
}

#cart-title{
  text-align: center;
  letter-spacing: .9rem;
  font-family: "Plus Jakarta Sans", serif;
  margin-top: 2rem;
}

#cart-title h1 {
  font-size: 2.3rem;
  letter-spacing: 2rem;
  margin-bottom: 1.5rem;
  color: #554149;
  text-transform: uppercase;
}

.cart-container {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  flex-wrap: wrap;
  padding-top: 2%;
  width: 90%;
  margin: 0 auto;
}

.cart-items {
  flex: 2;
  background: linear-gradient(135deg, rgba(255, 240, 245, 0.8), rgba(255, 200, 221, 0.8));
  border-radius: 15px;
  padding: 20px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  animation: fadeIn 0.3s ease-in-out;
}

.cart-group {
  margin-bottom: 20px;
}

.cart-group h2 {
  font-size: 1.4rem;
  margin-bottom: 10px;
  color: #554149;
}

.cart-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #eee;
  padding: 12px 0;
}

.cart-item-image {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}


.cart-item-details {
  flex: 1;
  margin-left: 20px;
  margin-bottom: 5px;
}

.cart-item-details h4 {
  font-size: 1.2rem;
  color: #333;
  margin-bottom: 5px;
}

.cart-item-details p {
  font-size: 1rem;
  color: #656565;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-top:9px;
}

.quantity-btn {
  background: linear-gradient(135deg, #a48e69, #dcc39c);
  color: white;
  border: none;
  border-radius: 50%;
  padding: 8px 12px;
  font-size: 1rem;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
}

.quantity-btn:hover {
  background: linear-gradient(135deg, #dcc39c, #a48e69);
  transform: scale(1.1);
}

.remove-button {
  background-color: #ff4d4f;
  color: white;
  border: none;
  border-radius: 5px;
  padding: 8px 16px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.remove-button:hover {
  background-color: #d43f3f;
  transform: translateY(-3px);
}

.cart-summary {
  flex: 1;
  background: linear-gradient(135deg, #fff1f5, #ffe5ed);
  padding: 25px;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
  text-align: center;
  max-height: 450px;
  animation: fadeIn 0.3s ease-in-out;
  font-family: "Plus Jakarta Sans", sans-serif;
}

.cart-summary h2 {
  font-size: 1.8rem;
  font-weight: bold;
  color: #554149;
  margin-bottom: 1rem;
  text-transform: uppercase;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.cart-summary p {
  font-size: 1.2rem;
  margin: 10px 0;
  color: #6b4f57;
  font-weight: 500;
}

.cart-summary p span {
  font-weight: bold;
  color: #a48e69;
}

.cart-summary .total-price {
  font-size: 1.6rem;
  font-weight: bold;
  color: #554149;
  margin: 1rem 0;
  padding: 0.5rem 0;
  background: linear-gradient(135deg, #ffe8e8, #ffc8dd);
  border-radius: 10px;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
}

.checkout-inputs {
  font-family: "Plus Jakarta Sans", sans-serif;
  margin: 20px 0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.checkout-inputs input {
  padding: 12px;
  border-radius: 10px;
  border: 2px solid #ccc;
  font-size: 1rem;
  transition: border 0.3s ease, box-shadow 0.3s ease;
}

.checkout-inputs input:focus {
  border-color: #ffc8dd;
  box-shadow: 0 0 8px rgba(255, 200, 221, 0.5);
}

.checkout-inputs input.invalid {
  border-color: red;
}

.checkout-button {
  background: linear-gradient(135deg, #6bcf6b, #4caf50);
  color: white;
  border: none;
  border-radius: 25px;
  padding: 12px 20px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
  transition: all 0.3s ease-in-out, transform 0.3s ease-in-out;
}

.checkout-button:disabled {
  background-color: #a5d6a7;
  cursor: not-allowed;
  box-shadow: none;
}

.checkout-button:hover:enabled {
  background: linear-gradient(135deg, #4caf50, #6bcf6b);
  transform: translateY(-3px);
}

.remove-button {
  background: linear-gradient(135deg, #ff5f57, #d9534f);
  color: white;
  border: none;
  border-radius: 25px;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 1rem;
  font-weight: bold;
  box-shadow: 0 5px 15px rgba(217, 83, 79, 0.3);
  transition: background 0.3s ease, transform 0.3s ease;
}

.remove-button:hover {
  background: linear-gradient(135deg, #d9534f, #ff5f57);
  transform: translateY(-3px);
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 10px;
}

.quantity-btn {
  background: linear-gradient(135deg, #a48e69, #dcc39c);
  color: white;
  border: none;
  border-radius: 50%;
  padding: 8px 12px;
  font-size: 1rem;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
}

.quantity-btn:hover {
  background: linear-gradient(135deg, #dcc39c, #a48e69);
  transform: scale(1.1);
}

.empty-cart {
  text-align: center;
  margin-top: 30px;
  color: #555;
  font-size: 1.2rem;
  font-weight: bold;
}

/* FPX Bank Selection Styling */
.bank-dropdown {
  display: flex;
  align-items: center;
  gap: 1rem;
  justify-content: center;
  margin-top: -0.3rem;
}

.bank-dropdown select {
  padding: 0.75rem;
  font-size: 1rem;
  font-weight: bold;
  border: 2px solid #ccc;
  border-radius: 10px;
  background-color: #fff;
  cursor: pointer;
  transition: all 0.3s ease;
}

.bank-dropdown select:hover {
  border-color: #ffc8dd;
  background-color: #fff6f8;
}

.bank-dropdown .bank-icon img {
  width: 40px;
  height: 40px;
  object-fit: contain;
  display: inline-block;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
