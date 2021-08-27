const _state = () => ({
  currentRoute: null,
  currentRouteProps: null,
  slideOpen: false,
  filterOption: null,

  instances: [],
  links: [],
});

const getters = {
  currentRoute: (state, getters, rootState) => state.currentRoute,
  currentRouteProps: (state) => state.currentRouteProps,
  routeCheckSum: (state) => `${state.currentRoute}:${JSON.stringify(state.currentRouteProps)}`,
  slideOpen: (state) => state.slideOpen,
  filterOption: (state) => state.filterOption,

  instances: (state) => state.instances,
  links: (state) => state.links,
};

const actions = {
  showSlide: ({ commit, getters, dispatch, state }, { route, props }) => {
    commit('closeSlide')
    if (state.currentRoute === route) {
      if (state.currentRouteProps !== props) commit('updateSlide', null)
      commit('openSlide', null)
    }
    commit('updateSlide', route)
    commit('updateSlideProps', props)
  },
};

const mutations = {
  updateSlide: (state, route) => state.currentRoute = route,
  updateSlideProps: (state, props) => state.currentRouteProps = props,
  openSlide: (state) => state.slideOpen = true,
  closeSlide: (state) => state.slideOpen = false,
  setSlide: (state, status) => state.slideOpen = status,
  setFilterOption: (state, payload) => state.filterOption = payload.filterOption,

  setInstances: (state, payload) => state.instances = payload.instances,
  setLinks: (state, payload) => state.links = payload.links,
};


export default {
  namespaced: true,
  state: _state,
  getters,
  actions,
  mutations
};
