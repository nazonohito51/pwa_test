export default {
    methods: {
        getApiClient: function () {
            return axios.create({
                timeout: 2000,
                params: {
                    api_token: this.$store.state.credential.api_token
                },
                responseType: 'json'
            });
        }
    }
};
