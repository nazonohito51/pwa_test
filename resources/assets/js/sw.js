const CACHE_NAME = 'my-site-cache-v1';
const urlsToCache = [
    '/onsenVue',
    '/js/onsenVue.js',
    '/css/app.css',
    '/images/avators/no_image.png',
    '/fonts/vendor/onsenui/css/font_awesome/fontawesome-webfont.woff2?af7ae505a9eed503f8b8e6982036873e',
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function(cache) {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('activate', function(event) {
    console.log('Finally active. Ready to start serving content!');
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            // キャッシュがあったのでそのレスポンスを返す
            if (response) {
                return response;
            }
            return fetch(event.request);
        })
    );
});

self.addEventListener('push', function(event) {
    console.log('[Service Worker] Push Received.');
    // console.log(`[Service Worker] Push had this data: "${event.data}"`);

    const title = 'PWA TEST';
    const options = {
        body: event.data.json().message,
        icon: event.data.json().icon,
        badge: event.data.json().icon
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event) {
    console.log('[Service Worker] Notification click Received.');

    event.notification.close();

    event.waitUntil(
        clients.openWindow('https://developers.google.com/web/')
    );
});
