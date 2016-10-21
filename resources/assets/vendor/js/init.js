/*
 * Created by Marijan on 25.04.2016..
 */
$(function() {

    /*Blank space fix for textareas*/
    $('textarea').each(function() {
        $(this).html($(this).html().trim());
    });
    /*End Blank space fix for textareas*/

    $('#side-menu').metisMenu({
        singleTapToGo: true,
        // doubleTapToGo: false,
        toggle: false,
        // preventDefault: false,
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
        ],
        order: [ 
            [ $('th.defaultSort').index(), 'asc' ]
        ],
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
            
             var docWidth = 680, docHeight =450;//820
             
            if( $('.document-orientation').length ){
                 docWidth = 'auto', docHeight = 680;
            }   
             if( $(this).data('height') ){
                docWidth = 'auto',docHeight = $(this).data('height');
            }
            tinymce.init({ 
                selector: '.editable', 
                skin_url: '/css/style',
                plugins:[ "table" ],
                 toolbar1: " mybutton | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                body_class: classes,
                width: docWidth,
                height: docHeight, 
                removed_menuitems: 'newdocument',
                elementpath: false,
                setup: function (editor) {
                editor.on('click', function (e) {
                });
                editor.on('NodeChange', function(e) {
                    if( e && e.element.nodeName.toLowerCase() == 'table' || e && e.element.nodeName.toLowerCase() == 'tr' ){
                       
                        e.find('td').each(function(){
                            processTableColumn( $(this) );
                        })
                    }
                    if( e && e.element.nodeName.toLowerCase() == 'td' ){
                        processTableColumn(e);
                    }
                    
    });
                editor.addButton('mybutton', {
                        type: 'button',
                        title: 'Bilder Upload',
                        icon: 'image-text',
                        onclick: function (e) {
                            var triggeredInsert = false;
                            
                            if ($(e.target).prop("tagName") == 'BUTTON') {
                                // console.log($(e.target).parent().parent().find('input').attr('id'));      
                                if ($(e.target).parent().parent().find('input').attr('id') != 'tinymce-uploader') {
                                    $(e.target).parent().parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;

                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 2');
                                            img = new Image();
                                            img.src = fr.result;
                                            editor.insertContent('<img style="max-width:100% !important" src="' + img.src + '"/>');
                                        }


                                    }

                                });

                            }
                            if ($(e.target).prop("tagName") == 'DIV') {
                                if ($(e.target).parent().find('input').attr('id') != 'tinymce-uploader') {
                                    console.log($(e.target).parent().find('input').attr('id'));
                                    $(e.target).parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;
                                    console.log('insert');
                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 3');
                                            img = new Image();
                                            img.src = fr.result;
                                            editor.insertContent('<img style="max-width:100% !important" src="' + img.src + '"/>');
                                        }

                                    }

                                });

                            }
                            if ($(e.target).prop("tagName") == 'I') {
                                console.log($(e.target).parent().parent().parent().find('input').attr('id'));
                                if ($(e.target).parent().parent().parent().find('input').attr('id') != 'tinymce-uploader') {
                                    $(e.target).parent().parent().parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;

                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 1');
                                            img = new Image();
                                            img.src = fr.result;
                                            editor.insertContent('<img style="max-width:100% !important" src="' + img.src + '"/>');
                                        }

                                    }

                                });

                            }

                        }
                    }//end setup button
                );
            }

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
                toolbar1: " mybutton | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                body_class: classes,
                height: docHeight, 
                height: 350,
                removed_menuitems: 'newdocument',
                elementpath: false,
                setup: function (editor) {
                editor.on('click', function (e) {
                });
                editor.on('NodeChange', function(e) {
                    if( e && e.element.nodeName.toLowerCase() == 'table' || e && e.element.nodeName.toLowerCase() == 'tr' ){
                       
                        e.find('td').each(function(){
                            processTableColumn( $(this) );
                        })
                    }
                    if( e && e.element.nodeName.toLowerCase() == 'td' ){
                        processTableColumn(e);
                    }
                    
    });
                editor.addButton('mybutton', {
                        type: 'button',
                        title: 'Bilder Upload',
                        icon: 'image-text',
                        onclick: function (e) {
                            var triggeredInsert = false;
                            
                            if ($(e.target).prop("tagName") == 'BUTTON') {
                                // console.log($(e.target).parent().parent().find('input').attr('id'));      
                                if ($(e.target).parent().parent().find('input').attr('id') != 'tinymce-uploader') {
                                    $(e.target).parent().parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;

                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 2');
                                            img = new Image();
                                            img.src = fr.result;
                                            editor.insertContent('<img style="max-width:100% !important" src="' + img.src + '"/>');
                                        }


                                    }

                                });

                            }
                            if ($(e.target).prop("tagName") == 'DIV') {
                                if ($(e.target).parent().find('input').attr('id') != 'tinymce-uploader') {
                                    console.log($(e.target).parent().find('input').attr('id'));
                                    $(e.target).parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;
                                    console.log('insert');
                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 3');
                                            img = new Image();
                                            img.src = fr.result;
                                            editor.insertContent('<img style="max-width:100% !important" src="' + img.src + '"/>');
                                        }

                                    }

                                });

                            }
                            if ($(e.target).prop("tagName") == 'I') {
                                console.log($(e.target).parent().parent().parent().find('input').attr('id'));
                                if ($(e.target).parent().parent().parent().find('input').attr('id') != 'tinymce-uploader') {
                                    $(e.target).parent().parent().parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;

                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 1');
                                            img = new Image();
                                            img.src = fr.result;
                                            editor.insertContent('<img style="max-width:100% !important" src="' + img.src + '"/>');
                                        }

                                    }

                                });

                            }

                        }
                    }//end setup button
                );
            }

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
            
            var docWidth = 680, docHeight =450;//820
            if( $('.document-orientation').length )
                  docWidth = 'auto', docHeight = 680; 
            
            tinymce.init({
                selector: '.variant',
                skin_url: '/css/style',
                plugins:[ "table" ],
                // toolbar1: " mybutton | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect  ",
                toolbar1: " mybutton | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
                body_class: classes,
                width: docWidth,
                height: docHeight, 
                
                removed_menuitems: 'newdocument',
                style_formats: [
                    { title: 'Spiegelstriche', selector: 'ul', classes: 'list-style-dash'},
                    // {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}, classes: 'red-text'}
                ],
                style_formats_merge: true,
                elementpath: false,
                setup: function (editor) {
                editor.on('click', function (e) {
                });
                editor.on('NodeChange', function(e) {
                    /*if( e && e.element.nodeName.toLowerCase() == 'table' || e && e.element.nodeName.toLowerCase() == 'tr' ){
                       
                        e.find('td').each(function(){
                            processTableColumn( $(this) );
                        })
                    }*/
                    if( e && e.element.nodeName.toLowerCase() == 'td' ){
                        processTableColumn(e);
                    }
                    
    });
                editor.addButton('mybutton', {
                        type: 'button',
                        title: 'Bilder Upload',
                        icon: 'image-text',
                        onclick: function (e) {
                            var triggeredInsert = false;
                            
                            if ($(e.target).prop("tagName") == 'BUTTON') {
                                // console.log($(e.target).parent().parent().find('input').attr('id'));      
                                if ($(e.target).parent().parent().find('input').attr('id') != 'tinymce-uploader') {
                                    $(e.target).parent().parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;

                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 2');
                                            img = new Image();
                                            img.src = fr.result;
                                           imgWidth = img.width;
                                            imgHeight =img.height;
                                             editor.insertContent('<img style="max-width:100%;" src="' + img.src + '"/>');
                                        }


                                    }

                                });

                            }
                            if ($(e.target).prop("tagName") == 'DIV') {
                                if ($(e.target).parent().find('input').attr('id') != 'tinymce-uploader') {
                                    console.log($(e.target).parent().find('input').attr('id'));
                                    $(e.target).parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;
                                    console.log('insert');
                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 3');
                                            img = new Image();
                                            img.src = fr.result;
                                            imgWidth = img.width;
                                            imgHeight =img.height;
                                            img.onload = function() {
                                              imgWidth = this.width;
                                              imgHeight= this.height;
                                            }
                                             editor.insertContent('<img style="max-width:100%;" src="' + img.src + '"/>');
                                        }

                                    }

                                });

                            }
                            if ($(e.target).prop("tagName") == 'I') {
                                if ($(e.target).parent().parent().parent().find('input').attr('id') != 'tinymce-uploader') {
                                    $(e.target).parent().parent().parent().append('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                                }
                                $('#tinymce-uploader').trigger('click');
                                $('#tinymce-uploader').change(function () {
                                    var input, file, fr, img;

                                    if (typeof window.FileReader !== 'function') {
                                        write("The file API isn't supported on this browser yet.");
                                        return;
                                    }

                                    input = document.getElementById('tinymce-uploader');
                                    if (!input) {
                                        write("Um, couldn't find the imgfile element.");
                                    }
                                    else if (!input.files) {
                                        write("This browser doesn't seem to support the `files` property of file inputs.");
                                    }
                                    else if (!input.files[0]) {
                                        write("Please select a file before clicking 'Load'");
                                    }
                                    else {
                                        file = input.files[0];
                                        fr = new FileReader();
                                        fr.onload = createImage;
                                        fr.readAsDataURL(file);
                                    }

                                    function createImage() {
                                        if (triggeredInsert == false) {
                                            triggeredInsert = true;
                                            // console.log('create image 1');
                                            img = new Image();
                                            img.src = fr.result;
                                            imgWidth = img.width;
                                            imgHeight =img.height;
                                            img.onload = function() {
                                              imgWidth = this.width;
                                              imgHeight= this.height;
                                            }
                                            editor.insertContent('<img style="max-width:100%;" src="' + img.src + '"/>');
                                        }

                                    }

                                });

                            }

                        }
                    }//end setup button
                );
            }
            });
        });
        
    }
    
    function processTableColumn(e){
        var td = $(e.element), maxHeight =  $(e.element).height()+1 ;
        
        /*If td has images correct the whole row */
        if(td.find('img').length){
           /* $( e.element ).find('img').each(function() {
                var height = $(this).innerHeight(), width = $(this).innerWidth();
                // $(this).attr('style', $(this).attr('style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
                // $(this).attr('data-mce-style', $(this).attr('data-mce-style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
                $(this).attr('style', $(this).attr('style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
                $(this).attr('data-mce-style', $(this).attr('data-mce-style')+'min-height: '+height+'px !important; width: '+width+'px !important;');
                if(height != maxHeight && height > maxHeight)
                    maxHeight = height;
            });*/
        }               
        
        
        /* Determine the biggest height in tr*/
        
            td.closest('tr').find('td').each(function(){
                var calc = $(this).height();
                if( maxHeight < $(this).height())
                    maxHeight = $(this).height(); 
            });
        /* End Determine the biggest height in tr*/
        
        /* Go to each td, clear the style height, data-mce-style and after that set height and width */
            td.closest('tr').attr('style',findTinyMCE(td.closest('tr'),'height')+';').find('td').each(function(){
                    removeCss( $(this),'height');
                    removeCss( $(this),'min-height');
                    // removeCss( $(this),'width');
                    
                    removeCss( $(this),'vertical-align');
                    removeCss( $(this),'height','data-mce-style');
                    removeCss( $(this),'min-height','data-mce-style');
                    // removeCss( $(this),'width','data-mce-style');
                    removeCss( $(this),'vertical-align','data-mce-style');
                    
                    removeCss( $(this),'height');
                    removeCss( $(this),'height','data-mce-style');
                    
                    setNewTdAttributes($(this), maxHeight,'style',true)
                    setNewTdAttributes($(this), maxHeight, attribute='data-mce-style',true)
            });
        /* End Go to each td, clear the style height, data-mce-style and after that set height and width */
                    findTinyMCE(td.closest('tr'),'height');
                     totalTableHeight = td.closest('table').height();
                    
                    /*removeCss( $(this),'height');
                    removeCss( $(this),'height','data-mce-style');*/
                    var cnt = 0;
                    td.closest('table').find('tr').each(function(){
                        cnt = cnt+ $(this).height();
                    });
                    td.closest('table').css('height','auto')
                    /*setNewTdAttributes(td.closest('table'), cnt, attribute='style',true);
                    setNewTdAttributes(td.closest('table'), cnt, attribute='data-mce-style',true);*/
    }
    
    /* Remove style */
    function removeCss(element, toDelete,attribute='style'){
            var props=element.attr(attribute).split(';');
            var tmp=-1;
            for( var p=0;p<props.length; p++){if(props[p].indexOf(toDelete)!==-1){tmp=p}};
            if(tmp!==-1){
                delete props[tmp];
            }
                for(var key in props){
                    if( props[key].trim() == ''  )
                        delete props[key];
                }
                var finalAttr = '';
                for(var key in props){
                    finalAttr += props[key]+'; '
                }
          return element.attr(attribute,finalAttr);
        
    }
    /*End remove style*/
    
    /* Remove style */
    function findTinyMCE(element, toDelete,attribute='data-mce-style'){
            if(typeof element.attr(attribute) != 'undefined' )
                var props=element.attr(attribute).split(';');
            else
                return 'height:'+element.height()-1+'px';
            var tmp=-1;
            for( var p=0;p<props.length; p++){if(props[p].indexOf(toDelete)!==-1){tmp=p}};
            if(tmp!==-1){
                return props[tmp];
            }
               /* var finalAttr = '';
                for(var key in props){
                    finalAttr += props[key]+'; '
                }
          return element.attr(attribute,finalAttr);*/
        
    }
    /*End remove style*/
    
    function setNewTdAttributes(element, height, attribute='style',isRow=false){
      
            var existingAttribute = element.attr(attribute);
            if( existingAttribute.indexOf('height') == -1 )
                existingAttribute = existingAttribute+' height:'+height+'px !important; ';
            if( isRow == false ){
                if( existingAttribute.indexOf('width') == -1 )
                    existingAttribute = existingAttribute+' width:'+element.width()+'px !important; ';  
            }    
              
            return element.attr(attribute,existingAttribute);
        
    }
    
    
    

    /* Automatic trigger to open the panel heading */
    if( $('[data-open]').length){
        $('[data-open]').each(function(){
           $(this).click();
        });
    } 
    /* End Automatic trigger to open the panel heading */
    
    /* Add empty select option to doropdon */
    // $(".empty-select").prepend("<option value='' >&nbsp;</option>");
    /* End Add empty select option to doropdon */
      
      
}); /*End function() wrapper*/