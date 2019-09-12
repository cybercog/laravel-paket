<template>
    <div>
        <div>
            <span
                class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-2"
                v-text="type"
            ></span>
            <span v-text="getTitle()"></span>
        </div>
        <div>
            <div v-for="(option, optionName) in options">
                <div v-if="typeof option === 'object'" v-for="(optionValue, optionKey) in option" class="text-sm mt-4">
                    <span class="font-semibold">options.{{ optionName }}.{{ optionKey }}:</span> {{ optionValue }}
                </div>
                <div v-if="typeof option !== 'object'" class="text-sm mt-4">
                    <span class="font-semibold">options.{{ optionName }}:</span> {{ option }}
                </div>
            </div>
        </div>
        <div v-if="package">
            <div v-for="(distValue, distKey) in package.dist" class="text-sm mt-4">
                <span class="font-semibold">package.dist.{{ distKey }}:</span> {{ distValue }}
            </div>
            <div v-for="(sourceValue, sourceKey) in package.source" class="text-sm mt-4">
                <span class="font-semibold">package.source.{{ sourceKey }}:</span> {{ sourceValue }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            repository: {
                type: Object,
                required: true,
            },
        },

        mounted() {
            this.type = this.repository.type || null;
            this.url = this.repository.url || null;
            this.options = this.repository.options || [];
            this.package = this.repository.package || null;
        },

        data() {
            return {
                type: null,
                url: null,
                options: [],
                package: null,
            }
        },

        methods: {
            getTitle() {
                if (this.url !== null) {
                    return this.url;
                }

                if (this.package !== null) {
                    return `${this.package.name} ${this.getPackageVersion()}`;
                }

                return null;
            },

            getPackageVersion() {
                if (this.package.version.substr(0, 1) === 'v') {
                    return this.package.version;
                }

                return `v${this.package.version}`;
            },
        },
    }
</script>
