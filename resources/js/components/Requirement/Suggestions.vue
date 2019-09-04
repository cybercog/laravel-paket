<template>
    <div v-show="hasSuggestions()" class="mt-4">
        <h5>
            <a data-toggle="collapse" :href="`#${getCollapsibleId(requirement.name)}`" role="button" aria-expanded="false">
                <span class="text-purple-800 hover:text-purple hover:underline">
                    Suggestions
                    ({{ getInstalledSuggestionsCount() }}/{{ getTotalSuggestionsCount() }})
                </span>
            </a>
        </h5>
        <div class="collapse" :id="`${getCollapsibleId(requirement.name)}`">
            <ul>
                <li v-for="(description, suggestion) in getSuggestions()" class="mt-2">
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
                    <span class="font-mono">{{ suggestion }}</span>
                    <span class="text-gray-600">{{ description }}</span>
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

        methods: {
            getCollapsibleId(name) {
                return `collapseSuggestions-` + name.replace('/', '');
            },

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
        },
    }
</script>
