//import vue router
import { createRouter, createWebHistory } from 'vue-router'

//define a routes
const routes = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/auth/Login.vue')
    },
    {
        path: '/register',
        name: 'register',
        component: () => import( '@/views/auth/Register.vue')
    },
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import( '@/views/dashboard/Index.vue')
    },
    {
        path: '/update',
        name: 'update',
        component: () => import( '@/views/auth/Update.vue')
    }
]

//create router
const router = createRouter({
    history: createWebHistory(),
    routes  // config routes
})

export default router