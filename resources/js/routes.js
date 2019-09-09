export default [
    {
        path: '/',
        redirect: '/dashboard',
    },

    {
        path: '/dashboard',
        name: 'dashboard',
        component: require('./screens/dashboard/index').default,
    },

    {
        path: '/laravel',
        name: 'laravel',
        component: require('./screens/laravel/index').default,
    },

    {
        path: '/requirements',
        name: 'requirements',
        component: require('./screens/requirements/index').default,
    },

    {
        path: '/repositories',
        name: 'repositories',
        component: require('./screens/repositories/index').default,
    },

    {
        path: '/jobs',
        name: 'jobs',
        component: require('./screens/jobs/index').default,
    },

    {
        path: '/jobs/:id',
        name: 'job',
        component: require('./screens/jobs/item').default,
    },
];
