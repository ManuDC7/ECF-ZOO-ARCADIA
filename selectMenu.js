$(document).ready(function(){
    $('#animal-select').change(function(){
        var animalId = $(this).val();
        $.get('animal_report.php', {id: animalId}, function(data){
            $('#date-select').html(data);
        });
    });
    $('#date-select').change(function(){
        var reportDate = $(this).val();
        var parts = reportDate.split("-");
        var timestamp = new Date(parts[2], parts[1] - 1, parts[0]).getTime() / 1000;
        $.get('date_report.php', {date: timestamp}, function(data){
            $('#report-field').val(data);
        });
    });
});