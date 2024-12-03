<template>
    <div>
      <h1>Data Table</h1>
      <table class="data-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Pageviews</th>
            <th>Display Time</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in tableData" :key="row.id">
            <td>{{ row.id }}</td>
            <td>{{ row.title }}</td>
            <td>{{ row.author }}</td>
            <td>{{ row.pageviews }}</td>
            <td>{{ row.displayTime }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script setup>
  const tableData = [
    { id: 1, title: 'Sample Title 1', author: 'Author 1', pageviews: 1200, displayTime: '2024-12-03 14:00:00' },
    { id: 2, title: 'Sample Title 2', author: 'Author 2', pageviews: 900, displayTime: '2024-12-03 15:00:00' },
  ];
  </script>
  
  <style scoped>
  .data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
  }
  
  .data-table th, .data-table td {
    border: 1px solid #ddd;
    padding: 0.5rem;
    text-align: left;
  }
  
  .data-table th {
    background-color: #f4f4f4;
    font-weight: bold;
  }
  </style>