import 'es6-promise/auto';
import 'leaflet/dist/leaflet.css';

import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { createApp, h } from 'vue';

import { store as mapStore } from './Stores/map.state';

require('./bootstrap');

// Import modules...
const el = document.getElementById('app');

createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin)
    .use(mapStore)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
