<template>
    <div>
      <h1>Admin Information</h1>
      <p><strong>Name:</strong> admin</p>
      <p><strong>Role:</strong> admin</p>
      <ul>
        <li v-for="item in items" :key="item.id">{{ item.name }}</li>
      </ul>
    </div>
    <div>
        <h1>Test Data</h1>
        <ul>
            <li v-for="item in testData" :key="item.id">
                {{ item.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import apiClient from '../api';
import axios from 'axios';

export default {
  data() {
    return {
      items: [], // To hold the data fetched from the API
      testData: [],
    };
  },
  mounted() {
    // Fetch data when the component is mounted
    apiClient.get('/items')
      .then(response => {
        this.items = response.data; // Assign API response to `items`
      })
      .catch(error => {
        console.error('Error fetching items:', error);
      });
    axios.get('http://127.0.0.1:8000/api/test')
      .then(response => {
        this.testData = response.data; // Assign the response data
      })
      .catch(error => {
        console.error('Error fetching test data:', error);
      });
  },
};
</script>