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
                searchPlaceholder: 'Type to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });


        // Left and right fixed columns
        var table = $('#model_item_table').DataTable({
            "paging": true,
            "pageLength": 50,
            columnDefs: [

                {
                    width: 80,
                    targets: 0,
                    orderable: false
                },
                {
                    width: 280,
                    targets: 1,
                    orderable: false
                },
                {
                    width: 200,
                    targets: 2,
                    orderable: false
                },
                {
                    targets: 3,
                    orderable: false
                },
                {
                    targets: 4,
                    orderable: false
                },
                {
                    targets: 5,
                    orderable: false,
                    width: 100
                },
                {
                    targets: 6,
                    orderable: false
                },




            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            info: false,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },



            "columns": [
                { "data": "item_code" },
                { "data": "item_name" },
                { "data": "pack_size" },

                { "data": "from_b_stock" },
                { "data": "to_b_stock" },
                { "data": "avg_sale" },
                { "data": "supply_group" },
                { "data": "checkbox" }






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



var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;

var sales_order_Id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
$(document).ready(function () {
    $('#btnApprove').hide();
    $('#btnReject').hide();

    $(".select2").select2({
        dropdownParent: $("#item_model")

    });
    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'DD/MM/YYYY',
        }
    });


    $('#modelBtn').on('click', function () {
        $("#item_model").modal('show');
    });
    getServerTime();


    //back button
    $('#btnBack').hide();
    $('#btnBack').on('click', function () {

        var url = "/sd/getSalesOrderList";
        window.location.href = url;
    });



    getBranches();



    //gross total
    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });


    ItemList = loadItems();
    DataChooser.addCollection("item", ['', '', '', '', ''], ItemList);



    $('#txtCustomerID').on('focus', function () {


        DataChooser.showChooser($(this), $(this), "Customer");
        $('#data-chooser-modalLabel').text('Customers');

    });



    $('select').change(function () {

        validateSelectTag(this);

    });


    $('#item_model').on('hide.bs.modal', function (e) {
        $('#selectAll').prop('checked', false);
        $('#model_item_table').DataTable().$('input[type="checkbox"]').prop('checked', false);
        var data_ = [];
        var table = $('#model_item_table').DataTable();
        table.clear();
        table.rows.add(data_).draw();

    });

    $('#item_model').on('shown.bs.modal', function (e) {
        load_supply_group();

    });



    $('#cmbBranch').on('change', function () {
        var numberOfOptions = $('#cmbBranch option').length;
        if (numberOfOptions <= 1) {
            showWarningMessage('Unable to select same branch');
            return
        } else {
            if ($(this).val() == $('#ToBranch').val()) {
                showWarningMessage('Unable to select same branch');
                var BranchValue = $('#ToBranch').val();
                var newToBranchValue = parseInt(BranchValue) + 1;
                $('#cmbBranch').val(newToBranchValue).trigger('change');
            }
        }
    });




    $('#ToBranch').on('change', function () {
        var numberOfOptions = $('#ToBranch option').length;
        if (numberOfOptions <= 1) {
            showWarningMessage('Unable to select same branch');
            return;
        } else {
            
            if ($(this).val() == $('#cmbBranch').val()) {
                showWarningMessage('Unable to select same branch');
                var BranchValue = $('#cmbBranch').val();
                var newToBranchValue = parseInt(BranchValue) + 1;
                $('#ToBranch').val(newToBranchValue).trigger('change');
            }
        }

    });



    //from list
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        sales_order_Id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];




        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#chk').hide();
            $('#btnBack').show();
        }
        else if (action == 'edit' && status == 'Original') {
            if (parseInt(order_type_status) != 0) {

                showWarningMessage('Unauthorized Access');
                var url = "/sd/getSalesOrderList";
                window.location.href = url;
                return;
            } else if (parseInt(is_order_status) != 1) {
                showWarningMessage('Unauthorized Access');
                var url = "/sd/getSalesOrderList";
                window.location.href = url;
                return;
            } else {

                $('#btnSave').text('Update');
                $('#btnSaveDraft').hide();
                $('#btnApprove').hide();
                $('#btnReject').hide();
                $('#btnBack').show();
            }


        } else if (action == 'edit' && status == 'Draft') {
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
            /*   $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();

        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
            disableComponents();

        }

        getEachSalesOrder(sales_order_Id, status);
        getEachproduct(sales_order_Id, status);
    }


    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:370px", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "focusNextLine(this)", "width": "*", },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 }
        ],
        "auto_focus": 0,
        "hidden_col": [6]


    });

    tableData.addRow();

    $('#tblData').on('input', 'input[type="text"]', function () {
        // Remove any consecutive dots
        this.value = this.value.replace(/\.+/g, '.');

        // Remove any dots except the first one
        if ((this.value.match(/\./g) || []).length > 1) {
            var parts = this.value.split('.');
            this.value = parts.shift() + '.' + parts.join('').replace(/\./g, '');
        }

        // Allow only numbers and a single dot
        this.value = this.value.replace(/[^0-9.]/g, '');
    });



    $('#btnSave').on('click', function () {
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            if (arr[i][0].attr('data-id') == "undefined") {
                showWarningMessage("Please select a correct Item");
                arr[i][0].focus();
                return;
            } else if (arr[i][7].val() == "" || arr[i][7].val() == "0" || arr[i][7].val() == "undefined" || arr[i][7].val() == "null") {
                continue;

            } else {
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": parseFloat(arr[i][7].val().replace(/,/g, '')),
                    "PackSize": arr[i][2].val(),
                    "from_branch_stock": arr[i][3].val(),
                    "to_branch_stock": arr[i][5].val(),
                    "avg_sales": arr[i][4].val(),
                }));


            }
        }

        bootbox.confirm({
            title: 'Save confirmation',
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
                //console.log('Confirmation result:', result);
                if (result) {

                    newReferanceID('internal_orders', '2400');
                    addInternalOrders(collection);

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




    })



    $('#btnApprove').on('click', function () {
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
                //console.log('Confirmation result:', result);
                if (result) {
                    approveSalesOrder(sales_order_Id);

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

    })



    //reject
    $('#btnReject').on('click', function () {
        bootbox.confirm({
            title: 'Reject confirmation',
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
                    rejectSalesOrder(Invoice_id);
                } else {

                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');


    })

    var id_array = [];
    //load supply group item
    $('#cmbSupplyGroup').on('change', function () {
        $sup_id = $(this).val();

        if ($('#chkMultiple').prop('checked')) {
            if (id_array.indexOf($sup_id) === -1) {
                // $sup_id is not in the array, so add it
                id_array.push($sup_id);
                load_supply_group_item($sup_id);
            }



        } else {
            id_array = [];
            load_supply_group_item($sup_id);
        }

    });


    $('#item_model').on('hidden.bs.modal', function () {
        $('#model_item_table tbody').empty();
    });


});


function clickx(id) {
    tableData.clear();
}

function transactionTableKeyEnterEvent(event, id) {

    if (id == 'tblData') {
        tableData.addRow();

    }

}



//add Sales order
function addInternalOrders(collection) {
    console.log(collection);



    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an item');
        return
    } else if ($('#cmbBranch').val() == $('#ToBranch').val()) {
        showWarningMessage('Internal orders can not be done within the same branch');
        $('#cmbBranch').addClass('is-invalid');
        $('#ToBranch').addClass('is-invalid');
    }
    else {

        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', referanceID);
        formData.append('order_date_time', $('#order_date_time').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('ToBranch', $('#ToBranch').val());
        formData.append('from_date', $('#from_date').val());
        formData.append('to_date', $('#to_date').val());

        $.ajax({
            url: '/sc/addInternalOrders',
            method: 'post',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSave').prop('disabled', true);
            }, success: function (response) {
                $('#btnSave').prop('disabled', false);
                var status = response.status

                if (status) {

                    showSuccessMessage("Successfully saved");
                    //  resetForm();

                    url = "/sc/internal_orders_list";
                    setTimeout(function () {
                        window.location.href = url;
                    }, 1000);


                } else {

                    showWarningMessage("Unable to save");
                }



            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {

            }
        })
        getServerTime();

    }


}





function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event, "item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

function dataChooserShowEventListener(event) {

}


function loadItems() {
    var list = [];
    $.ajax({
        url: '/sd/loadItems',
        type: 'get',
        async: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;

            }
        },
        error: function (error) {
            console.log(error);
        },

    });
    return list;
}
//load custoners






function dataChooserEventListener(event, id, value) {
    if ($(event.inputFiled).attr('id') == 'txtCustomerID') {
        loadCustomerOtherDetails(value);
        $('#lblCustomerName').val(id);

    } else {
        console.log(event.inputFiled);
        var selected = event.getSelected();
        var item_id = selected.hidden_id;
        var row_childs = event.getRowChilds();
        var hash_map = [];
        var arr = tableData.getDataSource();
        for (var i = 0; i < arr.length - 1; i++) {
            hash_map.push(arr[i][0]);
        }

        console.log(hash_map);
        if (hash_map.includes(value)) {

            showErrorMessage('Already exist ' + value);
            /* alert('Already exist '+value); */
            event.inputFiled.val('');
            return;
        }

        var from_branch_id = $('#cmbBranch').val();
        var to_branch = $('#ToBranch').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        $.ajax({
            url: '/sc/getItemInfo_internal_order/' + item_id + '/' + from_branch_id + '/' + to_branch,
            type: 'get',
            data: {
                from_date: from_date,
                to_date: to_date
            },
            success: function (response) {
                console.log(response[0].avg_sales);
                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[2]).val(response[0].package_unit);
                $(row_childs[3]).val(response[0].from_balance);
                $(row_childs[5]).val(response[0].to_balance);
                $(row_childs[4]).val(response[0].avg_sales);
                $(row_childs[6]).val(response[0].reorder_level);


                /* { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:370px", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", },
                { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 } */

            }

        });
        $('#from_date').prop('disabled', true);
        $('#to_date').prop('disabled', true);

    }

}











function getEachSalesOrder(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sd/getEachSalesOrder/' + id + '/' + status,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (salesInv) {
            console.log(salesInv);
            var res = salesInv.data;


            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#order_date_time').val(res[0].order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerName').val(res[0].customer_name);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbPaymentTerm').val(res[0].payment_term_id);
            $('#cmbDeliverType').val(res[0].deliver_type_id);
            $('#txtRemarks').val(res[0].remarks);
            $('#delivery_date_time').val(res[0].expected_date_time);
            $('#txtDeliveryInst').val(res[0].delivery_instruction);
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            $('#txtYourReference').val(res[0].your_reference_number);

            /* var cusID = txt[0].customer_id;
            $('#lblCustomerName').attr('data-id',cusID); */

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}









//approve
function approveSalesOrder(id) {
    $.ajax({
        url: '/sd/approveSalesOrder/' + id,
        type: 'post',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            /* $('#btnSave').prop('disabled', true); */
        }, success: function (response) {
            /*   $('#btnSave').prop('disabled', false);*/
            var status = response.status
            console.log(status);
            if (status) {
                showSuccessMessage("Record approved");

                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
                closeCurrentTab();
                window.opener.location.reload();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })
}

//reject
function rejectSalesOrder(id) {
    $.ajax({
        url: '/sd/rejectSalesOrder/' + id,
        type: 'post',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            /* $('#btnSave').prop('disabled', true); */
        }, success: function (response) {
            /*   $('#btnSave').prop('disabled', false);*/
            var status = response.status
            console.log(status);
            if (status) {
                showSuccessMessage("Request rejected");

                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
                closeCurrentTab();
                window.opener.location.reload();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })
}

//reset form
function resetForm() {
    $('.validation-invalid-label').empty();
    $('#form').trigger('reset');
    $('#lblGrossTotal').text('0.00');
    $('#lblNetTotal').text('0.00');
    $('#lblTotalDiscount').text('0.00');
    $('#lblTotaltax').text('0.00');


}

// clear table
function clearTableData() {
    dataSource = [];
    tableData.setDataSource(dataSource);

}


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);


}

//get server time
function getServerTime() {
    $.ajax({
        url: '/sd/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#order_date_time').val(formattedDate);



            var currentDate = new Date(formattedDate);
            // Get the first date of the month
            var firstDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            var formattedFirstDate = formatDate(firstDateOfMonth);

            // Get the last date of the month
            var lastDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            var formattedLastDate = formatDate(lastDateOfMonth);
            console.log(lastDateOfMonth);
            $('#from_date').val(formattedFirstDate);
            $('#to_date').val(formattedLastDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}






function getDeliveryTypes() {

    $.ajax({
        url: '/sd/getDeliveryTypes',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbDeliverType').append('<option value="' + value.delivery_type_id + '">' + value.delivery_type_name + '</option>');

            })

        },
    })
}

function getPaymentTerm() {
    $.ajax({
        url: '/sd/getPaymentTerm',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentTerm').append('<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>');

            })

        },
    })

}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_InternalOrders", table, doc_number);
    // $('#LblexternalNumber').val(referanceID);
}




function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#ToBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
            })
            $('#cmbBranch').trigger('change');
            $('#ToBranch').trigger('change');

            var cmbBranchValue = $('#cmbBranch').val();
            var toBranchValue = $('#ToBranch').val();

            // Ensure the values are not the same
            if (cmbBranchValue === toBranchValue) {
                // Increment the value of the second select tag
                var newToBranchValue = parseInt(toBranchValue) + 1;
                $('#ToBranch').val(newToBranchValue).trigger('change');
            }
        },
    })
}


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);
}

function formatDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1; // Months are zero-based
    var year = date.getFullYear();

    // Pad day and month with leading zeros if needed
    day = day < 10 ? '0' + day : day;
    month = month < 10 ? '0' + month : month;

    return day + '/' + month + '/' + year;
}

//load supply group
function load_supply_group() {
    $('#cmbSupplyGroup').empty();
    $.ajax({
        url: '/sc/load_supply_group',
        type: 'get',
        async: false,
        success: function (data) {
            var dt = data.data;
            $.each(dt, function (index, value) {
                $('#cmbSupplyGroup').append('<option value="' + value.supply_group_id + '">' + value.supply_group + '</option>');

            })

            $('#cmbSupplyGroup').trigger('change');

        },
    })
}

//load supply group item
function load_supply_group_item(id) {
    var from = $('#cmbBranch').val();
    var to = $('#ToBranch').val();
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    $.ajax({
        url: '/sc/load_supply_group_item/' + id + '/' + from + '/' + to,
        type: 'get',
        async: false,
        data: {
            from_date: from_date,
            to_date: to_date
        },
        success: function (data) {
            var dt = data;

            var data_ = []
            for (var i = 0; i < dt.length; i++) {
                var checkbox = '<input type="checkbox" class="form-check-input" id="' + dt[i].item_id + '" onchange="unselect(this)">'
                data_.push({
                    "item_code": dt[i].Item_code,
                    "item_name": '<div title="' + dt[i].item_Name + '">' + shortenString(dt[i].item_Name, 25) + '</div>',
                    "pack_size": dt[i].package_unit,
                    "from_b_stock": dt[i].from_balance,
                    "to_b_stock": dt[i].to_balance,
                    "avg_sale": dt[i].avg_sales,
                    "checkbox": checkbox,
                    "supply_group": dt[i].supply_group
                });



            }

            var table = $('#model_item_table').DataTable();
            if ($('#chkMultiple').prop('checked')) {
                table.rows.add(data_).draw();
            } else {
                table.clear();
                table.rows.add(data_).draw();
            }


        },
    })
}


function selectAll(event) {
    var table = $('#model_item_table').DataTable(); // Replace 'yourDataTableID' with the actual ID of your DataTable
    var checkboxes = table.rows().nodes().to$().find('td input[type="checkbox"]');

    if ($(event).prop('checked')) {
        checkboxes.prop('checked', true);
    } else {
        checkboxes.prop('checked', false);
    }

}

function unselect(event) {
    if ($(event).prop('checked')) {
        var table = $('#model_item_table').DataTable();
        var checkboxes = table.rows().nodes().to$().find('td input[type="checkbox"]');
        var allChecked = checkboxes.length > 0 && checkboxes.length === checkboxes.filter(':checked').length;
        if (allChecked) {
            $('#selectAll').prop('checked', true);
        }
    } else {
        $('#selectAll').prop('checked', false);
    }
}




//load selected items t transaction table
function loadSelectedItems() {
    if ($('#cmbBranch').val() == $('#ToBranch').val()) {
        showWarningMessage('Internal orders can not be done within the same branch');
        $('#cmbBranch').addClass('is-invalid');
        $('#cmbBranch').addClass('is-invalid');
    } else {


        var itemArray = [];
        var table = $('#model_item_table').DataTable();
        var checkboxes = table.rows().nodes().to$().find('td input[type="checkbox"]:checked');
        console.log(checkboxes);
        checkboxes.each(function () {
            var checkboxId = $(this).attr('id'); // Assuming your checkbox is within a table row (tr)
            console.log(checkboxId);
            if (checkboxId) {
                itemArray.push(checkboxId);
            }
        });

        var from = $('#cmbBranch').val();
        var to = $('#ToBranch').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        $.ajax({
            url: '/sc/loadSelectedItems/' + from + '/' + to,
            type: 'get',
            async: false,
            data: {
                itemArray: itemArray,
                from_date: from_date,
                to_date: to_date
            },
            success: function (data) {
                console.log(data);
                var dt = data.data;

                var dataSource = [];

                for (var i = 0; i < dt.length; i++) {
                    for (var j = 0; j < dt[i].length; j++) {
                        console.log(dt[i][j].avg_sales);
                        dataSource.push([
                            { "type": "text", "class": "transaction-inputs", "value": dt[i][j].Item_code, "data_id": dt[i][j].item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
                            { "type": "text", "class": "transaction-inputs", "value": dt[i][j].item_Name, "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:370px", "disabled": "disabled" },
                            { "type": "text", "class": "transaction-inputs", "value": dt[i][j].package_unit, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "", "disabled": "disabled" },
                            { "type": "text", "class": "transaction-inputs", "value": Math.abs(dt[i][j].from_balance), "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                            { "type": "text", "class": "transaction-inputs math-abs", "value": dt[i][j].avg_sales, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                            { "type": "text", "class": "transaction-inputs", "value": Math.abs(dt[i][j].to_balance), "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },

                            { "type": "text", "class": "transaction-inputs math-abs", "value": dt[i][j].reorder_level, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
                            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "focusNextLine(this)", "width": "*", },
                            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 }

                        ]);

                    }
                }

                tableData.setDataSource(dataSource);
                $("#item_model").modal('hide');

            },
        })
    }
}


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}


function TableRefresh() {
    var table = $('#model_item_table').DataTable();
    table.columns.adjust().draw();
}

function focusNextLine(event){

   
    var current_row = $($(event).parent()).parent();
    var next_row = current_row.next();
    var previous_row = current_row.prev();
   
    
    
    
    
}