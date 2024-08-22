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
        var table = $('.datatable-fixed-both').DataTable({
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
                    width: 500,
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
            "order": [],
            "columns": [
                { "data": "reference" },
                { "data": "date" },
                { "data": "customer" },
                { "data":"route" },
                { "data": "sales_rep" },
                { "data": "Amount" },
                { "data": "branch" },
                { "data": "user" },
                { "data": "action" }
       
            ],
            "stripeClasses": ['odd-row', 'even-row']


            

            

        });

        


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

$(document).ready(function(){
    $(function () {
        $(".tooltip-target").tooltip();
    });
    get_reprint_request();
    

});


 
function Approval(id) {
    bootbox.confirm({
        title: 'Re-print approval',
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
                approve_request(id)
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

function Reject(id){
    bootbox.confirm({
        title: 'Reject re-print request',
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
                reject_request(id)
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





//load data to table
function get_reprint_request(){
    $.ajax({
        url:'/sd/get_reprint_request',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
              /*   var str_id = "'" + dt[i].sales_invoice_Id + "'";
                var str_status = "'" + dt[i].status + "'";
                var str_primary = dt[i].sales_invoice_Id; // edit button id
 */
                btn_approve = '<button class="btn btn-success btn-sm tooltip-target" onclick="Approval(' + dt[i].reprint_requests_id + ')" title="Approve re-print">Approve</button>';
                btn_reject = '<button class="btn btn-danger btn-sm tooltip-target" onclick="Reject(' + dt[i].reprint_requests_id + ')" title="Reject re-print">Reject</button>';
                
                data.push({
                   
                    
                    "reference": dt[i].manual_number,
                    "date": dt[i].order_date_time,
                    "customer": dt[i].customer_name,
                    "route": dt[i].route_name,
                    "sales_rep": dt[i].employee_name,
                    "Amount": parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "branch":dt[i].branch_name,
                    "user":dt[i].name,
                   
                    "action":  btn_approve+" "+btn_reject,
                });    
                
                
                
               
            }

            var table = $('#sales_invoice_table').DataTable();
            table.clear(); 
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    
}




function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}


function approve_request(id){
    $.ajax({
        url: '/sd/approve_request/' + id,
        method: 'post',
        enctype: 'multipart/form-data',
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
            var status = response.status
            var msg = response.message
            if (msg == 'granted') {
                showSuccessMessage("Reprint allowed");
                get_reprint_request();
                return;

            } else{

                showWarningMessage("Unable to approve");
                get_reprint_request();
                return;
            }
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}

function reject_request(id){
    $.ajax({
        url: '/sd/reject_request/' + id,
        method: 'post',
        enctype: 'multipart/form-data',
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
            var status = response.status
            var msg = response.message
            if (msg == 'rejected') {
                showSuccessMessage("Request rejected");
                get_reprint_request();
                return;

            } else{

                showWarningMEssage("Unable to reject");
                get_reprint_request();
                return;
            }
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}