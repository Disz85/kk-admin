<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        @viteReactRefresh
        @vite(['resources/scss/app.scss', 'resources/js/App.jsx'])
    </head>
    <body>
        <div id="application-root"></div>
    </body>
</html>
