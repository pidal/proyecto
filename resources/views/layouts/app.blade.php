<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="@yield('keywords')">
    <meta name="author" content="@yield('author')">
    <meta name="description" content="@yield('description')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="csrf-token" content="{{{ csrf_token() }}}">

    <title>@yield('title', 'SSM')</title>


    <script src="/css/boostrap-rebot.css/bootstrap.min.css"></script>
    <link rel="stylesheet" property="stylesheet" href="css/app.css">
    @yield('styles')
    @yield('head')
</head>
<body background="/image/background.jpg" bgcolor="FFCECB" style="background-position: center">
@section('header')
@show

@yield('content')


@section('footer')
@show

<script src="/js/boostrap.js/bootstrap.min.js"></script>
<script src="/js/app.js"></script>
@yield('scripts')
</body>
</html>