<template>
    <span :class="getStatusClass()" v-text="getStatus()"></span>
</template>

<script>
    export default {
        props: {
            status: {
                type: String,
                required: true,
            },
        },

        methods: {
            getStatus() {
                const statuses = {
                    Pending: 'Pending',
                    Running: 'Running',
                    Success: 'Success',
                    Failed: 'Failed',
                };

                return statuses[this.status];
            },

            getStatusClass() {
                const statusClasses = {
                    Pending: 'secondary',
                    Running: 'primary',
                    Success: 'success',
                    Failed: 'danger',
                };

                return `badge badge-${statusClasses[this.status]}`;
            },
        },
    }
</script>