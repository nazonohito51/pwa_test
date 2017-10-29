<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Settings</div>
        </v-ons-toolbar>

        <ul class="list">
            <li class="list-header" v-if="isRegistered">
                アカウント設定
            </li>
            <li class="list-item" v-if="isRegistered">
                <v-ons-card style="width: 100%;">
                    <div class="user">
                        <div class="left">
                            <img v-bind:src="avator_url" style="width: 48px; height: 48px; border-radius: 50%;">
                            {{nickname}}
                        </div>
                    </div>
                </v-ons-card>
            </li>
            <li class="list-item list-item--tappable list-item--chevron" @click="pushUserPage()" v-if="isRegistered">
                <div class="list-item__center">ニックネーム</div>
            </li>
            <li class="list-item list-item--tappable list-item--chevron" @click="pushAvatorPage()" v-if="isRegistered">
                <div class="list-item__center">アイコン</div>
            </li>

            <li class="list-header">
                通知設定
            </li>
            <li class="list-item">
                <div class="list-item__center">
                    プッシュ通知
                </div>
                <div class="list-item__right">
                    <label class="switch">
                        <input type="checkbox" class="switch__input" v-model="subscribeSwitch" :disabled="subscribeSwitch" @change="subscribeConfirm">
                        <div class="switch__toggle">
                            <div class="switch__handle"></div>
                        </div>
                    </label>
                </div>
            </li>
            <li class="list-item">
                <div class="list-item__center">
                    他のユーザの記事投稿時に通知する
                </div>
                <div class="list-item__right">
                    <label class="switch">
                        <input type="checkbox" class="switch__input" v-model="postArticleNotification" @change="updateUserSettings">
                        <div class="switch__toggle">
                            <div class="switch__handle"></div>
                        </div>
                    </label>
                </div>
            </li>
            <li class="list-item">
                <div class="list-item__center">
                    自分の記事がいいね！されたら通知する
                </div>
                <div class="list-item__right">
                    <label class="switch">
                        <input type="checkbox" class="switch__input" v-model="likeArticleNotification" @change="updateUserSettings">
                        <div class="switch__toggle">
                            <div class="switch__handle"></div>
                        </div>
                    </label>
                </div>
            </li>
        </ul>

        <!--<v-ons-list>-->
            <!--<v-ons-list-header v-if="isRegistered">アカウント設定</v-ons-list-header>-->
            <!--<v-ons-list-item v-if="isRegistered">-->
                <!--<v-ons-card style="width: 100%;">-->
                    <!--<div class="user">-->
                        <!--<div class="left">-->
                            <!--<img v-bind:src="avator_url" style="width: 48px; height: 48px; border-radius: 50%;">-->
                            <!--{{nickname}}-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</v-ons-card>-->
            <!--</v-ons-list-item>-->
            <!--<v-ons-list-item modifier="chevron" tappable @click="pushUserPage()" v-if="isRegistered">ニックネーム</v-ons-list-item>-->
            <!--<v-ons-list-item modifier="chevron" tappable @click="pushAvatorPage()" v-if="isRegistered">アイコン</v-ons-list-item>-->
            <!--<v-ons-list-header>通知設定</v-ons-list-header>-->
            <!--<ons-list-item>-->
                <!--<label class="center" for="notification_switch">-->
                    <!--プッシュ通知-->
                <!--</label>-->
                <!--<div class="right">-->
                    <!--<v-ons-switch input-id="notification_switch" v-model="subscribeSwitch" :disabled="subscribeSwitch" @change="subscribeConfirm">-->
                    <!--</v-ons-switch>-->
                <!--</div>-->
            <!--</ons-list-item>-->
            <!--&lt;!&ndash;<v-ons-list-item tappable @click="checkCredential()">認証情報確認</v-ons-list-item>&ndash;&gt;-->
        <!--</v-ons-list>-->

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
                postArticleNotification: false,
                likeArticleNotification: false,
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
                this.nickname = local_storage.getItem('Settings:nickname');
                this.avator_url = local_storage.getItem('Settings:avator_url');
                this.postArticleNotification = local_storage.getItem('Settings:postArticleNotification');
                this.likeArticleNotification = local_storage.getItem('Settings:likeArticleNotification');

                this.subscribeSwitch = this.$store.state.serviceWorker.isSubscribed;
                this.getUserInfo();
            },
            getUserInfo: function () {
                const username = this.$store.state.credential.username;

                if (username) {
                    this.getRequest("/api/user/" + username, function (response) {
                        this.nickname = response.data.user.nickname;
                        this.avator_url = response.data.user.avator_url;
                        this.postArticleNotification = response.data.user.user_setting.post_article_notification;
                        this.likeArticleNotification = response.data.user.user_setting.like_article_notification;

                        const local_storage = window.localStorage;
                        local_storage.setItem('Settings:nickname', this.nickname);
                        local_storage.setItem('Settings:avator_url', this.avator_url);
                        local_storage.setItem('Settings:postArticleNotification', this.postArticleNotification);
                        local_storage.setItem('Settings:likeArticleNotification', this.likeArticleNotification);
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
            },
            updateUserSettings() {
                const username = this.$store.state.credential.username;
                this.putRequest("/api/user/" + username + '/setting', {
                    post_article_notification: this.postArticleNotification,
                    like_article_notification: this.likeArticleNotification,
                }, function (response) {
                    this.$ons.notification.toast('通知設定を更新しました。', {timeout: 2000});
                }.bind(this), function () {
                    this.$ons.notification.toast('通知設定を更新に失敗しました。', {timeout: 2000});
                }.bind(this));
            }
        }
    };
</script>
