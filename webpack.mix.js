const mix = require('laravel-mix');
const path = require('path');
// const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
  .vue()
  .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
    require('autoprefixer'),
  ])
  .sourceMaps()
  .alias({
    '@': path.resolve('resources/js'),
  });
// .webpackConfig(webpack => {
//   return {
//     stats: {
//       children: false,
//     },
//     output: {
//       chunkFilename: 'js/[name].js?id=[chunkhash]',
//     },
//     plugins: [
//       new webpack.DefinePlugin({
//         __VUE_OPTIONS_API__: true,
//         __VUE_PROD_DEVTOOLS__: false,
//       }),
//     ],
//   }
// });

if (mix.inProduction()) {
  mix.version();
} else {
  const hmrPort = Number(process.env.MIX_HMR_PORT)

  mix.options({
    // https://github.com/JeffreyWay/laravel-mix/blob/c67ff394bfffead9a1e2bc71ac97559e394e4cfe/src/config.js#L24
    hmrOptions: {
      host: 'localhost',
      port: hmrPort,
    }
  })
}
