<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Repositories</h1>

        <div v-if="getRepositories().length === 0" class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 mt-6 shadow" role="alert">
            <p class="font-bold">Repositories list is empty</p>
            <p>You can't install new packages without repositories!</p>
        </div>

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

        mounted() {
            this.fetchData();
        },

        methods: {
            async fetchData() {
                await this.$store.dispatch('collectRepositories');
            },

            getRepositories() {
                return this.$store.getters.getRepositories();
            },
        },
    }
</script>
