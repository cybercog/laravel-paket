<template>
    <div class="container mt-4">
        <div class="d-flex mt-4">
            <h1>Requirements</h1>
        </div>

        <install-form class="mt-4"></install-form>

        <h2 class="mt-4">Essentials</h2>

        <h3 class="mt-4">Root</h3>
        <ul class="list-unstyled mb-0">
            <li class="mt-2" v-for="(requirement, index) in getRequirements('essentials', 'root')" v-bind:key="`${requirement.name}-${index}`">
                <requirement
                    :requirement="requirement"
                    :is-dependency="false"
                ></requirement>
            </li>
        </ul>

        <h3 class="mt-4">Development</h3>
        <ul class="list-unstyled mb-0">
            <li class="mt-2" v-for="(requirement, index) in getRequirements('essentials', 'dev')" v-bind:key="`${requirement.name}-${index}`">
                <requirement
                    :requirement="requirement"
                    :is-dependency="false"
                ></requirement>
            </li>
        </ul>

        <h2 class="mt-4">Dependencies</h2>

        <h3 class="mt-4">Root</h3>
        <ul class="list-unstyled mb-0">
            <li class="mt-2" v-for="(requirement, index) in getRequirements('dependencies', 'root')" v-bind:key="`${requirement.name}-${index}`">
                <requirement
                    :requirement="requirement"
                    :is-dependency="true"
                ></requirement>
            </li>
        </ul>

        <h3 class="mt-4">Development</h3>
        <ul class="list-unstyled mb-0">
            <li class="mt-2" v-for="(requirement, index) in getRequirements('dependencies', 'dev')" v-bind:key="`${requirement.name}-${index}`">
                <requirement
                    :requirement="requirement"
                    :is-dependency="true"
                ></requirement>
            </li>
        </ul>
    </div>
</template>

<script>
    import InstallForm from '../../components/Requirement/InstallForm';
    import Requirement from '../../components/Requirement/Requirement';

    export default {
        components: {
            InstallForm,
            Requirement,
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
        },
    }
</script>
