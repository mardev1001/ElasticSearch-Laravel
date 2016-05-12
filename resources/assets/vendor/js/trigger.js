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
   
    $('.trigger-inputs').on('change',function(e){
        if ( $(this).is(':checked') ){
            $('[data-hide='+$(this).data('second')+']' ).removeClass('hide').attr('required');
        }
        else{
            $('[data-hide='+$(this).data('second')+']' ).addClass('hide').removeAttr('required');
        }
        
    });

    $('[data-adder]').on('click touch', addRow);
    /*End copy new line*/
    
});