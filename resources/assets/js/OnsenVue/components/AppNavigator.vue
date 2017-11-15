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

    export default {
        beforeCreate() {
            this.$store.commit('navigator/push', AppTabbar);
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
