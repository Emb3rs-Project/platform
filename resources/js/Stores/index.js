import { createLogger, createStore } from 'vuex';

import map from './modules/map';
import objects from './modules/objects';
import sources from './modules/Objects/sources';
import teamRoles from './modules/team-roles';
import notifications from './modules/notifications';

const inLocalMode = process.env.MIX_ENV === 'local';

const loggerOptions = {
    collapsed: true,
    logActions: true, // Log Actions
    logMutations: true, // Log mutations
    logger: console, // implementation of the `console` API, default `console`
};

// add plugins that will go in prod, here
const plugins = [];

const store = createStore({
    strict: inLocalMode ? true : false,
    plugins: inLocalMode
        //https://next.vuex.vuejs.org/guide/plugins.html#built-in-logger-plugin
        ? [...plugins, createLogger(loggerOptions)]
        : plugins,
    modules: {
        map,
        objects,
        sources,
        teamRoles,
        notifications
    },
});

export { store };
