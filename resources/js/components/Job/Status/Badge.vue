<template>
    <span :class="getStatusClass()" v-text="getStatus()"></span>
</template>

<script>
    export default {
        props: {
            job: {
                type: Object,
                required: true,
            },
        },

        methods: {
            getStatus() {
                const {
                    status,
                } = this.job;

                const statuses = {
                    Waiting: 'Waiting',
                    InProgress: 'In Progress',
                    Done: 'Done',
                    Failed: 'Failed',
                };

                return statuses[status];
            },

            getStatusClass() {
                const {
                    status,
                } = this.job;

                const statusClasses = {
                    Waiting: 'secondary',
                    InProgress: 'primary',
                    Done: 'success',
                    Failed: 'danger',
                };

                return `badge badge-${statusClasses[status]}`;
            },
        },
    }
</script>