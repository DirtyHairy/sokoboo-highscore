var Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('highscores', './assets/ts/highscores.tsx')
    // .splitEntryChunks()
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(true)
    //.enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabel(() => {
    }, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .enableSassLoader()
    .enableTypeScriptLoader()

// uncomment if you use API Platform Admin (composer req api-admin)
//.enableReactPreset()
;

module.exports = Encore.getWebpackConfig();
