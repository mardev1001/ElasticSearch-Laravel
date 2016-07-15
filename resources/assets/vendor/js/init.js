/**
 * Created by Marijan on 25.04.2016..
 */
$(function() {

    /*Blank space fix for textareas*/
    $('textarea').each(function() {
        $(this).html($(this).html().trim());
    });
    /*End Blank space fix for textareas*/

    $('#side-menu').metisMenu({
        doubleTapToGo: true,
    });

    $(".select").chosen({});

    $(".datetimepicker").datetimepicker({
        locale: 'de',
        format: 'DD.MM.YYYY',
        showTodayButton: true,
        showClear: true,
    });

    if ($('.tree-view').length) {
        let counter = 0;
        let $treeview = [];
        $('.tree-view').each(function() {
            $treeview[counter] = $(this).treeview({
                expandIcon: 'custom-expand-icon',
                collapseIcon: 'custom-collapse-icon',
                data: $('.' + $(this).data('selector')).html(),
                color: "#428bca",
                showTags: false,
                enableLinks: true,
                enableDelete: true,
                levels: 0,
            });
        });

    }
    
    if( $('ul.pagination').length){
        $('ul.pagination').each(function(){
           $(this).find('li').first().addClass('pull-left').find('a').html('&lt; zurück');
           $(this).find('li').first().find('span').html('&lt; zurück');
           
           $(this).find('li').last().addClass('pull-right').find('a').html('weiter &gt;');
           $(this).find('li').last().find('span').html('weiter &gt;');
        });
    }
    
    $('.data-table').DataTable({
        searching: false,
        paging: false,
        info: false,
        language: {
        	"sEmptyTable":   	"Keine Daten vorhanden.",
        	"sInfo":         	"_START_ bis _END_ von _TOTAL_ EintrÃ¤gen",
        	"sInfoEmpty":    	"0 bis 0 von 0 EintrÃ¤gen",
        	"sInfoFiltered": 	"(gefiltert von _MAX_ EintrÃ¤gen)",
        	"sInfoPostFix":  	"",
        	"sInfoThousands":  	".",
        	"sLengthMenu":   	"_MENU_ EintrÃ¤ge anzeigen",
        	"sLoadingRecords": 	"Wird geladen...",
        	"sProcessing":   	"Bitte warten...",
        	"sSearch":       	"Suchen",
        	"sZeroRecords":  	"Keine EintrÃ¤ge vorhanden.",
        	"oPaginate": {
        		"sFirst":    	"Erste",
        		"sPrevious": 	"ZurÃ¼ck",
        		"sNext":     	"NÃ¤chste",
        		"sLast":     	"Letzte"
        	},
        	"oAria": {
        		"sSortAscending":  ": aktivieren, um Spalte aufsteigend zu sortieren",
        		"sSortDescending": ": aktivieren, um Spalte absteigend zu sortieren"
        	}
        },
        columnDefs: [{
            targets: 'no-sort',
            orderable: false
        }]
    });
    if ($('.editable').length) {
        var counter = 0;
        $('.editable').each(function() {
            counter++;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'editor-' + counter);
            var classes = '';
            if( $(this).data('classes') )
                classes = $(this).data('classes');
            
             var docWidth = 794, docHeight =1122;
             
            if( $('.document-orientation').length ){
                 docWidth = 'auto', docHeight = 794;
            }    
                
            tinymce.init({
                selector: '.editable',
                skin_url: '/css/style',
                body_class: classes,
                width: docWidth,
                height: docHeight,
                removed_menuitems: 'newdocument',
                elementpath: false,

            });
        });
    }
    if ($('.content-editor').length) {
        var counter = 0;
        $('.content-editor').each(function() {
            counter++;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'content-editor-' + counter);
            var classes = '';
            if( $(this).data('classes') )
                classes = $(this).data('classes');
            tinymce.init({
                selector: '.content-editor',
                skin_url: '/css/style',
                body_class: classes,
                height: 350,
                removed_menuitems: 'newdocument',
                elementpath: false,

            });
        });
    }
    if ($('.variant').length) {
        var counter = 0;
        $('.variant').each(function() {
            counter++;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'variant-' + counter);
            var classes = '';
            if( $(this).data('classes') )
                classes = $(this).data('classes');
            
            var docWidth = 794, docHeight =1122;
            if( $('.document-orientation').length )
                  docWidth = 'auto', docHeight = 794;
                
            tinymce.init({
                selector: '.variant',
                skin_url: '/css/style',
                body_class: classes,
                width: docWidth,
                height: docHeight,
                removed_menuitems: 'newdocument',
                elementpath: false,

            });
        });
    }
    /*Scroll to element id*/
    if( $('.scrollTo').length ){
        var element = $('.scrollTo').val(), navHeight = $('.navbar-fixed-top').height()+10;
        var contactTopPosition = $(element).position().top;
       
      /*   $('html,body').animate({
            scrollTop: $('#page-wrapper').find(element).offset().top
        }, 2000); */
      
        // $('#page-wrapper').css('margin-top',navHeight);
    }
    /*End Scroll to element id*/


}); /*End function() wrapper*/