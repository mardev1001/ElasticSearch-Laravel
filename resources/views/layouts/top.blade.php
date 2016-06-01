<!-- Navigation -->
<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="">
        <div class="col-xs-12 col-sm-4 col-md-6">
            <a class="nav-brand" href="/"><strong><img src="/img/NeptunLogo.png" alt="Neptun logo"/></strong></a>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-6">
            <ul class="nav navbar-nav icons pull-right">
                <li><a href="#"><span class="nav-icon icon-home"></span>Home</a></li>
                <li><a href="#"><span class="nav-icon icon-profil"></span>Profil</a></li>
                <li><a href="{{ url('logout') }}"><span class="nav-icon icon-logout"></span>Log out</a></li>
                <li><a href="#"><span class="nav-icon icon-kontakt"></span>Kontakt</a></li>
                <li>
                    {{ Form::open(['route'=>['suche.index'], 'method'=>'GET']) }}
                    <div class="input-group custom-search-form">
                        <input type="text" class="form-control" name="parameter" placeholder="{{ trans('navigation.advanced_search') }}" required >
                        <!--<span class="input-group-btn">
                    <button type="submit" name="search" class="btn btn-default" title="Suche">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="{{ action('SearchController@searchAdvanced') }}" class="btn btn-default" title="Erweiterte Suche ...">
                        <i class="fa fa-list"></i>
                    </a>
                </span> -->

                    </div>
                    {{ Form::close() }}

                </li>
            </ul>
        </div>

    <!-- /.navbar-top-links -->

    <div class="clearfix"></div>
