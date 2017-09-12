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

import AppNavigator from './OnsenVue/components/AppNavigator.vue';
import storeLike from './OnsenVue/store.js';

const app = new Vue({
    el: '#app',
    data: {
        credential: {
            name: null,
            api_token: null
        }
    },
    render: h => h(AppNavigator),
    store: new Vuex.Store(storeLike),
    created: function () {
        const username = document.head.querySelector('meta[name="app-username"]');
        const api_token = document.head.querySelector('meta[name="api-token"]');

        if (username) {
            this.credential.name = username.content;
        }
        if (api_token) {
            this.credential.api_token = api_token.content;
        }

        console.log(this.credential);
    }
});
