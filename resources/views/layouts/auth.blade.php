<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @section('title', config('app.name'))
    @include('layouts.head')

    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="login-page bg-body-secondary">
    {{ $slot }}

    @include('layouts.footer-scripts')
    @livewireScripts
</body>
</html>
