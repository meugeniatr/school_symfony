window.onload = function () {
    $("#tags").change(function(){
        $("#tags").after('<span class="tag">'+$("#tags option:selected").text()+' <a href="javascript:;" id="'+$("#tags option:selected").val()+'"><i class="fa fa-trash-o"></i></a></span> <input class="updateTags" type="hidden" name="tags[]" value="'+$("#tags option:selected").val()+'" id="'+$("#tags option:selected").val()+'">');
        $("#selectedTags").val($("#selectedTags").val()+' '+$("#tags option:selected").val());
        $("#tags option:selected").remove();
        $("#tags").val('1');
    });



    $('body').on('click', ".tag a", function () {
        $(this).parent().next().remove();

        $(this).parent().remove();
        $("#tags").append('<option value="'+$(this).attr("id")+'">'+$(this).parent().text()+'</option>')
        });
    
    
    $('#date').daterangepicker({
        "showDropdowns": true,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "alwaysShowCalendars": true
    }, function(start, end, label) {
      console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });
}