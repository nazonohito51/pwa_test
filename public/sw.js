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
workboxSW.precache([
  {
    "url": "manifest.json",
    "revision": "741dac8960d7dc97ff56631cea60f0f4"
  },
  {
    "url": "css/app.css",
    "revision": "e4e4f7de3b55a3c02aec67022089780a"
  },
  {
    "url": "images/icon.png",
    "revision": "2ec865798d70af5d6b83f2be36a57a5a"
  },
  {
    "url": "images/error.png",
    "revision": "e95375c9c88a3c476ff4f28907713c2b"
  },
  {
    "url": "images/avatars/no_image.png",
    "revision": "b187f90e869f3dc2841f5afdc0afdd97"
  },
  {
    "url": "js/app.js",
    "revision": "34c6a123721a8f0932269e19b036f4b9"
  },
  {
    "url": "js/main.js",
    "revision": "18b52d679d1837e62c6e65330d38945e"
  },
  {
    "url": "js/onsenVue.js",
    "revision": "4495de31ac9b61cf8a6885bc12abed50"
  },
  {
    "url": "js/preinstall.js",
    "revision": "b359a70bbec89def12dd2e4de65343ee"
  },
  {
    "url": "js/pushNotification.js",
    "revision": "302cecf7b6e74b39473202871f5d2c3e"
  },
  {
    "url": "js/sw.js",
    "revision": "83768718fc22fe7b1eed2025bc60d59a"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.eot",
    "revision": "f4769f9bdb7466be65088239c12046d1"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.svg",
    "revision": "89889688147bd7575d6327160d64e760"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.ttf",
    "revision": "e18bbf611f2a2e43afc071aa2f4e1512"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.woff",
    "revision": "fa2772327f55d8198301fdb8bcfc8158"
  },
  {
    "url": "fonts/vendor/bootstrap-sass/bootstrap/glyphicons-halflings-regular.woff2",
    "revision": "448c34a56d699c29117adc64c43affeb"
  },
  {
    "url": "fonts/vendor/onsenui/css/font_awesome/fontawesome-webfont.eot",
    "revision": "674f50d287a8c48dc19ba404d20fe713"
  },
  {
    "url": "fonts/vendor/onsenui/css/font_awesome/fontawesome-webfont.svg",
    "revision": "912ec66d7572ff821749319396470bde"
  },
  {
    "url": "fonts/vendor/onsenui/css/font_awesome/fontawesome-webfont.ttf",
    "revision": "b06871f281fee6b241d60582ae9369b9"
  },
  {
    "url": "fonts/vendor/onsenui/css/font_awesome/fontawesome-webfont.woff",
    "revision": "fee66e712a8a08eef5805a46892932ad"
  },
  {
    "url": "fonts/vendor/onsenui/css/font_awesome/fontawesome-webfont.woff2",
    "revision": "af7ae505a9eed503f8b8e6982036873e"
  },
  {
    "url": "fonts/vendor/onsenui/css/ionicons/ionicons.eot",
    "revision": "19e65b89cee273a249fba4c09b951b74"
  },
  {
    "url": "fonts/vendor/onsenui/css/ionicons/ionicons.svg",
    "revision": "aff28a207631f39ee0272d5cdde43ee7"
  },
  {
    "url": "fonts/vendor/onsenui/css/ionicons/ionicons.ttf",
    "revision": "dd4781d1acc57ba4c4808d1b44301201"
  },
  {
    "url": "fonts/vendor/onsenui/css/ionicons/ionicons.woff",
    "revision": "2c159d0d05473040b53ec79df8797d32"
  },
  {
    "url": "fonts/vendor/onsenui/css/material-design-iconic-Material-Design-Iconic-Font.ttf",
    "revision": "b351bd62abcd96e924d9f44a3da169a7"
  },
  {
    "url": "fonts/vendor/onsenui/css/material-design-iconic-Material-Design-Iconic-Font.woff",
    "revision": "d2a55d331bdd1a7ea97a8a1fbb3c569c"
  },
  {
    "url": "fonts/vendor/onsenui/css/material-design-iconic-Material-Design-Iconic-Font.woff2",
    "revision": "a4d31128b633bc0b1cc1f18a34fb3851"
  },
  {
    "url": "fonts/vendor/quill/icons/align-center.svg",
    "revision": "fcb8ba3f0d3bbc7bf3868cd1bb4687cd"
  },
  {
    "url": "fonts/vendor/quill/icons/align-justify.svg",
    "revision": "630c54561e462e24229018e90d1e15f1"
  },
  {
    "url": "fonts/vendor/quill/icons/align-left.svg",
    "revision": "40dc102f90d71a5fdbfc0b0e448c1504"
  },
  {
    "url": "fonts/vendor/quill/icons/align-right.svg",
    "revision": "17f51fdbb5d0cba7fb7b109f3e86e6cb"
  },
  {
    "url": "fonts/vendor/quill/icons/background.svg",
    "revision": "16ab136200087859f680615bdc9c3d08"
  },
  {
    "url": "fonts/vendor/quill/icons/blockquote.svg",
    "revision": "7415413ac05d88d82fe79d3e5d1f5d4c"
  },
  {
    "url": "fonts/vendor/quill/icons/bold.svg",
    "revision": "89e9c6380f97da65a4b77aba66eecdc3"
  },
  {
    "url": "fonts/vendor/quill/icons/clean.svg",
    "revision": "708e464c7cfab5d57414fd5a86abefde"
  },
  {
    "url": "fonts/vendor/quill/icons/code.svg",
    "revision": "6a784d7d9bdf249df8c81085a2f51f11"
  },
  {
    "url": "fonts/vendor/quill/icons/color.svg",
    "revision": "6aeb1f84796ed56cc34bec2dccfe5ac6"
  },
  {
    "url": "fonts/vendor/quill/icons/direction-ltr.svg",
    "revision": "5e91242ff67c36a0e01b64bb0a18c0db"
  },
  {
    "url": "fonts/vendor/quill/icons/direction-rtl.svg",
    "revision": "680de93d69e406a44c3c88c80e596ed6"
  },
  {
    "url": "fonts/vendor/quill/icons/dropdown.svg",
    "revision": "bb997e2447d304ae8f16d3ec4c9a5c8a"
  },
  {
    "url": "fonts/vendor/quill/icons/float-center.svg",
    "revision": "57666face07cb37327941376d2c00498"
  },
  {
    "url": "fonts/vendor/quill/icons/float-full.svg",
    "revision": "72eb8df3258bc2c8297927f4346fd69d"
  },
  {
    "url": "fonts/vendor/quill/icons/float-left.svg",
    "revision": "81964468f36d6bd42f1816631432a62d"
  },
  {
    "url": "fonts/vendor/quill/icons/float-right.svg",
    "revision": "44927c71c5727754beb5e78827a4db2b"
  },
  {
    "url": "fonts/vendor/quill/icons/formula.svg",
    "revision": "d861ad5e1ab01dc555858bc1f31634bf"
  },
  {
    "url": "fonts/vendor/quill/icons/header-2.svg",
    "revision": "1f6f7b4301e5072c0f2b8ca038f0883f"
  },
  {
    "url": "fonts/vendor/quill/icons/header.svg",
    "revision": "8e0609e2987aca56bdc6cdeffd53130e"
  },
  {
    "url": "fonts/vendor/quill/icons/image.svg",
    "revision": "c7588b71e3f74be65b4248cedb371d17"
  },
  {
    "url": "fonts/vendor/quill/icons/indent.svg",
    "revision": "76a79b6983038899da800e68e0995494"
  },
  {
    "url": "fonts/vendor/quill/icons/italic.svg",
    "revision": "b802b8f99528684510bd7fd1f7a5eeab"
  },
  {
    "url": "fonts/vendor/quill/icons/link.svg",
    "revision": "94205e9015a3f4994b2c8ff8fdd3b72a"
  },
  {
    "url": "fonts/vendor/quill/icons/list-bullet.svg",
    "revision": "ce5a250b5912e56a42dafa5c60cc82ac"
  },
  {
    "url": "fonts/vendor/quill/icons/list-check.svg",
    "revision": "41df892186335c0f787172ba5ea91618"
  },
  {
    "url": "fonts/vendor/quill/icons/list-ordered.svg",
    "revision": "2d710b9b8181bc3e17226a07c5959291"
  },
  {
    "url": "fonts/vendor/quill/icons/outdent.svg",
    "revision": "7fd7ed8a44e99c1b07ef65a438d6917e"
  },
  {
    "url": "fonts/vendor/quill/icons/strike.svg",
    "revision": "15b73b2921153970408096e7a3e7510f"
  },
  {
    "url": "fonts/vendor/quill/icons/subscript.svg",
    "revision": "57a2ac1eedb06f3a2d42fa6ed43476a9"
  },
  {
    "url": "fonts/vendor/quill/icons/superscript.svg",
    "revision": "6e2b0092bf1e415dfa0e69fde002f83d"
  },
  {
    "url": "fonts/vendor/quill/icons/underline.svg",
    "revision": "bde1efcb420a1746f3ce13b6e23265ed"
  },
  {
    "url": "fonts/vendor/quill/icons/video.svg",
    "revision": "eeea6356161e816c5200292388b0d692"
  },
  {
    "url": "/app",
    "revision": "5dfd484d76e8812b988c7a4d51a64ff4"
  }
]);

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

function controlAvatarResponse(response) {
    console.log('avatar response', response);
    if (!response || response.type === 'error') {
        return caches.match('images/error.png');
    } else if (response.status === 404) {
        return caches.match('images/avatars/no_image.png');
    }
    return response;
}

workboxSW.router.registerRoute(/images\/avatars\/[^\.\/]+\.png$/, function (args) {
    // console.log('args:', args);
    // {url: URL, event: FetchEvent, params: undefined}
    return avatars_handler.handle(args).then(controlAvatarResponse);
}, 'GET');
workboxSW.router.registerRoute(/images\/avatars\/[^\.\/]+\.png\?self&rand=[a-z0-9]{16}$/, function (args) {
    return avatar_network_first_handler.handle(args).then(controlAvatarResponse);
}, 'GET');
workboxSW.router.registerRoute(/api\/articles\/\d+$/, function (args) {
    return article_handler.handle(args).then(function (response) {
        console.log('article response', response);
        return response;
    })
}, 'GET');

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
