@extends('master')

@section('content')

    <h1 class="text-primary">
        Dashboard
    </h1>

    <div class="col-xs-12 col-md-6 box">
        <h3 class="text-info">Neue Dokumente/Roundschreiben</h3>
        <div class="col-xs-12">
            <div class="tree">
                <ul>
                    <li>
                        <span><i class="icon-calendar"></i> 2016, Week 2</span>
                        <ul>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 7: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 8.00</span> &ndash; Changed CSS to accomodate...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Tuesday, January 8: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-time"></i> 6.00</span> &ndash; <a href="">Altered code...</a>
                                    </li>
                                    <li>
                                        <span><i class="icon-time"></i> 2.00</span> &ndash; <a href="">Simplified our approach to...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-warning"><i class="icon-minus-sign"></i> Wednesday, January 9: 6.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 3.00</span> &ndash; Fixed bug caused by...</a>
                                    </li>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 3.00</span> &ndash; Comitting latest code to Git...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-important"><i class="icon-minus-sign"></i> Wednesday, January 9: 4.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 2.00</span> &ndash; Create component that...</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span><i class="icon-calendar"></i> 2013, Week 3</span>
                        <ul>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 14: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-time"></i> 7.75</span> &ndash; <a href="">Writing documentation...</a>
                                    </li>
                                    <li>
                                        <span><i class="icon-time"></i> 0.25</span> &ndash; <a href="">Reverting code back to...</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!--end .tree-->
        </div>
    </div>

    <div class="col-xs-12 col-md-6 box pink">
        <h3 class="text-info">Ersteller - Meine Rundschreiben</h3>
        <div class="col-xs-12">
            <div class="tree">
                <ul>
                    <li>
                        <span><i class="icon-calendar"></i> 2016, Week 2</span>
                        <ul>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 7: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 8.00</span> &ndash; Changed CSS to accomodate...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Tuesday, January 8: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-time"></i> 6.00</span> &ndash; <a href="">Altered code...</a>
                                    </li>
                                    <li>
                                        <span><i class="icon-time"></i> 2.00</span> &ndash; <a href="">Simplified our approach to...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-warning"><i class="icon-minus-sign"></i> Wednesday, January 9: 6.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 3.00</span> &ndash; Fixed bug caused by...</a>
                                    </li>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 3.00</span> &ndash; Comitting latest code to Git...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-important"><i class="icon-minus-sign"></i> Wednesday, January 9: 4.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 2.00</span> &ndash; Create component that...</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span><i class="icon-calendar"></i> 2013, Week 3</span>
                        <ul>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 14: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-time"></i> 7.75</span> &ndash; <a href="">Writing documentation...</a>
                                    </li>
                                    <li>
                                        <span><i class="icon-time"></i> 0.25</span> &ndash; <a href="">Reverting code back to...</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!--end .tree-->
        </div>
    </div>


    <div class="col-xs-12 col-md-6 box purple">
        <h3 class="text-info">Neue Wiki-Einträge</h3>
        <div class="col-xs-12">
            <div class="tree">
                <ul>
                    <li>
                        <span><i class="icon-calendar"></i> 2016, Week 2</span>
                        <ul>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 7: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 8.00</span> &ndash; Changed CSS to accomodate...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Tuesday, January 8: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-time"></i> 6.00</span> &ndash; <a href="">Altered code...</a>
                                    </li>
                                    <li>
                                        <span><i class="icon-time"></i> 2.00</span> &ndash; <a href="">Simplified our approach to...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-warning"><i class="icon-minus-sign"></i> Wednesday, January 9: 6.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 3.00</span> &ndash; Fixed bug caused by...</a>
                                    </li>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 3.00</span> &ndash; Comitting latest code to Git...</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <span class="badge badge-important"><i class="icon-minus-sign"></i> Wednesday, January 9: 4.00 hours</span>
                                <ul>
                                    <li>
                                        <a href=""><span><i class="icon-time"></i> 2.00</span> &ndash; Create component that...</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <span><i class="icon-calendar"></i> 2013, Week 3</span>
                        <ul>
                            <li>
                                <span class="badge badge-success"><i class="icon-minus-sign"></i> Monday, January 14: 8.00 hours</span>
                                <ul>
                                    <li>
                                        <span><i class="icon-time"></i> 7.75</span> &ndash; <a href="">Writing documentation...</a>
                                    </li>
                                    <li>
                                        <span><i class="icon-time"></i> 0.25</span> &ndash; <a href="">Reverting code back to...</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!--end .tree-->
        </div>
    </div>
    
    <div class="clearfix"></div>
    
    <br>
    
@stop
   