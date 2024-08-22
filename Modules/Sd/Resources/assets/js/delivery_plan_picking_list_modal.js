const DeliveryPlanPickingTable = function () {

    var delivery_plan_picking_table = undefined;
    const _DeliveryPlanNonPickingFixedColumns = function () {
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
        delivery_plan_picking_table = $('.datatable-fixed-both-delivery-plan-picking-list').DataTable({
            columnDefs: [
                {
                    width: 50,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                }
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

                { "data": "preview" },
                { "data": "external_no" },
            ],
        });

        // Adjust columns on window resize
        setTimeout(function () {
            $(window).on('resize', function () {
                delivery_plan_picking_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanNonPickingFixedColumns();
        },
        refresh: function () {
            if (delivery_plan_picking_table != undefined) {
                delivery_plan_picking_table.columns.adjust();
            }
        }
    }
}();






const DeliveryPlanNonPickingTable = function () {

    var delivery_plan_non_picking_table = undefined;
    const _DeliveryPlanNonPickingFixedColumns = function () {
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
        delivery_plan_non_picking_table = $('.datatable-fixed-both-delivery-plan-non-picking-list').DataTable({
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
                    width: 50,
                    targets: 5
                },
                {
                    width: 50,
                    targets: 6
                },
                {
                    width: 30,
                    targets: 7
                }
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
                { "data": "order_date" },
                { "data": "order_no" },
                { "data": "check" },


            ],
        });

        // Adjust columns on window resize
        setTimeout(function () {
            $(window).on('resize', function () {
                delivery_plan_non_picking_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanNonPickingFixedColumns();
        },
        refresh: function () {
            if (delivery_plan_non_picking_table != undefined) {
                delivery_plan_non_picking_table.columns.adjust();
            }
        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    DeliveryPlanPickingTable.init();
    DeliveryPlanNonPickingTable.init();
});




function showPickingListModal(delivery_plan_id,external_no, route_id) {
    getNonPickingList(delivery_plan_id, route_id);
    getPickingList(delivery_plan_id, route_id);
    $('#hid_delivery_plan_external_no').val(external_no);
    $('#modalDeliveryPlanPackingList').modal('show');
}



function getNonPickingList(delivery_plan_id, route_id) {
    $.ajax({
        type: "GET",
        url: '/sd/getNonPickingList/' + delivery_plan_id + '/' + route_id,
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
                    "invoice_no": result[i].external_number,
                    "customer": result[i].customer_name,
                    "town": result[i].townName,
                    "amount": result[i].total_amount,
                    "order_date": result[i].order_date_time,
                    "order_no": result[i].order_no,
                    "check": '<input id="nonPickingCheck' + i + '" data-delivery_plan_id="' + delivery_plan_id + '" data-sales_invoice_id="' + sales_invoice_id + '" name="nonPickingCheck" type="checkbox" id="selectAll">',
                });
            }
            var table = $('#nonPickingListTable').DataTable();
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



function getPickingList(delivery_plan_id, route_id) {
    $.ajax({
        type: "GET",
        url: '/sd/getPickingList/' + delivery_plan_id + '/' + route_id,
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
                var delivery_plan_packing_list_id = result[i].delivery_plan_packing_list_id;
                var external_number = result[i].external_number;
                data.push({
                    "preview": '<button class="btn btn-success" onclick="showPickingReport(' + delivery_plan_packing_list_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>',
                    "external_no": external_number,
                });
            }
            var table = $('#pickingListTable').DataTable();
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



function getSelectedNonPickingInvoice() {
    var invoiceArray = [];
    var table = document.getElementById('nonPickingListTable'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, id;

    for (i = 0, j = rows.length; i < j; ++i) {
        cells = rows[i].getElementsByTagName('td');
        if (!cells.length) {
            continue;
        }


        var checkBox = $(cells[7].childNodes[0]);
        if (checkBox.is(':checked')) {
            invoiceArray.push({
                "sales_invoice_id": checkBox.attr('data-sales_invoice_id'),
                "delivery_plan_id": checkBox.attr('data-delivery_plan_id'),
            });
        }

    }

    return invoiceArray;
}



function showPickingReport(delivery_plan_packing_list_id) {
    /* location.href= "/sd/pickinglist/"+delivery_plan_packing_list_id; */
    const newWindow = window.open("/sd/pickinglist/"+delivery_plan_packing_list_id);
    newWindow.onload = function() {
        newWindow.print();
      }
           
}


function hideActionPickingListInvoice(){
    $('#btnActionPickingListInvoice').hide();
}


function showActionPickingListInvoice(){
    $('#btnActionPickingListInvoice').show();
}