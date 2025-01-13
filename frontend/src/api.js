import axios from 'axios';
import router from './router/index.js';

const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// Add a request interceptor to include the token
apiClient.interceptors.request.use((config) => {
    const token = sessionStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`; // Include the token
    }
    return config;
});

  // Add a response interceptor to handle errors globally
  let isRefreshing = false;
  let failedRequests = [];
  
  apiClient.interceptors.response.use(
    (response) => response,
    async (error) => {
      const { response } = error;
  
      if (response && response.status === 401 && !error.config._retry) {
        error.config._retry = true;
  
        if (!isRefreshing) {
          isRefreshing = true;
          try {
            const refreshResponse = await apiClient.post('/auth/refresh-token');
            const newToken = refreshResponse.data.token;
            sessionStorage.setItem('token', newToken);
            apiClient.defaults.headers.Authorization = `Bearer ${newToken}`;
            failedRequests.forEach((req) => req.resolve(newToken));
            failedRequests = [];
            isRefreshing = false;
          } catch (err) {
            sessionStorage.removeItem('token');
            router.push('/login');
          }
        }
  
        return new Promise((resolve, reject) => {
          failedRequests.push({
            resolve: (token) => {
              error.config.headers.Authorization = `Bearer ${token}`;
              resolve(apiClient(error.config));
            },
            reject: (err) => reject(err),
          });
        });
      }
  
      return Promise.reject(error);
    }
  );
  
  
export default apiClient;