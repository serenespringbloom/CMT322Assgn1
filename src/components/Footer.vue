<script>
    import { RouterLink, RouterView } from 'vue-router';
</script>

<template>
    <div class="footer-container">
      <!-- Left Logo -->
      <div class="footer-logo">
        <img src="" alt="PERSIS Logo" class="footer-logo-img" />
      </div>

      <!-- Center Navigation -->
      <div class="footer-nav">
        <h2>MCB</h2>
        <nav>
          <ul class="footer-links">
            <li><RouterLink to="/home">HOME</RouterLink></li>
            <li><RouterLink to="/events">EVENTS</RouterLink></li>
            <li><RouterLink to="/ticket">TICKET</RouterLink></li>
            <li><RouterLink to="/merchandise">MERCHANDISE</RouterLink></li>
            <li><RouterLink to="/contact">CONTACT</RouterLink></li>
            <li><RouterLink to="/feedback">FEEDBACK</RouterLink></li>
          </ul>
        </nav>
        <!-- Social Media Icons -->
        <div class="footer-social">
          <a href="https://instagram.com" target="_blank" aria-label="Instagram">
            <i class="fa fa-instagram"></i>
          </a>
          <a href="https://facebook.com" target="_blank" aria-label="Facebook">
            <i class="fa fa-facebook"></i>
          </a>
        </div>
      </div>

      <!-- Right Logo -->
      <div class="footer-logo">
        <img src="" alt="USM Logo" class="footer-logo-img" />
      </div>
    </div>
</template>

<style>
.footer {
  margin-top: 5%;
  background-color: #665758; /* Match the background color */
  color: white;
  padding: 20px 0;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.footer-logo {
  display: flex;
  align-items: center;
}

.footer-logo-img {
  max-width: 80px;
  height: auto;
}

.footer-nav {
  text-align: center;
}

.footer-nav h2 {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 10px;
}

.footer-links {
  display: flex;
  gap: 20px;
  list-style: none;
  padding: 0;
  margin: 0;
  z-index: 10;
}

.footer-links a {
  text-decoration: none;
  color: white;
  font-size: 14px;
}

.footer-links a:hover {
  color: #ffc8dd; /* Subtle hover effect */
}

.footer-social {
  margin-top: 10px;
}

.footer-social a {
  color: white;
  font-size: 20px;
  margin: 0 10px;
  text-decoration: none;
}

.footer-social a:hover {
  color: #ffc8dd; /* Subtle hover effect */
}
</style>