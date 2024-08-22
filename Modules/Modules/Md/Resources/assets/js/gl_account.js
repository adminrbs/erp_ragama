


const DatatableFixedColumns = function () {


    //
    // Setup module components
    //

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
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });



        // Left and right fixed columns
        var table = $('#glAccountTable').DataTable({
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
                        width: '25%',
                        targets: 1
                    },
                    {
                        width: 300,
                        targets: [2]
                    },
                    {
                        width: '100%',
                        targets: 3
                    },


                ],
                scrollX: true,
                //scrollY: 350,
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 1
                },
                autoWidth: false,
                "pageLength": 100,
                "order": [],
                "columns": [
                { "data": "id" },
                { "data": "account_code" },
                { "data": "accounttitel" },
                { "data": "accounttype" },
                { "data": "action" },

            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false);



        //
        // Fixed column with complex headers
        //

    };


    //
    // Return objects assigned to module
    //

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


var formData = new FormData();
$(document).ready(function () {
    glaccountType();
    allglaccountdata();
    $('#btnglaccount').on('click', function () {
        $('input[type="text"]').val('');


    });
    
    $('#btnsave').on('click', function () {

        if ($('#btnsave').text().trim() == 'Save') {
            save_glaccount();
        }
        else {
            updateglAccount();
        }

    });

    $('#btnCloseupdate').on('click', function () {
        $('#modalNonproprietary').modal('hide');

    });
});
function glaccountType() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/glaccountType",

        success: function (response) {
var data= response

            $.each(data, function (index, value) {

                $('#cmdAccountType').append('<option value="' + value.gl_account_type_id + '">' + value.gl_account_type + '</option>');

            })

        },

    });
}

function save_glaccount() {

    formData.append('txtAccountCode', $('#txtAccountCode').val());
    formData.append('txtAccountTitle', $('#txtAccountTitle').val());
    formData.append('cmdAccountType', $('#cmdAccountType').val());

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/md/save_glaccount',
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
                console.log(response)
                $('#btnsave').prop('disabled',false);
                //suplyGroupAllData();
                var msg = response.message;
                if(msg == "duplicated"){
                    showWarningMessage('Account code can not be duplicated');
                    $('#txtAccountCode').addClass('is-invalid');
                    return;
                }else{
                    $('#modalNonproprietary').modal('hide');
                    allglaccountdata()
                    showSuccessMessage("Successfully Sved");

                }

             


            },
            error: function (error) {
                //showErrorMessage('Something went wrong');
                showErrorMessage("Something went wrong");

            },
            complete: function () {

            }

        });
    

}

function allglaccountdata() {
    $.ajax({
        type: 'GET',
        url: '/md/allglaccountdata',
        success: function (response) {

            var dt = response;
            console.log(dt);
            var data = [];
            for (i = 0; i < response.length; i++) {

                var dt = response;



                var data = [];
                for (var i = 0; i < dt.length; i++) {


                    data.push({
                        "id": dt[i].account_id,
                        "account_code": dt[i].account_code,
                        "accounttitel": dt[i].account_title,
                        "accounttype":dt[i].gl_account_type,
                       "action": '<button title="Edit" class="btn btn-primary  btn-sm lonmodel" data-bs-toggle="modal" data-bs-target="#modalNonproprietary" onclick="edit(' + dt[i].account_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success btn-sm loneview" data-bs-toggle="modal" data-bs-target="#modalNonproprietary"  onclick="getcontributeview(' + dt[i].account_id + ')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].account_id + ')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                     
                    });
                }

            }
            var table = $('#glAccountTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (data) {
            console.log(data);
        }, complete: function () {

        }
    });
}
function edit(id) {
    $('#btnsave').text('Update');
    $.ajax({
        url: '/md/getglaccount/' + id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {
            console.log(response);


            $('#id').val(response.account_id );
            $("#txtAccountCode").val(response.account_code);
            $('#txtAccountTitle').val(response.account_title);
            $('#cmdAccountType').val(response.account_type_id );
          
        }
    });
}

function updateglAccount() {
    var id = $('#id').val();
   
    formData.append('txtAccountCode', $('#txtAccountCode').val());
    formData.append('txtAccountTitle', $('#txtAccountTitle').val());
    formData.append('cmdAccountType', $('#cmdAccountType').val());

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/md/updateglAccount/' + id,
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
                var msg = response.message;
                if(msg == "duplicated"){
                    showWarningMessage('Account code can not be duplicated');
                    $('#txtAccountCode').addClass('is-invalid');
                    return;
                }else{
                    $('#modalNonproprietary').modal('hide');
                    allglaccountdata()
                    showSuccessMessage("Successfully Updated");

                }
               

            },
            error: function (error) {
                showErrorMessage('Something went wrong');
                //$('#modalNonproprietary').modal('hide');
                console.log(error);

            },
            complete: function () {

            }

        });
    
}


function getcontributeview(id) {
    $('#btnsave').hide();
    $('input[type="text"]').prop('disabled', true);
    $('select').prop('disabled', true);
    $.ajax({
        url: '/md/getglaccount/' + id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {
            console.log(response);


            $('#id').val(response.account_id );
            $("#txtAccountCode").val(response.account_code);
            $('#txtAccountTitle').val(response.account_title);
            $('#cmdAccountType').val(response.account_type_id );



        }
    });
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
                deleteglaccount(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteglaccount(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/glAccounDelete/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

                allglaccountdata()
             
                showSuccessMessage("Successfully Delete");
          


        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

