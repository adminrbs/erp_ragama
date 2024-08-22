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
        $('#return_transfer_list_table').DataTable({
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
                { "data": "ref_number" },
                { "data": "date" },
                { "data": "branch" },
                { "data": "from_locatin" },
                { "data": "to_location" },
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


/* --------------end of data table--------- */

$(document).ready(function () {
    getReturnTransfer();

    //tool tips
    $(function () {
        $(".tooltip-target").tooltip();
    });

    
});





function view(id) {
    url = "/sd/retrun_trnasfer?id=" + id + "&action=view" + "&task=null";
    window.location.href = url;
}


function getReturnTransfer() {
    $.ajax({
        type: "GET",
        url: "/sd/getReturnTransfer",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            var disabled = "disabled"

            for (var i = 0; i < dt.length; i++) {
                
                btn_view = '<button class="btn btn-success btn-sm tooltip-target" id="view' + dt[i].return_transfer_id + '" onclick="view(' +dt[i].return_transfer_id + ')" title="View"><i class="fa fa-eye" aria-hidden="true" ></i></button>';
                report = '<button class="btn btn-secondary btn-sm" id="btn_print' + dt[i].return_transfer_id + '"' + disabled + '><i class="fa fa-print" aria-hidden="true" ></i></button>'
                data.push({
                    "ref_number": dt[i].external_number,
                    "date": dt[i].transfer_date,
                    "branch": dt[i].branch_name,
                    "from_locatin": dt[i].from_location,
                    "to_location": dt[i].to_location,
                    "action": btn_view ,
                });

             
            }


            var table = $('#return_transfer_list_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}



