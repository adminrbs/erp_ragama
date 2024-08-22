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
        var table = $('.datatable-fixed-both-batchTable').DataTable({
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
            autoWidth: false,
            scrollX: true,
            scrollY: 150,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "batch_no" },
                { "data": "cost_price" },
                { "data": "wholeSale" },
                { "data": "retail" },
                { "data": "avl_qty" },
                { "data": "setOff" },
               

            ],
            "stripeClasses": ['odd-row', 'even-row']

        });
        table.column(1).visible(false);



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
var checkedRows = [];
var colleaction = [];
var orderID;
$(document).ready(function () {
    alert('linked');
    $('#batchModel').on('show.bs.modal', function () {
        
        getItemHistorySetoffBatch(1,1);
        TableRefresh();
        
    })


});


function getItemHistorySetoffBatch(location_id,item_id) {
    alert('batch');
    $.ajax({
        type: "GET",
        url: "/sd/getItemHistorySetoffBatch/"+location_id+"/"+item_id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];

            for (var i = 0; i < dt.length; i++) {
                data.push({
                    "batch_no": dt[i].sales_order_Id,
                    "cost_price": '<div data-id = "' + dt[i].sales_order_Id + '">' + dt[i].order_date_time + '</div>',
                    "wholeSale": dt[i].external_number,
                    "retail": dt[i].customer_name,
                    "avl_qty": dt[i].amount,
                    "setOff": dt[i].deliver_date_time,
                   
                });

              


            }

            var table = $('#batchTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}






function TableRefresh() {
    var table = $('#batchTable').DataTable();
    table.columns.adjust().draw();
    $('.dataTables_scrollBody').css('height', '150px');
}
