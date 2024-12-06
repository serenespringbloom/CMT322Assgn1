<script setup>
  import { RouterLink, RouterView } from 'vue-router'
  import { computed } from 'vue';
  import { useRoute } from 'vue-router';

</script>

<template>
    <div class="header">
      <h1>MERCHANDISE</h1>
    </div>
    <!-- Merchandise Section -->
    <section class="merchant-list">
      <!-- First Merchant -->
      <div class="merchant-card">
        <img src="@/assets/images/merchant1.png" alt="Black Pink Cotton Tee" />
        <div class="card-content">
          <h2>Black Pink Cotton Tee</h2>
          <p>RM 38</p>
          <RouterLink to="/merchandise-detail">
            <button class="button">Buy Now</button>
          </RouterLink>
        </div>
      </div>
      <!-- Second Merchant -->
      <div class="merchant-card">
        <img src="@/assets/images/merchant2.png" alt="White Pink Cotton Tee" />
        <div class="card-content">
          <h2>White Pink Cotton Tee</h2>
          <p>RM 38</p>
          <RouterLink to="/merchandise-detail2">
            <button class="button">Buy Now</button>
          </RouterLink>
        </div>
      </div>
      <!-- Third Merchant -->
      <div class="merchant-card">
        <img src="@/assets/images/merchant3.png" alt="White Blue Cotton Tee" />
        <div class="card-content">
          <h2>White Blue Cotton Tee</h2>
          <p>RM 38</p>
          <RouterLink to="/merchandise-detail3">
            <button class="button">Buy Now</button>
          </RouterLink>
        </div>
      </div>
    </section>
</template>

<style>
@media (min-width: 1024px) {
  .a{
    top: 20;
    min-height: 10vh;
    display: flex;
    justify-content: center;
    align-items: center;
    letter-spacing: .9rem;
    font-family: 'Plus Jakarta Sans', serif;
    font-size: 1.rem;
    padding-top: 10;
    margin-top: 4%;
  }
}

/* Header */
.header {
  text-align: center;
  letter-spacing: .9rem;
  font-family: "Plus Jakarta Sans", serif;
  margin-top: 2rem;
}

.header h1 {
  font-size: 2.3rem;
  letter-spacing: 2rem;
  margin-bottom: 1.5rem;
  color: #554149;
  text-transform: uppercase;
}

/* Merchandise Section */
.merchant-list {
  display: flex;
  gap: 2rem;
  padding: 2rem;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap; /* Ensures responsiveness */
}

/* Merchant Card */
.merchant-card {
  background: linear-gradient(135deg, #fff, #f9f9f9);
  border-radius: 15px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  text-align: center;
  padding: 1.5rem;
  width: 320px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.merchant-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

/* Merchant Image */
.merchant-card img {
  width: 100%;
  height: auto;
  border-radius: 12px;
  margin-bottom: 1rem;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Card Content */
.card-content h2 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #554149;
  margin-bottom: 0.5rem;
}

.card-content p {
  font-size: 1rem;
  color: #777;
  margin-bottom: 1rem;
}

/* Button */
.button {
  background-color: #dcc39c;
  color: #000;
  font-size: 1rem;
  font-weight: bold;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  transition: background-color 0.3s ease, transform 0.3s ease;
}

.button:hover {
  background-color: #f0f0f0;
  transform: translateY(-3px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* Responsive Design */
@media (max-width: 768px) {
  .merchant-list {
    flex-direction: column;
    align-items: center;
  }

  .merchant-card {
    width: 90%; /* Adjust card width for smaller screens */
  }
}
</style>
