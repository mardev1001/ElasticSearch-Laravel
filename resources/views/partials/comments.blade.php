<!-- {{ $title }} comments -->
@if( $withRow == true)
<div class="row">
@endif
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper home">
            <h1 class="title">{{ $title }}</h1>
            <div class="box home">
                <div class="commentsMy">
                    @if(count($collection))
                        @foreach( $collection as $k => $comment )
                            @if( $comment->deleted_at == null )
                                <div class="comment-{{++$k}} row flexbox-container">
                                    <!-- delete comment box -->
                                    <div class="pull-left delete-comment">
                                         <a href="/comment-delete/{{$comment->id}}/{{ $comment->document_id }}" class="no-underline delete-prompt"
                                         data-text="Wollen Sie diesen Kommentar wirklich löschen?">
                                             <span class="icon icon-trash inline-block delete-prompt"
                                         data-text="Wollen Sie diesen Kommentar wirklich löschen?" title="Entfernen"></span>
                                         </a> 
                                    </div><!-- end delete comment box -->
                                    
                                    <!-- delete box -->
                                    <div class="pull-left">
                                        <span class="comment-header">
                                            @if( $comment->freigeber == 1 &&  $comment->published != null)
                                                <strong>
                                                @if( $comment->published->approved == 1)
                                                    Freigegeben
                                               @else
                                                   Nicht Freigegeben
                                               @endif
                                               </strong><br/>
                                            @endif
                                        @if( $comment->document->published != null )
                                            <a href="{{url('/dokumente/'. $comment->document->published->url_unique)}}"> <strong>
                                                @if( isset($comment->betreff) && !empty($comment->betreff) )
                                                    {{ $comment->betreff }} -&nbsp;
                                                @endif
                                                </strong> </a>
                                        @else
                                            <a href="{{url('/dokumente/'. $comment->document->id)}}"> <strong>
                                             @if( isset($comment->betreff) && !empty($comment->betreff) )
                                                {{ $comment->betreff }} -&nbsp;
                                            @endif
                                            </strong> </a>
                                        @endif
                                        @if( isset($comment->user) && !empty($comment->user) )
                                            {{ $comment->user->title }} {{ $comment->user->first_name }} {{ $comment->user->last_name }}, {{ $comment->created_at }}
                                        @endif
                                        </span> <br>
                                        
                                        <span class="comment-body">
                                            @if( isset($comment->comment) && !empty($comment->comment) )
                                                {!! str_limit( str_replace(["\r\n", "\r", "\n"], "<br/>", $comment->comment) , $limit = 200, $end = ' ...') !!}
                                            @endif
                                        </span> 
                                        
                                    </div><!-- end delete box -->
                                    
                                </div>
                                <hr/>
                                <div class="clearfix"></div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@if( $withRow == true)
</div><!-- end .row -->
@endif
<!-- end {{ $title }} comments -->