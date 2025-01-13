import axios from 'axios';

// Base API client
const apiClient = axios.create({
    baseURL: 'http://127.0.0.1:8003/api', // Correct Laravel API URL
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

// Function to fetch event data by ID
export const getEventById = (id) => apiClient.get(`/event/${id}`);
export const getAgendasByEvent = (eventId) => apiClient.get(`/events/${eventId}/agendas`);

// Function to fetch ticket types
export const getTicketTypes = () => apiClient.get('/ticket_types');

export default apiClient;
