<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Laravel Ecosystem</h1>

        <div class="flex flex-wrap -mx-2">
            <div class="w-1/3" v-for="paket in pakets">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <Paket
                            :name="paket.name"
                            :title="paket.title"
                            :description="paket.description"
                            :icon="paket.icon"
                            :isDevelopment="paket.isDevelopment"
                        ></Paket>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Paket from '../../components/Paket';

    export default {
        components: {
            Paket,
        },

        data() {
            return {
                pakets: [
                    {
                        name: 'laravel/horizon',
                        title: 'Horizon',
                        description: 'Queue Monitoring',
                        icon: 'https://avatars.githubusercontent.com/u/5887416?s=250&v=4',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/telescope',
                        title: 'Telescope',
                        description: 'Debug Assistant',
                        icon: 'https://avatars.githubusercontent.com/u/5887416?s=250&v=4',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/passport',
                        title: 'Passport',
                        description: 'OAuth Server',
                        icon: 'https://avatars.githubusercontent.com/u/5887416?s=250&v=4',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/socialite',
                        title: 'Socialite',
                        description: 'OAuth Authentication',
                        icon: 'https://avatars.githubusercontent.com/u/5887416?s=250&v=4',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/scout',
                        title: 'Scout',
                        description: 'Full-Text Search',
                        icon: 'https://avatars.githubusercontent.com/u/5887416?s=250&v=4',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/dusk',
                        title: 'Dusk',
                        description: 'Browser Testing and Automation',
                        icon: 'https://avatars.githubusercontent.com/u/5887416?s=250&v=4',
                        isDevelopment: true,
                    },
                    {
                        name: 'laravel/tinker',
                        title: 'Tinker',
                        description: 'Interactive REPL',
                        icon: 'https://avatars.githubusercontent.com/u/5887416?s=250&v=4',
                        isDevelopment: false,
                    },
                ],
            };
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
