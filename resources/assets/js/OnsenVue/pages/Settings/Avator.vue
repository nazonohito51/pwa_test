<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button>Settings</v-ons-back-button>
            </div>
            <div class="center">アイコン</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-item>
                <label for="icon-upload">
                    <a class="btn btn-primary">
                        <i class="fa fa-camera" aria-hidden="true"></i>  アイコン画像をアップロードする
                    </a>
                    <input id="icon-upload" type="file" style="display: none;" @change="loadImage"/>
                </label>
                <!--<img src="/images/avators/no_image.png" style="display: block; margin: 0 auto; width: 96px; height: 96px; border-radius: 50%;">-->
            </v-ons-list-item>
            <v-ons-list-item>
                <v-ons-button modifier="large" @click="initCropper()">アイコン画像をアップロードする</v-ons-button>
            </v-ons-list-item>
        </v-ons-list>

        <v-ons-modal :visible="cropperVisible">
            <div style="width: 80%; margin: 0 auto;">
                <div style="margin: 30px auto;">
                    <img id="uploadImage" src="/images/avators/nazonohito51.jpeg" style="max-width: 100%;">
                </div>
                <div style="margin: 10px auto; width: 80%;">
                    <v-ons-button modifier="large--cta" style="margin: 6px;">アップロード</v-ons-button>
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
                cropperVisible: false
            }
        },
        methods: {
            initCropper: function () {
                this.cropperVisible = true;

                const image = document.getElementById('uploadImage');
                this.cropper = new Cropper(image, {
                    viewMode: 1,
                    aspectRatio: 1,
                    crop: function(e) {
                        console.log(e.detail.x);
                        console.log(e.detail.y);
                        console.log(e.detail.width);
                        console.log(e.detail.height);
                        console.log(e.detail.rotate);
                        console.log(e.detail.scaleX);
                        console.log(e.detail.scaleY);
                    }
                });
            },
            cancelCropper: function () {
                this.cropperVisible = false;
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
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
            }
        }
    };
</script>
