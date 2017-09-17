/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Webpack CSS import
import 'onsenui/css/onsenui.css';
import 'onsenui/css/onsen-css-components.css';

// JS import
import Vue from 'vue';
import Vuex from 'vuex';
import VueOnsen from 'vue-onsenui'; // This already imports 'onsenui'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Vuex);
Vue.use(VueOnsen);
Vue.config.debug = true;

import AppNavigator from './OnsenVue/components/AppNavigator.vue';
import storeLike from './OnsenVue/store.js';

const app = new Vue({
    el: '#app',
    data: function () {
        return {
            applicationServerPublicKey: 'BJbwhdyPzgvLnBmxYat8cGJSck_wy0Ph_vRTPHemglPtSrmiLZ1R05yFbnfQJen-MbS97RejCn3xm6Y4v1ZvZ1Q',
            credential: {
                name: null,
                api_token: null
            }
        };
    },
    computed: {
        swRegistration() {
            return this.$store.state.serviceWorker.registration;
        },
        isSubscribed() {
            return this.$store.state.serviceWorker.isSubscribed;
        }
    },
    render: h => h(AppNavigator),
    store: new Vuex.Store(storeLike),
    created: function () {
        this.getCredential();
        this.registerServiceWorker();
    },
    methods: {
        getCredential: function () {
            const username = document.head.querySelector('meta[name="app-username"]');
            const api_token = document.head.querySelector('meta[name="api-token"]');

            if (username) {
                this.credential.name = username.content;
            }
            if (api_token) {
                this.credential.api_token = api_token.content;
            }
        },
        registerServiceWorker: function () {
            if ('serviceWorker' in navigator && 'PushManager' in window) {
                console.log('Service Worker and Push is supported');

                navigator.serviceWorker.register('/sw.js', {scope: '/'}).then(function (registration) {
                    console.log('Service Worker is registered', registration);

                    this.$store.commit('serviceWorker/setRegistration', registration);
                    this.subscribeUser();
                }.bind(this)).catch(function (error) {
                    console.error('Service Worker Error', error);
                });
            } else {
                console.warn('Push messaging is not supported');
            }
        },
        checkSubscription: function () {
            this.swRegistration.pushManager.getSubscription()
                .then(function (subscription) {
                    if (subscription !== null) {
                        this.$store.commit('serviceWorker/subscribe');
                    } else {
                        this.$store.commit('serviceWorker/unsubscribe');
                    }
                });
        },
        subscribeUser: function () {
            const applicationServerKey = this.urlB64ToUint8Array(this.applicationServerPublicKey);
            if (this.swRegistration) {
                this.swRegistration.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                }).then(function (subscription) {
                    this.updateSubscriptionOnServer(subscription);
                    this.$store.commit('serviceWorker/subscribe');
                }.bind(this)).catch(function (err) {
                    console.log('Failed to subscribe the user: ', err);
                });
            }
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
                }).then(
                    response => {
                        console.log(response);

                        if (response.error) {
                            console.log('updating subscription on server is failed.');
                        } else {
                            console.log('updating subscription on server is succeeded.');
                        }
                    }
                ).catch(function (err) {
                    subscription.unsubscribe().then(function (successful) {
                        console.log('unsubscribing is succeeded.', successful);
                    });
                });
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
        }
    }
});
