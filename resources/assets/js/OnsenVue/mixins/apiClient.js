export default {
    computed: {
        apiClient: function () {
            return axios.create({
                timeout: 10000,
                params: {
                    api_token: this.$store.state.credential.api_token
                },
                responseType: 'json'
            });
        }
    },
    methods: {
        getRequest: function (uri, resolve_callback, reject_callback) {
            this.apiClient.get(uri).then(function (response) {
                console.log(response);

                if (response.error) {
                    reject_callback();
                } else {
                    resolve_callback(response);
                }
            }).catch(function (error) {
                reject_callback();
            });
        },
        postRequest: function (uri, params, resolve_callback, reject_callback) {
            this.apiClient.post(uri, params).then(function (response) {
                console.log(response);

                if (response.error) {
                    reject_callback();
                } else {
                    resolve_callback(response);
                }
            }).catch(function (error) {
                reject_callback();
            });
        },
        putRequest: function (uri, params, resolve_callback, reject_callback) {
            this.apiClient.put(uri, params).then(function (response) {
                console.log(response);

                if (response.error) {
                    reject_callback();
                } else {
                    resolve_callback(response);
                }
            }).catch(function (error) {
                reject_callback();
            });
        }
    }
};
