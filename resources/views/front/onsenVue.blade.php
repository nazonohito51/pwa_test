@extends('layouts.onsen')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsenui.css">
    <link rel="stylesheet" href="https://unpkg.com/onsenui/css/onsen-css-components.min.css">
@endsection

@section('scripts')
    {{--<script src="{{ asset('js/onsenVue.js') }}"></script>--}}

    <script src="https://unpkg.com/vue@2.4.2/dist/vue.js"></script>
    <script src="https://unpkg.com/onsenui/js/onsenui.min.js"></script>
    <script src="https://unpkg.com/vue-onsenui@2.1.1/dist/vue-onsenui.js"></script>
    <script>
        const page2 = {
            key: 'page2',
            template: '#page2'
        };

        const page1 = {
            key: 'page1',
            template: '#page1',
            methods: {
                push() {
                    this.$emit('push-page', page2);
                }
            }
        };

        var vm = new Vue({
            el: '#app',
            template: '#main',
            data() {
                return {
                    pageStack: [page1]
                };
            }
        });
    </script>
@endsection

@section('vue_templates')
    <template id="main">
        @verbatim
        <v-ons-navigator swipeable
                         :page-stack="pageStack"
                         @push-page="pageStack.push($event)"
        ></v-ons-navigator>
        @endverbatim
    </template>

    <template id="page1">
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

    <template id="page2">
        <v-ons-page>
            <v-ons-toolbar>
                <div class="left">
                    <v-ons-back-button>Page 1</v-ons-back-button>
                </div>
                <div class="center">Page 2</div>
            </v-ons-toolbar>
            <p style="text-align: center">This is the second page</p>
        </v-ons-page>
    </template>

    <template id="hello_button">
        <v-ons-page>
            <v-ons-toolbar>
                <div class="center">Title</div>
            </v-ons-toolbar>

            <p style="text-align: center">
                <v-ons-button @click="$ons.notification.alert('Hello World!')">
                    Click me!
                </v-ons-button>
            </p>
        </v-ons-page>
    </template>
@endsection

@section('content')
@endsection
