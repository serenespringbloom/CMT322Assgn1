<script setup>
import { RouterLink, RouterView } from 'vue-router';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const username = ref('');
const password = ref('');
const error = ref('');
const router = useRouter();

const handleLogin = () => {   
  const adminUsername = 'admin';
  const adminPassword = 'password123';

  if (username.value === adminUsername && password.value === adminPassword) {
    sessionStorage.setItem('isAuthenticated', 'true');
    router.push('/dashboard');
  } else {
    error.value = 'Invalid username or password';
  }
};

const returnHome = () => {
  router.push('/'); // Redirect to home page
};
</script>

<template>
  <div class="login-container">
    <h1>Admin Login</h1>
    <form @submit.prevent="handleLogin">
      <label for="username">Username:</label>
      <input id="username" v-model="username" type="text" />

      <label for="password">Password:</label>
      <input id="password" v-model="password" type="password" />

      <button type="submit">Login</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>
    <button @click="returnHome">Return To Home</button>
  </div>
</template>

<style scoped>
.login-container {
  max-width: 400px;
  margin: auto;
  padding: 1rem;
  text-align: center;
}

.error {
  color: red;
}
</style>
