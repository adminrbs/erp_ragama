
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
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                    'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
                }
            }

        });

        // Left and right fixed columns
        $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "20px");
            },
            columnDefs: [{
                orderable: false,
                targets: 2
            },
            {
                width: 80,
                height: 20,
                targets: 0
            },
            {
                width: 80,
                height: 20,
                targets: 1,

            },
            {
                width: '86%',
                height: 20,
                targets: 2,

            },
            {
                width: 80,
                height: 20,
                targets: 3,

            },
            {
                width: 30,
                height: 20,
                targets: 4,

            },
            {
                width: 75,
                height: 20,
                targets: 5,

            },
            {
                width: 80,
                height: 20,
                targets: 6,

            },
            {
                width: 80,
                height: 20,
                targets: 7,

            },
            {
                width: 80,
                height: 20,
                targets: 8,

            },
            {
                "targets": '_all',
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).css('padding', '2px');
                }
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
                {
                    "data": "referance"
                },
                {
                    "data": "date"
                },
                {
                    "data": "item",
                },
                {
                    "data": "batch",

                },
                {
                    "data": "qty",

                },
                {
                    "data": "cost_price",
                    className: "right-align",
                },
                {
                    "data": "wholesale_price",
                    className: "editable right-align",

                },
                {
                    "data": "retail_price",
                    className: "editable right-align",

                },
                {
                    "data": "action",

                }

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


    // Default initialization
    $('.select2').select2();
    // End of Default initialization

    $('.select2').on('change',function(){
        getBatchData();
        $('.editable').attr('contenteditable', true);
    });

    $('.editable').on('click', function () {
        $(this).addClass('editing');
    });
    loadFilters();
    getBatchData();

    //table click event
    $('#batchPriceTable').on('click', 'tr', function (e) {

       
        $('.editable').attr('contenteditable', true);
      
      

    });
});






function loadFilters() {
    $.ajax({
        type: "GET",
        url: '/tools/get_filter_data',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                var filterData = response.data;
                var supplyGroup = filterData.supplyGroup;
                var branch = filterData.branch;
                var item = filterData.item;


                /** Append supply group */
                $('#cmbSupplyGroup').empty();
                $('#cmbSupplyGroup').append('<option value="any">Any</option>');
                for (var i = 0; i < supplyGroup.length; i++) {
                    var supply_group_id = supplyGroup[i].supply_group_id;
                    var supply_group_name = supplyGroup[i].supply_group;
                    $('#cmbSupplyGroup').append('<option value="' + supply_group_id + '">' + supply_group_name + '</option>');
                }
                /** End of Suppli group */



                /** Append Branch */
                $('#cmbBranch').empty();
                $('#cmbBranch').append('<option value="any">Any</option>');
                for (var i = 0; i < branch.length; i++) {
                    var branch_id = branch[i].branch_id;
                    var branch_name = branch[i].branch_name;
                    $('#cmbBranch').append('<option value="' + branch_id + '">' + branch_name + '</option>');
                }
                /** End of Branch */



                /** Append Product */
                $('#cmbItem').empty();
                $('#cmbItem').append('<option value="any">Any</option>');
                for (var i = 0; i < item.length; i++) {
                    var item_id = item[i].item_id;
                    var item_name = item[i].item_Name;
                    $('#cmbItem').append('<option value="' + item_id + '">' + item_name + '</option>');
                }
                /** End of Product */
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}


function getBatchData() {

    $('#batchPriceTableBody').empty();

    var filters =
    {
        "supply_group": $('#cmbSupplyGroup').val(),
        "branch": $('#cmbBranch').val(),
        "item": $('#cmbItem').val()
    };

    var formData = new FormData();
    formData.append("filters", "xxx");


    $.ajax({
        type: "GET",
        url: '/tools/getBatchData/' + JSON.stringify(filters),
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                var result = response.data;
                var data = [];
                for (var i = 0; i < result.length; i++) {

                    var id = result[i].item_history_setoff_id;
                    var btn_update = '<button class="btn btn-primary btn-sm btn-action" onclick="updateBatchPrice(this)">Update</button>';

                    data.push({
                        "referance": '<label data-id = "' + id + '">' + result[i].external_number + '</label>',
                        "date": result[i].transaction_date,
                        "item": result[i].item_Name,
                        "batch": result[i].batch_number,
                        "qty":Math.abs(result[i].qty),
                        "cost_price": parseFloat(result[i].cost_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                        "wholesale_price": parseFloat(result[i].whole_sale_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                        "retail_price": parseFloat(result[i].retial_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                        "action": btn_update,
                    });
                }
                var table = $('#batchPriceTable').DataTable();
                table.clear();
                table.rows.add(data).draw();
                $('.editable').attr('contenteditable', true);
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}


function markedAsEdit(event) {
    $(event).css('color', 'red');
}

function updateBatchPrice(event) {

    var row = $($(event).parent()).parent();
    console.log(row);
    var row_childs = row.children();

    if ($(row_childs[6]).text().trim().length === 0) {
        $(row_childs[6]).focus();
        showWarningMessage('Invalied Wholesale price');
        return;
    }

    if ($(row_childs[7]).text().trim().length === 0) {
        $(row_childs[7]).focus();
        showWarningMessage('Invalied Retail price');
        return;
    }

    var id = $($(row_childs[0]).children()[0]).attr('data-id');


    $.ajax({
        url: '/tools/updateBatchPrice/' + id,
        method: 'PUT',
        enctype: 'multipart/form-data',
        data: {
            "item_setoff_id": $(row_childs[0]).attr('data-id'),
            "whole_sale_price": parseFloat($(row_childs[6]).text().replace(/,/g, '')),
            "retail_price": parseFloat($(row_childs[7]).text().replace(/,/g, '')),

        },
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            if (response.status) {
                $(row_childs[6]).css('color', 'black');
                $(row_childs[7]).css('color', 'black');
                showSuccessMessage('Batch Price has been updated');
            } else {
                showErrorMessage('Something went wrong');
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}

