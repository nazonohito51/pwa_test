<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Editor</div>
            <div class="right">
                <v-ons-toolbar-button @click="postArticle()" v-if="articleInput">
                    投稿する
                </v-ons-toolbar-button>
            </div>
        </v-ons-toolbar>

        <div class="article">
            <ul class="list">
                <li class="list-item">
                    <div class="list-item__center">
                        <input v-model="title" class="text-input" maxlength="25" placeholder="タイトルを入力してください" style="width: 100%; font-size: 25px;">
                    </div>
                </li>
            </ul>

            <div class="article__body">
                <quill-editor v-model="body"
                              ref="myTextEditor"
                              :options="editorOption"
                              style="background-color: #FFFFFF;">
                </quill-editor>
            </div>
        </div>

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

        <v-ons-modal :visible="postingVisible" @click="cancelPostArticle()">
            <p style="text-align: center">
                投稿中... <v-ons-icon icon="fa-spinner" spin></v-ons-icon>
            </p>
        </v-ons-modal>
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
                title: '',
                body: '',
                editorOption: {
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
            articleInput(event) {
                return (this.title !== '') && (this.body !== '');
            },
            editor() {
                return this.$refs.myTextEditor.quill
            }
        },
        methods: {
            init() {
                if (this.$store.state.serviceWorker.isSubscribed !== true) {
                    this.alertRegistration();
                }
            },
            alertRegistration() {
                this.$ons.notification.alert('投稿するにはプッシュ通知を許可する必要があります。').then(function (response) {
                    this.$store.commit('tabBar/show', 2);
                }.bind(this));
            },
            postArticle() {
                this.postingVisible = true;

                const username = this.$store.state.credential.username;
                this.postRequest("/api/user/" + username + "/articles", {title: this.title, body: this.body}, function (response) {
                    this.postingVisible = false;
                    this.title = '';
                    this.body = '';
                    this.$store.commit('tabBar/show', 0);
                    this.$ons.notification.toast('記事を投稿しました。', {timeout: 1000});
                }.bind(this), function () {
                    this.postingVisible = false;
                    this.$ons.notification.toast('記事の投稿に失敗しました。', {timeout: 1000});
                }.bind(this));
            },
            cancelPostArticle() {
                this.postingVisible = false;
            }
        }
    };
</script>
