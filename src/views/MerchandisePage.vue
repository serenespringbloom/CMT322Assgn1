<script setup>
  import { RouterLink, RouterView } from 'vue-router'
  import { computed } from 'vue';
  import { useRoute } from 'vue-router';    
  
</script>

<template>
    <body>
        <div class="a">
        <h1>MERCHANDISE</h1>
        </div>
        <!-- Section -->
        <section class="merchant-list">
        <!-- First Merchant -->
            <div class="bajusatu">
            <img src="@/assets/images/merchant1.png" alt="" />
              <div class="card-satu">
                <h2>Black Pink Cotton Tee</h2>
                <p>RM 38</p>
                <RouterLink to="/merchandise-detail"><button class="button">Buy Now</button></RouterLink>
              </div>
            </div>
        <!-- Second Merchant -->
            <div class="bajusatu">
            <img src="@/assets/images/merchant2.png" alt="" />
              <div class="card-satu">
                <h2>White Pink Cotton Tee</h2>
                <p>RM 38</p>
                <RouterLink to="/merchandise-detail2"><button class="button">Buy Now</button></RouterLink>
              </div>
            </div>
        <!-- Third Merchant -->
            <div class="bajusatu">
            <img src="@/assets/images/merchant3.png" alt="" />
              <div class="card-satu">
                <h2>White Blue Cotton Tee</h2>
                <p>RM 38</p>
                <RouterLink to="/merchandise-detail3"><button class="button">Buy Now</button></RouterLink>
              </div>
            </div>
        </section>
        </body>
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

.merchant-list{
  display: flex;
  padding: 3rem;
  column-gap: 30px;
  align-items: center;
  justify-content: center;
}

.bajusatu img{
  display: flex;
  position: relative;
  flex-direction: row;
  height: 320px;
  width: 400px;
  min-width: 250px;
  padding: 1.5rem;
  column-gap: 30px;
  border-radius: 16px;
  background: aliceblue;
  box-shadow: -1rem 0 3rem #f4d0d6;
}

.card-satu{
  padding-top: 2%;
  top: 75px;
  right: 300px;
  font-size: medium;
}

.button {
  margin-top: 4%;
  padding: 3%;
  background-color: #DCC39C;
  font-size: 0.95rem;
  width:130px;
  border-radius: 8px;
  border: none;
  color: black;
}

.button:hover {
  background-color: #f0f0f0;
  border-color: #b5b5b5;
  cursor: pointer;
}
</style>