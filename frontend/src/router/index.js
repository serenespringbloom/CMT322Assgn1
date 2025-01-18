import { createRouter, createWebHistory } from 'vue-router'
import apiClient from '../api';

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
      path: '/ticket-refund',
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
        { path: '/admin/dashboard', component: () => import('../views/AdminSummaryDashboard.vue') },
        { path: '/admin/feedback', component: () => import('../views/AdminFeedbackDashboard.vue') },
        { path: '/admin/event', component: () => import('../views/Event.vue') },
        {path: '/admin/tickets',component: () => import('../views/AdminTicketDashboard.vue')},
        {path: '/admin/merchandise',  component: () => import('../views/AdminMerchandiseOrders.vue')  },
      ]
    },
 

    {path: '/admin/merchandise',  component: () => import('../views/AdminMerchandiseOrders.vue')  },
    {
      path: '/merchandise-refund',
      name: 'UserMerchandiseRefund',
      component: () => import('../views/MerchandiseRefund.vue')
    },
    {
      path: '/order-confirmation/:id',
      name: 'OrderConfirmation',
      component: () => import('../views/MerchandiseOrderConfirmation.vue')
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const token = sessionStorage.getItem('token');

  if (to.path === '/login' && token) {
    next('/dashboard');
    return;
  }

  if (to.meta.requiresAuth) {
    if (!token) {
      next('/login');
      return;
    }

    try {
      const response = await apiClient.post('/check-token', {}, { headers: { Authorization: `Bearer ${token}` } });
      if (response.data.success) {
        next();
      } else {
        throw new Error('Token expired');
      }
    } catch {
      sessionStorage.removeItem('token');
      alert('Your session has expired. You will be redirected to the login page.');      
      next('/login');
    }
  } else {
    next();
  }
});

export default router
