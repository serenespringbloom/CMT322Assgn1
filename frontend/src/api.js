import axios from 'axios';

const apiClient = axios.create({
    baseURL: 'http://127.0.0.1:8000/api', // Laravel API URL
    
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

export default apiClient;