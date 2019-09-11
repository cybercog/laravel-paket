<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl underline">
            <router-link class="text-indigo-700 hover:text-indigo-900" :to="{name: 'jobs'}">Jobs</router-link>
        </h1>

        <div class="rounded overflow-hidden shadow mt-3 py-3">
            <div class="flex">
                <div class="mx-3">
                    <h4 class="text-xl font-mono" v-text="job.id"></h4>
                </div>

                <div class="flex ml-auto">
                    <span class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-3" title="Execution start time">
                        <time v-text="getCreatedAt(job)"></time>
                    </span>
                    <job-status-badge class="mr-3" :status="getStatus(job)"></job-status-badge>
                    <job-options-menu class="mr-3" :job="job"></job-options-menu>
                </div>
            </div>

            <div class="p-4 text-left bg-black mt-4 -mb-3" v-show="processOutput">
                <code class="bg-black text-white border-0" v-html="processOutput"></code>
            </div>
        </div>
    </div>
</template>

<script>
    import AnsiConverter from 'ansi-to-html';
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
                job: {},
                isMounted: true,
            };
        },

        mounted() {
            this.fetchData();
            this.autoRefreshData();
        },

        beforeDestroy() {
            this.isMounted = false;
        },

        computed: {
            processOutput() {
                if (!this.job.process) {
                    return '';
                }

                return this.job.process.output;
            },
        },

        methods: {
            async autoRefreshData() {
                setTimeout(async () => {
                    if (this.isMounted === false) {
                        return;
                    }

                    if (this.job.status === 'Pending' || this.job.status === 'Running') {
                        await this.fetchData();
                        await this.autoRefreshData();
                    }
                }, 1000);
            },

            async fetchData() {
                const response = await this.$store.getters.getJob(this.$route.params.id);

                if (typeof response !== 'undefined') {
                    this.job = response.data;

                    this.job.process.output = this.asHtml(this.job.process.output);
                }
            },

            asHtml(string) {
                return new AnsiConverter()
                    .toHtml(string)
                    .replace(/(?:\r\n|\r|\n)/g, '<br/>')
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
        },
    }
</script>
