import route from "../../../../vendor/tightenco/ziggy/src/js";

const _state = () => ({
  focusedMarker: null,
  selectedMarker: null,
  selectedMarkerColor: 'green',
  currentLinks: {},
  center: []
});

const getters = {
  selectedMarker: (state) => state.selectedMarker,
  selectedMarkerColor: (state) => state.selectedMarkerColor,
  currentLinks: (state) => state.currentLinks,
  center: (state) => state.center
};

const actions = {
  centerAt: () => { },
  refreshMap: () => { },
  selectMarker: ({ commit, state }, { marker, color }) => {
    commit("selectMarker", marker)
    commit("selectMarkerColor", color)
  },
  focusMarker: ({ commit }, marker) => { commit("focusMarker", marker) },
  unfocusMarker: ({ commit }) => { commit("focusMarker", null) },
  setLink: ({ commit }, { id, link }) => commit("setLink", { id, link }),
  unsetLink: ({ commit, state }, id) => {
    const links = state.currentLinks
    if (links[id]) commit("unsetLink", id)
  },
  setCenter: async ({ commit }, { center }) => {
    await window.axios.post(route('user.mapData.store'), {
      map: {
        center: center
      }
    });

    commit('setCenter', center);
  },
  getCenter: async ({ commit }) => {
    const res = await window.axios.get(route('user.mapData.index'));

    commit('setCenter', res.map.center);
  },
};

const mutations = {
  selectMarker: (state, marker) => state.selectedMarker = marker,
  selectMarkerColor: (state, color) => state.selectedMarkerColor = color,
  focusMarker: (state, marker) => state.focusedMarker = marker,
  setLink: (state, { id, link }) => state.currentLinks[id] = link,
  unsetLink: (state, id) => delete state.currentLinks[id],
  startLinks: (state) => state.currentLinks = {},
  setCenter: (state) => state.center
};


export default {
  namespaced: true,
  state: _state,
  getters,
  actions,
  mutations
};
