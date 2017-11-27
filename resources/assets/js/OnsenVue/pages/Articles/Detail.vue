<template>
    <v-ons-page @init="init()">
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button @click="popHistory">Timeline</v-ons-back-button>
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
                <hr v-if="displayLikeDiv" style="margin-top: 50px; margin-bottom: 15px; border-top: 1px dashed #8c8b8b;">
                <div>
                    <img v-for="user in like_users" v-bind:src="user.avatar_url" style="width: 24px; height: 24px; border-radius: 50%;">
                </div>
                <div v-if="isRegistered" style="width: 20%; margin-left: auto;">
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

            <div class="card">
                <div class="user">
                    <div class="left">
                        <img v-bind:src="article.user.avatar_url" style="width: 48px; height: 48px; border-radius: 50%;">
                        {{article.user.nickname}}
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                <svg class="progress-circular progress-circular--indeterminate">
                    <circle class="progress-circular__background"/>
                    <circle class="progress-circular__primary progress-circular--indeterminate__primary"/>
                    <circle class="progress-circular__secondary progress-circular--indeterminate__secondary"/>
                </svg>
            </div>
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
                like_users: [],
                loading: false
            }
        },
        methods: {
            init: function () {
                this.like_users = [];

                this.getArticle();
            },
            getArticle: function () {
//                this.loading = true;

                this.getRequest("/api/articles/" + this.article_id, function (response) {
                    this.article = response.data.article;
                    this.loading = false;
                    this.getLikeUsers();
                }.bind(this), function () {
                    if (!this.article) {
                        this.$ons.notification.toast('記事の取得に失敗しました。', {timeout: 1000});
                        this.loading = false;
                        this.$store.commit('navigator/pop');
                    }
                }.bind(this))
            },
            getLikeUsers: function () {
                this.getRequest("/api/articles/" + this.article_id + "/like", function (response) {
                    this.like_users = response.data.users;
                }.bind(this), function () {
                })
            },
            postLike: function () {
                if (this.isRegistered && this.isLiked !== true) {
                    this.postSync('/api/articles/' + this.article.id + '/like', {}, function () {}, function () {});

                    const username = this.$store.state.credential.username;
                    const avatar_url = this.$store.state.credential.avatar_url;
                    this.like_users.push({
                        name: username,
                        avatar_url: avatar_url
                    });

//                    const local_storage = window.localStorage;
//                    this.nickname = local_storage.getItem('Settings:nickname');
//                    this.avatar_url = local_storage.getItem('Settings:avatar_url');
//                    this.article.like_users.push({
//                        name: nickname,
//                        avatar_url: avatar_url
//                    });

//                    this.postRequest("/api/articles/" + this.article.id + "/like", {}, function (response) {
//                        this.$ons.notification.toast('いいね！を送信しました。', {timeout: 1000});
//                        this.getArticle();
//                    }.bind(this), function () {
//                        this.$ons.notification.toast('いいね！の送信に失敗しました。', {timeout: 1000});
//                    }.bind(this));
                }
            },
            popHistory: function (event) {
                history.back();
            }
        },
        computed: {
            isRegistered: function () {
                return this.$store.state.serviceWorker.isSubscribed;
            },
            displayLikeDiv: function () {
                return (this.isRegistered || (this.likeCount > 0));
            },
            isLoaded: function () {
                return this.article;
            },
            likeCount: function () {
                return this.like_users.length;
            },
            isLiked: function () {
                const username = this.$store.state.credential.username;

                for (let user of this.like_users) {
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
