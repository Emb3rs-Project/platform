const _state = () => ({
  unreadNotifications: [],
  notificationInterval: null,
  notificationInterval: null,
});

const getters = {
  unreadNotifications: (state) => state.unreadNotifications,
  unreadNotificationsCount: (state) => state.unreadNotificationsCount,

};

const actions = {
  checkForNewNotifications: (ctx) => {
    window.axios.get(route("notifications.new"))
      .then(({ data }) => {
        ctx.commit('setUnreadNotifications', {
          unreadNotifications: data.unreadNotifications
        });

        ctx.commit('setUnreadNotificationCount', {
          unreadNotificationsCount: data.unreadNotificationsCount
        });

      }).catch((e) => console.error(e));
  },
  watchForNewNotifications: (ctx) => {
    const watchFrequency = 5 * 1000;

    const watcher = setInterval(() => {
      window.axios.get(route("notifications.new"))
        .then(({ data }) => {
          if (data.unreadNotificationsCount !== ctx.state.unreadNotificationsCount) {
            ctx.commit('setUnreadNotifications', {
              unreadNotifications: data.unreadNotifications
            });

            ctx.commit('setUnreadNotificationCount', {
              unreadNotificationsCount: data.unreadNotificationsCount
            });
          }

        }).catch((e) => console.error(e));
    }, watchFrequency);


    ctx.commit('registerUnreadNotificationWatcher', {
      watcher: watcher
    });
  },
  stopWatchingForNewNotifications: (ctx) => {
    ctx.commit('unregisterUnreadNotificationWatcher');
  },
};

const mutations = {
  setUnreadNotifications: (state, payload) => {
    state.unreadNotifications = payload.unreadNotifications;
  },
  setUnreadNotificationCount: (state, payload) => {
    state.unreadNotificationsCount = payload.unreadNotificationsCount;
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
