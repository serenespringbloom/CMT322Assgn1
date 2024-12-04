<script>
    let qty = document.querySelector("#qtyInput");

    function decrement() {
        if (qty.value <= 1) {
            qty.value = 1;
        } else {
            qty.value = parseInt(qty.value) - 1;
        }
    }

    function increment() {
        qty.value = parseInt(qty.value) + 1;
    }
</script>

<template>
    <body>
        <div>
        <h1>This is Merchandise 1</h1>
        </div>

        <section>
            <div>
                <img src="@/assets/images/merchant1.png" alt="" />
                <div class="product-info">
                    <section>
                        <p>Malam Citra Bayu</p>
                        <div class="product-title">WHITE PINK COTTON TEE</div>
                        <div>RM 38</div>
                        <!-- Size Clothes -->
                        <fieldset class="product-size">
                            <label>XS
                                <span></span>
                            </label>
                            <label>S
                                <span></span>
                            </label>
                            <label>M
                                <span></span>
                            </label>
                            <label>L
                                <span></span>
                            </label>
                            <label>XL
                                <span></span>
                            </label>
                            <label>2XL
                                <span></span>
                            </label>
                        </fieldset>
                        <!-- Quantity -->
                        <div class="quantity">
                            <label>Quantity</label>
                            <div class="counter">
                                <button class="btn minus" type="button" v-on:click="decrement()">
                                -</button>
                            <input type="text" value="1" id="qtyInput">  
                            <button class="btn minus" type="button" v-on:click="increment()">
                                +</button>
                            </div>
                        </div>

                        <!-- Add to Cart -->
                         <div>
                            <button>Add to Cart</button>
                         </div>
                    </section>
                </div>
            </div>
        </section>
    </body>
</template>

<style>
.counter{
    width: 200px;
    height: 70px;
    background-color: #edeefa;
    padding: 15px;
    display: inline-flex;
    overflow: hidden;
    align-items: center;
    border-radius: 20px;
}

.btn{
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    border: none;
    width: 50px;
    height: 50px;
    font-size: 1rem;
    cursor: pointer;
}

#qtyInput{
    width: 100%;
    border: none;
    font-size: 1rem;
    font-family:'Courier New', Courier, monospace;
    text-align: center;
    color: #000;
    background: 0 0;
    font-weight: 700;
}

#qtyInput:focus-visible{
    outline: none;
}
</style>
