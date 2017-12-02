<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Settings</div>
        </v-ons-toolbar>

        <ul class="list">
            <li class="list-header" v-if="isRegistered">
                ユーザ情報
            </li>
            <li class="list-item" v-if="isRegistered">
                <v-ons-card style="width: 100%;">
                    <div class="user">
                        <div class="left">
                            <img v-bind:src="avatar_url" style="width: 48px; height: 48px; border-radius: 50%;">
                            {{nickname}}
                        </div>
                    </div>
                </v-ons-card>
            </li>
            <li class="list-item list-item--tappable list-item--chevron" @click="pushUserPage()" v-if="isRegistered">
                <div class="list-item__center">ニックネーム</div>
            </li>
            <li class="list-item list-item--tappable list-item--chevron" @click="pushavatarPage()" v-if="isRegistered">
                <div class="list-item__center">アバター</div>
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
                        <input type="checkbox" class="switch__input" v-model="notificationSetting" @change="subscribeConfirm">
                        <div class="switch__toggle">
                            <div class="switch__handle"></div>
                        </div>
                    </label>
                </div>
            </li>
            <!--<li class="list-item" v-if="isRegistered">-->
                <!--<div class="list-item__center">-->
                    <!--他のユーザの記事投稿時に通知する-->
                <!--</div>-->
                <!--<div class="list-item__right">-->
                    <!--<label class="switch">-->
                        <!--<input type="checkbox" class="switch__input" v-model="postArticleNotification" :disabled="!notificationSetting" @change="updateUserSettings">-->
                        <!--<div class="switch__toggle">-->
                            <!--<div class="switch__handle"></div>-->
                        <!--</div>-->
                    <!--</label>-->
                <!--</div>-->
            <!--</li>-->
            <!--<li class="list-item" v-if="isRegistered">-->
                <!--<div class="list-item__center">-->
                    <!--自分の記事がいいね！されたら通知する-->
                <!--</div>-->
                <!--<div class="list-item__right">-->
                    <!--<label class="switch">-->
                        <!--<input type="checkbox" class="switch__input" v-model="likeArticleNotification" :disabled="!notificationSetting" @change="updateUserSettings">-->
                        <!--<div class="switch__toggle">-->
                            <!--<div class="switch__handle"></div>-->
                        <!--</div>-->
                    <!--</label>-->
                <!--</div>-->
            <!--</li>-->
            <!--<li>-->
                <!--<v-ons-list-item tappable @click="test()">認証情報確認</v-ons-list-item>-->
            <!--</li>-->
        </ul>

        <v-ons-dialog cancelable :visible.sync="registrationDialogVisible">
            <p style="margin: 10px auto 5px; text-align: center; font-size: 3em;">🎉</p>
            <p style="text-align: center;">記事の投稿が可能になりました！</p>
        </v-ons-dialog>
    </v-ons-page>
</template>

<script>
    import User from './Settings/User.vue';
    import avatar from './Settings/avatar.vue';
    import serviceWorkerMixin from '../mixins/serviceWorker.js';
    import apiClientMixin from '../mixins/apiClient.js';

    export default {
        mixins: [serviceWorkerMixin, apiClientMixin],
        data: function () {
            return {
                notificationSetting: false,
//                postArticleNotification: false,
//                likeArticleNotification: false,
                registrationDialogVisible: false,
                rand: null
            }
        },
        computed: {
            nickname: function () {
                return this.$store.state.credential.nickname;
            },
            avatar_url: function () {
                if (this.$store.state.credential.avatar_url) {
                    return this.$store.state.credential.avatar_url + '?self&rand=' + this.rand;
                } else {
                    return '/images/avatars/no_image.png';
                }
            },
            isRegistered: function () {
                return this.$store.state.serviceWorker.isSubscribed;
            }
        },
        methods: {
            init() {
                this.regenerateRand();
                this.getSettingsFromLocal().then(() => {
                    this.getSettingsFromServer();
                });
            },
            regenerateRand: function () {
                const chars = "abcdefghijklmnopqrstuvwxyz0123456789";

                const chars_length = chars.length;
                let ret = "";
                for (let i = 0; i < 16; i++) {
                    ret += chars[Math.floor(Math.random() * chars_length)];
                }
                this.rand = ret;
            },
            getSettingsFromLocal: function () {
                return new Promise((resolve, reject) => {
                    const local_storage = window.localStorage;
                    this.notificationSetting = local_storage.getItem('Settings:notificationSetting');

                    resolve();
                });
            },
            getSettingsFromServer: function () {
                const username = this.$store.state.credential.username;

                if (username) {
                    this.getRequest("/api/user/" + username, (response) => {
                        this.setUserInfoToLocal(response.data.user.nickname, response.data.user.avatar_url);
                        this.setNotificationSettingToLocal(response.data.user.user_setting.notification);
                    }, () => {
                        // not registered yet.
                    });
                }
            },
            setUserInfoToLocal: function (nickname, avatar_url) {
                this.$store.commit('credential/update', {
                    'nickname': nickname,
                    'avatar_url': avatar_url
                });
            },
            setNotificationSettingToLocal: function (notification) {
                const bool = (notification === true || notification === "1" || notification === 1);
                this.notificationSetting = bool;
                const local_storage = window.localStorage;
                local_storage.setItem('Settings:notificationSetting', bool);
//                local_storage.setItem('Settings:postArticleNotification', this.postArticleNotification);
//                local_storage.setItem('Settings:likeArticleNotification', this.likeArticleNotification);
            },
            pushUserPage() {
                const username = this.$store.state.credential.username;
                history.pushState({page: 'User'}, 'user page', '/app/self/');
                this.$store.commit('navigator/push', User);
            },
            pushavatarPage() {
                const username = this.$store.state.credential.username;
                history.pushState({page: 'Avatar'}, 'avatar page', '/app/self/avatar');
                this.$store.commit('navigator/push', avatar);
            },
            checkCredential() {
                console.log('nickname: ' + this.nickname);
                console.log('avatar_url: ' + this.avatar_url);

                console.log('username: ' + this.$store.state.credential.username);
                console.log('nickname: ' + this.$store.state.credential.nickname);
                console.log('avatar_url: ' + this.$store.state.credential.avatar_url);
                console.log('api_token: ' + this.$store.state.credential.api_token);
            },
            subscribeConfirm(event) {
                if (this.notificationSetting === true) {
                    if (!this.isRegistered) {
                        // display subscribe modal when only is not subscribed
                        this.$ons.notification.confirm('記事を投稿するにはプッシュ通知を許可していただく必要があります。プッシュ通知を許可しますか？').then(function (response) {
                            if (response) {
                                this.subscribeUser(function () {
                                    this.registrationDialogVisible = true;
                                }.bind(this));
                            } else {
                                this.notificationSetting = false;
                            }
                        }.bind(this));
                    } else {
                        this.updateUserSettings();
                    }
                } else {
                    this.updateUserSettings();
                }
            },
            updateUserSettings() {
                const username = this.$store.state.credential.username;
                this.putRequest("/api/user/" + username + '/setting', {
                    notification: this.notificationSetting,
//                    post_article_notification: this.postArticleNotification,
//                    like_article_notification: this.likeArticleNotification,
                }, function (response) {
                    this.setNotificationSettingToLocal(this.notificationSetting);
                    this.$ons.notification.toast('通知設定を更新しました。', {timeout: 2000});
                }.bind(this), function () {
                    this.$ons.notification.toast('通知設定を更新に失敗しました。', {timeout: 2000});
                }.bind(this));
            },
            getavatarUrlOnError(test1, test2) {
                console.log('test', test1, test2);
                if (this.$store.state.credential.avatar_url) {
                    return this.$store.state.credential.avatar_url;
                } else {
                    return '/images/avatars/no_image.png';
                }
            },
            test() {
                const article_id = this.counter;
                this.counter += 1;
                this.getSync('/api/articles/' + article_id, {}, function () {}, function () {});
            }
        }
    };
</script>
