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
        $('#credit_note_list').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/dl/get_credit_note_pending_details',
               
            },
            columnDefs: [
              
                {
                    width: 100,
                    targets: 0
                },
                {
                    width: 80,
                    targets: 1
                },
                {
                    orderable:false,
                    width: 50,
                    targets: 2,
                    class: 'text-right'
                },
                {
                    width: 300,
                    targets: 3
                },
                {
                    width: 50,
                    targets: 5
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
                { "data": "external_number" },
                { "data": "trans_date" },
                { "data":"amount" },
                { "data":"customer_name" },
                { "data":"employee_name" },
                { "data":"narration_for_account" },
                { "data":"name" },
                { "data":"branch_name"},
                { "data": "buttons" }

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

$(document).ready(function () {
    

});


function view(id){
    url = "/dl/credit_note?id=" + id + "&action=view";
    window.location.href = url;
}

function print(id){
    
    url = "/dl/print_cr/" + id;
    window.location.href = url;
}

function approve(id){

    url = "/dl/credit_note?id=" + id + "&action=approve";
    window.location.href = url;
}



