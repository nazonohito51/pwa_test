<template>
    <v-ons-page>
        <v-ons-tabbar position="auto"
                      :modifier="md ? 'autogrow white-content' : null"
                      :tabs="tabs"
                      :visible="true"
                      :index.sync="activeIndex"
                      swipeable
                      :on-swipe="md ? onSwipe : null"
                      :tabbar-style="swipeTheme"
        >
        </v-ons-tabbar>
    </v-ons-page>
</template>

<script>
    import Page1 from '../pages/Page1.vue';
    import Timeline from '../pages/Articles/Timeline.vue';
    import Editor from '../pages/Articles/Editor.vue';
    import Settings from '../pages/Settings.vue';

    const red = [244, 67, 54];
    const blue = [30, 136, 229];
    const green = [20, 190, 22];
    const gray = [100, 100, 100];
    // Just a linear interpolation formula
    const lerp = (x0, x1, t) => parseInt((1 - t) * x0 + t * x1, 10);

    export default {
        data () {
            return {
                tabs: [
                    {
                        icon: this.md ? null : 'fa-list',
                        label: 'Timeline',
                        page: Timeline,
                        theme: red
                    },
                    {
                        icon: this.md ? null : 'fa-magic',
                        label: 'Editor',
                        page: Editor,
                        theme: blue
                    },
                    {
                        icon: this.md ? null : 'fa-gear',
                        label: 'Settings',
                        page: Settings,
                        theme: green
                    }
                ],
                colors: red,
                animationOptions: {}
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
            },
            swipeTheme: function () {
                return this.md && {
                    backgroundColor: `rgb(${this.colors.join(',')})`,
                    transition: `all ${this.animationOptions.duration || 0}s ${this.animationOptions.timing || ''}`
                }
            },
        },
        methods: {
            onSwipe: function (index, animationOptions) {
                this.animationOptions = animationOptions;

                const a = Math.floor(index), b = Math.ceil(index), ratio = index % 1;
                this.colors = this.colors.map((c, i) => {
                    return lerp(this.tabs[a].theme[i], this.tabs[b].theme[i], ratio);
                });
            }
        }
    };
</script>

<style>
    /* Custom 'white-content' modifier */

    .page--material .toolbar--white-content__center,
    .page--material .toolbar-button--white-content,
    .page--material :checked + .tabbar--white-content__button {
        color: white;
    }

    .page--material .tabbar--white-content__button {
        color: rgba(255, 255, 255, 0.7);
    }

    .page--material .tabbar--white-content__border {
        background-color: white;
    }
</style>
