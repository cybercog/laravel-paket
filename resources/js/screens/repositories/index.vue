<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Repositories</h1>

        <div class="rounded overflow-hidden shadow mt-3 p-4" v-for="repository in getRepositories()">
            <span
                class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-3"
                v-text="repository.type"
            ></span>
            {{ repository.url }}
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            getRepositories() {
                return this.$store.getters.getRepositories();
            },
        },
    }
</script>
