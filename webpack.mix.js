const mix = require("laravel-mix");
const path = require("path");

mix.js("resources/js/app.js", "public/js")
    .vue()
    .postCss("resources/css/app.css", "public/css", [require("tailwindcss")])
    .sourceMaps()
    .alias({
        "@": path.resolve("resources/js"),
        ziggy: path.resolve("vendor/tightenco/ziggy/dist"),
    })
    .webpackConfig((webpack) => {
        return {
            // stats: {
            //   children: false,
            // },
            plugins: [
                new webpack.DefinePlugin({
                    __VUE_OPTIONS_API__: true,
                    __VUE_PROD_DEVTOOLS__: false,
                }),
            ],
        };
    });

if (mix.inProduction()) {
    mix.version();
} else {
    mix.options({
        // https://github.com/JeffreyWay/laravel-mix/blob/c67ff394bfffead9a1e2bc71ac97559e394e4cfe/src/config.js#L24
        hmrOptions: {
            host: "localhost",
            port: +process.env.MIX_HMR_PORT,
        },
    });
}
