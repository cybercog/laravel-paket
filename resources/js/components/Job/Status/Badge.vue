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
                    Pending: 'bg-yellow-200 text-yellow-800',
                    Running: 'bg-blue-200 text-blue-800',
                    Success: 'bg-green-200 text-green-800',
                    Failed: 'bg-red-200 text-red-800',
                };

                return `px-2 py-1 text-sm inline-block px-2 uppercase font-semibold font-mono tracking-wide ${statusClasses[this.status]}`;
            },
        },
    }
</script>