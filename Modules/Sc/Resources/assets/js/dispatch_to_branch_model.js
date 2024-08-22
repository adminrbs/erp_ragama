

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


       


        var table = $('#gettable').DataTable({
           
            columnDefs: [
                {
                    width: '20%',
                    targets: 0,
                   
                },
                {
                    width: '20%',
                    targets: 1,
                    
                },
                {
                    width: 100,
                    targets: 2,
                    
                },
            
               
                

            ],
            autoWidth: true,
            scrollX: true,
            scrollY: '700px',
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "info":true,
            "paging": true,
            "pageLength": 100,
            "order": [],
            "columns": [
                
                { "data": "date" },
                { "data": "Order_no" },
                { "data": "from_branch" },
              

            ],
          
            "stripeClasses": ['odd-row', 'even-row']

        });



    };


    return {

        init: function () {
            _componentDatatableFixedColumns();
        },

        refresh: function () {
            if (table != undefined) {
                table.columns.adjust().draw();
            }
        }
    }
}();


// Initialize module
// ------------------------------
var BranchIDforGRN;
document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});

var GRN_id;
var hash_map = new HashMap();
var pickOrderStatus = false;
$(document).ready(function(){
    
    $('#btnReturnModel').on('click',function(){
        loadGRN();
    });



   

    $('#batchModel').on('hide.bs.modal', function () {

        setOff();
    });


  


});









$(document).keyup(function (event) {


    if (event.which === 115) { // 115 is the keycode for F4


        var focusedInput = $("input:focus");
        if (focusedInput.attr('name') == "qty" || focusedInput.attr('name') == "foc") {

            var b_id = $('#br_id').val();
            var _location_id_id = $('#cmbLocation').val();
            var row = $(focusedInput.parent()).parent();
            var rowIndex = row.index();
            $('#rowIndex').val(rowIndex);
            // console.log(row);
            var cell = row.find('td');
            var ItemID = $(cell[0]).children().eq(0).attr('data-id');
            // console.log(ItemID);
            $('#hiddenItem').val(ItemID);
            TemprorySave(ItemID);
            getItemHistorySetoffBatch(b_id, ItemID, _location_id_id);

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

//set set off qty to table
function setOff() {


    var rowObjects = tableData.getDataSourceObject();
    for (var i = 0; i < rowObjects.length; i++) {
        var item_id = rowObjects[i][0].attr('data-id');
         var disc_precen = rowObjects[i][8].val();
        var qty_ = rowObjects[i][2].val();
        var foc = rowObjects[i][3].val();
       
       
       if(isNaN(foc) || foc == ""){
        foc = 0;
       }
      
      

        var item = hash_map.get(item_id);
        if (item) {
            var array = item.toArray();
            var setoff_quantity = 0;
            var setWholesalePrice =0;
            var setRetailPrice = 0;
            var setcost_price = 0;
            var setWholesalePrice_temp = 0;
            var avl_qty = 0;
            for (var i2 = 0; i2 < array.length; i2++) {
                var setoffObject = array[i2][1];
                setoff_quantity += parseFloat(setoffObject.getSetoffQuantity());
                if(setoffObject.getSetoffQuantity() > 0){
                    setWholesalePrice = parseFloat(setoffObject.getWholesalePrice());
                    setRetailPrice = parseFloat(setoffObject.getRetailPrice());
                    setcost_price = parseFloat(setoffObject.getCostPrice());
                }
                
                setWholesalePrice_temp = parseFloat(setoffObject.getWholesalePrice());  // use when user enter 0 set off qty
                
                avl_qty += parseFloat(setoffObject.getAvailableQuantity());
            }
            var total_qty = parseFloat(qty_) + parseFloat(foc);
           
            if(parseFloat(total_qty) < parseFloat(setoff_quantity)){
               // showWarningMessage("Set off quantity should be same to the total quantity");
             //  setoffObject.setSetoffQuantity(parseFloat(avl_qty));
                return;
            }
            if(setoff_quantity == 0){
                // alert(setWholesalePrice_temp);
                // setoffObject.setSetoffQuantity(parseFloat(total_qty));
                 setWholesalePrice = parseFloat(setWholesalePrice_temp); 
                 
             
             }
             if(parseFloat(total_qty) > parseFloat(setoff_quantity)){
                showWarningMessage("Batch is not selected properly");
                rowObjects[i][10].val(Math.abs(0));
               
              
             }else if(parseFloat(total_qty) != parseFloat(setoff_quantity)){
                showWarningMessage("Batch is not selected properly");
                rowObjects[i][10].val(Math.abs(0));
               /*  setoffObject.setSetoffQuantity(parseFloat(total_qty)); */
                return;

            }else{
              //  var disc_amount = parseFloat((setWholesalePrice * qty_)) * parseFloat(disc_precen / 100);
                var value = (parseFloat(qty_) * parseFloat(setWholesalePrice));
                
                rowObjects[i][10].val(Math.abs(setoff_quantity));
                rowObjects[i][4].val(parseFloat(setWholesalePrice).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                rowObjects[i][13].val(parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                rowObjects[i][14].val(parseFloat(setWholesalePrice).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                rowObjects[i][15].val(parseFloat(setRetailPrice).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                rowObjects[i][16].val(parseFloat(setcost_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }
           
        }
    }

    calculation();
}


//set of automatically without model
function setOffAutoMatically(collection) {
    console.log(collection);

    //var hashmap = new HashMap();
    var branchID = $('#cmbBranch').val();
    var loca_id = $('#cmbLocation').val();
    for (var i = 0; i < collection.length; i++) {

        $.ajax({
            type: "GET",
            url: "/prc/getItemHistorySetoffBatch/" + branchID + "/" + collection[i].item_id + "/" + loca_id,
            cache: false,
            timeout: 800000,
            async: false,
            beforeSend: function () { },
            success: function (response) {

                var dt = response.data
                console.log(response);
                var itemobj = new Item();
                $.each(dt, function (index, item) {
                    console.log(item);

                   
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

//set item to table
function setItems(collection) {
   console.log(collection);
    var dataSource = [];
    for (var i = 0; i < collection.length; i++) {
        // console.log('text item')
        var internal_order_items_id = collection[i].internal_order_items_id;
        var item_code = collection[i].Item_code;
        var item_id = collection[i].item_id;
        var item_name = collection[i].item_name;
        var quantity = parseFloat(collection[i].quantity);
        var from_sales = Math.abs(collection[i].from_sales);
        var to_sales = collection[i].to_sales;
        var from_balance = collection[i].from_balance;
        var package_unit = collection[i].package_unit;
        var to_balance = collection[i].to_balance;
      console.log(from_sales);
      
        if(isNaN(quantity)){
            quantity = 0
        }
        if (from_balance <= 0) {
            
            continue;
        }else if((parseFloat(quantity)) > parseFloat(from_balance)){
            quantity = from_balance;
        }
       
        dataSource.push([
          


            { "type": "text", "class": "transaction-inputs", "value": item_code, "data_id": item_id,"style": "width:100px;", "event": "","valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": item_name, "data_id": internal_order_items_id,"style": "width:350px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": quantity, "style": "width:55px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);","compulsory":true },
            
            { "type": "text", "class": "transaction-inputs","value": package_unit, "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:70px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);", "disabled": "disabled" },    
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs","value": from_sales, "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs","value": to_sales, "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs","value": from_balance, "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs","value": to_balance, "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            
           
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            
        
            { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
        
        
           
        ]);
    }
    
    
    console.log(dataSource);
    tableData.setDataSource(dataSource);
    pickOrderStatus = true;
}


var P_price = 0;
//get previouse purchase price
function get_Pr_price(id){
    $.ajax({
        type: "GET",
        url: "/prc/get_Pr_price/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (data) {
            var res = data
            $.each(res, function (index, item) {
                P_price = item.previouse_purchase_price
            });
            return P_price;
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });


}
//load hearder details
function getHeaderDetails(id) {
    /* var table = $('#gettableItems');
    var tableBody = $('#gettableItems tbody');
    tableBody.empty(); */
    $.ajax({
        type: "GET",
        url: "/prc/getheaderDetailsReturn/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (data) {
            var res = data.data
            $('#cmbBranch').val(res[0].branch_id)
            $('#cmbLocation').val(res[0].location_id);
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtSupplier').val(res[0].supplier_code);
            $('#lblSupplierName').val(res[0].supplier_name);
            $('#lblSupplierAddress').val(res[0].primary_address);
            $('#txtPurchaseORder').val(res[0].purchase_order_id);
            $('#txtSupplierInvoiceNumber').val(res[0].supppier_invoice_number);
            $('#txtSupplierInvoiceAmount').val(res[0].invoice_amount);
            $('#dtPaymentDueDate').val(res[0].payment_due_date);
            $('#cmbPaymentType').val(res[0].payment_mode_id);
            
            /* $('#txtDiscountPrecentage').val(res[0].discount_percentage); */
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#txtSupplier').attr('data-id', res[0].supplier_id);
            $('#LblexternalNumber').attr('data-id', id);



        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });

}


//set of by button to show model (batch button)
function setOffbybuton(event) {

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

    $('#hiddenItem').val(ItemID);
    $('#rowIndex').val(rowIndex);

    totalQty = qty + foc
    br_id = $('#cmbBranch').val();
    var loc_id = $('#cmbLocation').val();
     

    


    $('#batchModel').modal('show');

     $('#batchModelTitle').text("Item Set Off Quantity" + " " + totalQty);
    TemprorySave(ItemID);
    getItemHistorySetoffBatch(br_id, ItemID,loc_id);
    autoSetOff(ItemID); 
}


//remove hash map index
function removeHashMapIndex(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var ItemID = $(cell[0]).children().eq(0).attr('data-id');
    hash_map.remove(ItemID);
}


/**Item History set off */
function getItemHistorySetoffBatch(branchID, ItemID,location_id_) {


    var table = $('#batchTableData');
    var tableBody = $('#batchTableDataBody');
    tableBody.empty();

    $.ajax({
        type: "GET",
        url: "/sd/getItemHistorySetoffBatch/" + branchID + "/" + ItemID +"/"+location_id_,
        cache: false,
        timeout: 800000,
        async: false,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data
            var itemObject = hash_map.get(ItemID);
            $.each(dt, function (index, item) {




                var itemSetOff = new ItemSetoff();
                itemSetOff.setPrimaryID(item.item_history_setoff_id);
                itemSetOff.setItemID(ItemID);
                itemSetOff.setBatchNo(item.batch_number);
                itemSetOff.setWholesalePrice(item.whole_sale_price);
                itemSetOff.setSetoffQuantity(0);
                itemSetOff.setCostPrice(item.cost_price);
                itemSetOff.setRetailPrice(item.retial_price);
                itemSetOff.setAvailableQuantity(item.AvlQty);
                itemObject.add(itemSetOff);

            });



        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

//set off automatically according to whole sale price in the model
function autoSetOff(item_id) {

    $('#batchTableDataBody').empty();
    var item = hash_map.get(item_id);
    var array = item.toArray();

    var str_item_id = "'" + item_id + "'";
    //for (var i3 = (array.length - 1); i3 >= 0; i3--) {
    for (var i3 = 0; i3 < array.length; i3++) {
        var setoffObject = array[i3][1];
        var str_history_primary_id = "'" + setoffObject.getPrimaryID() + "'";
        var row = '<tr>';
        row += '<td data-id="' + setoffObject.getPrimaryID() + '">' + setoffObject.getBatchNo() + '</td>';
        row += '<td>' + parseFloat(setoffObject.getCostPrice()).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
        row += '<td>' + parseFloat(setoffObject.getWholesalePrice()).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
        row += '<td>' + parseFloat(setoffObject.getRetailPrice()).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
        row += '<td>' + parseFloat(setoffObject.getAvailableQuantity()).toFixed(0) + '</td>';
        row += '<td><input type="number" style="text-align:right;" class="transaction-inputs" value="' + setoffObject.getSetoffQuantity() + '" oninput="resetoff(this,' + str_item_id + ',' + str_history_primary_id + ')" onkeyup="allowOnlyNumbers(this)" disabled></td>';
        row += '</tr>'
        $('#batchTableDataBody').append(row);
    }


}


//check different whole sale prices
function checkWholeSalePrice(event) {

    var row = $($(event).parent()).parent();
    var rowIndex = row.index();
    var cell = row.find('td');
    var ItemID = $(cell[0]).children().eq(0).attr('data-id');
    branchID = $('#cmbBranch').val();
    var location = $('$cmbLocation').val();
    // var val_ = 0;

    $.ajax({
        type: "GET",
        url: "/sd/getItemHistorySetoffBatch/" + branchID + "/" + ItemID + "/" + location,
        cache: false,
        timeout: 800000,
        async: false,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data

            var previous_whole_sale_price;
            $.each(dt, function (index, item) {
                if (previous_whole_sale_price == null) {
                    previous_whole_sale_price = item.whole_sale_price;
                } else {
                    if (previous_whole_sale_price != item.whole_sale_price) {
                       // $('#batchModel').modal('show');
                        // val_ = 1;

                        // return val_;
                    }
                }


            });



        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

    //   return parseInt(val_);
}



//allow numbers only

function allowOnlyNumbers(inputElement) {
    let inputValue = $(inputElement).val();
    inputValue = inputValue.replace(/[^0-9]/g, "");
    $(inputElement).val(inputValue);
  }


function resetoff(event, item_id, history_id) {

    // alert(" ItemID : " + item_id + " PrimaryID : " + history_id);
    var item = hash_map.get(item_id);
    var setoffObject = item.get(history_id);
    var setoff_quantity = parseFloat($(event).val());
    if (isNaN(setoff_quantity)) {
        setoff_quantity = 0;
    }
    setoffObject.setSetoffQuantity(setoff_quantity);
    balanceQuantity(item_id);

}

function balanceQuantity(item_id) {
    var item = hash_map.get(item_id);
    var array = item.toArray();
    var setoff_total_quantity = 0;
    for (var i = 0; i < array.length; i++) {
        var setoffObject = array[i][1];
        setoff_total_quantity += setoffObject.getSetoffQuantity();
    }
    var setOff_blnc = totalQty - setoff_total_quantity;
   // $('#lblBalance').text(setOff_blnc);
}

function displayBalance(totalQty) {

    var totalSetOffAmount = 0;
    var balance = 0;

    $('#batchTableData tbody tr').each(function (index, row) {
        var columns = $(row).find('td');
        var abl_qty = $(columns[4]).text();
        var setOffAmount = $(columns[5]).find('input.transaction-inputs');
        var currentValue = parseFloat(setOffAmount.val());
        if (abl_qty < currentValue) {
            showWarningMessage("Insufficent Balance");
        }

        if (!isNaN(currentValue)) {
            totalSetOffAmount += currentValue;
        }

        balance = totalSetOffAmount - totalQty;
      //  $('#lblBalance').text(balance);
    });
}


function setoffItem(collection, hashmap) {
console.log(hashmap);
    var items = hashmap.toArray();


    for (var count = 0; count < collection.length; count++) {
        var bool_whole_sale_price = true;
        var from_balance = parseFloat(collection[count].from_balance);
        var quantity = parseFloat(collection[count].quantity);

        if((parseFloat(quantity)) > parseFloat(from_balance)){
            quantity = from_balance;
           
        }

       
        var item = hashmap.get(collection[count].item_id);
        console.log(item);
        var whole_sale_price = 0;
        var setoff_array = item.toArray();
        //for (var i = (setoff_array.length - 1); i >= 0; i--) {
        for (var i = 0; i < setoff_array.length; i++) {
            var setoffObject = setoff_array[i][1];
            if (bool_whole_sale_price) {
                whole_sale_price = setoffObject.getWholesalePrice();
                bool_whole_sale_price = false;
            }
            var available_quantity = parseFloat(setoffObject.getAvailableQuantity());
           // console.log("avl"+available_quantity);
            if (available_quantity >= quantity) {
                if (whole_sale_price == setoffObject.getWholesalePrice()) {
                    setoffObject.setSetoffQuantity(quantity);
                    quantity = 0;
                }
                //alert(setoffObject.getPrimaryID() + " - available quantity : " + setoffObject.getAvailableQuantity() + " - quantity : " + quantity);
            } else {
                quantity = (quantity - available_quantity);
                if (whole_sale_price == setoffObject.getWholesalePrice()) {
                    setoffObject.setSetoffQuantity(available_quantity);
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
            console.log("history_id : " + setoffObject.getPrimaryID() + " ItemID : " + setoffObject.getItemID() + " setoff quantity : " + setoffObject.getSetoffQuantity());
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
    console.log(setoffCollection);
    return JSON.stringify(setoffCollection);
}


function itemsetOffontypeFunction(event) {

    $(event).focusout(function () {
        var td_parent = $(event).parent();
        var row_parent = td_parent.parent();

        var itemID = $($(row_parent.children()[0]).children()[0]).attr('data-id');
        var quantity = parseFloat($($(row_parent.children()[2]).children()[0]).val());
       // var foc = parseFloat($($(row_parent.children()[3]).children()[0]).val());
        if(isNaN(quantity)){
            quantity = 0;
        }
       /*  if(isNaN(foc)){
            foc = 0;
        } */
      var  foc = 0;
       
        var collection = [{ "item_id": itemID, "quantity": quantity, "free_quantity": foc }];
        setOffAutoMatically(collection);
    });

}

function getItemID(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    ItemID = $(cell[0]).children().eq(0).attr('data-id');
    //console.log(ItemID);


}


function selectedData(orderID) {
    console.log('in');
    var is_insufficeint = 0;
  
  
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var branchID_ = $('#cmbBranch').val();
    var _location_id = $('#cmbLocation').val();
    var to_branch = $('#cmb_to_Branch').val();
    var to_location = $('#cmb_to_Location').val();
    var selectedIds = [];

    $('#gettableItemsbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.prop('checked')) {
            var avlQ = $(this).find('td:eq(2)').text();
            var _qty = $(this).find('td:eq(3) input[type="text"]').val();
           
            if(parseFloat(avlQ) < parseFloat(_qty)){
                showWarningMessage("Insufficient Balance");
                $(this).removeAttr('class').addClass('highlight-row');
                is_insufficeint = 1
               
            }
            var dataId = checkbox.attr('id');
            selectedIds.push(dataId);
        }
        
    });



    if(is_insufficeint != 1){
        $.ajax({
            type: "get",
            url: "/sc/getItemsFordispatchtable/" + branchID_ + "/"+_location_id+"/" + orderID + "/" + to_branch + "/" + to_location,
            data: { 'Item_ids': JSON.stringify(selectedIds),
                    'from_date':from_date,
                    'to_date':to_date
            
                },
            async: false,
            beforeSend: function () { },
            success: function (response) {
                //  alert('r');
                //  console.log(response);
                var dt = response.data;
                console.log(dt);
    
                setItems(dt);
                // setOffAutoMatically(dt)
                for (var i = 0; i < dt.length; i++) {
                    console.log(dt[i].setOffData);
                    var item_setOffData = dt[i].setOffData;
                    var itemobj = new Item();
                    
                    for (var j = 0; j < item_setOffData.length; j++) {
                        var item = item_setOffData[j];
                       
                        if (item != undefined) {
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
    
                        }
                    }
    
                    hash_map.put(dt[i].item_id, itemobj);
    
                }
                setoffItem(dt, hash_map)
                setOff();
    
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () {
    
    
            }
        });
        
    }else{
        selectedIds = [];
    }
    calculation();

    $('#exampleModal').modal('hide');
}


function TableRefresh() {
  //  alert();
    var table = $('#gettable').DataTable();
    table.columns.adjust().draw();
    $('.dataTables_scrollBody').css('height', '150px');
}