// import 'es6-promise/auto';
import 'leaflet/dist/leaflet.css';

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress';

import route from 'ziggy';

import { store } from './Stores';
import Notifications from '@kyvg/vue3-notification'

require('./bootstrap');

createInertiaApp({
  title: title => title ? `${title} | EMB3R's` : `EMB3R's`,
  resolve: name => require(`./Pages/${name}`).default,
  setup({ el, app, props, plugin }) {
    createApp({ render: () => h(app, props) })
      .mixin({ methods: { route: route } })
      .use(plugin)
      .use(store)
      .use(Notifications)
      .mount(el)
  },
})

InertiaProgress.init({ color: '#F59E0B' });
