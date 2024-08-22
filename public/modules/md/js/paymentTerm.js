var formData = new FormData();
$(document).ready(function () {
  
    $('#btnSavePaymentTerm').show();
    $('#btnUpdatePaymentTerm').hide();


    $('#btnPaymentTerm').on('click', function () {
        $('#btnSavePaymentTerm').show();
        $('#btnUpdatePaymentTerm').hide();
        $('#id').val('');
        $("#txtPaymentTermName").val('');
    });

    $("#btnClosepayment").on("click", function(e) {
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
            $("#paymentTermModel").modal("hide"); // This will close the modal
            var urlWithoutQuery = window.location.href.split('?')[0];
        },
          error: function(xhr, status, error) {

          }
        });
      });


      $('#btnSavePaymentTerm').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        savePaymentTerm();
    });

    $('#btnUpdatePaymentTerm').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updatePementTerm();
    });
    $('#btnSavePaymentTerm').show();
    $('#btnUpdatePaymentTerm').hide();

});



const DatatableFixedColumnsPT = function () {


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
        var table =  $('.datatable-fixed-both_term').DataTable({
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
                { "data": "payment_term_id"},
                { "data": "supply_payment_method"},
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
    DatatableFixedColumnsPT.init();
});


//...Suply Group Data
function PaymentTerm() {
   

    $.ajax({
        type: "GET",
        url: "/md/getPaymentTerm",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

           
            var dt = response.data;

           
            
            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";

               data.push({

                   "payment_term_id": dt[i].payment_term_id,
                   "supply_payment_method": dt[i].payment_term_name,
                   "edit":'<button class="btn btn-primary paymentTerm" data-bs-toggle="modal" data-bs-target="#paymentTermModel" id="' + dt[i].payment_term_id  + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                   "delete":'&#160<button class="btn btn-danger" onclick="SuplyPementTermDelete(' + dt[i].payment_term_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                   "status":'<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxTermStatus" value="1" onclick="cbxPaymentTermStatus('+ dt[i].payment_term_id + ')" required '+isChecked+'></lable>',
               });
            }


            var table = $('#PaymentTerm').DataTable();
                table.clear();
                table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })


}
PaymentTerm();




//.....supply perment  Save.....

function savePaymentTerm(){
    
    formData.append('txtPaymentTerm', $('#txtPaymentTermName').val());

   
    if (formData.txtSupplygroup == '') {
        //alert('Please enter item category level 1');
        return false;
    }

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/savePaymentTerm',
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
            PaymentTerm();
            $('#paymentTermModel').modal('hide');
            if (response.status) {
            showSuccessMessage('Successfully saved');
           console.log(response);
            }else{
                showErrorMessage('Something went23 wrong');
                $('#paymentTermModel').modal('hide');
                }
    
            },
            error: function (error) {
            showErrorMessage('Something went 78wrong');
            $('#paymentTermModel').modal('hide');
                console.log(error);
    
            },
            complete: function () {
    
            }
    
        });
    
    }
    
    //edit supply payment
    
    
    $(document).on('click', '.paymentTerm', function(e) {
        e.preventDefault();
        let payment_term_id  = $(this).attr('id');
        $.ajax({
            url: '/md/suplyPaymentTermEdite/'+payment_term_id,
            method: 'get',
            data: {
                //id: id,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#btnSavePaymentTerm').hide();
                 $('#btnUpdatePaymentTerm').show();

                 //console.log("response,",response.payment_term_name);
    
    
    
                $('#id').val(response.payment_term_id);
                $("#txtPaymentTermName").val(response.payment_term_name);
    
    
            }
        });
    });
    
    // suply Group Update
    
    function updatePementTerm(){
    
        var id = $('#id').val();
        formData.append('txtPaymentTermName', $('#txtPaymentTermName').val());

        var cat1 = $('#txtPaymentTermName').val();
        if(cat1 == ""){
            showErrorMessage('Something went wrong');
        }else{
    
        console.log(formData);
        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            url: '/md/updatepaymentTerm/'+id,
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
    
                PaymentTerm();
                $('#paymentTermModel').modal('hide');
    
                showSuccessMessage('Successfully updated')
    
            }, error: function (error) {
                console.log(error);
                showErrorMessage('Something went wrong')
                $('#paymentTermModel').modal('hide');
            }
        });
    }
    }
    
    function SuplyPementTermDelete(id) {
    
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
                deletepayementTerm(id);
               }else{
    
               }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
    
        }
    
        function deletepayementTerm(id) {
    
            $.ajax({
                type: 'DELETE',
                url: '/md/deletepayementterm/' + id,
                data: {
                    _token: $('input[name=_token]').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
    
                },success:function(response){

                    if(response.success){
                        PaymentTerm();
                    $('#txtPaymentTerm').val('');
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
    
    
    function cbxPaymentTermStatus(payment_term_id) {
        var status = $('#cbxTermStatus').is(':checked') ? 1 : 0;
    
    
        $.ajax({
            url: '/md/cbxPaymentTermStatus/'+payment_term_id,
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
    
    function paymentTermTable() {
        var table = $('#PaymentTerm').DataTable();
        table.columns.adjust().draw();
    }
    
    /////////////////////////////////////////////////////////////////////////