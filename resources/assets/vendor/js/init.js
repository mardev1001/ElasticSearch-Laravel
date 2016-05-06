/**
 * Created by Marijan on 25.04.2016..
 */
$( function() {
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
        /*  $treeview[counter].on('click', function (e) {
              var expandibleNodes = $( $treeview[counter]+' li').find('span.node-icon').html();
          $treeview[counter].treeview('toggleNodeExpanded', 
		  [ expandibleNodes, { silent: true }]);
        }); */
        });
        
    }
    
    tinymce.init({
        selector: '.editable',
        skin_url: '/css/style'
    });
    
    //$(".switch").bootstrapSwitch();
});/*End function() wrapper*/