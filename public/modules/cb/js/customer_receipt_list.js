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
       var table = $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/cb/customer_receipt_list/getReceiptList',
               
            },
            columnDefs: [
                {
                    orderable: false,
                    targets: 3,
                    width: 100,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    }

                },
                {
                    orderable: false,
                    targets: 4,
                    width: 80,
                },
                {
                    orderable: false,
                    targets: 5,
                    width: 100,
                },
                {
                    orderable: false,
                    targets: 6,
                    width: 100,
                },
                {
                    orderable: false,
                    targets: 7,
                    width: 100,
                },
                
                {
                    orderable: false,
                    width: 100,
                    targets: 0
                },
                {
                    orderable: false,
                    width: 80,
                    targets: 1
                },
                {
                    orderable: false,
                    width: 200,
                    targets: 2
                },

            ],
            scrollX: true,
            /*  scrollY: 600, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "external_number" },
                { "data": "receipt_date" },
                { "data": "customer_name" },
                { "data": "amount" },
                { "data": "cheque_number" },
                { "data": "banking_date" },
                { "data": "payment_mode" },
                { "data": "buttons" },
                

            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });
        table.column(8).visible(false);
    };

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function () {
    showProgress();
    DatatableFixedColumns.init();
    //hideProgress();
    
});


/* --------------end of data table--------- */


$(document).ready(function () {

   // getReceiptList();
   hideProgress();
});




function getReceiptList() {
    $.ajax({
        url: '/cb/customer_receipt_list/getReceiptList',
        type: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var result = response.data;
            console.log(result);
            
            var data = [];
            for (var i = 0; i < result.length; i++) {
                var id = result[i].customer_receipt_id;
                var btn_edit = '<button class="btn btn-primary btn-sm" id="btnEdit_12" onclick="edit(' + id + ')" style="display:none;><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
                var btn_view = '<button class="btn btn-success btn-sm" onclick="view(' + id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                var btn_delete = '<button class="btn btn-danger btn-sm" onclick="_delete(' + id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                var btn_print = '<button class="btn btn-secondary btn-sm" onclick="generateReceiptList(' + id + ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
               
                data.push({

                    "ref_number": result[i].external_number,
                    "date": result[i].receipt_date,
                    "customer":'<div title="'+result[i].customer_name+'">'+shortenString(result[i].customer_name,27)+'</div',
                    "amount": parseFloat(result[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                    "chq_no":result[i].cheque_number,
                    "banking_date": result[i].banking_date,
                    "payment_mode": result[i].payment_mode,
                    "action": btn_edit + '&nbsp;' + btn_view + '&nbsp;' + btn_delete + '&nbsp;' + btn_print,
                    "invoice_numbers":result[i].invoice_numbers
                });

            }

            var table = $('#customerReceiptTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { 
            hideProgress();
        }
    });
}


function view(id) {
    location.href = 'customer_receipt?id=' + id + '&action=view';
}

function edit(id) {
    location.href = 'customer_receipt?id=' + id + '&action=edit';
}

function _delete(id) {
    alert(id);
}

function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}