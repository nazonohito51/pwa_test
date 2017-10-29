<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="left">
                <v-ons-back-button>Timeline</v-ons-back-button>
            </div>
            <div class="center">Article</div>
        </v-ons-toolbar>

        <div class="card">
            <div class="article">
                <div v-html="article.title" class="article__title">
                </div>
                <div v-html="article.body" class="article__body">
                </div>
            </div>
            <hr style="margin-top: 50px; margin-bottom: 15px; border-top: 1px dashed #8c8b8b;">
            <div style="width: 25%; margin-left: auto;">
                <!--<v-ons-icon icon="fa-thumbs-o-up"></v-ons-icon> : 321-->
                <button class="button button--material--flat" style="min-height: 1.5em; line-height: 1.5em;">
                    <v-ons-icon icon="fa-thumbs-o-up" style="line-height: 1.2em;"></v-ons-icon> : 321
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
    </v-ons-page>
</template>

<script>
    export default {
        data: function () {
            return {
                article: null,
                likeOpacity: 0.3
            }
        },
        methods: {
            postLike: function () {
                this.likeOpacity = 1.0;
            }
        }
    };
</script>
