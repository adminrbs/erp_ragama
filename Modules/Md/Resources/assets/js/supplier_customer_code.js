const DatatableFixedColumns = function () {

    //$('#frm').trigger("reset");
    //
    // Setup module components
    //

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
            columnDefs: [
                {
                    orderable: false,
                    width: 200,
                    targets: 2
                },
                {
                    width: 400,
                    targets: 0
                },
                {
                    width: 100,
                    targets: 3
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 100,
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
                { "data": "customer" },
                { "data": "customer_code" },
                { "data": "branch" },
                { "data": "action" },


            ], "stripeClasses": ['odd-row', 'even-row'],
        });



    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();
///.................... Item Loard..........................--------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});























$(document).ready(function () {

    $('.select2').select2();
    loadBranches();


    $('#btnAdd').on('click', function (e) {
        if ($('#cmbBranch').val() == "" || $('#cmbBranch').val() == null) {
            showWarningMessage("Please select branch");
            return;
        }

        $('#supplierCustomerForm').trigger('reset');
        $('#modelCustomer').modal('toggle');
        $('#txtBranch').val($('#cmbBranch option:selected').text());
        $('#txtBranch').attr('data-id', $('#cmbBranch').val());
        $('#btnSaveModal').show();
        $('#btnUpdateModal').hide();
        loadCustomers();

        $('#cmbCustomer').select2({
            dropdownParent: $('#modelCustomer') // Important to set the parent of the dropdown
        });
    });

    $('#btnCloseModal').on('click', function () {
        $('#modelCustomer').modal('hide');
    });


    $('#btnSaveModal').on('click', function () {
        save();
    });

    $('#btnUpdateModal').on('click', function () {
        update();
    });

    $('#cmbCustomer').on('change', function () {
        isExistingRecord();
    });

    viewAllData();
});



function loadBranches() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/loadBranches",
        success: function (data) {
            if (data.status) {

                var html = '<option selected disabled>--Select Here--</option>';
                $.each(data.data, function (key, value) {
                    html = html + "<option value=" + value.branch_id + ">" + value.branch_name + "</option>"
                });
                $('#cmbBranch').html(html);
            }
        }

    });
}



function loadCustomers() {

    $.ajax({
        type: "get",
        async: false,
        dataType: 'json',
        url: "/md/loadCustomers",
        success: function (data) {
            if (data.status) {

                var html = '<option selected disabled>--Select Here--</option>';
                $.each(data.data, function (key, value) {
                    html = html + "<option value=" + value.customer_id + ">" + value.customer_name + "</option>"
                });
                $('#cmbCustomer').html(html);
            }
        }

    });
}



function isExistingRecord() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/isExistingRecord/" + $('#cmbCustomer').val() + "/" + $('#cmbBranch').val(),
        success: function (data) {
            if (data.status) {
                if (data.data != "") {
                    $('#btnSaveModal').hide();
                    $('#btnUpdateModal').show();
                    $('#txtCustomerCode').val(data.data);
                } else {
                    $('#btnSaveModal').show();
                    $('#btnUpdateModal').hide();
                    $('#txtCustomerCode').val("");
                }

            }
        }

    });
}




function save() {


    if ($('#cmbCustomer').val() == "" || $('#cmbCustomer').val() == null) {
        showWarningMessage("Please select customer");
        return;
    }

    if ($('#txtCustomerCode').val().trim() == "") {
        showWarningMessage("Please enter customer code");
        return;
    }

    var formData = new FormData();
    formData.append("customer_id", $('#cmbCustomer').val());
    formData.append("customer_code", $('#txtCustomerCode').val());
    formData.append("branch_id", $('#txtBranch').attr('data-id'));

    $.ajax({
        url: '/md/saveSupplierCustomerCode',
        method: 'POST',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        async: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                showSuccessMessage("Successfully saved");
                $('#modelCustomer').modal('hide');
            } else {
                showErrorMessage('Something went wrong');
            }


        }, error: function (data) {

        }, complete: function () {
            viewAllData();
        }
    });
}



function update() {


    if ($('#cmbCustomer').val() == "" || $('#cmbCustomer').val() == null) {
        showWarningMessage("Please select customer");
        return;
    }

    if ($('#txtCustomerCode').val().trim() == "") {
        showWarningMessage("Please enter customer code");
        return;
    }

    var formData = new FormData();
    formData.append("customer_id", $('#cmbCustomer').val());
    formData.append("customer_code", $('#txtCustomerCode').val());
    formData.append("branch_id", $('#txtBranch').attr('data-id'));

    $.ajax({
        url: '/md/updateSupplierCustomerCode',
        method: 'POST',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        async: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                showSuccessMessage("Successfully updated");
                $('#modelCustomer').modal('hide');
            } else {
                showErrorMessage('Something went wrong');
            }


        }, error: function (data) {

        }, complete: function () {
            viewAllData();
        }
    });
}




function edit(customer_id, branch_id) {

    getSupplierCustomerData(customer_id, branch_id, true);
}


function view(customer_id, branch_id) {
    getSupplierCustomerData(customer_id, branch_id, false);
}


function delete_confirem(customer_id, branch_id) {

    bootbox.confirm({
        title: 'Delete confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i class="fa fa-times fa-5x text-danger" ></i></div><div class="d-flex justify-content-center align-items-center "><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-Danger'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-link'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                delete_(customer_id, branch_id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
}


function delete_(customer_id, branch_id) {

    $.ajax({
        url: '/md/deleteSupplierCustomerCode/' + customer_id + "/" + branch_id,
        type: 'delete',
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response;
            if (status) {
                showSuccessMessage("Successfully deleted");

            } else {
                showErrorMessage("Something went wrong")
            }

            viewAllData();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })
}





function viewAllData() {


    $.ajax({
        type: "GET",
        url: "/md/viewAllData",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var customer_id = "'" + dt[i].customer_id + "'";
                var branch_id = "'" + dt[i].branch_id + "'";
                var action_edit = '<button class="btn btn-primary btn-sm" onclick="edit(' + customer_id + ',' + branch_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
                var action_view = '<button class="btn btn-success btn-sm" onclick="view(' + customer_id + ',' + branch_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                var action_delete = '<button class="btn btn-danger btn-sm" onclick="delete_confirem(' + customer_id + ',' + branch_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>';

                var action = action_edit + '&nbsp;' + action_view + '&nbsp;' + action_delete;
                data.push({

                    "customer": dt[i].customer_name,
                    "customer_code": dt[i].supplier_customer_code,
                    "branch": dt[i].branch_name,
                    "action": action,
                });
            }


            var table = $('#supplierCustomerCodeTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}



function getSupplierCustomerData(customer_id, branch_id, bool) {


    loadCustomers();
    $.ajax({
        type: "get",
        async: false,
        dataType: 'json',
        url: "/md/getSupplierCustomerData/" + customer_id + "/" + branch_id,
        success: function (data) {
            if (data.status) {
                if (data.data != "") {
                    $('#modelCustomer').modal('toggle');
                    $('#btnSaveModal').hide();
                    $('#btnUpdateModal').hide();
                    $('#txtCustomerCode').val(data.data.supplier_customer_code);
                    $('#cmbCustomer').val(data.data.customer_id);
                    $('#txtBranch').val(data.data.branch_name);
                    $('#txtBranch').attr('data-id', data.data.branch_id);
                    if (bool) {
                        $('#btnUpdateModal').show();
                    }
                } else {
                    showErrorMessage('Something went wrong');
                }

            }
        }

    });
}