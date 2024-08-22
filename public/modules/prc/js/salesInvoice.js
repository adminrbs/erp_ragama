var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var customers = []
var PO_id = null;
var reuqestID;
$(document).ready(function () {
    $('#btnApprove').hide();
    $('#btnReject').hide();
    getBranches();
    loadItems();
    loadPamentType();
    getServerTime();
  
    
    customers = loadCustomerTOchooser();

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    })

    $('#txtCustomerID').on('click', function () {
        DataChooser.emptyTable();
        DataChooser.setDataSourse(customers);

    })

    //setting datat to data chooser -supplier
    $('#txtCustomerID').on('input', function () {
        
        DataChooser.showChooser($(this));
    });

    
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
      /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        PO_id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if(action == 'edit' && status == 'Original' && task == 'approval'){
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
        
        } else if(action == 'edit' && status == 'Draft' ){
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
            $('#btnSaveDraft').show();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            
        }else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            
        }
       /*  getEachPurchasingOrder(reuqestID, status);
        getEachproduct(reuqestID, status);
        getEachOther(reuqestID, status); */  
        /* getEachGRN(GRNID, status);
        getEachproduct(GRNID, status); */
        getEachPO(PO_id,status);
        getEachproduct(PO_id,status);
    }


    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "form-control form-control-sm", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "DataChooser.showChooser(this,this)" },
            { "type": "text", "value": "", "style": "max-height:30px;margin-left:10px;", "event": "clickx(1)", "style": "width:200px", "disabled": "disabled" },
            { "type": "text", "class": "form-control form-control-sm", "value": "", "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;","event":"calValueandCostPrice(this)" },
            { "type": "text", "class": "form-control form-control-sm", "value": "", "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;","event":"calValueandCostPrice(this)" },
            { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*" },
            { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:150px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this)", "width": 30 }
        ],
        "auto_focus": 1,
        "hidden_col": [5]


    });

    tableData.addRow();

    $('#tblData').on('input', 'input[type="text"]', function () {
        var cellIndex = $(this).closest('td').index();
        if (cellIndex != 14) {
            this.value = this.value.replace(/[^0-9.]/g, ''); // allow numbers only
            var dotCount = (this.value.match(/\./g) || []).length;
            if (dotCount > 1) {
                this.value = this.value.replace(/\.+$/, '');
            }
        } else {
            this.value = this.value.replace(/[^0-9.-]/g, ''); // Allow numbers, dots, and minus sign
            var dotCount = (this.value.match(/\./g) || []).length;
            var minusCount = (this.value.match(/-/g) || []).length;
            if (dotCount > 1 || minusCount > 2) {
                this.value = this.value.replace(/\.+$/, '');
                this.value = this.value.replace(/-+$/, '');
            }
        }
        
        
    });
    

    $('#btnSave').on('click', function () {
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
                    if ($('#btnSave').text() == 'Save and Send') {
                        addPurchaseOrder(collection, PO_id);
                    } else if ($('#btnSave').text() == 'Update') {
                        updatePO(collection,PO_id);

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

    $('#btnSaveDraft').on('click',function(){
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

                        addPurchaseOrderDraft(collection);

                    } else if ($('#btnSaveDraft').text() == 'Update Draft') {

                        
                        updatePODraft(collection, PO_id);

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

    $('#btnApprove').on('click',function(){
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
                    approveRequestPO(PO_id);
                    
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
    $('#btnReject').on('click',function(){
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
               if(result){
                rejectRequestPO(PO_id);
               }else{
    
               }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

        
    })



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
        url: '/prc/getBranches',
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



//add PO
function addPurchaseOrder(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').text());
    formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').val());
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').text());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('cmbDeliveryType', $('#cmbDeliveryType').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('deliveryDate', $('#deliveryDate').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());

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

//add PO draft
function addPurchaseOrderDraft(collection) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').text());
    formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').val());
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').text());
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
function loadItems() {
    $.ajax({
        url: '/prc/loadItems',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var itemData = response.data;
                DataChooser.setDataSourse(itemData);
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load item
function loadCustomerTOchooser() {

    var data = [];
    $.ajax({
        url: '/prc/loadCustomerTOchooser',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response) {
                var customerData = response.data;
                console.log(customerData);
                /*  DataChooser.setDataSourse(supplierData); */
                data = customerData;
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return data;
}
//load supplier other details
function loadCustomerOtherDetails(id) {
    alert();
    $.ajax({
        url: '/prc/loadCustomerOtherDetails/' + id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            console.log(data)
            var txt = data.data;
            /*  console.log(txt); */
            $('#lblCustomerAddress').text(txt[0].primary_address);
            var cusID = txt[0].customer_id;
            $('#lblCustomerName').attr('data-id',cusID);

            var testID = $('#lblCustomerName').data('id');
            alert(testID);
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
  if($(event.inputFiled).attr('id') == 'txtCustomerID'){
        loadCustomerOtherDetails(value);
        $('#lblCustomerName').text(id);

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



        $.ajax({
            url: '/prc/getItemInfo/' + item_id,
            type: 'get',
            success: function (response) {
                console.log(response);
                var expireDateManage = response[0].manage_expire_date;
                if (expireDateManage == 1) {
                    $(row_childs[14]).removeAttr('disabled');
                }

                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[4]).val(response[0].unit_of_measure);
                $(row_childs[6]).val(response[0].package_unit);
                $(row_childs[5]).val(response[0].package_size);
                $(row_childs[7]).val(response[0].average_cost_price);
                $(row_childs[11]).val(response[0].whole_sale_price);
                $(row_childs[12]).val(response[0].retial_price);
            }
        })

    }

}



//discount amount calcution
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

//cal val and cost (change with qty, free qty)
function calValueandCostPrice(event){
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
    var dicountPrecentage = (discountAmount / originalPrice) * 100;


    $($(cell[9]).children()[0]).val(discountAmount);
    $($(cell[10]).children()[0]).val(value);
    $($(cell[15]).children()[0]).val(costPrice)
    $($(cell[8]).children()[0]).val(dicountPrecentage);
    $($(cell[10]).children()[0]).val(value);
    $($(cell[15]).children()[0]).val(costPrice)

  

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
            $('#LblexternalNumber').text(res[0].external_number);
            $('#purchase_order_date_time').val(res[0].purchase_order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#txtSupplier').val(res[0].supplier_id);
            $('#lblSupplierName').text(res[0].supplier_name);
            $('#lblSupplierAddress').text(res[0].primary_address);
            $('#cmbPaymentType').val(res[0].payment_mode_id);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbDeliveryType').val(res[0].deliver_type_id);
            $('#txtRemarks').val(res[0].remarks);
            $('#deliveryDate').val(res[0].deliver_date_time);
            $('#txtDeliveryInst').val(res[0].delivery_instruction);

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
                var disAmount = value.discount_amount;
                var valueS = (qty * price) - disAmount;
                dataSource.push([
                    { "type": "text", "class": "form-control form-control-sm", "value": value.Item_code,"data_id":value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "DataChooser.showChooser(this,this)" },
                    { "type": "text", "value": value.item_name, "style": "max-height:30px;margin-left:10px;", "event": "clickx(1)", "style": "width:200px", "disabled": "disabled" },
                    { "type": "text", "class": "form-control form-control-sm", "value": value.quantity, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", },
                    { "type": "text", "class": "form-control form-control-sm", "value": value.free_quantity, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", },
                    { "type": "text", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "value": value.package_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "value": value.package_unit, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "value": value.price, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*",  },
                    { "type": "text", "value": value.discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*" },
                    { "type": "text", "value": value.discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "discountAmount(this)", "width": "*", },
                    { "type": "text", "value": valueS, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*","disabled": "disabled" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this)", "width": 30 }

                ]);


            });
            tableData.setDataSource(dataSource);
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}


//update GRN
function updatePO(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').text());
    formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').val());
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').text());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('cmbDeliveryType', $('#cmbDeliveryType').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('deliveryDate', $('#deliveryDate').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());

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


//update PO draft
function updatePODraft(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').text());
    formData.append('purchase_order_date_time', $('#purchase_order_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').val());
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').text());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('cmbDeliveryType', $('#cmbDeliveryType').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('deliveryDate', $('#deliveryDate').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());

    $.ajax({
        url: '/prc/updatePODraft/'+id,
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
function approveRequestPO(id){
    $.ajax({
        url:'/prc/approveRequestPO/'+id,
        type:'post',
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

                $('#btnApprove').prop('disabled',true);
                $('#btnReject').prop('disabled',true);
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
function rejectRequestPO(id){
    $.ajax({
        url:'/prc/rejectRequestPO/'+id,
        type:'post',
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

                $('#btnApprove').prop('disabled',true);
                $('#btnReject').prop('disabled',true);
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
function getServerTime(){
    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {
          
            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#purchase_order_date_time').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}


