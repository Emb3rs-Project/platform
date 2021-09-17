import route from "../../../../vendor/tightenco/ziggy/src/js";

const _state = () => ({
  focusedMarker: null,
  selectedMarker: null,
  selectedMarkerColor: 'green',
  currentLinks: {},

  center: [],
  zoom: null,
  defaultLocation: []
});

const getters = {
  selectedMarker: (state) => state.selectedMarker,
  selectedMarkerColor: (state) => state.selectedMarkerColor,
  currentLinks: (state) => state.currentLinks,
  center: (state) => state.center,
  zoom: (state) => state.zoom,
  defaultLocation: (state) => state.defaultLocation,
};

const actions = {
  centerAt: () => { },
  refreshMap: ({ dispatch }) => {
    // DO LOGIC HERE
    // GET INFO FROM DB or FROM ANOTHER STATE OR WTV
    // THEN TRIGGER THE doREFRESHMAP!

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
  setZoom: (ctx, payload) => {
    const zoom = payload.zoom;

    window.axios.post(route("user.mapData.store"), {
      map: {
        zoom: zoom,
      },
    }).then(() => {
      ctx.commit('setZoom', zoom)
    }).catch((error) => {
      console.error("map.js Module", error);
    });
  },
  setCenter: (ctx, payload) => {
    const center = payload.center;

    window.axios.post(route("user.mapData.store"), {
      map: {
        center: center,
      },
    }).then(() => {
      ctx.commit('setCenter', center)
    }).catch((error) => {
      console.error("map.js Module", error);
    });
  },
  setDefaultLocation: async (ctx, payload) => {
    const location = payload.defaultLocation;

    window.axios.post(route("user.mapData.store"), {
      map: {
        defaultLocation: location
      }
    }).then(() => {
      ctx.commit('setDefaultLocation', location);
    }).catch((error) => {
      console.error("map.js Module", error);
    });
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
  setZoom: (state, zoom) => state.zoom = zoom,
  setDefaultLocation: (state, location) => state.defaultLocation = [location.lat, location.lng],
};


export default {
  namespaced: true,
  state: _state,
  getters,
  actions,
  mutations
};
