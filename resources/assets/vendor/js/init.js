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
        toggle: false,
    });

    $(".select").chosen({});

    $(".datetimepicker").datetimepicker({
        locale: 'de',
        format: 'DD.MM.YYYY',
        showTodayButton: true,
        showClear: true,
        widgetPositioning: {
            horizontal: 'auto',
            vertical: 'bottom'
        }
    });

    if ($('.tree-view').length) {
        var counter = 0; //let insted of var
        var $treeview = [];//let insted of var
        $('.tree-view').each(function() {
            $treeview[counter] = $(this).treeview({
                expandIcon: 'custom-expand-icon',
                collapseIcon: 'custom-collapse-icon',
                data: $('.' + $(this).data('selector')).html(),
                color: "#428bca",
                showTags: false,
                enableLinks: true,
                enableDelete: true,
                enableHistory: true,
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
        columnDefs: [
            { targets: 'no-sort', orderable: false },
            { targets: 'col-hide', visible: false }
        ]
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
                classes += $(this).data('classes');
            
             var docWidth = 794, docHeight =1122;
             
            if( $('.document-orientation').length ){
                 docWidth = 'auto', docHeight = 794;
            }   
             if( $(this).data('height') ){
                docWidth = 'auto',docHeight = $(this).data('height');
            }
              
            tinymce.init({ 
                selector: '.editable', 
                skin_url: '/css/style',
                plugins:[ "table" ],
                toolbar1: " | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect  ",
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
            var docHeight =350;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'content-editor-' + counter);
            var classes = '';
            if( $(this).data('classes') )
                classes += $(this).data('classes');
            tinymce.init({
                selector: '.content-editor',
                skin_url: '/css/style',
                plugins:[ "table" ],
                toolbar1: " | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect  ",
                body_class: classes,
                height: docHeight, 
                height: 350,
                removed_menuitems: 'newdocument',
                elementpath: false,

            });
        });
    }
    if ($('.variant').length) {
        $('.variant').closest('form').addClass('.tinymce-image');
        
        var counter = 0;
        $('.variant').each(function() {
            
            counter++;
            if ($(this).data('id'))
                $(this).attr('id', $(this).data('id'));
            else
                $(this).attr('id', 'variant-' + counter);
            var classes = ' ';
            if( $(this).data('classes') )
                classes += $(this).data('classes');
            
            var docWidth = 794, docHeight =1122;
            if( $('.document-orientation').length )
                  docWidth = 'auto', docHeight = 794; 
            
            tinymce.init({
                selector: '.variant',
                skin_url: '/css/style',
                plugins:[ "table" ],
                toolbar1: " | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect  ",
                body_class: classes,
                width: docWidth,
                height: docHeight, 
                removed_menuitems: 'newdocument',
                elementpath: false,

            });
        });
    }
    
   
    /*$('form').submit(function(e){
        
        if( $(this).find('.variant').length ){
            // 
            
            e.preventDefault();
            var form = $(this);
             $('.variant').each(function(){
                 var id = $(this).attr('id');
                 $('#'+id).html(tinymce.get(id).getContent());
                 tinymce.get(id).save();
                 var content = $('#'+id+'_ifr').get(0).contentWindow.document.body.innerHTML;
                 if( content == '<p><br data-mce-bogus="1"></p>')
                    content = '';
                 console.log(id);
                //  console.log(tinyMCE.get(id));
                //  console.log(tinymce.get(id).getContent());
                //  console.log( $('#'+id+'_ifr body').contents() );
                //  console.log( $('#'+id+'_ifr body').contents().html() );
                //  console.log( $('#'+id+'_ifr body').html() );
                //  console.log( $('#'+id+'_ifr body').text() );
                 console.log( );
                //  console.log( $(document).find('#'+id+'_ifr body').contents().find("html").html() );
                //  console.log( document.getElementById(id).contentWindow.document.body.innerHTML);
                 
             });
            //   form.trigger('submit');
        }
        
        // $('#elm1').html(tinymce.get('elm1').getContent()); 
    });*/
    
    function elFinderBrowser (field_name, url, type, win) {
      tinymce.activeEditor.windowManager.open({
        file: '',// use an absolute path!
        title: 'elFinder 2.0',
        width: 900,
        height: 450,
        resizable: 'yes'
      }, {
        setUrl: function (url) {
          win.document.getElementById(field_name).value = url;
        }
      });
      return false;
    }
    var imageFilePicker = function (callback, value, meta) {               
    tinymce.activeEditor.windowManager.open({
        title: 'Image Picker',
        url: '/images/getimages',
        width: 650,
        height: 550,
        buttons: [{
            text: 'Insert',
            onclick: function () {
                //.. do some work
                tinymce.activeEditor.windowManager.close();
            }
        }, {
            text: 'Close',
            onclick: 'close'
        }],
    }, {
        oninsert: function (url) {
            callback(url);
            console.log("derp");
        },
    });
};
    /*Scroll to element id*/
    if( $('.scrollTo').length ){
        var element = $('.scrollTo').val(), navHeight = $('.navbar-fixed-top').height()+10;
        var contactTopPosition = $(element).position().top;
    
    }
    /*End Scroll to element id*/

    /* Automatic trigger to open the panel heading */
    if( $('[data-open]').length){
        $('[data-open]').each(function(){
           $(this).click();
        });
    } 
    /* End Automatic trigger to open the panel heading */
      
      
}); /*End function() wrapper*/