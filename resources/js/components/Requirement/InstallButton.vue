<template>
    <button
        type="button"
        :class="getClasses()"
        :disabled="isFormDisabled()"
        v-text="buttonText"
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

        data() {
            return {
                buttonText: 'Install',
                isInvoker: false,
            };
        },

        methods: {
            async install() {
                this.disableForm();

                try {
                    this.alertInfo({
                        title: `Installing\n${this.requirement.name}`,
                        showConfirmButton: false,
                    });

                    await this.$store.dispatch('postJobs', {
                        type: 'RequirementInstall',
                        requirement: this.requirement,
                    });

                    this.alertSuccess({
                        title: `Installed\n${this.requirement.name}`,
                    });
                } catch (exception) {
                    this.alertError(exception);
                }

                this.enableForm();
            },

            getClasses() {
                const activeClasses = 'bg-indigo-600 hover:bg-indigo-800 text-indigo-100 hover:text-white';
                const invokerClasses = 'bg-indigo-800 text-white';
                const othersClasses = 'bg-gray-400 text-black';

                const inactiveClasses = this.isInvoker ? invokerClasses : othersClasses;

                const classes = 'font-semibold px-2 py-1 text-xs rounded focus:outline-none focus:shadow-outline uppercase';

                return `${classes} ${this.isFormDisabled() ? inactiveClasses : activeClasses}`;
            },

            isFormDisabled() {
                return this.$store.state.isComposerBusy;
            },

            disableForm() {
                this.buttonText = 'Installing';
                this.isInvoker = true;
                this.$store.commit('runComposer');
            },

            enableForm() {
                this.buttonText = 'Install';
                this.isInvoker = false;
                this.$store.commit('stopComposer');
            },
        },
    }
</script>
