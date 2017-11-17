<template>
    <v-ons-page>
        <v-ons-tabbar position="auto"
                      :tabs="tabs"
                      :visible="true"
                      :index.sync="activeIndex"
                      swipeable
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
                tabs: [
                    {
                        icon: this.md() ? null : 'fa-list',
                        label: 'Timeline',
                        page: Timeline
                    },
                    {
                        icon: this.md() ? null : 'fa-magic',
                        label: 'Editor',
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
        computed: {
            activeIndex: {
                get: function () {
                    return this.$store.state.tabBar.activeIndex;
                },
                set: function (index) {
                    this.$store.commit('tabBar/show', index);
                }
            }
        },
        methods: {
            md() {
                return this.$ons.platform.isAndroid();
            }
        }
    };
</script>
