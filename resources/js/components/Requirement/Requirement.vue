<template>
    <div class="flex">
        <div>
            <h4 class="text-gray-700 font-mono" v-text="requirement.name"></h4>
            <div class="mt-2">
                <span class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-2" v-text="requirement.version"></span>
                <span class="bg-gray-200 border-b-2 border-gray-400 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-2" v-if="getLicense(requirement)" v-text="getLicense(requirement)"></span>
            </div>
        </div>
        <div class="ml-auto">
            <uninstall-button
                :requirement="requirement"
                v-if="isUninstallable()"
            ></uninstall-button>
        </div>
    </div>
</template>

<script>
    import UninstallButton from './UninstallButton';

    export default {
        components: {
            UninstallButton,
        },

        props: {
            requirement: {
                type: Object,
                required: true,
            },
            isDependency: {
                type: Boolean,
                required: false,
                default: false,
            },
        },

        methods: {
            getLicense(requirement) {
                if (typeof requirement.license === 'undefined') {
                    return null;
                }

                const {
                    license
                } = requirement;

                if (license.length === 0) {
                    return '';
                }

                return license[0];
            },

            isUninstallable() {
                if (this.isDependency) {
                    return false;
                }

                return this.$store.getters.isNotProtectedRequirement(this.requirement.name);
            },
        },
    }
</script>
