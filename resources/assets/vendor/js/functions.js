/**
 * Created by Marijan on 27.04.2016..
 */
$(function() {

    $('[data-hideswitch]').on('click', function(e) {
        $(this).toggle();
        console.log('trig');
        console.log($(this).attr('checked'));
        var $this = $(this),
            yesClass = $('.' + $this.data('yes')),
            noClass = $('.' + $this.data('no'));
        if ($this.attr('checked', true) || $this.attr('checked', 'checked')) {
            yesClass.removeClass('hidden');
            noClass.addClass('hidden');
        }
        else {
            noClass.removeClass('hidden');
            yesClass.addClass('hidden');
        }

    });

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
    
    
    

});
