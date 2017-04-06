@if(count($sendingList) )
<!-- versand panel -->
<div class="panel panel-primary" id="panelVersand">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-target="#versandPanel" href="#versandPanel" class="transform-normal collapsed">
                Versand
            </a>
        </h4>
    </div>
            
    <div id="versandPanel" class="panel-collapse collapse" role="tabpanel">
        <div class="panel-body">
            <div class="sendingList">
                @foreach( $sendingList as $item )
                    <div class="sent-item-{{$item->document_id}} row flexbox-container col-xs-12">
                        <div class="pull-left">                                
                                <span class="comment-header">
                                    <strong> {{ $item->userEmailSetting->recievers_text }}</strong> <br>

                                    @if( $item->sent == true )
                                        Gesendet
                                    @else
                                        Nicht gesendet
                                    @endif
                                </span>

                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <hr/>
                @endforeach
            </div>
        </div><!--end .panel-body -->
    </div><!--end #freigabePanel -->
</div><!-- end freigeber panel -->        
@endif