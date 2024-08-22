

/* ----------data table---------------- */
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
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
            
        });

        // Left and right fixed columns
        $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            columnDefs: [
                {
                    orderable: false,
                    targets: 11
                },
                {
                    orderable: false,
                    targets: 12
                },
                {
                    orderable: false,
                    targets: 13
                },
                {
                    orderable: false,
                    targets: 14
                },
                {
                    orderable: false,
                    targets: 15
                },
                {
                    width:150,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 350,
                    targets: [2]
                },
         
            ],
            scrollX: true,
            scrollY: 600,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1,
                rightColumns: 2,
                rightColumns: 3,
                rightColumns: 4,
                rightColumns: 5,
                rightColumns: 6,
                rightColumns: 7,
                rightColumns: 8,
            },
            "pageLength": 100,
            "paging": false,
            "order": [],
            "columns": [
                { "data": "invoice_date" },
                { "data": "external_number" },
                { "data": "customer_name" },
                { "data": "invoice_amount" },
                { "data": "sales_order_number" },          
                { "data": "loading_number" },              
                { "data": "invoice_add_user" },
                { "data": "sales_order_added_user" },
                { "data": "loading_added_user" },              
                { "data": "employee_name" },
                { "data": "route" },   
                { "data": "delivered" },
                { "data": "seal" },
                { "data": "signature" },
                { "data": "cash" },
                { "data": "cheque" },
                { "data": "no_seal" },
                { "data": "cancel" },
                { "data": "picking" }
            ],
            "stripeClasses": [ 'odd-row', 'even-row' ],


           
        });  
       

    };

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});


/* --------------end of data table--------- */



$(document).ready(function(){
    $('.select2').select2(); //initilazing select 2
    $('#cmbdelevery_plan').change()

    $('#cmbdelevery_plan').on('change',function(){
        var id = $(this).val();
      //  alert(id);
        getDeliveryConfirmationData(id);
    });

    $('#save_confirmation').on('click',function(){
        

        bootbox.confirm({
            title: 'Save confirmation',
            message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>&nbsp;Yes',
                    className: 'btn-warning'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i>&nbsp;No',
                    className: 'btn-link'
                }
            },
            callback: function (result) {
                //console.log('Confirmation result:', result);
                if (result) {
                    confirm_all();
                } else {

                }
            },
            onShow: function () {
                $('#question-icon').addClass('swipe-question');
            },
            onHide: function () {
                $('#question-icon').removeClass('swipe-question');
            }
        });

        $('.bootbox').find('.modal-header').addClass('bg-warning text-white');
    });
   
    loadDeliveryPlans();
});



//gettting data to the list (data table)
function getDeliveryConfirmationData(id){
    $.ajax({
        url:'/sd/getDeliveryConfirmationData/'+id,
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var delivered_checked = '<input class="form-check-input" type="checkbox" id="delivered' +"_"+dt[i].sales_invoice_Id + '" onchange="addDeliveryConfirmation(this)">';
                var signature_checked = '<input class="form-check-input" type="checkbox" id="signature'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)">';
                var seal_checked = '<input class="form-check-input" type="checkbox" id="seal'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)">';
                var cash_checked = '<input class="form-check-input" type="checkbox" id="cash'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" >';
                var check_checked = '<input class="form-check-input" type="checkbox" id="cheque'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" >';
                var noSeal_checked = '<input class="form-check-input" type="checkbox" id="noSeal'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)">';
                var cancel = '<input class="form-check-input" type="checkbox" id="cancel'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)">';
                /* if(dt[i].delivered == 1){
                    delivered_checked = '<input class="form-check-input" type="checkbox" id="delivered' +"_"+dt[i].sales_invoice_Id + '" onchange="addDeliveryConfirmation(this)" checked>';
                }
                if(dt[i].Seal == 1){
                    seal_checked = '<input class="form-check-input" type="checkbox" id="seal'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" checked>';
                }
                if(dt[i].Signature == 1){
                    signature_checked = '<input class="form-check-input" type="checkbox" id="signature'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" checked>';
                }
                if(dt[i].Cash == 1){
                    cash_checked = '<input class="form-check-input" type="checkbox" id="cash'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" checked>';
                }
                if(dt[i].Cheque == 1){
                    check_checked = '<input class="form-check-input" type="checkbox" id="cheque'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" checked>';
                }
                if(dt[i].noSeal == 1){
                    noSeal_checked = '<input class="form-check-input" type="checkbox" id="noSeal'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" checked>';
                }
                if(dt[i].cancel == 1){
                    var cancel = '<input class="form-check-input" type="checkbox" id="cancel'+"_"+dt[i].sales_invoice_Id+'" onchange="addDeliveryConfirmation(this)" checked>';
                } */

                var pick = dt[i].picking_list_id;
                if(dt[i].picking_list_id == '' || dt[i].picking_list_id == null){
                    pick = "-";
                }
                data.push({
                    "invoice_date": dt[i].order_date_time,
                    "external_number": '<div data-id="'+dt[i].sales_invoice_Id+'">'+dt[i].external_number+'</div>',
                    "customer_name":shortenString(dt[i].customer_name,18),
                    "invoice_amount":parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "sales_order_number": dt[i].SO_external_number,
                    
                    "loading_number": "" /* dt[i].LO_external_number */,
                  
                    "invoice_add_user": dt[i].SI_user,
                    "sales_order_added_user":dt[i].SO_user,
                    "loading_added_user":""/* dt[i].LO_user */,
                   
                    "employee_name":dt[i].employee_name,
                    "route":'<div title="'+dt[i].route_name+'">'+shortenString(dt[i].route_name,13)+'</div>',
                  
                    "delivered":delivered_checked,
                    "seal":seal_checked,
                    "signature":signature_checked,
                    "cash": cash_checked,
                    "cheque":check_checked,
                    "no_seal":noSeal_checked,
                    "cancel":cancel,
                    "picking":pick
                });  
                
              
            }

            var table = $('#delivery_confirmation_table').DataTable();
            table.clear(); 
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    


}

function addDeliveryConfirmation(event){
    var check_box_values = {};
    var $row = $(event).closest('tr');
    var checkBoxId = $(event).attr('id');
    

    
    //invoice id
    var $firstCell = $row.find('td:eq(1)');
    var $div = $firstCell.find('div');
    var invoice_id = $div.data('id');
    console.log(invoice_id);
    //delivered value
    var delivered_cell = $row.find('td:eq(11) input[type="checkbox"]');
    var delivered_value = delivered_cell.is(':checked') ? 1:0;
    
    //seal value
    var seal_cell = $row.find('td:eq(12) input[type="checkbox"]');
    var seal_value = seal_cell.is(':checked') ? 1:0;

    //signature value
    var signature_cell = $row.find('td:eq(13) input[type="checkbox"]');
    var signature_value = signature_cell.is(':checked') ? 1:0;

    //cash value
    var cash_cell = $row.find('td:eq(14) input[type="checkbox"]');
    var cash_value = cash_cell.is(':checked') ? 1:0;

     //cheque value
     var checque_cell = $row.find('td:eq(15) input[type="checkbox"]');
     var cheque_value = checque_cell.is(':checked') ? 1:0;

      //no seal value
      var noSeal_cell = $row.find('td:eq(16) input[type="checkbox"]');
      var noSeal_value = noSeal_cell.is(':checked') ? 1:0;

      var cancel_cell = $row.find('td:eq(17) input[type="checkbox"]');
      var cancel_value = cancel_cell.is(':checked') ? 1:0;

   
    var formData = new FormData();
    formData.append('deliver',delivered_value);
    formData.append('seal',seal_value);
    formData.append('cash',cash_value);
    formData.append('cheque',cheque_value);
    formData.append('signature',signature_value);
    formData.append('noSeal',noSeal_value);
    formData.append('cancel',cancel_value);
    
      console.log(formData);
      

    $.ajax({
        url: '/sd/addDeliveryConfirmation/'+invoice_id,
        method: 'post',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            
        }, success: function (response) {
          console.log(response);

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
   

}


function confirm_all() {
    
    var $table = $('#delivery_confirmation_table');

    var confirm_data_array = [];
    $table.find('tr').each(function () {
        var checkboxes = $(this).find('input[type="checkbox"]');
        checkboxes.each(function () {
            if ($(this).prop('checked')) {
                var dataIdValue = $(this).closest('tr').find('td:eq(1) div').data('id');
                confirm_data_array.push(dataIdValue);
                return false;
            }
        });
    });
console.log(confirm_data_array);
    formData.append('confirm_data_array',JSON.stringify(confirm_data_array));
    if(confirm_data_array.length > 0){
        $.ajax({
            url: '/sd/confirm_all',
            method: 'post',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                
            }, success: function (response) {
              var status = response.status;
              if(status){
                showSuccessMessage('Record Updated');
                getDeliveryConfirmationData(0);
              }
    
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
    
            }
        })

    }else{
        showWarningMessage('No selected record to update');
    }

    
    
    
}


//load delivery plans to select tag
function loadDeliveryPlans(){
    $.ajax({
        url: '/sd/loadDeliveryPlans',
        type: 'get',
        async: false,
        success: function (data) {
            var htmlContent = "";
            
            htmlContent += "<option value='0'>Any</option>";

            $.each(data, function (key, value) {

                htmlContent += "<option value='" + value.delivery_plan_id + "'>" + value.external_number + "</option>";
            });
            $('#cmbdelevery_plan').html(htmlContent);
            
        },
       
    })
    $('#cmbdelevery_plan').change();
}

function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}