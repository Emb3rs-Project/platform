// import 'es6-promise/auto';
import "leaflet/dist/leaflet.css";
import "vue-select/dist/vue-select.css";

import {createApp, h} from "vue";
import {createInertiaApp} from "@inertiajs/inertia-vue3";
import {InertiaProgress} from "@inertiajs/progress";

import route from "ziggy";

import {store} from "./Stores";
import Notifications from "@kyvg/vue3-notification";
import lvProgressbar from 'lightvue/progress-bar';
import {echo} from './Mixins/vueEcho'
import axios from "axios";

require("./bootstrap");

createInertiaApp({
    title: (title) => (title ? `${title} | EMB3R's` : `EMB3R's`),
    resolve: (name) => require(`./Pages/${name}`).default,
    setup ({el, App, props, plugin}) {
         axios.get('/config').then(({data}) => {
             createApp({render: () => h(App, props)})
                 .mixin({methods: {route: route}})
                 .use(plugin)
                 .use(store)
                 .use(Notifications)
                 .use(echo({
                     broadcaster: 'pusher',
                     authEndpoint: 'broadcasting/auth',
                     key: data.pusherKey,
                     forceTLS: data.pusherTLS,
                     wsHost: data.pusherHost,
                     wsPort: data.pusherPort,
                     wssPort: data.pusherPort,
                     disableStats: true,
                     user_id: data.user_id
                 }))
                 .component('lv-progressbar', lvProgressbar)
                 .mount(el);
         })
    },
});

InertiaProgress.init({color: "#F59E0B"});
