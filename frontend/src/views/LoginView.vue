<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import apiClient from '../api.js'; // Adjust the import path as necessary

const username = ref('');
const password = ref('');
const error = ref('');
const router = useRouter();

const loading = ref(false); // Track loading state

const handleLogin = async () => {
  loading.value = true; // Show "Signing In..."
  error.value = ''; // Clear previous errors

  if (!username.value || !password.value) {
    error.value = 'Username and password are required.';
    loading.value = false; // Stop "Signing In..."
    return;
  }

  try {
    const response = await apiClient.post('/admin/login', {
      username: username.value,
      password: password.value,
    });

    if (response.data.success) {
      // Successful login
      sessionStorage.setItem('token', response.data.token); // Save the token
      router.push('/dashboard'); // Redirect to the admin dashboard
    } else {
      error.value = response.data.error; // Show error message
    }
  } catch (err) {
    error.value = 'An unexpected error occurred. Please try again.';
    console.error(err); // Debugging
  } finally {
    loading.value = false; // Stop "Signing In..."
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
        <img src="../assets/MCBLogo.png" alt="Logo" class="logo" />
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
  flex-direction: row; /* Default row layout for larger screens */
}

/* Sidebar */
.sidebar {
  width: 40%; /* Emphasized sidebar for larger screens */
  background: #fff; /* White Sidebar */
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  position: relative;
  padding: 3rem; /* Increased padding for better spacing */
  box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.home-link {
  position: absolute;
  top: 20px;
  left: 20px;
  font-size: 1rem;
  color: #ff69b4; /* Hot Pink */
  cursor: pointer;
  text-decoration: underline;
}

.home-link:hover {
  text-decoration: none;
}

.login-content {
  text-align: center;
}

.logo {
  width: 200px; /* Adjusted logo size for better responsiveness */
  height: auto;
  margin-bottom: 1rem;
}

h2 {
  margin-bottom: 2rem;
  font-size: 1.75rem;
  color: #ff69b4; /* Hot Pink */
}

/* Form Styling */
.login-form {
  width: 100%;
}

.form-group {
  margin-bottom: 1.5rem; /* Adjust spacing for smaller screens */
}

input {
  width: 100%;
  padding: 0.8rem; /* Reduced padding for smaller screens */
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 1rem;
}

input:focus {
  outline: none;
  border-color: #ff69b4; /* Hot Pink */
  box-shadow: 0 0 5px rgba(255, 105, 180, 0.3);
}

.btn-login {
  background-color: #ff69b4; /* Hot Pink */
  color: #fff;
  border: none;
  padding: 1rem 2rem;
  font-size: 1.1rem; /* Adjusted font size */
  font-weight: bold;
  border-radius: 6px;
  cursor: pointer;
  width: 100%;
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
  background: linear-gradient(to right, #ffb6c1, #ff69b4);
  color: white;
  text-align: center;
}

.background h1 {
  font-size: 2rem;
  font-weight: bold;
  opacity: 0.8;
}

/* Responsive Design */
@media (max-width: 768px) {
  .login-page {
    flex-direction: column; /* Stack the layout vertically */
  }

  .sidebar {
    width: 100%; /* Full width for smaller screens */
    padding: 2rem;
    text-align: center;
  }

  .background {
    display: none; /* Hide the background on small screens */
  }

  .btn-login {
    width: 100%; /* Full-width button for smaller screens */
  }
}

@media (max-width: 480px) {
  .logo {
    width: 150px; /* Smaller logo for very small screens */
  }

  h2 {
    font-size: 1.5rem; /* Adjust header size */
  }

  input {
    font-size: 0.9rem; /* Smaller input font */
    padding: 0.6rem; /* Reduced padding */
  }

  .btn-login {
    font-size: 1rem; /* Smaller button font */
    padding: 0.8rem 1.5rem;
  }
}
</style>
