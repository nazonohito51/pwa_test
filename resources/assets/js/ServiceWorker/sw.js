importScripts('workbox-sw.prod.v2.1.1.js');
importScripts('workbox-background-sync.prod.v2.0.3.js');

const workboxSW = new WorkboxSW({
    cacheId: "pwa-test",
    clientsClaim: true,
    skipWaiting: false,
    ignoreUrlParametersMatching: []
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

self.addEventListener('sync', function (event) {
    if (event.tag.match(/^(GET|POST|PUT|DELETE):/)) {
        const matches = event.tag.match(/^(GET|POST|PUT|DELETE):(.*)/);
        const method = matches[1];
        const uri = matches[2];
        event.waitUntil(fetchWithApiToken(new Request(uri, {method: method})));
    }
});

function fetchWithApiToken(request) {
    const idbRequest = indexedDB.open("pwa_test", 1);

    idbRequest.onsuccess = function (event) {
        const idb = event.target.result;
        const trans = idb.transaction('credential', 'readonly');
        trans.oncomplete = function (event) {
            const store = trans.objectStore('credential');
            const getRequest = store.get('1');

            getRequest.onsuccess = function(event){
                const formData = new FormData();
                formData.append('api_token', event.target.result.api_token);
                fetch(request, {body: formData}).then(function(response) {
                    console.log('response in fetchWithApiToken.', response);
                });
            }
        };
        trans.onerror = function (event) {
            console.log('transaction error in fetchWithApiToken', event);
        };
    };
    idbRequest.onerror = function (event) {
        console.log('error in fetchWithApiToken.', event);
    }
}

// let bgQueue = new workbox.backgroundSync.QueuePlugin({
//     callbacks: {
//         replayDidSucceed: async(hash, res) => {
//             self.registration.showNotification('Background sync demo', {
//                 body: 'Product has been purchased.',
//                 icon: '/images/icon.png',
//             });
//         },
//         replayDidFail: (hash) => {},
//         requestWillEnqueue: (reqData) => {},
//         requestWillDequeue: (reqData) => {},
//     },
// });
//
// const requestWrapper = new workbox.runtimeCaching.RequestWrapper({
//     plugins: [bgQueue],
// });
//
// const route = new workbox.routing.RegExpRoute({
//     regExp: new RegExp('api/.*$'),
//     handler: new workbox.runtimeCaching.NetworkOnly({requestWrapper}),
// });
//
// const router = new workbox.routing.Router();
// router.registerRoute({route});
