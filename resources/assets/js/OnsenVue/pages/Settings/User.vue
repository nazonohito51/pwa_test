<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="center">ユーザ設定</div>
        </v-ons-toolbar>

        <v-ons-list>
            <v-ons-list-header>ニックネーム</v-ons-list-header>
            <v-ons-list-item :modifier="$ons.platform.isAndroid() ? 'nodivider' : ''">
                <div class="left">
                    <v-ons-icon icon="md-face" class="list-item__icon"></v-ons-icon>
                </div>
                <label class="center">
                    <v-ons-input float maxlength="20"
                                 placeholder="Name"
                                 v-model="name"
                    >
                    </v-ons-input>
                </label>
            </v-ons-list-item>
        </v-ons-list>
    </v-ons-page>
</template>

<script>
    export default {
        data() {
            return {
                name: 'ほげユーザ'
            };
        }
    };
</script>
