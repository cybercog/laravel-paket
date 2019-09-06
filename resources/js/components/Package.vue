<template>
    <div class="flex align-top">
        <img
            class="w-10 h-10 rounded mr-4"
            src="https://avatars.githubusercontent.com/u/958072?s=200&v=4"
            alt=""
        />
        <div>
            <h4 class="text-gray-700 font-mono" v-text="title"></h4>
        </div>

        <div class="ml-auto text-right">
            <div v-if="isInstalled()">
                <uninstall-button
                    :requirement="{name: this.name}"
                ></uninstall-button>
                <div class="mt-2">
                    <span class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700" v-text="getVersion()"></span>
                </div>
            </div>
            <div v-if="!isInstalled()">
                <install-button
                    :requirement="{name: this.name}"
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
            icon: {
                type: String,
                required: false,
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
