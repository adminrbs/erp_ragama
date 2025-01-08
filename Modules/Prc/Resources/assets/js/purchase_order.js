

var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var PO_id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList = [];
var pickOrderStatus = false;
$(document).ready(function () {
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();
    
    $('#txtDiscountAmount').hide();
    $('#btnBack').hide();

    /* ItemList = loadItems(0); */
    loadPamentType();
    getServerTime();
    getDeliveryTypes();
    suppliers = loadSupplierTochooser();

    DataChooser.addCollection("supplier",['Supplier', 'Code', '', '',''], suppliers);
    DataChooser.addCollection("item",['Item', 'Code', 'Supply Group', '',''], ItemList);



    //gross total calculation
    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });

    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });


    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    })

    getBranches();
    $('#cmbBranch').change();

    $('#txtSupplier').on('focus', function () {
        DataChooser.showChooser($(this),$(this),"supplier");
        $('#data-chooser-modalLabel').text('Suppliers');

       

        var upArrowEvent = $.Event('keydown', { keyCode: 38 });
    });




    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        PO_id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            pickOrderStatus = true;
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#chk').hide();
            $('#btnSaveDraft').hide();
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
            /*  $('#btnSaveDraft').show(); */
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
        /*  getEachPurchasingOrder(reuqestID, status);
         getEachproduct(reuqestID, status);
         getEachOther(reuqestID, status); */
        /* getEachGRN(GRNID, status);
        getEachproduct(GRNID, status); */
        getEachPO(PO_id, status);
        getEachproduct(PO_id, status);
    }

    $('#btnBack').on('click', function () {
        if (task == "approval") {
            var url = "/prc/purchaseOrderApprovalList";
            window.location.href = url;
        } else {
            var url = "/prc/purchaseOrderList";
            window.location.href = url;
        }


    });

    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;margin-right:10px;background-color:white;width:370px", "event": "clickx(1)", "style": "width:200px", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);", "compulsory": true },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "validate_qty(this);calValueandCostPrice(this)" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "validate_qty(this);calValueandCostPrice(this)" }, //additional qty
            { "type": "text", "class": "transaction-inputs", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            
            { "type": "text", "class": "transaction-inputs", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true },
            { "type": "text", "class": "transaction-inputs math-abs", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true }, // cost price

            { "type": "text", "class": "transaction-inputs math-abs", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "style": "max-height:30px;text-align:right;width:150px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-right:10px;", "event": "removeRow(this);calculation()", "width": 30 }
        ],
        "auto_focus": 0,
        "hidden_col": [8,13,5]


    });

    tableData.addRow();

    /*     $('#tblData').on('input', 'input[type="text"]', function () {
            var cellIndex = $(this).closest('td').index();
                // Allow only numbers and dots for other cell indexes
                this.value = this.value.replace(/[^0-9.]/g, '');
                var dotCount = (this.value.match(/\./g) || []).length;
                if (dotCount > 1) {
                    this.value = this.value.replace(/\.+$/, '');
                }
            
        }); */

    $('#tblData').on('input', 'input[type="text"]', function () {
        // Remove any consecutive dots
        var cellIndex = $(this).closest('td').index();
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
            } else if ((arr[i][2].val() == "" && arr[i][3].val() == "") || (parseFloat(arr[i][2].val()) == "0" && parseFloat(arr[i][3].val()) == "0") || (arr[i][2].val() == "0" && arr[i][3].val() == "") || (arr[i][2].val() == "" && arr[i][3].val() == "0")) {
              
                showWarningMessage("Quantity must be greater than 0");
                return;
            } else if (arr[i][10].val() == "" || arr[i][10].val() == "0" || arr[i][10].val() == "undefined" || arr[i][10].val() == "null" || parseFloat(arr[i][10].val()) == 0) {
                showWarningMessage("Price must be gretaer than 0.00");
                return;
            } else {
                
               
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "uom": arr[i][5].val(),
                    "PackUnit": arr[i][9].val(),
                    "PackSize": arr[i][8].val(),
                    "free_quantity": arr[i][3].val(),
                    "price": parseFloat(arr[i][10].val().replace(/,/g, '')),
                    "cost": parseFloat(arr[i][11].val().replace(/,/g, '')),
                    "discount_percentage": arr[i][12].val(),
                    "discount_amount": parseFloat(arr[i][13].val().replace(/,/g, '')),
                    "addQty":arr[i][4].val()


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
                        newReferanceID('purchase_order_notes', 110);
                        addPurchaseOrder(collection, PO_id);
                        getServerTime()
                    } else if ($('#btnSave').text() == 'Update') {
                        updatePO(collection, PO_id);
                        getServerTime();

                    }
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
        var arr = tableData.getDataSourceObject();
        var collection = [];

        for (var i = 0; i < arr.length; i++) {

            if (arr[i][0].attr('data-id') == "undefined") {
                showWarningMessage("Please select a correct Item");
                arr[i][0].focus();
                return;
            } else if ((arr[i][2].val() == "" && arr[i][3].val() == "") || (parseFloat(arr[i][2].val()) == "0" && parseFloat(arr[i][3].val()) == "0") || (arr[i][2].val() == "0" && arr[i][3].val() == "") || (arr[i][2].val() == "" && arr[i][3].val() == "0")) {
              
                showWarningMessage("Quantity must be greater than 0");
                return;
            } else if (arr[i][10].val() == "" || arr[i][10].val() == "0" || arr[i][10].val() == "undefined" || arr[i][10].val() == "null" || parseFloat(arr[i][10].val()) == 0) {
                showWarningMessage("Price must be gretaer than 0.00");
                return;
            } else {
                
               
                collection.push(JSON.stringify({
                   "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "uom": arr[i][5].val(),
                    "PackUnit": arr[i][9].val(),
                    "PackSize": arr[i][8].val(),
                    "free_quantity": arr[i][3].val(),
                    "price": parseFloat(arr[i][10].val().replace(/,/g, '')),
                    "cost": parseFloat(arr[i][11].val().replace(/,/g, '')),
                    "discount_percentage": arr[i][12].val(),
                    "discount_amount": parseFloat(arr[i][13].val().replace(/,/g, '')),
                    "addQty":arr[i][4].val()


                }));


            }
        }
        calculation();
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
                    approveRequestPO(collection,PO_id);

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
                    rejectRequestPO(PO_id);
                } else {

                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');


    });

    $('#txtDiscountPrecentage').on('input',function(){
        apply_discount();
    });


    /* if ($('#lblSupplierName').text().length > 0) {

        if($('#rdoAny').prop('checked')){
            ItemList = loadItems(0);
        }else{
            var id = $('#txtSupplier').attr('data-id')
            ItemList = loadItems(id);
        }
        
    } */


    $('#rdoAny').on('change', function() {
        if ($(this).prop('checked')) {
           
            ItemList = loadItems(0);
            DataChooser.addCollection("item",['', '', '', '',''], ItemList);
            console.log(ItemList);
        }
    });
    
    $('#rdoSupplyGroup').on('change', function() {
        if ($(this).prop('checked')) {
            var id = $('#txtSupplier').attr('data-id')
            ItemList = loadItems(id);
            console.log(ItemList);
            DataChooser.addCollection("item",['', '', '', '',''], ItemList);
        }
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
        url: '/prc/getLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })

        },
    })
}

function getDeliveryTypes() {
    $.ajax({
        url: '/prc/getDeliveryTypes',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbDeliveryType').append('<option value="' + value.delivery_type_id + '">' + value.delivery_type_name + '</option>');

            })

        },
    })
}

//add PO
function addPurchaseOrder(collection, id) {
    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an item');
        return
    }
    var return_result = _validation($('#txtSupplier'), $('#txtSupplier'));
    if (return_result) {
        showWarningMessage("Please fill all required fields");
        return;
    } else {
        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', referanceID);
        formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('txtSupplier', $('#txtSupplier').data('id'));
        formData.append('lblSupplierName', $('#lblSupplierName').text());
        formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
        formData.append('cmbPaymentType', $('#cmbPaymentType').val());
        formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
        formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
        formData.append('cmbDeliveryType', $('#cmbDeliveryType').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('deliveryDate', $('#deliveryDate').val());
        formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
        formData.append('txtYourReference', $('#txtYourReference').val());

        $.ajax({
            url: '/prc/addPurchaseOrder/' + id,
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
                    
                 /*    clearTableData();
                    tableData.addRow(); */
                    /* if ($('#chkPrintReport').prop('checked')) {
                        generatePOreport(primaryKey);
                    } */
                  /*   resetForm(); */


                    url = "/prc/purchaseOrderList";
                    window.location.href = url;

                } else {

                    showErrorMessage("Something went wrong");
                }


            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {

            }
        })
        getServerTime();
    }
}

//add PO draft
function addPurchaseOrderDraft(collection) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', referanceID);
    formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').data('id'));
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('cmbDeliveryType', $('#cmbDeliveryType').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('deliveryDate', $('#deliveryDate').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());

    $.ajax({
        url: '/prc/addPurchaseOrderDraft/',
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


//load item
function loadItems(id) {
    var list = []
    
    $.ajax({
        url: '/prc/loadItemsPurchaseOrder/'+id,
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

//load item
function loadSupplierTochooser() {

    var data = [];
    $.ajax({
        url: '/prc/loadSupplierTochooser',
        type: 'get',
        dataType: 'json',
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
            var supID = txt[0].supplier_id;
            /*  console.log(txt); */
            $('#txtSupplier').attr('data-id', supID);
            $('#lblSupplierAddress').val(txt[0].primary_address);
            $('#cmbPaymentType').focus();

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
        async: false,
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


    if ($(event.inputFiled).attr('id') == 'txtSupplier') {
        loadSupplierOtherDetails(value);
        $('#lblSupplierName').text(id);
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
            event.inputFiled.val('');
            return;
        }



        $.ajax({
            url: '/prc/getItemInfo_purchase_order/' + item_id +'/'+$('#cmbBranch').val(),
            type: 'get',
            success: function (response) {
                console.log(response);
                var expireDateManage = response[0].manage_expire_date;
                if (expireDateManage == 1) {
                    $(row_childs[14]).removeAttr('disabled');
                }

                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[5]).val(response[0].unit_of_measure);
                $(row_childs[9]).val(response[0].package_unit);
                $(row_childs[8]).val(response[0].package_size);
                $(row_childs[10]).val(response[0].previouse_purchase_price);
                $(row_childs[6]).val(parseInt(response[0].from_balance));
                $(row_childs[7]).val(parseInt(response[0].avg_sales));
                $(row_childs[2]).focus();
                $(row_childs[2]).val('');
                $(row_childs[3]).val('');

                /* var valueS = parseFloat((qty * cst) - disAmount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); */
                $(row_childs[11]).val(response[0].previouse_purchase_price); //cost price. sachin 2025-01-02
                if($('#txtDiscountPrecentage').val().length > 0){
                    $(row_childs[12]).val($('#txtDiscountPrecentage').val());
                    
                }else{
                    $(row_childs[12]).removeAttr('disabled');
                }
               
                calculation();
            }
        })

    }

    if($('#rdoSupplyGroup').prop('checked')){
        var sup_id = event.inputFiled.attr('data-id');
    
        ItemList = loadItems(sup_id);
        console.log(ItemList);
       
        DataChooser.addCollection("item",['', '', '', '',''], ItemList);


    }else{

        var sup_id = event.inputFiled.attr('data-id');
    
         ItemList = loadItems(0);
         console.log(ItemList);
        
         DataChooser.addCollection("item",['', '', '', '',''], ItemList);

    }

   
}

//apply discunt precentage for all
function apply_discount(){
    
    /* var arr = tableData.getDataSource(); */
    var arr = tableData.getDataSourceObject();
    console.log(arr);
    
    for (var i = 0; i < arr.length; i++) {
        
        var row_childs = $(arr[i][12]);
        var code_text = $(arr[i][0]).val();
        if(code_text != ''){
            if($('#txtDiscountPrecentage').val().length < 1){
                row_childs.removeAttr('disabled');
            }else{
                row_childs.attr('disabled','disabled');
            }
           row_childs.val($('#txtDiscountPrecentage').val());
           row_childs.trigger('input');
        }
       
    }
   
    
}



function calValueandCostPrice(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');

    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[11]).children()[0]);
    var discount_percentage = $($(cell[12]).children()[0]);
    var discount_amount = $($(cell[13]).children()[0]);

    var AMOUNT = getDiscountAmount(qty, price, discount_percentage, discount_amount);
    $($(cell[14]).children()[0]).val(AMOUNT);

    calcDisc(event);
    calculation();
    

}

function calcDisc(event){
    var row = $($(event).parent()).parent();
    var cell = row.find('td');

    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[11]).children()[0]);
    var discount_percentage = $($(cell[12]).children()[0]);
   
    var quantity = parseFloat(qty.val().replace(/,/g, ""));
    var unit_price = parseFloat(price.val().replace(/,/g, ""));
    var percentage = parseFloat(discount_percentage.val().replace(/,/g, ""));
   
var dis_amount = quantity * unit_price * (percentage / 100);
   
    $($(cell[13]).children()[0]).val(dis_amount);
  //  calculation();
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
        var price = parseFloat(arr[i][11].val().replace(/,/g, "")); // changed to cost price
        var discount_pres = parseFloat(arr[i][12].val().replace(/,/g, ""));


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




function getEachPO(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/prc/getEachPO/' + id + '/' + status,
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
            getLocation(res[0].branch_id);
            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#LblexternalNumber').attr('data-id', res[0].internal_number);
            $('#purchase_order_date_time').val(res[0].purchase_order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#txtSupplier').attr('data-id', res[0].supplier_id);
            $('#txtSupplier').val(res[0].supplier_code);
            $('#lblSupplierName').text(res[0].supplier_name);
            $('#lblSupplierAddress').val(res[0].primary_address);
            $('#cmbPaymentType').val(res[0].payment_mode_id);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbDeliveryType').val(res[0].deliver_type_id);
            $('#txtRemarks').val(res[0].remarks);
            $('#deliveryDate').val(res[0].deliver_date_time);
            $('#txtDeliveryInst').val(res[0].delivery_instruction);
            $('#txtYourReference').val(res[0].your_reference_number);

            ItemList = loadItems(res[0].supplier_id);
            console.log(ItemList);
       
            DataChooser.addCollection("item",['', '', '', '',''], ItemList);

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of PO
function getEachproduct(id, status) {
    $.ajax({
        url: '/prc/getEachproductofPO/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);


            var dataSource = [];
            $.each(data, function (index, value) {
                var qty = value.quantity;
                var price = value.price;
                var cst = value.cost_price;
                var price_ = parseFloat(value.price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                var disAmount = value.discount_amount;
                var valueS = parseFloat((qty * cst) - disAmount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:200px", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "compulsory": true, "event": "calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.free_quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "validate_qty(this);calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.additional_bonus, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.from_balance, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.avg_sales, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                   
                    { "type": "text", "class": "transaction-inputs", "value": value.package_unit, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": price_, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.cost_price, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": valueS, "style": "max-height:30px;text-align:right;width:150px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-right:10px;", "event": "removeRow(this);calculation()", "width": 30 }

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
function updatePO(collection, id) {

    var return_result = _validation($('#txtSupplier'), $('#txtSupplier'));
    if (return_result) {
        showWarningMessage("Please fill all required fields");
        return;
    } else {
        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', $('#LblexternalNumber').val());
        formData.append('internalNumber', $('#LblexternalNumber').attr('data-id'));
        formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('txtSupplier', $('#txtSupplier').data('id'));
        formData.append('lblSupplierName', $('#lblSupplierName').text());
        formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
        formData.append('cmbPaymentType', $('#cmbPaymentType').val());
        formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
        formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
        formData.append('cmbDeliveryType', $('#cmbDeliveryType').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('deliveryDate', $('#deliveryDate').val());
        formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
        formData.append('txtYourReference', $('#txtYourReference').val());

        $.ajax({
            url: '/prc/updatePO/' + id,
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
                    url = "/prc/purchaseOrderList";
                    window.location.href = url;

                    clearTableData();
                    tableData.addRow();
                    if ($('#chkPrintReport').prop('checked')) {
                        generatePOreport(primaryKey);
                    }
                    resetForm();
                } else {

                    showWarningMessage("Unable to update");
                }


            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {

            }
        })
        getServerTime();
    }
}

/* url: '/prc/updatePO/' + id, */

//update PO draft
function updatePODraft(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').val());
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('cmbDeliveryType', $('#cmbDeliveryType').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('deliveryDate', $('#deliveryDate').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());

    $.ajax({
        url: '/prc/updatePODraft/' + id,
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


//approve
function approveRequestPO(collection,id) {
    formData.append('collection', JSON.stringify(collection));
    $.ajax({
        url: '/prc/approveRequestPO/' + id,
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
            $('#btnSave').prop('disabled', true);
        }, success: function (response) {
              $('#btnSave').prop('disabled', false);
            var status = response.status;
            var msg = response.msg; 

            if (msg == "no") {
                showWarningMessage('Unable to approve');
                return;
            }

            if (status) {
                showSuccessMessage("Record approved");
                generatePOreport(id);
                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
                url = "/prc/purchaseOrderApprovalList";
                setTimeout(function() {
                    window.location.href = url;
                }, 3000); //

                
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
function rejectRequestPO(id) {
    $.ajax({
        url: '/prc/rejectRequestPO/' + id,
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
            var status = response.status;
            var msg = response.msg;

            if (msg == "no") {
                showWarningMessage('Unable to reject');
                return;
            }
            if (status) {
                showSuccessMessage("Request rejected");

                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
                url = "/prc/purchaseOrderApprovalList";
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
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#purchase_order_date_time').val(formattedDate);
            $('#deliveryDate').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_PO_rferenceId", table, doc_number);
    //  $('#LblexternalNumber').val(referanceID);
}

function getDiscountAmount(qty, price, discount_percentage, discount_amount) {
    var quantity = parseFloat(qty.val().replace(/,/g, ""));
    var unit_price = parseFloat(price.val().replace(/,/g, ""));
    var percentage = parseFloat(discount_percentage.val().replace(/,/g, ""));
    var amount = parseFloat(discount_amount.val().replace(/,/g, ""));

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


    var quantity_price = (quantity * unit_price);
    var percentage_price = 0;
    var final_value = 0;


    if (discount_percentage.is(':focus')) {
        percentage_price = (quantity_price / 100.00) * percentage;
        discount_amount.val(percentage_price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    } else if (discount_amount.is(':focus')) {
        var prc = ((amount / quantity_price) * 100.0);
        percentage_price = (quantity_price / 100.00) * prc;
        discount_percentage.val(prc.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    } else {
        percentage_price = (quantity_price / 100.00) * percentage;
    }
    final_value = (quantity_price - percentage_price);


    return final_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString();
}


function dataChooserShowEventListener(event) {
    if (pickOrderStatus) {
        DataChooser.dispose();
        pickOrderStatus = false;
    }

}


function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event,"item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

//validate qty
function validate_qty(event){
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var qty = $($(cell[2]).children()[0]).val();
    

    if(qty == ''){
        $($(cell[2]).children()[0]).val('0');
    }
    

}