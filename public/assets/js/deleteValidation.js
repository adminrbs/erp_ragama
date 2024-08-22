

function validateDeleteItem(table,columnName,id){
   
    var exist = false;
    $.ajax({
        type: "GET",
        url: '../value_exist/' + table + '/' + columnName + '/' + id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if(response.message == 'used'){
                exist = true
            }
           
           console.log(exist);
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }

    });
    
    return exist;

}