import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import VueCountdown from "@chenfengyuan/vue-countdown";
import Toast from "vue-toastification";


const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(Toast)

app.component("VueCountdown", VueCountdown);

app.mount('#app')