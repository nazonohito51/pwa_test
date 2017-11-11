<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button>Timeline</v-ons-back-button>
            </div>
            <div class="center">Article</div>
        </v-ons-toolbar>

        <div v-if="isLoaded">
            <div class="card">
                <div class="article">
                    <div v-html="article.title" class="article__title">
                    </div>
                    <div v-html="article.body" class="article__body">
                    </div>
                </div>
                <hr style="margin-top: 50px; margin-bottom: 15px; border-top: 1px dashed #8c8b8b;">
                <div>
                    <img v-for="user in article.like_users" v-bind:src="user.avator_url" style="width: 24px; height: 24px; border-radius: 50%;">
                </div>
                <div style="width: 20%; margin-left: auto;">
                    <button class="button button--material--flat" style="min-height: 1.5em; line-height: 1.5em;" :disabled="isLiked" @click="postLike()">
                        <strong v-bind:style="likeStyle">
                            <v-ons-icon v-bind:icon="likeIcon" style="line-height: 1.5em;"></v-ons-icon> : {{likeCount}}
                        </strong>
                    </button>
                </div>
            </div>

            <!--<v-ons-fab position='bottom right' ripple v-bind:style="{ opacity: likeOpacity }" @click="postLike()">-->
                <!--<v-ons-icon icon="fa-thumbs-o-up"></v-ons-icon>-->
            <!--</v-ons-fab>-->

            <v-ons-card style="width: 100%;">
                <div class="user">
                    <div class="left">
                        <img v-bind:src="article.user.avator_url" style="width: 48px; height: 48px; border-radius: 50%;">
                        {{article.user.nickname}}
                    </div>
                </div>
            </v-ons-card>
        </div>
        <div v-else>
            記事データを読み込み中...
        </div>

        <v-ons-modal :visible="loading" @click="loading=false">
            <p style="text-align: center">
                Loading <v-ons-icon icon="fa-spinner" spin></v-ons-icon>
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
                article_id: null,
                article: null,
                loading: false
            }
        },
        methods: {
            init: function () {
                this.getArticle();
            },
            getArticle: function () {
                this.loading = true;

                this.getRequest("/api/articles/" + this.article_id, function (response) {
//                    this.$ons.notification.toast('いいね！を送信しました。', {timeout: 1000});
                    this.article = response.data.article;

//                    for (let i = 0; i < 50; i++) {
//                        this.article.like_users.push({
//                            name: 'hoge',
//                            avator_url: 'http://localhost:8000/images/avators/EryNomAoNaVZbWp4S1BEmn0pAW8vnX.png'
//                        });
//                    }

                    this.loading = false;
                }.bind(this), function () {
                    this.$ons.notification.toast('記事の取得に失敗しました。', {timeout: 1000});
                    this.loading = false;
                }.bind(this))
            },
            postLike: function () {
                if (this.isLiked !== true) {
                    this.postSync('/api/articles/' + this.article.id + '/like', {}, function () {}, function () {});

//                    const local_storage = window.localStorage;
//                    this.nickname = local_storage.getItem('Settings:nickname');
//                    this.avator_url = local_storage.getItem('Settings:avator_url');
//                    this.article.like_users.push({
//                        name: nickname,
//                        avator_url: avator_url
//                    });

//                    this.postRequest("/api/articles/" + this.article.id + "/like", {}, function (response) {
//                        this.$ons.notification.toast('いいね！を送信しました。', {timeout: 1000});
//                        this.getArticle();
//                    }.bind(this), function () {
//                        this.$ons.notification.toast('いいね！の送信に失敗しました。', {timeout: 1000});
//                    }.bind(this));
                }
            }
        },
        computed: {
            isLoaded: function () {
                return (this.article && this.article_id === this.article.id);
            },
            likeCount: function () {
                return this.article.like_users.length;
            },
            isLiked: function () {
                const username = this.$store.state.credential.username;

                for (let user of this.article.like_users) {
                    if (user.name === username) {
                        return true;
                    }
                }
                return false;
            },
            likeStyle: function () {
                if (this.isLiked) {
                    return {'font-weight': 'bold', 'color': '#ff6977'};
                }

                return {'font-weight': 'normal'};
            },
            likeIcon: function () {
                if (this.isLiked) {
                    return 'fa-thumbs-up';
                }

                return 'fa-thumbs-o-up';
            }
        }
    };
</script>
