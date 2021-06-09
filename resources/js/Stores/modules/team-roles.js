const _state = () => ({
    roles: []
});

const getters = {
    roles: (state) => state.roles
};

const actions = {
    createRole: async (ctx, { role, permissions }) => {
        try {
            const response = await axios.post(route('team-roles.store'), { role, permissions });

            ctx.commit('addRole', { role: response.data.role });
        } catch (error) {
            console.log(error.response.data)
        }
    },
    getRoles: async (ctx) => {
        try {
            const response = await axios.get(route('team-roles.index'));

            // console.log(response.data.roles);

            ctx.commit('addRoles', { roles: response.data.roles });
        } catch (error) {
            console.log(error.response.data)
        }
    },
};

const mutations = {
    addRole(state, payload) {
        state.roles = [...state.roles, payload.role];
    },
    addRoles(state, payload) {
        state.roles = payload.roles;
    },
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
