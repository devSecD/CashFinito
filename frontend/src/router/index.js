import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const routes = [
    {
        path: '/', 
        redirect: '/dashboard'
    }, 
    {
        path: '/login', 
        name: 'Login',
        component: () => import('@/views/auth/LoginView.vue'),
        meta: { guest: true }
    }, 
    {
        path: '/register',
        name: 'Register',
        component: () => import('@/views/auth/RegisterView.vue'),
        meta: { guest: true }
    }, 
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: () => import('@/views/auth/ForgotPasswordView.vue'),
        meta: { guest: true }
    }, 
    {
        path: '/reset-password',
        name: 'reset-password',
        component: () => import('@/views/auth/ResetPasswordView.vue'),
        meta: { guest: true }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('@/views/DashboardView.vue'),
        meta: { requiresAuth: true }
    }, 
    {
        path: '/profile',
        name: 'Profile',
        component: () => import('@/views/auth/ProfileView.vue'),
        meta: { requiresAuth: true }
    }
]

const router = createRouter({
    history: createWebHistory(), 
    routes
})

// Navigation Guard
router.beforeEach( (to, from, next) => {
    const authStore = useAuthStore()
    const isAuthenticated  = authStore.isAuthenticated

    if (to.meta.requiresAuth && !isAuthenticated) {
        // Ruta protegida pero no autenticado → Login
        next('/login');
    } else if (to.meta.guest && isAuthenticated) {
        // Ruta de invitado pero ya autenticado → Dashboard
        next('/dashboard')
    } else {
        next()
    }
})

export default router