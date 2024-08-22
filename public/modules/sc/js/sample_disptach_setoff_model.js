

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
        var table = $('.datatable-fixed-both-getdata').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "25px");
            },
            columnDefs: [
                {
                    width: 100,
                    targets: 0
                },
                {
                    width: 1000,
                    targets: 1
                },
                {
                    width: 100,
                    targets: 2
                },
                {
                    width: 100,
                    targets: 6
                },
                {
                    width: 100,
                    targets: 7
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '5px');
                    }
                },

            ],
            autoWidth: false,
            scrollX: true,
            scrollY: '500px',
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
                { "data": "id" },
                { "data": "date" },
                { "data": "Order_no" },
                { "data": "customer_name" },
                { "data": "rep_name" },
                { "data": "amount" },
                { "data": "deliver_date" },
                { "data": "action" }

            ],
          
            "stripeClasses": ['odd-row', 'even-row']

        });
        table.column(0).visible(false);





    };


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
var checkedRows = [];
var colleaction = [];
var orderID;
var formData = new FormData();
var pickOrderStatus = false;
var global_discount_amount;
var global_qty_amount;
var rep_id_for_cus_block = undefined;
var cus_id_for_cus_block = undefined;
$(document).ready(function () {

    $('#warningClose').on('click',function(){
        /* $('#warning_alert').css('display', 'none'); */
       
        $('#warning_alert').removeClass('show');
    });


    //displaying slaes order model    
    $('#exampleModal').on('show.bs.modal', function () {

       

        // $("#gettableItems th:nth-child(5)").hide();
        TableRefresh();

    })


    $('#exampleModal').on('hide.bs.modal', function () {

        var tableBody = $('#gettableItems tbody');
        tableBody.empty();

    })

    $('#bntLoadData').on('click', function () {
        selectedData(OrderID);
    });



    //table click
    //getting data id and offer type
    $('#gettable').on('click', 'tr', function (e) {

        if ($(e.target).closest('td').index() === 4 || $(e.target).closest('td').index() === 5 || $(e.target).closest('td').index() === 6) {
            return; // Do nothing if the click was on cell 5 or 6
        }
        $('#gettable tr').removeClass('selected');
        /*  $('#gettableItems tnody').empty(); */

        // Add the selected class to the clicked row
        $(this).addClass('selected');
        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children();
        var dateVal = $($(this).find('td:eq(5)')).text();

        var hidden_rep = $(this).find('td:eq(3)').children();
        rep_id_for_cus_block = hidden_rep.attr('data-id');

         cus_id_for_cus_block = $(this).find('td:eq(2)').children().attr('data-id');
       
        
        childElements.each(function () {
            OrderID = $(this).attr('data-id');
       
            getorderItems(cus_id_for_cus_block,OrderID,dateVal);
            /*   $("#gettableItems .hidden_col").hide(); */


        });
        //select all
        $('#gettableItems th input[type="checkbox"]').click(function () {
            // Get the checked state of the header checkbox
            var isChecked = $(this).prop('checked');
            $('#gettableItems td input[type="checkbox"]').prop('checked', isChecked);
        });



    });

});




//add selected data to array
var checkedRows = [];



//load order items to SI item table
function selectedData(orderID) {
   var block_status_ = checkBlockStatus(rep_id_for_cus_block,cus_id_for_cus_block);
   if(block_status_){

    $('#exampleModal').modal('hide');
    
        /* showWarningMessage('Customer blocked'); */
        $('#warning_alert').addClass('show');
        return;
   }
  
    var date = $('#invoice_date_time').val();
    var branchID_ = $('#cmbBranch').val();
    var _location_id = $('#cmbLocation').val();
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
        url: "/sd/getItemsForIncoiceTotable/" + branchID_ + "/" + orderID + "/" + date + "/" + _location_id,
        data: { 'Item_ids': JSON.stringify(selectedIds) },
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
    calculation();


}

//type function for qty and oc in item table for item set off
function itemsetOffontypeFunction(event) {

    $(event).focusout(function () {
        autoSetOff_wrong_qty(event);

    });

}

//set item to table

function setItems(collection) {


    var dataSource = [];
    var foc_qty = 0;
    for (var i = 0; i < collection.length; i++) {
         console.log('text item')
        var item_code = collection[i].Item_code;
        var item_id = collection[i].item_id;
        var item_name = collection[i].item_name;
        var quantity = parseInt(collection[i].quantity);
        // var free_quantity = collection[i].free_quantity;
        var unit_of_measure = collection[i].unit_of_measure;
        var pack_size = collection[i].package_unit;
        var package_size = collection[i].package_size;
        var price = parseFloat(collection[i].price);
        var discount_percentage = collection[i].discount_percentage;
        var discount_amount = collection[i].discount_amount;
        var values = parseFloat((quantity * price) - discount_amount);

        var balance = parseInt(collection[i].Balance);
        var cu_id = collection[i].customer_id;
        
        
        foc_qty = foc_calculation_threshold_pick_order(cu_id,item_id, parseFloat(quantity));



        if (balance <= 0 || (parseFloat(quantity) + parseFloat(foc_qty)) > parseFloat(balance)) {
            
            continue;
        } else {
            
            global_discount_amount = discount_amount
            global_qty_amount = quantity;

            dataSource.push([
                { "type": "text", "class": "transaction-inputs", "value": item_code, "data_id": item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "" },
                { "type": "text", "class": "transaction-inputs", "value": item_name, "style": "max-height:30px;margin-right:10px", "event": "", "style": "width:370px", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs math-round", "value": quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "checkavailableQty(this);itemsetOffontypeFunction(this);foc_calculation_threshold(this);calValueandCostPrice(this);" },
                { "type": "text", "class": "transaction-inputs math-abs math-round", "value": foc_qty, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "checkavailableQty(this);itemsetOffontypeFunction(this)", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": package_size, "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": pack_size, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": price, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*","disabled":"disabled" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "discountAmount(this)", "width": "*", },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": balance, "style": "max-height:30px;text-align:right;width:60px;margin-right:10px;", "event": "", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                { "type": "button", "class": "btn btn-primary", "value": "Batch&nbsp<span class='badge bg-yellow text-black translate-middle-middle  rounded-pill'style='padding:4px;'>"+whole_sale_count+"</span>", "style": "max-height:30px;margin:0px;", "event": "setOffbybuton(this)", "width": "*" },
                { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this);calculation()", "width": "*" }

            ]);



        }


    }
    tableData.setDataSource(dataSource);
    getHeaderDetails(OrderID);
    $('#exampleModal').modal('hide');
    pickOrderStatus = true;


    //setOffAutoMatically(collection);

}





function TableRefresh() {
    var table = $('#gettable').DataTable();
    table.columns.adjust().draw();
    $('.dataTables_scrollBody').css('height', '150px');
}


function clearTableData() {
    dataSource = [];
    tableData.setDataSource(dataSource);
}



/**Item History set off */
function getItemHistorySetoffBatch(branchID, ItemID,_location_id) {


    var table = $('#batchTableData');
    var tableBody = $('#batchTableDataBody');
    tableBody.empty();

    $.ajax({
        type: "GET",
        url: "/sd/getItemHistorySetoffBatch/" + branchID + "/" + ItemID + "/" +_location_id,
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
        row += '<td style="display:none">' + parseFloat(setoffObject.getCostPrice()).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
        row += '<td>' + parseFloat(setoffObject.getWholesalePrice()).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
        row += '<td>' + parseFloat(setoffObject.getRetailPrice()).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
        row += '<td>' + parseFloat(setoffObject.getAvailableQuantity()).toFixed(0) + '</td>';
        row += '<td><input type="number" style="text-align:right;" class="transaction-inputs" value="' + setoffObject.getSetoffQuantity() + '" oninput="resetoff(this,' + str_item_id + ',' + str_history_primary_id + ')" onkeyup="allowOnlyNumbers(this)" disabled></td>';
        row += '</tr>'
        $('#batchTableDataBody').append(row);
    }


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






function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}