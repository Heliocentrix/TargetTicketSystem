<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>@yield('sectionTitle') | Maintenance Accounts</title>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300|Montserrat" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ elixir('build/css/app.css') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" sizes="57x57" href="/images/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/images/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/images/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/images/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/images/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/images/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/images/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/images/apple-touch-icon-180x180.png">

        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css">

        <link rel="icon" type="image/png" href="/images/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="/images/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/images/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="/images/android-chrome-192x192.png" sizes="192x192">
        <meta name="msapplication-square70x70logo" content="/images/smalltile.png">
        <meta name="msapplication-square150x150logo" content="/images/mediumtile.png">
        <meta name="msapplication-wide310x150logo" content="/images/widetile.png">
        <meta name="msapplication-square310x310logo" content="/images/largetile.png">

        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        @yield('styles')
    </head>

    @if(auth()->check() && auth()->user()->admin)
    <body class="admin">
    @else
    <body class="user">
    @endif
        <div class="nav-container">
            <div class="container">
                @if(auth()->check() && auth()->user()->admin)
                <div class="btn-group-justified main-nav" role="group">
                    @if(request()->is('*/tickets/*'))
                    <a href="{{ url(request()->segment(1) . '/tickets') }}" class="btn btn-default btn-menu">Back To Tickets</a>
                    @else
                    <a href="{{ url('/') }}" class="btn btn-default btn-menu">Maintenance Accounts</a>
                    @endif

                    <a href="{{ request()->url() }}" class="btn btn-default btn-menu active">@yield('sectionTitle')</a>
                    <a href="{{ url('auth/logout') }}" class="btn btn-default btn-menu btn-logout">Logout</a>
                </div>
                @elseif(auth()->check())
                <div class="btn-group-justified main-nav" role="group">
                    @if(request()->is('*/tickets/*'))
                    <a href="{{ url(request()->segment(1) . '/tickets') }}" class="btn btn-default btn-menu">Back To Tickets</a>
                    @else
                    <a href="{{ url('/') }}" class="btn btn-default btn-menu">Maintenance Accounts</a>
                    @endif

                    <a href="{{ request()->url() }}" class="btn btn-default btn-menu active">@yield('sectionTitle')</a>
                    <a href="{{ url('auth/logout') }}" class="btn btn-default btn-menu btn-logout">Logout</a>
                </div>
                @endif
            </div>
        </div>
        <div class="container">
            @yield('content')
        </div>

        <script src="{{ elixir('build/js/app.js') }}"></script>
        @if(auth()->check())
            <script src="/js/validation.js?{{ config('app.hash') }}"></script>
            @yield('scripts')
        @endif

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>

        @if(app()->environment() != 'local')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/airbrake-js/0.5.8/client.min.js" type="text/javascript"></script>
        <script type="text/javascript">
        var airbrake = new airbrakeJs.Client({projectId: '6e24fe7e9906ef1d07138da38487bc64', projectKey: '6e24fe7e9906ef1d07138da38487bc64'});
        airbrake.setHost("https://helio-errbit.herokuapp.com/");
        </script>
        @endif
    </body>
</html>
