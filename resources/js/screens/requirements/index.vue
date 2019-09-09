<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Requirements</h1>

        <install-form class="mt-4"></install-form>

        <h2 class="text-xl mt-6">Roots</h2>

        <div class="flex flex-wrap">
            <div class="w-1/2 pr-3">
                <h3 class="text-lg mt-4">Essential</h3>
                <div class="rounded overflow-hidden shadow mt-3 p-4" v-for="(requirement, index) in getRequirements('roots', 'essential')" v-bind:key="`${requirement.name}-${index}`">
                    <requirement
                        :requirement="requirement"
                        :is-dependency="false"
                    ></requirement>
                    <suggestions
                        :requirement="requirement"
                    ></suggestions>
                </div>
            </div>
            <div class="w-1/2 pl-3">
                <h3 class="text-lg mt-4">Development</h3>
                <div class="rounded overflow-hidden shadow mt-3 p-4" v-for="(requirement, index) in getRequirements('roots', 'dev')" v-bind:key="`${requirement.name}-${index}`">
                    <requirement
                        :requirement="requirement"
                        :is-dependency="false"
                    ></requirement>
                    <suggestions
                        :requirement="requirement"
                    ></suggestions>
                </div>
            </div>
        </div>

        <h2 class="text-xl mt-6">Dependencies</h2>

        <div class="flex flex-wrap">
            <div class="w-1/2 pr-3">
                <h3 class="text-lg mt-4">Essential</h3>
                <div class="rounded overflow-hidden shadow mt-3 p-4" v-for="(requirement, index) in getRequirements('dependencies', 'essential')" v-bind:key="`${requirement.name}-${index}`">
                    <requirement
                        :requirement="requirement"
                        :is-dependency="true"
                    ></requirement>
                    <suggestions
                        :requirement="requirement"
                    ></suggestions>
                </div>
            </div>
            <div class="w-1/2 pl-3">
                <h3 class="text-lg mt-4">Development</h3>
                <div class="rounded overflow-hidden shadow mt-3 p-4" v-for="(requirement, index) in getRequirements('dependencies', 'dev')" v-bind:key="`${requirement.name}-${index}`">
                    <requirement
                        :requirement="requirement"
                        :is-dependency="true"
                    ></requirement>
                    <suggestions
                        :requirement="requirement"
                    ></suggestions>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InstallForm from '../../components/Requirement/InstallForm';
    import Requirement from '../../components/Requirement/Requirement';
    import Suggestions from '../../components/Requirement/Suggestions';

    export default {
        components: {
            InstallForm,
            Requirement,
            Suggestions,
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
        },
    }
</script>
