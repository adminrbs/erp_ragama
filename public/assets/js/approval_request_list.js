$(document).ready(function () {
    approvalRequestList();

    $('#cmbRequest').on('change', function () {
        if ($(this).val() == 0) {
            $('#num_time').val(0);
            $('#div_num_time').hide();
        } else {
            $('#num_time').val(1);
            $('#div_num_time').show();
        }
    });
});


function approvalRequestList() {

    $.ajax({
        type: "GET",
        url: '/approvalRequestList',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);

            for (var i = 0; i < response.length; i++) {
                var str_id = "'" + response[i].id + "'";
                var status = "Activated";
                if(response[i].approval == 0){
                    status = "Inactive";
                }
                var html = '<tr>';
                html += '<td>';
                html += response[i].name;
                html += '</td>';
                html += '<td>';
                html += response[i].email;
                html += '</td>';
                html += '<td>';
                html += response[i].browser;
                html += '</td>';
                html += '<td>';
                html += status;
                html += '</td>';
                html += '<td>';
                html += '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setRequestID(' + str_id + ')">Approval</button>';
                html += '</td>';
                html += '</tr>';
                $('#tbl_request').append(html);
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}



function setRequestID(id) {

    $('#hid_request_id').val(id);
}



function confirmRequest() {
    var request_id = $('#hid_request_id').val();

    var formData = new FormData();
    formData.append('request_id', request_id);
    formData.append('status', $('#cmbRequest').val());
    formData.append('time', $('#num_time').val());

    console.log(formData);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/confirmRequest',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            alert('Confirmed');
            location.href = "/approval_request_list";


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}