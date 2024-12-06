<script>
    import { RouterLink, RouterView } from 'vue-router';
</script>

<template>
    <div class="footer-container">
      <!-- Left Logo -->
      <div class="footer-logo">
        <img src="../assets/images/persis-logo.png" alt="PERSIS Logo" class="footer-logo-img" />
      </div>

      <!-- Center Navigation -->
      <div class="footer-nav">
        <h2>MCB</h2>
        <nav>
          <ul class="footer-links">
            <li><RouterLink to="/">HOME</RouterLink></li>
            <li><RouterLink to="/ticket">TICKET</RouterLink></li>
            <li><RouterLink to="/merchandise">MERCHANDISE</RouterLink></li>
            <li><RouterLink to="/feedback">FEEDBACK</RouterLink></li>
            <li><RouterLink to="/refund">REFUND</RouterLink></li>
            <li><RouterLink to="/dashboard">ADMIN</RouterLink></li>
          </ul>
        </nav>
        <!-- Social Media Icons -->
        <div class="footer-social">
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
          <a href="https://www.instagram.com/persis_usm/" target="_blank" aria-label="Instagram">
            <i class="fa fa-instagram"></i>
          </a>
          <a href="https://www.facebook.com/people/Persis-Usm/pfbid0itJdVSwedqY1HNc1W9jEgVnyDFxR5gLCCKafzMYoxLobiVET2mZWVGfFXiLWGBm7l/?mibextid=ZbWKwL" 
             target="_blank" aria-label="Facebook">
            <i class="fa fa-facebook"></i>
          </a>
          <a href="https://www.youtube.com/@persisusm4596" target="_blank" aria-label="Youtube">
            <i class="fa fa-youtube"></i>
          </a>
          <a href="https://linktr.ee/persisusm" target="_blank" aria-label="Linktree">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="m13.736 5.853l4.005-4.117l2.325 2.38l-4.2 4.005h5.908v3.305h-5.937l4.229 4.108l-2.325 2.334l-5.74-5.769l-5.741 5.769l-2.325-2.325l4.229-4.108H2.226V8.121h5.909l-4.2-4.004l2.324-2.381l4.005 4.117V0h3.472zm-3.472 10.306h3.472V24h-3.472z"/></svg>
          </a>
        </div>
      </div>

      <!-- Right Logo -->
      <div class="footer-logo">
        <img src="../assets/images/usm-logo.png" alt="USM Logo" class="footer-logo-img" />
      </div>
    </div>
</template>

<style>
.footer {
  margin-top: 5%;
  background-color: #665758; /* Match the background color */
  color: white;
  padding: 20px 0;
  height: 20%;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  max-width: 1200px;
  margin: 0 auto;
  padding: 10px 20px;
}

.footer-logo {
  display: flex;
  align-items: center;
}

.footer-logo-img {
    height:125px;
    width:auto;
    gap: 500px;
}

.footer-nav {
  text-align: center;
  z-index: 5;
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
}

.footer-links li a {
  text-decoration: none;
  color: white;
  font-size: 14px;
}

.footer-links li a:hover {
  color: #ffc8dd;
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

svg{
  height:20px;
  padding-top:4px;
}
</style>