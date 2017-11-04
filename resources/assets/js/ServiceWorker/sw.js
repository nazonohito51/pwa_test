importScripts('workbox-sw.prod.v2.1.1.js');

const workboxSW = new WorkboxSW({
    cacheId: "pwa-test",
    clientsClaim: true,
    skipWaiting: false,
    ignoreUrlParametersMatching: [/./]
});
workboxSW.precache([]);

workboxSW.router.registerRoute(/images\/avators\/[^\.\/]+\.png$/, workboxSW.strategies.staleWhileRevalidate({
    "cacheName": "image-avators",
    "cacheExpiration": {
        "maxEntries": 10,
        "maxAgeSeconds": 86400
    }
}), 'GET');
workboxSW.router.registerRoute(/api\/articles\/\d+$/, workboxSW.strategies.cacheFirst({
    "cacheName": "article-details",
    "cacheExpiration": {
        "maxEntries": 10,
        "maxAgeSeconds": 600
    }
}), 'GET');

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
