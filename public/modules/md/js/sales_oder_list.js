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
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width:200,
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
            scrollY: 600,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "order_date_time" },
                { "data": "external_number" },
                { "data": "customer_name" },
                { "data": "employee_name" },
                { "data": "Delivery_id" },
                { "data": "deliver_date_time" },
                { "data": "Status" },
                { "data": "ActionMenu" }

            ],
            "stripeClasses": [ 'odd-row', 'even-row' ],
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

$(document).ready(function(){
    getSalesOrderDetails();

});

function getSalesOrderDetails(){
    $.ajax({
        type: "GET",
        url: "/md/getSalesOrderDetails",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response;

            var data = [];
            
            for (var i = 0; i < dt.length; i++) {
                data.push({
                    "order_date_time": dt[i].order_date_time,
                    "external_number": dt[i].external_number,
                    "customer_name": dt[i].customer_name,
                    "employee_name": dt[i].employee_name,
                    "Delivery_id": dt[i].deliver_type_id,
                    "deliver_date_time": dt[i].deliver_date_time,
                    "Status": dt[i].order_status_id,
                    "ActionMenu":'<div class="dropdown position-static">' +
            '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i>'+
            '</a>' +
            '<div class="dropdown-menu dropdown-menu-end">' +
              '<a class="dropdown-item" href="#">PDF</a>' +
              '<a class="dropdown-item" href="#">CSV</a>' +
              '<a class="dropdown-item" href="#">Excel</a>' +
            '</div>' 
                });
            }

            
            var table = $('#sales_oderTable').DataTable();
                table.clear();
                table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}