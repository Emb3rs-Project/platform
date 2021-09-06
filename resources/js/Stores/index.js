import { createLogger, createStore } from 'vuex';

import map from './modules/map';
import objects from './modules/objects';
import source from './modules/Objects/source';
import teamRoles from './modules/team-roles';
import notifications from './modules/notifications';
import snackbarNotifications from './modules/notifications/snackbar-notifications';

const inLocalMode = process.env.MIX_ENV !== 'production';

const loggerOptions = {
  collapsed: true,
  logActions: true, // Log Actions
  logMutations: true, // Log mutations
  logger: console, // implementation of the `console` API, default `console`
};

// add plugins that will go in prod, here
const plugins = [];

const store = createStore({
  strict: inLocalMode,
  plugins: inLocalMode
    //https://next.vuex.vuejs.org/guide/plugins.html#built-in-logger-plugin
    ? [...plugins, createLogger(loggerOptions)]
    : plugins,
  modules: {
    map,
    objects,
    source,
    teamRoles,
    notifications,
    snackbarNotifications
  },
});

export { store };
