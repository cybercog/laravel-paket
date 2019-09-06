<template>
    <div class="flex align-top">
        <img
            class="w-10 h-10 rounded mr-4 bg-gray-700 p-1"
            :style="`background-color: ${this.iconBg}`"
            :src="this.icon"
            alt=""
        />
        <div>
            <h4 class="text-gray-900 font-mono" v-text="title"></h4>
            <div class="text-gray-600 font-mono text-xs" v-text="description"></div>
        </div>

        <div class="ml-auto text-right">
            <div v-if="isInstalled()">
                <uninstall-button
                    :requirement="{name: this.name, isDevelopment: this.isDevelopment}"
                ></uninstall-button>
                <div class="mt-2">
                    <span class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700" v-text="getVersion()"></span>
                </div>
            </div>
            <div v-if="!isInstalled()">
                <install-button
                    :requirement="{name: this.name, isDevelopment: this.isDevelopment}"
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
            name: {
                type: String,
                required: true,
            },
            title: {
                type: String,
                required: false,
            },
            description: {
                type: String,
                required: false,
            },
            icon: {
                type: String,
                required: false,
            },
            iconBg: {
                type: String,
                required: false,
                default: '#4a5568',
            },
            isDevelopment: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        mounted() {
            this.fetchData();
        },

        methods: {
            async fetchData() {
                await this.$store.dispatch('collectRequirements');
            },

            isInstalled() {
                return this.getVersion() !== null;
            },

            getVersion() {
                let version;

                version = this.findRequirementVersion(this.getRequirements('roots', 'essential'), this.name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('roots', 'dev'), this.name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('dependencies', 'essential'), this.name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('dependencies', 'dev'), this.name);
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
