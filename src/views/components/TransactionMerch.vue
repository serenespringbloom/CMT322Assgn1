<script setup>
import ArgonButton from "@/components/ArgonButton.vue";
</script>
<template>
  <div class="card h-100 mb-4">
    <div class="card-header pb-0 px-3">
      <div class="row">
        <div class="col-md-6">
          <h6 class="mb-0">Merchandise Sales Record</h6>
        </div>
        <div class="col-md-6 d-flex justify-content-end align-items-center">
          <i class="far fa-calendar-alt me-2" aria-hidden="true"></i>
          <small>23 - 30 April 2024</small>
        </div>
      </div>
    </div>
    <div class="card-body pt-4 p-3">
      <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
        Newest
      </h6>
      <ul class="list-group">
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex align-items-center">
            <argon-button
              color="danger"
              variant="outline"
              size="sm"
              class="btn-icon-only btn-rounded mb-0 me-3 d-flex align-items-center justify-content-center"
            >
              <i class="fas fa-arrow-up*-" aria-hidden="true"></i>
            </argon-button>
            <div class="d-flex flex-column">
              <h6 class="mb-1 text-dark text-sm">134256</h6>
              <span class="text-xs">5 May 2024, at 11:30 PM</span>
            </div>
          </div>
          <div
            class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold"
          >
            + RM24
          </div>
        </li>
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex align-items-center">
            <argon-button
              color="success"
              variant="outline"
              size="sm"
              class="btn-icon-only btn-rounded mb-0 me-3 d-flex align-items-center justify-content-center"
            >
              <i class="fas fa-arrow-up" aria-hidden="true"></i>
            </argon-button>
            <div class="d-flex flex-column">
              <h6 class="mb-1 text-dark text-sm">233523</h6>
              <span class="text-xs">7 May 2024, at 04:30 AM</span>
            </div>
          </div>
          <div
            class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold"
          >
            + RM60
          </div>
        </li>
      </ul>
      <h6 class="text-uppercase text-body text-xs font-weight-bolder my-3">
        LAST MONTH
      </h6>
      <ul class="list-group">
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex align-items-center">
            <argon-button
              color="success"
              variant="outline"
              size="sm"
              class="btn-icon-only btn-rounded mb-0 me-3 d-flex align-items-center justify-content-center"
            >
              <i class="fas fa-arrow-up" aria-hidden="true"></i>
            </argon-button>
            <div class="d-flex flex-column">
              <h6 class="mb-1 text-dark text-sm">235875</h6>
              <span class="text-xs">26 April 2024, at 13:45 PM</span>
            </div>
          </div>
          <div
            class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold"
          >
            + RM72
          </div>
        </li>
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex align-items-center">
            <argon-button
              color="success"
              variant="outline"
              size="sm"
              class="btn-icon-only btn-rounded mb-0 me-3 d-flex align-items-center justify-content-center"
            >
              <i class="fas fa-arrow-up" aria-hidden="true"></i>
            </argon-button>
            <div class="d-flex flex-column">
              <h6 class="mb-1 text-dark text-sm">536322</h6>
              <span class="text-xs">26 April 2024, at 12:30 PM</span>
            </div>
          </div>
          <div
            class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold"
          >
            + RM150
          </div>
        </li>
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex align-items-center">
            <argon-button
              color="success"
              variant="outline"
              size="sm"
              class="btn-icon-only btn-rounded mb-0 me-3 d-flex align-items-center justify-content-center"
            >
              <i class="fas fa-arrow-up" aria-hidden="true"></i>
            </argon-button>
            <div class="d-flex flex-column">
              <h6 class="mb-1 text-dark text-sm">353246</h6>
              <span class="text-xs">27 April 2024, at 09:30 AM</span>
            </div>
          </div>
          <div
            class="d-flex align-items-center text-success text-gradient text-sm font-weight-bold"
          >
            + RM76
          </div>
        </li>
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex align-items-center">
            <argon-button
              color="success"
              variant="outline"
              size="sm"
              class="btn-icon-only btn-rounded mb-0 me-3 d-flex align-items-center justify-content-center"
            >
              <i class="fas fa-arrow-up" aria-hidden="true"></i>
            </argon-button>
            <div class="d-flex flex-column">
              <h6 class="mb-1 text-dark text-sm">2358432</h6>
              <span class="text-xs">26 April 2024, at 05:00 AM</span>
            </div>
          </div>
          <div
            class="d-flex align-items-center text-dark text-sm font-weight-bold"
          >
            Pending
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
