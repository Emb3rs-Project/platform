const _state = () => ({
    // count: 0
    currentRoute: null,
    currentRouteProps: null,
    objectIndexOpen: true,
    slideOpen: false
});

const getters = {
    currentRoute: (state, getters, rootState) => state.currentRoute,
    currentRouteProps: (state) => state.currentRouteProps,
    slideOpen: (state) => state.slideOpen,
    indexOpen: (state) => state.objectIndexOpen
};

const actions = {
    goToSlideOver: ({ commit, getters, dispatch }, { route, props }) => {
        commit('updateSlide', route)
        commit('updateSlideProps', props)
        dispatch('openSlide')
    }
};

const mutations = {
    updateSlide: (state, route) => state.currentRoute = route,
    updateSlideProps: (state, props) => state.currentRouteProps = props,
    openSlide: (state) => state.slideOpen = true,
    closeSlide: (state) => state.slideOpen = false,
    updateIndex: (state, status) => state.objectIndexOpen = status
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
