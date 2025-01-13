import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('../views/AboutView.vue'),
      meta: { showHeader: true,
              showFooter: false,
              requiresAuth: false,
              home: true
            },
    },
    {
      path: '/ticket',
      name: 'ticket',
      component: () => import('../views/TicketView.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {

      path: '/merchandise',
      name: 'merchandise',
      component: () => import('../views/MerchandisePage.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {

      path: '/merchandise-detail',
      name: 'merchandise-detail',
      component: () => import('../views/MerchandiseDetail1.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {

      path: '/merchandise-detail2',
      name: 'merchandise-detail2',
      component: () => import('../views/MerchandiseDetail2.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {

      path: '/merchandise-detail3',
      name: 'merchandise-detail3',
      component: () => import('../views/MerchandiseDetail3.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {
      path: '/feedback',
      name: 'feedback',
      component: () => import('../views/FeedbackView.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {
      path: '/cart',
      name: 'cart',
      component: () => import('../views/Cart.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {
      path: '/refund',
      name: 'refund',
      component: () => import('../views/RefundView.vue'),
      meta: { showHeader: true,
              showFooter: true,
              requiresAuth: false,
              home: false
            },
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue'),
      meta: { showHeader: false,
              showFooter: false,
              requiresAuth: false,
              home: false
            },
    },
    {
      path: '/test',
      name: 'test',
      component: () => import('../views/test.vue'),
      meta: { showHeader: false,
              showFooter: false,
              requiresAuth: false,
              home: false
            },
    },
    {
      path: '/admin',
      name: 'admin',
      component: () => import('../views/AdminView.vue'),
      meta: { showHeader: false, showFooter: false, requiresAuth: true, home: false },
      children: [
        { path: '/dashboard', name: 'admin-dashboard', component: () => import('../views/Firstpage.vue') },
        { path: '/feedback-admin', name: 'feedback-admin', component: () => import('../views/Feedback.vue') },
        { path: '/event', name: 'event', component: () => import('../views/Event.vue') },
        { path: '/billing', name: 'billing', component: () => import('../views/Billing.vue') },
        { path: '/refunding', name: 'refunding', component: () => import('../views/Refund.vue') },
      ],
    }      
  ],
})

router.beforeEach((to, from, next) => {
  const token = sessionStorage.getItem('token');
  console.log('Token:', token); // Debugging

  // Check if the user is already logged in and trying to access the login page
  if (to.path === '/login' && token) {
    console.log('User already logged in, redirecting to /dashboard'); // Debugging
    next('/dashboard'); // Redirect to the dashboard if logged in
    return;
  }

  console.log('Token:', token, '| to.meta.requiresAuth:', to.meta.requiresAuth); // Debug both values

  if (to.meta.requiresAuth && !token) {
    console.log('Not authenticated, redirecting to /login'); // Debugging
    next('/login'); // Redirect to login if the user is not authenticated
  } else {
    console.log('Authenticated, proceeding to', to.path); // Debugging
    next(); // Proceed to the route
  }
});


export default router
