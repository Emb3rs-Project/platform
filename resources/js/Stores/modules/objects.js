const _state = () => ({
    // count: 0
    currentSlideOver: null,
    currentSlideOverProps: null
});

const getters = {
    currentRoute: (state, getters, rootState) => state.currentSlideOver,
    currentRouteProps: (state) => state.currentSlideOverProps
};

const actions = {
    goToSlideOver: ({ commit }, { route, props }) => {
        commit('updateSlideOver', route)
        commit('updateSlideProps', props)
    }
};

const mutations = {
    updateSlideOver: (state, route) => state.currentSlideOver = route,
    updateSlideProps: (state, props) => state.currentSlideOverProps = props,
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
