const _state = () => ({
  currentRoute: null,
  currentRouteProps: null,
  slideOpen: false,
  filterOption: null,
  instances: [],
  links: [],
  showObject: {
    route: '',
    param: ''
  },
  notify: {
    title: '',
    text: '',
    type: ''
  }
});

const getters = {
  currentRoute: (state) => state.currentRoute,
  currentRouteProps: (state) => state.currentRouteProps,
  routeCheckSum: (state) => `${state.currentRoute}:${JSON.stringify(state.currentRouteProps)}`,
  slideOpen: (state) => state.slideOpen,
  filterOption: (state) => state.filterOption,
  instances: (state) => state.instances,
  links: (state) => state.links,
  notify: (state) => state.notify,
  showObject: (state) => state.showObject
};

const actions = {
  showSlide: ({ commit, state }, { route, props }) => {
    commit('closeSlide')
    if (state.currentRoute === route) {
      if (state.currentRouteProps !== props) commit('updateSlide', null)
      commit('openSlide')
    }
    commit('updateSlide', route)
    commit('updateSlideProps', props)
  },
  resetSlide: (ctx) => {
    ctx.commit('updateSlide', null);
    ctx.commit('updateSlideProps', null);
    ctx.commit('closeSlide');
  },
};

const mutations = {
  updateSlide: (state, route) => state.currentRoute = route,
  updateSlideProps: (state, props) => state.currentRouteProps = props,
  openSlide: (state) => state.slideOpen = true,
  closeSlide: (state) => state.slideOpen = false,
  setFilterOption: (state, payload) => state.filterOption = payload.filterOption,
  setInstances: (state, payload) => state.instances = payload.instances,
  setLinks: (state, payload) => state.links = payload.links,
  setNotify: (state, payload) => state.notify = payload,
  setShowObject: (state, payload) => state.showObject = payload,
};


export default {
  namespaced: true,
  state: _state,
  getters,
  actions,
  mutations
};
