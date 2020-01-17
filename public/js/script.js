$(document).ready(function() {

    $("#dialog").dialog({ autoOpen: false,
       
        });
    $('#dialog').dialog('option', 'position', 'center');
    $("#opener").click(function() {
    $("#dialog" ).dialog("open");
    });



    $('#displayLists').click(function() {
       $('#addList').fadeIn(300);
    });
 
    $('#close').click(function() {
       $('#addList').fadeOut(300);
    });
    $('.my-flipster').flipster();

 });