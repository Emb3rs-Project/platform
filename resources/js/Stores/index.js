import { createLogger, createStore } from 'vuex';

import map from './modules/map';
import objectIndex from './modules/object_index';
import sources from './modules/Objects/sources';

const isLocalMode = process.env.MIX_ENV === 'local' ? true : false;

const loggerOptions = {
    collapsed: false
};

// add plugins that will go in prod, here
const plugins = [];

const store = createStore({
    strict: isLocalMode ? true : false,
    plugins: isLocalMode
        //https://next.vuex.vuejs.org/guide/plugins.html#built-in-logger-plugin
        ? [...plugins, createLogger(loggerOptions)]
        : plugins,
    modules: {
        map,
        objectIndex,
        sources
    },
});

export { store };
