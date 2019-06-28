<template>
    <button
        type="button"
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

                    await this.$store.dispatch('postRequirements', {
                        name: this.requirement.name,
                        version: this.requirement.version,
                        isDevelopment: this.requirement.isDevelopment,
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
