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
    <div class="head-two">
      <h1>{{ merchandise[0].name.toUpperCase() }}</h1>
    </div>

    <!-- Product Section -->
    <section>
      <div class="product-two">
        <!-- Product Image -->
        <div class="product-media">
          <img src="../assets/images/merchant2.png" alt="Product Image" />
        </div>

        <!-- Product Info -->
        <div class="product-info">
          <section>
            <p>Malam Citra Bayu</p>
            <div class="product-title">{{ merchandise[0].name }}</div>
            <div>RM {{ merchandise[0].price }}</div>

            <!-- Size Selection -->
            <fieldset class="product-size">
              <div class="size-group">
                <label>Shirt Size:</label>
                <div
                  v-for="(size, index) in ['S', 'M', 'L', 'XL']"
                  :key="index"
                  class="size-button"
                  @click="selectedSize = size.toLowerCase()"
                >
                  <input
                    type="radio"
                    :id="'type' + index"
                    name="type"
                    :value="size.toLowerCase()"
                    v-model="selectedSize"
                  />
                  <label :for="'type' + index" class="size-label">
                    <p>{{ size }}</p>
                  </label>
                </div>
              </div>
            </fieldset>

            <!-- Quantity Selection -->
            <div class="quantity">
              <label>Quantity</label>
              <div class="counter">
                <button class="btn minus" type="button" @click="decrement">-</button>
                <input type="text" v-model="quantity" id="qtyInput" readonly />
                <button class="btn plus" type="button" @click="increment">+</button>
              </div>
            </div>

            <!-- Add to Cart -->
            <button
              class="button-cart"
              @click="addToCart(merchandise[0])"
            >
              Add to Cart
            </button>
          </section>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.head-two {
  top: 20;
  min-height: 10vh;
  display: flex;
  justify-content: center;
  align-items: center;
  letter-spacing: .9rem;
  font-family: 'Plus Jakarta Sans', serif;
  font-size: 1.0rem;
  margin-top: 4%;
  margin-bottom: 30px;
}

.product-two {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  margin-bottom: 2rem;
  padding-top: 1%;
}

.product-media {
  display: block;
  position: relative;
  background: transparent;
  box-sizing: inherit;
  border-style: double;
}

.product-info {
  display: block;
  padding: 0 0 0 4rem;
  max-width: 35%;
}

.product-title {
  font-size: 1.5rem;
  letter-spacing: 0.06rem;
}

.product-size {
  margin-top: 20px;
}

.size-group {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  margin-bottom: 15px;
}

.size-button {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 60px;
  cursor: pointer;
  text-align: center;
}

.size-button input[type="radio"] {
  display: none;
}

.size-label {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  border: 2px solid #de1919;
  border-radius: 30px;
  font-weight: bold;
  text-transform: uppercase;
  background-color: #dcc39c;
  color: #333;
  transition: all 0.3s ease;
}

.size-label:hover {
  background-color: #f0f0f0;
  border-color: #b5b5b5;
}

.size-button input[type="radio"]:checked + .size-label {
  background-color: #e0e0e0;
  border-color: #b5b5b5;
}

/* Quantity styling */
.counter {
  width: 200px;
  height: 70px;
  background-color: #edeefa;
  padding: 15px;
  margin-top: 20px;
  margin-left: 20px;
  display: inline-flex;
  overflow: hidden;
  align-items: center;
  border-radius: 20px;
}

.btn {
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  border: none;
  width: 50px;
  height: 50px;
  font-size: 1rem;
  cursor: pointer;
}

#qtyInput {
  width: 100%;
  border: none;
  font-size: 1rem;
  font-family: 'Courier New', Courier, monospace;
  text-align: center;
  color: #000;
  background: 0 0;
  font-weight: 700;
}

#qtyInput:focus-visible {
  outline: none;
}

.button-cart {
  background-color: #dcc39c;
  padding: 16px 30px;
  margin-top: 20px;
  border: none;
  color: black;
}

.button-cart:hover {
  background-color: #f0f0f0;
  border-color: #b5b5b5;
  cursor: pointer;
}
</style>
