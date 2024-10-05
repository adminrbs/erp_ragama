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
                { "data": "ref_number" },
                { "data": "date" },
                { "data": "customer" },
                { "data": "amount" },
                { "data": "chq_no" },
                { "data": "banking_date" },
                { "data": "payment_mode" },
                { "data": "action" }

            ],
            "stripeClasses": ['odd-row', 'even-row'],
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


$(document).ready(function () {

    getReceiptList();
});




function getReceiptList() {
    $.ajax({
        url: '/sl/supplier_pyment_list/getReceiptList',
        type: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var result = response.data;

            var data = [];
            for (var i = 0; i < result.length; i++) {
                var id = result[i].supplier_payment_id;
                var btn_edit = '<button class="btn btn-primary btn-sm" id="btnEdit_12" onclick="edit(' + id + ')" style="display:none;><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
                var btn_view = '<button class="btn btn-success btn-sm" onclick="view(' + id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                var btn_delete = '<button class="btn btn-danger btn-sm" onclick="_delete(' + id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                var btn_print = '<button class="btn btn-secondary btn-sm" onclick="supplierReceiptReport(' + id + ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
               
                data.push({

                    "ref_number": result[i].external_number,
                    "date": result[i].receipt_date,
                    "customer":'<div title="'+result[i].supplier_name+'">'+shortenString(result[i].supplier_name,27)+'</div',
                    "amount": parseFloat(result[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                    "chq_no":result[i].cheque_number,
                    "banking_date": result[i].banking_date,
                    "payment_mode": result[i].payment_mode,
                    "action": btn_view + '&nbsp;' + btn_print,
                });

            }

            var table = $('#supplier_payment_list').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}


function view(id) {
    location.href = 'supplier_payment?id=' + id + '&action=view';
}

function edit(id) {
    location.href = 'supplier_payment?id=' + id + '&action=edit';
}

function _delete(id) {
  //  alert(id);
}

function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}