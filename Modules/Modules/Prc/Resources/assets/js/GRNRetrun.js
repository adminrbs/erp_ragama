var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var GRNID = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
var foc_qty_threshold_from_pick_orders = undefined;
$(document).ready(function () {
    $('#batchModelTitle').hide();
    $('#lblBalance').hide();
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();

    ItemList = loadItems();
    loadPamentType();
    getServerTime();
    suppliers = loadSupplierTochooser();

    DataChooser.addCollection("Suppliers", ['Supplier Name', 'Supplier Code', '', '', ''], suppliers);
    DataChooser.addCollection("item", ['', '', '', '', ''], ItemList);

    $('#btnBack').hide();

    //back
    $('#btnBack').on('click', function () {
        if (task == "approval") {
            var url = "/prc/goodReceiveReturnList";
            window.location.href = url;
        } else {
            var url = "/prc/goodReceiveReturnList";
            window.location.href = url;
        }


    });

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    })


    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });

    getBranches();
    $('#cmbBranch').change();


    //setting datat to data chooser -supplier
    $('#txtSupplier').on('focus', function () {
        DataChooser.showChooser($(this), $(this), "Suppliers");
        $('#data-chooser-modalLabel').text('Suppliers');

    });

    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });

    var hiddem_col_array = [5, 9,16,17,18];
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        GRNID = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#btnBack').show();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();

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
            hiddem_col_array = [5, 9, 15, 14, 13, 12,16,17,18];
            disableComponents();

        }
        /*  getEachPurchasingOrder(reuqestID, status);
         getEachproduct(reuqestID, status);
         getEachOther(reuqestID, status); */
        getEachGR_rtn(GRNID, status);
        getEachproduct(GRNID);

    }


    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "", "valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:350px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:55px;text-align:right;", "event": "calValueandCostPrice(this);getItemID(this);", "compulsory": true,"disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:55px;text-align:right;", "event": "calValueandCostPrice(this);getItemID(this);","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:150px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:150px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
        ],
        "auto_focus": 0,
        "hidden_col": hiddem_col_array


    });

    tableData.addRow();

    $('#tblData').on('input', 'input[type="text"]', function () {
        var cellIndex = $(this).closest('td').index();

        if (cellIndex === 11) {
            // Allow numbers, '.', and letters for cell index 13
            this.value = this.value.replace(/[^0-9A-Za-z.]/g, '');
            var dotCount = (this.value.match(/\./g) || []).length;
            if (dotCount > 1) {
                this.value = this.value.replace(/\.+$/, '');
            }

            // Remove any dots except the first one
            if ((this.value.match(/\./g) || []).length > 1) {
                var parts = this.value.split('.');
                this.value = parts.shift() + '.' + parts.join('').replace(/\./g, '');
            }
        } else if (cellIndex === 14) {
            // Allow numbers and hyphens for cell index 14
            this.value = this.value.replace(/[^0-9-]/g, '');
        } else {
            // Allow only numbers and dots for other cell indexes
            this.value = this.value.replace(/[^0-9.]/g, '');
            var dotCount = (this.value.match(/\./g) || []).length;
            if (dotCount > 1) {
                this.value = this.value.replace(/\.+$/, '');
            }

            // Remove any dots except the first one
            if ((this.value.match(/\./g) || []).length > 1) {
                var parts = this.value.split('.');
                this.value = parts.shift() + '.' + parts.join('').replace(/\./g, '');
            }
        }
    });


    $('#btnSave').on('click', function () {
        

        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            if (arr[i][0].attr('data-id') == "undefined") {
                showWarningMessage("Please select a correct Item");
                arr[i][0].focus();
                return;
            } else if (arr[i][2].val() == "" || arr[i][2].val() == "0" || arr[i][2].val() == "undefined" || arr[i][2].val() == "null") {
                showWarningMessage("Quantity must be greter than 0");
                return;
            } else if (arr[i][7].val() == "" || arr[i][7].val() == "0" || arr[i][2].val() == "undefined" || arr[i][2].val() == "null" || parseFloat(arr[i][7].val()) == 0) {
                showWarningMessage("Price must be greter than 0");
                return;
            } else {
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "uom": arr[i][4].val(),
                    "PackUnit": arr[i][6].val(),
                    "PackSize": arr[i][5].val(),
                    "free_quantity": arr[i][3].val(),
                    "price": parseFloat(arr[i][7].val().replace(/,/g, '')),
                    "discount_percentage": arr[i][8].val(),
                    "discount_amount": arr[i][9].val(),
                    "whole_sale_price": parseFloat(arr[i][16].val().replace(/,/g, '')),
                    "retial_price": parseFloat(arr[i][17].val().replace(/,/g, '')),
                    "batch_number": arr[i][13].val(),
                    "expire_date": arr[i][14].val(),
                    "cost_price": parseFloat(arr[i][18].val().replace(/,/g, '')),
  

                }));


            }
        }
        calculation();
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
                    if ($('#btnSave').text() == 'Save and Send') {
                        newReferanceID('goodreceivereturns', 130);
                        addGR_Return(collection, GRNID);
                    } else if ($('#btnSave').text() == 'Update') {
                        updateGRReturn(collection, GRNID);

                    }
                    getServerTime();
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

    $('#btnSaveDraft').on('click', function () {
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            collection.push(JSON.stringify({
                "item_id": arr[i][0].attr('data-id'),
                "item_name": arr[i][1].val(),
                "qty": arr[i][2].val(),
                "uom": arr[i][4].val(),
                "PackUnit": arr[i][6].val(),
                "PackSize": arr[i][5].val(),
                "free_quantity": arr[i][3].val(),
                "price": arr[i][7].val(),
                "discount_percentage": arr[i][8].val(),
                "discount_amount": arr[i][9].val(),
                "whole_sale_price": arr[i][11].val(),
                "retial_price": arr[i][12].val(),
                "batch_number": arr[i][13].val(),
                "expire_date": arr[i][14].val(),
                "cost_price": arr[i][15].val(),
            }));


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
                    if ($('#btnSaveDraft').text() == 'Save Draft') {
                        /*    newReferanceID('goods_received_note_drafts',130); */
                        addGR_ReturnDraft(collection);

                    } else if ($('#btnSaveDraft').text() == 'Update Draft') {


                        updateGR_Return_Draft(collection, GRNID);

                    }
                    getServerTime();
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
                    approveRequest(GRNID);

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
                    rejectRequestGRReturn(GRNID);
                } else {

                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');


    })


    $('#btn_add_row').on('click',function(){
        tableData.addRow();
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

//enable date
function enableDate(event) {
    /*  var length = $(event).val().replace('-', '').length;

   if ((length <= 4) && (length % 2 == 0)) {
       $(event).val($(event).val() + '-');
   } 
 */
    var value = $(event).val();

    if (value.length === 4) {
        value += '-';
        $(event).val(value);
    }

    if (value.length === 7) {
        value += '-';
        $(event).val(value);
    }


}
//loading branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })

        },
    })
}


//loading location
function getLocation(id) {
    $('#cmbLocation').empty();
    $.ajax({
        url: '/prc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })

        },
    })
}



//add grn data
function addGR_Return(collection, id) {
    var save_data = getArrayData(global_save_array);
    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an item');
        return
    }
    var return_result = _validation($('#txtSupplier'), $('#txtSupplier'));
    if (return_result) {
        showWarningMessage("Please fill all required fields");
        return;
    } else {
        var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
        formData.append('collection', JSON.stringify(collection));
        formData.append('setOffArray',JSON.stringify(save_data));
        
        formData.append('LblexternalNumber', referanceID);
        formData.append('goods_received_date_time', $('#goods_received_date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('txtSupplier', $('#txtSupplier').data('id'));
        formData.append('lblSupplierName', $('#lblSupplierName').text());
        formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
        /*  formData.append('txtPurchaseORder', $('#txtPurchaseORder').val()); */
        formData.append('txtSupplierInvoiceNumber', $('#txtSupplierInvoiceNumber').val());
        /*  formData.append('dtPaymentDueDate', $('#dtPaymentDueDate').val()); */
        /* formData.append('cmbPaymentType', $('#cmbPaymentType').val()); */
        formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
        formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
        /* formData.append('txtAdjustmentAmount', $('#txtAdjustmentAmount').val()); */
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('lblNetTotal', total_amount);

        $.ajax({
            url: '/prc/addGRReturn/' + id,
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
             //   $('#btnSave').prop('disabled', true);
            }, success: function (response) {
                console.log(response);
            //    $('#btnSave').prop('disabled', false);
                var status = response.status
                var primaryKey = response.primaryKey;
                var msg = response.message
                if (msg == 'insuficent') {
                    showWarningMessage("Insufficent Balance");
                    return;
                } else if (msg == 'qty_zero') {
                    showWarningMessage("Quantity should be greater than 0");
                    return;
                } else if (msg == 'set_off_zero') {
                    showWarningMessage("Set off quantity should be greater than 0");
                    return;
                }
                if (msg == 'insuficent') {
                    showWarningMessage("Insufficent Balance");
                    return;
                }
                if (status) {
                    showSuccessMessage("Successfully saved");
                    resetForm();
                    /* clearTableData();
                    tableData.addRow(); */
                    var url = "/prc/goodReceiveReturnList";
                    window.location.href = url;

                } else {

                    showErrorMessage("Something went wrong");
                }

            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {

            }
        })
    }
}

//add GRN draft
function addGR_ReturnDraft(collection) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', referanceID);
    formData.append('goods_received_date_time', $('#goods_received_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').data('id'));
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
    formData.append('txtPurchaseORder', $('#txtPurchaseORder').val());
    formData.append('txtSupplierInvoiceNumber', $('#txtSupplierInvoiceNumber').val());
    formData.append('dtPaymentDueDate', $('#dtPaymentDueDate').val());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtAdjustmentAmount', $('#txtAdjustmentAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());

    $.ajax({
        url: '/prc/addGRReturnDraft',
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
            var primaryKey = response.primaryKey;
            if (status) {
                showSuccessMessage("Successfully saved");
                resetForm();
                clearTableData();
                tableData.addRow();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}

/* function itemEventListner(event){

    console.log(ItemList);
    DataChooser.setDataSourse(['','','',''],ItemList);
    DataChooser.showChooser(event,event);
    $('#data-chooser-modalLabel').text('Items');
} */

function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event, "item");
        $('#data-chooser-modalLabel').text('Items');
    }
}


//load item
function loadItems() {
    var list = [];
    $.ajax({
        url: '/prc/loadItems',
        type: 'get',
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;
                /* DataChooser.setDataSourse(itemData); */
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return list;
}

//load item
function loadSupplierTochooser() {

    var data = [];
    $.ajax({
        url: '/prc/loadSupplierTochooser',
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        success: function (response) {
            if (response) {
                var supplierData = response.data;
                console.log(supplierData);
                /*  DataChooser.setDataSourse(supplierData); */
                data = supplierData;
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return data;
}
//load supplier other details
function loadSupplierOtherDetails(id) {

    $.ajax({
        url: '/prc/loadSupplierOtherDetails/' + id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            console.log(data)
            var txt = data.data;
            $('#lblSupplierAddress').val(txt[0].primary_address);
            var supID = txt[0].supplier_id;
            $('#txtSupplier').attr('data-id', supID);
            $('#txtSupplierInvoiceNumber').focus();

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load payment type
function loadPamentType() {
    $.ajax({
        url: '/prc/loadPamentType',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentType').append('<option value="' + value.supplier_payment_method_id + '">' + value.supplier_payment_method + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })


}

function dataChooserEventListener(event, id, value) {
    var branch_id = $('#cmbBranch').val();
    var location_id_ = $('#cmbLocation').val();
    if ($(event.inputFiled).attr('id') == 'txtSupplier') {
        loadSupplierOtherDetails(value);
        $('#lblSupplierName').text(id);
    } else {
        console.log(event.inputFiled);
        var selected = event.getSelected();
        var item_id = selected.hidden_id;
        var row_childs = event.getRowChilds();
        // validate transacation table and hashmap
        var hash_mapx = new HashMap();
        var table_data_source = tableData.getDataSourceObject();
        for (var i = 0; i < table_data_source.length; i++) {
            var data_id = $(table_data_source[i][0]).attr('data-id');
            var item = hash_map.get(data_id);
            hash_mapx.put(data_id, item);
        }
        hash_map = hash_mapx;
        var itm = hash_map.get(item_id);
        var boolean = false;
        if (hash_map.get(item_id) != undefined) {
            for (var i = 0; i < table_data_source.length; i++) {
                var data_id = $(table_data_source[i][0]).attr('data-id');
                var row_item = hash_map.get(data_id);

                if (itm.wolesale_price == row_item.wolesale_price) {
                    boolean = true;
                    break;
                }

            }

            if (boolean) {
                showWarningMessage('Already exist');
                return;
            }


        }
        // end of validate transacation table and hashmap
        resetTransactionTableRow(row_childs);
        //var pr_price_ = get_Pr_price(item_id)

        $.ajax({
            url: '/prc/getItemInfotogrnReturn/' + branch_id + '/' + item_id + '/' + location_id_,
            type: 'get',
            success: function (response) {
                console.log(response);


                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[4]).val(response[0].unit_of_measure);
                $(row_childs[6]).val(response[0].package_unit);
                $(row_childs[5]).val(response[0].package_size);
                $(row_childs[12]).val(response[0].Balance);
                $(row_childs[7]).val(response[0].previouse_purchase_price);

                $(row_childs[2]).focus();
            }
        })

    }

    hash_map_array = [];
}



/* //discount amount calcution
function discountAmount(event) {

    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var cellData_price = $(cell[7]).children()[0];
    var originalPrice = $(cellData_price).val();

    var qty = $(cell[2]).children()[0];
    var originalqty = $(qty).val();

    var freeQTY = $(cell[3]).children()[0];
    var originalFreeQTY = $(freeQTY).val();

    var cellData_discountPrecentage = $(cell[8]).children()[0];
    var discountPrecenage = $(cellData_discountPrecentage).val();

    //formulas
    var discountAmount = (originalPrice / 100) * discountPrecenage;
    var value = (originalqty * originalPrice) - discountAmount;
    var costPrice = value / (originalqty + originalFreeQTY);


    $($(cell[9]).children()[0]).val(discountAmount);
    $($(cell[10]).children()[0]).val(value);
    $($(cell[15]).children()[0]).val(costPrice)

}

//disocunt precentage calculation
function discountPrecentage(event) {

    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var cellData_price = $(cell[7]).children()[0];
    var originalPrice = $(cellData_price).val();

    var qty = $(cell[2]).children()[0];
    var originalqty = $(qty).val();


    //formulas
    var freeQTY = $(cell[3]).children()[0];
    var originalFreeQTY = $(freeQTY).val();


    var cellData_discountAmount = $(cell[9]).children()[0];
    var discountAmount = $(cellData_discountAmount).val();
    var dicountPrecentage = (discountAmount / originalPrice) * 100;
    var value = (originalqty * originalPrice) - discountAmount;
    var costPrice = value / (originalqty + originalFreeQTY);
    $($(cell[8]).children()[0]).val(dicountPrecentage);
    $($(cell[10]).children()[0]).val(value);
    $($(cell[15]).children()[0]).val(costPrice)

}
 */
//cal val and cost (change with qty, free qty)
function calValueandCostPrice(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[7]).children()[0]);
    var discount_percentage = $($(cell[8]).children()[0]);
    var discount_amount = $($(cell[9]).children()[0]);
    var foc = $($(cell[3]).children()[0]);
    var cost_price = $($(cell[15]).children()[0]);



    var value = getDiscountAmount(qty, price, discount_percentage, discount_amount, foc, cost_price);
    $($(cell[10]).children()[0]).val(value);


    calculation();

}


//grand total
function calculation() {
    var grossTotal = 0;
    var tableDiscount = 0;
    var tax = 0;
    var arr = tableData.getDataSourceObject();
    var headerDiscountAmount = parseFloat($('#txtDiscountAmount').val().replace(/,/g, ""));

    for (var i = 0; i < arr.length; i++) {
        var qty = parseFloat(arr[i][2].val().replace(/,/g, ""));
        var price = parseFloat(arr[i][7].val().replace(/,/g, ""));
        var discount_pres = parseFloat(arr[i][8].val().replace(/,/g, ""));


        // Check if the field values are not NaN or empty
        if (isNaN(qty)) {
            qty = 0;
        }
        if (isNaN(price)) {
            price = 0;
        }
        if (isNaN(discount_pres)) {
            discount_pres = 0;
        }
        discount_amount = (qty * price) * (discount_pres / 100);
        grossTotal += (qty * price);
        tableDiscount += discount_amount;

    }

    if (isNaN(headerDiscountAmount)) {
        headerDiscountAmount = 0;
    }

    var totalDiscount = (headerDiscountAmount + tableDiscount);
    var netTotal = (grossTotal - totalDiscount + tax);

    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}



function getEachGR_rtn(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/prc/getEachGR_return/' + id + '/' + status,
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
            console.log(status);
        }, success: function (PurchaseRequestData) {
            console.log(PurchaseRequestData);
            var res = PurchaseRequestData.data;
            /*  getLocation(res[0].branch_id);  */
            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#goods_received_date_time').val(res[0].goods_received_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(res[0].location_id);
            $('#txtSupplier').val(res[0].supplier_code);
            $('#txtSupplier').attr('data-id', res[0].supplier_id);
            $('#lblSupplierName').text(res[0].supplier_name);
            $('#lblSupplierAddress').val(res[0].primary_address);
            /*  $('#txtPurchaseORder').val(res[0].purchase_order_id); */
            $('#txtSupplierInvoiceNumber').val(res[0].supppier_invoice_number);
            /*   $('#cmbPaymentType').val(res[0].payment_mode_id);
              $('#txtDiscountPrecentage').val(res[0].discount_percentage);
              $('#txtDiscountAmount').val(res[0].discount_amount);
              $('#txtAdjustmentAmount').val(res[0].adjustment_amount); */
            $('#txtRemarks').val(res[0].remarks);
            $('#txtYourReference').val(res[0].your_reference_number);
            /* $('#dtPaymentDueDate').val(res[0].payment_due_date); */
            /*   $('#dtPaymentDueDate').val(res[0].payment_due_date); */

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of GRN
function getEachproduct(id) {
    $.ajax({
        url: '/prc/getEachproductofGR_rtn/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);

            var dataSource = [];
            $.each(data, function (index, value) {
                var qty = Math.abs(parseFloat(value.quantity));
                var foc = Math.abs(parseFloat(value.free_quantity));
                var price = value.price;
                var disAmount = value.discount_amount;
                var valueS = (qty * price) - disAmount;
                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "DataChooser.showChooser(this,this)" },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "max-height:30px;margin-left:10px", "event": "", "style": "width:350px", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs math-round", "value": qty, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs math-round", "value": foc, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_unit, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": price, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": valueS.toFixed(2), "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.batch_number, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": value.balance, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-left:10px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:20px;", "event": "removeRow(this)", "width": 30 }

                ]);





            });
            tableData.setDataSource(dataSource);
            calculation();
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}


//update GRN
function updateGRReturn(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('goods_received_date_time', $('#goods_received_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').data('id'));
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
    formData.append('txtPurchaseORder', $('#txtPurchaseORder').val());
    formData.append('txtSupplierInvoiceNumber', $('#txtSupplierInvoiceNumber').val());
    formData.append('dtPaymentDueDate', $('#dtPaymentDueDate').val());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtAdjustmentAmount', $('#txtAdjustmentAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());

    $.ajax({
        url: '/prc/updateGRReturn/' + id,
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
            var primaryKey = response.primaryKey;
            if (status) {
                showSuccessMessage("Successfully saved");
                /*  resetForm();
                clearTableData();
                tableData.addRow(); */
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


//update GRN draft
function updateGR_Return_Draft(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('goods_received_date_time', $('#goods_received_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').data('id'));
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
    formData.append('txtPurchaseORder', $('#txtPurchaseORder').val());
    formData.append('txtSupplierInvoiceNumber', $('#txtSupplierInvoiceNumber').val());
    formData.append('dtPaymentDueDate', $('#dtPaymentDueDate').val());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtAdjustmentAmount', $('#txtAdjustmentAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());

    $.ajax({
        url: '/prc/updateGRReturnDraft/' + id,
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
            var primaryKey = response.primaryKey;
            if (status) {
                showSuccessMessage("Successfully saved");
                resetForm();
                clearTableData();
                tableData.addRow();
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


//approve
function approveRequest(id) {
    $.ajax({
        url: '/prc/approveRequestGRReturn/' + id,
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
function rejectRequestGRReturn(id) {
    $.ajax({
        url: '/prc/rejectRequestGRReturn/' + id,
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
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#goods_received_date_time').val(formattedDate);
            $('#dtPaymentDueDate').val(formattedDate);
        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_GoodsReturn", table, doc_number);
    //  $('#LblexternalNumber').val(referanceID);
}

//calculations
function getDiscountAmount(qty, price, discount_percentage, discount_amount, foc_quantity, cost_price) {

    var quantity = parseFloat(qty.val().replace(/,/g, ""));
    var unit_price = parseFloat(price.val().replace(/,/g, ""));
    var percentage = parseFloat(discount_percentage.val().replace(/,/g, ""));
    var amount = parseFloat(discount_amount.val().replace(/,/g, ""));
    var foc = parseFloat(foc_quantity.val().replace(/,/g, ""));

    if (isNaN(quantity)) {
        quantity = 0;
    }
    if (isNaN(unit_price)) {
        unit_price = 0;
    }
    if (isNaN(percentage)) {
        percentage = 0;
    }
    if (isNaN(amount)) {
        amount = 0;
    }
    if (isNaN(foc)) {
        foc = 0;
    }


    var quantity_price = (quantity * unit_price);
    var percentage_price = (quantity_price / 100.00) * percentage;

    if (discount_percentage.is(':focus')) {
        discount_amount.val(percentage_price);
    } else if (discount_amount.is(':focus')) {
        var prc = (amount / quantity_price) * 100.0;
        discount_percentage.val(prc.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    }

    var final_value = (quantity_price - percentage_price);
    var cost_value = (final_value / (quantity + foc));
    cost_price.val(cost_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());

    return final_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString();
}


//check availble qty with qty and foc
function checkavailableQty(event) {

    var row = $($(event).parent()).parent();
    var cell = row.find('td');

    var cellData_qty = $(cell[2]).children()[0];
    var qty = parseFloat($(cellData_qty).val().replace(/,/g, ''));

    var cellData_foc = $(cell[3]).children()[0];
    var foc = parseFloat($(cellData_foc).val().replace(/,/g, ''));

    var cellData_avl_qty = $(cell[11]).children()[0];
    var avl_qty = parseFloat($(cellData_avl_qty).val());

    if (isNaN(qty)) {
        qty = 0;
    }
    if (isNaN(foc)) {
        foc = 0;
    }
    totalQty = qty + foc
    if (totalQty > avl_qty) {
        showWarningMessage("Insufficient Stock");

    }
    // console.log(qty);
}



function resetTransactionTableRow(row) {

    /*  $(row[0]).val('');
     $(row[0]).attr('data-id', undefined); */
    $(row[2]).val('');
    $(row[3]).val('');
    $(row[4]).val('');
    $(row[5]).val('');
    $(row[6]).val('');
    $(row[7]).val('');
    $(row[8]).val('');
    $(row[9]).val('');
    $(row[10]).val('');
    $(row[11]).val('');
    $(row[12]).val('');
    // foc_calculation_threshold(row)


}


function dataChooserShowEventListener(event) {
    if (pickOrderStatus) {
        DataChooser.dispose();
        pickOrderStatus = false;
    }

}

//foc calculation threshold (manually item insertion)
var global_foc_qty_ = 0;
function foc_calculation_threshold(event) {
    item_row = $($(event).parent()).parent();

    var item_id = $($(item_row.children()[0]).children()[0]).attr('data-id');

    console.log($(item_row.children()[0]).children()[0]);
    console.log(item_id);
    var entered_qty = $($(item_row.children()[2]).children()[0]).val();
    console.log(entered_qty)
    var formatted_entered_qty = parseFloat(entered_qty.replace(/,/g, ""));
    /*  console.log(formatted_entered_qty); */
    if (isNaN(formatted_entered_qty)) {
        formatted_entered_qty = 0;
    }
    var date = $('#invoice_date_time').val();

    $.ajax({
        url: '/prc/getItem_foc_threshold_For_grnRtn/' + item_id + "/" + formatted_entered_qty + "/" + date,
        type: 'get',
        success: function (response) {
            // console.log(response);
            $.each(response, function (index, value) {

                global_foc_qty_ = value.Offerd_quantity;
                $($(item_row.children()[3]).children()[0]).val(value.Offerd_quantity)

            })

        }
    })

}

//foc calculation threshold (pick order insertion)
function foc_calculation_threshold_pick_order(item_id, entered_qty) {
    var date = $('#invoice_date_time').val();


    $.ajax({
        url: '/sd/getItem_foc_threshold_ForInvoice/' + item_id + "/" + entered_qty + "/" + date,
        type: 'get',
        async: false,
        success: function (response) {
            $.each(response, function (index, value) {

                foc_qty_threshold_from_pick_orders = value.Offerd_quantity;


            })

        }

    })
    return foc_qty_threshold_from_pick_orders;
}



//check foc with auto caled  FOC
function checkqtyandFoc_gr_rtn(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var foc = $($(cell[3]).children()[0]).val();



    if (parseInt(foc) > parseInt(global_foc_qty_)) {
        showWarningMessage('FOC should be equal or less than system foc');
        $($(cell[3]).children()[0]).val(global_foc_qty_);

    }
    calValueandCostPrice(event)


}