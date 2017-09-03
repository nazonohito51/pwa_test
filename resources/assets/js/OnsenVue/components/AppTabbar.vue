<template>
    <v-ons-page>
        <v-ons-tabbar position="auto"
                      :tabs="tabs"
                      :visible="true"
                      :index.sync="activeIndex"
        >
        </v-ons-tabbar>
    </v-ons-page>
</template>

<script>
    import Page1 from '../pages/Page1.vue';
    import Page2 from '../pages/Page2.vue';

    export default {
        data () {
            return {
                activeIndex: 0,
                tabs: [
                    {
                        icon: this.md() ? null : 'fa-list',
                        label: 'Page1',
                        page: Page1
                    },
                    {
                        icon: this.md() ? null : 'fa-magic',
                        label: 'Page2',
                        page: Page2
                    },
                    {
                        icon: this.md() ? null : 'fa-gear',
                        label: 'Page2',
                        page: Page2
                    }
                ]
            };
        },
        methods: {
            md() {
                return this.$ons.platform.isAndroid();
            }
        }
    };
</script>
