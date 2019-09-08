<template>
    <div class="relative">
        <button :class="getOptionsButtonClass()" v-on:click="toggleOptionsMenu()">
            <svg aria-label="Show options" viewBox="0 0 13 16" version="1.1" width="13" height="16" role="img">
                <path fill-rule="evenodd" d="M1.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm5 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zM13 7.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
            </svg>
        </button>
        <div class="absolute bg-white border rounded right-0 shadow text-sm whitespace-no-wrap" v-if="isMenuOpened">
            <button class="hover:bg-red-300 py-2 px-3" v-on:click="confirmDeleteJob()">Delete Job</button>
        </div>
    </div>
</template>

<script>
    import Swal from 'sweetalert2';

    export default {
        props: {
            job: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                isMenuOpened: false,
            };
        },

        methods: {
            getOptionsButtonClass() {
                const classes = 'p-2 hover:bg-gray-200 rounded-full';

                return classes + ` ${this.isMenuOpened ? 'bg-gray-200' : ''}`;
            },

            toggleOptionsMenu() {
                this.isMenuOpened = !this.isMenuOpened;
            },

            async deleteJob() {
                await this.$store.dispatch('deleteJobs', {
                    id: this.job.id,
                });

                await this.$store.dispatch('collectJobs');
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

                    await Swal.fire({
                        type: 'success',
                        title: 'Job Deleted!',
                    });

                    if (this.$router.currentRoute.name !== 'jobs') {
                        await this.$router.replace({
                            name: 'jobs',
                        });
                    }
                }
            },
        },
    }
</script>