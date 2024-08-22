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
        $('#item_history_table').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
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
           /*  scrollY: 600, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "id" },
                { "data": "internal_number" },
                { "data": "external_number" },
                { "data": "document_no" },
                { "data": "qty" },
                { "data": "foc" },

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
    
    getItemHistory();

});



function getItemHistory(){
    $.ajax({
        type: "GET",
        url: "/sc/getItemHistory",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
           // console.log(response);
            var dt = response.data;

            var data = [];
            
            for (var i = 0; i < dt.length; i++) {
                data.push({
                    "id": dt[i].item_history_id,
                    "internal_number": dt[i].internal_number,
                    "external_number": dt[i].external_number,
                    "document_no": dt[i].document_number,
                    "qty": dt[i].quantity,
                    "foc": dt[i].free_quantity,
                   
                });

            }
            
            var table = $('#item_history_table').DataTable();
                table.clear();
                table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

