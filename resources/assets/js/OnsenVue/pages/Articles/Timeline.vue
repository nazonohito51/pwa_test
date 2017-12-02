<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Timeline</div>
        </v-ons-toolbar>

        <v-ons-pull-hook
                :action="pullToRefresh"
                :fixed-content="md"
                :height="md ? 84 : 64"
                :on-pull="md && updatePullToRefreshStyle || null"
                @changestate="pullToRefreshState = $event.state"
        >
            <div class="pull-hook-progress">
                <v-ons-progress-circular
                        :value="ratio * 100"
                        :indeterminate="pullToRefreshState === 'action'"
                        :style="{ transform: `rotate(${ratio}turn)` }"
                ></v-ons-progress-circular>
            </div>
        </v-ons-pull-hook>

        <ul class="list list--material">
            <li class="list-item list-item--material" v-for="article in articles" :key="article.id" @click="pushDetailPage(article)" v-observe-visibility="(isVisible, entry) => visibilityChanged(isVisible, entry, article)">
                <div class="list-item__left list-item--material__left">
                    <img class="list-item__thumbnail list-item--material__thumbnail" v-bind:src="article.user.avatar_url">
                </div>

                <div class="list-item__center list-item--material__center" style="width: 70%;">
                    <div class="list-item__title list-item--material__title">{{article.title}}</div>
                    <div class="list-item__subtitle list-item--material__subtitle" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;">{{article.description}}</div>
                </div>

                <div class="list-item__right list-item--material__right">
                    <!--<i class="angle-right"></i>-->
                    <v-ons-icon icon="md-chevron-right"></v-ons-icon>
                </div>
            </li>
        </ul>

        <!--<v-ons-list>-->
            <!--<v-ons-list-item v-for="article in articles" :key="article.id">-->
                <!--<v-ons-card style="width: 100%;">-->
                    <!--<div class="user">-->
                        <!--<div class="left">-->
                            <!--&lt;!&ndash;<v-ons-icon icon="md-face" class="list-item__icon"></v-ons-icon>&ndash;&gt;-->
                            <!--<img v-bind:src="article.user.avatar_url" onerror="this.src='/images/avatars/no_image.png'" style="width: 48px; height: 48px; border-radius: 50%;">-->
                            <!--{{article.user.nickname}}-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--&lt;!&ndash;<div class="title">&ndash;&gt;-->
                        <!--&lt;!&ndash;{{article.title}}&ndash;&gt;-->
                    <!--&lt;!&ndash;</div>&ndash;&gt;-->
                    <!--<div class="content">-->
                        <!--{{article.body}}-->
                    <!--</div>-->
                <!--</v-ons-card>-->
            <!--</v-ons-list-item>-->
        <!--</v-ons-list>-->

        <v-ons-modal :visible="loading" @click="loading=false">
            <p style="text-align: center">
                Loading <v-ons-icon icon="fa-spinner" spin></v-ons-icon>
            </p>
        </v-ons-modal>
    </v-ons-page>
</template>

<script>
    import apiClientMixin from '../../mixins/apiClient.js';
    import Detail from './Detail.vue';

    export default {
        mixins: [apiClientMixin],
        data: function () {
            return {
                loading: false,
                pullToRefreshState: 'initial',
                ratio: 0,
                articles: function () { return [] },
                article_details: {}
            }
        },
        methods: {
            init: function () {
                const local_storage = window.localStorage;
                if (local_storage.getItem('Timeline:articles')) {
                    this.articles = JSON.parse(local_storage.getItem('Timeline:articles'));
                }

                this.fetchData().then(function () {}).catch(function () {});
            },
            fetchData: function () {
                return new Promise((resolve, reject) => {
                    if (this.articles.length === 0) {
                        this.loading = true;
                    }

                    this.getRequest("/api/articles", (response) => {
                        this.articles = response.data.articles;
                        this.resetArticleDetails();
                        const local_storage = window.localStorage;
                        local_storage.setItem('Timeline:articles', JSON.stringify(this.articles));
                        this.loading = false;
                        resolve();
                    }, () => {
//                    this.$ons.notification.toast('記事の一覧の取得に失敗しました。', {timeout: 2000});
                        this.loading = false;
                        reject();
                    });
                });
            },
            resetArticleDetails: function () {
                const detail_ids = Object.keys(this.article_details);
                const article_ids = this.articles.map(function (article) {
                    return article.id.toString();
                });

                detail_ids.forEach(function (element, index, array) {
                    if (article_ids.indexOf(element) < 0) {
                        delete this.article_details[element];
                    }
                }.bind(this));
            },
            pushDetailPage: function (article) {
                history.pushState({page: 'Detail', article_id: article.id}, 'article detail page', '/app/article/' + article.id);
                const article_detail = this.article_details[article.id] ? this.article_details[article.id] : null;
                this.$store.commit('navigator/push', {
                    extends: Detail,
                    data: function () {
                        return {
                            article_id: article.id,
                            article: article_detail
                        };
                    }
                });
            },
            visibilityChanged: function (isVisible, entry, article) {
                if (isVisible) {
                    this.prefetch(article.url, function (response) {
                        this.article_details[article.id] = response.data.article;
                    }.bind(this), function () {
                    });
//                    entry.target._vue_intersectionObserver.unobserve(entry.target);
                }
            },
            pullToRefresh(done) {
                this.fetchData().then(function () {
                    done();
                }).catch(function () {
                    this.$ons.notification.toast('記事の一覧の取得に失敗しました。', {timeout: 2000});
                    done();
                });
            },
            updatePullToRefreshStyle(ratio) {
                this.ratio = ratio;
            }
        }
    }
</script>

<style>
    .pull-hook-spinner {
        color: #666;
        transition: transform .25s ease-in-out;
    }

    .pull-hook-progress {
        background-color: white;
        width: 32px;
        height: 32px;
        margin: 30px auto 0;
        border-radius: 100%;
        position: relative;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        display: inline-block;
        line-height: 0px;
    }

    .pull-hook-progress .progress-circular {
        width: 24px;
        height: 24px;
        position: absolute;
        top: 4px;
        left: 4px;
    }

    .pull-hook-progress .progress-circular__primary {
        transition: stroke-dashoffset 0s;
    }
</style>
