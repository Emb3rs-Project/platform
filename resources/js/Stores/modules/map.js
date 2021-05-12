const _state = () => ({
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
    }
};

const mutations = {
    selectMarker: (state, marker) => state.selectedMarker = marker,
    selectMarkerColor: (state, color) => state.selectedMarkerColor = color,
    setMap: (state, payload) => state.map = payload.map,
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
