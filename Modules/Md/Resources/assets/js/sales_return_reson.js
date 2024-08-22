var formData = new FormData();
$(document).ready(function(){
    
    $('#btnsalseRetorn').on('click', function () {

        $('#btnSavesalesReturn').show();
        $('#btnUpdatesalesReturn').hide();
        $('#id').val('');
        $("#txtsalesReturnNme").val('');
        
    });

    
    //...level 1 Update

   
    $('#btnSavesalesReturn').on('click',function(){
        addsalesRetornreson();
    })
    $('#btnUpdatesalesReturn').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updatesalesRetornreson();
    });



    $('#btnSavesalesReturn').show();
    $('#btnUpdatesalesReturn').hide();



    
    $("#btnCloseSalesreturn").on("click", function (e) {
        // Prevent the default form submission behavior
        e.preventDefault();
        var formData = $("form").serialize();
        $.ajax({
            type: "POST",
            url: '/md/close',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function (response) {
                $("#salesReturnResonModel").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });

});






const DatatableFixedColumnd = function () {


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

        var table = $('.datatable-fixed-both_salesReturnReson').DataTable({
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
            autoWidth: false,
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "sales_return_reson_id" },
                { "data": "sales_return_resons" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "status" },



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
    DatatableFixedColumnd.init();
});


//...Category load Data
function salesRetornresonAll() {

    $.ajax({
        type: "GET",
        url: '/md/gesalesRetornreson',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
           
                var dt = response.data;

                var data = [];

                for (var i = 0; i < dt.length; i++) {
                    var isChecked = dt[i].is_active ? "checked" : "";



                    data.push({
                        "sales_return_reson_id": dt[i].sales_return_reson_id ,
                        "sales_return_resons": dt[i].sales_return_resons,
                        "edit": '<button class="btn btn-primary salesRetornReson" data-bs-toggle="modal" data-bs-target="#salesReturnResonModel"  id="' + dt[i].sales_return_reson_id  + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                        "delete": '&#160<button class="btn btn-danger"  id="" value="Delete" onclick="salesReturnDelete(' + dt[i].sales_return_reson_id  + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                        "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single"id="cbsalesRetornReson" value="1" onclick="cbxSalesRetornStatus(' + dt[i].sales_return_reson_id  + ')" required ' + isChecked + '></label>'
                    });

                }


                var table = $('#salesReturnResonTable').DataTable();
                table.clear();
                table.rows.add(data).draw();
            
        },
        error: function (error) {
            console.log(error);

        },
        complete: function () { }
    });

}

salesRetornresonAll();

function dsalesretornTable() {
    var table = $('#salesReturnResonTable').DataTable();
    table.columns.adjust().draw();
}




  //add supplier payment method
  function addsalesRetornreson(){
    formData.append('txtsalesReturnNme', $('#txtsalesReturnNme').val());

    console.log(formData);
    

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/addsalesRetornreson',
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
            salesRetornresonAll();
            $('#salesReturnResonModel').modal('hide');
            if (response.status) {
            showSuccessMessage('Successfully saved');
           console.log(response);
            }else{
                showErrorMessage('Something went wrong');
                $('#salesReturnResonModel').modal('hide');
                }
    
            },
            error: function (error) {
            showErrorMessage('Something went wrong');
            $('#salesReturnResonModel').modal('hide');
                console.log(error);
    
            },
            complete: function () {
    
            }
    
        });
    
    
}




//.......edit......

$(document).on('click', '.salesRetornReson', function (e) {
    e.preventDefault();
    let sales_return_reson_id  = $(this).attr('id');
    $.ajax({
        url: '/md/salesRetornResonEdite/' + sales_return_reson_id ,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
           
    $('#btnSavesalesReturn').hide();
    $('#btnUpdatesalesReturn').show();
           
            
            $('#id').val(response.sales_return_reson_id );
            $("#txtsalesReturnNme").val(response.sales_return_resons);


        }
    });
});


//....lavel1 Update


function updatesalesRetornreson() {

    var id = $('#id').val();
    formData.append('txtsalesReturnNme', $('#txtsalesReturnNme').val());

    var cat1 = $('#txtsalesReturnNme').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{
    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/salesRetornResonUpdate/' + id,
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

            salesRetornresonAll();
            $('#salesReturnResonModel').modal('hide');
            showSuccessMessage('Successfully updated');


        }, error: function (error) {
            showErrorMessage('Something went wrong');
           
            console.log(error);
        }
    });
}
}


function salesReturnDelete(id) {

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
                deletesalesretornReson(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deletesalesretornReson(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deletesalesretornReson/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            
            if(response.success){
                salesRetornresonAll();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
            
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });

    
}



function cbxSalesRetornStatus(sales_return_reson_id ) {
    var status = $('#cbsalesRetornReson').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/cbxSalesRetornStatus/'+sales_return_reson_id ,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'status': status
        },
        success: function (response) {
            showSuccessMessage('saved')
         console.log("data save");
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}
