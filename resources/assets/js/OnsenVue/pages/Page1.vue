<template>
    <v-ons-page>
        <v-ons-toolbar>
            <div class="center">Page 1</div>
        </v-ons-toolbar>
        <p style="text-align: center">
            This is the first page
            <v-ons-button @click="push">Push Page 2</v-ons-button>
        </p>
    </v-ons-page>
</template>

<script>
    import Page2 from './Page2.vue';

    export default {
        methods: {
            push() {
                this.$store.commit('navigator/push', Page2);
            }
        }
    };
</script>
