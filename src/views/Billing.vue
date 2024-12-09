<script setup>

import TransactionMerch from "./components/TransactionMerch.vue";
import BillingMerch from "./components/BillingMerch.vue";
import InvoiceCard from "./components/InvoiceCard.vue";
import BillingCard from "./components/BillingCard.vue";
import TransactionCard from "./components/TransactionCard.vue";
</script>
<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8">
        <div class="row mt-4">
          <div class="col-xl-6 mb-xl-0 mb-4">
         
          </div>
          <div class="col-xl-30">
            <div class="row">
              <div class="col-md-30">
                <invoice-card class="mt-4" />
              </div>
             
            </div>
          </div>
          <div class="col-md-12 mb-4">
            <payment-card />
          </div>
        </div>
      </div>
    
    </div>
    <div class="row">
      <div class="col-md-7">
        <billing-card />
      </div>
      <div class="col-md-5">
        <transaction-card />
      </div>
    </div>

    <div class="row">
      <div style="margin-top: 20px;" class="col-md-7">
        <billing-merch />
      </div>
      <div style="margin-top: 20px;" class="col-md-5">
        <transaction-merch />
      </div>
    </div>
  </div>
</template>
