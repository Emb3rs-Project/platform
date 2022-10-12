import Echo from "laravel-echo";
import "pusher-js";
import {createApp} from 'vue';
import {notify} from "@kyvg/vue3-notification";

const app = createApp({})


class BroadCastClient {
    options = {};
    userChannel = {};

    constructor (options) {
        this.options = Object.assign(this.defaultOptions(), options);
    }

    defaultOptions () {
        return {
            broadcaster: "pusher",
            key: "app-key",
            forceTLS: false,
            cluster: "us2",
            encrypted: true,
            disableStats: true,
            authEndpoint: "/api/broadcasting/auth",
            auth: {}
        };
    }

    connect () {
        try {
            console.log('[echo]: connecting...')
            const process = new Echo(this.options)

            this.userChannel = process.private(`user-${this.options.user_id}`)
                .notification((notification) => {
                    notify({
                        group: "notifications",
                        title: "Notification",
                        text: notification.description,
                        duration: 5000,
                        data: {
                            type: "info",
                        },
                    });
                })
            console.log('[echo]: connected')
            global.wsON = true
            return process;
        } catch (error) {
            global.wsON = false
            console.log(`[echo]: ${error}`)
        }
    }
}

export const echo = (options) => {
    app.config.globalProperties.$echo = new BroadCastClient(options).connect()
    app.config.globalProperties.$echoOptions = options
    return app.config.globalProperties.$echo
}

export const broadcast = () => {
    if (app.config.globalProperties.$echo === undefined) {
        return echo(app.config.globalProperties.$echoOptions)
    }
    return app.config.globalProperties.$echo;
}
