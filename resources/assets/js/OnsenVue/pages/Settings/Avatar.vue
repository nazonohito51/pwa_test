<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button @click="popHistory">Settings</v-ons-back-button>
            </div>
            <div class="center">アイコン</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-item>
                <img v-bind:src="avatar_url" style="display: block; margin: 20px auto 10px; width: 128px; height: 128px; border-radius: 50%;">
            </v-ons-list-item>
            <v-ons-list-item>
                <label for="icon-upload">
                    <a class="btn btn-primary">
                        <i class="fa fa-camera" aria-hidden="true"></i>  アイコン画像をアップロードする
                    </a>
                    <input id="icon-upload" type="file" style="display: none;" @change="loadImage"/>
                </label>
            </v-ons-list-item>
            <!--<v-ons-list-item>-->
                <!--<v-ons-button modifier="large" @click="initCropper()">アイコン画像をアップロードする</v-ons-button>-->
            <!--</v-ons-list-item>-->
        </v-ons-list>

        <v-ons-modal :visible="cropperVisible">
            <div style="width: 80%; margin: 0 auto;">
                <div style="margin: 30px auto;">
                    <img id="uploadImage" src="" style="max-width: 100%;">
                </div>
                <div style="margin: 10px auto; width: 80%;">
                    <v-ons-button modifier="large--cta" @click="uploadImage();" style="margin: 6px;">アップロード</v-ons-button>
                    <v-ons-button modifier="material--flat" @click="cancelCropper();" style="margin: 6px;">キャンセル</v-ons-button>
                </div>
            </div>
        </v-ons-modal>
    </v-ons-page>
</template>

<script>
    import apiClientMixin from '../../mixins/apiClient.js';
    import Cropper from 'cropperjs';

    export default {
        mixins: [apiClientMixin],
        data: function () {
            return {
                cropper: null,
                cropperVisible: false,
                rand: null
            }
        },
        computed: {
            avatar_url: function () {
                return this.$store.state.credential.avatar_url + '?self&rand=' + this.rand;
            }
        },
        methods: {
            init: function () {
                this.regenerateRand();
//                this.getavatarUrl();
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
            loadImage: function (event) {
                const file = event.target.files[0];
                const reader = new FileReader();

                if(file.type.indexOf("image") < 0){
                    return false;
                }

                reader.onload = (function (file) {
                    return function (event) {
                        const image = document.getElementById('uploadImage');
                        image.src = event.target.result;

                        this.initCropper();
                    }.bind(this);
                }.bind(this))(file);

                reader.readAsDataURL(file);
            },
            initCropper: function () {
                this.cropperVisible = true;

                const image = document.getElementById('uploadImage');
                this.cropper = new Cropper(image, {
                    viewMode: 1,
                    aspectRatio: 1
                });
            },
            cancelCropper: function () {
                this.cropperVisible = false;
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
            },
            uploadImage: function () {
                if (this.cropper) {
                    const username = this.$store.state.credential.username;
                    const canvas = this.cropper.getCroppedCanvas();

                    let resize_canvas = document.createElement('canvas');
                    let resize_canvas_context = resize_canvas.getContext('2d');
                    resize_canvas.width = 96;
                    resize_canvas.height = 96;
                    resize_canvas_context.drawImage(canvas, 0, 0, canvas.width, canvas.height, 0, 0, 96, 96);

                    //const base64 = resize_canvas.toDataURL('image/png').replace(/^.*,/, ''); // remove "data:image/png;base64,"
                    const base64 = resize_canvas.toDataURL('image/png');

                    this.putRequest("/api/user/" + username + "/avatar", {image: base64}, function (response) {
                        this.$store.commit('navigator/pop');
                        this.popHistory();
                        this.$ons.notification.toast('アイコン画像をアップロードしました。', {timeout: 1000});
                    }.bind(this), function () {
                        this.$ons.notification.toast('アイコン画像をアップロードに失敗しました。', {timeout: 1000});
                    }.bind(this));
                } else {
                    this.$ons.notification.toast('画像のアップロードに失敗しました。', {timeout: 2000});
                }

                this.cropperVisible = false;
            },
            popHistory: function () {
                history.back();
            }
//            updateavatarUrl: function() {
//                const api_token = this.$store.state.credential.api_token;
//
//                this.putRequest("/api/user/" + api_token, {}, function (response) {
//                    console.log(response);
//                    this.$store.commit('credential/update', {'avatar_url': response.data.user.avatar_url,});
//                }.bind(this), function () {
//                });
//            }
        }
    };
</script>
