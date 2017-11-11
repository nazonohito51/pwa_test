<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="/favicon.png" sizes="48x48">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#FFFFFF">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="app-username" content="">
    <meta name="app-nickname" content="">
    <meta name="api-token" content="">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('styles')
</head>
<body>

<div id="app">
    @yield('content')
</div>

@yield('scripts')
</body>
</html>
