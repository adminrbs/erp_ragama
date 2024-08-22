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
                    width: 200,
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
                { "data": "sales_rep" },
                { "data": "Amount" },
                { "data": "approvalStatus" },
                { "data": "action" },
       
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
    
    getPendingapprovals();
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


function Approval(id){
    
        url = "/sd/salesInvoice?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.open(url, "_blank");
       
}

function edit(id, status) {

    url = "/sd/salesInvoice?id=" + id +"&paramS=Original"+"&action=edit"+"&task=null";
    window.open(url, "_blank");

}

function view(id,status){
    url = "/sd/salesInvoice?id=" + id +"&paramS=Original"+"&action=view"+"&task=null";
    window.open(url, "_blank");
}

//load data to table
function getPendingapprovals(){
    $.ajax({
        url:'/sd/getPendingapprovalsSalesInv',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
               
                var label_approval = '<label class="badge badge-pill bg-warning">' + dt[i].approval_status + '</label>';
                if (dt[i].approval_status == "Approved") {
                    label_approval = '<label class="badge badge-pill bg-success">' + dt[i].approval_status + '</label>';
                } else if (dt[i].approval_status == "Rejected")
                    label_approval = '<label class="badge badge-pill bg-danger">' + dt[i].approval_status + '</label>';

                    var str_id = "'"+dt[i].sales_invoice_Id+"'";
                    var st = "Original"
                     var str_status = "'"+st+"'"; 
                data.push({
                    "reference": dt[i].external_number,
                    "date": dt[i].order_date_time,
                    "customer": dt[i].customer_name,
                    "sales_rep": dt[i].employee_name,
                    "Amount": dt[i].total_amount,
                    "approvalStatus": label_approval,
                    "action": '<button class="btn btn-primary btn-sm" onclick="edit(' + str_id + ',' + str_status+ ')" style="display:none;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success btn-sm" onclick="view(' + str_id + ',' + str_status+ ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger btn-sm" onclick="_delete(' + str_id + ',' + str_status+ ')"><i class="fa fa-trash" aria-hidden="true"></i></button>&#160<button class="btn btn-info btn-sm" onclick="Approval(' + str_id + ')"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>',
                });
                
            }

            var table = $('#approval_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    
}


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

            getPendingapprovals();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })

}
