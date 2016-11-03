<div class="navbar-default sidebar" role="navigation">
    <div class="">
        <button type="button" id="nav-btn" class="navbar-toggle big hidden-xs" title="Navi Ein-/Ausblenden"></button>
    </div>
    
    <div class="sidebar-nav navbar-collapse" id="nav-collapse">
        <ul class="nav" id="side-menu">

            <li>
                <a href="/" class="home-class">Startseite</a>
            </li>
            
            <li>
                <a href="{{ url('favoriten') }}">{{ ucfirst( trans('navigation.favorites') ) }}</a>
            </li>
            
            <li>
                <a href="{{ url('suche') }}">{{ ucfirst( trans('navigation.advanced_search') ) }}
                    <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                         <li>
                            <a href="{{ url('tipps-und-tricks') }}">{{ ucfirst( trans('navigation.tipsAndTricks') ) }}</a>
                        </li>
                    </ul>
            </li>
                
           <li class="">
                <a href="{{ url('dokumente') }}">{{ ucfirst( trans('navigation.documents') ) }} <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    
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
                </ul>

            </li>


            <li>
                <a href="{{ url('telefonliste') }}">{{ ucfirst( trans('navigation.phonebook') ) }}</a>
            </li>

            {{--
            <li>
                <a href="#">{{ ucfirst( trans('navigation.wiki') ) }}</a>
            </li>
            --}}
            
            @if( ViewHelper::universalHasPermission( array(15,16) ) == true ) 
                <li class="">
                    <a href="{{ url('wiki') }}">{{ ucfirst(trans('navigation.wiki')) }}
                        <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
    
                        {{--
                        <li>
                            <a href="{{ url('wiki') }}">{{ ucfirst(trans('wiki.wikiList')) }}</a>
                        </li>
                        --}}
                        
                        @if( ViewHelper::canViewWikiManagmentAdmin() == true )
                            <li>
                                <a href="{{ url('wiki/verwalten-admin') }}">{{ ucfirst( trans('wiki.verwalten') ) }} </a>
                            </li>
                        @else
                            {{--
                            <li>
                                <a href="{{ url('wiki/verwalten') }}">{{ ucfirst( trans('wiki.verwalten') ) }} </a>
                            </li>
                            --}}
                        @endif  
                        
                        @if( ViewHelper::universalHasPermission( array(15) ) == true ) 
                            <li>
                                <a href="{{ url('wiki-kategorie') }}">{{ ucfirst( trans('wiki.wikiCategory') ) }} </a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(15) ) == true ) 
                            <li>
                                <a href="{{ url('wiki/create') }}">{{ ucfirst( trans('wiki.wikiCreate') ) }} </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            
            @if( ViewHelper::universalHasPermission( array(6,18,19,20) ) == true ) 
                <li class="">
                    <a href="{{ url('mandantenverwaltung') }}">{{ ucfirst(trans('navigation.mandantenverwaltung')) }}
                        <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        @if( ViewHelper::universalHasPermission( array(6,19,20) ) == true ) 
                            <li>
                                <a href="{{ url('mandanten') }}">{{ ucfirst(trans('navigation.ubersicht')) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6,18,19,20) ) == true ) 
                            <li>
                                <a href="{{ url('mandanten/export') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.export') }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6) ) == true )
                            <li>
                                <a href="{{ url('mandanten/create') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.anlegen') }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(6,17) ) == true )
                            <li>
                                <a href="{{ url('benutzer/create') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            
            @if( ViewHelper::universalHasPermission( array(2,4), false ) == true )
                <li>
                    <a href="{{ url('benutzer') }}">{{ ucfirst( trans('navigation.benutzer') ) }}</a>
                </li>
            @endif
            
            @if( ViewHelper::universalHasPermission( array() ) == true )
                <li class="">
                    <a href="{{ url('neptun-verwaltung') }}">
                        NEPTUN-{{ ucfirst( trans('navigation.verwaltung') )}}
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a href="{{ url('empfangerkreis') }}">{{ ucfirst( trans('navigation.adressate') ) }}</a>
                        </li>
                        <li>
                            <a href="{{ url('dokument-typen') }}">{{ ucfirst( trans('navigation.document') ) }}-{{ ucfirst( trans('navigation.types') ) }}</a>
                        </li>
                        <li>
                            <a href="{{ url('iso-kategorien') }}">{{ ucfirst( trans('navigation.iso') ) }}-{{ trans('navigation.kategorien') }} </a>
                        </li>
                        <li>
                            <a href="{{ url('rollen') }}">{{ ucfirst( trans('navigation.rollenverwatung') ) }}</a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="legend"> <!-- legend - start -->
                <span class="legend-text">Legende</span>
                <span id="btn-legend" class="icon-legend pull-right" title="Legende Ein-/Ausblenden"></span>
            </li> <!-- legend - end -->
            
            <div class="clearfix"></div>
        </ul>
    </div>
    
    
    <div class="legend-wrapper legend">
        <ul class="legend-ul nav">
            <li>
                <span class="legend-text">neues Dokument</span>
                <span class="legend-icons icon-favorites "></span>
            </li>
            <li>
                <span class="legend-text">nicht freigegeben</span>
                <span class="legend-icons icon-blocked"></span>
            </li>
            <li>
                <span class="legend-text">freigegeben</span>
                <span class="legend-icons icon-open"></span>
            </li>
            <li>
                <span class="legend-text">muss gelesen werden</span>
                <span class="legend-icons icon-notread "></span>
            </li>
            <li>
                <span class="legend-text">gelesen</span>
                <span class="legend-icons icon-read"></span>
            </li>
            <li>
                <span class="legend-text">nicht veröffentlicht</span>
                <span class="legend-icons icon-notreleased"></span>
            </li>
            <li>
                <span class="legend-text">veröffentlicht</span>
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
        </ul>
    </div>

</div>


