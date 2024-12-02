import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/ticket',
      name: 'ticket',
      component: () => import('../views/TicketView.vue'),
    },
    {
      path: '/feedback',
      name: 'feedback',
      component: () => import('../views/FeedbackView.vue'),
    },
    {
      path: '/merchandise',
      name: 'merchandise',
      component: () => import('../views/MerchandiseView.vue'),
    }
  ],
})

export default router
