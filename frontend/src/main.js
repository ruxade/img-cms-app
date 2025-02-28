import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios';

const app = createApp(App)

app.use(createPinia())
app.use(router)

 app.config.devtools = false;

//  Axios
 axios.defaults.baseURL = 'http://localhost:8000/api';
 axios.defaults.headers.common['Accept'] = 'application/json';

// token
 const token = localStorage.getItem('access_token')
 if (token) {
   axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }

// global properties
  app.config.globalProperties.$axios = axios;
  app.config.globalProperties.$imageUrl = (path) => `http://localhost:8000/storage/${path}`;


 app.mount('#app')
