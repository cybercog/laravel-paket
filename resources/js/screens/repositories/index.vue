<template>
    <div class="container mx-auto mt-6">
        <h1 class="text-2xl">Repositories</h1>

        <div class="rounded overflow-hidden shadow mt-3 p-4" v-for="repository in getRepositories()">
            <span
                class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-2"
                v-text="repository.type"
            ></span>
            <span>{{ getTitle(repository) }}</span>
            <div>
                <div v-for="(option, optionName) in repository.options">
                    <div v-if="typeof option === 'object'" v-for="(optionValue, optionKey) in option" class="text-sm mt-4">
                        <span class="font-semibold">options.{{ optionName }}.{{ optionKey }}:</span> {{ optionValue }}
                    </div>
                    <div v-if="typeof option !== 'object'" class="text-sm mt-4">
                        <span class="font-semibold">options.{{ optionName }}:</span> {{ option }}
                    </div>
                </div>
            </div>
            <div v-if="repository.package">
                <div v-for="(distValue, distKey) in repository.package.dist" class="text-sm mt-4">
                    <span class="font-semibold">dist.{{ distKey }}:</span> {{ distValue }}
                </div>
            </div>
            <div v-if="repository.package">
                <div v-for="(sourceValue, sourceKey) in repository.package.source" class="text-sm mt-4">
                    <span class="font-semibold">source.{{ sourceKey }}:</span> {{ sourceValue }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            getRepositories() {
                return this.$store.getters.getRepositories();
            },

            getTitle(repository) {
                if (repository.package) {
                    return `${repository.package.name} v${repository.package.version}`;
                }

                return repository.url;
            },
        },
    }
</script>
