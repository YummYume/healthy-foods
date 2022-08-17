const Encore = require('@symfony/webpack-encore');
const path = require('path');
const webpack = require('webpack');
const autoPreprocess = require('svelte-preprocess');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addLoader({
        test: /\.(svelte)$/,
        use: {
            loader: 'svelte-loader',
            options: {
                emitCss: Encore.isProduction(),
                hotReload: !Encore.isProduction(),
                preprocess: autoPreprocess(),
            },
        },
    })
    .addAliases({
        '@app': path.resolve('assets/src'),
        '@stores': path.resolve('assets/src/stores')
    })
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .disableSingleRuntimeChunk()
    .configureBabel(() => { }, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .enableSassLoader()
    .enablePostCssLoader();

const config = Encore.getWebpackConfig();
config.resolve.mainFields = ['svelte', 'browser', 'module', 'main'];
config.resolve.extensions = ['.wasm', '.mjs', '.js', '.json', '.jsx', '.vue', '.ts', '.tsx', '.svelte'];
config.plugins = [...config.plugins, new webpack.HotModuleReplacementPlugin()];

module.exports = config;
