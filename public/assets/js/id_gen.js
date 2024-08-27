
var referanceID = undefined;


function newID(urlx, table, doc_number) {
    var formData = new FormData();
   var branch_id = $('#cmbBranch').val();
   
  
   console.log(formData);
    var new_id = undefined;
    $.ajax({
        type: "GET",
        url: urlx + "/" + table + "/" + doc_number,
        async: false,
        data:{
            id:branch_id
        },
       // processData: false,
        //contentType: false,
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var id = generateID(response.id);
           
            var prefix = response.prefix;
            if (action == 'edit') {
                id = generateID(parseInt(referanceID.replace(/\D/g, '')));
            }
            new_id = prefix + id;
            console.log(new_id);
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }

    });
console.log(new_id);
    return new_id;
}


/**
* generateID
* This function is used to generate id
* @param id This is the paramter to require id
*/
function generateID(id) {

    let pattern = {
        1: "0000",
        2: "000",
        3: "00",
        4: "0",
    };
    var length = Math.ceil(Math.log(id + 1) / Math.LN10);
    return pattern[length] + id;
}