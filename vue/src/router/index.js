import store from "@/store/index";
import { createRouter, createWebHistory } from 'vue-router';
import DashboardView from '../views/DashboardView'
import LoginView from '../views/LoginView'
import RegisterView from '../views/RegisterView'

const routes = [
  {
    path: '/',
    component: DashboardView,
    meta: { requiresAuth: true },
  },
  {
    path: '/login',
    component: LoginView,
    meta: { requiresAuth: false },
  },
  {
    path: '/register',
    component: RegisterView,
    meta: { requiresAuth: false },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(async (to, from, next) => {
  await store.dispatch("auth/getAuthUser");
  const authUser = store.getters["auth/authUser"];
  const reqAuth = to.matched.some((record) => record.meta.requiresAuth);
  const loginQuery = { path: "/login" };
  const dashboardQuery = { path: "/" };
  const logoutQuery = { path: "/logout" };

  if (reqAuth && !authUser) {
    try {
      if (!store.getters["auth/authUser"]) {
        // Halt navigation and redirect to login asynchronously
        return next(loginQuery);
      }
    } catch (error) {
      console.error('Error fetching auth user:', error);
    }
  }
  
  // If trying to visit the login page while authenticated, redirect to the dashboard
  if (to.path === loginQuery.path && authUser) {
    return next(dashboardQuery);
  } else if (to.path === logoutQuery.path && !authUser) {
    return next(loginQuery);
  }

  // Continue with the navigation
  next();
});


export default router;