<template>
    <div>
        <div class="flex">
            <div>
                <h4 class="text-gray-700 font-mono" v-text="requirement.name"></h4>
                <div class="mt-2">
                    <span class="bg-gray-200 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-3" v-text="requirement.version"></span>
                    <span class="bg-gray-200 px-2 py-1 text-sm font-semibold font-mono tracking-wide text-gray-700 mr-3" v-if="getLicense(requirement)" v-text="getLicense(requirement)"></span>
                </div>
            </div>
            <div class="ml-auto">
                <uninstall-button
                    class="btn btn-outline-danger"
                    :requirement="requirement"
                    v-if="isUninstallable()"
                ></uninstall-button>
            </div>
        </div>
        <suggestions
            :requirement="requirement"
        ></suggestions>
    </div>
</template>

<script>
    import Suggestions from './Suggestions';
    import UninstallButton from './UninstallButton';

    export default {
        components: {
            Suggestions,
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
