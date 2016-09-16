<div class="header">
    <div class="div-pusher">
        <p></p>
    </div>
    <div class="image-div">
         @if($mandant->logo)
            <img class="img-responsive" src="{{url('/files/pictures/mandants/'. $mandant->logo)}}"/>
        @else
            <img class="img-responsive" src="{{url('/img/mandant-default.png')}}"/>
        @endif
    </div>
</div>