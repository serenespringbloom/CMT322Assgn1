<script>
export default {
  data() {
    return {
      // Merchandise data
      merchandise: [
        { id: 2, name: "White Pink Cotton Tee", price: 38, image: "../assets/images/merchant2.png" }
      ],
      // Selected size and quantity state
      selectedSize: "",
      quantity: 1,
    };
  },
  methods: {
    increment() {
      this.quantity++;
    },
    decrement() {
      if (this.quantity > 1) this.quantity--;
    },
    addToCart(item) {
      if (!this.selectedSize) {
        alert("Please select a size.");
        return;
      }

      // Get the current cart from localStorage or initialize it to an empty array
      const currentCart = JSON.parse(localStorage.getItem("cart")) || [];

      // Check if the item already exists in the cart
      const existingItem = currentCart.find((cartItem) => cartItem.id === item.id && cartItem.size === this.selectedSize);

      if (existingItem) {
        // If the item exists and the size matches, increase the quantity
        existingItem.quantity += this.quantity;
      } else {
        // If the item doesn't exist, add it with the selected size and quantity
        currentCart.push({
          ...item,
          size: this.selectedSize,
          quantity: this.quantity,
        });
      }

      // Save the updated cart to localStorage
      localStorage.setItem("cart", JSON.stringify(currentCart));

      // Dispatch an event to notify other components of the cart update
      window.dispatchEvent(new Event("cartUpdated"));

      // Show feedback message
      alert(`${item.name} in size ${this.selectedSize.toUpperCase()} has been added to the cart!`);

      this.selectedSize = ""; // Unselect size
      this.quantity = 1; // Reset quantity to default value
    }
  }
};
</script>

<template>
  <div>
    <!-- Header -->
    <div class="header">
      <h1>{{ merchandise[0].name.toUpperCase() }}</h1>
    </div>

    <!-- Product Section -->
    <section class="product-container">
      <!-- Product Image -->
      <div class="product-image">
        <img src="../assets/images/merchant2.png" alt="White Pink Cotton Tee" />
      </div>

      <!-- Product Info -->
      <div class="product-details">
        <p class="brand">Malam Citra Bayu</p>
        <h2 class="product-name">{{ merchandise[0].name }}</h2>
        <p class="price">RM {{ merchandise[0].price }}</p>

        <!-- Size Selector -->
        <div class="size-selector">
          <label class="label">Shirt Size:</label>
          <div class="size-options">
            <div
              v-for="(size, index) in ['S', 'M', 'L', 'XL']"
              :key="index"
              class="size-option"
            >
              <input
                type="radio"
                :id="'size' + index"
                name="size"
                :value="size.toLowerCase()"
                v-model="selectedSize"
              />
              <label :for="'size' + index" class="size-label">{{ size }}</label>
            </div>
          </div>
        </div>

        <!-- Quantity Selector -->
        <div class="quantity-selector">
          <label class="label">Quantity:</label>
          <div class="quantity-controls">
            <button class="btn" @click="decrement">-</button>
            <input type="text" v-model="quantity" readonly />
            <button class="btn" @click="increment">+</button>
          </div>
        </div>

        <!-- Add to Cart -->
        <button
          class="add-to-cart"
          @click="addToCart(merchandise[0])"
        >
          Add to Cart
        </button>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Header */
.header {
  text-align: center;
  margin: 2rem 0;
  font-family: "Plus Jakarta Sans", serif;
}

.header h1 {
  font-size: 2.5rem;
  letter-spacing: 2rem;
  color: #554149;
  text-transform: uppercase;
}



/* Product Container */
.product-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  gap: 3rem;
  padding: 2rem;
}

.product-image img {
  width: 100%;
  max-width: 400px;
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.product-details {
  max-width: 400px;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

/* Brand and Name */
.brand {
  font-size: 1rem;
  color: #888;
}

.product-name {
  font-size: 2rem;
  color: #554149;
  font-weight: bold;
}

.price {
  font-size: 1.5rem;
  color: #de1919;
  font-weight: bold;
}

/* Size Selector */
.size-selector {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.label {
  font-size: 1rem;
  color: #554149;
  font-weight: bold;
}

.size-options {
  display: flex;
  gap: 1rem;
}

.size-option {
  position: relative;
}

.size-option input {
  display: none;
}

.size-label {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 50px;
  height: 50px;
  border: 2px solid #dcc39c;
  border-radius: 50%;
  background-color: #fff;
  font-weight: bold;
  cursor: pointer;
  transition: all 0.3s ease;
}

.size-option input:checked + .size-label {
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: #fff;
}

/* Quantity Selector */
.quantity-selector {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.quantity-controls .btn {
  width: 40px;
  height: 40px;
  background-color: #dcc39c;
  border: none;
  border-radius: 5px;
  font-size: 1.5rem;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.quantity-controls .btn:hover {
  background-color: #e0b987;
}

.quantity-controls input {
  width: 50px;
  height: 40px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 5px;
}

/* Add to Cart Button */
.add-to-cart {
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: #554149;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  font-weight: bold;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.add-to-cart:hover {
  background-color: #e0b987;
  transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 768px) {
  .product-container {
    flex-direction: column;
    gap: 2rem;
  }

  .product-details {
    max-width: 90%;
  }
}
</style>
