import { createRouter, createWebHistory } from 'vue-router';
import HomeIndex from '../pages/Home/Index.vue';
import AuthLogin from '../pages/Auth/Login.vue';
import AuthRegister from '../pages/Auth/Register.vue';
import adminDashboard from './admin/dashboard.js';
import ServicesIndex from '../pages/Services/Index.vue';

const baseRoutes = [
    {
        path: '/',
        name: 'home',
        component: HomeIndex,
        meta: {
            auth: false,
            breadcrumb: 'home',
        },
    },
    {
        path: '/login',
        name: 'authLogin',
        component:  AuthLogin,
        meta: {
            auth: false,
            breadcrumb: 'login',
        },
    },
    {
        path: '/register',
        name: 'authRegister',
        component: AuthRegister,
        meta: {
            auth: false,
            breadcrumb: 'register',
        },
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: { name: 'home' },
    },
];
const routes = baseRoutes.concat(
    adminDashboard,
);

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { top: 0, left: 0 };
    },
});

router.beforeEach((to, from, next) => {
    let isAuthenticated = false;

    try {
        const authData = JSON.parse(localStorage.getItem('auth'));
        if (authData && authData.access_token) {
            isAuthenticated = true;
        }
    } catch (e) {
        isAuthenticated = false;
    }

    // Если маршрут требует авторизацию, а пользователь не авторизован
    if (to.meta.auth && !isAuthenticated) {
        return next({ name: 'authLogin' });
    }

    // Если пользователь уже авторизован, не пускать на Login и Register
    if (
        isAuthenticated &&
        (to.name === 'authLogin' || to.name === 'authRegister')
    ) {
        return next({ name: 'home' });
    }

    return next();
});



export default router;
