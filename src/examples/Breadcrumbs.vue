<script setup>
import { computed } from "vue";
import { useStore } from "vuex";

const store = useStore();
const isRTL = computed(() => store.state.isRTL);
//const isNavFixed = computed(() => store.state.isNavFixed);

defineProps({
  currentPage: {
    type: String,
    default: "",
  },
  currentDirectory: {
    type: String,
    default: "",
  },
});
</script>
<template>
  <nav aria-label="breadcrumb" style="color: white;">
    <ol
      class="px-0 pt-1 pb-0 mb-0 bg-transparent breadcrumb"
     
    >
      <li class="text-sm breadcrumb-item">
        <a
          v-if="isRTL"
          class="opacity-5 ps-2"
          href="#"
          
          >لوحات القيادة</a
        >
        <a
          v-else
         
          class="opacity-8"
          href="#"
          >Pages</a
        >
      </li>
      <li
        class="text-sm breadcrumb-item active"
       
        aria-current="page"
      >
        {{ currentPage }}
      </li>
    </ol>
    <h6
      class="mb-0 font-weight-bolder"
     
    >
      {{ currentDirectory }}
    </h6>
  </nav>
</template>
