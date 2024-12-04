<script setup>
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
    <div class="login-card">
      <h1>Admin Login</h1>
      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="username">Username:</label>
          <input id="username" v-model="username" type="text" placeholder="Enter your username" />
        </div>

        <div class="form-group">
          <label for="password">Password:</label>
          <input id="password" v-model="password" type="password" placeholder="Enter your password" />
        </div>

        <button type="submit" class="btn-login">Login</button>
      </form>

      <p v-if="error" class="error">{{ error }}</p>
      <button @click="returnHome" class="btn-home">Return To Home</button>
    </div>
  </div>
</template>

<style scoped>
/* General Layout */
.login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #ffe4e6; /* Light Pink Background */
  font-family: Arial, sans-serif;
}

/* Login Card */
.login-card {
  background: #fff;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  text-align: center;
  width: 100%;
  max-width: 400px;
}

/* Form Styling */
.login-form {
  margin-top: 1rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.5rem;
  color: #333;
}

input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 1rem;
  box-sizing: border-box;
}

input:focus {
  outline: none;
  border-color: #ff69b4; /* Hot Pink */
  box-shadow: 0 0 5px rgba(255, 105, 180, 0.3); /* Pink Focus Glow */
}

/* Buttons */
.btn-login {
  background-color: #ff69b4; /* Hot Pink */
  color: #fff;
  border: none;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  font-weight: bold;
  border-radius: 4px;
  cursor: pointer;
  width: 100%;
  margin-top: 1rem;
  transition: background-color 0.3s ease;
}

.btn-login:hover {
  background-color: #ff1493; /* Deep Pink */
}

.btn-home {
  background: none;
  border: none;
  color: #ff69b4; /* Hot Pink */
  font-size: 1rem;
  margin-top: 1rem;
  cursor: pointer;
}

.btn-home:hover {
  text-decoration: underline;
}

/* Error Message */
.error {
  color: #ff4d4f;
  margin-top: 1rem;
  font-weight: bold;
  font-size: 0.9rem;
}
</style>
