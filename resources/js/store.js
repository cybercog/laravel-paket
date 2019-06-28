import Axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    isComposerBusy: false,
    requirements: [],
    jobs: [],
    protectedRequirements: [
        'php',
        'cybercog/laravel-paket',
        'laravel/framework',
    ],
};

const mutations = {
    runComposer(state) {
        state.isComposerBusy = true;
    },

    stopComposer(state) {
        state.isComposerBusy = false;
    },
};

const actions = {
    async collectRequirements() {
        const response = await Axios.get(`/paket/api/requirements`);

        this.state.requirements = response.data;
    },

    async postRequirements(context, payload) {
        await Axios.post(`/paket/api/requirements`, payload);

        this.dispatch('collectRequirements');
    },

    async deleteRequirements(context, id) {
        await Axios.delete(`/paket/api/requirements/${id}`);

        this.dispatch('collectRequirements');
    },

    async collectJobs() {
        const response = await Axios.get(`/paket/api/jobs`);

        this.state.jobs = response.data.reverse();
    },
};

const getters = {
    isRequirementInstalled: (state) => (name) => {
        return state.requirements['essentials']['core'].filter(requirement => requirement.name === name).length > 0
            || state.requirements['essentials']['dev'].filter(requirement => requirement.name === name).length > 0
            || state.requirements['dependencies']['core'].filter(requirement => requirement.name === name).length > 0
            || state.requirements['dependencies']['dev'].filter(requirement => requirement.name === name).length > 0;
    },

    isNotProtectedRequirement: (state) => (name) => {
        return state.protectedRequirements.indexOf(name) === -1;
    },
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
});
