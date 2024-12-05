<script setup lang="ts">
  import CountdownTimer from "@/components/countdownTimer.vue"; // Include if using the timer component
  import { Icon } from "@iconify/vue";
</script>

<template>
  <div class="body">
    <div class="hero">
      <!-- Left Side: Title and Buttons -->
      <div class="hero-content left">
        <p class="date">01 JUNE, 2024</p>
        <h1 class="title">Malam Citra Bayu 16th</h1>
        <p class="tagline">Megar Karya, Remangi Persona Bayu</p>
        <div class="buttons">
          <button class="btn-primary">BOOK NOW</button>
          <a href="#" class="teaser-link">🎥 Watch The Teaser</a>
        </div>
      </div>

      <!-- Right Side: Countdown -->
      <div class="hero-content right">
        <section class="countdown">
          <h2 class="countdown-title">COUNTDOWN 🕒</h2>
          <div class="timer-container">
            <div class="timer">
              <CountdownTimer />
            </div>
          </div>
          <button class="venue">Dewan Tuanku Syed Putra, USM</button>
        </section>
      </div>
    </div>
    <!-- Event Details Section -->
    <div class="event-details">
      <!-- About Section -->
      <section id="about" class="details-section about">
        <div class="content-wrapper">
    <!-- Text Content -->
    <div class="text-content">
      <button class="venue">ABOUT</button>
      <p>
        Malam Citra Bayu (MCB) is one of the largest annual cultural events at Universiti Sains Malaysia
        (USM) organized by the Sabah Student Organization (PERSIS USM). MCB is a national-level creative
        traditional dance competition conceptualized to appreciate the arts and culture of the community
        in Sabah. For this year's event, the 16th edition of Malam Citra Bayu returns with a new theme
        and fresh perspective, inviting 9 public universities in Malaysia to compete on the stage of
        Dewan Tuanku Syed Putra with their own choreography.
      </p>
    </div>
    <!-- Image Content -->
    <div class="image-content">
      <div class="image-wrapper">
        <div class="gradient-overlay">
          <img src="@/assets/images/usmdancers.png" alt="USM Dancers" />
        </div>
      </div>
    </div>
  </div>
</section>

      <!-- History Section -->
      <section id="history">
        <div class="history-container">
    <div>
      <Icon icon="mingcute:certificate-fill" width="55" height="55"  style="color: #ec9910" />
      <h2>Established Event</h2>
      <p>Now in its 16th edition since 2008, continue its legacy as premier cultural event</p>
    </div>
    <div>
      <Icon icon="arcticons:pass-culture" width="55" height="55"  style="color: #ec9910" />
      <h2>Iconic culture</h2>
      <p>Present apa" yang ada hihaa</p>
    </div>
    <div>
      <Icon icon="mdi:dance-ballroom" width="55" height="55"  style="color: #ec9910" />
      <h2>Multiple Performers</h2>
      <p>Banyak penari-penari mantap</p>
    </div>
    <div>
      <Icon icon="fluent:people-team-add-24-filled" width="55" height="55"  style="color: #ec9910" />
      <h2>Engaging Vibe</h2>
      <p>Dengar lagu Naratu tu pasti layan</p>
    </div>
  </div>
</section>


      <!-- Agenda Section -->
      <section id="agenda">
        <h2>Agenda</h2>
        <h2>01 June, Saturday</h2>
        <div class="agendaWrapper">
          <div class="agenda-detail">
            <h3>07:30 PM - 07:45 PM</h3>
            <h3>1</h3>
            <h3>Registration and Wristband</h3>
            <p>Register your personal data in customer service and take wristband before entering the room</p>
          </div>

          <div class="agenda-detail">
            <h3>07:45 PM - 08:00 PM</h3>
            <h3>2</h3>
            <h3>Welcoming Speech</h3>
            <p>Hearing speech from our deputy vice chancellor. An opening speech to open the ceremony and expose the student about the whole picture of MCB event in USM.</p>
          </div>

          <div class="agenda-detail">
            <h3>08:00 PM - 10:30 PM</h3>
            <h3>3</h3>
            <h3>Night Citra Performances</h3>
            <p>Stay for entertaining performance from our invited celebrities from Sabah and also students that perform their traditional dance which originated from Sabah. </p>
          </div>

          <div class="agenda-detail">
            <h3>10:30 PM - 11:00 PM</h3>
            <h3>4</h3>
            <h3>Award Giving Ceremony</h3>
            <p>Giving the award based on the category that has finalised and annoucing the winner of MCB 16th edition.</p>
          </div>

          <div class="agenda-detail">
            <h3>11:00 PM - 11:30 PM</h3>
            <h3>5</h3>
            <h3>Closing Ceremony & Event Dismissal</h3>
            <p>Students and Guest may dismiss. Photography session with our guest may be available subjected to celebrities’ approval.</p>
          </div>
        </div>
      </section>

      <!-- Performers Section -->
      <section id="performers">
        <h2>Performers</h2>
        <p>
          Featuring renowned artists and talented performers from across the
          country.
        </p>
      </section>

      <!-- Ticket and Merchandise Section -->
      <section id="tickets-merchandise">
        <h2>Ticket & Merchandise</h2>
        <p>
          Get your tickets and explore exclusive merchandise for the event!
          <a href="#">Buy Tickets</a> | <a href="#">Shop Merchandise</a>
        </p>
      </section>

      <!-- Past Events Section -->
      <section id="past-events">
        <h2>Past Event Highlights</h2>
        <p>Check out photos and feedback from our previous events!</p>
        <img src="https://via.placeholder.com/600x300" alt="Past Event Photos" />
      </section>

      <!-- Sponsors Section -->
      <section id="sponsors">
        <h2>Sponsors & Special Thanks</h2>
        <p>
          A big thank you to our sponsors for making this event possible.
        </p>
        <ul>
          <li>Sponsor 1</li>
          <li>Sponsor 2</li>
          <li>Sponsor 3</li>
        </ul>
      </section>

      <!-- Google Maps Section -->
      <section id="location">
        <h2>Location</h2>
        <p>Dewan Tuanku Syed Putra, Universiti Sains Malaysia, Penang.</p>
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18..."
          width="600"
          height="450"
          style="border:0;"
          loading="lazy"
        ></iframe>
      </section>
    </div>
  </div>
</template>

<style scoped>
html,
body {
  margin: 0;
  padding: 0;
  width: 100%;
  height: 100%;
}

.title {
  font-family: 'EB Garamond', serif; /* Use EB Garamond */
  font-size: 4.5rem; /* Adjust the font size for emphasis */
  font-weight: 400; /* Use a bold weight for better visibility */
  color: #554149; /* White text for contrast */
  text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Optional: Add a subtle shadow for effect */
  margin: -0.3rem 0; /* Add some spacing around the title */
}

.body {
  position: relative;
  background-color: #ffc8dd; /* Full opacity background color */
  height: 100vh;
  width: 100%;
}

.body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-image: url('@/assets/backgroundMain.png');
  background:cover;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  opacity: 0.05; /* 5% opacity for the background image */
  z-index: 1;
}

.date {
  font-size: 1.3rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

h1 {
  font-size: 4rem;
  margin: 0.5rem 0;
}

.tagline {
  font-family: 'EB Garamond', serif;
  font-size: 1.3rem;
  font-weight: bold;
  margin: 1rem 0;
  color: #554149;
}

.hero {
  position: relative;
  display: flex; /* Enable flexbox layout */
  justify-content: space-between; /* Space items apart */
  align-items: center; /* Align items vertically centered */
  height: 100%;
  width: 100%;
  clip-path: polygon(0 0, 100% 0, 100% 100%, 0 85%);
  background-color: rgba(255, 240, 240, 0.3);
  z-index: 2; /* Content above the background image */
  color: #554149;
  text-align: left;
  padding: 0 2rem;
}

.hero-content {
  flex: 1; /* Each content block takes equal space */
  max-width: 50%; /* Limit content width */
  padding: 11.5rem;
  padding-bottom:15%;
}

.hero-content.left {
  text-align: left; /* Align text to the left for the title */
}

.hero-content.right {
  flex: 2; /* Allocate more space to the right side */
  text-align: center; /* Center align the countdown content */
  padding: 4rem; /* Add padding for better spacing */
}

.buttons {
  margin-top: 1.5rem;
}

.btn-primary {
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  color: #000; /* Text color */
  font-size: 1rem;
  font-weight: bold;
  border: none;
  border-radius: 0px;
  padding: 0.85rem 1.5rem;
  margin-right: 1rem;
  cursor: pointer;
  transition: background 0.3s ease;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.btn-primary:hover {
  background: linear-gradient(108deg, #dcc39c -50%, #a48e69 100%);
  transform: scale(1.05);
}

.teaser-link {
  color: #554149;
  font-size: 1rem;
  text-decoration: none;
  transition: color 0.3s ease;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-weight: bold;
}

.teaser-link:hover {
  color: #433303;
}

/* Countdown Section */
.countdown {
  text-align: center;
  padding: 1rem;
  color: #333;
}

/* Title Styling */
.countdown-title {
  font-size: 2rem;
  margin-bottom: 1rem;
  letter-spacing: 0.6rem; /* Add spacing between characters */
  font-weight: bold;
  text-transform: uppercase; /* Optional: Make all text uppercase */
  font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Venue Styling */
.venue {
  margin-top: 1rem;
  font-size: 1.2rem;
  font-style: bold;
  background: linear-gradient(108deg, #a48e69 -50%, #dcc39c 100%);
  padding: 0.6rem 1rem;
  border: none;
  border-radius: 20px; /* Rounded corners */
  display: inline-block; /* Ensure proper spacing */
  font-weight: bold; /* Optional: Highlight venue text */
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.venue:hover {
  background: linear-gradient(108deg, #dcc39c -50%, #a48e69 100%);
}

/* General Styling for Event Details */
.details-section {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  padding: 3rem;
  color: #554149;
  text-align: justify;
  background-color: rgba(255, 200, 221, 0.2); /* Light pink overlay */
  border-radius: 10px;
  overflow: hidden; /* Prevent content from spilling out */
  margin: 2rem 0;
}

.details-section.about .content-wrapper {
  display: flex;
  flex-direction: row;
  align-items: center;
  gap: 2rem;
}

.text-content {
  flex: 1; /* Take equal space */
}

.image-content {
  flex: 1; /* Image takes up equal space */
  display: flex;
  justify-content: flex-end; /* Push the image to the right */
  align-items: center; /* Align content vertically */
  position: relative; /* For gradient positioning */
  overflow: hidden; /* Hide overflow outside the container */
  margin-right: -4rem;
}

.image-wrapper {
  position: relative;
  width: 100%; /* Full width of the right-side container */
  height: 100%; /* Full height of the right-side container */
}

.image-wrapper img {
  width: 100%;
  height: auto;
  object-fit: cover; /* Ensure the image fits nicely */
}

.gradient-overlay {
  position: relative;
  width: 100%;
  height: 100%;
  opacity: 0.7;
  mask-image: linear-gradient(to left, rgba(0, 0, 0, 1.0) 15%, transparent 100%);
}

.details-section h2 {
  font-size: 2rem;
  margin-bottom: 1rem;
  font-weight: bold;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.details-section p {
  font-size: 1rem;
  font-family: 'Plus Jakarta Sans', sans-serif;
  line-height: 1.6;
}



/* Responsive Styling */
@media (max-width: 768px) {
  .details-section.about .content-wrapper {
    flex-direction: column; /* Stack content vertically on smaller screens */
    text-align: center;
  }

  .image-content {
    justify-content: center; /* Add spacing between text and image */
  }
  .gradient-overlay {
    mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 1.0) 50%, transparent 100%);
  }
}

/* Styling for History */
#history {
  padding: 50px 20px;
  background-color: #ffc8dd;
  display: flex;
  justify-content: center;
}

/* History Container using Grid */
.history-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Responsive columns */
  gap: 30px; /* Space between grid items */
  max-width: 1200px; /* Optional: max width for the container */
  width: 100%;
  padding: 20px;
}

/* Individual div styling with Flexbox */
.history-container > div {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start; /* Align content to the left */
}

/* Headings styling */
.history-container h2 {
  font-size: 1.5rem;
  font-weight: bold;
  margin-bottom: 10px;
  color: #333;
}

/* Paragraphs styling */
.history-container p {
  font-size: 1rem;
  color: #555;
  line-height: 1.6;
}

/* Optional: Responsive typography */
@media (max-width: 768px) {
  .history-container h2 {
    font-size: 1.25rem;
  }

  .history-container p {
    font-size: 0.95rem;
  }
}

/* Agenda styling */
/* General Section Styling */
#agenda {
  background-color: #f8e8f0; /* Light pink background */
  padding: 50px;
  text-align: center;
  font-family: 'Arial', sans-serif;
}

#agenda h2:first-of-type {
  font-size: 36px;
  font-weight: 700;
  color: #333;
  margin-bottom: 10px;
}

#agenda h2:nth-of-type(2) {
  font-size: 20px;
  font-weight: 600;
  color: #666;
  margin-bottom: 40px;
}

/* Agenda Wrapper */
.agendaWrapper {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* Individual Agenda Details */
.agenda-detail {
  background-color: white;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  text-align: left;
  position: relative;
}

/* Styling for Time */
.agenda-detail h3:first-of-type {
  font-size: 16px;
  color: #555;
  margin: 0 0 10px 0;
}

/* Styling for Step Number */
.agenda-detail h3:nth-of-type(2) {
  font-size: 36px;
  font-weight: 700;
  color: #c49b61; /* Gold for step numbers */
  position: absolute;
  top: 20px;
  right: 20px;
}

/* Styling for Title */
.agenda-detail h3:nth-of-type(3) {
  font-size: 18px;
  font-weight: 600;
  color: #333;
  margin: 0 0 10px 0;
}

/* Styling for Description */
.agenda-detail p {
  font-size: 14px;
  line-height: 1.6;
  color: #666;
}

/* Button Style */
#agenda button {
  background-color: #c49b61; /* Gold Button */
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  border-radius: 5px;
  cursor: pointer;
  margin-top: 20px;
}

#agenda button:hover {
  background-color: #b38d55;
}

/* Optional Media Query for Mobile */
@media (max-width: 768px) {
  .agendaWrapper {
    flex-direction: column;
  }
}


</style>
