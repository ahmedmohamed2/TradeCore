<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), config('locale.rtl'), true) ? 'rtl' : 'ltr' }}">
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
    <div class="position-absolute top-0 end-0 p-3 z-3">
        @include('layouts.partials.locale-switcher', ['asNavItem' => false])
    </div>
    {{ $slot }}

    @include('layouts.footer-scripts')
    @livewireScripts
</body>
</html>
