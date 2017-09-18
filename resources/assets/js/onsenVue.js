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
import serviceWorkerMixin from './OnsenVue/mixins/serviceWorker.js';

const app = new Vue({
    el: '#app',
    mixins: [serviceWorkerMixin],
    render: h => h(AppNavigator),
    store: new Vuex.Store(storeLike),
    created: function () {
        this.getCredential();

        this.registerServiceWorker();
        this.checkSubscription();
    },
    methods: {
        getCredential: function () {
            const username = document.head.querySelector('meta[name="app-username"]');
            const nickname = document.head.querySelector('meta[name="app-nickname"]');
            const api_token = document.head.querySelector('meta[name="api-token"]');

            if (username && nickname && api_token) {
                this.$store.commit('credential/update', username.content, nickname.content, api_token.content);
            }
        },
        updateCredential: function (username, nickname, api_token) {
            this.$store.commit('credential/update', username, nickname, api_token);

            const username_dom = document.head.querySelector('meta[name="app-username"]');
            const nickname_dom = document.head.querySelector('meta[name="app-nickname"]');
            const api_token_dom = document.head.querySelector('meta[name="api-token"]');
            username_dom.content = username;
            nickname_dom.content = nickname;
            api_token_dom.content = api_token;
        }
    }
});
