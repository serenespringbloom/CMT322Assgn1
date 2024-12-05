<script>
import { ref } from "vue";

export default {
  data() {
    return {
      // Merchandise data
      merchandise: [
        { id: 1, name: "Black Pink Cotton Tee", price: 38, image: "../assets/images/merchant1.png" }
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
      alert(`${item.name} in size ${this.selectedSize} has been added to the cart!`);
    }
  }
};
</script>

<template>
    <body>
        <div class="head-one">
        <h1>BLACK PINK COTTON TEE</h1>
        </div>

        <section>
            <div class="product-one">
                <div class="product-media"><img src="@/assets/images/merchant1.png" alt="" /></div>
                <div class="product-info">
                    <section>
                        <p>Malam Citra Bayu</p>
                        <div class="product-title">BLACK PINK COTTON TEE</div>
                        <div>RM 38</div>
                        <!-- Size Clothes -->
                        <fieldset class="product-size">
                         <div class="size-group">
                            <label>Shirt Size:</label>
                            <div class="size-button">
                                <input checked type="radio" id="type1" name="type" value="small" v-model="selectedSize" />
                                <label data-icon="S" for="type1"><p>S</p></label>
                            </div>
                            
                            <div class="size-button">
                                <input disabled type="radio" id="type2" value="medium">
                                <label data-icon="M" for="type2"><p>M</p></label>
                            </div>
                            
                            <div class="size-button">
                                <input type="radio" id="type3" name="type" value="large" v-model="selectedSize" />
                                <label data-icon="L" for="type3">
                                <p>L</p>
                                </label>
                            </div>
                            
                            <div class="size-button">
                                <input type="radio" id="type4" name="type" value="extra-large" v-model="selectedSize" />
                                <label data-icon="XL" for="type4"><p>XL</p></label>
                            </div>
                            </div> 
                        </fieldset>

                        <!-- Quantity -->
                        <div class="quantity">
                            <label>Quantity</label>
                            <div class="counter">
                                <button class="btn minus" type="button" v-on:click="decrement">
                                -</button>
                            <input type="text" v-model="quantity" id="qtyInput" readonly />  
                            <button class="btn minus" type="button" v-on:click="increment">
                                +</button>
                            </div>
                        </div>

                         <!-- Add to Cart Button -->
                        <div>
                        <button @click="addToCart(merchandise[0])" :disabled="!selectedSize">Add to Cart</button>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </body>
</template>

<style>
.head-one{
    top: 20;
    min-height: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;
    letter-spacing: .2rem;
    font-family: 'Plus Jakarta Sans', serif;
    font-size: 1.0rem;
    padding-top: 10px;
    margin-bottom: 30px;
}

.product-one{
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom: 2rem;
    padding: 0;
}

.product-media{
    display: block;
    position: relative;
    background: transparent;
    box-sizing: inherit;
    border-style: double;

}
.product-info{
    display: block;
    padding: 0 0 0 4rem;
    max-width: 35%;
}

.product-title{
    font-size: 1.5rem;
    letter-spacing: 0.06rem
}

Style the container for the size selection
.product-size {
  margin-top: 20px;
}

/* Style the size group (container for buttons) */
.size-group {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  margin-bottom: 15px;
}

/* Style individual size button (each button with a label) */
.size-button {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 10px 20px;
  border: 2px solid #de1919;
  border-radius: 25px;
  cursor: pointer;
  transition: all 0.3s ease;
  background-color: #DCC39C;
  text-align: center;
  position: relative;
}

/* Hide the radio input */
.size-button input[type="radio"] {
    display: none;
}

/* Style the label for each button */
.size-button label {
  font-size: 14px;
  color: #333;
  font-weight: bold;
  text-transform: uppercase;
  margin: 0;
  display: inline-block;
  text-align: center;
}

/* Hover effect for size button */
.size-button:hover {
  background-color: #f0f0f0;
  border-color: #b5b5b5;
}

/* Active state effect for size button */
.size-button:active {
  background-color: #ddd;
}

/* Style the selected size button (when the radio input is checked) */
.size-button input[type="radio"]:checked + label {
    color: #333;
    border: 2px solid #DCC39C;
    background: #e0e0e0;
}

/* Disable the button for unavailable sizes (e.g. Medium is disabled) */
.size-button input[type="radio"]:disabled + label {
  background-color: #e0e0e0;
  color: #b5b5b5;
  cursor: not-allowed; /* Prevent click */
}

/* Optional: style the label text to be uppercase and bold */
.size-button label p {
  margin: 0;
}

/* Responsive design: stack size buttons vertically on small screens */
@media (max-width: 600px) {
  .size-group {
    flex-direction: column; /* Stack the size buttons vertically */
    align-items: center; /* Center the buttons */
  }

  .size-button {
    width: 100%; /* Make each button take the full width */
    margin-bottom: 10px; /* Add space between stacked buttons */
  }
}

/* quantity styling */
.counter{
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

.btn{
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

#qtyInput{
    width: 100%;
    border: none;
    font-size: 1rem;
    font-family:'Courier New', Courier, monospace;
    text-align: center;
    color: #000;
    background: 0 0;
    font-weight: 700;
}

#qtyInput:focus-visible{
    outline: none;
}

.button-cart{
  background-color: #DCC39C;
  padding: 16px 30px;
  margin-top: 20px;
  border: none;
  color: black;
}
</style>
