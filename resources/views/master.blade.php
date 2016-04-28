<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="/img/favicon.png">
    <title>@yield("title",'NEPTUN Intranet')</title>

    {!! Html::style(elixir('css/style.css')) !!}
            <!-- CSS files - end -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <meta name="csrf-token" content="{{csrf_token()}}"/>
</head>

<body>
<div id="wrapper">
    
    <!-- Header search -->
    @include('layouts.top')

    <!-- Sidebar navigation -->
    @include('layouts.sidebar')

    <!-- Page Content -->
    <div id="page-wrapper">
        <div class="container-fluid first">
            
            @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif
            
            @if (isset($errors) && count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
                
            @if (Session::has('flash_notification.message'))
                <div class="alert alert-{{ Session::get('flash_notification.level') }}">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ Session::get('flash_notification.message') }}
                </div>
            @endif

            <!-- Backend content -->
            @yield('content')

            @yield('modal')
            
            <!--search results-->
            @yield('searchResults')<!-- End search results-->
                
        </div> <!-- End container fluid-->
    </div> <!-- End #wrapper-->
    
    <!-- Right sidebar -->

</div>

<!-- JS files - start -->
@yield('preScript')
{!! Html::script(elixir('js/script.js')) !!}
<!-- JS files - end -->

@yield('script')

</body>
</html>
