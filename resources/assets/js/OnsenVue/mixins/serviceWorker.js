export default {
    computed: {
        applicationServerPublicKey() {
            return this.$store.state.serviceWorker.applicationServerPublicKey;
        },
        swRegistration() {
            return this.$store.state.serviceWorker.registration;
        },
        isSubscribed() {
            return this.$store.state.serviceWorker.isSubscribed;
        }
    },
    methods: {
        registerServiceWorker: function () {
            if ('serviceWorker' in navigator && 'PushManager' in window) {
                console.log('Service Worker and Push is supported');

                navigator.serviceWorker.register('/sw.js', {scope: '/'}).then((registration) => {
                    console.log('Service Worker is registered', registration);
                    this.$store.commit('serviceWorker/setRegistration', registration);

                    this.checkSubscription();
                    this.checkServiceWorkerUpdate();
                }).catch((error) => {
                    console.error('Service Worker Error', error);
                });
            } else {
                console.warn('Push messaging is not supported');
            }
        },
        checkSubscription: function () {
            if (this.swRegistration) {
                this.swRegistration.pushManager.getSubscription().then((subscription) => {
                    if (subscription !== null) {
                        console.log('is subscribed already.');
                        this.$store.commit('serviceWorker/subscribe');
                    } else {
                        console.log('is not subscribed yet.');
                        this.$store.commit('serviceWorker/unsubscribe');
                    }
                });
            }
        },
        subscribeUser: function (callback) {
            const applicationServerKey = this.urlB64ToUint8Array(this.applicationServerPublicKey);

            navigator.serviceWorker.ready.then(function (registration) {
                registration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                }).then(function (subscription) {
                    this.updateSubscriptionOnServer(subscription);
                    this.$store.commit('serviceWorker/subscribe');
                    callback();
                }.bind(this)).catch(function (err) {
                    console.log('Failed to subscribe the user: ', err);
                });
            }.bind(this));
        },
        updateSubscriptionOnServer: function (subscription) {
            if (subscription) {
                const key = subscription.getKey('p256dh');
                const token = subscription.getKey('auth');
                let contentEncoding;
                if ('supportedContentEncodings' in PushManager) {
                    contentEncoding = PushManager.supportedContentEncodings.includes('aes128gcm') ? 'aes128gcm' : 'aesgcm';
                } else {
                    contentEncoding = 'aesgcm';
                }

                axios.post("/api/interim_user", {
                    endpoint: subscription.endpoint,
                    key: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                    token: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                    contentEncoding: contentEncoding
                }).then(function (response) {
                    console.log(response);

                    if (response.error) {
                        console.log('updating subscription on server is failed.');
                    } else {
                        this.updateCredential(response.data.name, response.data.nickname, response.data.avator_url, response.data.api_token);
                        console.log('updating subscription on server is succeeded.');
                    }
                }.bind(this)).catch(function (err) {
                    subscription.unsubscribe().then(function (successful) {
                        console.log('unsubscribing is succeeded.', successful);
                        this.$store.commit('serviceWorker/unsubscribe');
                    });
                }.bind(this));
            } else {
                console.log('updating subscription on server is failed.');
            }
        },
        urlB64ToUint8Array: function (base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        },
        updateCredential: function (username, nickname, avator_url, api_token) {
            this.$store.commit('credential/update', {
                'username': username,
                'nickname': nickname,
                'avator_url': avator_url,
                'api_token': api_token
            });

            let idbRequest = indexedDB.open("pwa_test", 1);
            idbRequest.onupgradeneeded = function (event) {
                let idb = event.target.result;
                let credentialStore = idb.createObjectStore("credential", { keyPath: "id" });

                //データの追加
                credentialStore.add({
                    id: "1",
                    username: username,
                    nickname: nickname,
                    api_token: api_token
                })
            };

            const username_dom = document.head.querySelector('meta[name="app-username"]');
            const nickname_dom = document.head.querySelector('meta[name="app-nickname"]');
            const api_token_dom = document.head.querySelector('meta[name="api-token"]');
            if (username_dom && nickname_dom && api_token_dom) {
                username_dom.content = username;
                nickname_dom.content = nickname;
                api_token_dom.content = api_token;
            }
        },
        checkServiceWorkerUpdate: function () {
            if (this.swRegistration) {
                if (this.swRegistration.waiting) {
                    this.displayUpdateDialog();
                } else {
                    this.swRegistration.onupdatefound = function () {
                        const installingWorker = this.swRegistration.installing;
                        installingWorker.onstatechange = function () {
                            switch (installingWorker.state) {
                                case 'installed':
                                    if (navigator.serviceWorker.controller) {
                                        this.displayUpdateDialog();
                                    } else {
                                        console.log('Content is now available offline!!');
                                    }
                                    break;
                                case 'redundant':
                                    console.error('The installing service worker became redundant.');
                                    break;
                            }
                        }.bind(this);
                    }.bind(this);
                }
            }
        },
        displayUpdateDialog: function () {
            this.$ons.notification.alert('アプリの新しいバージョンがダウンロードされました。再起動すると自動更新されます。アプリを同時に複数起動している場合、自動更新されない場合があります。');
        }
    }
};
