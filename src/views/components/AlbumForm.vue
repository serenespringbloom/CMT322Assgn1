
<template>
  <div class="card pb-4">
    <div class="card-header pb-0 p-3">
      <div class="row">
        <div class="col-6 d-flex align-items-center">
          <h6 class="mb-0">Event Creation</h6>
        </div>
       
      </div>
    </div>
    <div class="card-body p-3 pb-0 mb-0">
      <ul class="list-group">
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex flex-column">
            <h6 class="mb-1 text-dark font-weight-bold text-sm">
              Photo
            </h6>
            <span class="text-xs">Upload Event Photo</span>
          </div>
          <div class="d-flex align-items-center text-sm">
            
            <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4">
              <i class="fas fa-file-pdf text-lg me-1" aria-hidden="true"></i>
              PDF
            </button>
          </div>
        </li>
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
        
          <div class="d-flex align-items-center text-sm">
            
            <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4">
              <i class="fas fa-file-pdf text-lg me-1" aria-hidden="true"></i>
              PDF
            </button>
          </div>
        </li>
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex flex-column">
            <h6 class="text-dark mb-1 font-weight-bold text-sm">
              Event Details
            </h6>
            <span class="text-xs">Write event details</span>
          </div>
          <div class="d-flex align-items-center text-sm">
            
            <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4">
              <i class="fas fa-file-pdf text-lg me-1" aria-hidden="true"></i>
              PDF
            </button>
          </div>
        </li>
        <li
          class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg"
        >
          <div class="d-flex flex-column">
            <h6 class="text-dark mb-1 font-weight-bold text-sm">
              Tags
            </h6>
            <span class="text-xs">Input Relevan tags</span>
          </div>
          <div class="d-flex align-items-center text-sm">
          
            <button class="btn btn-link text-dark text-sm mb-0 px-0 ms-4">
              <i class="fas fa-file-pdf text-lg me-1" aria-hidden="true"></i>
              PDF
            </button>
          </div>
        </li>
        
       
      </ul>
      <button style="margin-left:10px;" class="roundedbut">POST</button>
    </div>
  </div>

  <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <p id="modalText">Hello!</p>
        </div>
    </div>
</template>
<style>

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

.form-container {
  padding: 20px;
  border: 2px solid #4caf50;
  border-radius: 8px;
  background-color: #ffffff;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  text-align: center;
}

label {
  display: block;
  margin-bottom: 10px;
  font-size: 18px;
}

input[type="text"] {
  width: 100%;
  padding: 8px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
}

button {
  background-color: #4caf50;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 16px;
}

button:hover {
  background-color: #45a049;
}

.modal {
  display: none; /* Hidden by default */
  position: fixed;
  z-index: 1;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.4);
  justify-content: center;
  align-items: center;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.close-button {
  color: red;
  float: right;
  font-size: 24px;
  cursor: pointer;
}

.close-button:hover {
  color: darkred;
}


</style>

<script setup >

</script>