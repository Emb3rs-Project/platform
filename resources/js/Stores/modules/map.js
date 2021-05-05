const state = () => ({
    count: 0
});

const getters = {
    counter(state, getters, rootState) {
        return state.count;
    }
};

const actions = {
    increment({ commit, state }) {
        commit('doIncrement');
    }
};

const mutations = {
    doIncrement(state) {
        state.count++;
    }
};


export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
