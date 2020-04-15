<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>zernovoz.su</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('black-dashboard/assets/css/black-dashboard.css') }}">
        <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    </head>
    <body>
        @csrf
        <div id="app">
            <header-component></header-component>
            <app-component></app-component>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://unpkg.com/ionicons@4.0.0-11/dist/ionicons.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    </body>
</html>
