<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button>Timeline</v-ons-back-button>
            </div>
            <div class="center">Article</div>
        </v-ons-toolbar>

        <div class="article">
            <div v-html="article.title" class="article__title">
            </div>
            <div v-html="article.body" class="article__body">
            </div>
        </div>

        <v-ons-card style="width: 100%;">
            <div class="user">
                <div class="left">
                    <img v-bind:src="article.user.avator_url" style="width: 48px; height: 48px; border-radius: 50%;">
                    {{article.user.nickname}}
                </div>
            </div>
        </v-ons-card>
    </v-ons-page>
</template>

<script>
    export default {
        data: function () {
            return {
                article: null
            }
        }
    };
</script>
