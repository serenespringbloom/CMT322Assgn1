<template>
  <div class="flex flex-wrap justify-center gap-4 p-4 w-full">
    <div
      v-for="card in cards"
      :key="card.id"
      class="flex flex-wrap md:flex-col bg-gray-100 border border-gray-300 rounded-lg shadow p-4  w-[20%]"
    >
      <div class="flex flex-wrap md:flex-row items-center align-middle gap-4 justify-center md:gap-20">
        <img
          :src="card.image"
          alt="Card image"
          class="w-[80px] h-[80px] object-cover rounded-lg"
        />
        <div class="text-left">
          <h3 class="text-lg font-semibold text-gray-800">{{ card.title }}</h3>
          <p class="text-gray-600">{{ card.description }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white w-4/5 mx-auto p-8 rounded-lg shadow relative text-center mt-10">
    <div
      class="   bg-gradient-to-r from-yellow-700 to-yellow-400 text-white py-2 px-6 rounded-md border-4 border-yellow-600 text-lg w-full "
    >
      Sales by Entrance Category
    </div>
    <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-4">
      <div
        v-for="newcard in newcards"
        :key="newcard.id"
        class="bg-gray-100 border border-gray-300 rounded-lg shadow p-4"
      >
        <div class="flex items-start gap-4">
          <img
            :src="newcard.image"
            alt="Card image"
            class="w-24 h-24 object-cover rounded-lg"
          />
          <div class="flex flex-col justify-between">
            <h3 class="text-lg font-semibold text-gray-800">{{ newcard.title }}</h3>
            <div class="flex gap-4 text-gray-700">
              <strong>Sales:</strong>
              <p>{{ newcard.description }}</p>
            </div>
            <div class="flex gap-4 text-gray-700">
              <strong>Values:</strong>
              <p>{{ newcard.description1 }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-white w-4/5 mx-auto p-8 rounded-lg shadow relative text-center mt-10">
    <h2 class="text-2xl font-semibold">HOME</h2>
    <p class="text-gray-600">Take me to Test page</p>
    <button
      @click.prevent="triggerEndpoint"
      class="mt-4 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600"
    >
      Trigger Endpoint
    </button>
    <p v-if="response" class="text-gray-700 mt-4">{{ response }}</p>
  </div>

  <div class="bg-white w-4/5 mx-auto p-8 rounded-lg shadow relative text-center mt-10">
    <h2 class="text-2xl font-semibold">Add New Item</h2>
    <form @submit.prevent="storeItem" class="space-y-4 max-w-lg mx-auto mt-6">
      <div>
        <label class="block text-gray-700 text-sm font-medium" for="name">Name:</label>
        <input
          type="text"
          v-model="form.name"
          required
          class="mt-1 w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
      <div>
        <label
          class="block text-gray-700 text-sm font-medium"
          for="description"
        >
          Description:
        </label>
        <textarea
          v-model="form.description"
          class="mt-1 w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"
        ></textarea>
      </div>
      <button
        type="submit"
        class="w-full bg-green-500 text-white py-2 px-4 rounded-md shadow hover:bg-green-600"
      >
        Save
      </button>
    </form>
    <p v-if="responseMessage" class="mt-4 text-green-600">{{ responseMessage }}</p>
  </div>
</template>

<script>
import axios from 'axios';
import { ref } from 'vue';

// Define reactive state for response
const response = ref(null);

// Define the API call function
const triggerEndpoint = async () => {
    try {
        const res = await  axios.get('http://127.0.0.1:8000/api/test');//get data from database
        response.value = res.data; // Assign API response
    } catch (error) {
        console.error('Error:', error);
        response.value = 'Failed to fetch data from the endpoint.';
    }
};
export default {
  data() {
    return {
      form: {
        name: '',
        description: ''
      },
      responseMessage: ''
    };
  },
  methods: {
    async storeItem() {
      try {
        const response = await axios.post(`${import.meta.env.VITE_API_URL}/api/store-item`, this.form);//post data to database
        this.responseMessage = response.data.message;
        this.form.name = '';
        this.form.description = '';
      } catch (error) {
        console.error('Error:', error.response.data);
        this.responseMessage = 'Failed to store item!';
      }
    }
  }
};
</script>
<script setup>
  
import salesImage from '../assets/images/sales.jpg';
import clientImage from '../assets/images/client.png';
import feedbackImage from '../assets/images/feedback.png';
import totalSales from '../assets/images/totalSales.png';
import studentSales from '../assets/images/flags/studentic.png';
import guestSales from '../assets/images/flags/guestic.png';

  const cards = [
    {
      id: 1,
      title: "Total Sales",
      description: "+RM400",
      image: salesImage, // Example placeholder image
    },
    {
      id: 2,
      title: "Merchandise Sales",
      description: "+30",
      image: clientImage,
    },
    {
      id: 3,
      title: "Customer Feedback",
      description: "+20",
      image: feedbackImage,
    },
    {
      id: 4,
      title: "Ticket Sales",
      description: "RM5000",
      image: totalSales,
    },
  ];
  const newcards = [
    {
      id: 7,
      title: "Student",
      description: "230",
      description1: "RM6000",
      image: studentSales, // Example image URL
    },
    {
      id: 8,
      title: "Guest",
      description: "150",
      description1: "RM4000",
      image: guestSales, // Example image URL
    },
  
  ];
</script>
  
<style >
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>