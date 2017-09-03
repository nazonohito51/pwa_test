<template>
    <v-ons-navigator swipeable
                     :page-stack="pageStack"
                     :pop-page="storePop"
    ></v-ons-navigator>
</template>

<script>
    import Page1 from '../pages/Page1.vue';
    import Page2 from '../pages/Page2.vue';

    export default {
        data () {
            return {
                pages: [
                    {
                        component: Page1,
                        label: 'page1 label',
                        desc: 'page1 desc.'
                    },
                    {
                        component: Page2,
                        label: 'page2 label',
                        desc: 'page2 desc.'
                    }
                ]
            };
        },
        beforeCreate() {
            this.$store.commit('navigator/push', Page1);
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
