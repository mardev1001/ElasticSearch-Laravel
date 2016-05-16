/**
 * Created by Marijan on 25.04.2016..
 */
$( function() {
    /*Bind laravel security token to ajax*/
     $.ajaxSetup({
            headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });
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
    }).addClass('active').parent().parent().addClass('in').parent();
        
    /*Fix the problem where the */    
    if( (location.protocol + "//" + location.host+'/')  !=  url.href)
        $('a[href="/"]').removeClass('active');
        $('a.active').each(function(){
            var url = window.location, currentLink = window.location.href 
            if( $(this).attr('href') != currentLink )
                $(this).removeClass('active');
        });
        
    if (element.is('li')) {
        element.addClass('active');
    }
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
    
    
    /* Image preview before upload */
    if( $('#logo').length ){
        document.getElementById("logo").onchange = function () {
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
              form.closest('.bind-before').before(data);
            },
            error:function(data){
               


            },
            
        });

        e.preventDefault();
    };
   
    $('[data-adder]').on('click touch', addRow);
    /*End copy new line*/
     /*
     *Prevent accordion collapse trigger from adding hashtags at the address bar. 
     * This will prevent metisMenu (sidebar) from expanding
     */
    $('[data-toggle="collapse"]').on('click touch',function(e){
        e.preventDefault();
    });
    
    $('.list-group').on('click touch',function(){
        console.log('change');
        console.log( $(this) );
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
    
    /* Trigger tab creation*/
       $('.add-tab').on('click touch',function(){
           	var parent =  $(this),
           	prevNumber = $(this).closest('.parent-tabs').find('.nav-tabs li').size(),
           	nextTab = $(this).closest('.parent-tabs').find('.nav-tabs li').size()+1,
           	prevHTML = '';
           	//Check if content exists to prevent undefined error
           	if( $('.variant-'+prevNumber).length )
                prevHTML = tinymce.get('variant-'+prevNumber).getContent();
                
      	// create the tab
      	$('<li><a href="#variation'+nextTab+'" data-toggle="tab">Variation '+nextTab+'</a></li>').appendTo('#tabs');
      	 
      	// create the tab content
      	$('<div class="tab-pane" id="variation'+nextTab+'"><div data-id="'+nextTab+'" id="variant-'+nextTab+'" class="editable variant-'+nextTab+'" >'+prevHTML+'</div></div>').appendTo('.tab-content');
      	$('.editable').each(function(){
      	    var id=$(this).data('id');
      	     tinymce.init({
                selector: '.variant-'+id,
                skin_url: '/css/style'
            });
      	});
      	if( $('.nav-tabs li.active').length < 1 ){
      	    $('.nav-tabs li').first().addClass('active'); 
      	    $('.tab-content .tab-pane').first().addClass('active'); 
      	}
       });
    /* End trigger tab creation*/
    
    
    
});