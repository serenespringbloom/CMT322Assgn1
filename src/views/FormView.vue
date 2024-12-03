<template>
    <div>
      <h1>Event Form</h1>
      <form class="event-form">
        <label>
          Event Name:
          <input type="text" placeholder="Enter event name" />
        </label>
  
        <label>
          Event Region:
          <select>
            <option>Select a region</option>
            <option>Region 1</option>
            <option>Region 2</option>
          </select>
        </label>
  
        <label>
          Event Date:
          <input type="date" />
        </label>
  
        <label>
          Immediate Delivery:
          <input type="checkbox" />
        </label>
  
        <div class="actions">
          <button type="submit">Create</button>
          <button type="reset">Cancel</button>
        </div>
      </form>
    </div>
  </template>
  
  <style scoped>
  .event-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  
  label {
    display: flex;
    flex-direction: column;
    font-size: 1rem;
  }
  
  .actions {
    display: flex;
    gap: 1rem;
  }
  </style>