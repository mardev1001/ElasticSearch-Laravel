<!-- {{ $title }} comments -->
<div class="row">
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper home">
            <h1 class="title">{{ $title }}</h1>
            <div class="box home">
                <div class="commentsMy">
                    @if(count($collection))
                        @foreach( $collection as $k => $comment )
                            <div class="comment-{{++$k}} row flexbox-container">
                                <!-- delete comment box -->
                                <div class="pull-left delete-comment">
                                     <a href="/comment-delete/{{$comment->id}}/{{ $comment->document_id }}" class="no-underline">
                                         <span class="icon icon-trash inline-block"></span>
                                     </a> 
                                </div><!-- end delete comment box -->
                                
                                <!-- delete box -->
                                <div class="pull-left">
                                   <span class="comment-header">
                                    {{ $comment->user->title }} {{ $comment->user->first_name }} {{ $comment->user->last_name }} - 
                                    <a href="{{url('/dokumente/'. $comment->document->published->url_unique)}}">
                                        <strong>{{ $comment->betreff }}</strong>
                                    </a>
                                    , {{ $comment->created_at }}
                                    </span> <br>
                                    
                                    <span class="comment-body">
                                        {{ str_limit($comment->comment, $limit = 200, $end = ' ...') }}
                                    </span> 
                                    
                                </div><!-- end delete box -->
                                
                            </div>
                            <div class="clearfix"></div><br>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div><!-- end {{ $title }} comments -->