const DatatableFixedColumns = function () {

    // Basic Datatable examples
    const _componentDatatableFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [2]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Press enter to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });


        // Left and right fixed columns
        var table = $('.datatable-fixed-both').DataTable({
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/md/sfallData',
               
            },
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 200,
                    targets: [2]
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "info":false,
            "order": [],
            "columns": [
                { "data": "employee_id" }, //id
                { "data": "employee_name" },//name
                { "data": "mobile_user_name" },//email
                { "data": "buttons" },



            ],
            "stripeClasses": ['odd-row', 'even-row']

        });

        table.column(0).visible(false);


    };

    // Return objects assigned to module

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});



var formData = new FormData();
$(document).ready(function () {

    $('#btnCustomApp').on('click', function () {
        $("#txtuserPassword").val('');
        $("#txtConfirmPassword").val('');
        $("#cmbusername").val('');

        $('#modalsfa').modal('show');
        $('#btnsave').show();
        $('#btnsave').text('Save');
        $('#employee').prop('disabled', false);
        $('input[type="email"]').prop('disabled', false);
        $('input[type="password"]').prop('disabled', false);
        employeename();
    });

   // sfallData();



    // Default initialization
    //$('.select').select2();
    $(".select2").select2({
        dropdownParent: $("#modalsfa")

    });
    
    // End of Default initialization
    ///////////////////////////close//////////


    // close

    $("#btnClose").on("click", function (e) {
        $('#modalsfa').modal('hide');
    });



    $('#btnsave').on('click', function (e) {
        if ($('#btnsave').text().trim() == 'Save') {
            saveSFAaccess();
        }
        else {
            updateSFAaccess();
        }


    });

});




function sfallData() {


    $.ajax({
        type: "GET",
        url: "/md/sfallData",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response;
            console.log(dt);
            var data = [];
            for (var i = 0; i < dt.length; i++) {



                data.push({

                    "id": dt[i].employee_id,
                    "name": dt[i].employee_name,
                    "email": dt[i].mobile_user_name,
                    "action": '<button class="btn btn-primary btn-sm" onclick="edit(' + dt[i].employee_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success btn-sm" onclick="view(' + dt[i].employee_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].employee_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                });
            }



            var table = $('#sfaTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

//customeerUserappAllData();



//.....customer app user Save.....
function saveSFAaccess() {
    /*
    var password2 = $('#txtuserPasswordtwo').val();
    var password = $('#txtuserPassword').val();
    
    if(password != password2){
    
    showWarningMessage("Confirm Password is incorrect")
    }else{
    alert("ok")
    }*/
    var emailLength = $('#cmbusername').val().length;
    var passwordLength = $('#txtuserPassword').val().length;
    if (passwordLength <= 7) {
        showErrorMessage("Please Enter a Password with more than 7 characters");
        return;
    }else if($('#txtuserPassword').val() != $('#txtConfirmPassword').val()){
        showWarningMessage('Please enter correct passwords');
        return;
    }
    if (emailLength > 0 && $('#cmbusername').hasClass('is-invalid')) {

        showErrorMessage("Please check the email address");
        return;
    } else {

        formData.append('employee', $('#employee').val());
        formData.append('cmbusername', $('#cmbusername').val());
        formData.append('txtuserPassword', $('#txtuserPassword').val());


        console.log(formData);

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/md/saveSfa',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                // Perform any tasks before sending the request
                $('#btnsave').prop('disabled',true);
            },
            success: function (response) {
                $('#btnsave').prop('disabled',false);
                var msg = response.message;
                if (msg == "duplicated") {
                    showWarningMessage('User Name can not be duplicated');

                    return;

                }
                showSuccessMessage('Successfully Save');
                $('#modalsfa').modal('hide');
                console.log(response);
                //sfallData()
                var table = $('#sfaTable').DataTable();
                table.ajax.reload();
            },
            error: function (error) {
                showErrorMessage('Something went wrong');
                console.log(error);
            }
        });
    }











}

//.......edit......

function edit(id) {

    $('#employee').empty();
    $('#modalsfa').modal('show');
    $('#btnsave').show();
    $('#btnsave').text('Update');
    $('#employee').prop('disabled', true);
    $('input[type="email"]').prop('disabled', false);
    $('input[type="password"]').prop('disabled', false);
    $.ajax({
        url: '/md/getsfaaccess/' + id,
        method: 'get',
        async: false,

        success: function (response) {

            $("#id").val(response[0].employee_id);

            // $("#employeee").val(response[0].employee_id).trigger('change');
            $.each(response, function (index, value) {

                $('#employee').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

            $("#cmbusername").val(response[0].mobile_user_name);
            //$("#txtuserPassword").val(response.mobile_app_password)
        }
    });


}
function view(id) {

    $('#modalsfa').modal('show');
    $('#btnsave').hide();
    $('input[type="email"]').prop('disabled', true);
    $('input[type="password"]').prop('disabled', true);
    $('#employee').prop('disabled', true);


    $.ajax({
        url: '/md/getsfaaccess/' + id,
        method: 'get',

        success: function (response) {
            $("#id").val(response[0].employee_id);

            // $("#employeee").val(response[0].employee_id).trigger('change');
            $.each(response, function (index, value) {

                $('#employee').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

            $("#cmbusername").val(response[0].mobile_user_name);
            //$("#txtuserPassword").val(response.mobile_app_password)
        }
    });

}




//....customer app user Update


function updateSFAaccess() {
    var password = $('#txtuserPassword').val();
    var usename = $('#cmbusername').val();
    if (password > 0) {
        if (password.length <= 7) {
            showErrorMessage("Please Enter a Password with mor than 7 characters");
            return;
        }else if($('#txtuserPassword').val() != $('#txtConfirmPassword').val()){
            showWarningMessage('Please enter correct passwords');
            return;
        }

    }
    // if (password != "" && usename != "") {
    var emailLength = $('#cmbusername').val().length;
    if (emailLength > 0 && $('#cmbusername').hasClass('is-invalid')) {

        showErrorMessage("Please check the email address");
        return;
    } else {



        var id = $('#id').val();
        formData.append('employee', $('#employee').val());
        formData.append('cmbusername', $('#cmbusername').val());
        formData.append('txtuserPassword', $('#txtuserPassword').val());

        console.log(formData);
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/md/updateSFAaccess/' + id,
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
                $('#btnsave').prop('disabled',true);
            },
            success: function (response) {
                $('#btnsave').prop('disabled',false);
                var msg = response.message
                if (msg == "code_duplicated") {
                    showWarningMessage('User Name can not be duplicated');
                    return;
                }
                $('#modalsfa').modal('hide');

                showSuccessMessage('Successfully updated');
                var table = $('#sfaTable').DataTable();
                table.ajax.reload();


            }, error: function (error) {
                showErrorMessage('Something went wrong');
                //$('#modalCustomerApp').modal('hide');
                console.log(error);
            }
        });
        /*} else {
            showWarningMessage("Enter user Name And Password");*/
    }

}







function _delete(id) {

    bootbox.confirm({
        title: 'Delete confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i class="fa fa-times fa-5x text-danger" ></i></div><div class="d-flex justify-content-center align-items-center "><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-Danger'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-info'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                deleteSFAaccess(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteSFAaccess(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteSFAaccess/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {


            showSuccessMessage('Successfully deleted');
            $('#modalsfa').modal('hide');
            sfallData()
            employeename()
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}



function employeename() {
    $('#employee').empty();
    $.ajax({
        url: '/md/getEmployee',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {

            $.each(response, function (index, value) {

                $('#employee').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })
}


