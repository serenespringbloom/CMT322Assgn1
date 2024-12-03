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
                <a href="#" class="button">Buy Now</a>
              </div>
            </div>
        <!-- Second Merchant -->
            <div class="bajusatu">
            <img src="@/assets/images/merchant2.png" alt="" />
              <div class="card-satu">
                <h2>White Pink Cotton Tee</h2>
                <p>RM 38</p>
                <a href="#" class="button">Buy Now</a>
              </div>
            </div>
        <!-- Third Merchant -->
            <div class="bajusatu">
            <img src="@/assets/images/merchant3.png" alt="" />
              <div class="card-satu">
                <h2>White Blue Cotton Tee</h2>
                <p>RM 38</p>
                <a href="#" class="button">Buy Now</a>
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
    font-size: 1.0rem;
    padding-top: 10;
  }
}

.merchant-list{
  display: flex;
  padding: 3rem;
  column-gap: 30px;
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
  top: 75px;
  right: 300px;
}

.button {
  background-color: #DCC39C;
  border: none;
  color: black;
}

</style>