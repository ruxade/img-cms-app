import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import ImageUpload from '../views/ImageUpload.vue'
import ImageList from '../views/ImageList.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'Home', component: HomeView },
    { path: '/about', name: 'about',  // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },

    { path: '/login', name: 'Login', component: Login },
    { path: '/register', name: 'Register', component: Register },
    { path: '/upload', name: 'Upload', component: ImageUpload },
    { path: '/images', name: 'Images', component: ImageList },
  ],
})

export default router
