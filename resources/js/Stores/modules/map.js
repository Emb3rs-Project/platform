const _state = () => ({
    selectedMarker: null
});

const getters = {
    selectedMarker: (state) => state.selectedMarker,
};

const actions = {
    centerAt: () => { }
};

const mutations = {
    selectMarker: (state, marker) => state.selectedMarker = marker,
    setMap: (state, payload) => state.map = payload.map,
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
