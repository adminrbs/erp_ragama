function disableComponents(){
    //disabling select tags
    $("select").each(function() {
        $(this).prop('disabled', true);
        
    });


    //disabling dates
    $("input[type='date']").each(function() {
        $(this).prop('disabled', true);
        
    });

    //disable textboxes
    $("input[type='text']").each(function() {
        $(this).prop('disabled', true);
        
    });


    //disable numbers
    $("input[type='number']").each(function() {
        $(this).prop('disabled', true);
        
    });

}