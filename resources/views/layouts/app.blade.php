<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Maven+Pro:wght@400;500;600;700;800;900&family=Trirong:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @viteReactRefresh
    @vite(['resources/scss/app.scss', 'resources/js/App.jsx'])
</head>
<body>
<div id="application-root"></div>
</body>
</html>
