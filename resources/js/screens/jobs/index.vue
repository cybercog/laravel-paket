<template>
    <div class="container mt-4">
        <h1>Jobs</h1>

        <ul class="list-group">
            <li class="list-group-item" v-for="job in getJobs">
                <div class="d-flex">
                    <div>
                        <div class="text-monospace" v-text="getCommandLine(job)"></div>
                        <router-link class="text-monospace" :to="linkTo(job)" v-text="getId(job)"></router-link>
                    </div>
                    <div class="text-muted ml-auto">
                        <div v-text="getCreatedAt(job)"></div>
                        <job-status-badge :job="job" class="ml-auto"></job-status-badge>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    import moment from 'moment';
    import JobStatusBadge from '../../components/Job/Status/Badge';

    export default {
        components: {
            JobStatusBadge,
        },

        data() {
            return {
                jobs: [],
            };
        },

        mounted() {
            this.fetchData();
        },

        computed: {
            getJobs() {
                return this.$store.state.jobs;
            },
        },

        methods: {
            async fetchData() {
                await this.$store.dispatch('collectJobs');
            },

            linkTo(job) {
                return {
                    name: 'job',
                    params: {
                        id: job.id,
                    },
                };
            },

            getId(job) {
                return job.id;
            },

            getCommandLine(job) {
                const {
                    type,
                    requirement,
                } = job;

                const executable = this.getExecutable(type);
                const requirementName = requirement.name;
                const version = requirement.version && type === 'ComposerInstall' ? `:${requirement.version}` : '';
                const isDevelopment = requirement.isDevelopment ? '--dev' : '';

                return `${executable} ${requirementName}${version} ${isDevelopment}`.trim();
            },

            getCreatedAt(job) {
                const {
                    createdAt,
                } = job;

                return moment(createdAt).format('YYYY-MM-DD HH:mm:ss');
            },

            getExecutable(type) {
                switch (type) {
                    case 'ComposerInstall':
                        return 'composer require';
                    case 'ComposerUninstall':
                        return 'composer remove';
                    default:
                        return '%UNKNOWN_EXECUTABLE%';
                }
            },
        },
    }
</script>
