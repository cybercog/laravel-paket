import Axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    isAutoRefreshingJobs: false,
    isInstallerLocked: false,
    installerCurrentJob: null,
    requirements: [],
    requirementSuggestions: [],
    repositories: [],
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
        // Need this pre-lock to work with `sync` queue
        context.commit('lockInstaller', payload);

        try {
            const response = await Axios.post(this.getters.getUrl('/api/jobs'), payload);
            context.commit('lockInstaller', response.data);
        } catch (exception) {
            context.commit('unlockInstaller');
        }

        this.dispatch('collectRequirements');
        this.dispatch('collectJobs');
    },

    async deleteJobs(context, payload) {
        await Axios.delete(this.getters.getUrl(`/api/jobs/${payload.id}`));
    },

    async collectRepositories() {
        const url = this.getters.getUrl('/api/repositories');

        try {
            const response = await Axios.get(url);

            if (response.status === 200) {
                this.state.repositories = response.data;
            }
        } catch (exception) {
            console.warn(`Cannot fetch ${url}`);
        }
    },

    async collectJobs() {
        const url = this.getters.getUrl('/api/jobs');

        try {
            const response = await Axios.get(url);

            if (response.status === 200) {
                this.state.jobs = response.data.reverse();
            }
        } catch (exception) {
            console.warn(`Cannot fetch ${url}`);
        }
    },

    async autoRefreshJobs(context) {
        if (context.isAutoRefreshingJobs) {
            return;
        }

        context.isAutoRefreshingJobs = true;

        setTimeout(async () => {
            await context.dispatch('collectJobs');

            if (context.getters.getActiveJobs().length > 0) {
                await context.dispatch('autoRefreshJobs');
            } else {
                await context.dispatch('collectRequirements');
                context.commit('unlockInstaller');
                context.isAutoRefreshingJobs = false;
            }
        }, 1000);
    },

    async collectRequirementSuggestions(context, payload) {
        const response = await Axios.get(`https://packagist.org/search.json?q=${payload.query}`);

        context.state.requirementSuggestions = response.data.results;
    },

    clearRequirementSuggestions(context) {
        context.state.requirementSuggestions = [];
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

    getRepositories: (state, getters) => () => {
        return state.repositories;
    },

    getJobs: (state, getters) => () => {
        return state.jobs;
    },

    getJob: (state, getters) => async (jobId) => {
        const url = getters.getUrl(`/api/jobs/${jobId}`);

        try {
            return await Axios.get(url);
        } catch (exception) {
            console.warn(`Cannot fetch ${url}`);
        }
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
