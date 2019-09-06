<template>
    <button
        type="button"
        :class="getClass()"
        :disabled="isDisabled"
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
            isRunning: {
                type: Boolean,
                required: false,
                default: false,
            },
            isDisabled: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        methods: {
            async install() {
                try {
                    await this.$store.dispatch('postJobs', {
                        type: 'RequirementInstall',
                        requirement: this.requirement,
                    });
                } catch (exception) {
                    this.alertError(exception);
                }
            },

            getClass() {
                const classes = 'font-semibold px-2 py-1 text-xs rounded focus:outline-none focus:shadow-outline uppercase';
                const activeClasses = 'bg-indigo-600 hover:bg-indigo-800 text-indigo-100 hover:text-white';
                const runningClasses = 'bg-indigo-800 text-white';
                const lockedClasses = 'bg-gray-400 text-black';

                const disabledClasses = this.isRunning ? runningClasses : lockedClasses;

                return `${classes} ${this.isDisabled ? disabledClasses : activeClasses}`;
            },

            getText() {
                return this.isRunning ? 'Installing' : 'Install';
            },
        },
    }
</script>
