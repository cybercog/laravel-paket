<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Jobs</h1>

        <div class="rounded overflow-hidden shadow mt-3" v-for="job in getJobs">
            <div class="flex p-4">
                <div>
                    <div class="font-mono" v-text="getCommandLine(job)"></div>
                    <router-link class="font-mono text-indigo-800 hover:text-indigo-900 hover:underline" :to="linkTo(job)" v-text="getId(job)"></router-link>
                </div>
                <div class="text-muted ml-auto text-right">
                    <div class="text-gray-700 text-xs font-mono" v-text="getCreatedAt(job)"></div>
                    <job-status-badge :status="getStatus(job)"></job-status-badge>
                </div>
            </div>
        </div>
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
                const version = requirement.version && type === 'RequirementInstall' ? `:${requirement.version}` : '';
                const isDevelopment = requirement.isDevelopment ? '--dev' : '';

                return `${executable} ${requirementName}${version} ${isDevelopment}`.trim();
            },

            getCreatedAt(job) {
                const {
                    createdAt,
                } = job;

                return moment(createdAt).format('YYYY-MM-DD HH:mm:ss');
            },

            getStatus(job) {
                if (!job.hasOwnProperty('status')) {
                    return '';
                }

                const {
                    status,
                } = job;

                return status;
            },

            getExecutable(type) {
                switch (type) {
                    case 'RequirementInstall':
                        return 'composer require';
                    case 'RequirementUninstall':
                        return 'composer remove';
                    default:
                        return '%UNKNOWN_EXECUTABLE%';
                }
            },
        },
    }
</script>
