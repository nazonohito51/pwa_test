<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="center">タイムライン</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-item v-for="article in articles" :key="article.id">
                <v-ons-card>
                    <div class="user">
                        <div class="left">
                            <v-ons-icon icon="md-face" class="list-item__icon"></v-ons-icon>
                            username
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

    </v-ons-page>
</template>

<script>
    export default {
        data: function () {
            return {
                loading: false,
                // 初期値の空配列を定義
                articles: function () { return [] },
                error: null
            }
        },
        // 初期化時にデータを取得する
        created: function () {
            this.fetchData()
        },
        methods: {
            fetchData: function () {
                this.loading = true;
                // 取得したデータの結果をarticlesに格納する
                getarticles((function (err, response) {
                    this.loading = false;

                    if (response.error) {
                        this.error = response.error.toString();
                    } else {
                        this.articles = response.articles;
                    }
                }).bind(this))
            }
        }
    }

    // 擬似的にAPI経由で情報を取得したようにする
    let getarticles = function (callback) {
        setTimeout(function () {
            axios.get("/api/articles").then(
                response => {
                    console.log(response);
                    callback(null, response.data);
                });
        }, 500);
    }
</script>
