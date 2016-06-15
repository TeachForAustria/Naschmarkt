<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Naschmarkt</title>

        <!-- Styles -->
        <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('lib/lato-font/css/lato.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('lib/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('img/favicon.ico') }}" rel="shortcut icon">
        @stack('stylesheets')

    </head>
    <body id="app-layout">

        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <div class="wrapper">
            <nav class="navbar navbar-inverse navbar-static-top">
                <div class="container">
                    <div class="navbar-header">

                        <!-- Collapsed Hamburger -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <!-- Branding Image -->
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <span><img src="{{ URL::asset('img/vogel_white.png') }}"></span>
                            Naschmarkt
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Left Side Of Navbar -->
                        <ul class="nav navbar-nav">
                            @if(!isset($posts) && Auth::check())
                                <form action="{{ url('/posts') }}" method="GET" class="navbar-form navbar-left" role="search" enctype="multipart/form-data">
                                    {!! csrf_field() !!}
                                    <div class="form-group">
                                        <input data-hotkey="q" name="q" placeholder="Search Posts" aria-label="Search Posts" data-unscoped-placeholder="Search Posts" data-scoped-placeholder="Search" tabindex="1" type="text" class="form-control header-search-input js-site-search-focus">
                                    </div>
                                </form>
                            @endif

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @if (Auth::check())
                                <li><a href="{{ url('/posts') }}"> Posts </a></li>
                                <li><a href="{{ url('/projects') }}"> Projekte </a></li>
                                <li><a href="{{ url('/upload') }}">Upload</a></li>
                                @can('register-user')
                                <li><a href="{{ url('/register') }}">Register</a></li>
                                @endcan
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="{{ url('/user') }}"><i class="fa fa-btn"></i>Profile</a></li>
                                        <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="main-wrapper">
                @if(Session::has('status'))
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-10 col-sm-offset-1">
                                <div class="alert alert-{{ session('status')['type'] }} alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    {{ session('status')['content'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>

            <div class="footer-push"></div>
        </div>

        <footer>
            <div class="container footer-content">
                <div class="row">
                    <div class="col-sm-4">
                        <a href="http://www.teachforaustria.at/"><img src="{{ URL::asset('img/logo.svg') }}" class="brand"></a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2 pull-right footer-right">
                        <div class="row">
                            <a href="https://github.com/TeachForAustria/Naschmarkt/">
                                <i class="fa fa-code" aria-hidden="true"></i> with <i class="fa fa-heart" aria-hidden="true"></i> as Open Source
                            </a>
                        </div>
                        <div class="row">
                            <i class="fa fa-copyright" aria-hidden="true"></i> 2016, Teach for Austria
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- JavaScripts -->
        <script src="{{ URL::asset('lib/jquery/jquery.min.js') }}" ></script>
        <script src="{{ URL::asset('lib/bootstrap/bootstrap.min.js') }}" ></script>
        @stack('scripts')
        {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
    </body>
</html>
