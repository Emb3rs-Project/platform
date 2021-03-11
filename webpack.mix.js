const mix = require('laravel-mix');
const path = require('path');

mix.js('resources/js/app.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]).alias({
        '@': path.resolve('resources/js'),
    });

if (mix.inProduction()) {
    mix.version();
} else {
    const hmrPort = Number(process.env.MIX_HMR_PORT)
    // No need to use LiveReload since we have $ yarn hot
    // mix.webpackConfig(require('./webpack.config'));
    mix.options({
        // https://github.com/JeffreyWay/laravel-mix/blob/c67ff394bfffead9a1e2bc71ac97559e394e4cfe/src/config.js#L24
        hmrOptions: {
            host: 'localhost',
            port: hmrPort,
        }
    })
}
