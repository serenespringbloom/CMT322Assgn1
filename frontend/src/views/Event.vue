<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const events = ref([]); // Holds the list of events
const form = reactive({
  event_id: null,
  event_title: '',
  event_subtitle: '',
  event_venue: '',
  event_datetime: '',
  event_about: '',
  event_email: '',
  event_phone: '',
}); // Holds form data
const isEditing = ref(false); // Tracks whether the form is in edit mode

const fetchEvents = async () => {
  try {
    // Log the URL to check if it's correct
    console.log(`${import.meta.env.VITE_API_URL}/api/events`);

    const response = await axios.get(`${import.meta.env.VITE_API_URL}/api/events`);
    events.value = response.data; // Update event list with data from API
  } catch (error) {
    console.error('Error fetching events:', error);
  }
};

const submitForm = async () => {
  try {
    if (isEditing.value) {
      // Update event
      await axios.put(`${import.meta.env.VITE_API_URL}/api/events/${form.event_id}`, form);
    } else {
      // Create new event
      await axios.post(`${import.meta.env.VITE_API_URL}/api/events`, form);
    }

    resetForm();
    await fetchEvents(); // Refresh event list
  } catch (error) {
    console.error('Error submitting form:', error);
  }
};

const editEvent = (event) => {
  // Populate the form with event data for editing
  form.event_id = event.event_id;
  form.event_title = event.event_title;
  form.event_subtitle = event.event_subtitle;
  form.event_venue = event.event_venue;
  form.event_datetime = event.event_datetime;
  form.event_about = event.event_about;
  form.event_email = event.event_email;
  form.event_phone = event.event_phone;
  isEditing.value = true;
};

const deleteEvent = async (id) => {
  try {
    await axios.delete(`${import.meta.env.VITE_API_URL}/api/events/${id}`);
    await fetchEvents(); // Refresh event list
  } catch (error) {
    console.error('Error deleting event:', error);
  }
};

const resetForm = () => {
  form.event_id = null;
  form.event_title = '';
  form.event_subtitle = '';
  form.event_venue = '';
  form.event_datetime = '';
  form.event_about = '';
  form.event_email = '';
  form.event_phone = '';
  isEditing.value = false;
};

onMounted(fetchEvents); // Fetch events on page load
</script>


<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Form Section -->
    <form @submit.prevent="submitForm" class="space-y-6 bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">{{ isEditing ? 'Edit Event' : 'Add New Event' }}</h2>

      <div>
        <label class="block font-medium text-gray-700">Title:</label>
        <input
          v-model="form.event_title"
          type="text"
          placeholder="Enter event title"
          required
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Subtitle:</label>
        <input
          v-model="form.event_subtitle"
          type="text"
          placeholder="Enter event subtitle"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Venue:</label>
        <input
          v-model="form.event_venue"
          type="text"
          placeholder="Enter event venue"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Date and Time:</label>
        <input
          v-model="form.event_datetime"
          type="datetime-local"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">About:</label>
        <textarea
          v-model="form.event_about"
          placeholder="Enter event description"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        ></textarea>
      </div>

      <div>
        <label class="block font-medium text-gray-700">Email:</label>
        <input
          v-model="form.event_email"
          type="email"
          placeholder="Enter event email"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Phone:</label>
        <input
          v-model="form.event_phone"
          type="tel"
          placeholder="Enter phone number"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-green-500 text-white py-2 px-4 rounded-md shadow hover:bg-green-600"
      >
        {{ isEditing ? 'Update Event' : 'Create Event' }}
      </button>
      <button
        type="button"
        v-if="isEditing"
        @click="resetForm"
        class="w-full mt-2 bg-gray-500 text-white py-2 px-4 rounded-md shadow hover:bg-gray-600"
      >
        Cancel
      </button>
    </form>

    <!-- Table Section -->
    <div class="mt-10">
      <h2 class="text-xl font-bold mb-4">Events List</h2>
      <table class="min-w-full bg-white border border-gray-300">
        <thead class="bg-gray-100">
          <tr>
            <th class="py-2 px-4 border-b">Title</th>
            <th class="py-2 px-4 border-b">Subtitle</th>
            <th class="py-2 px-4 border-b">Venue</th>
            <th class="py-2 px-4 border-b">Date & Time</th>
            <th class="py-2 px-4 border-b">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="event in events" :key="event.event_id">
            <td class="py-2 px-4 border-b">{{ event.event_title }}</td>
            <td class="py-2 px-4 border-b">{{ event.event_subtitle }}</td>
            <td class="py-2 px-4 border-b">{{ event.event_venue }}</td>
            <td class="py-2 px-4 border-b">{{ new Date(event.event_datetime).toLocaleString() }}</td>
            <td class="py-2 px-4 border-b">
              <button @click="editEvent(event)" class="bg-yellow-500 text-white px-4 py-2 rounded-md">
                Edit
              </button>
              <button @click="deleteEvent(event.id)" class="bg-red-500 text-white px-4 py-2 rounded-md ml-2">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>
