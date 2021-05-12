const _state = () => ({
    // count: 0
    currentRoute: null,
    currentRouteProps: null,
    slideOpen: false
});

const getters = {
    currentRoute: (state, getters, rootState) => state.currentRoute,
    currentRouteProps: (state) => state.currentRouteProps,
    slideOpen: (state) => state.slideOpen,
};

const actions = {
    showSlide: ({ commit, getters, dispatch, state }, { route, props }) => {
        commit('closeSlide')
        if (state.currentRoute === route) commit('openSlide')
        commit('updateSlide', route)
        commit('updateSlideProps', props)
    },
};

const mutations = {
    updateSlide: (state, route) => state.currentRoute = route,
    updateSlideProps: (state, props) => state.currentRouteProps = props,
    openSlide: (state) => state.slideOpen = true,
    closeSlide: (state) => state.slideOpen = false,
    setSlide: (state, status) => state.slideOpen = status
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
