<template>
    <button
        type="button"
        class="bg-indigo-600 hover:bg-indigo-800 text-indigo-100 hover:text-white font-semibold px-2 py-1 text-xs rounded focus:outline-none focus:shadow-outline uppercase"
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

            isFormDisabled() {
                return this.$store.state.isComposerBusy;
            },

            disableForm() {
                this.buttonText = 'Installing';
                this.$store.commit('runComposer');
            },

            enableForm() {
                this.buttonText = 'Install';
                this.$store.commit('stopComposer');
            },
        },
    }
</script>
