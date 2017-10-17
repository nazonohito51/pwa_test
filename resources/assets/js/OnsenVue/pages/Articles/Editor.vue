<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="center">Tweet</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header>ツイート</v-ons-list-header>
            <v-ons-list-item :modifier="$ons.platform.isAndroid() ? 'nodivider' : ''">
                <div class="left">
                    <v-ons-icon icon="fa-magic" class="list-item__icon"></v-ons-icon>
                </div>
                <label class="center">
                    <v-ons-input float maxlength="255" placeholder="Tweet" v-model="tweet"></v-ons-input>
                </label>
            </v-ons-list-item>
            <v-ons-list-item v-if="tweetInput">
                <v-ons-button modifier="large" @click="postTweet()">投稿</v-ons-button>
            </v-ons-list-item>
        </v-ons-list>

        <v-ons-modal :visible="postingVisible" @click="cancelPostTweet()">
            <p style="text-align: center">
                投稿中... <v-ons-icon icon="fa-spinner" spin></v-ons-icon>
            </p>
        </v-ons-modal>
    </v-ons-page>
</template>

<script>
    import apiClientMixin from '../../mixins/apiClient.js';

    export default {
        mixins: [apiClientMixin],
        data: function () {
            return {
                postingVisible: false,
                tweet: ''
            }
        },
        computed: {
            tweetInput(event) {
                return this.tweet !== '';
            }
        },
        methods: {
            postTweet() {
                this.postingVisible = true;

                const username = this.$store.state.credential.username;
                this.postRequest("/api/user/" + username + "/articles", {title: 'title', body: this.tweet}, function (response) {
                    this.postingVisible = false;
                    this.tweet = '';
                    this.$store.commit('tabBar/show', 0);
                    this.$ons.notification.toast('ツイートを投稿しました。', {timeout: 1000});
                }.bind(this), function () {
                    this.postingVisible = false;
                    this.$ons.notification.toast('ツイートの投稿に失敗しました。', {timeout: 1000});
                }.bind(this));
            },
            cancelPostTweet() {
                this.postingVisible = false;
            }
        }
    };
</script>
