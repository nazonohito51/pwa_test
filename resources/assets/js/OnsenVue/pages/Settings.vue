<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Settings</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header  v-if="isRegistered">アカウント設定</v-ons-list-header>
            <v-ons-list-item v-if="isRegistered">
                <v-ons-card style="width: 100%;">
                    <div class="user">
                        <div class="left">
                            <img v-bind:src="avator_url" style="width: 48px; height: 48px; border-radius: 50%;">
                            {{nickname}}
                        </div>
                    </div>
                </v-ons-card>
            </v-ons-list-item>
            <v-ons-list-item modifier="chevron" tappable @click="pushUserPage()" v-if="isRegistered">ニックネーム</v-ons-list-item>
            <v-ons-list-item modifier="chevron" tappable @click="pushAvatorPage()" v-if="isRegistered">アイコン</v-ons-list-item>
            <v-ons-list-header>通知設定</v-ons-list-header>
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
    import Avator from './Settings/Avator.vue';
    import serviceWorkerMixin from '../mixins/serviceWorker.js';
    import apiClientMixin from '../mixins/apiClient.js';

    export default {
        mixins: [serviceWorkerMixin, apiClientMixin],
        data: function () {
            return {
                nickname: null,
                avator_url: null,
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
                const local_storage = window.localStorage;
                console.log(local_storage.getItem('Settings:nickname'));
                console.log(local_storage.getItem('Settings:avator_url'));
                this.nickname = local_storage.getItem('Settings:nickname');
                this.avator_url = local_storage.getItem('Settings:avator_url');

                this.subscribeSwitch = this.$store.state.serviceWorker.isSubscribed;
                this.getUserInfo();
            },
            getUserInfo: function () {
                const username = this.$store.state.credential.username;

                if (username) {
                    this.getRequest("/api/user/" + username, function (response) {
                        this.nickname = response.data.user.nickname;
                        this.avator_url = response.data.user.avator_url;

                        const local_storage = window.localStorage;
                        local_storage.setItem('Settings:nickname', this.nickname);
                        local_storage.setItem('Settings:avator_url', this.avator_url);
                    }.bind(this), function () {
//                        this.$ons.notification.toast('ユーザ情報の取得に失敗しました。', {timeout: 2000});
                    }.bind(this));
                }
            },
            pushUserPage() {
                this.$store.commit('navigator/push', User);
            },
            pushAvatorPage() {
                this.$store.commit('navigator/push', Avator);
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
                                this.getUserInfo();
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
