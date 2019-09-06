<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Laravel Ecosystem</h1>

        <div class="flex flex-wrap -mx-2">
            <div class="w-1/3">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Package
                            name="laravel/horizon"
                            title="Horizon"
                            icon="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                        ></Package>
                    </div>
                </div>
            </div>
            <div class="w-1/3">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Package
                            name="laravel/telescope"
                            title="Telescope"
                            icon="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                        ></Package>
                    </div>
                </div>
            </div>
            <div class="w-1/3">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Package
                            name="laravel/passport"
                            title="Passport"
                            icon="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                        ></Package>
                    </div>
                </div>
            </div>
            <div class="w-1/3">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Package
                            name="laravel/socialite"
                            title="Socialite"
                            icon="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                        ></Package>
                    </div>
                </div>
            </div>
            <div class="w-1/3">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Package
                            name="laravel/cashier"
                            title="Cashier"
                            icon="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                        ></Package>
                    </div>
                </div>
            </div>
            <div class="w-1/3">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Package
                            name="laravel/scout"
                            title="Scout"
                            icon="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                        ></Package>
                    </div>
                </div>
            </div>
            <div class="w-1/3">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Package
                            name="laravel/tinker"
                            title="Tinker"
                            icon="https://avatars.githubusercontent.com/u/5887416?s=250&v=4"
                        ></Package>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Package from '../../components/Package';

    export default {
        components: {
            Package,
        },

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

                return null;
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
