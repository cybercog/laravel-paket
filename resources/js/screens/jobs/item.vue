<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">
            <router-link :to="{name: 'jobs'}">Jobs</router-link>
        </h1>

        <div class="flex mt-3">
            <h4 class="text-xl font-mono">{{ job.id }}</h4>
        </div>
        <div class="flex mt-3">
            <span class="bg-gray-200 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-3">
                Started: <time v-text="getCreatedAt(job)"></time>
            </span>
            <job-status-badge :status="getStatus(job)"></job-status-badge>
        </div>

        <div class="p-4 text-left bg-black mt-4" v-show="processOutput">
            <code class="bg-black text-white border-0" v-html="processOutput"></code>
        </div>
    </div>
</template>

<script>
    import AnsiConverter from 'ansi-to-html';
    import moment from 'moment';
    import JobStatusBadge from '../../components/Job/Status/Badge';

    export default {
        components: {
            JobStatusBadge,
        },

        data() {
            return {
                job: {},
            };
        },

        mounted() {
            this.fetchData();
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
            async fetchData() {
                const response = await this.$store.getters.getJob(this.$route.params.id);

                this.job = response.data;

                this.job.process.output = this.asHtml(this.job.process.output);
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
