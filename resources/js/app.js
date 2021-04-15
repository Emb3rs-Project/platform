import 'leaflet/dist/leaflet.css';

import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { createApp, h } from 'vue';

import { useSlideOver } from './Mixins/UseSlideOver';

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
    .mixin(useSlideOver)
    .use(InertiaPlugin)
    .mount(el);

InertiaProgress.init({ color: '#4B5563' });
