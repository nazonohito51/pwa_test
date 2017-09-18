<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button>Settings</v-ons-back-button>
            </div>
            <div class="center">ユーザ設定</div>
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
                this.getApiClient().get("/api/user/test").then(
                    response => {
                        console.log(response);

                        if (response.error) {
                            this.$ons.notification.toast('ユーザ情報の取得に失敗しました。', {timeout: 2000});
                        } else {
                            this.nickname = response.data.user.nickname;
                            this.originalNickname = response.data.user.nickname;
                        }
                    }
                );
            },
            updateUserNickName: function() {
                axios.put("/api/user/test", {
                    nickname: this.nickname
                }).then(
                    response => {
                        console.log(response);

                        if (response.error) {
                            this.$ons.notification.toast('ニックネームの更新に失敗しました。', {timeout: 2000});
                        } else {
                            this.nickname = response.data.user.nickname;
                            this.originalNickname = response.data.user.nickname;

                            this.$ons.notification.toast('ニックネームを更新しました。', {timeout: 2000});
                        }
                    }
                );
            }
        },
        computed: {
            nicknameChanged: function() {
                return this.originalNickname != this.nickname;
            }
        }
    };
</script>
