<div class="navbar-default sidebar" role="navigation">
     <div class="">
        <button type="button" id="nav-btn" class="navbar-toggle big hidden-xs" title="Navi Ein-/Ausblenden">
            <span class="sr-only">Navi Ein-/Ausblenden</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <!--    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Navi Ein-/Ausblenden</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button> -->
    </div>
    <div class="sidebar-nav navbar-collapse" id="nav-collapse">
        <ul class="nav" id="side-menu">

            <li>
                <a href="/"><i class="fa fa-dashboard fa-fw"></i> Startseite</a>
            </li>

            <li class="">
                <a href="#"><i class="fa fa-files-o fa-fw"></i> {{ ucfirst( trans('navigation.document') ) }} <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('dokumente/create') }}">{{ ucfirst( trans('navigation.document') ) }} {{ trans('navigation.anlegen') }}</a>
                    </li>
                    
                    <li>
                        <a href="#">{{ ucfirst( trans('navigation.iso') ) }} {{ ucfirst(trans('navigation.documents')) }}
                            <span class="fa arrow"></span>
                        </a>
                    @if(!empty($isoCategories))
                        <ul class="nav nav-third-level">
                            @foreach($isoCategories as $isoCategory)
                                @if($isoCategory->parent)
                                <li>
                                    <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}<span class="fa arrow"></span></a>
                                    <ul class="nav nav-fourth-level">
                                    @foreach($isoCategories as $isoCategoryChild)
                                        @if($isoCategoryChild->iso_category_parent_id == $isoCategory->id)
                                            <li><a href="{{ url('iso-dokumente/'. $isoCategoryChild->slug ) }}">{{$isoCategoryChild->name}}</a></li>
                                        @endif
                                    @endforeach
                                    </ul>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                        <!-- /.nav-third-level -->
                    </li>
                    
                    <li>
                        <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ ucfirst( trans('navigation.rundschreiben') ) }} {{ trans('navigation.qmr') }}</a>
                    </li>
                    
                    <li>
                        <!--<a href="{{ url('dokumente/rundschreiben-news') }}">{{ ucfirst( trans('navigation.rundschreiben') ) }}  {{ trans('navigation.news') }}</a>-->
                        <a href="#">{{ ucfirst( trans('navigation.rundschreiben') ) }}  {{ trans('navigation.news') }}</a>
                    </li>
                    
                    <li>
                        <a href="{{ url('dokumente/rundschreiben') }}">{{ ucfirst( trans('navigation.rundschreiben') ) }}</a>
                    </li>
                    
                    <li>
                        <a href="{{ url('dokumente/vorlagedokumente') }}">{{ ucfirst( trans('navigation.vorlagendokumente') ) }}</a>
                    </li>

                </ul><!--End .nav-second-level -->

            </li><!-- End menu item -->

            <li>
                <a href="{{ url('favoriten') }}"><i class="fa fa-heart fa-fw"></i> {{ ucfirst( trans('navigation.favorites') ) }}</a>
            </li>

            <li>
                <a href="{{ url('telefonliste') }}"><i class="fa fa-phone fa-fw"></i> {{ ucfirst( trans('navigation.phonebook') ) }}</a>
            </li>

            <li>
                <a href="#"><i class="fa fa-wikipedia-w fa-fw"></i> {{ ucfirst( trans('navigation.wiki') ) }}</a>
            </li>

            <li class="">
                <a href="#"><i class="fa fa-building fa-fw"></i> {{ ucfirst(trans('navigation.mandantenverwaltung')) }}
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">

                    <li>
                        <a href="{{ url('mandanten') }}">{{ ucfirst(trans('navigation.ubersicht')) }}</a>
                    </li>

                    <li>
                        <a href="{{ url('mandanten/create') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.anlegen') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('benutzer/create') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                    </li>
                </ul><!--End .nav-second-level -->
            </li><!-- End menu item -->

            <li class="">
                <a href="#">
                    <i class="fa fa-cogs fa-fw"></i> {{ ucfirst( trans('navigation.redaktion') )}} {{ ucfirst( trans('navigation.verwaltung') )}}
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('adressaten') }}">{{ ucfirst( trans('navigation.adressate') ) }}</a>
                    </li>
                    <li>
                        <a href="{{ url('dokument-typen') }}">{{ ucfirst( trans('navigation.document') ) }} {{ ucfirst( trans('navigation.type') ) }}</a>
                    </li>
                    <li>
                        <a href="{{ url('iso-kategorien') }}">{{ ucfirst( trans('navigation.iso') ) }} {{ trans('navigation.kategorien') }} </a>
                    </li>
                    <li>
                        <a href="{{ url('rollen') }}">{{ ucfirst( trans('navigation.rollenverwatung') ) }}</a>
                    </li>
                </ul><!--End .nav-second-level -->
            </li><!-- End menu item -->
            
            <li class="legend"> <!-- legend - start -->
                <span class="legend-text">Legende</span>
                <span id="btn-legend" class="icon-legend pull-right"></span>
            </li> <!-- legend - end -->
            <div class="legend-wrapper legend">
                <ul class="legend-ul nav">
                    <li>
                        <span class="legend-text">Neuestes Dokument</span>
                        <span class="legend-icons icon-favorites "></span>
                    </li>
                    <li>
                        <span class="legend-text">Nicht Freigegeben</span>
                        <span class="legend-icons icon-blocked"></span>
                    </li>
                    <li>
                        <span class="legend-text">Freigegeben</span>
                        <span class="legend-icons icon-open"></span>
                    </li>
                    <li>
                        <span class="legend-text">Ungelesen</span>
                        <span class="legend-icons icon-notread "></span>
                    </li>
                    <li>
                        <span class="legend-text">Gelesen</span>
                        <span class="legend-icons icon-read"></span>
                    </li>
                    <li>
                        <span class="legend-text">Unveröffentlicht</span>
                        <span class="legend-icons icon-notreleased"></span>
                    </li>
                    <li>
                        <span class="legend-text">Veröffentlicht</span>
                        <span class="legend-icons icon-released"></span>
                    </li>
                    <li>
                        <span class="legend-text">Historie vorhanden</span>
                        <span class="legend-icons icon-history"></span>
                    </li>
                    <li>
                        <span class="legend-text">Download</span>
                        <span class="legend-icons icon-download"></span>
                    </li>
                    <li>
                        <span class="legend-text">Link</span>
                        <span class="legend-icons icon-goto"></span>
                    </li>
                    <li>
                        <span class="legend-text">Kommentar</span>
                        <span class="legend-icons icon-comment"></span>
                    </li>

                </ul><!--End .nav-second-level -->
            </div>
            <div class="clearfix"></div>
        </ul>
    </div>
    <!-- /.sidebar-collapse -->

</div>
<!-- /.navbar-static-side -->

</nav>