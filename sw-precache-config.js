module.exports = {
    swFilePath: 'public/sw.js',
    staticFileGlobs: [
        'public/app',
        'public/manifest.json',
        'public/css/**.css',
        'public/images/error.png',
        'public/images/avators/no_image.png',
        'public/js/**.js',
        'public/fonts/**/**.*',
    ],
    dynamicUrlToDependencies: {
        '/app': ['resources/views/layouts/onsen.blade.php', 'resources/views/front/onsenVue.blade.php']
    },
    stripPrefix: 'public/',
    ignoreUrlParametersMatching: [/./],
    cacheId: 'pwa-test',
    clientsClaim: false,
    runtimeCaching: [{
        urlPattern: /this\\.is\\.a\\.regex/,
        handler: 'networkFirst'
    }],
    // onsenVue.js is over 2MB(default miximumFileSize).
    maximumFileSizeToCacheInBytes: 6 * 1024 * 1024,
    verbose: true,
    importScripts: ['js/pushNotification.js'],
};
