let mix = require('laravel-mix');
const workboxPlugin = require('workbox-webpack-plugin');
const dist = 'public';

mix.webpackConfig({
    plugins: [
        new workboxPlugin({
            globDirectory: dist,
            swDest: path.join(dist, 'sw.js'),
            cacheId: 'pwa-test',
            globPatterns: [
                'manifest.json',
                'css/**.css',
                'images/error.png',
                'images/avators/no_image.png',
                'js/**.js',
                'fonts/**/**.*',
            ],
            templatedUrls: {
                '/app': ['../resources/views/layouts/onsen.blade.php', '../resources/views/front/onsenVue.blade.php']
            },
            runtimeCaching: [
                {
                    urlPattern: /images\/avators\/[^\.\/]+\.png$/,
                    handler: 'staleWhileRevalidate',
                    options: {
                        cacheName: 'image-avators',
                        cacheExpiration: {
                            maxEntries: 10,
                            maxAgeSeconds: 24 * 60 * 60
                        },
                    },
                },
                {
                    urlPattern: /api\/articles\/\d+$/,
                    handler: 'cacheFirst',
                    options: {
                        cacheName: 'article-details',
                        cacheExpiration: {
                            maxEntries: 10,
                            maxAgeSeconds: 10 * 60
                        },
                    },
                }
            ],
            // onsenVue.js is over 2MB(default miximumFileSize).
            maximumFileSizeToCacheInBytes: 6 * 1024 * 1024,
            ignoreUrlParametersMatching: [/./],
            clientsClaim: true,
            skipWaiting: false,
        })
    ]
});

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .js('resources/assets/js/onsenVue.js', 'public/js')
    .js('resources/assets/js/main.js', 'public/js');
    // .js('resources/assets/js/ServiceWorker/pushNotification.js', 'public/js')
    // .js('resources/assets/js/sw.js', 'public');
