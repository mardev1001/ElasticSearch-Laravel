<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
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
                        <a href="#">{{ ucfirst( trans('navigation.iso') ) }} {{ trans('navigation.document') }}
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-third-level">
                            <li>
                                <a href="{{ url('iso-dokumente/level-1') }}">Level 1</a>
                            </li>
                            <li>
                                <a href="{{ url('iso-dokumente/level-1') }}">Level 1</a>
                            </li>
                            <li>
                                <a href="{{ url('iso-dokumente/level-1') }}">Level 1 <span class="fa arrow"></span></a>
                                <ul class="nav nav-fourth-level">
                                    <li>
                                        <a href="{{ url('iso-dokumente/level-2') }}">Level 2</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('iso-dokumente/level-2') }}">Level 2</a>
                                    </li>
                                    <li>
                                        <a href="{{ url('iso-dokumente/level-2') }}">Level 2</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Third Level Item</a>
                            </li>
                        </ul>
                        <!-- /.nav-third-level -->
                    </li>
                    <li>
                        <a href="{{ url('dokumente/iso-dokumente') }}">{{ ucfirst( trans('navigation.rundschreiben') ) }}</a>
                    </li>
                    <li>
                        <a href="{{ url('dokumente/rundschreiben-qmr') }}">{{ ucfirst( trans('navigation.rundschreiben') ) }} {{ trans('navigation.qmr') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('dokumente/rundschreiben') }}">{{ ucfirst( trans('navigation.rundschreiben') ) }}</a>
                    </li>
                    <li>
                        <a href="{{ url('dokumente/rundschreiben-news') }}">{{ ucfirst( trans('navigation.vorlagendokument') ) }}  {{ trans('navigation.news') }}</a>
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

            {{--
            <li class="">
                <a href="#"><i class="fa fa-users fa-fw"></i> {{ ucfirst(trans('navigation.benutzerverwaltung')) }}<span
                            class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('benutzer') }}">{{ ucfirst(trans('navigation.ubersicht')) }}</a>
                    </li>

                    <li>
                        <a href="{{ url('benutzer/create') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                    </li>
                </ul><!--End .nav-second-level -->
            </li><!-- End menu item -->
            --}}

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

        </ul>
    </div>
    <!-- /.sidebar-collapse -->

</div>
<!-- /.navbar-static-side -->

</nav>