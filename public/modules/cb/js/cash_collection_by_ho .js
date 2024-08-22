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
                    targets: 2
                },
                {
                    width: 50,
                    targets:4 
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
               
                { "data": "date" },
                { "data": "ref_number" },
                { "data": "customer" },
                { "data": "amount" },
                { "data": "action" },
              

               

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

$(document).ready(function(){

});

//load customer receipts for cash collecion by branch list
function loadCustomerReceipts_cash_ho(){
    $.ajax({
            url:'/cv/loadCustomerReceipts_cash_ho',
            type:'get',
            cache: false,
            timeout: 800000,
            beforeSend: function () { },
            success: function (response) {
                var dt = response.data;
    
                var data = [];
                for (var i = 0; i < dt.length; i++) {
                   
                    data.push({
                        "invoice_date": dt[i].order_date_time,
                        "external_number": '<div data-id="'+dt[i].sales_invoice_Id+'">'+dt[i].external_number+'</div>',
                        "customer_name":shortenString(dt[i].customer_name,25),
                        "invoice_amount":parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "sales_order_number": dt[i].SO_external_number,
                      
                    });  
                    
                  
                }
    
                var table = $('#cash_collection_by_ho_table').DataTable();
                table.clear(); 
                table.rows.add(data).draw();
    
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () { }
        })

}