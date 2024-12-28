<template>

    <div class="card-container">
    <div style="max-width:30%;height: 280px;" class="mb-3 card">
      <div style="border: 2px solid #aa9758;border-radius: 20px;" class="p-3 card-body">
  
        <img class="album" src="@/assets/images/event/eventpic1.jpg">
        <button class="danger">Delete</button>
        <button class="primary">Update</button>
      </div>
      </div>
      <div style="max-width:30%;height: 280px;" class="mb-3 card">
      <div style="border: 2px solid #aa9758;border-radius: 20px;" class="p-3 card-body">
  
        <img class="album" src="@/assets/images/event/eventpic2.jpg">
        <button class="danger">Delete</button>
        <button class="primary">Update</button>
      </div>
      </div>
      <div style="max-width:30%;height: 280px;" class="mb-3 card">
      <div style="border: 2px solid #aa9758;border-radius: 20px;" class="p-3 card-body">
  
        <img class="album" src="@/assets/images/event/eventpic3.jpg">
        <button class="danger">Delete</button>
        <button class="primary">Update</button>
      </div>
      </div>
      <div style="max-width:30%;height: 280px;" class="mb-3 card">
      <div style="border: 2px solid #aa9758;border-radius: 20px;" class="p-3 card-body">
  
        <img class="album" src="@/assets/images/event/eventpic4.jpg">
        <button class="danger">Delete</button>
        <button class="primary">Update</button>
      </div>
      </div>
      <div style="max-width:30%;height: 280px;" class="mb-3 card">
      <div style="border: 2px solid #aa9758;border-radius: 20px;" class="p-3 card-body">
  
        <img class="album" src="@/assets/images/event/eventpic5.jpg">
        <button class="danger">Delete</button>
        <button class="primary">Update</button>
      </div>
      </div>
    </div>
      
  </template>
  
  <style>
  
  
  .roundedbut{
  
  background-image: linear-gradient(to right, rgba(164, 142, 105, 1), rgba(220, 195, 156, 1));
                   
                   color: white;
                   padding: 10px 20px;
                   
                   border-radius: 20px;
                   font-size: 16px;
                   cursor: pointer;
                   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                   transition: all 0.2s ease-in-out;
                   margin-top: 20px;
                   margin-left: 25px;
                   border: 2px solid #aa9758;
  
  
  }
  
  .danger{
  
  background-color: red;
                   
                   color: white;
                   padding: 10px 20px;
                   border: 2px solid #aa9758;
                   border-radius: 20px;
                   font-size: 16px;
                   cursor: pointer;
                   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                   transition: all 0.2s ease-in-out;
                   margin-top: 20px;
                   margin-left: 25px;
  
  
  
  }
  
  .primary{
  
  background-color: blue;
                   
                   color: white;
                   padding: 10px 20px;
                   border: 2px solid #aa9758;
                   border-radius: 20px;
                   font-size: 16px;
                   cursor: pointer;
                   box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                   transition: all 0.2s ease-in-out;
                   margin-top: 20px;
                   margin-left: 25px;
  
  
  
  }
  
  .star5{
    max-width: 100px; 
    
  }
  
  .album{
    border-radius: 20px;
    max-width: 100%;
    
  }
  
  .card-container {
      display: flex;
      justify-content: space-around; /* Adjusts space between cards */
      align-items: flex-start; /* Aligns cards to the top */
      gap: 20px; /* Adds consistent spacing between cards */
      padding: 20px;
      overflow-x: auto; /* Allows horizontal scrolling if cards overflow */
  }
  
  
  </style>
  