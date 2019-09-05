<template>
    <div v-show="hasSuggestions()" class="mt-6 bg-gray-200 -mb-4 -ml-4 -mr-4 p-3">
        <h5 class="flex">
            <button
                :class="getToggleButtonClass()"
                v-text="getToggleButtonText()"
                v-on:click="toggle()"
            ></button>
            <span class="ml-auto font-mono font-semibold text-gray-600">
                <span v-text="getInstalledSuggestionsCount()"></span>
                /
                <span v-text="getTotalSuggestionsCount()"></span>
            </span>
        </h5>
        <div :class="getListClass()">
            <ul>
                <li v-for="(description, suggestion) in getSuggestions()" class="flex hover:bg-gray-300 -mx-4 px-4 py-3">
                    <div>
                        <div class="font-mono" v-text="suggestion"></div>
                        <div class="text-gray-600 text-xs" v-text="description"></div>
                    </div>
                    <div class="ml-auto w-1/5 text-right">
                        <uninstall-button
                            class="btn btn-outline-danger btn-sm"
                            v-if="isUninstallable(suggestion)"
                            :requirement="getRequirementFromSuggestion(suggestion)"
                        ></uninstall-button>
                        <install-button
                            class="btn btn-primary btn-sm"
                            v-if="isInstallable(suggestion)"
                            :requirement="getRequirementFromSuggestion(suggestion)"
                        ></install-button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import InstallButton from './InstallButton';
    import UninstallButton from './UninstallButton';

    export default {
        components: {
            InstallButton,
            UninstallButton,
        },

        props: {
            requirement: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
              isOpened: false,
            };
        },

        methods: {
            getInstalledSuggestionsCount() {
                let count = 0;

                Object.keys(this.getSuggestions()).forEach(suggestion => {
                    if (this.isInstalled(suggestion)) {
                        count++;
                    }
                });

                return count;
            },

            getTotalSuggestionsCount() {
                return Object.keys(this.getSuggestions()).length;
            },

            hasSuggestions() {
                return this.getTotalSuggestionsCount() > 0;
            },

            getSuggestions() {
                if (typeof this.requirement['suggest'] === 'undefined') {
                    return {};
                }

                const {
                    suggest,
                } = this.requirement;

                return suggest;
            },

            getRequirementFromSuggestion(suggestion) {
                return {
                    name: suggestion,
                    version: null,
                    isDevelopment: this.requirement.isDevelopment,
                }
            },

            getToggleButtonClass() {
                const classes = 'text-gray-600 hover:text-gray-800 hover:underline font-mono font-semibold uppercase';

                return classes + ` ${this.isOpened ? 'underline text-indigo-900' : ''}`;
            },

            getToggleButtonText() {
                return this.isOpened ? 'Hide Suggestions' : 'Show Suggestions';
            },

            getListClass() {
                return this.isOpened ? 'mt-3' : 'hidden';
            },

            isInstalled(suggestionName) {
                return this.$store.getters.isRequirementInstalled(suggestionName);
            },

            isInstallable(suggestion) {
                if (this.isInstalled(suggestion)) {
                    return false;
                }

                return this.$store.getters.isNotProtectedRequirement(suggestion);
            },

            isUninstallable(suggestion) {
                if (!this.isInstalled(suggestion)) {
                    return false;
                }

                return this.$store.getters.isNotProtectedRequirement(suggestion);
            },

            toggle() {
                this.isOpened = !this.isOpened;
            },
        },
    }
</script>
