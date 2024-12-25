<script setup>


import TableBox from "../components/TableBox.vue";
import TableBox2 from "../components/TableBox2.vue";
import TableBox3 from "../components/TableBox3.vue";
import TableBox4 from "../components/TableBox4.vue";
</script>
<template>
  <div class="container-fluid">

    <div style="background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));"
      >
     
      <div style="background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));" 
      class="text-white p-4 text-xl font-bold flex item-center justify-center">
         <h2>Ticket Billing</h2> 
        </div>
      </div>
    <div class="row">
      <div class="col-lg-8">
        <div class="row mt-4">
          <div class="bg-white border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow flex item-center justify-center"> 
          <div class="col-xl-6 mb-xl-0 mb-4">
         
          </div>
          <div class="col-xl-30">
            <TableBox />
          </div>
          <div class="col-md-12 mb-6">
            <TableBox2/>
          </div>
          </div>
        </div>
      </div>
    
    </div>
    <div style="background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));margin-top: 25px;"
      >
     
      <div style="background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));" 
      class="text-white p-4 text-xl font-bold flex item-center justify-center">
         <h2>Merchandise Billing</h2> 
        </div>
      </div>
    <div class="row mt-4">
      <div class="bg-white border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow flex item-center justify-center"> 
      <div class="col-md-7">
       <TableBox3/>
      </div>
      <div class="col-md-5">
        <TableBox4/>
      </div>
      </div>
    </div>
</div>
 
</template>
