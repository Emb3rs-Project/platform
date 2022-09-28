// https://next.vuex.vuejs.org/api/#state
const state = () => ({
  source: {
    data: {}
  },
  equipment: [],
  selectedEquipment: [],
  selectedProcesses: [],
  processes: [],
  template: null,
  location: null,
  withAdvancedProperties: null,
  additionalStreams: []
});

// https://next.vuex.vuejs.org/api/#getters
const getters = {
  source: (state) => state.source,
  equipment: (state) => state.equipment,
  selectedEquipment: (state) => state.selectedEquipment,
  processes: (state) => state.processes,
  selectedProcesses: (state) => state.selectedProcesses,
  template: (state) => state.template,
  location: (state) => state.location,
  withAdvancedProperties: (state) => state.withAdvancedProperties,
  additionalStreams: (state) => state.additionalStreams,
};

// https://next.vuex.vuejs.org/api/#actions
const actions = {
  reset: (ctx) => {
    ctx.commit('setTemplate', { template: null });
    ctx.commit('setLocation', { location: null });
    ctx.commit('setSourceData', { data: {} });
    ctx.commit('setEquipment', { equipment: [] });
    ctx.commit('setSelectedEquipment', { selectedEquipment: [] });
    ctx.commit('setProcesses', { processes: [] });
    ctx.commit('setSelectedProcesses', { selectedProcesses: [] });
    ctx.commit('setAdvancedPropertiesOption', { withAdvancedProperties: null });
    ctx.commit('setAdditionalStreams', { additionalStreams: [] });
  }
};

// https://next.vuex.vuejs.org/api/#mutations
const mutations = {
  setTemplate: (state, payload) => state.template = payload.template,
  setLocation: (state, payload) => state.location = payload.location,
  setSourceData: (state, payload) => state.source.data = payload.data,
  setEquipment: (state, payload) => state.equipment = payload.equipment,
  setSelectedEquipment: (state, payload) => state.selectedEquipment = payload.selectedEquipment,
  setProcesses: (state, payload) => state.processes = payload.processes,
  setSelectedProcesses: (state, payload) => state.selectedProcesses = payload.selectedProcesses,
  setAdvancedPropertiesOption: (state, payload) => state.withAdvancedProperties = payload.withAdvancedProperties,
  setAdditionalStreams: (state, payload) => state.additionalStreams = payload.additionalStreams,
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
