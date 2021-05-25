const _state = () => ({
    focusedMarker: null,
    selectedMarker: null,
    selectedMarkerColor: 'green',
    currentLinks: {}
});

const getters = {
    selectedMarker: (state) => state.selectedMarker,
    selectedMarkerColor: (state) => state.selectedMarkerColor,
    currentLinks: (state) => state.currentLinks
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
    }
};

const mutations = {
    selectMarker: (state, marker) => state.selectedMarker = marker,
    selectMarkerColor: (state, color) => state.selectedMarkerColor = color,
    focusMarker: (state, marker) => state.focusedMarker = marker,
    setLink: (state, { id, link }) => state.currentLinks[id] = link,
    unsetLink: (state, id) => delete state.currentLinks[id],
    startLinks: (state) => state.currentLinks = {}
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
