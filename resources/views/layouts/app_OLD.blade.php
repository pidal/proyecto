<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'PFG') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">

                @auth
                    <?php   $idRol = DB::table('profesor')->where('users_id',auth()->id())->value('roles_id');
                    if($idRol == null){
                        $idRol = DB::table('alumno')->where('users_id',auth()->id())->value('roles_id');
                    };?>

                    @if($idRol == null)
                            <a class="navbar-brand" href="{{ url('/register') }}">
                                {{ config('app.menu4', 'REGISTRARSE') }}
                            </a>
                    @elseif($idRol == 2)
                        <a class="navbar-brand" href="{{ url('/admin') }}">
                            {{ config('app.menu1', 'CREAR PRÁCTICA') }}
                        </a>

                        <a class="navbar-brand" href="{{ url('/alumno') }}">
                            {{ config('app.menu2', 'PRÁCTICAS') }}
                        </a>

                        <a class="navbar-brand" href="{{ url('/admin') }}">
                            {{ config('app.menu3', 'ALUMNOS') }}
                        </a>
                  @elseif($idRol == 3)

                    <a class="navbar-brand" href="{{ url('/alumno') }}">
                        {{ config('app.menu2', 'PRÁCTICAS') }}
                    </a>
                            <a class="navbar-brand" href="{{ url('/mostrar') }}">
                                {{ config('app.menu5', 'RESULTADOS') }}
                            </a>
                    @endif

                @else

                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'PFG') }}
                </a>

        @endauth

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Acceso') }}</a>
                            </li>
                            {{--<li class="nav-item">--}}
                                {{--@if (Route::has('register'))--}}
                                    {{--<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>--}}
                                {{--@endif--}}
                            {{--</li>--}}
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="container">
            @yield('content')
        </main>
    </div>
</body>
</html>
