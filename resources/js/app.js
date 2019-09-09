import Vue from 'vue';
import VueRouter from 'vue-router';

import globals from './globals';
import routes from './routes';
import store from './store';

Vue.use(VueRouter);

const router = new VueRouter({
    routes: routes,
    mode: 'history',
    base: '/' + window.Paket.baseUri,
});

Vue.component('top-menu', require('./components/TopMenu.vue').default);

Vue.mixin(globals);

new Vue({
    el: '#paket',

    router,

    store,

    created() {
        this.fetchData();
    },

    methods: {
        async fetchData() {
            // Need it to run jobs state checker after page reload
            await this.$store.dispatch('autoRefreshJobs');
            await this.$store.dispatch('collectRequirements');
            await this.$store.dispatch('collectRepositories');
        },
    },
});
