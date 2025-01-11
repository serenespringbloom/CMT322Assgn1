<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const username = ref('');
const password = ref('');
const error = ref('');
const router = useRouter();

const loading = ref(false); // Track loading state

const handleLogin = async () => {
  loading.value = true;
  error.value = ''; // Clear previous errors
  if (!username.value || !password.value) {
    error.value = 'Username and password are required.';
    return;
  }
  
  try {
    const response = await fetch('http://127.0.0.1:8000/api/admin/login', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'include',
      body: JSON.stringify({ 
        username: username.value, 
        password: password.value 
      }),
    });

    if (!response.ok) {
      const errorData = await response.json();
      console.error('Login failed:', response.status, errorData);
      error.value = errorData.message || 'Login failed. Please try again.';
      return;
    }

    const data = await response.json();
    sessionStorage.setItem('token', data.token);
    router.push('/dashboard');
  } catch (err) {
    console.error('Login error:', err);
    error.value = 'Unable to connect to the server. Please check your connection.';
  } finally {
    loading.value = false;
  }
};

const returnHome = () => {
  router.push('/'); // Redirect to home page
};
</script>

<template>
  <div class="login-page">
   
    <!-- Sidebar -->
    <div class="sidebar">
      <a class="home-link" @click="returnHome">Return to Home</a>
      <div class="login-content">
        <img src="https://via.placeholder.com/128" alt="Logo" class="logo" />
        <h2>Admin Login</h2>
        <form @submit.prevent="handleLogin" class="login-form">
          <div class="form-group">
            <input id="username" v-model="username" type="text" placeholder="Enter your username" />
          </div>

          <div class="form-group">
            <input id="password" v-model="password" type="password" placeholder="Enter your password" />
          </div>

          <button type="submit" class="btn-login" :disabled="loading">
            <span v-if="loading">Signing In...</span>
            <span v-else>Sign In</span>
          </button>
        </form>

        <p v-if="error" class="error">{{ error }}</p>
      </div>
    </div>

    <!-- Background -->
    <div class="background">
      <h1>Welcome Back!</h1>
    </div>
  </div>
</template>

<style scoped>
/* General Layout */
.login-page {
  display: flex;
  height: 100vh;
  font-family: Arial, sans-serif;
  background: linear-gradient(to right, #ffc0cb, #ff69b4); /* Light Pink to Hot Pink */
  color: #333;
}

/* Sidebar */
.sidebar {
  width: 40%; /* Increased width to emphasize sidebar content */
  background: #fff; /* White Sidebar */
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  position: relative;
  padding: 3rem; /* Increased padding for better spacing */
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

/* Return Home Link */
.home-link {
  position: absolute;
  top: 20px; /* Adjusted for larger sidebar */
  left: 20px;
  font-size: 1rem; /* Slightly larger font */
  color: #ff69b4; /* Hot Pink */
  cursor: pointer;
  text-decoration: underline;
}

.home-link:hover {
  text-decoration: none;
}

/* Login Content */
.login-content {
  text-align: center;
}

.logo {
  width: 128px; /* Enlarged logo */
  height: 128px;
  margin-bottom: 2rem;
}

h2 {
  margin-bottom: 2rem;
  font-size: 1.75rem; /* Enlarged title */
  color: #ff69b4; /* Hot Pink */
}

/* Form Styling */
.login-form {
  width: 100%;
}

.form-group {
  margin-bottom: 2rem; /* Increased spacing between fields */
}

input {
  width: 100%;
  padding: 1rem; /* Enlarged input fields */
  border: 1px solid #ddd;
  border-radius: 6px; /* Slightly rounded corners */
  font-size: 1.2rem; /* Increased font size */
  box-sizing: border-box;
}

input:focus {
  outline: none;
  border-color: #ff69b4; /* Hot Pink */
  box-shadow: 0 0 5px rgba(255, 105, 180, 0.3); /* Pink Glow */
}

/* Sign-In Button */
.btn-login {
  background-color: #ff69b4; /* Hot Pink */
  color: #fff;
  border: none;
  padding: 1rem 2rem; /* Larger button size */
  font-size: 1.2rem; /* Increased font size */
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
  width: 600px;
  margin-top: 1.5rem;
  transition: background-color 0.3s ease;
}

.btn-login:hover {
  background-color: #ff1493; /* Deep Pink */
}

.btn-login:disabled {
  background-color: #ddd;
  cursor: not-allowed;
}

/* Error Message */
.error {
  color: #ff4d4f;
  margin-top: 1.5rem;
  font-weight: bold;
  font-size: 1rem;
}

/* Background */
.background {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(to right, #ffb6c1, #ff69b4); /* Gradient Pink Background */
  color: white;
  text-align: center;
}

.background h1 {
  font-size: 2.5rem; /* Enlarged text */
  font-weight: bold;
  opacity: 0.8;
}

@media (max-width: 768px) {
  .login-page {
    flex-direction: column;
  }

  .sidebar {
    width: 100%;
    padding: 2rem;
  }

  .btn-login {
    width: 100%; /* Full-width button for smaller screens */
  }

  .background {
    display: none; /* Hide the background on small screens */
  }
}

</style>
