const _state = () => ({
    focusedMarker: null,
    selectedMarker: null,
    selectedMarkerColor: 'green'
});

const getters = {
    selectedMarker: (state) => state.selectedMarker,
    selectedMarkerColor: (state) => state.selectedMarkerColor
};

const actions = {
    centerAt: () => { },
    refreshMap: () => { },
    selectMarker: ({ commit, state }, { marker, color }) => {
        commit("selectMarker", marker)
        commit("selectMarkerColor", color)
    },
    focusMarker: ({ commit }, marker) => { commit("focusMarker", marker) },
    unfocusMarker: ({ commit }) => { commit("focusMarker", null) }
};

const mutations = {
    selectMarker: (state, marker) => state.selectedMarker = marker,
    selectMarkerColor: (state, color) => state.selectedMarkerColor = color,
    focusMarker: (state, marker) => state.focusedMarker = marker
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
