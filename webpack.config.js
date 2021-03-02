const path = require('path');
var LiveReloadPlugin = require('webpack-livereload-plugin');

module.exports = () => {
    const liveReloadPort = Number(process.env.MIX_LIVERELOAD)

    return {
        resolve: {
            alias: {
                '@': path.resolve('resources/js'),
            },
        },
        plugins: [
            new LiveReloadPlugin({ port: liveReloadPort })
        ]
    }
};
