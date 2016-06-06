/**
 * Created by Marijan on 25.04.2016..
 */
$( function() {
    
    /*Blank space fix for textareas*/
    $('textarea').each(function(){
        $(this).html($(this).html().trim());
    });
    /*End Blank space fix for textareas*/
    
    $('#side-menu').metisMenu({
        doubleTapToGo: true,
    });
    
    $(".select").chosen({});
    
    $(".datetimepicker").datetimepicker({
        locale:'de',
        format:'DD.MM.YYYY'
    });
    
    if( $('.tree-view').length ){
        let counter = 0;
        let $treeview = [];
        $('.tree-view').each(function(){
          $treeview[counter] = $(this).treeview({
                data: $('.'+$(this).data('selector') ).html() ,
                color: "#428bca",
                showTags: true,
                enableLinks: true,
                levels: 0,
           }); 
        });
        
    }
    
    $('.data-table').DataTable({
        searching: false,
        paging: false,
        info: false,
        columnDefs: [
            {
                targets: 'no-sort', 
                orderable: false
            }
        ]
    });
    if( $('.editable').length ){
        var counter = 0;
        $('.editable').each(function(){
            counter++;
            if( $(this).data('id') )
                $(this).attr('id', $(this).data('id') );
            else
                 $(this).attr('id', 'editor-'+counter);
                
             tinymce.init({
                selector: '.editable',
                skin_url: '/css/style',
                
            });        
        });
    } 
    if( $('.variant').length ){
        var counter = 0;
        $('.variant').each(function(){
            counter++;
            if( $(this).data('id') )
                $(this).attr('id', $(this).data('id') );
            else
                 $(this).attr('id', 'variant-'+counter);
                
             tinymce.init({
                selector: '.variant',
                skin_url: '/css/style',
                height: 300,
                
            });        
        });
    } 
   
    
});/*End function() wrapper*/