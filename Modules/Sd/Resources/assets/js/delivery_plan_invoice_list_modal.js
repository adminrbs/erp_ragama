


const DeliveryPlanAllocatedInvoiceListTable = function () {

    var allocated_invoice_list_table = undefined;
    const _DeliveryPlanAllocatedInvoiceFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [1]
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
        allocated_invoice_list_table = $('.datatable-fixed-both-delivery-plan-allocated-invoice-list').DataTable({
            columnDefs: [
                {
                    width: 50,
                    targets: 0
                },
                {
                    width: 50,
                    targets: 1
                },
                {
                    width: '100%',
                    targets: 2
                },
                {
                    width: 50,
                    targets: 4
                },
                {
                    width: 100,
                    targets: 5
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '10px');
                    }
                },
            ],
            scrollX: true,
            scrollY: 300,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 1
            },
            "paging": false,
            "pageLength": 20,
            "order": [],
            "columns": [

                { "data": "date" },
                { "data": "invoice_no" },
                { "data": "customer" },
                { "data": "town" },
                { "data": "amount" },
                { "data": "remark" },
            ],

        });

        // Adjust columns on window resize
        setTimeout(function () {
            $(window).on('resize', function () {
                allocated_invoice_list_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanAllocatedInvoiceFixedColumns();
        },
        refresh: function () {
            if (allocated_invoice_list_table != undefined) {
                allocated_invoice_list_table.columns.adjust();
            }
        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    DeliveryPlanAllocatedInvoiceListTable.init();
});








function loadAllocatedInvoiceList(delivery_plan_id) {
    $('#modalDeliveryPlanInvoiceList').modal('toggle');
    $.ajax({
        type: "GET",
        url: '/sd/getAllocatedInvoice/' + delivery_plan_id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var result = response.data;
            var data = [];
            for (let i = 0; i < result.length; i++) {
                var sales_invoice_id = result[i].sales_invoice_Id;
                var delivery_instruction = result[i].delivery_instruction;
                if(delivery_instruction == "null"){
                    delivery_instruction = "";
                }
                data.push({
                    "date": result[i].date,
                    "invoice_no": result[i].external_number,
                    "customer": result[i].customer_name,
                    "town": result[i].townName,
                    "amount": result[i].total_amount,
                    "remark": delivery_instruction,
                    //"check": '<input id="invoiceCheck' + i + '" data-delivery_plan_id="' + delivery_plan_id + '" data-sales_invoice_id="' + sales_invoice_id + '" name="invoiceCheck" type="checkbox" id="selectAll">',
                });
            }
            var table = $('#allocatedInvoiceListTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}









