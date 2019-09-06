<template>
    <button
        type="button"
        :class="getClasses()"
        :disabled="isFormDisabled()"
        v-text="buttonText"
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

        data() {
            return {
                buttonText: 'Uninstall',
                isInvoker: false,
            };
        },

        methods: {
            async uninstall() {
                this.disableForm();

                try {
                    this.alertInfo({
                        title: `Uninstalling\n${this.requirement.name}`,
                        showConfirmButton: false,
                    });

                    await this.$store.dispatch('postJobs', {
                        type: 'RequirementUninstall',
                        requirement: this.requirement,
                    });

                    this.alertSuccess({
                        title: `Uninstalled\n${this.requirement.name}`,
                    });
                } catch (exception) {
                    this.alertError(exception);
                }

                this.enableForm();
            },

            getClasses() {
                const activeClasses = 'bg-red-600 hover:bg-red-800 text-red-100 hover:text-white';
                const invokerClasses = 'bg-red-800 text-white';
                const othersClasses = 'bg-gray-400 text-black';

                const inactiveClasses = this.isInvoker ? invokerClasses : othersClasses;

                const classes = 'font-semibold px-2 py-1 text-xs rounded focus:outline-none focus:shadow-outline uppercase';

                return `${classes} ${this.isFormDisabled() ? inactiveClasses : activeClasses}`;
            },

            isFormDisabled() {
                return this.$store.state.isComposerBusy;
            },

            disableForm() {
                this.buttonText = 'Uninstalling';
                this.isInvoker = true;
                this.$store.commit('runComposer');
            },

            enableForm() {
                this.buttonText = 'Uninstall';
                this.isInvoker = false;
                this.$store.commit('stopComposer');
            },
        },
    }
</script>
