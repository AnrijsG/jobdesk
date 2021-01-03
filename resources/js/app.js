require('./bootstrap');

import Vue from 'vue';
import Vuex from 'vuex';
import VueRouter from 'vue-router';
import { BootstrapVue } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import routes from './configs/routes.config.js';

import auth from './components/auth/stores/auth.store';
import advertisements from "./components/advertisement-list/stores/advertisement.store";
import personalAdvertisements from "./components/personal-advertisements/stores/personal-advertisements.store";
import appliableAdvertisements from "./components/advertisement-applications/stores/appliable-advertisement.store";
import {extend} from 'vee-validate';

Vue.use(VueRouter);
Vue.use(BootstrapVue);

import App from './views/index';

import { required, email, min, min_value, max_value } from 'vee-validate/dist/rules';

const store = new Vuex.Store({
    modules: {
        auth,
        advertisements,
        personalAdvertisements,
        appliableAdvertisements,
    }
});

const router = new VueRouter({mode: 'history', routes});

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

// Add the length rule
extend('min_value', {
    ...min_value,
});

// Add the length rule
extend('max_value', {
    ...max_value,
});
