<template>
    <div>
        <div class="loading" v-if="loading">Loading...</div>
        <div v-if="error" class="error">
            {{ error }}
        </div>
        <!-- articlesがロードされたら各ユーザーの名前を表示する -->
        <div v-for="article in articles" :key="article.id">
            <h2>{{ article.title }}</h2>
            <p>{{ article.body }}</p>
        </div>
    </div>
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

        // ルーティングが変更された時に再度データを取得するために$routeの変更をwatchする
        watch: {
            '$route': 'fetchData'
        },

        methods: {
            fetchData: function () {
                this.loading = true;
                // 取得したデータの結果をarticlesに格納する
                getarticles((function (err, articles) {
                    this.loading = false;
                    if (err) {
                        this.error = err.toString();
                    } else {
                        this.articles = articles;
                    }
                }).bind(this))
            }
        }
    }

    // 擬似的にAPI経由で情報を取得したようにする
    let getarticles = function (callback) {
        setTimeout(function () {
            axios.get("/api/user/test/articles").then(
                response => {
                    callback(null, response.data);
                });
        }, 500);
    }
</script>
