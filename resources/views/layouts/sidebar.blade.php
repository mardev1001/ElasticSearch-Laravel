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
                <a href="{{ url('favoriten') }}">{{ ucfirst( trans('navigation.favorites') ) }}
                    <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                         <li>
                            <a href="{{ url('favoriten/kategorieverwaltung') }}">{{ ucfirst( trans('navigation.kategorieverwaltung') ) }}</a>
                        </li>
                    </ul>
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
            
            @if(!empty($documentTypesMenu))
                        
                @foreach($documentTypesMenu as $documentTypeMenu)
                
                    @if($documentTypeMenu->active)
                    
                        @if($documentTypeMenu->visible_navigation)
                    
                            @if($documentTypeMenu->id == 1)
                                <li> <a href="{{ url('dokumente/news') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif($documentTypeMenu->id == 2)
                                <li> <a href="{{ url('dokumente/rundschreiben') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif($documentTypeMenu->id == 3)
                                <li> <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif($documentTypeMenu->id == 4)
                                <li>
                                    <a href="{{url('iso-dokumente')}}">{{ $documentTypeMenu->name }}
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
                            @elseif($documentTypeMenu->id == 5)
                                <li> <a href="{{ url('dokumente/vorlagedokumente') }}">{{ $documentTypeMenu->name }}</a> </li>
                            @elseif($documentTypeMenu->id != 1 )
                                <li> <a href="{{ url('dokumente/typ/' . str_slug($documentTypeMenu->name)) }}">{{ $documentTypeMenu->name }}</a> </li>
                            @endif
                    
                        @endif
                    
                    @endif
                    
                @endforeach
                
            @endif
                
            <li>
                <a href="{{ url('dokumente') }}">{{ ucfirst( trans('navigation.documents') ) }} <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    
                    @if(!empty($documentTypesSubmenu))
                        
                        @foreach($documentTypesSubmenu as $documentTypeSubmenu)
                        
                            @if($documentTypeSubmenu->active)
                            
                                @if($documentTypeSubmenu->visible_navigation)
                            
                                    @if($documentTypeSubmenu->id == 1)
                                        <li> <a href="{{ url('dokumente/news') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif($documentTypeSubmenu->id == 2)
                                        <li> <a href="{{ url('dokumente/rundschreiben') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif($documentTypeSubmenu->id == 3)
                                        <li> <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif($documentTypeSubmenu->id == 4)
                                        <li>
                                            <a href="{{url('iso-dokumente')}}">{{ $documentTypeSubmenu->name }}
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
                                    @elseif($documentTypeSubmenu->id == 5)
                                        <li> <a href="{{ url('dokumente/vorlagedokumente') }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @elseif($documentTypeSubmenu->id != 1 )
                                        <li> <a href="{{ url('dokumente/typ/' . str_slug($documentTypeSubmenu->name)) }}">{{ $documentTypeSubmenu->name }}</a> </li>
                                    @endif
                            
                                @endif
                            
                            @endif
                            
                        @endforeach
                        
                    @endif
                   
                    
                   
                    @if( ViewHelper::canCreateEditDoc() == true ) {{-- 11,13 --}}
                        <li>
                            <a href="{{ url('dokumente/create') }}">{{ ucfirst( trans('navigation.document') ) }} {{ trans('navigation.anlegen') }}</a>
                        </li>
                    @endif
                </ul>

            </li>
            
            <!--telefonliste-->
           <li>
                <a href="{{ url('telefonliste') }}">{{ ucfirst( trans('navigation.phonebook') ) }}</a>
            </li>
            <!--end telefonliste-->
            
            {{--
            <li>
                <a href="#">{{ ucfirst( trans('navigation.wiki') ) }}</a>
            </li>
            --}}
            
              
            @if( ViewHelper::universalHasPermission( array(7,27) ) == true )
                <!--inventarliste-->
                <li class="">
                    <a href="{{ url('inventarliste') }}">
                        {{ ucfirst( trans('navigation.inventoryList') )}}
                        <span class="fa arrow"></span>
                    </a>
                    @if( ViewHelper::universalHasPermission( array(27) ) == true )
                        <ul class="nav nav-second-level collapse">
                            <li>
                                <a href="{{ url('inventarliste/kategorien') }}">{{ ucfirst( trans('navigation.inventarCategory') )}}</a>
                            </li>
                            <li>
                                <a href="{{ url('inventarliste/groessen') }}">{{ ucfirst( trans('navigation.inventarSizes') )}}</a>
                            </li>
                            <li>
                                <a href="{{ url('inventarliste/create') }}">{{ ucfirst( trans('navigation.newInventory') )}}</a>
                            </li>
                            <!--<li>-->
                            <!--    <a href="{{ url('inventarliste/materialien-abrechnen') }}">{{ ucfirst( trans('navigation.deduct') )}}</a>-->
                            <!--</li>-->
                           
                        </ul>
                    @endif
                </li><!--end inventarliste-->
            @endif
            @if( ViewHelper::universalHasPermission( array(15,16) ) == true ) 
                <!--wiki-->
                <li class="">
                    <a href="{{ url('wiki') }}">{{ ucfirst(trans('navigation.wiki')) }}
                        <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
    
                        {{--
                        <li>
                            <a href="{{ url('wiki') }}">{{ ucfirst(trans('wiki.wikiList')) }}</a>
                        </li>
                        --}}
                        
                        @if( ViewHelper::canViewWikiManagmentAdmin() == true ) {{-- 15 --}}
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
                </li><!-- end wiki -->
            @endif
            
            @if( ViewHelper::universalHasPermission( array(17, 18, 20) ) == true )
                {{-- removed neptun Verwalter NEPTUN-610 --}}
                <li class="">
                    <a href="{{ url('mandantenverwaltung') }}">{{ ucfirst(trans('navigation.mandantenverwaltung')) }}
                        <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        @if( ViewHelper::universalHasPermission( array(17, 18) ) == true ) 
                            <li>
                                <a href="{{ url('mandanten') }}">{{ ucfirst(trans('navigation.ubersicht')) }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(20) ) == true ) 
                            <li>
                                <a href="{{ url('mandanten/export') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.export') }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(18) ) == true )
                            <li>
                                <a href="{{ url('mandanten/create') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.anlegen') }}</a>
                            </li>
                        @endif
                        
                        @if( ViewHelper::universalHasPermission( array(17) ) == true )
                            <li>
                                <a href="{{ url('benutzer/create') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            
            @if( (ViewHelper::universalHasPermission() == false) && (ViewHelper::universalHasPermission( array(2,4)) == true) ) {{-- 2,4 NEPTUN-605 --}}
                <li>
                    <a href="{{ url('benutzer') }}">{{ ucfirst( trans('navigation.benutzer') ) }}<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a href="{{ url('benutzer/create-partner') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                        </li>
                    </ul>
                    
                </li>
            @endif
            
            <!--neptun verwalten-->
            @if( ViewHelper::universalHasPermission( array(6) ) == true )
                <li class="">
                    <a href="{{ url('neptun-verwaltung') }}">
                        NEPTUN-{{ ucfirst( trans('navigation.verwaltung') )}}
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        <li>
                            <a href="{{ url('benutzer/standard-benutzer') }}">{{ ucfirst( trans('contactForm.defaultUser') ) }}</a>
                        </li>
                        <li>
                            <a href="{{ url('kontaktanfragen') }}">{{ ucfirst( trans('contactForm.kontaktanfragen') ) }}</a>
                        </li>
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
            <!--end neptun verwalten-->
            
            <!--papierkorb-->
            @if( ViewHelper::universalHasPermission( array(11,13) ) == true ) 
                <li>
                    <a href="{{ url('papierkorb') }}">{{ ucfirst( trans('navigation.trash') ) }}</a>
                </li>
            @endif
            <!--end papierkorb-->
            
            
            
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
                <span class="legend-text">Entwurf</span>
                <span class="legend-icons icon-draft"></span>
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
                <span class="legend-text">freigegeben, nicht veröffentlicht</span>
                <span class="legend-icons icon-approvedunpublished"></span>
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
                <span class="legend-text">Auswahl aufheben</span>
                <span class="legend-icons icon-reset"></span>
            </li>
        </ul>
    </div>

</div>


