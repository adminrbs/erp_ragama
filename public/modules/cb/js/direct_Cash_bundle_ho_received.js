
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
        $('#direct_cash_bundle_list').DataTable({
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
                    width: 110,
                    targets: 1,
                    orderable: false
                },
                {
                    width: 80,
                    targets: 2,
                    orderable: false
                },
                {
                    width: 30,
                    targets: 3,
                    orderable: false
                },
                {
                    width: 120,
                    targets: 4,
                    orderable: false
                },


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
                { "data": "branch" },
                { "data": "action" },
                { "data": "check" }

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

$(document).ready(function () {
    $('#cmbBranch').on('change', function () {

        load_direct_cash_bundles($('#cmbBranch').val())
    });

    $('#btnSave').on('click',function(){
        received_direct_Bundle_head_office();
    });
    getBranches();
    load_direct_cash_bundles(0);

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
function load_direct_cash_bundles(br_id) {


    $.ajax({
        url: '/cb/load_direct_cash_bundles/' + br_id,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;


            var data = [];
            for (var i = 0; i < dt.length; i++) {
                console.log(dt[i].book);
                btn_info = '<button class="btn btn-success btn-sm tooltip-target" title="Info" onclick="showModal(' + dt[i].direct_cash_bundle_id + ')"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                chk_box = '<input type="checkbox" class="form-check-input row_checkbox" id="' + dt[i].direct_cash_bundle_id + '" onchange="singleSelect(this)">';
                data.push({
                    "date": '<div data-id="' + dt[i].direct_cash_bundle_id + '">' + dt[i].trans_date + '</div>',
                    "ref_number": '<div data-id="' + dt[i].direct_cash_bundle_id + '">' + dt[i].external_number + '</div>',
                    "amount": parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "branch": dt[i].branch_name,
                    "action": btn_info,
                    "check": chk_box

                });



            }

            var table = $('#direct_cash_bundle_list').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

function showModal(id) {
    $('#receipt_modal').modal('show');
    loadDirectReciptsToModal(id)
}

function loadDirectReciptsToModal(id) {
    var table = $('#receipts_table');
    var tableBody = $('#receipts_table tbody');
    tableBody.empty();
    $.ajax({
        url: '/cb/loadDirectReciptsToModal/' + id,
        type: 'get',
        async: false,
        success: function (response) {
            var dt = response.data;
            console.log(dt);
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').text(item.receipt_date));
                row.append($('<td>').text(item.external_number));
                row.append($('<td style="text-align:right;">').text(parseFloat(item.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));

                table.append(row);
            });
        }
    })
}

function SelectAll(event) {

    if ($(event).prop('checked')) {
        $('#direct_cash_bundle_list tbody').each(function () {
            var checkbox = $(this).find('.row_checkbox');
            checkbox.prop('checked', true);
        });
    } else {
        $('#direct_cash_bundle_list tbody').each(function () {
            var checkbox = $(this).find('.row_checkbox');
            checkbox.prop('checked', false);
        });
    }
}

function singleSelect(event) {
    if ($(event).prop('checked')) {
        var is_all_checked = true;
        $('#direct_cash_bundle_list tbody tr').each(function () {
            var checkbox = $(this).find('.row_checkbox');
            if (!checkbox.prop('checked')) { // Corrected condition
                $('#chkSelectAll').prop('checked', false);
                is_all_checked = false;
                return false; // Break the loop
            }
        });

        if (is_all_checked) {
            $('#chkSelectAll').prop('checked', true);
        }
    } else {
        $('#chkSelectAll').prop('checked', false);
    }
}


function received_direct_Bundle_head_office() {
    var checkedIds = [];
    $('#direct_cash_bundle_list tbody input[type="checkbox"]:checked').each(function () {
        checkedIds.push($(this).attr('id'));
    });

    if(checkedIds.length < 1){
        showWarningMessage("Please select at least one record");
        return false;
    }else{
        $.ajax({
            url: '/cb/received_direct_Bundle_head_office',
            type: 'post',
            contentType: 'application/json', // Ensure the content type is JSON
            data: JSON.stringify({ checkedIds: checkedIds }), // Send data as a JSON object
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.status){
                    showSuccessMessage("Record updated");
                    load_direct_cash_bundles($('#cmbBranch').val());
                }else{
                    showWarningMessage("Unable to update");
                }
            },
            error: function(data) {
                showWarningMessage("Unable to update")
            }
        });

        
        
    }

}