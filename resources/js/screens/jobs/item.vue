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
                    <div class="relative mr-3">
                        <button :class="getOptionsButtonClass()" v-on:click="toggleOptionsMenu()">
                            <svg aria-label="Show options" viewBox="0 0 13 16" version="1.1" width="13" height="16" role="img">
                                <path fill-rule="evenodd" d="M1.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm5 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM13 7.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                            </svg>
                        </button>
                        <div class="absolute bg-white border rounded right-0 shadow text-sm whitespace-no-wrap" v-if="isMenuOpened">
                            <button class="hover:bg-red-300 py-2 px-3" v-on:click="confirmDeleteJob()">Delete Job</button>
                        </div>
                    </div>
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
    import Swal from 'sweetalert2';
    import JobStatusBadge from '../../components/Job/Status/Badge';

    export default {
        components: {
            JobStatusBadge,
        },

        data() {
            return {
                job: {},
                isMenuOpened: false,
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

            async deleteJob() {
                await this.$store.dispatch('deleteJobs', {
                    id: this.$route.params.id,
                });

                await Swal.fire({
                    type: 'success',
                    title: 'Job Deletion',
                    text: 'Job has been deleted!',
                });

                window.location.href = this.$store.getters.getUrl('/jobs');
            },

            async confirmDeleteJob() {
                this.toggleOptionsMenu();

                const response = await Swal.fire({
                    type: 'warning',
                    title: 'Job Deletion',
                    text: 'Do you want to delete job permanently?',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    confirmButtonColor: '#f56565',
                });

                if (response.value) {
                    this.deleteJob();
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

            getOptionsButtonClass() {
                const classes = 'h-full px-2 hover:bg-gray-200 rounded-full';

                return classes + ` ${this.isMenuOpened ? 'bg-gray-200' : ''}`;
            },

            toggleOptionsMenu() {
                this.isMenuOpened = !this.isMenuOpened;
            },
        },
    }
</script>
