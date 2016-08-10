/**
 * Created by Marijan on 25.04.2016..
 */
$( function() {
    /*Bind laravel security token to ajax*/
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $(document).find('[name="csrf-token"]').val() } });
    /* End Bind laravel security token to ajax*/
    
     $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    /*Exapand active class*/
    var url = window.location;
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).parents("ul").not('#side-menu').addClass('in');
    // }).addClass('active').parent().parent().addClass('in').parent();
        
    /*Fix the problem where the */    
    if( (location.protocol + "//" + location.host+'/')  !=  url.href){
        $('a[href="/"]').removeClass('active');
    }
        $('a.active').each(function(){
            var url = window.location, currentLink = window.location.href 
            if( $(this).attr('href') != currentLink )
                $(this).removeClass('active');
        });
    if( (location.protocol + "//" + location.host+'/')  ==  url.href){
        $('a[href="/"]').addClass('active');
    }
    else if( url.href.indexOf('edit') != -1 && url.href.indexOf('benutzer') != -1){
        $('a[href*="benutzer/create"]').addClass('active').closest('ul').addClass('in');  
    }
    else if(  typeof documentType !== 'undefined' && documentType.length){
        var detectHref = '/dokumente/rundschreiben';
        if(documentType == "Formulare")
            detectHref = '/dokumente/vorlagedokumente';
            
        else if(documentType == "QM-Rundschreiben")
            detectHref = '/dokumente/rundschreiben-qmr';
            
        else if(documentType == "News")
            detectHref = '/dokumente/news';
            
        else if(documentType == "ISO Dokumente"){
            if( typeof  isoCategoryName  != 'undefined') 
                detectHref = $('#side-menu').find('a:contains("'+isoCategoryName+'")').attr('href');
            else
                detectHref = '/iso-dokumente';
         
        }
       $('a[href$="'+detectHref+'"]').addClass('active').parents("ul").not('#side-menu').addClass('in');
    }
    else{
         $('a[href="'+url.href+'"]').addClass('active');
    }
    if (element.is('li')) {
        element.addClass('active');
    }
    
     
    else
    /*End Exapand active class*/
    
    /* Simulate tree view */
    if( $('.tree').length ){
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').addClass('icon-plus-sign').removeClass('icon-minus-sign');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').addClass('icon-minus-sign').removeClass('icon-plus-sign');
            }
            e.stopPropagation();
        });
    }
    /* End Simulate tree view */
    
    /* Simulate tree view2 */
    $('label.tree-toggler').click(function () {
		$(this).parent().children('ul.tree').toggle(300);
	});
    /* End Simulate tree view2 */
    
    /* Prevent a.href=# from exec. Becouse of the nav.active script */
    $('a').on('click touch',function(e){
       if( $(this).attr('href') == "#" )
            e.preventDefault();
    });
    /* End Prevent a.href=# from exec. Becouse of the nav.active script */
    
    /* Image preview before upload */
    if( $('#image-upload').length ){
        document.getElementById("image-upload").onchange = function () {
            var reader = new FileReader();
        
            reader.onload = function (e) {
                // get loaded data and render thumbnail.
                document.getElementById("image-preview").src = e.target.result;
            };

            // read the image file as a data URL.
            reader.readAsDataURL(this.files[0]);
        };
    }
    /* End Image preview before upload */
    
    /*Copy new line*/
     var addRow = function(e) {
        var form = $(this);
       var method = form.find('input[name="_method"]').val() || 'POST';
        $.ajax({
            type: method,
            url: form.attr('action'),
            data: form.serialize(),
            success: function(data) {
                // console.log(data);
              form.closest('.bind-before').before(data);
              $('.select.mandant-roles').chosen();
            },
            error:function(data){
               console.log(data);


            },
            
        });

        e.preventDefault();
    };
   
    // $('[data-adder]').on('click touch', addRow);
    /*End copy new line*/
     /*
     *Prevent accordion collapse trigger from adding hashtags at the address bar. 
     * This will prevent metisMenu (sidebar) from expanding
     */
    $('[data-toggle="collapse"]').on('click touch',function(e){
        e.preventDefault();
    });
    
    $('.list-group').on('click touch',function(){
        $(this).find('li.node-selected').find('.glyphicon').trigger('click');
       
    });
   
    // Show elements if checkbox is checked
    $('.trigger-inputs').on('change',function(e){
        if ( $(this).is(':checked') ){
            $('[data-hide='+$(this).data('second')+']' ).removeClass('hide').attr('required');
        }
        else{
            $('[data-hide='+$(this).data('second')+']' ).addClass('hide').removeAttr('required');
        }
        
    });
    
    // Hide elements if checkbox is checked
    $('.hide-input').on('change', function(e){
        if ( $(this).is(':checked') ){
            $('[data-hide='+$(this).data('hide-target')+']' ).addClass('hide').removeAttr('required');
            $('[data-disable='+$(this).data('disable-target')+']' ).attr('disabled', true);
        }
        else{
            $('[data-hide='+$(this).data('hide-target')+']' ).removeClass('hide').attr('required');
            $('[data-disable='+$(this).data('disable-target')+']' ).attr('disabled', false);
        }
        
    });
    
    
    
    /* Trigger tab switch*/
    /*$('.nav-tabs li a').click(function (e) {
      e.preventDefault()
      $(this).tab('show')
    })*/
    /* End Trigger tab switch*/
    
    /* Trigger tab destroy*/
        $(document).on('click touch','[data-delete-variant]',function(){
           var tabsNumber =  $('#tabs li').size()-1;
           if(tabsNumber >= 1){
                var variantId = $(this).data('delete-variant');
                tinymce.execCommand('mceRemoveControl', true, 'variant-'+variantId);
                $('#variant'+variantId).remove();
                $('#variation'+variantId).remove();
                $(this).closest('li').remove();
                 $('.nav-tabs li.active').removeClass('active');
          	    $('.tab-content .tab-pane').removeClass('active');
                $('.nav-tabs li').first().addClass('active'); 
          	    $('.tab-content .tab-pane').first().addClass('active'); 
           }
         
        });
    /* End Trigger tab destroy*/
    
    /* Trigger tab creation*/
       $('.add-tab').on('click touch',function(){
           	var parent =  $(this),
           	prevNumber = $(this).closest('.parent-tabs').find('.nav-tabs li').size(),
           	nextTab = $(this).closest('.parent-tabs').find('.nav-tabs li').size()+1,
           	prevHTML = '';
           	if( $(this).closest('.parent-tabs').find('.nav-tabs li').last().length ){
           	    var last =$(this).closest('.parent-tabs').find('.nav-tabs li').last().data('variant');
           	    
           	    if( isNaN( parseInt(last) ) == true)
           	        nextTab = $(this).closest('.parent-tabs').find('.nav-tabs li').size()+1;
           	    else{
         	         prevNumber = parseInt(last);
           	         nextTab = parseInt(last)+1;
           	    }
           	}
           	//Check if content exists to prevent undefined error
           	if( $('#variant-'+prevNumber).length ){
           	    prevHTML = tinymce.get('variant-'+prevNumber).getContent();
           	}
           	if( $('#editor-'+prevNumber).length ){
           	    prevHTML = tinymce.get('editor-'+prevNumber).getContent(); 
           	}
      	// create the tab
      	$('<li data-variant="'+nextTab+'"><a href="#variation'+nextTab+'" data-toggle="tab">Variante '+nextTab+' <span class="fa fa-close remove-editor" data-delete-variant="'+nextTab+'"></span></a></li>')
      	.appendTo('#tabs');
      	 
      	// create the tab content
      	$('<div class="tab-pane" id="variation'+nextTab+'" data-id="'+nextTab+'"><div data-id="'+nextTab+'" id="variant-'+nextTab+'" class="editable variant-'+nextTab+'" >'+prevHTML+'</div></div>').appendTo('.tab-content');
      	var counter = 0;
      	
      	/*
      	
      	*/
      	$('.editable').each(function() {
                        counter++;
                        
                        if ($(this).data('id'))
                            $(this).attr('id', 'variant-'+$(this).data('id'));
                        else
                            $(this).attr('id', 'variant-' + counter);
                       tinymce.init({
                            selector: '.editable',
                            skin_url: '/css/style',
                            width: 794,
                            height: 1122,
                            removed_menuitems: 'newdocument',
                        });
        });
      	if( $('.nav-tabs li.active').length < 1 ){
      	    $('.nav-tabs li').first().addClass('active'); 
      	    $('.tab-content .tab-pane').first().addClass('active'); 
      	}
      	else{
      	    $('.nav-tabs li.active').removeClass('active');
      	    $('.tab-content .tab-pane').removeClass('active');
      	    $(document).find('a[href="#variation'+nextTab+'"]').closest('li').addClass('active');
      	    $('#variation'+nextTab).addClass('active'); 
      	    $(document).find('a[href="#variation'+nextTab+'"]').click();//for preview hidden input change
      	    
      	}
       });
    /* End trigger tab creation*/
    
    /* Simulate submit button*/
        $('.simulate-submit').on('click touch',function(e){
            e.preventDefault();
            $(this).closest('form').submit(); 
        });
    /* End Simulate submit button*/
    
    
    $('#return-to-top').click(function() {      // When arrow is clicked
        $('body,html,#wrapper').animate({
            scrollTop : 0                       // Scroll to top of body
        }, 500);
    });
    
    /* Trigger scroll to top display on scroll */
    $('#wrapper').on('scroll',function() {
        if ($(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
            $('#return-to-top').fadeIn(200);    // Fade in the arrow
        } else {
            $('#return-to-top').fadeOut(200);   // Else fade out the arrow
        }
    });
    /* End Trigger scroll to top display on scroll */
    
    /* Go to top */
    $('#return-to-top').click(function() {      // When arrow is clicked
        $('body,html,#wrapper').animate({
            scrollTop : 0                       // Scroll to top of body
        }, 500);
    });
    /* End Go to top */
    
    /* On click check if form is empty and submit or go to URL*/
     $('[data-link]').on('click touch',function(e){
        window.location = $(this).data('link');
    });
    /* End On click check if form is empty and submit or go to URL*/
    
    /* Attachment option 2 - if file selected and  option dosent' have title, and isset 3 hidden fields*/
        $('input[type=file]').on('change',function(){
            var fileName = $('input[type=file]')[0].files[0].name, title =$(this).closest('form').find("input[name='name']").val(),
            fileNameWithoutExtension =fileName.replace(/\.[^/.]+$/, "");
            
            if( $( "input[name='user_id']" ).length && $( "input[name='document_id']" ) && $( "input[name='variant_id']" ) && title =='' ){
                $(this).closest('form').find("input[name='name']").val(fileNameWithoutExtension);
            }
        });
    /* EndAttachment option 2 - if file selected and  option dosent' have title, and isset 3 hidden fields*/
    
    
    /*Rechte and Freigabe form on click check is it a slow freigabe and add the required field */
    // $('.freigabe-process').submit(function(e){
    //     e.preventDefault();
    //     console.log('prevented');
    //     var allPost = $(this).serialize();
    //     console.log(allPost); 
    // })
    $('.freigabe-process .no-validate').on('click touch', function(e){
        e.preventDefault();
        var input = $("<input>").attr("type", "hidden").attr("name", $(this).attr('name')).val( $(this).val() );
        $('.freigabe-process').append($(input));
        $('.freigabe-process').find('.approval-users').removeAttr('required');
        $('.freigabe-process').submit(); 
    });
    
    $('.freigabe-process .validate').on('click touch', function(e){
        var input = $("<input>").attr("type", "hidden").attr("name", $(this).attr('name')).val( $(this).val() );
            $('.freigabe-process').append($(input));
            if( $('.approval-users')[0].checkValidity() == true ){
                $('.freigabe-process').submit(); 
            }
    });
    /*End Rechte and Freigabe form on click check is it a slow freigabe and add the required field */
    
    /* Change the hidden input value on sites with .preview */
    if( $('.preview').length ){
        $(document).on('click touch','[data-variant] a',function(){
           if( $(this).closest('li').hasClass('active') ){
                $( 'input[name="current_variant"]' ).val( $(this).closest('li').data('variant') );
            }
         
        });
       
    }
    /* End Change the hidden input value on sites with .preview */
    
    
});