<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button>Settings</v-ons-back-button>
            </div>
            <div class="center">UserProfile</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header>ニックネーム</v-ons-list-header>
            <v-ons-list-item :modifier="$ons.platform.isAndroid() ? 'nodivider' : ''">
                <div class="left">
                    <v-ons-icon icon="md-face" class="list-item__icon"></v-ons-icon>
                </div>
                <label class="center">
                    <v-ons-input float maxlength="20" placeholder="Name" v-model="nickname"></v-ons-input>
                </label>
            </v-ons-list-item>
            <v-ons-list-item v-if="nicknameChanged">
                <v-ons-button modifier="large" @click="updateUserNickName()">更新</v-ons-button>
            </v-ons-list-item>
        </v-ons-list>
    </v-ons-page>
</template>

<script>
    import apiClientMixin from '../../mixins/apiClient.js';

    export default {
        mixins: [apiClientMixin],
        created: function () {
            this.getUser();
        },
        data() {
            return {
                originalNickname: null,
                nickname: null
            };
        },
        methods: {
            getUser: function() {
                const api_token = this.$store.state.credential.api_token;
                this.getRequest("/api/user/" + api_token, function (response) {
                    console.log(response);
                    this.nickname = response.data.user.nickname;
                    this.originalNickname = response.data.user.nickname;
                }.bind(this), function () {
                    this.$ons.notification.toast('ユーザ情報の取得に失敗しました。', {timeout: 2000});
                    this.$store.commit('navigator/pop');
                }.bind(this));
            },
            updateUserNickName: function() {
                const username = this.$store.state.credential.username;
                this.putRequest("/api/user/" + username, {nickname: this.nickname}, function (response) {
                    this.nickname = response.data.user.nickname;
                    this.originalNickname = response.data.user.nickname;
                    this.$store.commit('navigator/pop');
                    this.$ons.notification.toast('ニックネームを更新しました。', {timeout: 2000});
                }.bind(this), function () {
                    this.$ons.notification.toast('ニックネームの更新に失敗しました。', {timeout: 2000});
                }.bind(this));
            }
        },
        computed: {
            nicknameChanged: function() {
                return this.originalNickname != this.nickname;
            }
        }
    };
</script>
