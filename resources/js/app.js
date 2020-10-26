import {UserModel} from "./components/auth/models/user.model";

require('./bootstrap');

import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import { BootstrapVue } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import auth from './components/auth/stores/auth.store';
import advertisements from "./components/advertisement-list/stores/advertisement.store";
import personalAdvertisements from "./components/personal-advertisements/stores/personal-advertisements.store";
import { extend } from 'vee-validate';

Vue.use(VueRouter);
Vue.use(BootstrapVue);

import App from './views/index';
import Homepage from './views/homepage';
import Dashboard from './views/dashboard';

import { required, email, min } from 'vee-validate/dist/rules';
import {EnvironmentModel} from "./components/auth/models/environment.model";

const store = new Vuex.Store({
    modules: {
        auth,
        advertisements,
        personalAdvertisements,
    }
});

async function requireLogin(to, from, next) {
    const response = await axios.post('/auth/get-user');
    const userData = response.data;

    if (!userData) {
        next({
            name: 'homepage',
        });
    } else {
        const user = UserModel.fromArray(userData);
        const userRole = user.environment.role;

        if (userRole === EnvironmentModel.ROLE_ADVERTISER || userRole === EnvironmentModel.ROLE_ADMIN) {
            next(true);
        } else {
            next({
                name: 'homepage',
            });
        }
    }
}

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/dashboard',
            name: 'dashboard',
            component: Dashboard,
            beforeEnter:requireLogin,
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
