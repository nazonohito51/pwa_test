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
    import Timeline from '../pages/Articles/Timeline.vue';
    import Editor from '../pages/Articles/Editor.vue';
    import Settings from '../pages/Settings.vue';

    export default {
        data () {
            return {
                activeIndex: 0,
                tabs: [
                    {
                        icon: this.md() ? null : 'fa-list',
                        label: 'Timeline',
                        page: Timeline
                    },
                    {
                        icon: this.md() ? null : 'fa-magic',
                        label: 'Tweet',
                        page: Editor
                    },
                    {
                        icon: this.md() ? null : 'fa-gear',
                        label: 'Settings',
                        page: Settings
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
