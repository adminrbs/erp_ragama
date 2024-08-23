
const DeliveryPlanInvoiceTable = function () {

    var invoice_table = undefined;
    const _DeliveryPlanInvoiceFixedColumns = function () {
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
        invoice_table = $('.datatable-fixed-both-delivery-plan-invoice').DataTable({
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
                    width: 50,
                    targets: 3
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
                { "data": "info" },
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
                invoice_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanInvoiceFixedColumns();
        },
        refresh: function () {
            if (invoice_table != undefined) {
                invoice_table.columns.adjust();
            }
        }
    }
}();


const DeliveryPlanAllocatedInvoiceTable = function () {

    var allocated_invoice_table = undefined;
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
        allocated_invoice_table = $('.datatable-fixed-both-delivery-plan-allocated-invoice').DataTable({
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
                        $(td).css('padding', '0px');
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
                allocated_invoice_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanAllocatedInvoiceFixedColumns();
        },
        refresh: function () {
            if (allocated_invoice_table != undefined) {
                allocated_invoice_table.columns.adjust();
            }
        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    DeliveryPlanInvoiceTable.init();
    DeliveryPlanAllocatedInvoiceTable.init();
});


function loadNonAllocatedInvoice(delivery_plan_id) {
    $.ajax({
        type: "GET",
        url: '/sd/getNonAllocateInvoice/' + delivery_plan_id,
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
                var btn = '<button class="btn btn-success btn-sm tooltip-target" title="Info" onclick="showInfoModel(' + result[i].customer_id + ','+result[i].branch_id+')"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                data.push({
                    "date": result[i].date,
                    "invoice_no": result[i].external_number,
                    "customer": result[i].customer_name,
                    "info":btn,
                    "town": result[i].townName,
                    "amount": result[i].total_amount,
                    "order_date": result[i].order_date_time,
                    "order_no": result[i].order_no,
                    "check": '<input id="invoiceCheck' + i + '" data-delivery_plan_id="' + delivery_plan_id + '" data-sales_invoice_id="' + sales_invoice_id + '" name="invoiceCheck" type="checkbox" id="selectAll">',
                });
            }
            var table = $('#invoiceTable').DataTable();
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

function showInfoModel(id,cus) {
    $('#block_id_hidden_lbl').val(id);
    $('#hidden_cus_lbl').val(cus);
    $('#block_customer_model_info').modal('show');
    $('#tabs a[href="#general"]').parent().hide();
    $('#general').hide();
   // load_block_info(id);
    loadOutstandingDataToTable(cus,$('#cmbBranch').val());
   
}

function loadOutstandingDataToTable(id,br){
    var table = $('#outstandingTable');
    var tableBody = $('#outstandingTable tbody');
    tableBody.empty();
    if(br == undefined){
        br = 0;
    }
    $.ajax({
        url: '/sd/loadOutstandingDataToTable/' + id +'/' + br,
        method: 'get',
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
            console.log(response);
            var dt = response.data;
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').text(item.trans_date));
                row.append($('<td>').text(item.external_number));
                row.append($('<td>').text(parseFloat(item.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').text(item.age));      
                table.append(row);
            });
            $('body').css('cursor', 'default');



        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })

}

function loadAllocatedInvoice(delivery_plan_id) {
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
                var remark = result[i].delivery_instruction;
                if (remark == null) {
                    remark = "";
                }
                data.push({
                    "date": result[i].date,
                    "invoice_no": result[i].external_number,
                    "customer": result[i].customer_name,
                    "town": result[i].townName,
                    "amount": result[i].total_amount,
                    "remark": '<input data-id="' + sales_invoice_id + '" type="text" style="min-height:35px;border:0px;background-color:transparent;" value="' + remark + '">',
                    //"check": '<input id="invoiceCheck' + i + '" data-delivery_plan_id="' + delivery_plan_id + '" data-sales_invoice_id="' + sales_invoice_id + '" name="invoiceCheck" type="checkbox" id="selectAll">',
                });
            }
            var table = $('#allocatedInvoiceTable').DataTable();
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





function getSelectedInvoice() {
    var invoiceArray = [];
    var table = document.getElementById('invoiceTable'),
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



function hideActionInvoice() {
    $('#btnActionInvoice').hide();
    $('#btnUpdateAllocatedRemark').show();
}



function showActionInvoice() {
    $('#btnActionInvoice').show();
    $('#btnUpdateAllocatedRemark').hide();
}



function updateAllocatedRemark() {


    var collection = [];
    var row = $('#tblAllocatedRemark').find('tr');

    for (var i = 0; i < row.length; i++) {

        var cell = $(row[i]).find('td');
        var input = $($(cell[5]).children()[0]);
        collection.push(JSON.stringify({ "id": input.attr("data-id"), "remark": input.val() }));
    }
    var formData = new FormData();
    formData.append("data", JSON.stringify(collection));
    $.ajax({
        url: '/sd/updateAllocatedRemark',
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
                showSuccessMessage('Data saved');
                $('#modalDeliveryPlanInvoice').modal('hide');
            }


        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });

}



