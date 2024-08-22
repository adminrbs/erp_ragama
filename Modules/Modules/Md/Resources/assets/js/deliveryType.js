var formData = new FormData();
$(document).ready(function(){
    
    $('#btndeliveryType').on('click', function () {

        $('#btnSaveDeliveryTypeNew').show();
        $('#btnUpdateDeliveryType').hide();
        $('#id').val('');
        $("#txtDeliveryType").val('');
        
    });

    
    //...level 1 Update

   
    $('#btnSaveDeliveryTypeNew').on('click',function(){
        addDeliveryTypes();
    })
    $('#btnUpdateDeliveryType').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateDeliverytype();
    });



    $('#btnSaveDeliveryTypeNew').show();
    $('#btnUpdateDeliveryType').hide();



    
    $("#btnCloseDeliveType").on("click", function (e) {
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
                $("#deliveryTypeModel").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });

});






const DatatableFixedColumndtt = function () {


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

        var table = $('.datatable-fixed-both_delivery').DataTable({
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
                { "data": "delivery_type_id" },
                { "data": "delivery_type_name" },
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
    DatatableFixedColumndtt.init();
});


//...Category load Data
function deliveryTypeAll() {

    $.ajax({
        type: "GET",
        url: '/md/getdeliveryType',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            if (true) {
                var dt = response.data;

                var data = [];

                for (var i = 0; i < dt.length; i++) {
                    var isChecked = dt[i].is_active ? "checked" : "";



                    data.push({
                        "delivery_type_id": dt[i].delivery_type_id,
                        "delivery_type_name": dt[i].delivery_type_name,
                        "edit": '<button class="btn btn-primary deliveryType" data-bs-toggle="modal" data-bs-target="#deliveryTypeModel"  id="' + dt[i].delivery_type_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                        "delete": '&#160<button class="btn btn-danger"  id="btnCategorylevel1" value="Delete" onclick="deliveryTyprDelete(' + dt[i].delivery_type_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                        "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single"id="cbxDeliveryType" value="1" onclick="cbxDeliveryTypeStatus(' + dt[i].delivery_type_id + ')" required ' + isChecked + '></label>'
                    });

                }


                var table = $('#tableDeliveryType').DataTable();
                table.clear();
                table.rows.add(data).draw();
            } else if (response.hasOwnProperty('error')) {
                console.log(response.error);
            } else {
                console.log('Invalid response format');
            }
        },
        error: function (error) {
            console.log(error);

        },
        complete: function () { }
    });

}

deliveryTypeAll();

function deliveryTypeTable() {
    var table = $('#tableDeliveryType').DataTable();
    table.columns.adjust().draw();
}




  //add supplier payment method
  function addDeliveryTypes(){
    var data = $('#deliveryTypeModal').serialize();
    console.log(data);
    $.ajax({
        url:'/md/addDeliveryType',
        type:'post',
        data:data,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            console.log(data);
        },
        success: function (response) {
            
            if (response.status) {
                deliveryTypeAll();
            $('#deliveryTypeModel').modal('hide');
            showSuccessMessage('Successfully save');
            }else{
                showErrorMessage('Something went wrong');
                $('#deliveryTypeModel').modal('hide');
            }

        },
        error: function (error) {

            showErrorMessage('Something went wrong');
            $('#deliveryTypeModel').modal('hide');
            console.log(error);

        },
        complete: function () {

        }

    })

    
}




//.......edit......

$(document).on('click', '.deliveryType', function (e) {
    e.preventDefault();
    let delivery_type_id = $(this).attr('id');
    $.ajax({
        url: '/md/deliveryTypeEdite/' + delivery_type_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
           
    $('#btnSaveDeliveryTypeNew').hide();
    $('#btnUpdateDeliveryType').show();
           
            
            $('#id').val(response.delivery_type_id);
            $("#txtDeliveryType").val(response.delivery_type_name);


        }
    });
});


//....lavel1 Update


function updateDeliverytype() {

    var id = $('#id').val();
    formData.append('txtDeliveryType', $('#txtDeliveryType').val());

    var cat1 = $('#txtDeliveryType').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/deliveryTypeUpdate/' + id,
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

            deliveryTypeAll();
            $('#deliveryTypeModel').modal('hide');
            showSuccessMessage('Successfully updated');


        }, error: function (error) {
            showErrorMessage('Something went wrong');
           
            console.log(error);
        }
    });
}
}


function deliveryTyprDelete(id) {

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
                deleteDeliveryType(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteDeliveryType(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteDeliveryType/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                deliveryTypeAll();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });

    
}



function cbxDeliveryTypeStatus(delivery_type_id) {
    var status = $('#cbxDeliveryType').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/deliveryypeStatus/'+delivery_type_id,
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
