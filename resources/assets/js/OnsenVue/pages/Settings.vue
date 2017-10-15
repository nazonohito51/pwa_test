<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Settings</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header>アカウント</v-ons-list-header>
            <v-ons-list-item modifier="chevron" tappable @click="pushUserPage()" v-if="isRegistered">ユーザ設定</v-ons-list-item>
            <ons-list-item>
                <label class="center" for="notification_switch">
                    プッシュ通知
                </label>
                <div class="right">
                    <v-ons-switch input-id="notification_switch" v-model="subscribeSwitch" :disabled="subscribeSwitch" @change="subscribeConfirm">
                    </v-ons-switch>
                </div>
            </ons-list-item>
            <!--<v-ons-list-item tappable @click="checkCredential()">認証情報確認</v-ons-list-item>-->
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
                subscribeSwitch: false,
                registrationDialogVisible: false
            }
        },
        computed: {
            isRegistered: function () {
                return this.$store.state.serviceWorker.isSubscribed;
            }
        },
        methods: {
            init() {
                this.subscribeSwitch = this.$store.state.serviceWorker.isSubscribed;
            },
            pushUserPage() {
                this.$store.commit('navigator/push', User);
            },
            checkCredential() {
                console.log('username: ' + this.$store.state.credential.username);
                console.log('nickname: ' + this.$store.state.credential.nickname);
                console.log('api_token: ' + this.$store.state.credential.api_token);
            },
            subscribeConfirm(event) {
                if (event.value === true) {
                    this.$ons.notification.confirm('プッシュ通知を許可しますか？').then(function (response) {
                        if (response) {
                            this.subscribeUser(function () {
                                this.registrationDialogVisible = true;
                            }.bind(this));
                        } else {
                            this.subscribeSwitch = false;
                        }
                    }.bind(this));
                } else {
//                    this.$ons.notification.alert('現在プッシュ通知の解除は対応しておりません。');
                }
            }
        }
    };
</script>
