const { EnvironmentPlugin } = require('webpack')
const mix = require('laravel-mix')
const glob = require('glob')
const path = require('path')

/*
 |--------------------------------------------------------------------------
 | Mix config
 |--------------------------------------------------------------------------
 */

mix.options({
    postCss: [require('autoprefixer')]
});

/*
 |--------------------------------------------------------------------------
 | Webpack config
 |--------------------------------------------------------------------------
 */

mix.webpackConfig({
    plugins: [
        new EnvironmentPlugin({
            // Application's public url
            BASE_URL: '/'
        })
    ],
    module: {
        rules: [{
            test: /node_modules(?:\/|\\).+\.js$/,
            loader: 'babel-loader',
            include: [
                path.join(__dirname, 'node_modules/bootstrap-vue'),
                path.join(__dirname, 'node_modules/vuejs-datepicker'),
                path.join(__dirname, 'node_modules/vue-echarts'),
                path.join(__dirname, 'node_modules/resize-detector'),
                path.join(__dirname, 'node_modules/vue-c3'),
                path.join(__dirname, 'node_modules/vue-masonry'),
                path.join(__dirname, 'node_modules/vue-cropper'),
                path.join(__dirname, 'node_modules/vuedraggable'),
                path.join(__dirname, 'node_modules/vue-simplemde'),
                path.join(__dirname, 'node_modules/sweet-modal-vue'),
            ],
            options: {
                presets: [['@babel/preset-env', { targets: 'last 2 versions, ie >= 10' }]],
                plugins: ['@babel/plugin-transform-destructuring', '@babel/plugin-proposal-object-rest-spread', '@babel/plugin-transform-template-literals'],
                babelrc: false
            }
        }]
    },
    resolve: {
        alias: {
            '@': path.join(__dirname, 'resources/assets/src'),
            'node_modules': path.join(__dirname, 'node_modules')
        }
    }
})

/*
 |--------------------------------------------------------------------------
 | Vendor assets
 |--------------------------------------------------------------------------
 */

function mixAssetsDir(query, cb) {
    (glob.sync('resources/assets/' + query) || []).forEach(f => {
        f = f.replace(/[\\\/]+/g, '/');
        cb(f, f.replace('resources/assets', 'public'));
    });
}

const sassOptions = {
    implementation: () => require('node-sass')
};

// Core javascripts
mixAssetsDir('vendor/js/**/*.js', (src, dest) => mix.scripts(src, dest));
//mix.js('vendor/js/**/*.js', 'public').version();

// Fonts
mixAssetsDir('vendor/fonts/*.css', (src, dest) => mix.copy(src, dest));
mixAssetsDir('vendor/fonts/*/*', (src, dest) => mix.copy(src, dest));

/*
 |--------------------------------------------------------------------------
 | Entry point
 |--------------------------------------------------------------------------
 */

 mix.webpackConfig({
    output: {
        chunkFilename: "[name].[chunkhash:8].js",
        filename: "[name].js",
    }
});

mix.js('resources/assets/src/entry-point.js', 'public');

mix.version();
if (Mix.isUsing('hmr')) { 
    mix.disableNotifications();
} else {
    mix.version();
}

if (mix.inProduction()) {
    mix.version();
}
