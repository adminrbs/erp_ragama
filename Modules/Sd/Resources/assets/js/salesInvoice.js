
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
var ItemList = [];
var locationID;
var totalQty = 0;
var qty_object = undefined;
var foc_qty_threshold_from_pick_orders; // using to assign foc qty on pick orders
var branchID;
var whole_sale_count = 0;
var return_request_collection = [];
var invoiceNetBalance = null;
var exsitingsetoffvalue = 0;
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
            getItemHistorySetoffBatch(branchID, ItemID, locationID);

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
    var credit = 0;
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

            if (isNaN(parseFloat(foc))) {
                foc = 0;
            }


            var item = hash_map.get(item_id);
            if (item) {
                var array = item.toArray();
                var setoff_quantity = 0;
                var setWholesalePrice = 0;
                var setWholesalePrice_temp = 0;
                var setRetailPrice = 0;
                var avl_qty = 0;
                for (var i2 = 0; i2 < array.length; i2++) {
                    var setoffObject = array[i2][1];
                    setoff_quantity += parseFloat(setoffObject.getSetoffQuantity());
                    if (setoffObject.getSetoffQuantity() > 0) {
                        setWholesalePrice = parseFloat(setoffObject.getWholesalePrice());
                        setRetailPrice = parseFloat(setoffObject.getRetailPrice());
                    }
                    setWholesalePrice_temp = parseFloat(setoffObject.getWholesalePrice());  // use when user enter 0 set off qty

                    avl_qty += parseFloat(setoffObject.getAvailableQuantity());

                }
                var total_qty = parseFloat(qty_) + parseFloat(foc);

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
                    //alert(setoff_quantity);
                    // alert();
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
                    rowObjects[i][13].val(parseFloat(setRetailPrice).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
                    credit = credit + (parseFloat(setoff_quantity) * parseFloat(setWholesalePrice));
                    setCredit(credit);
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
    //alert('in');
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
                    console.log(item);

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



    checkWholeSalePrice(event);

    $('#batchModelTitle').text("Item Set Off Quantity" + " " + totalQty);
    TemprorySave(ItemID);
    getItemHistorySetoffBatch(branchID, ItemID, locationID);
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
                /* if (previous_whole_sale_price == null) {
                    previous_whole_sale_price = item.whole_sale_price;
                } else {
                    if (previous_whole_sale_price != item.whole_sale_price) {
                       // $('#batchModel').modal('show');
                        // val_ = 1;

                        // return val_;
                    }
                } */

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





/* //save in DB
function checkHashMap(item,primary){
     var item = hash_map.get(item);
    var setoffObj = item.get(primary); 
   var wholseSalePrice = setoffObj.getWholesalePrice();
    console.log(wholseSalePrice);
  
      

} */

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
    ItemList = loadItems(0);
    //DataChooser.addCollection("item",['Item', 'Code', 'Supply Group', '',''], ItemList);

    loadPamentTerm();

    getServerTime();
    getPaymentMethods();
    /* $('#btnBack').hide(); */

    $('#btnBack').on('click', function () {

        var url = "/sd/salesInvoiceList";
        window.location.href = url;


    });

    $('#cmbBank').on('change', function () {
        getBankBranch($(this).val());
    });
    //loadItems(0);

    customers = loadCustomerTOchooser();

    DataChooser.addCollection("Customer", ['Customer Name', 'Customer Code', 'Town', 'Route', ''], customers);
    //DataChooser.addCollection("item",['', '', '', '',''], ItemList);

    $('#cmbSalesAnalysist').on('change', function () {

        ItemList = loadItems($(this).val());
        DataChooser.addCollection("item", ['Item Name', 'Item Code', 'Avl Qty', '', ''], ItemList);
    });

    $('#txtcardNo ').on('input', function () {
        if ($(this).val().length > 5) {
            showWarningMessage("Please enter the last 4 / 5 digits of the card number");
            return false;

        }
    });
    loadSupplyGroupsAsSalesAnalyst();
    //getting branch code
    $('#cmbBranch').on('change', function () {
        var branch_id_ = $(this).val();
        get_branch_code(branch_id_);
        if ($(this).val() == "") {
            $('#si_model_btn').prop('disabled', true);
        } else {
            $('#si_model_btn').prop('disabled', false);
        }
    });

    /* newReferanceID('sales_invoices','210'); */

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
        loademployeesAccordingToBranch(id);

    });
    $('#cmbLocation').change();
    $('#cmbPaymentMethod').prop('selectedIndex', 2);


    getBranches();
    $('#cmbBranch').change();

    //get location id
    $('#cmbLocation').on('change', function () {
        locationID = $(this).val();

    });

    //add - to chq number
    $('#txtbank').on('input', function () {
        var firtVal = $(this).val();
        if (firtVal.length == 4) {
            $(this).val(firtVal + '-');
        } else if (firtVal.length == 8) {
            var parts = firtVal.split('-');
            var bankCode = parts[0];
            var bankBranchCode = parts[1];

            loadBankData(bankCode, bankBranchCode);
            return false;
        }
    });

    //getting rep code 
    $('#cmbEmp').on('change', function () {
        var rep_id = $(this).val();
        get_rep_code(rep_id);
    });

    //loademployees();
    $('#cmbEmp').change();

    $('#batchModel').on('hide.bs.modal', function () {

        setOff();
    })

    /* $('#data-chooser-modal').on('show.bs.modal', function () {

        if($('#data-chooser-modalLabel').text() == "Data Chooser"){
           
           
           
        }
        
    }) */

    $('#warningClose').on('click', function () {


        $('#warning_alert').removeClass('show');
    });



    //payment input
    /*  $('.paymentInput').on('change', function () {
         calDueBalance();
         calCredit();
         calCashBalance();
     }); */
    $('.paymentInput').on('change', function () {
        //calDueBalance();
        calCredit();
        calCashBalance();
    });
    $('#txttender').on('input', function () {

        calCashBalance();
    });

    //gross total
    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });

    $('#si_model_btn').on('click', function () {
        $('#warning_alert').removeClass('show');
        $('#lblCount').text("");
        var table = $('#gettable tbody');
        table.empty();
    });


    $('#txtCustomerID').on('focus', function () {
        DataChooser.commitData();
        DataChooser.showChooser($(this), $(this), "Customer");
        $('#data-chooser-modalLabel').text('Customers');


        /* var event = $.Event("keypress", { keyCode: 38 });
        $(document).trigger(event); */


    });



    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });


    var hiddem_col_array = [5, 9, 16];
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
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
            /*   $('#btnSaveDraft').show(); */
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
            hiddem_col_array = [5, 9, 14, 13, 12, 11, 16];
            disableComponents();

        }

        getEachSalesInvoice(Invoice_id, status);
        getEachproduct(Invoice_id, status);
    }


    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:370px", "disabled": "disabled" },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);checkavailableQty(this);getItemID(this);itemsetOffontypeFunction(this);foc_calculation_threshold(this)", "compulsory": true, "name": "qty" },
            { "type": "number", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);checkavailableQty(this);getItemID(this);itemsetOffontypeFunction(this);", "name": "foc" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:55px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled", "thousand_seperator": true },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:60px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-primary batchBtn", "value": "Batch&nbsp<span class='badge bg-yellow text-black translate-middle-middle  rounded-pill'style='padding:4px;'>" + whole_sale_count + "</span>", "style": "max-height:30px;max-width:70px;margin:0px;", "event": "setOffbybuton(this)", "width": "70px" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this);removeHashMapIndex(this);calculation()", "width": "*" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
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
                if (arr.length == 1) {
                    showWarningMessage("Please select a correct Item");
                    arr[i][0].focus();
                    return;
                } else if (arr.length > 1) {
                    if (parseFloat(arr[i][2].val().replace(/,/g, '')) > 0) {
                        showWarningMessage("Please select a correct Item");
                        arr[i][0].focus();
                        return;
                    } else {
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

        var tableBody = $('#rtn_item tbody');
        tableBody.find('tr').each(function () {
            var row = $(this);
            var checkbox = row.find('input[type="checkbox"]');

            if (checkbox.is(':checked')) {
                var rowData = {

                    sfa_return_request_items_id: row.find('td:first').attr('data-id'),
                    rep_id: row.find('td').eq(1).attr('data-id'),
                    item_id: row.find('td').eq(2).attr('data-id'),
                    qty: row.find('td').eq(5).text()
                };

                return_request_collection.push(rowData);
            }
        });

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
                        newReferanceID('sales_invoices', '210');
                        if (parseFloat($('#txtdueBalance').val().replace(/,/g, '')) == 0) {
                            addSalesInvoice(collection, Invoice_id, return_request_collection);
                        } else {
                            showWarningMessage("Please check the payment details");
                        }

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
                        newReferanceID('sales_invoice_drafts', '210');
                        addSalesInvoiceDraft(collection);

                    } else if ($('#btnSaveDraft').text() == 'Update Draft') {


                        updateSalesInvoiceDraft(collection, Invoice_id);

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
                    approveRequestSalesInv(Invoice_id);

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
                //console.log(result);
                if (result) {
                    rejectRequestSalesInv(Invoice_id);
                } else {

                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

    })


    getBank();

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
            if (data.length > 1) {
                $('#cmbBranch').append('<option value="">Select Branch</option>');
            }
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })
            $('#cmbBranch').change();
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
function addSalesInvoice(collection, id, return_request_collection) {
    var order_id = $('#LblexternalNumber').attr('data-id');

    /* var is_block = checkBlockStatus($('#cmbEmp').val(),$('#lblCustomerName').data('id'),order_id);
    if(is_block){
        //alert();
        $('#warning_alert').addClass('show');
        return;
    } */



    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an item');
        return
    }

    var arr = createSetoffCollection();

    /* bankTransferData()
cardData()
chequeData()
createReturnData() */

    var return_result = _validation($('#txtCustomerID'), $('#lblCustomerName'));

    if (1 == 2) {
        /*   showWarningMessage("Please fill all required fields");
          return; */
    } else {
        var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
        formData.append('return_request_collection', JSON.stringify(return_request_collection));
        formData.append('collection', JSON.stringify(collection));
        formData.append('setOffArray', createSetoffCollection());
        formData.append('returnData', createReturnData());
        formData.append('bankTransferData', bankTransferData());
        formData.append('cardData', cardData());
        formData.append('chequeData', chequeData());
        formData.append('credit', $('#txtCredit').val());
        formData.append('LblexternalNumber', referanceID);
        formData.append('SO_number', $('#LblexternalNumber').attr('data-id'));
        formData.append('invoice_date_time', $('#invoice_date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('cmbEmp', $('#cmbEmp').val());
        formData.append('lblCustomerName', $('#lblCustomerName').val());
        formData.append('customerID', $('#lblCustomerName').data('id'));
        formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
        formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
        formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
        formData.append('grandTotal', total_amount);
        formData.append('locationID', locationID);
        formData.append('cmbPaymentMethod', $('#cmbPaymentMethod').val());
        formData.append('txtYourReference', $('#txtYourReference').val());
        formData.append('code', $('#invoice_date_time').data('id'));
        formData.append('branch_code', $('#cmbBranch').data('id'));
        formData.append('sales_analyst_id', $('#cmbSalesAnalysist').val());
        formData.append('cash', $('#txtcash').val());
        formData.append('credit', $('#txtCredit').val())
        if (!isNaN(parseInt(order_id))) {
            formData.append('order_id', order_id);
        }

        console.log(formData);


        $.ajax({
            url: '/sd/addSalesInvoice/' + id,
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
                // $('#btnSave').prop('disabled', true);
            }, success: function (response) {
                //console.log(response);
                // $('#btnSave').prop('disabled', false);
                var status = response.status
                var msg = response.message
                var primaryKey = response.primaryKey;
                if (msg == 'no order') {
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

                    updateStatusOfOrder(order_id);

                    hash_map = new HashMap();
                    url = "/sd/salesInvoiceList";
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
function addSalesInvoiceDraft(collection) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', referanceID);
    formData.append('invoice_date_time', $('#invoice_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
    formData.append('grandTotal', $('#lblTotal').text());

    $.ajax({
        url: '/sd/addSalesInvoiceDraft',
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
    });
}

//item event listner

/* function itemEventListner(event) {

    //console.log(ItemList);
    DataChooser.setDataSourse(['','','',''],ItemList);
    DataChooser.showChooser(event, event);
    $('#data-chooser-modalLabel').text('Items')

} */

function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.commitData();
        DataChooser.showChooser(event, event, "item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

//load item
/* function loadItems() {
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
} */

function loadItems(id) {
    var list = [];
    var branch = $('#cmbBranch').val();
    var location = $('#cmbLocation').val();
    $.ajax({
        url: '/sd/loadItemsforsalesinvoice/' + id,
        type: 'get',
        async: false,
        data: {
            branch: branch,
            location: location
        },
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
/* function loadCustomerTOchooser() {

    var data = [];
    $.ajax({
        url: '/sd/loadCustomerTOchooser',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response) {
                var customerData = response.data;
                data = customerData;
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return data;
} */

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
            $('#cmbPaymentTerm').val(payment_term_id_);
            $('#cmbDeliverType').focus();

            var tableBody = $('#rtn_item tbody');
            tableBody.empty();
            console.log(tableBody);
            loadReturnRequest(cusID);
            loadSalesReturns(cusID);//Only for united pharma


        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load payment term
function loadPamentTerm() {
    $.ajax({
        url: '/sd/loadPamentTerm',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentTerm').append('<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })

}

function loademployeesAccordingToBranch(branch_id) {
    $('#cmbEmp').empty();
    $.ajax({
        url: '/sd/loademployeesAccordingToBranch/' + branch_id,
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

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
        url: '/sd/getItem_foc_threshold_ForInvoice/' + cus_id + "/" + item_id + "/" + formatted_entered_qty + "/" + date,
        type: 'get',
        success: function (response) {
            // console.log(response);
            $.each(response, function (index, value) {
                /*  var qty = parseFloat(value.quantity);
                 var foc = parseFloat(value.free_offer_quantity);
                 var calculated_foc = (foc / qty) * formatted_entered_qty;
                 var final_foc = parseInt(calculated_foc);
                 console.log(final_foc);
                 if (isNaN(final_foc)) {
                     final_foc = 0;
                 } */
                //  $($(item_cell[3]).children()[0]).val(value.Offerd_quantity);
                $($(item_row.children()[3]).children()[0]).val(value.Offerd_quantity);
                $($(item_row.children()[15]).children()[0]).val(value.Offerd_quantity)

            })

        }
    })

}

//foc calculation threshold (pick order insertion)
function foc_calculation_threshold_pick_order(cus_id, item_id, entered_qty) {
    var date = $('#invoice_date_time').val();


    $.ajax({
        url: '/sd/getItem_foc_threshold_ForInvoice/' + cus_id + "/" + item_id + "/" + entered_qty + "/" + date,
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
    //setDueBalance(netTotal);
    //setCredit(netTotal);
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
                    { "type": "text", "class": "transaction-inputs math-abs", "value": qty, "style": "max-height:30px;width:80px;text-align:right;margin-left:10px;", "compulsory": true, "event": "calValueandCostPrice(this);checkavailableQty(this);getItemID(this);itemsetOffontypeFunction(this);foc_calculation_threshold(this)", "name": "qty" },
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


//update SI
function updateSI(collection, id) {
    var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
    formData.append('collection', JSON.stringify(collection));
    formData.append('setOffArray', createSetoffCollection());
    formData.append('LblexternalNumber', referanceID);
    formData.append('invoice_date_time', $('#invoice_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
    formData.append('grandTotal', total_amount);
    formData.append('locationID', locationID);
    formData.append('cmbPaymentMethod', $('#cmbPaymentMethod').val());
    formData.append('txtYourReference', $('#txtYourReference').val());

    $.ajax({
        url: '/sd/updateSalesInvoice/' + id,
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
function updateSalesInvoiceDraft(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('invoice_date_time', $('#invoice_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
    formData.append('grandTotal', $('#lblTotal').text());

    $.ajax({
        url: '/sd/updateSalesInvoiceDraft/' + id,
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
            $('#dtBankTransferDate').val(formattedDate);
            $('#chqDate').val(formattedDate);


        },
        error: function (error) {
            console.log(error);
        },

    })
}




function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_SalesInvoice", table, doc_number);

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
        console.log(quantity);
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




//load payment methods to cmb
function getPaymentMethods() {
    $.ajax({
        url: '/sd/getPaymentMethods',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentMethod').append('<option value="' + value.customer_payment_method_id + '">' + value.customer_payment_method + '</option>');

            });
            // $('#cmbPaymentMethod').val(value.customer_payment_method_id);

        },
        error: function (error) {
            console.log(error);
        },

    })
}


var trigger = '';
function transactionTableButtonListener(event) {



    if (event.type == "click") {
        $('#batchModel').modal('show');
    }
    else if (event.type == 'focus') {

    }
}


function get_rep_code(id) {
    $.ajax({
        url: '/sd/get_rep_code/' + id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var res = data.data;
            $('#invoice_date_time').attr('data-id', res[0].code);


        },
        error: function (error) {
            console.log(error);
        },

    })
}


function get_branch_code(id) {
    $.ajax({
        url: '/sd/get_branch_code/' + id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var res = data.data;
            $('#cmbBranch').attr('data-id', res[0].code);


        },
        error: function (error) {
            console.log(error);
        },

    })
}


function openModalWithDelay() {
    // Add a delay of 1000 milliseconds (1 second) before opening the modal
    setTimeout(function () {
        // Trigger the modal to open
        $('#exampleModal').modal('show');
    }, 1000);
}

function check_foc_qty(event) {
    var row = $($(event).parent()).parent();
    var rowIndex = row.index();
    var cell = row.find('td');
    if ($(cell[15]).children().eq(0).val() == "") {
        showWarningMessage("Please enter quantitty first")
    } else {
        var row_foc = parseFloat($(cell[15]).children().eq(0).val());
        if (parseFloat($(event).val()) > row_foc) {
            showWarningMessage('FOC should be less than ' + row_foc);
            $(event).val(row_foc)

        }

    }


}

/* function loadSupplyGroupsAsSalesAnalyst(){
    $.ajax({
        url: '/loadSupplyGroupsAsSalesAnalyst',
        type: 'get',
        async: false,
        success: function (data) {
            $('#cmbSalesAnalysist').append('<option value="0">Select</option>');
            $.each(data, function (index, value) {
                $('#cmbSalesAnalysist').append('<option value="' + value.supply_group_id + '">' + value.supply_group + '</option>');

            });
            $('#cmbSalesAnalysist').trigger('change');
        },
    })
}
 */

function loadSupplyGroupsAsSalesAnalyst() {
    $.ajax({
        url: '/loadSupplyGroupsAsSalesAnalyst',
        type: 'get',
        async: false,
        success: function (data) {
            let fragment = document.createDocumentFragment();
            let selectElement = document.getElementById('cmbSalesAnalysist');
            let defaultOption = document.createElement('option');
            defaultOption.value = 0;
            defaultOption.textContent = 'Select';
            fragment.appendChild(defaultOption);

            $.each(data, function (index, value) {
                let option = document.createElement('option');
                option.value = value.supply_group_id;
                option.textContent = value.supply_group;
                fragment.appendChild(option);
            });

            selectElement.appendChild(fragment);
            $('#cmbSalesAnalysist').trigger('change');
        },
    });
}


function showInfoModel() {
    //$('#block_id_hidden_lbl').val(id);

    //load_block_info(id);
    $cus = $('#lblCustomerName').attr('data-id');
    if ($cus != undefined) {
        $('#block_customer_model_info').modal('show');
        loadOutstandingDataToTable($cus);
    } else {
        showWarningMessage('Please select a customer');
    }


}


function loadOutstandingDataToTable(id) {
    var table = $('#outstandingTable');
    var tableBody = $('#outstandingTable tbody');
    tableBody.empty();
    var br_id = $('#cmbBranch').val();
    $.ajax({
        url: '/sd/loadOutstandingDataToTable/' + id + '/' + br_id,
        method: 'get',
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            var dt = response.data;
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').text(item.trans_date));
                row.append($('<td>').text(item.external_number));
                row.append($('<td style="text-align:right">').text(parseFloat(item.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td style="text-align:right">').text(parseFloat(item.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').text(item.age));
                table.append(row);
            });
            $('body').css('cursor', 'default');



        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })

}

function loadReturnRequest(customer_id) {
    // alert();
    var tableBody = $('#rtn_item tbody');

    var table = $('#rtn_item');
    tableBody.empty();


    $.ajax({
        url: '/sd/loadReturnRequest/' + customer_id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var res = data.data;

            $.each(res, function (index, item) {


                var row = $('<tr>');
                row.append($('<td>').attr('data-id', item.sfa_return_request_items_id).text(item.request_date_time));
                row.append($('<td>').attr('data-id', item.employee_id).text(item.employee_name));
                row.append($('<td>').attr('data-id', item.item_id).text(item.Item_code));
                row.append($('<td>').text(item.item_Name));
                row.append($('<td>').text(item.package_unit));
                row.append($('<td>').text(Math.round(item.quantity)));
                row.append($('<td>').append($('<input class="form-check-input" type="checkbox" checked> ').attr('id', item.sfa_return_request_items_id)));




                table.append(row);
            });

        },
        error: function (error) {
            console.log(error);
        },

    })

}

function check_all(event) {
    var table = $('#rtn_item');
    if ($(event).prop('checked')) {

        table.find('tr:has(td)').each(function () {
            $(this).find('input[type="checkbox"]').prop('checked', true);
        });
    } else {

        table.find('tr:has(td)').each(function () {
            $(this).find('input[type="checkbox"]').prop('checked', false);
        });
    }
}


//load sales returns
function loadSalesReturns(customerId) {


    $.ajax({
        url: '/sd/loadSalesReturns/' + customerId,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var tableObjBody = $('#salesReturnTable tbody');
            tableObjBody.empty(); // Clear the table body
            var res = data.data;

            $.each(res, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').attr('data-id', item.debtors_ledger_id).text(item.trans_date));
                row.append($('<td>').text(item.external_number));
                row.append($('<td>').text(formatNumber(item.amount)));
                row.append($('<td>').text(formatNumber(item.balance)));
                row.append($('<td>').text(formatNumber(item.balance)));
                row.append($('<td>').append($('<input class="form-control" type="text" onchange="manageReturns(this);" oninput="calCredit();" onfocus="getExisitingsetoffValue(this)">').attr('id', item.debtors_ledger_id)));
                row.append($('<td>').append($('<input class="form-check-input" type="checkbox" onchange="manageReturns(this)">').attr('id', item.debtors_ledger_id)));
                tableObjBody.append(row); // Append to the table body
            });
        },
        error: function (error) {
            console.log(error);
        }
    });
}


// Helper function to format numbers
function formatNumber(value) {

    return Math.abs(value) // Remove the minus sign (absolute value)
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Add thousand separators
}


// 16/12/2024 - Allow to move onto payment side even returns are exist
function manageReturns(event) {


    //getting sum of payment side values (cash/cheque/bank/card)
    var paymentSideTotal = getSumOfPayments();
    var creditSum = $('#txtCredit').val().length > 0 ? parseFloat($('#txtCredit').val().replace(/,/g, '')) : 0;
    var row = $(event).closest('tr');
    var tableSum = getSumOfTable(row);
    if (invoiceNetBalance == null) {
        invoiceNetBalance = $('#lblNetTotal').text();
        invoiceNetBalance = parseFloat(invoiceNetBalance.replace(/,/g, ''));
    }

    var remainingBalanceCell = row.find('td').eq(4);
    var setOffCell = row.find('td').eq(5).find('input[type="text"]');

    //Checking for the html element. checkbox or textbox
    if (event.type == 'checkbox') {
        if ($(event).prop('checked')) {
            if (invoiceNetBalance == 0) {
                $(event).prop('checked', false);
                showWarningMessage("Amount exceeded");
                return false;

            }
            var remainingBalance = parseFloat(remainingBalanceCell.text().replace(/,/g, ''));
            /*  var tableOtherSetoffValues =  */
            if (invoiceNetBalance >= (remainingBalance + paymentSideTotal + tableSum)) {
                setOffCell.val(remainingBalance);
                invoiceNetBalance = invoiceNetBalance - (paymentSideTotal + remainingBalance + tableSum);
                remainingBalanceCell.text('0.00');
            } else {
                setOffCell.val(invoiceNetBalance);
                var remeaining_bal = remainingBalance - invoiceNetBalance
                invoiceNetBalance = invoiceNetBalance - (invoiceNetBalance + paymentSideTotal + tableSum)
                remainingBalanceCell.text(remeaining_bal);
            }
        } else {
            remainingBalanceCell.text(parseFloat(remainingBalanceCell.text().replace(/,/g, '')) + parseFloat(setOffCell.val().replace(/,/g, '')));
            invoiceNetBalance = invoiceNetBalance + parseFloat(setOffCell.val().replace(/,/g, ''));
            setOffCell.val(0);
        }
    } else {
        //Return setoff table
        if (!$(event).hasClass('paymentInput')) {
            if ($(event).val().length > 0) {
                var insertValue = parseFloat($(event).val().replace(/,/g, ''));
                var remainingBalance = parseFloat(remainingBalanceCell.text().replace(/,/g, ''));
                console.log(parseFloat(remainingBalanceCell.text().replace(/,/g, '')));
                console.log(remainingBalance + paymentSideTotal + tableSum + creditSum);

                if (invoiceNetBalance >= (remainingBalance + paymentSideTotal + tableSum)) {
                    //setOffCell.val(remainingBalance);
                    invoiceNetBalance = invoiceNetBalance - (remainingBalance + paymentSideTotal + tableSum);
                    var remBal = parseFloat(remainingBalanceCell.text().replace(/,/g, '')) - parseFloat(insertValue.replace(/,/g, ''));
                    console.log(remBal);

                    remainingBalanceCell.text(remBal);
                } else {

                    //setOffCell.val(invoiceNetBalance);
                    var remeaining_bal = remainingBalance - invoiceNetBalance
                    console.log(remainingBalance);
                    console.log(invoiceNetBalance);


                    invoiceNetBalance = invoiceNetBalance - (remainingBalance + paymentSideTotal + tableSum)
                    remainingBalanceCell.text(remeaining_bal);
                }


            } else {
                if (exsitingsetoffvalue != 0) {
                    var remBal = parseFloat(remainingBalanceCell.text().replace(/,/g, '')) + parseFloat(exsitingsetoffvalue);
                    console.log(remBal);
                    invoiceNetBalance = invoiceNetBalance + exsitingsetoffvalue;
                    remainingBalanceCell.text(remBal);
                }
            }

            //Payment side    
        } else {

        }
    }

}

function getExisitingsetoffValue(event) {
    if ($(event).val().length > 0) {
        exsitingsetoffvalue = parseFloat($(event).val().replace(/,/g, ''));
    }
    console.log(exsitingsetoffvalue);

}
//getting payment side values
function getSumOfPayments() {
    let sumOfPaymentSide = 0;
    $('.paymentInput').each(function () {
        let value = parseFloat($(this).val().replace(/,/g, '')) || 0;
        sumOfPaymentSide += value;
    });

    return sumOfPaymentSide;
}

function getSumOfTable(row) {
    let tableOtherSetoffValues = 0;
    let currentRow = row;
    $('#salesReturnTable tr').each(function () {
        let row = $(this);
        console.log(row);
        if (row.find('th').length > 0) {
            return; // Skip this iteration
        }

        // Skip the current row
        if (row.is(currentRow)) {
            return;
        }

        // Get the setoff cell value (4th column, index 3)
        var setOffCell = row.find('td').eq(5).find('input[type="text"]');
        // var setOffCell = row.find('input[type="text"]');
        console.log(setOffCell);


        // Parse the value, remove thousand separators, and add to the sum
        let value = parseFloat(setOffCell.val().replace(/,/g, '')) || 0; // Default to 0 if invalid
        tableOtherSetoffValues += value;
    });

    return tableOtherSetoffValues;
}

//set invoice value to due balance
function setDueBalance(value) {
    $('#txtdueBalance').val(parseFloat(value).toLocaleString('en-US'));
}

function setCredit(val) {
    $('#txtCredit').val(parseFloat(val).toLocaleString('en-US'));
}

/* function calCredit() {
    var tableSum = 0;
    $('#salesReturnTable tr').each(function () {
        let row = $(this);
        if (row.find('th').length > 0) {
            return; // Skip this iteration
        }


        
        var setOffCell = row.find('td').eq(5).find('input[type="text"]');
       
        


        // Parse the value, remove thousand separators, and add to the sum
        let value = parseFloat(setOffCell.val().replace(/,/g, '')) || 0; // Default to 0 if invalid
        tableSum += value;
    });
    var paymentSum = getSumOfPayments();
    console.log("payment" + paymentSum);
    console.log("table" + tableSum);
    
    
    var totalDue = paymentSum + tableSum;
    var due = parseFloat($('#txtCredit').val().replace(/,/g, ''));
    var balance_credit =   due - totalDue;
    setCredit(balance_credit);
}
 */

function calCredit() {
    var tableSum = 0;
    $('#salesReturnTable tr').each(function () {
        let row = $(this);
        if (row.find('th').length > 0) {
            return; // Skip this iteration
        }
        var setOffCell = row.find('td').eq(5).find('input[type="text"]');
        // Parse the value, remove thousand separators, and add to the sum
        let value = parseFloat(setOffCell.val().replace(/,/g, '')) || 0; // Default to 0 if invalid
        tableSum += value;
    });

    var paymentSum = getSumOfPayments();
    // var currentCredit = parseFloat($('#txtCredit').val().replace(/,/g, ''));
    var total = paymentSum + tableSum;
    var balance = parseFloat($('#lblNetTotal').text().replace(/,/g, '')) - total;
    setCredit(balance);
    calDueBalance();
}
//calculate due balance while seto ff returns and payment side

function calDueBalance() {


    if (invoiceNetBalance == null) {
        invoiceNetBalance = $('#lblNetTotal').text();
        invoiceNetBalance = parseFloat(invoiceNetBalance.replace(/,/g, ''));
    }
    var tableSum = 0;
    $('#salesReturnTable tr').each(function () {
        let row = $(this);
        console.log(row);
        if (row.find('th').length > 0) {
            return; // Skip this iteration
        }


        // Get the setoff cell value (4th column, index 3)
        var setOffCell = row.find('td').eq(5).find('input[type="text"]');
        // var setOffCell = row.find('input[type="text"]');
        console.log(setOffCell);


        // Parse the value, remove thousand separators, and add to the sum
        let value = parseFloat(setOffCell.val().replace(/,/g, '')) || 0; // Default to 0 if invalid
        tableSum += value;
    });
    var paymentSum = getSumOfPayments();
    var crd = parseFloat($('#txtCredit').val().replace(/,/g, '')) || 0;
    var totalDue = 0;
    if (crd < 0) {
        totalDue = paymentSum + tableSum;
    } else {
        totalDue = paymentSum + tableSum + crd;
    }
    /* if(crd < 0){
        var totalDue = paymentSum + tableSum - crd;
    }else{
        var totalDue = paymentSum + tableSum + crd;
    } */



    console.log(paymentSum);
    console.log(tableSum);
    console.log(crd);
    console.log(totalDue);


    var val_ = parseFloat($('#lblNetTotal').text().replace(/,/g, '')) - totalDue;

    invoiceNetBalance = invoiceNetBalance - val_;

    setDueBalance(val_);

    triggerPaymentMethod();


}

function calCashBalance() {

    var total = getSumOfPayments();
    var cash = parseFloat($('#txtcash').val().replace(/,/g, '')) || 0;
    var balance = total - cash;
    var tender = parseFloat($('#txttender').val().replace(/,/g, '')) || 0;
    var tenderBalance = tender - total;
    $('#cashBalance').val(tenderBalance.toLocaleString('en-US'));
    // calDueBalance();

}
function getBank() {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getBank',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var banks = response.data;
                $('#cmbCardIssueBank').empty();
                $('#txtbank').empty();
                for (var i = 0; i < banks.length; i++) {
                    var id = banks[i].bank_id;
                    var name = banks[i].bank_name;
                    $('#cmbCardIssueBank').append('<option value="' + id + '">' + name + '</option>');
                    $('#cmbBank').append('<option value="' + id + '">' + name + '</option>');
                }

                getBankBranch($('#cmbBank').val());
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}

function getBankBranch(bank_id) {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getBankBranch/' + bank_id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var banks = response.data;
                $('#cmbBankBranch').empty();
                for (var i = 0; i < banks.length; i++) {
                    var id = banks[i].bank_branch_id;
                    var name = banks[i].bank_branch_name;
                    $('#cmbBankBranch').append('<option value="' + id + '">' + name + '</option>');
                }
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}

function loadBankData(bankCode, branchCode) {
    $.ajax({
        type: "GET",
        url: '/sd/loadBankData/' + bankCode + '/' + branchCode,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                $('#cmbBank').val(response.bank);
                $('#cmbBankBranch').val(response.branch);
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }
    })

}


/* function createReturnData(){
    var returnData = [];

    $('#salesReturnTable tr').each(function () {
        let row = $(this);
      
        if (row.find('th').length > 0) {
            return; // Skip this iteration
        }

        // Get the setoff cell value (4th column, index 3)
        var setOffCell = row.find('td').eq(5).find('input[type="text"]');
        if(parseFloat(setOffCell.val().replace(/,/g, '')) != "" || parseFloat(setOffCell.val().replace(/,/g, '')) != 0 || parseFloat(setOffCell.val().replace(/,/g, '')) != '0'){
            returnData.push(
                JSON.stringify({
                    "amount":parseFloat(setOffCell.val().replace(/,/g, '')),
                    "debtors_ledger_id":setOffCell.attr('id')
                })
            )       
        }
        
    });

    return returnData;
} */

function createReturnData() {
    var returnData = [];

    $('#salesReturnTable tr').each(function () {
        let row = $(this);

        if (row.find('th').length > 0) {
            return; // Skip this iteration
        }

        // Get the setoff cell value (5th column, index 4)
        var setOffCell = row.find('td').eq(5).find('input[type="text"]');
        var setOffValue = parseFloat(setOffCell.val().replace(/,/g, ''));

        if (!isNaN(setOffValue) && setOffValue !== 0) {
            returnData.push(
                JSON.stringify({
                    "amount": setOffValue,
                    "debtors_ledger_id": setOffCell.attr('id')
                })
            );
        }
    });

    return JSON.stringify(returnData);
}

function chequeData() {
    chequeDetails = [];
    var parts = $('#txtbank').val().split('-');
    chequeDetails.push(JSON.stringify({
        "chequeAmount": $('#txtchqAmount').val(),
        "chequeNo": $('#chequeNo').val(),
        "chqDate": $('#chqDate').val(),
        "bankId": $('#cmbBank').val(),
        "bankbranchId": $('#cmbBankBranch').val(),
        "bankCode": parts[0]
    }));

    return chequeDetails;
}

function cardData() {
    cardDetails = [];
    cardDetails.push(JSON.stringify({
        "cardAmount": $('#txtcard').val(),
        "cardNo": $('#txtcardNo').val(),
        "cardIssueBank": $('#cmbCardIssueBank').val(),
        "type": $('#type').val()

    }));

    return cardDetails;
}

function bankTransferData() {
    bankDetails = [];
    bankDetails.push(JSON.stringify({
        "bankAMount": $('#txtBankTransferAmount').val(),
        "bankReference": $('#txtBankReference').val(),
        "bankTransferDate": $('#dtBankTransferDate').val()
    }));

    return bankDetails;
}

function triggerPaymentMethod() {
    var paymentTypes = [];
    var ReturntableVal = 0;

    // Check return data
    $('#salesReturnTable tr').each(function () {
        let row = $(this);
        if (row.find('th').length > 0) {
            return; // Skip this iteration
        }

       
        // Get the setoff cell value (5th column, index 4)
        var setOffCell = row.find('td').eq(5).find('input[type="text"]');

        // Parse the value, remove thousand separators, and add to the sum
        let value = parseFloat(setOffCell.val().replace(/,/g, '')) || 0; // Default to 0 if invalid
        ReturntableVal += value;
    });

    if (ReturntableVal > 0) {
        paymentTypes.push("Returns");
    }

    if (parseFloat($("#txtcash").val().replace(/,/g, '')) > 0) {
        paymentTypes.push("Cash");
    }

    if (parseFloat($("#txtcard").val().replace(/,/g, '')) > 0) {
        paymentTypes.push("Card");
    }

    if (parseFloat($("#txtBankTransferAmount").val().replace(/,/g, '')) > 0) {
        paymentTypes.push("Bank Transfer");
    }

    if (parseFloat($("#txtchqAmount").val().replace(/,/g, '')) > 0) {
        paymentTypes.push("Cheque");
    }

    if (parseFloat($("#txtCredit").val().replace(/,/g, '')) > 0) {
        paymentTypes.push("Credit");
    }

    if (paymentTypes.length > 1) {
        $('#cmbPaymentMethod').val(9);
        $('#cmbPaymentMethod').trigger('change');
    } else {
        if (paymentTypes.length === 1) {
            switch (paymentTypes[0]) {
                case "Returns":
                    $('#cmbPaymentMethod').val(1); 
                    break;
                case "Cash":
                    $('#cmbPaymentMethod').val(1); 
                    break;
                case "Card":
                    $('#cmbPaymentMethod').val(8); 
                    break;
                case "Bank Transfer":
                    $('#cmbPaymentMethod').val(7); 
                    break;
                case "Cheque":
                    $('#cmbPaymentMethod').val(2); 
                    break;
                case "Credit":
                    $('#cmbPaymentMethod').val(10); 
                    break;
                default:
                    $('#cmbPaymentMethod').val(1); 
                    break;
            }
            $('#cmbPaymentMethod').trigger('change');
        }
    }
}