var formData = new FormData();
$(document).ready(function () {
    PaymentMethod()
    customerPaymentMethod()
   
    $('#btnSupmethord').on('click', function () {
        $('#btnSavePaymentMethod').show();
        $('#btnUpdatePaymentMethod').hide();
        $('#id').val('');
        $("#txtSupplierPaymentMethodNme").val('');
    });



    $("#btnClosesuppayment").on("click", function(e) {
        e.preventDefault();
        var formData = $("form").serialize();
        $.ajax({
          type: "POST",
          url: '/md/close',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          data: formData,
          success: function(response) {
            $("#paymentMethodModel").modal("hide"); // This will close the modal
            var urlWithoutQuery = window.location.href.split('?')[0];
        },
          error: function(xhr, status, error) {

          }
        });
      });

      $("#btnClosecusp").on("click", function(e) {
        
        e.preventDefault();
        var formData = $("form").serialize();
        $.ajax({
          type: "POST",
          url: '/md/close',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          data: formData,
          success: function(response) {
            $("#customerPaymentModel").modal("hide"); // This will close the modal
            var urlWithoutQuery = window.location.href.split('?')[0];
        },
          error: function(xhr, status, error) {

          }
        });
      });


      $('#btnSavePaymentMethod').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveSupplierPayment();
    });

    $('#btnUpdatePaymentMethod').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateSuplyPementMethord();
    });
    $('#btnSavePaymentMethod').show();
    $('#btnUpdatePaymentMethod').hide();

    // Customr Payment methord

      
    $('#btncustomerPayment').on('click', function () {
        $('#btnSaveCustomerPaymentMethod').show();
        $('#btnUpdateCustomerPaymentMethod').hide();
        $('#id').val('');
        $("#txtcustomerPaymentMethodNme").val('');
    });

    

      $('#btnSaveCustomerPaymentMethod').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveCustomerPayment();
    });

    $('#btnUpdateCustomerPaymentMethod').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateCustomerPementMethord();
    });
    $('#btnSaveCustomerPaymentMethod').show();
    $('#btnUpdateCustomerPaymentMethod').hide();

});



const DatatableFixedColumnsPM = function () {


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
        var table =  $('.datatable-fixed-both_supPaymentMethod').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width:200,
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
           // scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "supplier_payment_method_id"},
                { "data": "supply_payment_method" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "status" },



            ],"stripeClasses": [ 'odd-row', 'even-row' ],
        });table.column(0).visible(false);


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
    DatatableFixedColumnsPM.init();
});


//...Suply Group Data
function PaymentMethod() {
    

    $.ajax({
        type: "GET",
        url: "/md/getPaymentMethod",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

           
            var dt = response.data;

            console.log("dddd",dt);
            
            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";

               data.push({

                   "supplier_payment_method_id": dt[i].supplier_payment_method_id,
                   "supply_payment_method": dt[i].supplier_payment_method,
                   "edit":'<button class="btn btn-primary paymentMethord" data-bs-toggle="modal" data-bs-target="#paymentMethodModel" id="' + dt[i].supplier_payment_method_id  + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                   "delete":'&#160<button class="btn btn-danger" onclick="SuplyPementMethordDelete(' + dt[i].supplier_payment_method_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                   "status":'<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxMethordStatus" value="1" onclick="cbxPaymentMethordStatus('+ dt[i].supplier_payment_method_id + ')" required '+isChecked+'></lable>',
               });
            }


            var table = $('#supplierPaymentModeTable').DataTable();
                table.clear();
                table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })


}
PaymentMethod();




//.....supply perment  Save.....

function saveSupplierPayment(){

    formData.append('txtSupplierPaymentMethodNme', $('#txtSupplierPaymentMethodNme').val());

    console.log(formData);
    if (formData.txtSupplygroup == '') {
        //alert('Please enter item category level 1');
        return false;
    }

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveSupplierPayment',
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
            PaymentMethod();
            $('#paymentMethodModel').modal('hide');
            if (response.status) {
            showSuccessMessage('Successfully saved');
           console.log(response);
            }else{
                showErrorMessage('Something went wrong');
                $('#paymentMethodModel').modal('hide');
                }
    
            },
            error: function (error) {
            showErrorMessage('Something went wrong');
            $('#paymentMethodModel').modal('hide');
                console.log(error);
    
            },
            complete: function () {
    
            }
    
        });
    
    }
    
    //edit supply payment
    
    
    $(document).on('click', '.paymentMethord', function(e) {
        e.preventDefault();
        let supplier_payment_method_id  = $(this).attr('id');
        $.ajax({
            url: '/md/suplypaymentMethordEdite/'+supplier_payment_method_id,
            method: 'get',
            data: {
                //id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#btnSavePaymentMethod').hide();
                 $('#btnUpdatePaymentMethod').show();
    
    
    
                $('#id').val(response.supplier_payment_method_id );
                $("#txtSupplierPaymentMethodNme").val(response.supplier_payment_method);
    
    
            }
        });
    });
    
    // suply Group Update
    
    function updateSuplyPementMethord(){
    
        var id = $('#id').val();
        formData.append('txtSupplierPaymentMethodNme', $('#txtSupplierPaymentMethodNme').val());
        var cat1 = $('#txtSupplierPaymentMethodNme').val();
        if(cat1 == ""){
            showErrorMessage('Something went wrong');
        }else{
    
        console.log(formData);
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/md/updateSuplyPementMethord/'+id,
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
    
                PaymentMethod();
                $('#paymentMethodModel').modal('hide');
    
                showSuccessMessage('Successfully updated')
    
            }, error: function (error) {
                console.log(error);
                showErrorMessage('Something went wrong')
                $('#paymentMethodModel').modal('hide');
            }
        });
    }
    }
    
    function SuplyPementMethordDelete(id) {
    
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
               if(result){
                deletepayementMode(id);
               }else{
    
               }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
    
        }
    
        function deletepayementMode(id) {
    
            $.ajax({
                type: 'DELETE',
                url: '/md/deletepayementMode/' + id,
                data: {
                    _token: $('input[name=_token]').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
    
                },success:function(response){

                    if(response.success){
                        PaymentMethod();
                   
                       showSuccessMessage('Successfully deleted');
                   }else{
                       showWarningMessage('Uneble to Delete')
                   }

                   
                },error:function(xhr,status,error){
                    console.log(xhr.responseText);
                }
            });
        }
    
    
    
    // Status Save
    
    
    function cbxPaymentMethordStatus(supplier_payment_method_id) {
        var status = $('#cbxMethordStatus').is(':checked') ? 1 : 0;
    
    
        $.ajax({
            url: '/md/cbxPaymentMethordStatus/'+supplier_payment_method_id,
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
    
    function paymentTable() {
        var table = $('#supplierPaymentModeTable').DataTable();
        table.columns.adjust().draw();
    }
    
    /////////////////////////////////////////////////////////////////////////



const DatatableFixedColumnsCP = function () {


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
        var table =  $('#customerPaymentTable').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width:200,
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
           // scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "customer_payment_method_id"},
                { "data": "customer_payment_method" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "status" },



            ],"stripeClasses": [ 'odd-row', 'even-row' ],
        });table.column(0).visible(false);


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
    DatatableFixedColumnsCP.init();
});


//...Suply Group Data
function customerPaymentMethod() {
    

    $.ajax({
        type: "GET",
        url: "/md/getCustomerPaymentMethod",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

           
            var dt = response.data;

            console.log("sss",dt);
            
            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";

               data.push({

                   "customer_payment_method_id": dt[i].customer_payment_method_id,
                   "customer_payment_method": dt[i].customer_payment_method,
                   "edit":'<button class="btn btn-primary customerpaymentMethord" data-bs-toggle="modal" data-bs-target="#customerPaymentModel" id="' + dt[i].customer_payment_method_id   + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                   "delete":'&#160<button class="btn btn-danger" onclick="customerMethordDelete(' + dt[i].customer_payment_method_id  + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                   "status":'<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxCustomerMethordStatus" value="1" onclick="cbxCustomerPaymentMethordStatus('+ dt[i].customer_payment_method_id  + ')" required '+isChecked+'></lable>',
               });
            }


            var table = $('#customerPaymentTable').DataTable();
                table.clear();
                table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })


}
customerPaymentMethod();




//.....supply perment  Save.....

function saveCustomerPayment(){

    formData.append('txtcustomerPaymentMethodNme', $('#txtcustomerPaymentMethodNme').val());

    console.log(formData);
    

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveCustomerPayment',
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
            customerPaymentMethod();
            $('#customerPaymentModel').modal('hide');
            if (response.status) {
            showSuccessMessage('Successfully saved');
           console.log(response);
            }else{
                showErrorMessage('Something went wrong');
                $('#customerPaymentModel').modal('hide');
                }
    
            },
            error: function (error) {
            showErrorMessage('Something went wrong');
            $('#customerPaymentModel').modal('hide');
                console.log(error);
    
            },
            complete: function () {
    
            }
    
        });
    
    }
    
    //edit supply payment
    
    
    $(document).on('click', '.customerpaymentMethord', function(e) {
        e.preventDefault();
        let customer_payment_method_id   = $(this).attr('id');
        $.ajax({
            url: '/md/customerpaymentMethordEdite/'+customer_payment_method_id ,
            method: 'get',
            data: {
                //id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#btnSaveCustomerPaymentMethod').hide();
                 $('#btnUpdateCustomerPaymentMethod').show();
    
    
    
                $('#id').val(response.customer_payment_method_id  );
                $("#txtcustomerPaymentMethodNme").val(response.customer_payment_method);
    
    
            }
        });
    });
    
    // suply Group Update
    
    function updateCustomerPementMethord(){
    
        var id = $('#id').val();
        formData.append('txtcustomerPaymentMethodNme', $('#txtcustomerPaymentMethodNme').val());

        var cat1 = $('#txtcustomerPaymentMethodNme').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{
    
        console.log(formData);
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/md/updateCustomerPementMethord/'+id,
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
    
                customerPaymentMethod();
                $('#customerPaymentModel').modal('hide');
    
                showSuccessMessage('Successfully updated')
    
            }, error: function (error) {
                console.log(error);
                showErrorMessage('Something went wrong')
                //$('#customerPaymentModel').modal('hide');
            }
        });
    }
    }
    
    function customerMethordDelete(id) {
    
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
               if(result){
                deletecustomerpayementMode(id);
               }else{
    
               }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
    
        }
    
        function deletecustomerpayementMode(id) {
    
            $.ajax({
                type: 'DELETE',
                url: '/md/deletecustomerpayementMode/' + id,
                data: {
                    _token: $('input[name=_token]').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
    
                },success:function(response){

                    if(response.success){
                        customerPaymentMethod();
                        $('#txtcustomerPaymentMethodNme').val('');
                       showSuccessMessage('Successfully deleted');
                   }else{
                       showWarningMessage('Uneble to Delete')
                   }
                          
                       
                },error:function(xhr,status,error){
                    console.log(xhr.responseText);
                }
            });
        }
    
    
    
    // Status Save
    
    
    function cbxCustomerPaymentMethordStatus(supplier_payment_method_id) {
        var status = $('#cbxCustomerMethordStatus').is(':checked') ? 1 : 0;
    
    
        $.ajax({
            url: '/md/cbxCustomerPaymentMethordStatus/'+supplier_payment_method_id,
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
    
    function customerpayment() {
        var table = $('#customerPaymentTable').DataTable();
        table.columns.adjust().draw();
    }
    
    /////////////////////////////////////////////////////////////////////////