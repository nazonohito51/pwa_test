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
                <quill-editor id="quill-editor"
                              v-model="body"
                              ref="myTextEditor"
                              :options="editorOption"
                              style="background-color: #FFFFFF;">
                </quill-editor>
            </div>
        </div>

        <v-ons-modal :visible="postingVisible" @click="cancelPostArticle()">
            <p style="text-align: center">
                投稿中...
                <v-ons-icon icon="fa-spinner" spin></v-ons-icon>
            </p>
        </v-ons-modal>
    </v-ons-page>
</template>

<script>
    import apiClientMixin from '../../mixins/apiClient.js';
    import Quill from 'quill';

    export default {
        mixins: [apiClientMixin],
        data: function () {
            return {
                postingVisible: false,
                title: '',
                body: '',
                maxImageSize: 512,
                editorOption: {
                    placeholder: '本文を入力してください',
                    modules: {
                        toolbar: [
                            [{header: 1}, {header: 2}],
                            [{align: 'center'}, {align: 'right'}],
                            ['image']
                        ],
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
                this.$ons.notification.alert('記事を投稿するにはプッシュ通知を許可する必要があります。').then(function (response) {
                    this.$store.commit('tabBar/show', 2);
                }.bind(this));
            },
            postArticle() {
                this.postingVisible = true;

                this.resizeImages();

                const username = this.$store.state.credential.username;
                this.postRequest("/api/user/" + username + "/articles", {
                    title: this.title,
                    body: this.body
                }, function (response) {
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
            resizeImages: function () {
                const images = document.getElementById('quill-editor').getElementsByTagName('img');

                for (let i = 0; i < images.length; i += 1) {
                    this.resizeImage(images.item(i));
                }
            },
            resizeImage: function (imgElem) {
                if (imgElem.width > this.maxImageSize || imgElem.height > this.maxImageSize) {
                    let ratio, destWidth, destHeight;
                    if (imgElem.width > imgElem.height) {
                        ratio = imgElem.width / this.maxImageSize;
                        destWidth = this.maxImageSize;
                        destHeight = imgElem.height / ratio;
                    } else {
                        ratio = imgElem.height / this.maxImageSize;
                        destWidth = imgElem.width / ratio;
                        destHeight = this.maxImageSize;
                    }

                    this.resizeDataUrl(imgElem.src, destWidth, destHeight, function (dataUrl) {
                        console.log(dataUrl, imgElem);
                        imgElem.src = dataUrl;
                    });
                }
            },
            resizeDataUrl: function (src, width, height, callback) {
                const imgType = src.substring(5, src.indexOf(";"));

                const img = new Image();
                img.onload = function() {
                    let canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    const dest = canvas.toDataURL(imgType);

                    callback(dest);
                };
                img.src = src;
            },
            cancelPostArticle() {
                this.postingVisible = false;
            }
        }
    };
</script>
