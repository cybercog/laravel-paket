<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Jobs</h1>

        <div class="rounded overflow-hidden shadow mt-3" v-for="job in getJobs">
            <div class="p-4">
                <div class="flex">
                    <div class="font-mono" v-text="getCommandLine(job)"></div>
                    <job-options-menu class="ml-auto mr-3" :job="job"></job-options-menu>
                </div>
                <div class="flex">
                    <div class="mt-2">
                        <router-link class="font-mono text-indigo-800 hover:text-indigo-900 hover:underline" :to="linkTo(job)" v-text="getId(job)"></router-link>
                    </div>
                    <div class="flex mt-3 ml-auto mr-3">
                        <span class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-3" title="Execution start time">
                            <time v-text="getCreatedAt(job)"></time>
                        </span>
                        <job-status-badge :status="getStatus(job)"></job-status-badge>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import JobStatusBadge from '../../components/Job/StatusBadge';
    import JobOptionsMenu from '../../components/Job/OptionsMenu';

    export default {
        components: {
            JobStatusBadge,
            JobOptionsMenu,
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
