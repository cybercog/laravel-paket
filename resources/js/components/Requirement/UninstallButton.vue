<template>
    <button
        type="button"
        class="bg-red-600 hover:bg-red-800 text-red-100 hover:text-white font-semibold px-2 py-1 text-sm rounded focus:outline-none focus:shadow-outline uppercase"
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

            isFormDisabled() {
                return this.$store.state.isComposerBusy;
            },

            disableForm() {
                this.buttonText = 'Uninstalling';
                this.$store.commit('runComposer');
            },

            enableForm() {
                this.buttonText = 'Uninstall';
                this.$store.commit('stopComposer');
            },
        },
    }
</script>
