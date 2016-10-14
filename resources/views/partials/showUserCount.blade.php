(
    @if(count($usersActive)>0)
        @if(count($usersActive)==1)
            1 Aktiver
        @else
            {{ count($usersActive) . " Aktive" }}
        @endif
    @endif
    
    @if(count($usersInactive)>0)
        @if(count($usersInactive)==1)
            @if(count($usersActive) != 0) / @endif 1 Inaktiver
        @else
            @if(count($usersActive) != 0) /  @endif
            {{ count($usersInactive) . " Inaktive" }}     
        @endif
    @endif
    
    @if(count($usersActive) == 0 && count($usersInactive) == 0) 0 Aktive @endif
    Benutzer
)