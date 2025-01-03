<template>
    <div class="container">
      <div class="card" v-for="card in cards" :key="card.id">
        <div class="card-content">
          <img :src="card.image" alt="Card image" class="card-image" />
          <div class="card-text">
            <h3>{{ card.title }}</h3>
            <p>{{ card.description }}</p>
          </div>
        </div>
      </div>
  
    </div>
    <div class="card-container">
            <div class="container-title"> Sales by Entrance Category</div>
             
              <div class="card1" v-for="newcard in newcards" :key="newcard.id">
                <div class="card-content">
                  <img :src="newcard.image" alt="Card image" class="card-image" />
                      <div class="card-text">
                      <h3>{{ newcard.title }}</h3>
                      <strong><h4>  Sales</h4></strong>
                         <p>{{ newcard.description }}</p>
                         <strong><h4>  Values</h4></strong>
                          <p>{{ newcard.description1 }}</p>
                </div>
               </div>
            </div>
   
    </div>
    <div>
        <h2>HOME</h2>
        <p>Take me to Test page</p>
        <button @click.prevent="triggerEndpoint">Trigger Endpoint</button>
        <p v-if="response">{{ response }}</p>
    </div>
   <p>This for get data</p>   
    <div>
    <h2>Add New Item</h2>
    <form @submit.prevent="storeItem">
      <label for="name">Name:</label>
      <input type="text" v-model="form.name" required />
      
      <label for="description">Description:</label>
      <textarea v-model="form.description"></textarea>
      
      <button type="submit">Save</button>
    </form>
    <p v-if="responseMessage">{{ responseMessage }}</p>
  </div>
  <p>This for post data</p> 
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
        const response = await axios.post('http://hazimazman.xyz/backend/api/store-item', this.form);//post data to database
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
      title: "Todays' Sale",
      description: "+RM400",
      image: salesImage, // Example placeholder image
    },
    {
      id: 2,
      title: "New Users",
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
      title: "Total Sales",
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
  
  .container-title {
              position: absolute;
              top:5px;
              left: 50%;
              transform: translateX(-50%);
              background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));
      color: white;
              padding: 10px 20px;
              border-radius: 12px;
              border-color: #a2a465;
              border-width: 5px;
              font-size: 1.2rem;
            width:1350px;
            margin-bottom: 20px;
           
             
          }
  .card-container {
    background: white;
              width: 80%;
              padding: 60px 20px;
              border-radius: 10px;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
              position: relative;
              text-align: center;
  
  }
  .container {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
    padding: 1rem;
  }
  
  .card {
    flex: 1 1 calc(25% - 1rem);
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    margin: 0.5rem 0;
  }
  
  .card1 {
    top:20px;
    flex: 1 1 calc(50% - 1rem);
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    margin: 0.5rem 0;
  }
  
  .card2{
   display:flex;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 1rem;
    margin: 0.5rem 0;
  }
  
  .card-content {
    display: flex;
    align-items: center; /* Vertically align image and text */
    gap: 1rem; /* Space between image and text */
  }
  
  .card-image {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px; /* Optional: Make the image corners rounded */
  }
  
  .card-text {
    font-size: 20px;
    font-family: 'freight-big-pro';
    flex: 1; /* Allow text to take remaining space */
  }
  
  .card-text h3 {
    margin: 0.5rem 0;
    color: #333;
  }
  
  .card-text p {
    color: #555;
  }
  
  @media (max-width: 768px) {
    .card {
      flex: 1 1 100%;
    }
  }
  </style>