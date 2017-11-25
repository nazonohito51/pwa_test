importScripts('workbox-sw.dev.v2.1.1.js');
importScripts('workbox-background-sync.dev.v2.0.3.js');

const workboxSW = new WorkboxSW({
    cacheId: "pwa-test",
    clientsClaim: true,
    skipWaiting: false,
    ignoreUrlParametersMatching: [
        /^utm_/,
        /^api_token/,
        /^[a-z0-9]{32}/,  // web font hash of onsen ui
        /^rand/
    ]
});
workboxSW.precache([]);

let avatars_handler = workboxSW.strategies.staleWhileRevalidate({
    "cacheName": "image-avatars",
    "cacheExpiration": {
        "maxAgeSeconds": 86400
    }
});
let avatar_network_first_handler = workboxSW.strategies.networkFirst({
    "cacheName": "image-self-avatar",
    "cacheExpiration": {
        "maxAgeSeconds": 86400
    }
});
let article_handler = workboxSW.strategies.cacheFirst({
    "cacheName": "article-details",
    "cacheExpiration": {
        "maxAgeSeconds": 600
    }
});

// workboxSW.router.registerRoute(/images\/avatars\/[^\.\/]+\.png$/, avatars_handler, 'GET');
workboxSW.router.registerRoute(/images\/avatars\/[^\.\/]+\.png$/, function (args) {
    // console.log('args:', args);
    // {url: URL, event: FetchEvent, params: undefined}
    return avatars_handler.handle(args).then(function (response) {
        console.log('avatars_handler response', response);
        if (!response || response.type === 'error') {
            return caches.match('images/error.png');
        } else if (response.status === 404) {
            return caches.match('images/avatars/no_image.png');
        }
        return response;
    });
}, 'GET');
workboxSW.router.registerRoute(/images\/avatars\/[^\.\/]+\.png\?self$/, function (args) {
    return avatar_network_first_handler.handle(args).then(function (response) {
        console.log('avatar_network_first_handler response', response);
        if (!response || response.type === 'error') {
            return caches.match('images/error.png');
        } else if (response.status === 404) {
            return caches.match('images/avatars/no_image.png');
        }
        return response;
    });
}, 'GET');
workboxSW.router.registerRoute(/api\/articles\/\d+$/, article_handler, 'GET');

self.addEventListener('push', function(event) {
    console.log('[Service Worker] Push Received.', event);

    const title = 'PWA TEST';
    const options = {
        body: event.data.json().message,
        icon: event.data.json().icon,
        badge: event.data.json().badge
    };

    const uri = event.data.json().fetch_uri;
    if (uri) {
        // https://developer.mozilla.org/ja/docs/Web/API/FetchEvent/FetchEvent
        const myFetchEvent = new FetchEvent('fetch', {request: new Request(uri)});
        const url = new URL(uri, location.host);
        const responsePromise = article_handler.handle({
            url: url,
            event: myFetchEvent
        });
    }

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function(event) {
    console.log('[Service Worker] Notification click Received.', event);

    event.notification.close();

    // event.waitUntil(
    //     clients.openWindow('https://developers.google.com/web/')
    // );
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
        const store = trans.objectStore('credential');
        const getRequest = store.get('1');

        getRequest.onsuccess = function(event){
            const formData = new FormData();
            formData.append('api_token', event.target.result.api_token);
            fetch(request, {body: formData}).then(function(response) {
                console.log('response in fetchWithApiToken.', response);
            });
        };

        trans.onerror = function (event) {
            console.log('transaction error in fetchWithApiToken', event);
        };
        getRequest.onerror = function (event) {
            console.log('request error in fetchWithApiToken', event)
        };
    };
    idbRequest.onerror = function (event) {
        console.log('error in fetchWithApiToken.', event);
    };
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
