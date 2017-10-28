<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Tweet</div>
        </v-ons-toolbar>

        <quill-editor v-model="content"
                      ref="myTextEditor"
                      :options="editorOption">
        </quill-editor>

        <!--<v-ons-list>-->
            <!--<v-ons-list-header>ツイート</v-ons-list-header>-->
            <!--<v-ons-list-item :modifier="$ons.platform.isAndroid() ? 'nodivider' : ''">-->
                <!--<div class="left">-->
                    <!--<v-ons-icon icon="fa-magic" class="list-item__icon"></v-ons-icon>-->
                <!--</div>-->
                <!--<label class="center">-->
                    <!--<v-ons-input float maxlength="255" placeholder="Tweet" v-model="tweet"></v-ons-input>-->
                <!--</label>-->
            <!--</v-ons-list-item>-->
            <!--<v-ons-list-item v-if="tweetInput">-->
                <!--<v-ons-button modifier="large" @click="postTweet()">投稿</v-ons-button>-->
            <!--</v-ons-list-item>-->
        <!--</v-ons-list>-->

        <!--<v-ons-modal :visible="postingVisible" @click="cancelPostTweet()">-->
            <!--<p style="text-align: center">-->
                <!--投稿中... <v-ons-icon icon="fa-spinner" spin></v-ons-icon>-->
            <!--</p>-->
        <!--</v-ons-modal>-->
    </v-ons-page>
</template>

<script>
    import apiClientMixin from '../../mixins/apiClient.js';
    import Quill from 'quill';
    import { ImageImport } from '../../../Quill/ImageImport.js'
    Quill.register('modules/imageImport', ImageImport);

    export default {
        mixins: [apiClientMixin],
        data: function () {
            return {
                postingVisible: false,
                tweet: '',
                content: '<h2>I am Example</h2>',
                editorOption: {
                    debug: 'info',
                    placeholder: '本文を入力してください',
                    modules: {
                        toolbar: [
                            ['bold', 'italic'],
                            [{header: 1}, {header: 2}],
                            [{align: 'center'}, {align: 'right'}],
                            ['image']
                        ],
                        imageImport: true
                    }
                }
            }
        },
        computed: {
            tweetInput(event) {
                return this.tweet !== '';
            },
            editor() {
                return this.$refs.myTextEditor.quill
            }
        },
        methods: {
            init() {
//                if (this.$store.state.serviceWorker.isSubscribed !== true) {
//                    this.alertRegistration();
//                }
            },
            alertRegistration() {
                this.$ons.notification.alert('投稿するにはプッシュ通知を許可する必要があります。').then(function (response) {
                    this.$store.commit('tabBar/show', 2);
                }.bind(this));
            },
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
