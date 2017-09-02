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
        var vm = new Vue({
            el: '#app',
            template: '#main-page'
        });
    </script>
@endsection

@section('vue_templates')
    <template id="main-page">
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
