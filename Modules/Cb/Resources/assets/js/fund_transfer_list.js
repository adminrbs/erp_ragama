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
            autoWidth: false,
            dom: '<"datatable-header justify-content-center"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill"><div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span>',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }


        });


        // Left and right fixed columns
        var table = $('#fund_transfer').DataTable({
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Goods Received List',
                        text: 'Export to Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    /* {
                        extend: 'pdfHtml5',
                        title: 'Purchase Order',
                        exportOptions: {
                            columns: [ 0,1,2,3,4,5,6]
                        }
                    } */
                ]
            },
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
                    width: '50%',
                    targets: 1
                },
                {
                    width: '30%',
                    targets: 2
                },
                {
                    width: '30%',
                    targets: 3
                },
                {
                    width: '30%',
                    targets: [4]
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
                { "data": "date" },
                { "data": "description" },
                { "data": "created_by" },
                { "data": "branch" },
                { "data": "action" },

            ],
            "stripeClasses": ['odd-row', 'even-row']

        });

        //table.column(0).visible(false);



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
    getAllFundTransfer();
});



function getAllFundTransfer() {
    $.ajax({
        type: "GET",
        url: "/cb/getAllFundTransfer",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response.data);
            var dt = response.data;
            var data = [];
            var disabled = "";
            for (var i = 0; i < dt.length; i++) {

                var approval_status = dt[i].approval_status;
                var is_approved = "disabled";
                if (approval_status == 0) {
                    is_approved = "";
                }
                var btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + dt[i].fund_transfer_id + '" onclick="edit(' + dt[i].fund_transfer_id + ')" ' + disabled + '" ' + is_approved + '><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                var btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].fund_transfer_id + ')"' + is_approved + '><i class="fa fa-trash" aria-hidden="true"></i></button>';
                var btnPrint = '<button class="btn btn-secondary btn-sm" onclick="printVoucher(' + dt[i].fund_transfer_id + ')"><i class="fa fa-print" aria-hidden="true"></i></button>'
                var btnApproval = '<button class="btn btn-primary btn-sm" onclick="approval(' + dt[i].fund_transfer_id + ')" ' + is_approved + '><i class="fa fa-check-square-o" aria-hidden="true"></i></button>'

                var created_by = "Admin";
                if (dt[i].created_by != null) {
                    created_by = dt[i].created_by;
                }

                data.push({
                    "date": dt[i].transaction_date,
                    "description": dt[i].description,
                    "created_by": created_by,
                    "branch": dt[i].branch_name,
                    "action": btnEdit + '&#160<button class="btn btn-success btn-sm" onclick="view(' + dt[i].fund_transfer_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160' + btnDlt + '&#160' + '&#160' + btnApproval,
                });

            }

            var table = $('#fund_transfer').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }
    })
}



function edit(id, status) {

    url = "/cb/fund_transfer/" + id + "/edit";
    window.location.href = url;

}

function approval(id) {

    url = "/cb/fund_transfer/" + id + "/approval";
    window.location.href = url;

}

function view(id, status) {

    url = "/cb/fund_transfer_view/" + id;
    window.location.href = url;
}
