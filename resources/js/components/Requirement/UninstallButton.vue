<template>
    <button
        type="button"
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

        computed: {
            getRequirementUid() {
                return btoa(unescape(encodeURIComponent(this.requirement.name)));
            },
        },

        methods: {
            async uninstall() {
                this.disableForm();

                try {
                    this.alertInfo({
                        title: `Uninstalling\n${this.requirement.name}`,
                        showConfirmButton: false,
                    });

                    await this.$store.dispatch('deleteRequirements', this.getRequirementUid);

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
