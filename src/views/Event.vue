
<script setup>

import { ref } from 'vue';

// Reactive data
const userInput = ref(''); // Bind to the input field
const showModal = ref(false); // Control modal visibility
const modalMessage = ref(''); // Message to display in the modal




const loadFromSessionStorage = () => {
  const saveduserInput = JSON.parse(sessionStorage.getItem('userInput')) || [];


  userInput.value = saveduserInput;
};

// const saveToSessionStorage = () => {
//   sessionStorage.setItem('userInput', JSON.stringify(userInput.value));

// };



// Handle form submission
const handleSubmit = () => {
  if (userInput.value.trim() !== '') {
    modalMessage.value = `You have submitted event named :-, ${userInput.value}!`;
  } else {
    modalMessage.value = 'Please enter your name.';
  }
  showModal.value = true; // Show the modal
};

// Close the modal
const closeModal = () => {
  showModal.value = false; // Hide the modal
};

import EventCard from "../components/EventCard.vue";


loadFromSessionStorage();
</script>

<template>
  <div class="container-fluid">
 
    <div class="form-container">
    <!-- Form -->
    <form @submit.prevent="handleSubmit">
      <label for="textbox">Enter Event headline:</label>
      <input type="text" v-model="userInput" id="textbox" required>
      <label for="textbox">Enter Event details:</label>
      <input type="text" v-model="userInput" id="textbox" required>
      <label for="textbox">Enter Event tags:</label>
      <input type="text" v-model="userInput" id="textbox" required>
      <label for="textbox">Input Event image :</label>
      <input type="file" id="imagefile" required>
      <button type="submit">Submit</button>
    </form>

    <!-- Modal -->
    <div v-if="showModal" class="modal" @click.self="closeModal">
      <div class="modal-content">
        <span class="close-button" @click="closeModal">&times;</span>
        <p>{{ modalMessage }}</p>
      </div>
    </div>
  </div>

  <div style="margin-top: 20px;margin-left: 20px;" class="box">
        <div class="box-title">Event Carousel Album</div>
        <div class="box-content">
            Fill up the form above to add new event carousel.
        </div>
    </div>
    <div style="margin-bottom:700px;margin-top: 10px;" class="row">
      <div class="col-md-7">
        <EventCard />
      </div>
   
    </div>
  </div>






  
</template>

<style>

.box {
    width: 1000px;
    border: 2px solid #aa9758;
    border-radius: 8px;
    background-color: #ffffff;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.box-title {
  background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));
    color: #ffffff;
    padding: 10px;
    font-size: 18px;
    text-align: center;
}

.box-content {
    padding: 15px;
    color: #333;
    font-size: 16px;
    text-align: center;
}



.roundedbut{

background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));
                 
                 color: white;
                 padding: 10px 20px;
                 border: none;
                 border-radius: 20px;
                 font-size: 16px;
                 cursor: pointer;
                 box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                 transition: all 0.2s ease-in-out;
                 margin-top: 20px;
                 margin-left: 25px;



}

/* Form styles */
.form-container {
  padding: 20px;
  border: 2px solid #4caf50;
  border-radius: 8px;
  background-color: #ffffff;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}

input[type="text"] {
  width: 100%;
  padding: 8px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button {
  background-color: #4caf50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}

/* Modal styles */
.modal {
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.4);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
}

.close-button {
  color: red;
  font-size: 24px;
  cursor: pointer;
}


</style>



