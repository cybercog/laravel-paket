<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Environment</h1>

        <div class="flex flex-wrap -mx-2">
            <div class="w-1/4 mx-2">
                <div class="rounded overflow-hidden shadow mt-6">
                    <div class="px-6 py-4">
                        <div class="text-lg flex items-center">
                            <img
                                class="w-10 h-10 rounded mr-4"
                                src="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                                alt=""
                            />
                            Paket
                            <span
                                class="px-2 py-1 text-xl font-semibold text-indigo-900"
                                v-text="getRequirementVersion('cybercog/laravel-paket')"
                            ></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/4 mx-2">
                <div class="rounded overflow-hidden shadow mt-6">
                    <div class="px-6 py-4">
                        <div class="text-lg flex items-center">
                            <img
                                class="w-10 h-10 rounded mr-4"
                                src="https://avatars.githubusercontent.com/u/958072?s=200&v=4"
                                alt=""
                            />
                            Laravel
                            <span
                                class="px-2 py-1 text-xl text-indigo-900 font-semibold"
                                v-text="getRequirementVersion('laravel/framework')"
                            ></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h1 class="text-2xl mt-6">Requirements</h1>

        <div class="flex flex-wrap -mx-2">
            <div class="w-1/4 mx-2">
                <div class="rounded overflow-hidden shadow mt-6">
                    <div class="px-6 py-4">
                        <div class="font-semibold text-lg">
                            Essential
                        </div>
                        <div class="text-gray-700 mt-2">
                            {{ getRequirementsCount('roots', 'essential') }} roots
                        </div>
                        <div class="text-gray-700 mt-2">
                            {{ getRequirementsCount('dependencies', 'essential') }} dependencies
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-1/4 mx-2">
                <div class="rounded overflow-hidden shadow mt-6">
                    <div class="px-6 py-4">
                        <div class="font-semibold text-lg">
                            Development
                        </div>

                        <div class="text-gray-700 mt-2">
                            {{ getRequirementsCount('roots', 'dev') }} roots
                        </div>
                        <div class="text-gray-700 mt-2">
                            {{ getRequirementsCount('dependencies', 'dev') }} dependencies
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
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
