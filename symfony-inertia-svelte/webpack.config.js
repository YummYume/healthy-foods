const Encore = require('@symfony/webpack-encore');
const path = require('path');
const sveltePreprocess = require('svelte-preprocess');

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
                preprocess: sveltePreprocess(),
                compilerOptions: {
                    dev: !Encore.isProduction()
                },
                hotOptions: {
                    noPreserveState: false,
                    noReload: true,
                    optimistic: true
                }
            },
        },
    })
    .addRule({
        test: /@brainandbones\/[a-zA-Z0-9/]{1,}\.js/,
        resolve: {
            fullySpecified: false
        }
    })
    .addAliases({
        '@app': path.resolve('assets/src'),
        '@stores': path.resolve('assets/src/stores'),
        '@assets': path.resolve('assets/assets')
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
    .enablePostCssLoader()
    .enableBuildNotifications()
    .configureDevServerOptions(options => {
        options.server = {
            type: 'https',
            options: {
                key: './dev-server/server.key',
                cert: './dev-server/server.crt'
            }
        }
    });

const config = Encore.getWebpackConfig();
config.resolve.mainFields = ['svelte', 'browser', 'module', 'main'];
config.resolve.extensions = ['.wasm', '.mjs', '.js', '.json', '.jsx', '.vue', '.ts', '.tsx', '.svelte'];
config.devServer.client = { overlay: { warnings: false, errors: true } };

module.exports = config;
