const _state = () => ({
  unreadNotificationCount: 0,
  notificationInterval: null,
});

const getters = {
  unreadNotificationCount: (state) => state.unreadNotificationCount,

};

const actions = {
  checkForNewNotifications: async (ctx) => {
    try {
      const response = await window.axios.get(route("notifications.new"));

      if (response.data.unreadNotificationCount) {
        ctx.commit('updateUnreadNotificationCount', {
          unreadNotificationCount: response.data.unreadNotificationCount
        });
      }
    } catch (error) {
      console.log(error.response.data)
    }

  },
  watchForNewNotifications: async (ctx) => {
    const watchFrequency = 5 * 1000;

    const watcher = setInterval(async () => {
      try {
        const response = await window.axios.get(
          route("notifications.new")
        );

        if (response.data.unreadNotificationCount !== ctx.state.unreadNotificationCount) {
          ctx.commit('updateUnreadNotificationCount', {
            unreadNotificationCount: response.data.unreadNotificationCount
          });
        }
      } catch (error) {
        console.error(error)
      }
    }, watchFrequency);


    ctx.commit('registerUnreadNotificationWatcher', {
      watcher: watcher
    });
  },
  stopWatchingForNewNotifications: async (ctx) => {
    ctx.commit('unregisterUnreadNotificationWatcher');
  },
};

const mutations = {
  updateUnreadNotificationCount: (state, payload) => {
    state.unreadNotificationCount = payload.unreadNotificationCount;
  },
  registerUnreadNotificationWatcher: (state, payload) => {
    state.notificationInterval = payload.watcher;
  },
  unregisterUnreadNotificationWatcher: (state) => {
    clearInterval(state.notificationInterval);
  },
};

export default {
  namespaced: true,
  state: _state,
  getters,
  actions,
  mutations
};
