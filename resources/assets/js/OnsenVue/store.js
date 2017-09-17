export default {
    modules: {
        credential: {
            strict: true,
            namespaced: true,
            state: {
                name: null,
                nickname: null,
                api_token: null
            },
            mutations: {
                update(state, name, nickname, api_token) {
                    state.name = name;
                    state.nickname = nickname;
                    state.api_token = api_token;
                }
            }
        },

        serviceWorker: {
            strict: true,
            namespaced: true,
            state: {
                registration: null,
                isSubscribed: false
            },
            mutations: {
                setRegistration(state, registration) {
                    state.registration = registration;
                },
                subscribe(state) {
                    state.isSubscribed = true;
                },
                unsubscribe(state) {
                    state.isSubscribed = false;
                }
            }
        },

        navigator: {
            strict: true,
            namespaced: true,
            state: {
                stack: []
            },
            mutations: {
                push(state, page) {
                    state.stack.push(page);
                },
                pop(state) {
                    if (state.stack.length > 1) {
                        state.stack.pop();
                    }
                }
            }
        },
    }
};
