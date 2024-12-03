<template>
  <div class="about">
    <h1>This is an about page</h1>
  </div>
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}

.title {
  font-family: 'EB Garamond', serif; /* Use EB Garamond */
  font-size: 5rem; /* Adjust the font size for emphasis */
  font-weight: 400; /* Use a bold weight for better visibility */
  color: #554149; /* White text for contrast */
  text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); /* Optional: Add a subtle shadow for effect */
  margin: 0.5rem 0; /* Add some spacing around the title */
}

.body {
  position: relative;
  background-color: #ffc8dd; /* Full opacity background color */
  height: 100vh;
  width: 100%;
  overflow: hidden;
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

.hero {
  position: relative;
  display: flex; /* Enable flexbox layout */
  justify-content: space-between; /* Space items apart */
  align-items: center; /* Align items vertically centered */
  height: 100%;
  width: 100%;
  z-index: 2; /* Content above the background image */
  color: #554149;
  text-align: left;
  padding: 0 5rem;
}

.hero-content {
  flex: 1; /* Each content block takes equal space */
  max-width: 50%; /* Limit content width */
  padding: 1rem;
}

.hero-content.left {
  text-align: left; /* Align text to the left for the title */
}

.hero-content.right {
  text-align: center; /* Center align the countdown content */
}

.date {
  font-size: 1.2rem;
  font-weight: bold;
  margin-bottom: 0.5rem;
}

h1 {
  font-size: 3rem;
  margin: 0.5rem 0;
}

.tagline {
  font-family: 'EB Garamond', serif;
  font-size: 1.5rem;
  margin: 1rem 0;
  color: #554149;
}

.buttons {
  margin-top: 1.5rem;
}

.btn-primary {
  background-color: #ffcc33;
  color: #000;
  font-size: 1rem;
  font-weight: bold;
  border: none;
  border-radius: 0px;
  padding: 0.75rem 1.5rem;
  margin-right: 1rem;
  cursor: pointer;
  transition: background 0.3s ease;
}

.btn-primary:hover {
  background-color: #ffd966;
}

.teaser-link {
  color: #554149;
  font-size: 1rem;
  text-decoration: none;
  transition: color 0.3s ease;
}

.teaser-link:hover {
  color: #ffcc33;
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
}

/* Timer Styling with Background */
.timer-container {
  background: #f4d0d6; /* Background color for the timer */
  padding: 1.5rem;
  border-radius: 10px; /* Optional: Rounded corners */
  display: inline-block; /* Fit the content width */
}

.timer {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  font-size: 1.2rem;
  font-weight: bold;
}

/* Venue Styling */
.venue {
  margin-top: 1rem;
  font-size: 1rem;
  font-style: bold;
  background: #ffcc33; /* Gold background */
  color: #333; /* Text color */
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 20px; /* Rounded corners */
  display: inline-block; /* Ensure proper spacing */
  font-weight: bold; /* Optional: Highlight venue text */
}

.venue:hover {
  background-color: #ffd966;
}
</style> 
