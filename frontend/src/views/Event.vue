<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import axios from 'axios';

// Base API URL for events
const apiBaseUrl = 'http://127.0.0.1:8003/api/event'; // Leave this unchanged

// Reactive state
const events = ref([]);
const agendas = ref([]);
const sponsors = ref([]);
const loading = ref(false); // Loading state for fetching data

// Event form state
const form = reactive({
  event_id: null,
  event_title: '',
  event_subtitle: '',
  event_venue: '',
  event_datetime: '',
  event_about: '',
  event_email: '',
  event_phone: '',
});

// Agenda form state
const agendaForm = reactive({
  agenda_id: null,
  time_start: '',
  time_end: '',
  agenda_title: '',
  agenda_description: '',
});

// Sponsor form state
const sponsorForm = reactive({
  sponsor_id: null,
  sponsor_logo: null,
});

// Editing state
const isEditing = ref(false);

// Centralized error handler
const handleError = (error) => {
  console.error('API Error:', error.response?.data || error.message);
};

// Fetch all events
const fetchEvents = async () => {
  loading.value = true;
  try {
    const response = await axios.get(apiBaseUrl); // Base URL for events
    events.value = response.data;
  } catch (error) {
    handleError(error);
  } finally {
    loading.value = false;
  }
};

// Fetch agendas for a given event
const fetchAgendas = async (eventId) => {
  try {
    const response = await axios.get(`http://127.0.0.1:8003/api/events/${eventId}/agendas`); // Corrected agenda route
    agendas.value = response.data;
    console.log('Fetched Agendas:', agendas.value); // Debugging log
  } catch (error) {
    handleError(error);
  }
};

// Fetch sponsors for a given event
const fetchSponsors = async (eventId) => {
  try {
    const response = await axios.get(`http://127.0.0.1:8003/api/events/${eventId}/sponsors`); // Corrected sponsor route
    sponsors.value = response.data;
    console.log('Fetched Sponsors:', sponsors.value); // Debugging log
  } catch (error) {
    handleError(error);
  }
};

// Watcher for form.event_id changes
watch(
  () => form.event_id,
  async (newEventId, oldEventId) => {
    if (newEventId) {
      console.log(`Event ID changed from ${oldEventId} to ${newEventId}`);
      await Promise.all([fetchAgendas(newEventId), fetchSponsors(newEventId)]);
    } else {
      console.log('Event ID cleared, resetting agendas and sponsors');
      agendas.value = [];
      sponsors.value = [];
    }
  }
);

// Submit event form
const submitForm = async () => {
  try {
    if (isEditing.value) {
      await axios.put(`${apiBaseUrl}/${form.event_id}`, form); // Base URL for events
    } else {
      await axios.post(apiBaseUrl, form); // Base URL for events
    }
    resetForm();
    await fetchEvents();
  } catch (error) {
    handleError(error);
  }
};

// Submit agenda form
const submitAgendaForm = async (eventId) => {
  try {
    await axios.post(`http://127.0.0.1:8003/api/events/${eventId}/agendas`, agendaForm); // Corrected agenda route
    resetAgendaForm();
    await fetchAgendas(eventId);
  } catch (error) {
    handleError(error);
  }
};

// Submit sponsor form
const submitSponsorForm = async (eventId) => {
  try {
    const formData = new FormData();
    formData.append('event_id', eventId);
    formData.append('sponsor_logo', sponsorForm.sponsor_logo);

    await axios.post(`http://127.0.0.1:8003/api/events/${eventId}/sponsors`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }); // Corrected sponsor route
    resetSponsorForm();
    await fetchSponsors(eventId);
  } catch (error) {
    handleError(error);
  }
};

// Edit event
const editEvent = (event) => {
  Object.assign(form, { ...event });
  isEditing.value = true;
  fetchAgendas(event.event_id);
  fetchSponsors(event.event_id);
};

// Delete event
const deleteEvent = async (id) => {
  if (confirm('Are you sure you want to delete this event?')) {
    try {
      await axios.delete(`${apiBaseUrl}/${id}`); // Base URL for events
      await fetchEvents();
    } catch (error) {
      handleError(error);
    }
  }
};

// Reset forms
const resetForm = () => {
  Object.keys(form).forEach((key) => (form[key] = ''));
  form.event_id = null;
  isEditing.value = false;
};

const resetAgendaForm = () => {
  Object.keys(agendaForm).forEach((key) => (agendaForm[key] = ''));
};

const resetSponsorForm = () => {
  Object.keys(sponsorForm).forEach((key) => (sponsorForm[key] = null));
};

// Handle sponsor logo input change
const onSponsorLogoChange = (event) => {
  sponsorForm.sponsor_logo = event.target.files[0];
};

// Fetch initial data on component mount
onMounted(fetchEvents);
</script>



<template>
  <div class="container mx-auto px-4 py-6">
    <!-- Event Management Section -->
    <h2 class="text-2xl font-bold mb-4">Event Management</h2>
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

    <!-- Agenda Management Section -->
    <form @submit.prevent="submitAgendaForm(form.event_id)" class="space-y-6 bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Add Agenda</h2>

      <div>
        <label class="block font-medium text-gray-700">Time Start:</label>
        <input
          v-model="agendaForm.time_start"
          type="time"
          required
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Time End:</label>
        <input
          v-model="agendaForm.time_end"
          type="time"
          required
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Agenda Title:</label>
        <input
          v-model="agendaForm.agenda_title"
          type="text"
          placeholder="Enter agenda title"
          required
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <div>
        <label class="block font-medium text-gray-700">Agenda Description:</label>
        <textarea
          v-model="agendaForm.agenda_description"
          placeholder="Enter agenda description"
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        ></textarea>
      </div>

      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md shadow hover:bg-blue-600"
      >
        Add Agenda
      </button>
    </form>


    <!-- Sponsors Section -->
    <form @submit.prevent="submitSponsorForm(form.event_id)" enctype="multipart/form-data" class="space-y-6 bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Add Sponsor</h2>

      <div>
        <label class="block font-medium text-gray-700">Sponsor Logo:</label>
        <input
          type="file"
          @change="onSponsorLogoChange"
          accept="image/*"
          required
          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3"
        />
      </div>

      <button
        type="submit"
        class="w-full bg-green-500 text-white py-2 px-4 rounded-md shadow hover:bg-green-600"
      >
        Add Sponsor
      </button>
    </form>

    <!-- Events List Section -->
    <div class="mt-10">
      <h2 class="text-xl font-bold mb-4">Events List</h2>
      <div v-if="events.length === 0" class="text-gray-500">No events available</div>

      <table v-else class="min-w-full bg-white border border-gray-300">
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
              <button @click="deleteEvent(event.event_id)" class="bg-red-500 text-white px-4 py-2 rounded-md ml-2">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Agenda List -->
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Agendas List</h2>
      <ul class="space-y-4" v-if="agendas.length > 0">
        <li
          v-for="agenda in agendas"
          :key="agenda.agenda_id"
          class="border p-4 rounded-md shadow-sm"
        >
          <h3 class="font-bold">{{ agenda.agenda_title }}</h3>
          <p>{{ agenda.agenda_description }}</p>
          <p>
            <strong>Time:</strong> {{ agenda.time_start }} - {{ agenda.time_end }}
          </p>
        </li>
      </ul>
      <div v-else>No agendas found.</div>
    </div>

    <!-- Sponsor List -->
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Sponsors List</h2>
      <ul class="space-y-4" v-if="sponsors.length > 0">
        <li
          v-for="sponsor in sponsors"
          :key="sponsor.sponsor_id"
          class="flex items-center space-x-4 border p-4 rounded-md shadow-sm"
        >
          <img :src="sponsor.sponsor_logo" alt="Sponsor Logo" class="w-12 h-12 rounded-md" />
          <p><strong>Sponsor ID:</strong> {{ sponsor.sponsor_id }}</p>
        </li>
      </ul>
      <div v-else>No sponsors found.</div>
    </div>

  </div>
</template>

<style scoped>
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";
</style>
