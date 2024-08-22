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
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/sd/getSalesInvoiceData',
               
            },
            columnDefs: [
              
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 250,
                    targets: 2
                },
                {
                    width: 150,
                    targets: 3
                },
                {
                    width: 150,
                    targets: 4
                },
                {
                    width: 70,
                    targets: 5,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    }
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "info":false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "info" },
                { "data": "order_date_time" },
                { "data": "customer_name" },
                { "data":"route_name" },
                { "data": "employee_name" },
                { "data": "total_amount" },
               
                { "data": "statusLabel" },
                { "data": "printed" },
              /*   { "data": "approvalStatus" }, */
                { "data": "buttons" }
       
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
   // getSalesInvoiceData();
    

});
function _delete(id, status) {
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
                className: 'btn-link'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                deleteSI(id, status,);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
    
}

//reprint confrimation 
function reprint(id) {
    bootbox.confirm({
        title: 'Re-print confirmation',
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
                allowReportin(id)
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

function Approval(id){
    
        url = "/sd/salesInvoice?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.location.href = url;
       
}

function edit(id, status) {

    url = "/sd/salesInvoice?id=" + id +"&paramS="+status+"&action=edit"+"&task=null";
    window.location.href = url;

}

function view(id,status){
    url = "/sd/salesInvoiceView?id=" + id +"&paramS="+status+"&action=view"+"&task=null";
    window.location.href = url;
}

//load data to table
function getSalesInvoiceData(){
    $.ajax({
        url:'/sd/getSalesInvoiceData',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var str_id = "'" + dt[i].sales_invoice_Id + "'";
                var str_status = "'" + dt[i].status + "'";
                var str_primary = dt[i].sales_invoice_Id; // edit button id

                var label = '<label class="badge badge-pill bg-danger">' + dt[i].status + '</label>';
                if (dt[i].status == "Original") {
                    label = '<label class="badge badge-pill bg-success">' + dt[i].status + '</label>';

                }
                var label_approval = '<label class="badge badge-pill bg-warning">' + dt[i].approval_status + '</label>';
                var disabled = "";
                if (dt[i].approval_status == "Approved") {
                    label_approval = '<label class="badge badge-pill bg-success">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";


                } else if (dt[i].approval_status == "Rejected") {
                    label_approval = '<label class="badge badge-pill bg-danger">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";
                }
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_id + ',' + str_status + ')" ' + disabled + ' style="display:none;"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + str_id + ',' + str_status + ')"'+ disabled +'><i class="fa fa-trash" aria-hidden="true"></i></button>';
                btnPrintAllow = '<button class="btn btn-secondary btn-sm tooltip-target" onclick="reprint(' + dt[i].sales_invoice_Id + ')" title="Allow re-print"><i class="fa fa-print" aria-hidden="true"></i></button>';
                if(dt[i].is_reprint_allowed == 1){
                    btnPrintAllow = '<button class="btn btn-warning btn-sm tooltip-target" onclick="reprint(' + dt[i].sales_invoice_Id + ')" title="Revoke re-print"><i class="fa fa-print" aria-hidden="true"></i></button>'; 
                }
                var encodedManualNumber = base64Encode(dt[i].manual_number);
                info = '<a href="../sd/invoice_nfo?manual_number=' + encodedManualNumber+ '&action=inquery" onclick="updateTotal()" target="_blank">' + dt[i].manual_number +'&nbsp;&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';

                data.push({
                   
                     "reference": dt[i].external_number, 
                   /*  "reference": info, */
                    "date": dt[i].order_date_time,
                    "customer": shortenString(dt[i].customer_name,20),
                    "route": shortenString(dt[i].route_name,15),
                    "sales_rep": shortenString(dt[i].employee_name,15),
                    "Amount": parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                   
                    "status":label,
                    /* "approvalStatus": label_approval, */
                    "action": btnEdit + '&#160<button class="btn btn-success btn-sm " onclick="view(' + str_id + ',' + str_status + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160',
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


//delete PO
function deleteSI(id, status) {
    console.log(id);
    console.log(status);
    $.ajax({
        url: '/sd/deleteSI/' + id + '/' + status,
        type: 'delete',
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response.message;
            if (status == "Deleted") {
                showSuccessMessage("Successfully deleted");

            } else {
                showErrorMessage("Something went wrong")
            }

            getSalesInvoiceData();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })

}

function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}


function allowReportin(id){
    $.ajax({
        url: '/sd/allowReportin/' + id,
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
                getSalesInvoiceData();
                return;

            } else{

                showSuccessMessage("Reprint revoked");
                getSalesInvoiceData();
                return;
            }
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}


function base64Encode(str) {
    return btoa(encodeURIComponent(str));
}

// Function to decode a Base64-encoded string
function base64Decode(str) {
    return decodeURIComponent(escape(atob(str)));
}

function salesinvoiceReportpage(id){

    url="/sd/salesinvoiceReportiframe?id="+ id;
    window.location.href = url;
}