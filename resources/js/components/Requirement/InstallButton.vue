<template>
    <button
        type="button"
        :class="getClass()"
        :disabled="isDisabled()"
        v-text="getText()"
        v-on:click="install()"
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
            async install() {
                try {
                    await this.$store.dispatch('postJobs', {
                        type: 'RequirementInstall',
                        requirement: this.requirement,
                    });

                    await this.$store.dispatch('autoRefreshJobs');
                } catch (exception) {
                    this.alertError(exception);
                }
            },

            getClass() {
                const classes = 'font-semibold px-2 py-1 text-xs rounded focus:outline-none focus:shadow-outline uppercase';
                const activeClasses = 'bg-indigo-600 hover:bg-indigo-800 text-indigo-100 hover:text-white';
                const runningClasses = 'bg-indigo-800 text-white';
                const lockedClasses = 'bg-gray-400 text-black';

                const disabledClasses = this.isInProgress() ? runningClasses : lockedClasses;

                return `${classes} ${this.isDisabled() ? disabledClasses : activeClasses}`;
            },

            getText() {
                return this.isInProgress() ? 'Installing' : 'Install';
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
