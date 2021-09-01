import route from "../../../../vendor/tightenco/ziggy/src/js";


const _state = () => ({
  focusedMarker: null,
  selectedMarker: null,
  selectedMarkerColor: 'green',
  currentLinks: {},
  center: [],
  zoom: null,
});

const getters = {
  selectedMarker: (state) => state.selectedMarker,
  selectedMarkerColor: (state) => state.selectedMarkerColor,
  currentLinks: (state) => state.currentLinks,
  center: (state) => state.center,
  zoom: (state) => state.zoom,
};

const actions = {
  centerAt: () => { },
  refreshMap: ({ dispatch }) => {
    // DO LOGIC HERE
    // GET INFO FROM DB or FROM ANOTHER STATE OR WTV
    // THEN TRIGGER THE doREFRESHMAP!
    console.log("refreshMap");

    dispatch('doRefreshMap')
  },
  doRefreshMap: () => {
    console.log("doRefreshMap");

  },
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
  getCenter: async ({ commit }) => {
    const res = await window.axios.get(route('user.mapData.index')).then(({ data }) => data);

    if (res[0].data.map.center) commit('setCenter', res[0].data.map.center);
  },
  getZoom: async ({ commit }) => {
    const res = await window.axios.get(route('user.mapData.index')).then(({ data }) => data);

    if (res[0].data.map.zoom) commit('setZoom', res[0].data.map.zoom);
  },
  setData: async (ctx, payload) => {
    const map = {};

    if (payload.center) {
      map.center = payload.center;
      ctx.commit('setCenter', payload.center);
    }

    if (payload.zoom) {
      map.zoom = payload.zoom;
      ctx.commit('setZoom', payload.zoom);
    }

    await window.axios.post(route('user.mapData.store'), { map });
  },
};

const mutations = {
  selectMarker: (state, marker) => state.selectedMarker = marker,
  selectMarkerColor: (state, color) => state.selectedMarkerColor = color,
  focusMarker: (state, marker) => state.focusedMarker = marker,
  setLink: (state, { id, link }) => state.currentLinks[id] = link,
  unsetLink: (state, id) => delete state.currentLinks[id],
  startLinks: (state) => state.currentLinks = {},
  setCenter: (state, center) => state.center = [center.lat, center.lng],
  setZoom: (state, zoom) => state.zoom = zoom
};


export default {
  namespaced: true,
  state: _state,
  getters,
  actions,
  mutations
};
