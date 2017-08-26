<template>
    <div>
        <div class="loading" v-if="loading">ロード中...</div>
        <div v-if="error" class="error">
            {{ error }}
        </div>
        <!-- usersがロードされたら各ユーザーの名前を表示する -->
        <div v-for="user in users" :key="user.id">
            <h2>{{ user.name }}</h2>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                loading: false,
                // 初期値の空配列を定義
                users: function () { return [] },
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
                // 取得したデータの結果をusersに格納する
                getUsers((function (err, users) {
                    this.loading = false;
                    if (err) {
                        this.error = err.toString();
                    } else {
                        this.users = users;
                    }
                }).bind(this))
            }
        }
    }

    // 擬似的にAPI経由で情報を取得したようにする
    let getUsers = function (callback) {
        setTimeout(function () {
            callback(null, [
                {
                    id: '001',
                    name: 'Takuya Tejima'
                },
                {
                    id: '002',
                    name: 'Yohei Noda'
                }
            ])
        }, 1000)
    }
</script>
