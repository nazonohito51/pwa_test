<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="center">Settings</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header>アカウント</v-ons-list-header>
            <v-ons-list-item modifier="chevron" tappable @click="pushUserPage()">ユーザ設定</v-ons-list-item>
            <ons-list-item>
                <label class="center" for="notification_switch">
                    プッシュ通知
                </label>
                <div class="right">
                    <v-ons-switch input-id="notification_switch" v-model="isSubscribed" :disabled="isSubscribed">
                    </v-ons-switch>
                </div>
            </ons-list-item>
        </v-ons-list>

        <v-ons-dialog cancelable :visible.sync="registrationDialogVisible">
            <p style="text-align: center">アプリが利用可能になりました！</p>
        </v-ons-dialog>
    </v-ons-page>
</template>

<script>
    import User from './Settings/User.vue';
    import serviceWorkerMixin from '../mixins/serviceWorker.js';

    export default {
        mixins: [serviceWorkerMixin],
        data: function () {
            return {
                registrationDialogVisible: false
            }
        },
        computed: {
            isSubscribed: {
                get: function () {
                    return this.$store.state.serviceWorker.isSubscribed;
                },
                set: function (value) {
                    if (value === true) {
                        this.subscribeUser(function () {
                            this.registrationDialogVisible = true;
                        }.bind(this));
                    } else {
                        console.log('Unsubscribe is not supported.')
                    }
                }
            }
        },
        methods: {
            pushUserPage() {
                this.$store.commit('navigator/push', User);
            },
            notificationChange(event) {
                console.log(this.isSubscribed);
                if (event.value === true) {
                    this.$store.commit('serviceWorker/subscribe');
                } else {
                    this.$store.commit('serviceWorker/unsubscribe');
                }
            }
        }
    };
</script>
