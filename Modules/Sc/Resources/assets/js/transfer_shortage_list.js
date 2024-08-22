var table;
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
        table = $('#transfer_shortage_list_table').DataTable({
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
           
            ajax: {
                url : '/sc/get_transfer_shortages',
                type: 'get', // Set the request type to POST
                "dataType": "json",
                "contentType": 'application/json; charset=utf-8',
                "data": function (data) { 
                    data.from = $('#cmbFromBranch').val()
                    data.to = $('#cmbToBranch').val()
                 }
            },
            
            

            columnDefs: [
                {
                    orderable: false,
                    targets: '_all' // Apply to all columns
                },
                
                {
                    width: 170,
                    targets: 0
                },
                {
                    width: 100,
                    targets: 1
                },
                {
                    width:150,
                    targets: 2
                },
                {
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    },

                    targets: 6,
                    width:35
                },
                {
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    },

                    targets: 7,
                    width:35
                },

            ],
            scrollX: true,
            info:false,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "trans_date" },
                { "data": "external_number" },
                { "data": "item_Name" },
                { "data": "Item_code" },
                { "data": "package_unit" },
                { "data": "quantity" },
                { "data": "received_qty" },
                { "data": "balance" },
                { "data": "from_branch" },
              
                { "data": "to_branch" },
                { "data": "name" },
                { "data": "trans_date" }
               
                
               

            ],
            "stripeClasses": ['odd-row', 'even-row']

        });

        //table.column(0).visible(false);
/* 
        <th>Date</th>
        <th>Reference #</th>
        <th>Item Name</th>
        <th>Item Code</th>
        <th>QTY</th>
        <th>Received QTY</th>
        <th>Balance</th>
        <th>From Branch</th>
        <th>From Location</th>
        <th>To Branch</th>
        <th>To Location</th>
        <th>Balance QTY</th> */


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
    getBranches(); //callng branch loading ufnction
    $('#cmbFromBranch, #cmbToBranch').on('change',function(){
        table.ajax.reload(); 
    });

    
});


//load branches
function getBranches() {
    $.ajax({
        url: '/sc/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            if(data.length > 1){
                /* $('#cmbBranch').append('<option value="">Select Branch</option>');
                $('#cmbToBranch').append('<option value="">Select Branch</option>'); */
            }
            $.each(data, function (index, value) {
                $('#cmbFromBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#cmbToBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
            })
            $('#cmbFromBranch').trigger('change');
            $('#cmbToBranch').trigger('change');
        },
    })
}







