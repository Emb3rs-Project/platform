import { createStore } from 'vuex';

import map from './modules/map';


const store = createStore({
    modules: {
        map
    }
})


export { store }
