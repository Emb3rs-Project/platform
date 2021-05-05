// https://next.vuex.vuejs.org/api/#state
const state = () => ({
    source: {},
    equipments: [],
    processes: [],
    scripts: [],
    templateId: null,
});

// https://next.vuex.vuejs.org/api/#getters
const getters = {
    source(state, getters, rootState, rootGetters) {
        return state.source;
    },
    equipments(state, getters, rootState, rootGetters) {
        return state.equipments;
    },
    processes(state, getters, rootState, rootGetters) {
        return state.processes;
    },
    scripts(state, getters, rootState, rootGetters) {
        return state.scripts;
    },
    templateId(state, getters, rootState, rootGetters) {
        return state.templateId;
    },
};

// https://next.vuex.vuejs.org/api/#actions
const actions = {
    addSource(ctx, payload) {
        ctx.commit('addSource', payload);
    },
    removeSource(ctx) {
        ctx.commit('removeSource');
    },


    addEquipments(ctx, payload) {
        ctx.commit('addEquipment', payload);
    },
    removeEquipments(ctx) {
        ctx.commit('removeEquipments');
    },
    removeEquipment(ctx, payload) {
        ctx.commit('removeTemplate', payload);
    },


    addProcesses(ctx, payload) {
        ctx.commit('addProcesses', payload);
    },
    removeProcesses(ctx) {
        ctx.commit('removeProcesses');
    },
    removeProcess(ctx, payload) {
        ctx.commit('removeProcess', payload);
    },


    addScripts(ctx, payload) {
        ctx.commit('addScripts', payload);
    },
    removeScripts(ctx) {
        ctx.commit('removeScripts');
    },
    removeScript(ctx, payload) {
        ctx.commit('removeScript', payload);
    },

    addTemplateId(ctx, payload) {
        ctx.commit('addTemplateId', payload);
    },
    removeTemplateId(ctx) {
        ctx.commit('removeTemplateId');
    },
};

// https://next.vuex.vuejs.org/api/#mutations
const mutations = {
    addSource(state, payload) {
        state.source = payload.source;
    },
    removeSource(state) {
        state.source = {};
    },


    addEquipments(state, payload) {
        state.equipments = [payload.equipments];
    },
    removeEquipments(state) {
        state.equipments = [];
    },
    removeEquipment(state, payload) {
        state.equipments.splice(state.equipments.indexOf(payload.equipment), 1)
    },


    addProcesses(state, payload) {
        state.processes = [payload.processes];
    },
    removeProcesses(state) {
        state.processes = [];
    },
    removeProcess(state, payload) {
        state.processes.splice(state.processes.indexOf(payload.process), 1);
    },


    addScripts(state, payload) {
        state.scripts = [payload.scripts];
    },
    removeScripts(state) {
        state.scripts = [];
    },
    removeScript(state, payload) {
        state.scripts.splice(state.scripts.indexOf(payload.script), 1)
    },

    addTemplateId(state, payload) {
        state.templateId = payload.templateId;
    },
    removeTemplateId(state) {
        state.templateId = null;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
