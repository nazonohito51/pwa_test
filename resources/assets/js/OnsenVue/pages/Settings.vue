<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="center">Settings</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header>アカウント</v-ons-list-header>
            <v-ons-list-item modifier="chevron" tappable @click="pushUserPage()">ユーザ設定</v-ons-list-item>
            <ons-list-item>
                <label class="center" for="notification_switch">
                    プッシュ通知
                </label>
                <div class="right">
                    <v-ons-switch input-id="notification_switch" @change="notificationChange">
                    </v-ons-switch>
                </div>
            </ons-list-item>
        </v-ons-list>
    </v-ons-page>
</template>

<script>
    import User from './Settings/User.vue';

    export default {
        methods: {
            pushUserPage() {
                this.$store.commit('navigator/push', User);
            },
            notificationChange(event) {
                if (event.value === true) {
                    this.$store.commit('serviceWorker/subscribe');
                } else {
                    this.$store.commit('serviceWorker/unsubscribe');
                }
            }
        }
    };
</script>
