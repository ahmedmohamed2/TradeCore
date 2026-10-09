@php
    $direction = in_array(app()->getLocale(), config('locale.rtl'), true) ? 'rtl' : 'ltr';
    $adminlteCss = $direction === 'rtl' ? 'assets/css/adminlte.rtl.css' : 'assets/css/adminlte.css';
@endphp
<title>@yield('title', __('menu.dashboard'))</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<meta name="color-scheme" content="light dark" />
<meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
<meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
<meta name="supported-color-schemes" content="light dark" />

@if ($direction === 'rtl')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
@else
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
@endif

<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
  crossorigin="anonymous"
/>
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
  crossorigin="anonymous"
/>
<link rel="preload" href="{{ asset($adminlteCss) }}" as="style" />
<link rel="stylesheet" href="{{ asset($adminlteCss) }}" />

<style>
    [dir="rtl"] body {
        font-family: "Cairo", sans-serif;
    }
    .ltr-nums {
        direction: ltr;
        unicode-bidi: embed;
    }
</style>

@yield('css')

<script>
  (() => {
    'use strict';
    const STORAGE_KEY = 'lte-theme';
    let stored = null;
    try {
      stored = localStorage.getItem(STORAGE_KEY);
    } catch {
      // localStorage may be unavailable (private mode, sandboxed iframe).
    }
    const prefersDark = globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
    let resolved = 'light';
    if (stored === 'dark' || stored === 'light') {
      resolved = stored;
    } else if (prefersDark) {
      resolved = 'dark';
    }
    document.documentElement.setAttribute('data-bs-theme', resolved);
    document.documentElement.style.colorScheme = resolved;
  })();
</script>
