<template>
    <button
        type="button"
        :class="getClass()"
        :disabled="isDisabled()"
        v-text="getText()"
        v-on:click="uninstall()"
    ></button>
</template>

<script>
    export default {
        props: {
            requirement: {
                type: Object,
                required: true,
            },
        },

        methods: {
            async uninstall() {
                try {
                    await this.$store.dispatch('postJobs', {
                        type: 'RequirementUninstall',
                        requirement: this.requirement,
                    });

                    await this.$store.dispatch('autoRefreshJobs');
                } catch (exception) {
                    this.alertError(exception);
                }
            },

            getClass() {
                const classes = 'font-semibold px-2 py-1 text-xs rounded focus:outline-none focus:shadow-outline uppercase';
                const activeClasses = 'bg-red-600 hover:bg-red-800 text-red-100 hover:text-white';
                const runningClasses = 'bg-red-800 text-white';
                const lockedClasses = 'bg-gray-400 text-black';

                const disabledClasses = this.isInProgress() ? runningClasses : lockedClasses;

                return `${classes} ${this.isDisabled() ? disabledClasses : activeClasses}`;
            },

            getText() {
                return this.isInProgress() ? 'Uninstalling' : 'Uninstall';
            },

            isDisabled() {
                return this.$store.state.isInstallerLocked
                    || this.$store.getters.getActiveJobs().length > 0;
            },

            isInProgress() {
                return this.$store.getters.isProcessingRequirement(this.requirement)
                    || this.$store.getters.getRequirementActiveJobs(this.requirement).length > 0;
            },
        },
    }
</script>
