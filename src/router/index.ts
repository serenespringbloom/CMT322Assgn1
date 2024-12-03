import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/AboutView.vue'),
      meta: { showHeader: true },
    },
    {
      path: '/ticket',
      name: 'ticket',
      component: () => import('../views/TicketView.vue'),
      meta: { showHeader: true },
    },
    {

      path: '/merchandise',
      name: 'merchandise',
      component: () => import('../views/MerchandisePage.vue'),
      meta: { showHeader: true },
    },
    {
      path: '/feedback',
      name: 'feedback',
      component: () => import('../views/FeedbackView.vue'),
      meta: { showHeader: true },
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminView.vue'),
      meta: { showHeader: false },
      children: [
        { path: '/dashboard', component: () => import('../views/DashboardView.vue') },
        { path: '/form', component: () => import('../views/FormView.vue') },
        { path: '/table', component: () => import('../views/TableView.vue') },
      ]
    } 
  ],
})

export default router
