<!doctype html>
<html class="no-js" lang="{{ Lang::locale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="application-name" content="bugflux">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" sizes="16x16 24x24 32x32 48x48" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'bugflux')</title>

    @section('styles')
        <style type="riot"></style>
        <link rel="stylesheet" href="{{ asset('css/icomoon.css') }}">
        <link rel="stylesheet" href="{{ elixir('css/default.css') }}">
    @show

    <script> document.documentElement.className = document.documentElement.className.replace("no-js", "js"); </script>
</head>
<body class="@yield('bodyClass', '')">
    @include('layouts.partials.nav')
    @include('layouts.partials.menu')
    <div class="content">
        @yield('content')
    </div>

    @section('scripts')
        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ elixir('js/default.js') }}"></script>
    @show
</body>
</html>