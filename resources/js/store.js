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
        context.commit('runComposer');
        await Axios.post(this.getters.getUrl('/api/jobs'), payload);

        this.dispatch('collectRequirements');
        this.dispatch('collectJobs');
        context.commit('stopComposer');
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
        return window.location.origin + '/' + window.Paket.baseUri + uri;
    },

    getJobs: (state, getters) => () => {
        return state.jobs;
    },

    getJob: (state, getters) => (jobId) => {
        return Axios.get(getters.getUrl(`/api/jobs/${jobId}`));
    },

    getRequirementJobs: (state, getters) => (requirement) => {
        return getters
            .getJobs()
            .filter(job => requirement && job.requirement && job.requirement.name === requirement.name);
    },

    getRequirementActiveJobs: (state, getters) => (requirement) => {
        return getters
            .getRequirementJobs(requirement)
            .filter(job => job.status === 'Pending' || job.status === 'Running');
    },

    getActiveJobs: (state, getters) => (requirement) => {
        return getters
            .getJobs()
            .filter(job => job.status === 'Pending' || job.status === 'Running');
    },
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
});
