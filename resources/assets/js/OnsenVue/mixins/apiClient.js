export default {
    computed: {
        apiClient: function () {
            return axios.create({
                timeout: 60000,
                responseType: 'json'
            });
        },
        apiClientWithApiToken: function () {
            return axios.create({
                timeout: 60000,
                params: {
                    api_token: this.$store.state.credential.api_token
                },
                responseType: 'json'
            });
        }
    },
    methods: {
        prefetch: function (uri, resolve_callback, reject_callback) {
            this.apiClient.get(uri).then(function (response) {
                // this.loggingReponse(response);

                if (response.error) {
                    reject_callback();
                } else {
                    resolve_callback(response);
                }
            }.bind(this)).catch(function (error) {
                reject_callback();
            });
        },
        getRequest: function (uri, resolve_callback, reject_callback) {
            this.apiClient.get(uri).then(function (response) {
                this.loggingReponse(response);

                if (response.error) {
                    reject_callback();
                } else {
                    resolve_callback(response);
                }
            }.bind(this)).catch(function (error) {
                reject_callback();
            });
        },
        postRequest: function (uri, params, resolve_callback, reject_callback) {
            this.apiClientWithApiToken.post(uri, params).then(function (response) {
                this.loggingReponse(response);

                if (response.error) {
                    reject_callback();
                } else {
                    resolve_callback(response);
                }
            }.bind(this)).catch(function (error) {
                reject_callback();
            });
        },
        putRequest: function (uri, params, resolve_callback, reject_callback) {
            this.apiClientWithApiToken.put(uri, params).then(function (response) {
                this.loggingReponse(response);

                if (response.error) {
                    reject_callback();
                } else {
                    resolve_callback(response);
                }
            }.bind(this)).catch(function (error) {
                reject_callback();
            });
        },
        getSync: function (uri, params, resolve_callback, reject_callback) {
            const registration = this.$store.state.serviceWorker.registration;
            if (registration.active && window.SyncManager) {
                registration.sync.register('GET:' + uri);
            } else {
                console.log('sync not supported.');
                this.postRequest(uri, params, resolve_callback, reject_callback);
            }
        },
        postSync: function (uri, params, resolve_callback, reject_callback) {
            const registration = this.$store.state.serviceWorker.registration;
            if (registration.active && window.SyncManager) {
                registration.sync.register('POST:' + uri);
            } else {
                console.log('sync not supported.');
                this.postRequest(uri, params, resolve_callback, reject_callback);
            }
        },
        loggingReponse: function (response) {
            console.log(response);
        }
    }
};
