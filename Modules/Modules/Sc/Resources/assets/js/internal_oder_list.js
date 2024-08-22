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
                searchPlaceholder: 'Press enter to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });


        // Left and right fixed columns
        var table = $('.datatable-fixed-both').DataTable({
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/sc/get_internal_orders',
               
            },
            columnDefs: [
              
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 250,
                    targets: 2
                },
                {
                    width: 150,
                    targets: 3
                },
                {
                    width: 150,
                    targets: 4
                },
                {
                    width: 70,
                    targets: 5,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    }
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "info":false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "external_number" },
                { "data": "order_date_time" },
                { "data": "from_branch_name" },
                { "data": "to_branch_name" },
                { "data": "name" },
               
                { "data": "buttons" }
       
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
   
    

});



function Approval(id){
    
        url = "/sd/salesInvoice?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.location.href = url;
       
}

function edit(id) {

    url = "/sc/salesInvoice?id=" + id +"&paramS="+status+"&action=edit"+"&task=null";
    window.location.href = url;

}

function view(id){
    var status = "Original"
    url = "/sc/internal_order_view?id=" + id +"&paramS="+status+"&action=view"+"&task=null";
    window.location.href = url;
}






function base64Encode(str) {
    return btoa(encodeURIComponent(str));
}

// Function to decode a Base64-encoded string
function base64Decode(str) {
    return decodeURIComponent(escape(atob(str)));
}
