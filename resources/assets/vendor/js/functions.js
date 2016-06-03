/**
 * Created by Marijan on 27.04.2016..
 */
$(function() {

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

        if (navSidebar.hasClass('hidden')) {

            $.when(navSidebar.removeClass('hidden')).done(
                pageWrapper.removeAttr('style'),
                navbarToggle.removeClass('pull-left')
            );

        }
        else {
            $.when(navSidebar.addClass('hidden')).done(
                pageWrapper.css('margin-left', '65px'),
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

    if(mandantHauptstelleCheckbox.prop('checked') == true)
        mandantHauptstelleSelect.hide();
    else
        mandantHauptstelleSelect.show();
        
    mandantHauptstelleCheckbox.change(function(e){
        if(mandantHauptstelleCheckbox.prop('checked') == true)
            mandantHauptstelleSelect.hide();
        else
            mandantHauptstelleSelect.show();
    });
    
    // Hide or show PDF upload checkbox 

    if($(".document-type-select .select").val() == 3){
        $('.pdf-checkbox').show();
        $('.pdf-checkbox').find('input[name="pdf_upload"]').val(1);
    }
    else{
        $('.pdf-checkbox').hide();
        $('.pdf-checkbox').find('input[name="pdf_upload"]').removeAttr('value');
        
    }
    
    $('.document-type-select .select').chosen().change(function(event){
        if(event.target == this){
            // console.log($(this).val());
            if($(this).val() == 3)
                $('.pdf-checkbox').show();
            else
                $('.pdf-checkbox').hide();
        }
    });

    // Hide or show ISO category selection based on selected value
    
    if($(".document-type-select .select").val() == 4)
        $('.iso-category-select').show();
    else
        $('.iso-category-select').hide();
    
    $('.document-type-select .select').chosen().change(function(event){
        if(event.target == this){
            // console.log($(this).val());
            if($(this).val() == 4) 
                $('.iso-category-select').show();
            else
                $('.iso-category-select').hide();
        }
    });


});
