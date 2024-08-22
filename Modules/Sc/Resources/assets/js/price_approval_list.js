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
        $('#price_approval_list').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            columnDefs: [
                {
                    width: 110,
                    targets: 0
                },
                {
                    width: 100,
                    targets: 1
                },
                {
                    orderable: false,
                    width: 200,
                    targets: 2
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 3
                },
                {
                    orderable: false,
                    width: 50,
                    targets: 4
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 5
                },
                {
                    orderable: false,
                    width: 150,
                    targets: 6
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 7
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 8
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 9
                },



            ],
            scrollX: true,
            /*  scrollY: 600, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 3
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "date" },
                { "data": "external_number" },
                { "data": "item_code" },
                { "data": "item_name" },
                { "data": "pacs" },
                { "data": "qty" },
                { "data": "location" },
                { "data": "wh_price" },
                { "data": "retail" },
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
    /* get_price_approval_list();
 */
    $('#cmbLocation').on('change', function () {
        get_price_approval_list($(this).val());
    })
    getLocation();
    $('#cmbLocation').change();
});

function approve(id) {
    bootbox.confirm({
        title: 'Approval confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-warning'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-link'
            }
        },
        callback: function (result) {
            
            if (result) {
                approve_price(id);

            } else {

            }
        },
        onShow: function () {
            $('#question-icon').addClass('swipe-question');
        },
        onHide: function () {
            $('#question-icon').removeClass('swipe-question');
        }, 
    });

    $('.bootbox').find('.modal-header').addClass('bg-warning text-white');
    

}

function approve_price(id) {
    $.ajax({
        type: "POST",
        url: "/sc/approve_price/" + id,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () { },
        success: function (response) {

            var status = response.status;
            if(status){
                showSuccessMessage('Record Approved');
                get_price_approval_list($('#cmbLocation').val());
            }else{
                showWarningMessage('Unable to approve');
            }

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}




function get_price_approval_list(id) {
    $.ajax({
        type: "GET",
        url: "/sc/get_price_approval_list/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () {
            
         },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            var disabled = "";

            for (var i = 0; i < dt.length; i++) {

                btnApprove = '<button class="btn btn-success btn-sm" id="btnEdit_' + dt[i].item_history_setoff_id + '" onclick="approve(' + dt[i].item_history_setoff_id + ')" title="Approve"><i class="fa fa-check-square-o" aria-hidden="true" ></i></button>'
                data.push({
                    "date": dt[i].transaction_date,
                    "external_number": dt[i].external_number,
                    "item_code": shortenString(dt[i].Item_code, 15),
                    "item_name": dt[i].item_Name,
                    "pacs": dt[i].package_unit,
                    "qty": dt[i].quantity,
                    "location": dt[i].location_name,
                    "wh_price":parseFloat(dt[i].whole_sale_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }),
                    "retail":parseFloat(dt[i].retial_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }),
                    "action": btnApprove
                });

               


            }


            var table = $('#price_approval_list').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

var br_id_array = [];
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {

                br_id_array.push(value.branch_id);

            })

        },
    });

    return br_id_array;
}

function getLocation() {
    var id_array = getBranches();
    $.ajax({
        url: '/sc/getLocation_price_confirm',
        type: 'get',
        data: {
            id_array: id_array
        },
        async: false,
        success: function (data) {
            var dt = data[0];
            console.log(data);
            $.each(dt, function (index, value) {
                console.log(value.location_id);
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');


            })

        },
    })
}


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}