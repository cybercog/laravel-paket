<template>
    <div class="input-group">
        <input
            type="text"
            class="form-control"
            :readonly="isFormDisabled()"
            v-model="command"
            v-on:keyup.enter="install()"
        />
        <div class="input-group-append">
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="isFormDisabled()"
                v-text="buttonText"
                v-on:click="install()"
            ></button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                buttonText: 'Install',
                command: '',
                requirement: {
                    name: '',
                    version: null,
                    isDevelopment: false,
                },
            };
        },

        methods: {
            async install() {
                if (this.command === '') {
                    this.alertWarning({
                        title: 'Field `command` is required!',
                    });
                    return;
                }

                this.disableForm();
                this.sanitizeInput();
                this.parseCommand();

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

                this.forgetInput();
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

            sanitizeInput() {
                this.command = this.command.trim();
            },

            forgetInput() {
                this.command = '';
                this.requirement.name = '';
                this.requirement.version = null;
                this.requirement.isDevelopment = false;
            },

            parseCommand() {
                let command = this.command;

                const devPosition = command.indexOf('--dev');
                if (devPosition !== -1) {
                    this.requirement.isDevelopment = true;
                    command = command.substr(0, devPosition) + command.substr(devPosition + 5);
                    command = command.trim();
                }

                if (command.indexOf(' ') === -1) {
                    this.parseRequirementName(command);
                    return;
                }

                const commandParts = command.split(' ');
                this.parseRequirementName(commandParts[commandParts.length - 1]);
            },

            parseRequirementName(name) {
                const versionPosition = name.indexOf(':');

                if (versionPosition === -1) {
                    this.requirement.name = name;
                    return;
                }

                this.requirement.name = name.substr(0, versionPosition);
                this.requirement.version = name.substr(versionPosition + 1);
            },
        },
    }
</script>
