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

        var navSidebar = $('.navbar-default.sidebar');
        var pageWrapper = $('#page-wrapper');

        if (navSidebar.hasClass('hidden')) {

            $.when(navSidebar.removeClass('hidden')).done(
                pageWrapper.removeAttr('style')
            );

        }
        else {
            $.when(navSidebar.addClass('hidden')).done(
                pageWrapper.css('margin-left', '0')
            );
        }

    });


});
