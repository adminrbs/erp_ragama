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
                    width: 200,
                    targets: 0
                },
                {
                    width: 100,
                    targets: 1
                },
                {
                    width: 100,
                    targets: 2
                },
                {
                    width: 100,
                    targets: 3
                },
                {
                    orderable:false,
                    width: 200,
                    targets: 4,
                    class: 'text-right'
                },
                {
                    width: 200,
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
                { "data": "date" },
                { "data": "code" },
                { "data": "name" },
                { "data":"amount" },
                { "data":"user" },
                { "data":"branch_name"},
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
    get_transaction_allocation_details();

});


function showInfoModel(id) {
    
    $('#hiddenItem').val(id);
    $('#transaction_allocation_model').modal('show');
    load_info(id);
   

}

function load_info(id){
    $("#transaction_allocation_table tbody").empty();
    $.ajax({
        url: '/sl/load_info/' + id,
        method: 'get',
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            var dt = response.data;
           
           console.log(dt);
            $.each(dt, function (index, value) {
                var newRow = $("<tr>");

              
                var amount_ = parseFloat(Math.abs(value.set_off_amount)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, });
              
                newRow.append("<td>" + value.reference_external_number + "</td>");
                newRow.append("<td style='text-align:right;'>" + amount_ + "</td>");
                $("#transaction_allocation_table tbody").append(newRow);


            });

                
                   




        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    });


}

function get_transaction_allocation_details() {
    $.ajax({
        type: "GET",
        url: "/sl/get_transaction_allocation_details",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            var disabled = "";

            for (var i = 0; i < dt.length; i++) {
                btn_info = '<button class="btn btn-success btn-sm tooltip-target" title="Info" onclick="showInfoModel(' + dt[i].supplier_transaction_alocation_id + ')"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                data.push({
                    "external_number": dt[i].external_number,
                    "date": dt[i].created_date,
                    "code": dt[i].supplier_code,
                    "name": dt[i].supplier_name,
                    "amount": parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }),
                    "user":dt[i].employee_name,
                    "branch_name":dt[i].branch_name,
                    "action":btn_info
                    
                });


               /*  { "data": "external_number" },
                { "data": "date" },
                { "data":"amount" },
                { "data": "action" } */
            }


            var table = $('#transaction_allocation_list').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}




function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}