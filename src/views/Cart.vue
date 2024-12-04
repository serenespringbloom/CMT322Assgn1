<script setup>
    import { ref, onMounted } from "vue";

    const cart = ref([]);

    const removeFromCart = (itemId) => {
        cart.value = cart.value.filter((item) => item.id != itemId);

        localStorage.setItem("cart", JSON.stringify(cart.value));
    };

    onMounted(() => {
        const savedCart = JSON.parse(localStorage.getItem("cart")) || [];
        cart.value = savedCart;
    });
</script>

<template>
  <div class="cart-page">
    <h1>Your Cart</h1>
    <div v-if="cart.length">
      <div v-for="item in cart" :key="item.id" class="cart-item">
        <img :src="item.image" :alt="item.name" />
        <h2>{{ item.name }}</h2>
        <p>Price: RM {{ item.price }}</p>
        <p>Quantity: {{ item.quantity }}</p>
        <button @click="removeFromCart(item.id)">Remove</button>
      </div>
    </div>
    <div v-else>
      <p>Your cart is empty.</p>
    </div>
  </div>
</template>