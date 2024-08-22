

var selected_postpone_count = 0;
const DeliveryPostponeTable = function () {

    var delivery_postpone_table = undefined;
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
        delivery_postpone_table = $('.datatable-fixed-both-delivery-plan-postpone').DataTable({
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
                    width: 40,
                    targets: 5
                },
                {
                    width: 100,
                    targets: 6
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '5px');
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
                { "data": "postpone" },
                { "data": "reason" },
            ],

        });

        // Adjust columns on window resize
        setTimeout(function () {
            $(window).on('resize', function () {
                delivery_postpone_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanAllocatedInvoiceFixedColumns();
        },
        refresh: function () {
            selected_postpone_count = 0;
            if (delivery_postpone_table != undefined) {
                delivery_postpone_table.columns.adjust();
            }
        },
        rowCount: function () {
            var rowCount = delivery_postpone_table.rows().count();
            return rowCount;
        },
        getTable: function () {
            return delivery_postpone_table;
        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    DeliveryPostponeTable.init();
});








function showPostponeDelivery(delivery_plan_id) {
    $('#modalDeliveryPostponeList').modal('toggle');
    $.ajax({
        type: "GET",
        url: '/sd/getDeliveryplanPostpone/' + delivery_plan_id,
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

                data.push({
                    "date": result[i].date,
                    "invoice_no": result[i].manual_number,
                    "customer": result[i].customer_name,
                    "town": result[i].townName,
                    "amount": result[i].total_amount,
                    "postpone": '<input id="invoiceCheck' + i + '" data-delivery_plan_id="' + delivery_plan_id + '" data-sales_invoice_id="' + sales_invoice_id + '" name="invoiceCheck" type="checkbox" id="selectAll" onchange="checkCount(this)">',
                    "reason": '<input type="text" class="form-control" style="height:100%;min-width:200px;">',
                    //"check": '<input id="invoiceCheck' + i + '" data-delivery_plan_id="' + delivery_plan_id + '" data-sales_invoice_id="' + sales_invoice_id + '" name="invoiceCheck" type="checkbox" id="selectAll">',
                });
            }
            var table = $('#deliveryPostponeTable').DataTable();
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


function updatePostponeDelivery() {
    var collection = [];
    var row = $('#tblPostponeDelivery').find('tr');

    for (var i = 0; i < row.length; i++) {

        var cell = $(row[i]).find('td');
        var check_input = $($(cell[5]).children()[0]);
        var reason_input = $($(cell[6]).children()[0]);
        if (check_input.is(":checked")) {
            collection.push(JSON.stringify({ "delivery_plan_id": check_input.attr("data-delivery_plan_id"), "sales_invoice_id": check_input.attr("data-sales_invoice_id"), "reason": reason_input.val() }));
        }
    }
    var formData = new FormData();
    formData.append("data", JSON.stringify(collection));
    $.ajax({
        url: '/sd/updatePostponeDelivery',
        method: 'post',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);

            if (response.status) {
                showSuccessMessage('Data updated');
                $('#modalDeliveryPostponeList').modal('hide');
                if (STATUS == "delivered") {
                    getDeliveryPlansDeliverd();
                } else {
                    getDeliveryPlansNoneDeliverd();
                }
            }


        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}

function checkCount(event) {
    if ($(event).is(':checked')) {
        selected_postpone_count++;
    } else {
        selected_postpone_count--;
    }
    $('#postpone_delivery_header').html("Postpone "+selected_postpone_count);

}









