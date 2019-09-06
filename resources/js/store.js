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
        const response = await Axios.get(this.getters.getUrl('/api/requirements'));

        this.state.requirements = response.data;
    },

    async postJobs(context, payload) {
        await Axios.post(this.getters.getUrl('/api/jobs'), payload);

        this.dispatch('collectRequirements');
    },

    async deleteJobs(context, payload) {
        await Axios.delete(this.getters.getUrl(`/api/jobs/${payload.id}`));
    },

    async collectJobs() {
        const response = await Axios.get(this.getters.getUrl('/api/jobs'));

        this.state.jobs = response.data.reverse();
    },
};

const getters = {
    isRequirementInstalled: (state) => (name) => {
        return state.requirements['roots']['essential'].filter(requirement => requirement.name === name).length > 0
            || state.requirements['roots']['dev'].filter(requirement => requirement.name === name).length > 0
            || state.requirements['dependencies']['essential'].filter(requirement => requirement.name === name).length > 0
            || state.requirements['dependencies']['dev'].filter(requirement => requirement.name === name).length > 0;
    },

    isNotProtectedRequirement: (state) => (name) => {
        return state.protectedRequirements.indexOf(name) === -1;
    },

    getUrl: () => (uri) => {
        return '/' + window.Paket.baseUri + uri;
    },

    getJob: (state, getters) => (jobId) => {
        return Axios.get(getters.getUrl(`/api/jobs/${jobId}`));
    },
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
});
