<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Naschmarkt</title>

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
    <!--<link href="{{ URL::asset('css/cloud.css') }}" rel="stylesheet">-->
    @stack('stylesheets')

</head>
<body id="app-layout">

    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                    <span class="" aria-hidden="true"></span>
                    Naschmarkt
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ url('/about') }}">About</a></li>

                    @if(!isset($posts))
                        <form action="{{ url('/search') }}" method="POST" class="navbar-form navbar-left" role="search" enctype="multipart/form-data">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <input data-hotkey="s" name="searchQuery" placeholder="Search Posts" aria-label="Search Posts" data-unscoped-placeholder="Search Posts" data-scoped-placeholder="Search" tabindex="1" type="text" class="form-control header-search-input js-site-search-focus">
                            </div>
                        </form>
                    @endif

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::check())
                        <li><a href="{{ url('/posts') }}"> Posts </a></li>
                        <!-- <li><a href="{{ url('/search') }}"> Search </a></li> -->
                        <li><a href="{{ url('/cloud') }}"> Wordcloud </a></li>
                        <li><a href="{{ url('/upload') }}">Upload</a></li>
                        @can('register-user')
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @endcan
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url('/user/' . Auth::user()->id) }}"><i class="fa fa-btn"></i>Profile</a></li>
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <div class="main-wrapper">
        @if(isset($status))
            <div class="container">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="alert alert-{{ $status['type'] }} alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            {{ $status['content'] }}
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    @stack('scripts')
    {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
</body>
</html>
