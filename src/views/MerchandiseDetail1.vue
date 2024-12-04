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
            <div class="product-one">
                <div class="product-media"><img src="@/assets/images/merchant1.png" alt="" /></div>
                <div class="product-info">
                    <section>
                        <p>Malam Citra Bayu</p>
                        <div class="product-title">WHITE PINK COTTON TEE</div>
                        <div>RM 38</div>
                        <!-- Size Clothes -->
                        <fieldset class="product-size">
                         <div class="size-group">
                            <label>Shirt Size:</label>
                            <div class="size-button">
                                <input checked type="radio" id="type1" name="type" value="small">
                                <label data-icon="S" for="type1"><p>Small</p></label>
                            </div>
                            
                            <div class="size-button">
                                <input disabled type="radio" id="type2" name="type" value="medium">
                                <label data-icon="M" for="type2"><p>Medium</p></label>
                            </div>
                            
                            <div class="size-button">
                                <input type="radio" id="type3" name="type" value="large">
                                <label data-icon="L" for="type3">
                                <p>Large</p>
                                </label>
                            </div>
                            
                            <div class="size-button">
                                <input type="radio" id="type4" name="type" value="extra-large">
                                <label data-icon="XL" for="type4"><p>Extra Large</p></label>
                            </div>
                            </div> 
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
                            <button class="button" type="submit">Add to Cart</button>
                         </div>
                    </section>
                </div>
            </div>
        </section>
    </body>
</template>

<style>

.product-one{
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 2rem;
    padding: 0;
}

.product-media{
    display: block;
    position: relative;
    background: transparent;
    box-sizing: inherit;
    border-style: double;

}
.product-info{
    display: block;
    padding: 0 0 0 4rem;
    max-width: 35%;
}

.product-title{
    font-size: 1.5rem;
    letter-spacing: 0.06rem
}

.size {
  &-button {
    position: relative;
    display: inline-block;
      
    label {
    cursor: pointer;
    color: #9f9f9f;
    user-select: none;
    border: 1px solid #e6e6e6;
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    -webkit-tap-highlight-color: transparent;
    padding: 1em;
    height: 4em;
    width: 4em;
    overflow: hidden;
    display: block;
    text-align: center;
    border-radius: 4px;
    margin: 1em;
    box-shadow: 0px 0px 5px #e5e5e5;
    transition: all 120ms ease-in-out;
      
      p {
        font-size: 12px;
        margin: 0;
      }
      
      &:before {
        font-family: "Ionicons";
        content: attr(data-icon);
        font-size: 2em;
        display: block;
        line-height: 1.5;
      }
    }

    input[type="radio"] {
      display: none;
    }


     input[type="radio"]:checked + label {
       color: #fff;
       border: 1px solid $primary;
       background: $primary;
    }
    
    input[type="radio"]:disabled + label {
       cursor: not-allowed;
       color: #ccc;
       background: #f5f5f5; 
       border: 1px solid #e7e7e7;
    }
  }
}

.counter{
    width: 200px;
    height: 70px;
    background-color: #edeefa;
    padding: 15px;
    margin-left: 20px;
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

.button{
  background-color: #DCC39C;
  padding: 16px 30px;
  margin-top: 20px;
  border: none;
  color: black;
}
</style>
