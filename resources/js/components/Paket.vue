<template>
    <div class="flex align-top">
        <img
            class="w-10 h-10 rounded mr-4 bg-gray-700 p-1"
            :style="`background-color: ${paket.iconBg || '#4a5568'}`"
            :src="paket.icon"
            alt=""
        />
        <div>
            <h4 class="text-gray-900 font-mono" v-text="paket.title"></h4>
            <div class="text-gray-600 font-mono text-xs" v-text="paket.description"></div>
        </div>

        <div class="ml-auto text-right">
            <div v-if="isInstalled()">
                <uninstall-button
                    :requirement="getRequirement()"
                ></uninstall-button>
                <div class="mt-2">
                    <span class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700" v-text="getVersion()"></span>
                </div>
            </div>
            <div v-if="!isInstalled()">
                <install-button
                    :requirement="getRequirement()"
                ></install-button>
            </div>
        </div>
    </div>
</template>

<script>
    import InstallButton from './Requirement/InstallButton';
    import UninstallButton from './Requirement/UninstallButton';

    export default {
        components: {
            InstallButton,
            UninstallButton,
        },

        props: {
            paket: {
                type: Object,
                required: true,
            }
        },

        methods: {
            getRequirement() {
                return {
                    name: this.paket.name,
                    isDevelopment: this.paket.isDevelopment || false,
                };
            },

            hasActiveJobs() {
                return this.$store.getters.getActiveJobs().length > 0;
            },

            isInProgress() {
                const jobs = this.$store.getters.getRequirementActiveJobs(
                    this.getRequirement()
                );

                return jobs.length > 0;
            },

            isInstalled() {
                return this.getVersion() !== null;
            },

            getVersion() {
                let version;
                const name = this.paket.name;

                version = this.findRequirementVersion(this.getRequirements('roots', 'essential'), name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('roots', 'dev'), name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('dependencies', 'essential'), name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('dependencies', 'dev'), name);
                if (version !== null) {
                    return version;
                }

                return null;
            },

            getRequirements(level, environment) {
                const requirements = this.$store.state.requirements;

                if (typeof requirements[level] === 'undefined') {
                    return [];
                }

                if (typeof requirements[level][environment] === 'undefined') {
                    return [];
                }

                return requirements[level][environment];
            },

            findRequirementVersion(requirements, name) {
                const results = requirements.filter(requirement => requirement.name === name);

                if (results.length === 0) {
                    return null;
                }

                const version = results[0].version;

                if (version.substr(0, 1) === 'v') {
                    return version;
                }

                return `v${version}`;
            },
        },
    }
</script>
