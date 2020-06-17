<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Laravel Ecosystem</h1>

        <div class="flex flex-wrap -mx-2">
            <div class="w-1/3" v-for="paket in pakets">
                <div class="rounded overflow-hidden shadow mt-6 mx-2">
                    <div class="px-6 py-4">
                        <paket-card :paket="paket"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PaketCard from '../../components/PaketCard';

    export default {
        components: {
            PaketCard,
        },

        data() {
            return {
                pakets: [
                    {
                        name: 'laravel/horizon',
                        title: 'Horizon',
                        description: 'Queue Monitoring',
                        icon: 'https://laravel.com/img/ecosystem/horizon.min.svg',
                        iconBg: '#8c6ed3',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/telescope',
                        title: 'Telescope',
                        description: 'Debug Assistant',
                        icon: 'https://laravel.com/img/ecosystem/telescope.min.svg',
                        iconBg: '#4040c8',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/scout',
                        title: 'Scout',
                        description: 'Full-Text Search',
                        icon: 'https://laravel.com/img/ecosystem/scout.min.svg',
                        iconBg: '#f55d5c',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/passport',
                        title: 'Passport',
                        description: 'OAuth Server',
                        iconBg: '#7dd9f2',
                        icon: 'https://laravel.com/img/ecosystem/passport.min.svg',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/sanctum',
                        title: 'Sanctum',
                        description: 'Authentication for SPAs',
                        iconBg: '#28cef0',
                        icon: 'https://laravel.com/img/ecosystem/passport.min.svg',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/socialite',
                        title: 'Socialite',
                        description: 'OAuth Authentication',
                        icon: 'https://laravel.com/img/ecosystem/socialite.min.svg',
                        iconBg: '#e394ba',
                        isDevelopment: false,
                    },
                    {
                        name: 'laravel/dusk',
                        title: 'Dusk',
                        description: 'Browser Testing and Automation',
                        icon: 'https://laravel.com/img/ecosystem/dusk.min.svg',
                        iconBg: '#bb358b',
                        isDevelopment: true,
                    },
                    {
                        name: 'laravel/tinker',
                        title: 'Tinker',
                        description: 'Interactive REPL',
                        icon: 'https://laravel.com/img/ecosystem/tinker.min.svg',
                        iconBg: '#ec7658',
                        isDevelopment: false,
                    },
                ],
            };
        },

        methods: {
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
