<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Reporte Ciber') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="auth-page">
            <a href="/" class="auth-brand">
                <span class="auth-brand-icon">🛡️</span>
                <span class="auth-brand-name">Reporte Ciber</span>
                <span class="auth-brand-tag">Convivencia digital segura</span>
            </a>

            <div class="auth-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>