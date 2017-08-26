@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">vue test</div>

                    <div id="app">
                        @{{ hoge }}
                        <router-link :to="{ name: 'top' }">トップページ</router-link>
                        <router-link :to="{ name: 'users' }">ユーザー一覧ページ</router-link>
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
