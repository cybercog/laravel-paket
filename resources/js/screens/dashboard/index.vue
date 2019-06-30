<template>
    <div class="container mt-4">
        <h1>Paket</h1>

        <ul class="list-group list-group-flush mt-4">
            <li class="list-group-item">
                <strong>Paket Version:</strong>
                {{ getRequirementVersion('cybercog/laravel-paket') }}
            </li>
            <li class="list-group-item">
                <strong>Laravel Version:</strong>
                {{ getRequirementVersion('laravel/framework') }}
            </li>
        </ul>
        <h2 class="mt-4">Requirements</h2>
        <ul class="list-group list-group-flush mt-4">
            <li class="list-group-item">
                <strong>Root:</strong>
                {{ getRequirementsCount('essentials', 'root') }} essentials
                +
                {{ getRequirementsCount('dependencies', 'root') }} dependencies
            </li>
            <li class="list-group-item">
                <strong>Development:</strong>
                {{ getRequirementsCount('essentials', 'dev') }} essentials
                +
                {{ getRequirementsCount('dependencies', 'dev') }} dependencies
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

                version = this.findRequirementVersion(this.getRequirements('essentials', 'root'), name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('essentials', 'dev'), name);
                if (version !== null) {
                    return version;
                }

                version = this.findRequirementVersion(this.getRequirements('dependencies', 'root'), name);
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

                return results.length > 0 ? results[0].version : null;
            },
        },
    }
</script>
