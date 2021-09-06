const _state = () => ({
  show: false,
  type: "info",
  size: "small",
  message: "",
});

const getters = {
  show: (state) => state.show,
  type: (state) => state.type,
  size: (state) => state.size,
  message: (state) => state.message,
};

const actions = {
  show: (ctx, payload) => {
    ctx.commit('hide');

    ctx.commit('setType', { type: payload.type });
    ctx.commit('setSize', { size: payload.size });
    ctx.commit('setMessage', { message: payload.message });

    ctx.commit('show');

    // setTimeout(() => ctx.commit('hide'), 5000);
  },
};

const mutations = {
  hide: (state) => state.show = false,
  show: (state) => state.show = true,
  setType: (state, payload) => state.type = payload.type,
  setSize: (state, payload) => state.size = payload.size,
  setMessage: (state, payload) => state.message = payload.message,
};

export default {
  namespaced: true,
  state: _state,
  getters,
  actions,
  mutations
};
