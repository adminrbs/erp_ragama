
var hash_map = new HashMap();
var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var customers = []
var Invoice_id = null;
var reuqestID;
var action = undefined;
var referanceID
var ItemList;
var locationID;
var totalQty = 0;
var qty_object = undefined;
var foc_qty_threshold_from_pick_orders; // using to assign foc qty on pick orders
var branchID;
var whole_sale_count = 0;
//show batch model
$(document).keyup(function (event) {




    if (event.which === 115) { // 115 is the keycode for F4


        var focusedInput = $("input:focus");
        if (focusedInput.attr('name') == "qty" || focusedInput.attr('name') == "foc") {

            var row = $(focusedInput.parent()).parent();
            var rowIndex = row.index();
            $('#rowIndex').val(rowIndex);
            // console.log(row);
            var cell = row.find('td');
            var ItemID = $(cell[0]).children().eq(0).attr('data-id');
            // console.log(ItemID);
            $('#hiddenItem').val(ItemID);
            TemprorySave(ItemID);
            getItemHistorySetoffBatch(branchID, ItemID,locationID);

            $('#batchModel').modal('show');

            $('#batchModelTitle').text("Item Set Off Quantity" + " " + totalQty);

            autoSetOff(ItemID);


        }
    }
});



function TemprorySave(ItemID) {
    var item = new Item();
    hash_map.put(ItemID, item);


}

//set of manually in model
function setOff() {


    var rowObjects = tableData.getDataSourceObject();
    
    console.log(hash_map);
    for (var i = 0; i < rowObjects.length; i++) {
        var item_id = rowObjects[i][0].attr('data-id');
        var disc_precen = rowObjects[i][8].val();
        var qty_ = rowObjects[i][2].val();
        var foc = rowObjects[i][3].val();

        
        var row_ = ($($(rowObjects[i][0]).parent()).parent());
        checkWholeSalePrice(row_)
        if (isNaN(foc)) {
            foc = 0;
        }



        var item = hash_map.get(item_id);

        //   console.log(item.get("disc_amount"));
        for (var i = 0; i < rowObjects.length; i++) {
            
            var item_id = rowObjects[i][0].attr('data-id');
            var disc_precen = rowObjects[i][8].val();
            var qty_ = rowObjects[i][2].val();
            var foc = rowObjects[i][3].val();

            if (isNaN(foc)) {
                foc = 0;
            }


            var item = hash_map.get(item_id);
            if (item) {
                
                var array = item.toArray();
                var setoff_quantity = 0;
                var setWholesalePrice = 0;
                var setWholesalePrice_temp = 0;
                var avl_qty = 0;
                for (var i2 = 0; i2 < array.length; i2++) {
                    var setoffObject = array[i2][1];
                    setoff_quantity += parseFloat(setoffObject.getSetoffQuantity());
                    if (setoffObject.getSetoffQuantity() > 0) {
                        setWholesalePrice = parseFloat(setoffObject.getWholesalePrice());
                    }
                    setWholesalePrice_temp = parseFloat(setoffObject.getWholesalePrice());  // use when user enter 0 set off qty

                    avl_qty += parseFloat(setoffObject.getAvailableQuantity());
                    
                    
                }
                var total_qty = parseFloat(qty_) + parseFloat(0);
                if (parseFloat(avl_qty) < parseFloat(setoff_quantity)) {
                    showWarningMessage("Set off quantity should be same to the total quantity");
                    /* setoffObject.setSetoffQuantity(parseFloat(avl_qty)); */
                   
                    return;
                }
                if (setoff_quantity == 0) {
                    // alert(setWholesalePrice_temp);
                    /*  setoffObject.setSetoffQuantity(parseFloat(total_qty)); */
                    setWholesalePrice = parseFloat(setWholesalePrice_temp);
                    
                }

                if (parseFloat(total_qty) != parseFloat(setoff_quantity)) {
                    /* showWarningMessage("Batch is not selected properly"); */
                    /*  setoffObject.setSetoffQuantity(parseFloat(0)); */
                    
                    return;

                } else {
                    /*  alert(setWholesalePrice_temp); */
                    var disc_amount = parseFloat((setWholesalePrice * qty_)) * parseFloat(disc_precen / 100);
                    var value = (parseFloat(qty_) * parseFloat(setWholesalePrice)) - parseFloat(disc_amount);

                    rowObjects[i][12].val(setoff_quantity);
                    rowObjects[i][7].val(parseFloat(setWholesalePrice).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    rowObjects[i][10].val(parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                }
            }
        }
      /*   var cell = rowObjects.find('td');
        var batch_btn = $($(cell[13]).children()[0]); */
        
       
    }
    calculation();
}


//set of automatically without model
function setOffAutoMatically(collection) {
    //console.log(collection);


    //var hashmap = new HashMap();
    branchID = $('#cmbBranch').val();
    locationID = $('#cmbLocation').val();
    for (var i = 0; i < collection.length; i++) {

        $.ajax({
            type: "GET",
            url: "/sd/getItemHistorySetoffBatch/" + branchID + "/" + collection[i].item_id + "/" + locationID,
            cache: false,
            timeout: 800000,
            async: false,
            beforeSend: function () { },
            success: function (response) {

                var dt = response.data
                //console.log(response);
                var itemobj = new Item();
                $.each(dt, function (index, item) {
                    //console.log(item);

                    //   alert();
                    var itemSetOff = new ItemSetoff();
                    itemSetOff.setPrimaryID(item.item_history_setoff_id);
                    itemSetOff.setItemID(item.item_id);
                    itemSetOff.setBatchNo(item.batch_number);
                    itemSetOff.setWholesalePrice(item.whole_sale_price);
                    itemSetOff.setSetoffQuantity(0);
                    itemSetOff.setCostPrice(item.cost_price);
                    itemSetOff.setRetailPrice(item.retial_price);
                    itemSetOff.setAvailableQuantity(item.AvlQty);
                    itemobj.add(itemSetOff);


                });
                hash_map.put(collection[i].item_id, itemobj);


            },
            error: function (error) {
                console.log(error);
            },
            complete: function () { }
        })





    }
    setoffItem(collection, hash_map)
    setOff();
}

//set of by button to show model (batch button)
function setOffbybuton(event) {

    //console.log(event.originalEvent);
    $('#batchTableData tbody').empty();
    var row = $($(event).parent()).parent();
    var rowIndex = row.index();
    var cell = row.find('td');
    var ItemID = $(cell[0]).children().eq(0).attr('data-id');
    var qty = parseFloat($(cell[2]).children().eq(0).val());
    var foc = parseFloat($(cell[3]).children().eq(0).val());
    if (isNaN(qty)) {
        qty = 0;
    }
    if (isNaN(foc)) {
        foc = 0;
    }
    var qty_obj = $(cell[0]).children()[0];
    $('#hiddenItem').val(ItemID);
    $('#rowIndex').val(rowIndex);

    totalQty = qty + foc
    branchID = $('#cmbBranch').val();
    locationID = $('#cmbLocation').val();

    // getItemHistorySetoffBatch(locationID, ItemID);

    checkWholeSalePrice(event);

    $('#batchModelTitle').text("Item Set Off Quantity" + " " + totalQty);
    TemprorySave(ItemID);
    getItemHistorySetoffBatch(branchID, ItemID,locationID);
    autoSetOff(ItemID);
    autoSetOff_wrong_qty(qty_obj);

}

//use when user enter excess set of qty in model - auto set off (this use also auto set off - type qty)
var x = 0;
function autoSetOff_wrong_qty(event) {

    console.log(x++)
    var td_parent = $(event).parent();
    var row_parent = td_parent.parent();

    var itemID = $($(row_parent.children()[0]).children()[0]).attr('data-id');
    var quantity = parseFloat($($(row_parent.children()[2]).children()[0]).val());
    var foc = parseFloat($($(row_parent.children()[3]).children()[0]).val());
    if (isNaN(quantity)) {
        quantity = 0;
    }
    if (isNaN(foc)) {
        foc = 0;
    }
    var collection = [{ "item_id": itemID, "quantity": quantity, "free_quantity": foc }];
    setOffAutoMatically(collection);
    // console.log(collection);

}

//check different whole sale prices
function checkWholeSalePrice(event) {
    var whole_sale_array = [];
    var row = $($(event).parent()).parent();
    var rowIndex = row.index();
    var cell = row.find('td');
    var ItemID = $(cell[0]).children().eq(0).attr('data-id');
    branchID = $('#cmbBranch').val();
    // var val_ = 0;

    $.ajax({
        type: "GET",
        url: "/sd/getItemHistorySetoffBatch/" + branchID + "/" + ItemID + "/" + locationID,
        cache: false,
        timeout: 800000,
        async: false,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data

            var previous_whole_sale_price;
            $.each(dt, function (index, item) {
               

                if (!whole_sale_array.includes(item.whole_sale_price)) {
                    whole_sale_array.push(item.whole_sale_price);
                }

            });
            var batch_btn = $($(cell[13]).children()[0]);

            whole_sale_count = whole_sale_array.length;
            batch_btn.html("Batch&nbsp<span class='badge bg-yellow text-black translate-middle-middle  rounded-pill'style='padding:4px;'>" + whole_sale_count + "</span>");

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

    //   return parseInt(val_);
}







//remove hash map index
function removeHashMapIndex(event) {

    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var ItemID = $(cell[0]).children().eq(0).attr('data-id');
    hash_map.remove(ItemID);

}



$(document).ready(function () {

    /* $('#batchModelTitle').hide(); */
    $('#lblBalance').hide();

    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide(); // need to edit


    ItemList = loadItems();

    
    getServerTime();
   
    $('#btnBack').hide();

    $('#btnBack').on('click', function () {

        var url = "/sd/salesInvoiceList";
        window.location.href = url;


    });

  

    //getting branch code
    $('#cmbBranch').on('change', function () {
        var branch_id_ = $(this).val();
        
    });

    /* newReferanceID('sales_invoices','210'); */

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);

    });
    $('#cmbLocation').change();
    


    getBranches();
    $('#cmbBranch').change();

    //get location id
    $('#cmbLocation').on('change', function () {
        locationID = $(this).val();

    });

      //getting rep code 
      $('#cmbEmp').on('change', function () {
        var rep_id = $(this).val();
        get_rep_code(rep_id);
    });

    
    $('#cmbEmp').change();

    $('#batchModel').on('hide.bs.modal', function () {

        setOff();
    })

   

    $('#warningClose').on('click',function(){
       
       
        $('#warning_alert').removeClass('show');
    });



    customers = loadCustomerTOchooser();

    DataChooser.addCollection("Customer",['Customer Name', 'Customer Code', 'Town', 'Route',''], customers);
    DataChooser.addCollection("item",['', '', '', '',''], ItemList);


    //gross total
    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });

    $('#si_model_btn').on('click',function(){
        $('#warning_alert').removeClass('show');
    });


    $('#txtCustomerID').on('focus', function () {
        
        DataChooser.showChooser($(this),$(this),"Customer");
        $('#data-chooser-modalLabel').text('Customers');



    })



    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });


    var hiddem_col_array = [3,5,7,8,9,10];
    if (window.location.search.length > 0) {
        var urlParams = new URLSearchParams(window.location.search);
    
    
        var decodedNumber = base64Decode(urlParams.get('id'));
        var action = urlParams.get('action');

        if(action == 'view'){
           // disableComponents();
        }
      
        
         /* getEachSalesInvoice(Invoice_id, status);
        getEachproduct(Invoice_id, status); */
        
    }
   /*  if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
       
        Invoice_id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#chkPrintReport').hide();
            $('#btnBack').show();
            $('#si_model_btn').hide();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
            $('#si_model_btn').hide();

        } else if (action == 'edit' && status == 'Draft') {
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
           
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
            $('#si_model_btn').hide();

        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
            $('#si_model_btn').hide();
            hiddem_col_array = [5, 9, 14, 13, 12, 11];
            disableComponents();

        }

       
    } */


    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:370px", "disabled": "disabled" },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);checkavailableQty(this);getItemID(this);itemsetOffontypeFunction(this);", "compulsory": true, "name": "qty" },
            { "type": "number", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);checkavailableQty(this);getItemID(this);itemsetOffontypeFunction(this)", "name": "foc", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:55px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled", "thousand_seperator": true },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:60px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-primary batchBtn", "value": "Batch&nbsp<span class='badge bg-yellow text-black translate-middle-middle  rounded-pill'style='padding:4px;'>" + whole_sale_count + "</span>", "style": "max-height:30px;max-width:70px;margin:0px;", "event": "setOffbybuton(this)", "width": "70px" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this);removeHashMapIndex(this);calculation()", "width": "*" }
        ],
        "auto_focus": 0,
        "hidden_col": hiddem_col_array


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
                if(arr.length == 1){
                    showWarningMessage("Please select a correct Item");
                    arr[i][0].focus();
                    return;
                }else if(arr.length > 1){
                    if(parseFloat(arr[i][2].val().replace(/,/g, '')) > 0){
                        showWarningMessage("Please select a correct Item");
                        arr[i][0].focus();
                        return;
                    }else{
                        continue;
                    }
                    
                }
               
            } else if (arr[i][7].val() == "" || arr[i][7].val() == "0" || arr[i][7].val() == "undefined" || arr[i][7].val() == "null" || parseFloat(arr[i][7].val()) == 0) {
                showWarningMessage("Price must be greter than 0");
                return;
            } else {
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": parseFloat(arr[i][2].val().replace(/,/g, '')),
                    "uom": arr[i][4].val(),
                    "PackUnit": arr[i][6].val(),
                    "PackSize": arr[i][5].val(),
                    "free_quantity": arr[i][3].val(),
                    "price": arr[i][7].val().replace(/,/g, ''),
                    "discount_percentage": arr[i][8].val(),
                    "discount_amount": parseFloat(arr[i][9].val().replace(/,/g, '')),
                }));


            }
        }

        var setOff_array = JSON.stringify(hash_map.toJsonArray());
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
                        newReferanceID('sample_dispatches', '1300');
                        addSample_dispatch(collection);
                    } else if ($('#btnSave').text() == 'Update') {
                        updateSI(collection, Invoice_id);

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
            $('#cmbLocation').trigger('change');
        },
    })
}



//add SI
function addSample_dispatch(collection) {

    var is_block = checkBlockStatus($('#cmbEmp').val(),$('#lblCustomerName').data('id'));
    if(is_block){
        $('#warning_alert').addClass('show');
        return;
    }

    //var order_id = $('#LblexternalNumber').attr('data-id');

    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an item');
        return
    }

    var arr = createSetoffCollection();

    var return_result = _validation($('#txtCustomerID'), $('#lblCustomerName'));

    if (return_result) {
        showWarningMessage("Please fill all required fields");
        return;
    } else {
        var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
        formData.append('collection', JSON.stringify(collection));
        formData.append('setOffArray', createSetoffCollection());
        formData.append('LblexternalNumber', referanceID); //external number
       // formData.append('SO_number', $('#LblexternalNumber').attr('data-id'));
        formData.append('invoice_date_time', $('#invoice_date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
       // formData.append('cmbEmp', $('#cmbEmp').val());
        formData.append('lblCustomerName', $('#lblCustomerName').val());
        formData.append('customerID', $('#lblCustomerName').data('id'));
       // formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
       // formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
      //  formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
      // formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
       // formData.append('grandTotal', total_amount);
        formData.append('locationID', locationID);
      //  formData.append('cmbPaymentMethod', $('#cmbPaymentMethod').val());
      //  formData.append('txtYourReference', $('#txtYourReference').val());
       // formData.append('code', $('#invoice_date_time').data('id'));
        formData.append('branch_code', $('#cmbBranch').data('id'));
        /* if(!isNaN(parseInt(order_id))){
            formData.append('order_id', order_id);
        } */
        //  var ext = $('#LblexternalNumber').val();

        $.ajax({
            url: '/sc/addSample_dispatch',
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
                //console.log(response);
                $('#btnSave').prop('disabled', false);
                var status = response.status
                var msg = response.message
                var primaryKey = response.primaryKey;
                if(msg == 'no order'){
                    showWarningMessage("Sales Order already completed");
                    return;
                }
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
                    
                   
             
                    hash_map = new HashMap();
                    url = "/sc/sample_dispatch_list";
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




function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event,"item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

//load item
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

//load item
function loadCustomerTOchooser() {

    var data = [];
    $.ajax({
        url: '/sd/loadCustomerTOchooser',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response) {
                var customerData = response.data;
                //console.log(customerData);
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
function loadCustomerOtherDetails(id, value) {

    $.ajax({
        url: '/sd/loadCustomerOtherDetails/' + value,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            //console.log(data)
            var txt = data.data;
            /*  console.log(txt); */
            var cus_name = id;
            var cus_town = txt[0].townName
            var nameAndTown = cus_name + "-" + cus_town;

            $('#lblCustomerAddress').val(txt[0].primary_address);
            var cusID = txt[0].customer_id;
            var payment_term_id_ = txt[0].payment_term_id;
            $('#lblCustomerName').attr('data-id', cusID);
            $('#lblCustomerName').val(nameAndTown);
        


        },
        error: function (error) {
            console.log(error);
        },

    })
}






function dataChooserShowEventListener(event) {
    if (pickOrderStatus) {
        DataChooser.dispose();
        pickOrderStatus = false;
    }

}



function dataChooserEventListener(event, id, value) {

    if ($(event.inputFiled).attr('id') == 'txtCustomerID') {
        loadCustomerOtherDetails(id, value);
    } else {


        var item_branch_id = $('#cmbBranch').val();
        var item_location_id = $('#cmbLocation').val();
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

        if (hash_map.get(item_id) != undefined) {
            showWarningMessage('Already exist');
            return;
        }
        // end of validate transacation table and hashmap


        resetTransactionTableRow(row_childs);
        $.ajax({
            url: '/sd/getItemInfoForInvoice/' + item_id + '/' + item_branch_id + '/' + item_location_id,
            type: 'get',
            success: function (response) {
                //console.log(response);
                var expireDateManage = response[0].manage_expire_date;
                if (expireDateManage == 1) {
                    $(row_childs[14]).removeAttr('disabled');
                }

                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[4]).val(response[0].unit_of_measure);
                $(row_childs[6]).val(response[0].package_unit);
                $(row_childs[5]).val(response[0].package_size);
                //  $(row_childs[7]).val(response[0].average_cost_price);
                $(row_childs[11]).val(response[0].Balance);
                $(row_childs[2]).focus();
                //  $(row_childs[2]).val('');



                console.log(hash_map);


            }
        })

    }


}

function resetTransactionTableRow(row) {

   
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
   


}

//foc calculation threshold (manually item insertion)
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
    var cus_id = $('#lblCustomerName').attr('data-id');

    $.ajax({
        url: '/sd/getItem_foc_threshold_ForInvoice/'+cus_id +"/"+ item_id + "/" + formatted_entered_qty + "/" + date,
        type: 'get',
        success: function (response) {
            // console.log(response);
            $.each(response, function (index, value) {
             
                //  $($(item_cell[3]).children()[0]).val(value.Offerd_quantity);
                $($(item_row.children()[3]).children()[0]).val(value.Offerd_quantity)

            })

        }
    })

}




//calculation prices
function calValueandCostPrice(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');

    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[7]).children()[0]);
    var discount_percentage = $($(cell[8]).children()[0]);
    var discount_amount = $($(cell[9]).children()[0]);

    var AMOUNT = getDiscountAmount(qty, price, discount_percentage, discount_amount);
    $($(cell[10]).children()[0]).val(AMOUNT);

    calculation();

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

function MainFunction(event) {


}

function getEachSalesInvoice(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sd/getEachSalesInvoice/' + id + '/' + status,
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
            //console.log(salesInv);
            var res = salesInv.data;
            var cus_name = res[0].customer_name;
            var cus_town = res[0].town_name;
            var cusFulName = cus_name + "-" + cus_town;

            $('#LblexternalNumber').val(res[0].external_number);
            $('#invoice_date_time').val(res[0].order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbPaymentTerm').val(res[0].payment_term_id);
            $('#txtRemarks').val(res[0].remarks);
            $('#txtDeliveryInst').val(res[0].delivery_instruction);
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            $('#txtYourReference').val(res[0].your_reference_number);
            $('#lblCustomerName').val(cusFulName);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of SI
function getEachproduct(id, status) {
    $.ajax({
        url: '/sd/getEachproductofSalesInv/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            //console.log(data);

            var dataSource = [];
            $.each(data, function (index, value) {
                //console.log(value.Item_code);
                var qty = Math.abs(value.quantity);
                var price = value.price;
                var free_qty = Math.abs(value.free_quantity);
                var disAmount = value.discount_amount;
                var valueS = (qty * price) - disAmount;


                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:370px", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": qty, "style": "max-height:30px;width:80px;text-align:right;margin-left:10px;", "compulsory": true, "event": "calValueandCostPrice(this);checkavailableQty(this);getItemID(this);itemsetOffontypeFunction(this);", "name": "qty" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": free_qty, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);checkavailableQty(this);getItemID(this);itemsetOffontypeFunction(this)", "name": "foc", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:55px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "max-height:30px;text-align:right;width:55px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_unit, "style": "max-height:30px;text-align:right;width:55px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(value.price).toFixed(2), "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_percentage, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": parseFloat(valueS).toFixed(2), "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:60px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:60px;margin-right:10px;", "event": "", "width": "*", "disabled": "" },
                    { "type": "button", "class": "btn btn-primary chkPrice", "value": "Batch", "style": "max-height:30px;margin-left:10px;margin-right:20px;", "event": "setOffbybuton(this)", "width": "*" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);removeHashMapIndex(this);calculation()", "width": "*" }

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



//approve
function approveRequestSalesInv(id) {
    $.ajax({
        url: '/sd/approveRequestSalesInv/' + id,
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
            //console.log(status);
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
function rejectRequestSalesInv(id) {
    $.ajax({
        url: '/sd/rejectRequestSalesInv/' + id,
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
            //console.log(status);
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
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#invoice_date_time').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}




function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_sample_dispatch", table, doc_number);
    // $('#LblexternalNumber').val(referanceID);
}


function getItemID(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    ItemID = $(cell[0]).children().eq(0).attr('data-id');
    //console.log(ItemID);


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


//set off (pick order also)
function setoffItem(collection, hashmap) {

    var items = hashmap.toArray();


    for (var count = 0; count < collection.length; count++) {
        var bool_whole_sale_price = true;
        var quantity = (parseFloat(collection[count].quantity) + parseFloat(collection[count].free_quantity));

        var item = hashmap.get(collection[count].item_id);


        var whole_sale_price = 0;
        var setoff_array = item.toArray();
        console.log(collection[count]);
        //for (var i = (setoff_array.length - 1); i >= 0; i--) {
        for (var i = 0; i < setoff_array.length; i++) {
            var setoffObject = setoff_array[i][1];
            if (bool_whole_sale_price) {
                whole_sale_price = setoffObject.getWholesalePrice();
                bool_whole_sale_price = false;
            }
            var available_quantity = parseFloat(setoffObject.getAvailableQuantity());
            if (available_quantity >= quantity) {
                if (whole_sale_price == setoffObject.getWholesalePrice()) {
                    setoffObject.setSetoffQuantity(quantity);
                    console.log("set off qty" + quantity);
                    quantity = 0;
                }
                //alert(setoffObject.getPrimaryID() + " - available quantity : " + setoffObject.getAvailableQuantity() + " - quantity : " + quantity);
            } else {
                quantity = (quantity - available_quantity);
                if (whole_sale_price == setoffObject.getWholesalePrice()) {
                    setoffObject.setSetoffQuantity(available_quantity);
                    console.log("avl qty" + available_quantity);
                }

                //alert(setoffObject.getPrimaryID() + " - available quantity : " + setoffObject.getAvailableQuantity() + " - quantity : " + available_quantity);

            }
        }

    }

    hash_map = hashmap;
    readHashMap(hash_map);

}

function readHashMap(hashmap) {
    var items = hashmap.toArray();
    for (var i2 = 0; i2 < items.length; i2++) {
        var array = items[i2][1].toArray();
        for (var i3 = 0; i3 < array.length; i3++) {
            var setoffObject = array[i3][1];
            //console.log("history_id : " + setoffObject.getPrimaryID() + " ItemID : " + setoffObject.getItemID() + " setoff quantity : " + setoffObject.getSetoffQuantity());
        }
    }

}


//create set of array
function createSetoffCollection() {


    var setoffCollection = [];
    var items = hash_map.toArray();

    for (var i2 = 0; i2 < items.length; i2++) {
        var array = items[i2][1].toArray();
        for (var i3 = 0; i3 < array.length; i3++) {
            var setoffObject = array[i3][1];
            if (setoffObject.getSetoffQuantity() > 0) {
                setoffCollection.push(
                    JSON.stringify({
                        "item_id": setoffObject.getItemID(),
                        "batch_no": setoffObject.getBatchNo(),
                        "wholesale_price": setoffObject.getWholesalePrice(),
                        "setoff_quantity": setoffObject.getSetoffQuantity(),
                        "cost_price": setoffObject.getCostPrice(),
                        "retail_price": setoffObject.getRetailPrice(),
                        "avilable_quantity": setoffObject.getAvailableQuantity(),
                        "history_id": setoffObject.getPrimaryID(),
                    })
                );
            }
        }
    }
    //console.log(setoffCollection);
    return JSON.stringify(setoffCollection);
}



var trigger = '';
function transactionTableButtonListener(event) {



    if (event.type == "click") {
        $('#batchModel').modal('show');
    }
    else if (event.type == 'focus') {

    }
}