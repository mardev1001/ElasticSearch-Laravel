/**
 * Created by Marijan on 25.04.2016..
 */
$( function() {
    
    /*Blank space fix for textareas*/
    $('textarea').each(function(){
        $(this).html($(this).html().trim());
    });
    /*End Blank space fix for textareas*/
    
    $('#side-menu').metisMenu();
    
    $(".select").chosen({});
    
    $(".datetimepicker").datetimepicker({
        locale:'de',
        format:'DD.MM.YYYY.'
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
    
    tinymce.init({
        selector: '.editable',
        skin_url: '/css/style'
    });
    
});/*End function() wrapper*/