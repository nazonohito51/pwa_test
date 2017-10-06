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

                navigator.serviceWorker.register('/sw.js', {scope: '/'}).then(function (registration) {
                    console.log('Service Worker is registered', registration);

                    this.$store.commit('serviceWorker/setRegistration', registration);
                    this.checkSubscription();
                }.bind(this)).catch(function (error) {
                    console.error('Service Worker Error', error);
                });
            } else {
                console.warn('Push messaging is not supported');
            }
        },
        checkSubscription: function () {
            if (this.swRegistration) {
                this.swRegistration.pushManager.getSubscription().then(function (subscription) {
                    if (subscription !== null) {
                        this.$store.commit('serviceWorker/subscribe');
                    } else {
                        this.$store.commit('serviceWorker/unsubscribe');
                    }
                }.bind(this));
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
                        this.updateCredential(response.data.name, response.data.nickname, response.data.api_token);
                        console.log('updating subscription on server is succeeded.');
                    }
                }.bind(this)).catch(function (err) {
                    subscription.unsubscribe().then(function (successful) {
                        console.log('unsubscribing is succeeded.', successful);
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
        updateCredential: function (username, nickname, api_token) {
            const local_storage = window.localStorage;
            local_storage.setItem('credential:username', username);
            local_storage.setItem('credential:nickname', nickname);
            local_storage.setItem('credential:api_token', api_token);

            this.$store.commit('credential/update', {
                'name': username,
                'nickname': nickname,
                'api_token': api_token
            });

            const username_dom = document.head.querySelector('meta[name="app-username"]');
            const nickname_dom = document.head.querySelector('meta[name="app-nickname"]');
            const api_token_dom = document.head.querySelector('meta[name="api-token"]');
            if (username_dom && nickname_dom && api_token_dom) {
                username_dom.content = username;
                nickname_dom.content = nickname;
                api_token_dom.content = api_token;
            }
        }
    }
};
