<template>
    <div class="container mx-auto">
        <div class="rounded overflow-hidden shadow-lg mt-6">
            <h2 class="text-xl px-6 py-3 bg-gray-200">Environment</h2>
            <div class="flex flex-wrap">
                <div class="px-6 py-4">
                    <div class="font-bold text-lg">
                        Laravel
                        <span class="bg-gray-200 px-2 py-1 text-sm font-semibold text-gray-700">{{ getRequirementVersion('laravel/framework') }}</span>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="font-bold text-lg">
                        Paket
                        <span class="bg-gray-200 px-2 py-1 text-sm font-semibold text-gray-700">{{ getRequirementVersion('cybercog/laravel-paket') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="rounded overflow-hidden shadow-lg mt-6">
            <h2 class="text-xl px-6 py-3 bg-gray-200">Requirements</h2>
            <div class="flex flex-wrap">
                <div class="px-6 py-4">
                    <div class="font-bold text-lg">
                        Essential
                        <span class="bg-gray-200 px-2 py-1 text-sm font-semibold text-gray-700">{{ getRequirementsCount('roots', 'essential') }} roots
                        +
                        {{ getRequirementsCount('dependencies', 'essential') }} dependencies</span>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="font-bold text-lg">
                        Development
                        <span class="bg-gray-200 px-2 py-1 text-sm font-semibold text-gray-700">{{ getRequirementsCount('roots', 'dev') }} roots
                        +
                        {{ getRequirementsCount('dependencies', 'dev') }} dependencies</span>
                    </div>
                </div>
            </div>
        </div>


        <ul class="list-group list-group-flush mt-4">
            <li class="list-group-item">

            </li>
            <li class="list-group-item">

            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data() {
            return {};
        },

        mounted() {
            this.fetchData();
        },

        methods: {
            async fetchData() {
                await this.$store.dispatch('collectRequirements');
            },

            getRequirementVersion(name) {
                let version;

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

                return 'Not installed';
            },

            getRequirementsCount(level, environment) {
                return this.getRequirements(level, environment).length;
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
