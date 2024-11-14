
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
        $('#direct_cheque_bundle_list').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            columnDefs: [

                {
                    width: 100,
                    targets: 0,
                    orderable: false
                },
                {
                    width: "100%",
                    targets: 1,
                    orderable: false
                },
                {
                    width: 80,
                    targets: 2,
                    orderable: false
                },
                {
                    width: 200,
                    targets: 3,
                    orderable: false
                }
            


            ],
            scrollX: true,
            scrollY: '300px',
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
                { "data": "amount" },
                
                { "data": "action" }

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

$(document).ready(function(){
   /*  $('#cmbBranch').on('change', function () {
       
        load_direct_cheque_collection($('#cmbBranch').val());

    }); */
    //getBranches();
    load_cheque_collection();

});
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            var htmlContent = "";

            htmlContent += "<option value='0'>Select branch</option>";

            $.each(data, function (key, value) {

                htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
            });

            $('#cmbBranch').html(htmlContent);

        },
    })
}

//load direct customer receipts for cash bundle - ho
function load_cheque_collection() {
   
    $.ajax({
        url: '/cb/load_cheque_collection/',
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                console.log(dt[i].book);
               // btn_info = '<button class="btn btn-success btn-sm tooltip-target" title="Info" onclick="showModal(' +dt[i].direct_cheque_collection_id+')"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                btn_print = '<button class="btn btn-secondary btn-sm tooltip-target" title="Print" onclick="printBundle(' +dt[i].cheque_collection_id+')"><i class="fa fa-print" aria-hidden="true"></i></button>'
                data.push({
                    "date": '<div data-id="' + dt[i].cheque_collection_id + '">' + dt[i].created_date + '</div>',
                    "ref_number": '<div data-id="' + dt[i].cheque_collection_id + '">' + dt[i].external_number + '</div>',
                    "amount": parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    
                    "action": btn_print,

                });

            }

            var table = $('#direct_cheque_bundle_list').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}


function printBundle(id){
    let url = '/cb/printSFAChequeBundle/'+id;
    
   
    window.open(url, '_blank');
}