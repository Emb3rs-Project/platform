const _state = () => ({
    role: null
});

const getters = {
    role: (state) => state.role
};

const actions = {
    createRole: async (ctx, {role, permissions}) => {
        try {
            const response = await axios.post(route('team-roles.store'), {
                role: role,
                permissions: permissions
            });

            ctx.commit('addRole', {role: response.data.role});
            return {error: false}
        } catch (error) {
            console.log(error.response.data);
            return {error: true, ...error.response.data};
        }
    },
};

const mutations = {
    addRole (state, payload) {
        state.role = payload.role;
    },
};


export default {
    namespaced: true,
    state: _state,
    getters,
    actions,
    mutations
};
