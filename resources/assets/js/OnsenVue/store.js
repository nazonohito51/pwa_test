export default {
    modules: {
        credential: {
            strict: true,
            namespaced: true,
            state: {
                username: null,
                nickname: null,
                api_token: null
            },
            mutations: {
                update(state, payload) {
                    state.username = payload.username;
                    state.nickname = payload.nickname;
                    state.api_token = payload.api_token;

                    const local_storage = window.localStorage;
                    local_storage.setItem('credential:username', payload.username);
                    local_storage.setItem('credential:nickname', payload.nickname);
                    local_storage.setItem('credential:api_token', payload.api_token);
                }
            }
        },

        serviceWorker: {
            strict: true,
            namespaced: true,
            state: {
                applicationServerPublicKey: 'BJbwhdyPzgvLnBmxYat8cGJSck_wy0Ph_vRTPHemglPtSrmiLZ1R05yFbnfQJen-MbS97RejCn3xm6Y4v1ZvZ1Q',
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

        tabBar: {
            strict: true,
            namespaced: true,
            state: {
                activeIndex: 0
            },
            mutations: {
                show(state, index) {
                    state.activeIndex = index;
                }
            }
        }
    }
};
