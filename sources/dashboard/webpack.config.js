let Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

// import vuetify-loader as a plugin here
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin')

Encore
// directory where compiled assets will be stored
  .setOutputPath('public/build/')
// public path used by the web server to access the output path
  .setPublicPath('/build')
// only needed for CDN's or sub-directory deploy
//.setManifestKeyPrefix('build/')

/*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
  .addEntry('app', './assets/vue/index.js')
//.addEntry('page1', './assets/js/page1.js')
//.addEntry('page2', './assets/js/page2.js')

// When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

// will require an extra script tag for runtime.js
// but, you probably want this, unless you're building a single-page app
//.enableSingleRuntimeChunk()
  .disableSingleRuntimeChunk()

/*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
// enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())

// enables @babel/preset-env polyfills
  .configureBabel((babelConfig) => {
    babelConfig.plugins.push('@babel/plugin-transform-runtime');
  }, {
    useBuiltIns: 'usage',
    corejs: 3
  })

// enables Vue.js support
  .enableVueLoader()

  .addPlugin(new VuetifyLoaderPlugin())
  // enables Sass/SCSS support
  .enableSassLoader(options => {
    options.implementation = require('sass')
    options.sassOptions = {
      fiber: require('fibers')
    }
  })

// uncomment if you use TypeScript
//.enableTypeScriptLoader()

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
  .enableIntegrityHashes()

// enable ESLint
  .addLoader({
    enforce: 'pre',
    test: /\.(js|vue)$/,
    loader: 'eslint-loader',
    exclude: /node_modules/,
    options: {
      fix: true,
      emitError: true,
      emitWarning: true,
    },
  })

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

// uncomment if you use API Platform Admin (composer req api-admin)
//.enableReactPreset()
//.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();

// module.exports.rules = [
//   {
//     test: /\.s(c|a)ss$/,
//     use: [
//       'vue-style-loader',
//       'css-loader',
//       {
//         loader: 'sass-loader',
//         // Requires sass-loader@^7.0.0
//         options: {
//           implementation: require('sass'),
//           fiber: require('fibers'),
//           indentedSyntax: true // optional
//         },
//         // Requires sass-loader@^8.0.0
//         options: {
//           implementation: require('sass'),
//           sassOptions: {
//             fiber: require('fibers'),
//             indentedSyntax: true // optional
//           },
//         },
//       },
//     ],
//   },
// ];
