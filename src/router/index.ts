import { createRouter, createWebHistory } from 'vue-router'
import Billing from "../views/Billing.vue";
import Dashboard from "../views/Dashboard.vue";
import Profile from "../views/Profile.vue";
import Signin from "../views/Signin.vue";
import Event from  "../views/Event.vue";
import Feedback from "../views/Feedback.vue";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/AboutView.vue'),
      meta: { showHeader: true,
              showFooter: false
            },
    },
    {
      path: '/ticket',
      name: 'ticket',
      component: () => import('../views/TicketView.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {

      path: '/merchandise',
      name: 'merchandise',
      component: () => import('../views/MerchandisePage.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {

      path: '/merchandise-detail',
      name: 'merchandise-detail',
      component: () => import('../views/MerchandiseDetail1.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {

      path: '/merchandise-detail2',
      name: 'merchandise-detail2',
      component: () => import('../views/MerchandiseDetail2.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {

      path: '/merchandise-detail3',
      name: 'merchandise-detail3',
      component: () => import('../views/MerchandiseDetail3.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {
      path: '/feedback',
      name: 'feedback',
      component: () => import('../views/FeedbackView.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('../views/Cart.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {
      path: '/refund',
      name: 'refund',
      component: () => import('../views/RefundView.vue'),
      meta: { showHeader: true,
              showFooter: true
            },
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { showHeader: false,
              showFooter: false
            },
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminView.vue'),
      meta: { showHeader: false,
              showFooter: false,
              requiresAuth: true },
      children: [
        { path: '/dashboard', component: () => import('../views/DashboardView.vue') },
        { path: '/form', component: () => import('../views/FormView.vue') },
        { path: '/table', component: () => import('../views/TableView.vue') },
        
      ]
    },
    {
      path: "/dashboard-default",
      name: "Dashboard",
      component: () => import('../views/Dashboard.vue'),
      meta: { showHeader: false,
              showFooter: false,
              requiresAuth: true
            },
    },
    {
      path: "/admin-feedback",
      name: "Feedback",
      component: Feedback,
    },
    {
      path: "/billing",
      name: "Billing",
      component: Billing,
    },
    {
      path: "/event",
      name: "Event",
      component: Event,
    },
  
    {
      path: "/profile",
      name: "Profile",
      component: Profile,
    },
    {
      path: "/signin",
      name: "Signin",
      component: Signin,
    },
  
    
  ],
})

router.beforeEach((to, from, next) => {
  const isAuthenticated = sessionStorage.getItem('isAuthenticated') === 'true';

  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/login'); // Redirect to login if not authenticated
  } else {
    next(); // Proceed to route
  }
});

export default router
