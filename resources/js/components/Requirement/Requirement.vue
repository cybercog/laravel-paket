<template>
    <div class="card">
        <div class="card-body d-flex">
            <div>
                <h4 class="mb-0 text-muted">{{ requirement.name }}<span class="ml-2">:{{ requirement.version }}</span></h4>
                <span class="badge">{{ getLicense(requirement) }}</span>
                <div>
                    <suggestions
                        :requirement="requirement"
                    ></suggestions>
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
