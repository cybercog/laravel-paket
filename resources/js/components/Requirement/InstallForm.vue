<template>
    <div>
        <div class="flex">
            <input
                type="text"
                :class="getInputClass()"
                :readonly="isDisabled()"
                v-model="command"
                v-on:keyup.enter="install()"
                v-on:input="searchSuggestions"
                placeholder="Type in vendor/package OR composer require vendor/package"
            />
            <button
                type="submit"
                :class="getButtonClass()"
                :disabled="isDisabled()"
                v-text="getButtonText()"
                v-on:click="install()"
            ></button>
        </div>
        <div class="border absolute" v-if="getSuggestions().length > 0">
            <div v-for="suggestion in getSuggestions()" class="bg-white hover:bg-gray-100 p-2" v-on:click="suggestionSelect(suggestion)">
                <span v-text="suggestion.name" :title="suggestion.description"></span>
            </div>
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
                isInputInvalid: false,
                isProcessing: false,
            };
        },

        methods: {
            async install() {
                this.sanitizeInput();

                if (this.command === '') {
                    this.isInputInvalid = true;
                    return;
                }
                this.isInputInvalid = false;

                this.parseCommand();

                try {
                    await this.$store.dispatch('postJobs', {
                        type: 'RequirementInstall',
                        requirement: this.requirement,
                    });

                    await this.$store.dispatch('autoRefreshJobs');
                } catch (exception) {
                    this.alertError(exception);
                }

                this.forgetInput();
            },

            async searchSuggestions(input) {
                const query = input.target.value;

                if (query === '') {
                    await this.$store.dispatch('clearRequirementSuggestions');
                    return;
                }

                await this.$store.dispatch('collectRequirementSuggestions', {
                    query: query,
                });
            },

            suggestionSelect(suggestion) {
                this.command = suggestion.name;
                this.$store.dispatch('clearRequirementSuggestions');
            },

            getSuggestions() {
                return this.$store.state.requirementSuggestions;
            },

            isDisabled() {
                return this.$store.state.isInstallerLocked
                    || this.$store.getters.getActiveJobs().length > 0;
            },

            isInProgress() {
                return this.$store.getters.isProcessingRequirement(this.requirement)
                    || this.$store.getters.getRequirementActiveJobs(this.requirement).length > 0;
            },

            getInputClass() {
                const classes = 'shadow appearance-none rounded-l w-full py-2 px-3 text-gray-900 placeholder-gray-700 leading-tight focus:outline-none focus:shadow-outline border-2 border-r-0';
                const runningClasses = 'border-indigo-700 bg-indigo-100';
                const invalidClass = 'border-red-600 bg-red-100 placeholder-red-900';

                if (this.isInProgress()) {
                    return `${classes} ${runningClasses}`;
                }

                return `${classes} ${this.isInputInvalid ? invalidClass : ''}`;
            },

            getButtonClass() {
                const classes = 'font-semibold py-2 px-4 rounded-r uppercase focus:outline-none focus:shadow-outline';
                const activeClasses = 'bg-indigo-600 hover:bg-indigo-800 text-indigo-100 hover:text-white';
                const runningClasses = 'bg-indigo-800 text-white';
                const lockedClasses = 'bg-gray-400 text-black';

                const disabledClasses = this.isInProgress() ? runningClasses : lockedClasses;

                return `${classes} ${this.isDisabled() ? disabledClasses : activeClasses}`;
            },

            getButtonText() {
                return this.isInProgress() ? 'Installing' : 'Install';
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
