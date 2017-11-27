<template>
    <v-ons-navigator swipeable
                     :page-stack="pageStack"
                     :pop-page="storePop"
                     :options="{
                       animation: 'none'
                     }"
    ></v-ons-navigator>
</template>

<script>
    import AppTabbar from './AppTabbar.vue';
    import Detail from '../pages/Articles/Detail.vue';

    export default {
        beforeCreate() {
            this.$store.commit('navigator/push', AppTabbar);

            if (/^\/app\/article\/\d+$/.test(location.pathname)) {
                const matches = location.pathname.match(/\d$/);

                history.replaceState({page: 'Timeline'}, 'timeline page', '/app');
                history.pushState({page: 'Detail', article_id: matches[0]}, 'article detail page', '/app/article/' + matches[0]);
                this.$store.commit('navigator/push', {
                    extends: Detail,
                    data: function () {
                        return {
                            article_id: matches[0],
                            article: null
                        };
                    }
                });
            } else if (/^\/app\/?$/.test(location.pathname) === false) {
                location.href = '/app';
            }
        },
        computed: {
            pageStack() {
                return this.$store.state.navigator.stack;
            }
        },
        methods: {
            storePop() {
                this.$store.commit('navigator/pop');
            }
        }
    };
</script>
