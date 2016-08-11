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
                <a href="/" class="home-class">Startseite</a>
            </li>
            
            <li>
                <a href="{{ url('favoriten') }}">{{ ucfirst( trans('navigation.favorites') ) }}</a>
            </li>

            <li class="">
                <a href="#">{{ ucfirst( trans('navigation.documents') ) }} <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    
                    
                    
                    <!--
                    {{--
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
                    </li>
                    
                    <li>
                        <a href="{{ url('dokumente/rundschreiben') }}">{{ ucfirst( trans('navigation.rundschreiben') ) }}</a>
                    </li>
                    
                    <li>
                        <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ ucfirst( trans('navigation.rundschreiben-qmr') ) }}</a>
                    </li>
                    
                    <li>
                        <a href="{{ url('dokumente/vorlagedokumente') }}">{{ ucfirst( trans('navigation.vorlagendokumente') ) }}</a>
                    </li>
                    
                    <li>
                        <a href="{{ url('dokumente/rundschreiben-news') }}">{{ trans('navigation.news') }}</a>
                    </li>
                    --}}
                    -->
                    
                    @if(!empty($documentTypes))
                        
                        @foreach($documentTypes as $documentType)
                        
                            @if($documentType->active)
                            
                                @if($documentType->visible_navigation)
                            
                                    @if($documentType->id == 1)
                                        <li> <a href="{{ url('dokumente/news') }}">{{ $documentType->name }}</a> </li>
                                    @elseif($documentType->id == 2)
                                        <li> <a href="{{ url('dokumente/rundschreiben') }}">{{ $documentType->name }}</a> </li>
                                    @elseif($documentType->id == 3)
                                        <li> <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ $documentType->name }}</a> </li>
                                    @elseif($documentType->id == 4)
                                        <li>
                                            <a href="{{url('iso-dokumente')}}">{{ $documentType->name }}
                                                @if(!empty($isoCategories)) <span class="fa arrow"></span> @endif
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
                                        </li>
                                    @elseif($documentType->id == 5)
                                        <li> <a href="{{ url('dokumente/vorlagedokumente') }}">{{ $documentType->name }}</a> </li>
                                    @else
                                        <li> <a href="{{ url('dokumente/typ/' . str_slug($documentType->name)) }}">{{ $documentType->name }}</a> </li>
                                    @endif
                            
                                @endif
                            
                            @endif
                            
                        @endforeach
                        
                    @endif
                    @if( ViewHelper::canCreateEditDoc() == true )
                        <li>
                            <a href="{{ url('dokumente/create') }}">{{ ucfirst( trans('navigation.document') ) }} {{ trans('navigation.anlegen') }}</a>
                        </li>
                    @endif
                </ul><!--End .nav-second-level -->

            </li><!-- End menu item -->


            <li>
                <a href="{{ url('telefonliste') }}">{{ ucfirst( trans('navigation.phonebook') ) }}</a>
            </li>

            <li>
                <a href="#">{{ ucfirst( trans('navigation.wiki') ) }}</a>
            </li>

            <li class="">
                <a href="#">{{ ucfirst(trans('navigation.mandantenverwaltung')) }}
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
                    NEPTUN {{ ucfirst( trans('navigation.verwaltung') )}}
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('adressaten') }}">{{ ucfirst( trans('navigation.adressate') ) }}</a>
                    </li>
                    <li>
                        <a href="{{ url('dokument-typen') }}">{{ ucfirst( trans('navigation.document') ) }} {{ ucfirst( trans('navigation.types') ) }}</a>
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
                        <span class="legend-text">Neueste Dokument</span>
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
                        <span class="legend-text">Nicht veröffentlicht</span>
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

