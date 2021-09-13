// https://next.vuex.vuejs.org/api/#state
const state = () => ({
  source: {
    data: {}
  },
  equipment: [],
  processes: [],
  template: null,
  location: null
});

// https://next.vuex.vuejs.org/api/#getters
const getters = {
  source(state) {
    return state.source;
  },
  equipment(state) {
    return state.equipment;
  },
  processes(state) {
    return state.processes;
  },
  template(state) {
    return state.template;
  },
  location(state) {
    return state.location;
  },
};

// https://next.vuex.vuejs.org/api/#actions
const actions = {
  reset(ctx) {
    ctx.commit('setTemplate', {
      template: null
    });

    ctx.commit('setLocation', {
      location: null
    });

    ctx.commit('setSourceData', {
      data: {}
    });

    ctx.commit('setEquipment', {
      equipment: []
    });

    ctx.commit('setProcesses', {
      processes: []
    });
  }
};

// https://next.vuex.vuejs.org/api/#mutations
const mutations = {
  setTemplate(state, payload) {
    state.template = payload.template;
  },

  setLocation(state, payload) {
    state.location = payload.location
  },

  setSourceData(state, payload) {
    state.source.data = payload.data;
  },

  setEquipment(state, payload) {
    state.equipment = payload.equipment;
  },

  setProcesses(state, payload) {
    state.processes = payload.processes;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};