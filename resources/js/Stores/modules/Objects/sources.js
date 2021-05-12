// https://next.vuex.vuejs.org/api/#state
const state = () => ({
    source: {},
    equipments: [],
    processes: [],
    scripts: [],
    template: null,
    location: null
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
    template(state, getters, rootState, rootGetters) {
        return state.template;
    },
    form(state) {
        return {
            source: state.source,
            equipments: state.equipments,
            processes: state.processes,
            template_id: state.template.key,
            location_id: state.location.key
        }
    }
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
        ctx.commit('addEquipments', payload);
    },
    addEquipment(ctx, payload) {
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
    addProcess(ctx, payload) {
        ctx.commit('addProcess', payload);
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

    addTemplate(ctx, payload) {
        ctx.commit('addTemplate', payload);
    },
    removeTemplate(ctx) {
        ctx.commit('removeTemplate');
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

    // Equipments
    // key: e.id,
    // value: e.name,
    // parent: e.category_id,
    // props: e.template_properties,
    // data: {},
    addEquipments(state, payload) {
        state.equipments = payload.equipments;
    },
    addEquipment(state, payload) {
        state.equipments = [...state.equipments, payload.equipment];
    },
    removeEquipments(state) {
        state.equipments = [];
    },
    removeEquipment(state, payload) {
        state.equipments.splice(state.equipments.indexOf(payload.equipment), 1)
    },


    addProcesses(state, payload) {
        state.processes = payload.processes;
    },
    addProcess(state, payload) {
        state.processes = [...state.processes, payload.process];
    },
    removeProcesses(state) {
        state.processes = [];
    },
    removeProcess(state, payload) {
        state.processes.splice(state.processes.indexOf(payload.process), 1);
    },


    addScripts(state, payload) {
        state.scripts = payload.scripts;
    },
    removeScripts(state) {
        state.scripts = [];
    },
    removeScript(state, payload) {
        state.scripts.splice(state.scripts.indexOf(payload.script), 1)
    },

    addTemplate(state, payload) {
        state.template = payload.template;
    },
    removeTemplate(state) {
        state.template = null;
    },

    setLocation(state, payload) {
        state.location = payload
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
