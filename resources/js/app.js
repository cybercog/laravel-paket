import Axios from 'axios';
import Vue from 'vue';
import VueRouter from 'vue-router';

import globals from './globals';
import routes from './routes';
import store from './store';

require('bootstrap');

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    Axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.use(VueRouter);

const router = new VueRouter({
    routes: routes,
    mode: 'history',
    base: '/' + window.Paket.path + '/',
});

Vue.component('application-screen', require('./screens/dashboard/index').default);
Vue.component('requirements-screen', require('./screens/requirements/index').default);
Vue.component('jobs-screen', require('./screens/jobs/index').default);

Vue.mixin(globals);

new Vue({
    el: '#paket',

    router,

    store,
});
