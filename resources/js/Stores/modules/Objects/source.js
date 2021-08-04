// https://next.vuex.vuejs.org/api/#state
const state = () => ({
  source: {
    data: {}
  },
  equipments: [],
  processes: [],
  scripts: [],
  template: null,
  location: null
});

// https://next.vuex.vuejs.org/api/#getters
const getters = {
  source(state) {
    return state.source;
  },
  equipments(state) {
    return state.equipments;
  },
  processes(state) {
    return state.processes;
  },
  scripts(state) {
    return state.scripts;
  },
  template(state) {
    return state.template;
  },
  location(state) {
    return state.location;
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
  setProcesses(ctx, payload) {
    ctx.commit('setProcesses', payload);
  },

  setScripts(ctx, payload) {
    ctx.commit('setScripts', payload);
  },

  setTemplate(ctx, payload) {
    ctx.commit('setTemplate', payload);
  },

  setLocation(ctx, payload) {
    ctx.commit('setLocation', payload);
  },
};

// https://next.vuex.vuejs.org/api/#mutations
const mutations = {
  setSourceData(state, payload) {
    // state.source.data = {}
    state.source.data = payload.data;
    // console.log("payload", payload);
  },

  // Equipments
  // key: e.id,
  // value: e.name,
  // parent: e.category_id,
  // props: e.template_properties,
  // data: {},
  setEquipments(state, payload) {
    state.equipments = payload.equipments;
  },

  setProcesses(state, payload) {
    state.processes = payload.processes;
  },

  setScripts(state, payload) {
    state.scripts = payload.scripts;
  },

  setTemplate(state, payload) {
    state.template = payload.template;
  },

  setLocation(state, payload) {
    state.location = payload.location
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
