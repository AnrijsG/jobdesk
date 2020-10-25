require('./bootstrap');

import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';

import auth from './components/auth/stores/auth.store';
import advertisements from "./components/advertisement-list/stores/advertisement.store";
import { extend } from 'vee-validate';

Vue.use(VueRouter);

import App from './views/index';
import Homepage from './views/homepage';
import Dashboard from './views/dashboard';

import { required, email, min } from 'vee-validate/dist/rules';

const store = new Vuex.Store({
    modules: {
        auth,
        advertisements,
    }
});

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
        },
        {
            path: '/',
            name: 'homepage',
            component: Homepage,
        },
    ],
});

const app = new Vue({
    router,
    components: {App},
    store,
}).$mount('#app');


// Add the required rule
extend('required', {
    ...required,
    message: 'This field is required'
});

// Add the email rule
extend('email', {
    ...email,
    message: 'This field must be a valid email'
});

// Add the length rule
extend('min', {
    ...min,
    message: 'Password must consist from at least 8 characters'
});
