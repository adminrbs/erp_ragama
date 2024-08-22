

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
        var table = $('.datatable-fixed-both-GRN_TableData').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 200,
                    targets: [2]
                },

            ],
            autoWidth: false,
            scrollX: true,
            scrollY: 150,
            scrollCollapse: false,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "Date" },
                { "data": "external_No" },
                { "data": "supplier_name" },
                { "data": "prepared_by" },
                { "data": "action" }

            ],
            "stripeClasses": ['odd-row', 'even-row']

        });
        //  table.column(0).visible(false);



    };


    return {
        init: function () {
            _componentDatatableFixedColumns();
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



    $('#GRN_TableData').on('click', 'tr', function (e) {
        // Check if the click occurred on index 5 or 6 (6th and 7th cell)
        if ($(e.target).closest('td').index() === 4 || $(e.target).closest('td').index() === 6) {
            return; // Do nothing if the click was on cell 5 or 6
        }
    
        $('#GRN_TableData tr').removeClass('selected');
    
        // Add the selected class to the clicked row
        $(this).addClass('selected');
        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children();
    
        childElements.each(function () {
            GRN_id = $(this).attr('data-id');
           
             getGRN_Items(GRN_id); 
        });
    
        //select all
        $('#gettableItems th input[type="checkbox"]').click(function () {
            // Get the checked state of the header checkbox
            var isChecked = $(this).prop('checked');
            $('#gettableItems td input[type="checkbox"]').prop('checked', isChecked);
        });
    });

    $('#batchModel').on('hide.bs.modal', function () {

        setOff();
    });


    $('#btnGetData').on('click',function(){
        
        selectedData_grnReturn(GRN_id);
    });



});




//load GRN to model (return model) according to branch id
function loadGRN(){
    BranchIDforGRN = $('#cmbBranch').val();
    
    $.ajax({
        url: '/prc/loadGRN/'+BranchIDforGRN,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
            
                data.push({
                    "Date": "<div data-id='" + dt[i].goods_received_Id + "'>" + dt[i].goods_received_date_time + "</div>",
                    "external_No": dt[i].external_number,
                    "supplier_name": dt[i].supplier_name,
                    "prepared_by": dt[i].name,
                    "action": '<div class="dropdown position-static">' +
                        '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i>' +
                        '</a>' +
                        '<div class="dropdown-menu dropdown-menu-end">' +
                        '<a class="dropdown-item"  onclick="selectedData_grnReturn(' + dt[i].goods_received_Id + ')">Add Data</a>' +
                        '</div>'
                });

            }

            var table = $('#GRN_TableData').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

//load grn items to return model table on tr click event
function getGRN_Items(grn_id){
    var table = $('#gettableItems');
    var tableBody = $('#gettableItems tbody');
    tableBody.empty();
    $.ajax({
        type: "GET",
        url: "/prc/getGRN_Items/" + grn_id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (data) {
            var dt = data.data
            console.log(dt);
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.item_id).text(item.Item_code)));
                row.append($('<td>').text(item.item_name));
                row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(item.quantity)));
                row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(item.free_quantity)));
                row.append($('<td>').text(item.unit_of_measure));
                row.append($('<td>').text(item.package_unit));
                row.append($('<td>').text(item.package_size));
                row.append($('<td>').text(item.price));
                row.append($('<td>').text(item.discount_percentage));
                row.append($('<td>').text(item.discount_amount));
                row.append($('<td>').text(item.Value));
                row.append($('<td>').append($('<input>').attr('type', 'checkbox').val(item.item_id).prop('checked', true)));
                table.append(row);
            });

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });

}

//add checked items to array
function selectedData_grnReturn(grn_id) {
    
    var branch_id = $('#cmbBranch').val();
    var location_id_ = $('#cmbLocation').val();
    var selectedIds = [];

    $('#gettableItemsbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.prop('checked')) {
            var dataId = $(this).find('label').data('id');
            selectedIds.push(dataId);
        }
    });



    $.ajax({
        type: "get",
        url: "/prc/get_selectedItem_grnReturn/" + branch_id + "/" + grn_id +"/" + location_id_,
        data: { 'Item_ids': JSON.stringify(selectedIds) },
        async: false,
        beforeSend: function () { },
        success: function (response) {
            //  console.log(response);
            var dt = response.data;
            console.log(dt);

            setItems(dt);
          // setOffAutoMatically(dt)
          for(var i=0;i<dt.length;i++){
            console.log(dt[i].setOffData);
             var item_setOffData = dt[i].setOffData;
             var itemobj = new Item();
            // console.log(item == undefined);
            for(var j=0;j<item_setOffData.length;j++){
                var item = item_setOffData[j];
                if(item != undefined){
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
    calculation();
    $('#GRN_return_model').modal('hide');
   

}

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
            var setWholesalePrice_temp = 0;
            var avl_qty = 0;
            for (var i2 = 0; i2 < array.length; i2++) {
                var setoffObject = array[i2][1];
                setoff_quantity += parseFloat(setoffObject.getSetoffQuantity());
                if(setoffObject.getSetoffQuantity() > 0){
                    setWholesalePrice = parseFloat(setoffObject.getWholesalePrice());
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
             if(parseFloat(total_qty) != parseFloat(setoff_quantity)){
                showWarningMessage("Batch is not selected properly");
               /*  setoffObject.setSetoffQuantity(parseFloat(total_qty)); */
                return;

            }else{
                var disc_amount = parseFloat((setWholesalePrice * qty_)) * parseFloat(disc_precen / 100);
                var value = (parseFloat(qty_) * parseFloat(setWholesalePrice)) - parseFloat(disc_amount);
                
                rowObjects[i][7].val(setoff_quantity);
                rowObjects[i][4].val(parseFloat(setWholesalePrice).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                rowObjects[i][11].val(parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

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
    var foc_qty = 0;

    var dataSource = [];
    for (var i = 0; i < collection.length; i++) {
        // console.log('text item')
        var item_code = collection[i].Item_code;
        var item_id = collection[i].item_id;
        var item_name = collection[i].item_name;
        var quantity = parseFloat(collection[i].quantity);
        var free_quantity = parseFloat(collection[i].free_quantity);
        var unit_of_measure = collection[i].unit_of_measure;
        var pack_size = collection[i].package_unit;
        var package_size = collection[i].package_size;
        var price = parseFloat(collection[i].price).toFixed(2);
        var discount_percentage = collection[i].discount_percentage;
        var discount_amount = collection[i].discount_amount;
        var values = parseFloat((quantity * price) - discount_amount).toFixed(2);
        var balance = collection[i].Balance;
        foc_qty = foc_calculation_threshold_pick_order(item_id, parseFloat(quantity));
        console.log(balance);
        var pre_pr = get_Pr_price(item_id);
        var purchase_order_item_id = collection[i].purchase_order_item_id;
        if(isNaN(free_quantity)){
            free_quantity = 0
        }
        if(isNaN(quantity)){
            quantity = 0
        }
        var total_qty_foc = parseFloat(quantity) + parseFloat(free_quantity);
        if(balance == 0){
            continue;
        }else{
        dataSource.push([
            { "type": "text", "class": "transaction-inputs", "value": item_code, "data_id": item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "DataChooser.showChooser(this,this)" },
            { "type": "text", "class": "transaction-inputs", "value": item_name, "data_id": purchase_order_item_id, "style": "max-height:30px;margin-left:10px", "event": "", "style": "width:350px", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": Math.abs(quantity), "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": Math.abs(foc_qty), "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)" },
            { "type": "text", "class": "transaction-inputs", "value": unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": package_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": pack_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": pre_pr, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "class": "transaction-inputs math-abs", "value": discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*","disabled":"disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*",  },
            { "type": "text", "class": "transaction-inputs", "value": balance, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*",  },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*",  },
            { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-left:10px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:20px;", "event": "removeRow(this);calculation();", "width": 30 }

        ]);
    }
    }
    tableData.setDataSource(dataSource);
     getHeaderDetails(GRN_id);
 
    //setOffAutoMatically(collection);
    calculation();
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

    var items = hashmap.toArray();


    for (var count = 0; count < collection.length; count++) {
        var bool_whole_sale_price = true;
        var quantity = (parseFloat(collection[count].quantity) + parseFloat(collection[count].free_quantity));

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
