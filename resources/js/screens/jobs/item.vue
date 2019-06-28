<template>
    <div class="container mt-4">
        <h1>
            <router-link :to="{name: 'jobs'}">Jobs</router-link>
        </h1>

        <div class="d-flex mt-4 align-items-center">
            <h4>{{ job.id }}</h4>
            <div class="ml-auto">
                <job-status-badge :job="job"></job-status-badge>
            </div>
        </div>
        <div>
            Started: <time v-text="getCreatedAt(job)"></time>
        </div>

        <div class="p-4 text-nowrap text-left bg-black mt-4" v-show="job.process.output">
            <code class="bg-black text-white border-0" v-html="job.process.output"></code>
        </div>
    </div>
</template>

<script>
    import Axios from 'axios';
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

        methods: {
            async fetchData() {
                const response = await Axios.get(`/paket/api/jobs/${this.$route.params.id}`);

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
        },
    }
</script>
