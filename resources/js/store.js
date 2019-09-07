import Axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    isInstallerLocked: false,
    installerCurrentJob: null,
    requirements: [],
    jobs: [],
    protectedRequirements: [
        'php',
        'cybercog/laravel-paket',
        'laravel/framework',
    ],
};

const mutations = {
    lockInstaller(state, job) {
        state.isInstallerLocked = true;
        state.installerCurrentJob = job;
    },

    unlockInstaller(state) {
        state.isInstallerLocked = false;
        state.installerCurrentJob = null;
    },
};

const actions = {
    async collectRequirements() {
        const response = await Axios.get(this.getters.getUrl('/api/requirements'));

        this.state.requirements = response.data;
    },

    async postJobs(context, payload) {
        context.commit('lockInstaller', payload);

        await Axios.post(this.getters.getUrl('/api/jobs'), payload);

        this.dispatch('collectRequirements');
        this.dispatch('collectJobs');

        // TODO: Move to JobHasBeenTerminated event listener
        context.commit('unlockInstaller');
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

    isProcessingRequirement: (state, getters) => (requirement) => {
        if (state.installerCurrentJob === null) {
            return false;
        }

        return state.installerCurrentJob.requirement.name === requirement.name;
    },
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
});
