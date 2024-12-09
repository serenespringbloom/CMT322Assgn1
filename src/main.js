import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import "./assets/css/nucleo-icons.css";
import "./assets/css/nucleo-svg.css";
import ArgonDashboard from "./argon-dashboard";

const appInstance = createApp(App);
appInstance.use(store);
appInstance.use(ArgonDashboard);
createApp(App).mount('#app')
