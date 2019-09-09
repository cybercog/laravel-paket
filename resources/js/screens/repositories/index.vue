<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Repositories</h1>

        <div v-for="repository in getRepositories()" class="rounded overflow-hidden shadow mt-3 p-4">
            <repository :repository="repository"/>
        </div>
    </div>
</template>

<script>
    import Repository from '../../components/Repository/Repository';

    export default {
        components: {
            Repository,
        },

        methods: {
            getRepositories() {
                return this.$store.getters.getRepositories();
            },
        },
    }
</script>
