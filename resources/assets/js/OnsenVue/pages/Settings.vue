<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="center">Settings</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header>アカウント</v-ons-list-header>
            <v-ons-list-item modifier="chevron" tappable @click="pushUserPage()">ユーザ設定</v-ons-list-item>
            <v-ons-list-item modifier="chevron" tappable>通知設定</v-ons-list-item>
        </v-ons-list>
    </v-ons-page>
</template>

<script>
    import User from './Settings/User.vue';

    export default {
        methods: {
            pushUserPage() {
                this.$store.commit('navigator/push', User);
            }
        }
    };
</script>
