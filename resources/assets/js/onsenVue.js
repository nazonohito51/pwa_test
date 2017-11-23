/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// Webpack CSS import
import 'onsenui/css/onsenui.css';
import 'onsenui/css/onsen-css-components.css';
import 'cropperjs/dist/cropper.css';

// JS import
import Vue from 'vue';
import Vuex from 'vuex';
import VueOnsen from 'vue-onsenui'; // This already imports 'onsenui'
import VueQuillEditor from 'vue-quill-editor';
import VueObserveVisibility from 'vue-observe-visibility'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Vuex);
Vue.use(VueOnsen);
Vue.use(VueQuillEditor);
Vue.use(VueObserveVisibility);
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

        window.onpopstate = function(event) {
            console.log('onpopstate', event);
            this.$store.commit('navigator/pop');
        }.bind(this);
    },
    methods: {
        getCredential: function () {
            // const username = document.head.querySelector('meta[name="app-username"]');
            // const nickname = document.head.querySelector('meta[name="app-nickname"]');
            // const api_token = document.head.querySelector('meta[name="api-token"]');

            const local_storage = window.localStorage;
            const username = local_storage.getItem('credential:username');
            const nickname = local_storage.getItem('credential:nickname');
            const avator_url = local_storage.getItem('credential:avator_url');
            const api_token = local_storage.getItem('credential:api_token');

            if (username && nickname && avator_url && api_token) {
                this.updateCredential(username, nickname, avator_url, api_token);
            }
        },
    }
});
