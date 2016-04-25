<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Neptun intranet login</title>

    <!-- Fonts -->
   
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
    <link rel="shortcut icon" href="/img/favicon.png">
        <title>@yield("title",'Neptun intranet')</title>
        
        {!! Html::style(elixir('css/style.css')) !!}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>-->

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    Neptun intranet
                </a>
            </div>

        </div>
    </nav>

    @yield('content')

   <!-- JS files - start -->
          @yield('preScript')
          
        {!! Html::script(elixir('js/script.js')) !!}
        <!-- JS files - end -->
            @yield('script')
</body>
</html>
