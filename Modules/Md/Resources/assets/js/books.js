


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
        var table = $('#books_table').DataTable({
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
                        width: 500,
                        targets: 1
                    },
                    {
                        width: 300,
                        targets: 2
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
                "pageLength": 10,
                "order": [],
            "columns": [
                { "data": "id" },
                { "data": "book_number" },
                { "data": "book_name" },
                { "data": "book_type" },
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
    
    get_book_list();
    $('#btnglaccount').on('click', function () {
        $('input[type="text"]').val('');
        $('#btnsave').text('Save');
        $('#btnsave').show();
        $('#book_status_div').hide();

    });
    
    $('#btnsave').on('click', function () {

        if ($('#btnsave').text().trim() == 'Save') {
            save_book();
        }
        else {
            updateBook();
        }

    });
    $('#btnCloseupdate').on('click', function () {
        $('#modalNonproprietary').modal('hide');

    });

   



   
});


function save_book() {

    formData.append('txtBookNumber', $('#txtBookNumber').val());
    formData.append('txtBookName', $('#txtBookName').val());
    formData.append('bookType_id', $('#cmbBooksType').val());


        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/md/save_book',
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
                //suplyGroupAllData();
                var status = response.status;
                if(status){
                    showSuccessMessage("Successfully Sved");
                }else{
                    showWarningMessage("Unable to save");
                }
                $('#modalNonproprietary').modal('hide');
                get_book_list();
               


            },
            error: function (error) {
                //showErrorMessage('Something went wrong');
                showErrorMessage("Something went wrong");

            },
            complete: function () {

            }

        });
    

}

function get_book_list() {
    $.ajax({
        type: 'GET',
        url: '/md/get_book_list',
        success: function (response) {

            var dt = response;
          
            var data = [];

                var dt = response.data;

                for (var i = 0; i < dt.length; i++) {
                    data.push({
                        "id": dt[i].book_id,
                        "book_number": dt[i].book_number,
                        "book_name": dt[i].book_name,
                        "book_type": dt[i].book_type_name,
                       "action": '<button title="Edit" class="btn btn-primary  btn-sm lonmodel" data-bs-toggle="modal" data-bs-target="#modalNonproprietary" onclick="edit(' + dt[i].book_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success btn-sm loneview" data-bs-toggle="modal" data-bs-target="#modalNonproprietary"  onclick="getData_view(' + dt[i].book_id + ')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].book_id + ')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                     
                    });

                   
                }

            
            console.log(data);
            var table = $('#books_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (data) {
            console.log(data);
        }, complete: function () {

        }
    });
}

//get each book to edit
function edit(id) {
    $('#btnsave').text('Update');
   
        $('#btnsave').show();
    $('#book_status_div').show();
    $("#txtBookNumber").prop('disabled',false);
    $('#txtBookName').prop('disabled',false);
    $('#chkStatus').prop('disabled', false);
    $('#cmbBooksType').prop('disabled',false);
    $.ajax({
        url: '/md/getBook_data/' + id,
        method: 'get',
       
        success: function (data) {
            console.log(response);
            var response = data.data

            $('#id').val(response.book_id );
            $("#txtBookNumber").val(response.book_number);
            $('#txtBookName').val(response.book_name);
            $('#chkStatus').prop('checked', response.is_active == 1);
            $('#cmbBooksType').val(response.book_type_id);
        }
    });
}

function updateBook() {
    var id = $('#id').val();
    var chkStatus = $('#chkStatus').is(":checked") ? 1 : 0;

    formData.append('txtBookNumber', $('#txtBookNumber').val());
    formData.append('txtBookName', $('#txtBookName').val());
    formData.append('status', chkStatus);
    formData.append('bookType_id', $('#cmbBooksType').val());

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/md/updateBook/' + id,
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
                $('#modalNonproprietary').modal('hide');
                get_book_list();
                showSuccessMessage("Successfully Update");

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


function getData_view(id) {
    $('#btnsave').hide();
    $('input[type="text"]').prop('disabled', true);
    $('select').prop('disabled', true);
    $('#book_status_div').show();
    $('#chkStatus').prop('disabled',true);
    $('#cmbBooksType').prop('disabled',true);
    $.ajax({
        url: '/md/getBook_data/' + id,
        method: 'get',
        async:false,

        success: function (data) {
            console.log(response);
            var response = data.data;

       
            $('#id').val(response.book_id );
            $("#txtBookNumber").val(response.book_number);
            $('#txtBookName').val(response.book_name);
            $('#chkStatus').prop('checked', response.is_active == 1);
            $('#cmbBooksType').val(response.book_type_id);



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
                deleteBook(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteBook(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteBook/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            get_book_list();
             
                showSuccessMessage("Successfully Delete");
          


        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

