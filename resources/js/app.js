import 'es6-promise/auto';
import 'leaflet/dist/leaflet.css';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress';

import { store } from './Stores';

require('./bootstrap');

createInertiaApp({
  resolve: name => require(`./Pages/${name}`).default,
  setup({ el, app, props, plugin }) {
    createApp({ render: () => h(app, props) })
      .mixin({ methods: { route } })
      .use(plugin)
      .use(store)
      .mount(el)
  },
})

InertiaProgress.init({ color: '#F59E0B' });
