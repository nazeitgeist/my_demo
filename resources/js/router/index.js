import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import About from '../pages/About';
import Register from '../pages/Register';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';

import Developers from '../components/Developers';
import AddDeveloper from '../components/AddDeveloper';
import EditDeveloper from '../components/EditDeveloper';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'about',
        path: '/about',
        component: About
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'developers',
        path: '/developers',
        component: Developers
    },
    {
        name: 'adddeveloper',
        path: '/developers/add',
        component: AddDeveloper
    },
    {
        name: 'editdeveloper',
        path: '/developers/edit/:id',
        component: EditDeveloper
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
