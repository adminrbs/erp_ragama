$(document).ready(function(){
    load_gl_account_type();
   
});

function load_gl_account_type(){
    $('#typeTable tbody').empty();
    $.ajax({
        url: '/md/glaccountType',
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response;
            console.log(data);
            
            $.each(data, function (key, value) {
                var row = '<tr>' +
                    '<td>' + value.gl_account_type_id + '</td>' +
                    '<td>' + value.gl_account_type + '</td>' +
                    '</tr>';


                $('#typeTable tbody').append(row);

            })



        }
    })
}