// https://next.vuex.vuejs.org/api/#state
const state = () => ({
  source: {
    data: {}
  },
  equipment: [],
  processes: [],
  template: null,
  location: null,
  withAdvancedProperties: false
});

// https://next.vuex.vuejs.org/api/#getters
const getters = {
  source: (state) => state.source,
  equipment: (state) => state.equipment,
  processes: (state) => state.processes,
  template: (state) => state.template,
  location: (state) => state.location,
  withAdvancedProperties: (state) => state.withAdvancedProperties,
};

// https://next.vuex.vuejs.org/api/#actions
const actions = {
  reset: (ctx) => {
    ctx.commit('setTemplate', { template: null });
    ctx.commit('setLocation', { location: null });
    ctx.commit('setSourceData', { data: {} });
    ctx.commit('setEquipment', { equipment: [] });
    ctx.commit('setProcesses', { processes: [] });
    ctx.commit('setAdvancedPropertiesOption', { withAdvancedProperties: false });
  }
};

// https://next.vuex.vuejs.org/api/#mutations
const mutations = {
  setTemplate: (state, payload) => state.template = payload.template,
  setLocation: (state, payload) => state.location = payload.location,
  setSourceData: (state, payload) => state.source.data = payload.data,
  setEquipment: (state, payload) => state.equipment = payload.equipment,
  setProcesses: (state, payload) => state.processes = payload.processes,
  setAdvancedPropertiesOption: (state, payload) => state.withAdvancedProperties = payload.withAdvancedProperties
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
