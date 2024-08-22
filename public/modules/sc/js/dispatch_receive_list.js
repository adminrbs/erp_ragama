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
        var table = $('#dispatch_receive_list').DataTable({
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/sc/dispatch_receive_list',
               
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
                    width: '30%',
                    targets: 1
                },
                {
                    width:'100%',
                    targets: [2]
                },
                {
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    },

                    targets: 6
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
                { "data": "trans_date" },
                { "data": "external_number" },
                { "data": "from_branch" },
                { "data": "to_branch" },
                { "data": "from_location" },
                { "data": "to_location" },
                { "data": "total_amount" },
                { "data": "buttons" },

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


$(document).ready(function () {
   

    
    
});





function view(id) {
    var status = null
    url = "/sc/dispatch_receive_view?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.location.href = url;
}







