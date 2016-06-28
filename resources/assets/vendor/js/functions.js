/**
 * Created by Marijan on 27.04.2016..
 */
$(function() {
    
    // Delete prompt for buttons and anchors
    $('.delete-prompt').on('click touch', function(e) {
        if (confirm("Wollen Sie diesen Eintrag wirklich entfernen?"))
            return true;
        else
            return false;
    });
    

    // $('[data-hideswitch]').on('click', function(e) {
    //     $(this).toggle();
    //     console.log('trig');
    //     console.log($(this).attr('checked'));
    //     var $this = $(this),
    //         yesClass = $('.' + $this.data('yes')),
    //         noClass = $('.' + $this.data('no'));
    //     if ($this.attr('checked', true) || $this.attr('checked', 'checked')) {
    //         yesClass.removeClass('hidden');
    //         noClass.addClass('hidden');
    //     }
    //     else {
    //         noClass.removeClass('hidden');
    //         yesClass.addClass('hidden');
    //     }

    // });

   // Toggle sidebar navigation
    $('button.navbar-toggle.big').on('click', function(e) {

        var navSidebar = $('.sidebar-nav.navbar-collapse');
        var pageWrapper = $('#page-wrapper');
        var navbarToggle = $('#nav-btn');
        var fixedTitle = $('.fixed-position');

        if (navSidebar.hasClass('hidden')) {

            $.when(navSidebar.removeClass('hidden')).done(
                
                pageWrapper.removeAttr('style'),
                fixedTitle.css('left', '315px'),
                navbarToggle.removeClass('pull-left')
            );

        }
        else {
            $.when(navSidebar.addClass('hidden')).done(
              
                pageWrapper.css('margin-left', '65px'),
                fixedTitle.css('left', '131px'),
                navbarToggle.addClass('pull-left')
            );
        }

    });

    // Toggle legende btn in sidebar navigation
    var position = 'expanded';
    $('span#btn-legend').on('click', function(e) {
        $('.legend-wrapper').slideToggle();
        var menuHeight = $('#side-menu').height();
        if( menuHeight < 321 ) {
            $( '.legend' ).toggleClass( "legend-shadow legend-absolute" );
        }
        else {
            $( '.legend' ).toggleClass( "legend-shadow" );
        }


        if (position == 'expanded' && menuHeight < 480 ) {
            $('#side-menu').animate({
                "min-height": "480px"
            });
            $( '.legend' ).addClass( "legend-absolute" );
            position = 'collapsed';
        } else {
            $('#side-menu').animate({"min-height": "320px"});
            $( '.legend' ).removeClass( "legend-absolute" );
            position = 'expanded';
        }

    });
    

    // Hide or show mandant selection if checkbox is checked
    
    var mandantHauptstelleCheckbox = $('input[type="checkbox"]#hauptstelle');
    var mandantHauptstelleSelect = $('.select-mandants');

    if(mandantHauptstelleCheckbox.prop('checked') == true) {
        $('select[name="mandant_id_hauptstelle"]').prop('required', false);
        mandantHauptstelleSelect.hide(400);
    } else {
        $('select[name="mandant_id_hauptstelle"]').prop('required', true)
        mandantHauptstelleSelect.show(400);
    }
        
        
    mandantHauptstelleCheckbox.change(function(e){
        console.log(mandantHauptstelleSelect.prop('required'));
        if(mandantHauptstelleCheckbox.prop('checked') == true){
            $('select[name="mandant_id_hauptstelle"]').prop('required', false);
            mandantHauptstelleSelect.hide(400);
        } else {
            $('select[name="mandant_id_hauptstelle"]').prop('required', true);
            mandantHauptstelleSelect.show(400);
        }
    });
    
    // Mandant history add button
    
    $('.history-add').on('click touch', function(e) {
        
        var gfHistory = $('textarea[name="geschaftsfuhrer_history"]');
        var gfSelect = $('select[name="geschaftsfuhrer"] option:selected').html().trim();
        var gfInfo = $('input[name="geschaftsfuhrer_infos"]').val().trim();
        var gfVon = $('input[name="geschaftsfuhrer_von"]').val().trim();
        var gfBis = $('input[name="geschaftsfuhrer_bis"]').val().trim();
        
        // console.log("\n" + gfSelect + " [" + gfVon + " - " + gfBis + "]: " + gfInfo + ";");
        gfHistory.val(gfHistory.val() + "\n" + gfSelect + " [" + gfVon + " - " + gfBis + "]: " + gfInfo + ";");
        
    });
    
    // Mandant Gewerbeanmeldung history add button
    
    $('.history-gewerbeanmeldung-add').on('click touch', function(e) {
        
        var gwHistory = $('textarea[name="gewerbeanmeldung_history"]');
        var gwInfoAngemeldet = $('input[name="angemeldet_am"]').val().trim();
        var gwInfoUmgemeldet = $('input[name="umgemeldet_am"]').val().trim();
        var gwInfoAbgemeldet = $('input[name="abgemeldet_am"]').val().trim();
        
        // console.log("\n" + "Angemeldet am: " + gwInfoAngemeldet + "; " +  "Umgemeldet am: " + gwInfoUmgemeldet + "; " + "Abgemeldet am: " + gwInfoAbgemeldet + "; " );
        gwHistory.val(gwHistory.val() + "\n" + "Angemeldet am: " + gwInfoAngemeldet + "; " +  "Umgemeldet am: " + gwInfoUmgemeldet + "; " + "Abgemeldet am: " + gwInfoAbgemeldet + "; " );
        
    });
    
    
    // Hide or show PDF upload checkbox 

    if( $(".document-type-select .select").val() == 1 || $(".document-type-select .select").val() == 2 || $(".document-type-select .select").val() == 3){
        $('.pdf-checkbox').show(400);
        $('.pdf-checkbox').find('input[name="pdf_upload"]').val(1);
    }
    else{
        $('.pdf-checkbox').hide(400);
        $('.pdf-checkbox').find('input[name="pdf_upload"]').removeAttr('value');
        
    }
    
    $('.document-type-select .select').chosen().change(function(event){
        if(event.target == this){
            //  console.log($(this).val());
            if( $(this).val() == 3 || $(this).val() == 2 || $(this).val() == 1 )
                $('.pdf-checkbox').show(400);
            else
                $('.pdf-checkbox').hide(400);
        }
    });

    // Hide or show ISO category selection based on selected value
    
    if($(".document-type-select .select").val() == 4)
        $('.iso-category-select').show(400);
    else
        $('.iso-category-select').hide(400);
    
    $('.document-type-select .select').chosen().change(function(event){
        if(event.target == this){
            // console.log($(this).val());
            if($(this).val() == 4) 
                $('.iso-category-select').show(400);
            else
                $('.iso-category-select').hide(400);
        }
    });


});
