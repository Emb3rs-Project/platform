const _state = () => ({
    map: null,
    selectedMarker: null
});

const getters = {
    selectedMarker: (state) => state.selectedMarker
};

const actions = {
    // selectMarker: ({ commit, state }, marker) => {
    //     commit('doSelectMarker', marker);
    // }
};

const mutations = {
    doSelectMarker: (state, marker) => state.selectMarker = marker,
    setMap: (state, map) => state.map = map,
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
