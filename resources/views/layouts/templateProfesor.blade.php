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

    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" property="stylesheet" href="/css/app.css">
    @yield('styles')
    @yield('head')
    <style>
        body {font-family: Arial, Helvetica, sans-serif;}
        * {box-sizing: border-box;}

        /* Position the navbar container inside the image */
        .menu {
            position: absolute;
            margin: 20px;
            width: auto;
        }

        /* The navbar */
        .topnav {
            overflow: hidden;
            background-color: #333;
        }

        /* Navbar links */
        .topnav a {
            float: left;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
        }

        .topnav a:hover {
            background-color: #ddd;
            color: black;
        }

    </style>

</head>
<body background="/image/background.jpg" bgcolor="FFCECB" style="background-position: center">
@yield('header')
    <div class="bg-img">
        <div class="menu">
            <div class="topnav">
                <a href="{{ url('/teacherassignment') }}">Administrar práctica</a>
                <a href="{{ url('/showSubjects') }}">Mostrar prácticas</a>
                <a href="{{ url('/alumnos') }}">Alumnos</a>
                <a href="{{ url('/subjects') }}">Asignaturas</a>
                <a href="{{ url('/logout') }}">Salir</a>
            </div>
        </div>
    </div>


@yield('content')

@yield('scripts')
</body>
</html>
