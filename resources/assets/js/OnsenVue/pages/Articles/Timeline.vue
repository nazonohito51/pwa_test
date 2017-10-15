<template>
    <v-ons-page @show="init()">
        <v-ons-toolbar>
            <div class="center">Timeline</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-item v-for="article in articles" :key="article.id">
                <v-ons-card>
                    <div class="user">
                        <div class="left">
                            <v-ons-icon icon="md-face" class="list-item__icon"></v-ons-icon>
                            {{article.user.nickname}}
                        </div>
                    </div>
                    <div class="title">
                        {{article.title}}
                    </div>
                    <div class="content">
                        {{article.body}}
                    </div>
                </v-ons-card>
            </v-ons-list-item>
        </v-ons-list>

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
                loading: false,
                articles: function () { return [] }
            }
        },
        methods: {
            init: function () {
                this.fetchData();
            },
            fetchData: function () {
                this.loading = true;

//                setTimeout(function () {
                this.getRequest("/api/articles", function (response) {
                    this.articles = response.data.articles;
                    this.loading = false;
                }.bind(this), function () {
                    this.$ons.notification.toast('ツイートの一覧の取得に失敗しました。', {timeout: 2000});
                    this.loading = false;
                }.bind(this));
//                }.bind(this), 5000);
            }
        }
    }
</script>
