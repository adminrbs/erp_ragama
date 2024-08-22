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
        var table = $('.datatable-fixed-both').DataTable({
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/sc/get_pending_reverse_trasfers',
               
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
                    targets: [2]
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
                { "data": "branch_name" },
                { "data": "dispatch_ref" },
                
                { "data": "statusLabel" },
             
                { "data": "total_amount" },
                { "data": "buttons" },
       
            ],
            "stripeClasses": ['odd-row', 'even-row']

        });

        


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

$(document).ready(function(){
   // getPendingapprovalsGRN();
});


function approve(id){
    
        url = "/sc/reverse_devision_transfer?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.open(url, "_blank");
       
}

function edit(id, status) {

    url = "/prc/goodReciveNote?id=" + id +"&paramS=Original"+"&action=edit"+"&task=null";
    window.open(url, "_blank");

}

function view(id, status) {
   
    status = "Original";
    url = "/sc/reverse_devision_transfer?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.location.href = url;
}



