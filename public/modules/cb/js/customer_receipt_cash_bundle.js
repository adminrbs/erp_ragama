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
      var table =  $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            columnDefs: [

                {
                    width: 150,
                    targets: 0,
                    orderable: false
                },
                {
                    width: 150,
                    targets: 1,
                    orderable: false
                },
                {
                    width: 150,
                    targets: 2,
                    orderable: false
                },
                {
                    width: 180,
                    targets:3,
                    orderable: false,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    }
 
                },
                {
                    width: 50,
                    targets:4,
                    orderable: false 
                },
                {
                    width: 50,
                    targets:5,
                    orderable: false 
                },
                {
                    width: 150,
                    targets:6,
                    orderable: false 
                },
                {
                    width: 100,
                    targets:7,
                    orderable: false 
                },
               


            ],
            scrollX: true,
            scrollY: '300px',
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "date" },
                { "data": "ref_number" },
                { "data": "Cashier" },
                { "data": "amount" },
                { "data": "no_of" },
                { "data": "info" },
                { "data": "action" },
                { "data": "status" }
               
               
               

            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });

        table.column(7).visible(false);

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


$(document).ready(function(){
    loadcashBundle_receipt();
   // getBranches();
});


//load cash bundles
function loadcashBundle_receipt(){
    
    $.ajax({
            url:'/cb/loadcashBundle_receipt',
            type:'get',
            cache: false,
            timeout: 800000,
            beforeSend: function () { },
            success: function (response) {
                var dt = response.data;
    
                var data = [];
                for (var i = 0; i < dt.length; i++) {
                    var count = dt[i].cash_bundles_datas_count;
                   var create_button = '<button class="btn btn-primary" title="Create Button" type="button" id="btnrcpt_' +"_"+dt[i].cash_bundles_id + '" onclick="confirm_create(this)">Create Receipt</button>';
                    var badge_status = '<label class="badge badge-pill bg-warning" id='+dt[i].cash_bundles_id+'>Pending</label>';
                   var print_button = '<button class="btn btn-secondary" title="Print" type="button" id="btnrcptPrint_' +"_"+dt[i].cash_bundles_id + '" onclick="printBundle('+dt[i].cash_bundles_id+')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                    /*  if(dt[i].receipt_status == 1){
                    cash_check_box = '<input class="form-check-input" type="checkbox" id="cash_branch' +"_"+dt[i].customer_receipt_id + '" onchange="update_status_calculation(this)" checked>';
                   } */
                    data.push({
                        "date": '<div data-id="'+dt[i].cash_bundles_id+'">' + dt[i].cash_bundle_date + '</div>',
                        "ref_number":'<div data-id="'+dt[i].cash_bundles_id+'">' + dt[i].external_number + '</div>',
                        "Cashier":shortenString(dt[i].name,50),
                        "amount":parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "no_of":count,
                        "info":'<button class="btn btn-success btn-sm" onclick="showModel(this)" id="'+dt[i].internal_number+'"><i class="fa fa-info-circle" aria-hidden="true"></i></button>',
                        "action": create_button +" "+print_button,
                        "status":badge_status         
                       
                    });  

                   
                }
    
                var table = $('#cash_bundle_table_for_rcpt').DataTable();
                table.clear(); 
                table.rows.add(data).draw();
    
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () { }
        })

}

function printBundle(id){
    let url = '/cb/printSfaCashBundle/'+id;
    window.open(url, '_blank');
}

/* //locad branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            var htmlContent = "";
            if(data.length <=1 ){
                $.each(data, function (key, value) {

                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });
                $('#cmbBranch').html(htmlContent);
                $('#cmbBranch').prop('disabled',true);
                loadCustomerReceipts_cash_ho($('#cmbBranch').val());
                
            }else if(data.length > 1){
                htmlContent += "<option value=''>Select branch</option>";

                $.each(data, function (key, value) {
    
                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });
    
                $('#cmbBranch').html(htmlContent);
            }
         
          
            $('#cmbBranch').change();
        },
    })
} */

function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}

//create customer receipt using cash bundles

function create_rcpt(event) {

    var nextCell = $(event).parent().next();
    var label = nextCell.find('label');

    var recpt_id_ = $(event).attr('id');
   
    
            var parts = recpt_id_.split('_');
            var r_id_ = parts[2];
           
    var formData = new FormData();
      formData.append('b_id',r_id_);
      console.log(formData);
    
    $.ajax({
        url: '/cb/create_rcpt',
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
           
            var message = response.message;
            var status = response.status
            if (message == 'saved') {
                $(event).prop('disabled',true);
            }else if( message == 'used'){
                showWarningMessage('Receipt already created');
                label.removeClass('bg-warning').addClass('bg-danger');
                label.text('Failed');
                return;
            }

            if(status){
                showSuccessMessage('Receipt created');
                label.removeClass('bg-warning').addClass('bg-success');
                label.text('Completed');    
                
            }else{
                showWarningMessage('Unable to create');
                label.removeClass('bg-warning').addClass('bg-danger');
                label.text('Failed'); 
                return;
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}


//confirmaion box
function confirm_create(event){
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
                create_rcpt(event)
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

}



function showModel(event){
    
    $("#inv_model_cus_rcpt").modal("show");
    $('#hiddenItem').val($(event).attr('id'));
    loadInvoices_cus_rcpt($(event).attr('id'))
    
}

function loadInvoices_cus_rcpt(id){
    var table = $('#invoice_table');
    var tableBody = $('#invoice_table tbody');
    tableBody.empty();
   
    $.ajax({
        type: "GET",
        url: "/cb/loadInvoices_cus_rcpt/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
             var dt = response.data
            console.log(dt);
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').text(item.external_number));
                row.append($('<td>').text(item.receipt_date));
                row.append($('<td>').text(item.employee_name));
                row.append($('<td>').text(parseFloat(item.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))); 
                row.append($('<td>').text(item.remarks));
                table.append(row);
            });
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

