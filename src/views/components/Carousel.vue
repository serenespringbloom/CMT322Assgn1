

<template>
  <div class="card card-carousel overflow-hidden h-100 p-0">
    <div
      id="carouselExampleCaptions"
      class="carousel slide h-100"
      data-bs-ride="carousel"
    >
      <div class="carousel-inner border-radius-lg h-100">
        <div
          class="carousel-item h-100 active"
          :style="{
            backgroundImage:
              'url(' + require('@/assets/img/event/eventpic3.jpg') + ')',
            backgroundSize: 'cover',
          }"
        >
          <div
            class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5"
          >
            <div
              class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3"
            >
              <i class="ni ni-camera-compact text-dark opacity-10"></i>
            </div>
            <h5 class="text-white mb-1">Get to know Sabahan Culture</h5>
            <p>
              You can enterntain yourself by having a great time watching the performance brought by our Sabahan participants.
            </p>
          </div>
        </div>
        <div
          class="carousel-item h-100"
          :style="{
            backgroundImage:
              'url(' + require('@/assets/img/event/eventpic1.jpg') + ')',
            backgroundSize: 'cover',
          }"
        >
          <div
            class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5"
          >
            <div
              class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3"
            >
              <i class="ni ni-bulb-61 text-dark opacity-10"></i>
            </div>
            <h5 class="text-white mb-1">Learn more about Sabahan tribe and dances.</h5>
            <p>
              Get to know Sabah tribe and their respective dances and  cultureal performances.
            </p>
          </div>
        </div>
        <div
          class="carousel-item h-100"
          :style="{
            backgroundImage:
              'url(' + require('@/assets/img/event/eventpic2.jpg') + ')',
            backgroundSize: 'cover',
          }"
        >
          <div
            class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5"
          >
            <div
              class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3"
            >
              <i class="ni ni-trophy text-dark opacity-10"></i>
            </div>
            <h5 class="text-white mb-1">Meet our invited celebrity!</h5>
            <p>
              Don’t miss your chances to meet our celebrity guest originally from Sabah .
            </p>
          </div>
        </div>
      </div>
      <button
        class="carousel-control-prev w-5 me-3"
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button
        class="carousel-control-next w-5 me-3"
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
  </div>
</template>
<script setup>


</script>