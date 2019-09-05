import Axios from 'axios';
import Vue from 'vue';
import VueRouter from 'vue-router';

import globals from './globals';
import routes from './routes';
import store from './store';

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    Axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.use(VueRouter);

const router = new VueRouter({
    routes: routes,
    mode: 'history',
    base: '/' + window.Paket.baseUri,
});

Vue.mixin(globals);

new Vue({
    el: '#paket',

    router,

    store,
});
