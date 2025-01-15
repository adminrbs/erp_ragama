/* ----------data table---------------- */
const DatatableFixedColumns = function () {

    // Basic Datatable examples
    const _componentDatatableFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }


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
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },


            columnDefs: [
                {
                    width: 200,
                    targets: 0,
                    orderable: false
                },
                {
                    width: '100%',
                    targets: 1,
                    orderable: false
                },
                {
                    width: 80,
                    targets: 5,
                    orderable: false
                },
                {
                    width: 80,
                    targets: 6,
                    orderable: true

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
                { "data": "name" },
                { "data": "username" },
                { "data": "browser" },
                { "data": "remark" },
                { "data": "status" },
                { "data": "confirm" },
                { "data": "inactive" },


            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });
        table.column(9).visible(false);

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
    approvalRequestList();

    $('#cmbRequest').on('change', function () {
        if ($(this).val() == 0) {
            $('#num_time').val(0);
            $('#div_num_time').hide();
        } else {
            $('#num_time').val(1);
            $('#div_num_time').show();
        }
    });
});


function approvalRequestList() {

    $.ajax({
        type: "GET",
        url: '/approvalRequestList',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var data = [];
            for (var i = 0; i < response.length; i++) {
                var str_id = "'" + response[i].id + "'";
                var status = "Activated";
                if (response[i].approval == 0) {
                    status = "Inactive";
                }

                data.push({
                    "name": response[i].name,
                    "username": response[i].email,
                    "browser": response[i].browser,
                    "remark": response[i].remark,
                    "status": status,
                    "confirm": '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setRequestID(' + str_id + ')">Confirm</button>',
                    "inactive": '<button type="button" class="btn btn-danger" data-bs-target="#exampleModal" onclick="inactiveRequest(' + str_id + ')">Inactive</button>',

                });

            }

            var table = $('#tbl_request').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}



function setRequestID(id) {

    $('#hid_request_id').val(id);
}



function confirmRequest() {
    var request_id = $('#hid_request_id').val();

    var formData = new FormData();
    formData.append('request_id', request_id);
    formData.append('status', $('#cmbRequest').val());
    formData.append('time', $('#num_time').val());
    formData.append('remark', $('#txtRemark').val());

    console.log(formData);
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/confirmRequest',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            location.href = "/approval_request_list";


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}


function inactiveRequest(id) {

    bootbox.confirm({
        title: 'Inactive confirmation',
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
            console.log(result);
            if (result) {
                $.ajax({
                    type: "GET",
                    url: '/inactiveRequest/' + id,
                    processData: false,
                    contentType: false,
                    cache: false,
                    timeout: 800000,
                    timeout: 800000,
                    beforeSend: function () {

                    },
                    success: function (response) {
                        console.log(response);
                        location.href = "/approval_request_list";
                    },
                    error: function (error) {
                        console.log(error);

                    },
                    complete: function () {

                    }

                });

            } else {

            }
        },
        onShow: function () {
            $('#question-icon').addClass('swipe-question');
        },
        onHide: function () {
            $('#question-icon').removeClass('swipe-question');
        }
    });

    $('.bootbox').find('.modal-header').addClass('bg-warning text-white');


}