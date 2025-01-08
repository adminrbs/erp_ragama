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
        var table = $('.datatable-fixed-both-PO_TableData').DataTable({
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
                { "data": "orderDate" },
                { "data": "Order_No" },
                { "data": "supplier_name" },
                { "data": "amount" },
                { "data": "order_by" },
                { "data": "expected_date" },
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

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});
var OrderID;
var pickOrderStatus = false;
$(document).ready(function () {

    //loading data on model show
    $('#GRNPickOrderModal').on('show.bs.modal', function () {

        getPendingPurchaseOrder();

        

    })

    $('#GRNPickOrderModal').on('hide.bs.modal', function (e) {
       
        var tableBody = $('#gettableItems tbody');
        tableBody.empty();
      });

    //load items on row click
    $('#PO_TableData').on('click', 'tr', function (e) {
        // Check if the click occurred on index 5 or 6 (6th and 7th cell)
        if ($(e.target).closest('td').index() === 5 || $(e.target).closest('td').index() === 6) {
            return; // Do nothing if the click was on cell 5 or 6
        }
    
        $('#PO_TableData tr').removeClass('selected');
    
        // Add the selected class to the clicked row
        $(this).addClass('selected');
        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children();
    
        childElements.each(function () {
            OrderID = $(this).attr('data-id');
    
            getorderItems(OrderID);
        });
    
        
    });
    
    //select all
    $('#gettableItems th input[type="checkbox"]').click(function () {
        // Get the checked state of the header checkbox
        var isChecked = $(this).prop('checked');
        $('#gettableItems td input[type="checkbox"]').prop('checked', isChecked);
    });

    //inserting selected data to frn item table(calling function)
    $('#btnGetData').on('click',function(){
        selectedData(OrderID);
        
    })

});

//load pending purchase orders to the model
function getPendingPurchaseOrder() {
    var branch_id = $('#cmbBranch').val();
    $.ajax({
        url: '/prc/getPendingPurchaseOrder/'+branch_id,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
            
                data.push({
                    "orderDate": "<div data-id='" + dt[i].purchase_order_Id + "'>" + dt[i].purchase_order_date_time + "</div>",
                    "Order_No": shortenString(dt[i].external_number,15),
                    "supplier_name": shortenString(dt[i].supplier_name,20),
                    "amount": dt[i].total_amount,
                    "order_by": dt[i].prepaired_by,
                    "expected_date": dt[i].deliver_date_time,
                    "action": '<div class="dropdown position-static">' +
                        '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i>' +
                        '</a>' +
                        '<div class="dropdown-menu dropdown-menu-end">' +
                        '<a class="dropdown-item"  onclick="selectedData(' + dt[i].purchase_order_Id + ')">Add to GRN</a>' +
                        '<a class="dropdown-item" onclick="completeOrder('+dt[i].purchase_order_Id+')" >Complete</a>' +
                        '</div>'
                });

            }

            var table = $('#PO_TableData').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

//load items to model table according to clicked purchased order
function getorderItems(id){

        var table = $('#gettableItems');
        var tableBody = $('#gettableItems tbody');
        tableBody.empty();
        $.ajax({
            type: "GET",
            url: "/prc/getorderItems/" + id,
            cache: false,
            timeout: 800000,
            beforeSend: function () { },
            success: function (data) {
                var dt = data.data
                console.log(dt);
                $.each(dt, function (index, item) {
                  /*   var qty = item.quantity;
                    var price = item.price;
                    var discAmount = item.discount_amount
                    var value = parseFloat((qty * price) - (qty * discAmount)); */
                    var row = $('<tr>');
                    row.append($('<td>').append($('<label>').attr('data-id', item.item_id).text(item.Item_code)));
                    row.append($('<td>').text(item.item_name));
                    row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(item.PO_quantity)));
                    row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(item.quantity)));
                    row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(item.PO_Foc)));
                    row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(item.free_quantity)));
                    row.append($('<td>').text(item.additional_bonus));
                    row.append($('<td>').text(item.package_unit));
                    /* row.append($('<td>').text(item.package_unit));
                    row.append($('<td>').text(item.package_size)); */
                    row.append($('<td>').text(item.cost_price));
                    row.append($('<td>').text(item.discount_percentage));
                    /* row.append($('<td>').text(item.discount_amount)); */
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
function selectedData(orderID) {

    var branch_id = $('#cmbBranch').val();
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
        url: "/prc/getSelectedItems/" + branch_id + "/" + orderID,
        data: { 'Item_ids': JSON.stringify(selectedIds) },
        async: false,
        beforeSend: function () { },
        success: function (response) {
            //  console.log(response);
            var dt = response.data;
            
            console.log(dt);

            setItems(dt);
            got_from_pickOrder = true;
           


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }
    });
    calculation();
    $('#GRNPickOrderModal').modal('hide');
   

}






//set item to table

function setItems(collection) {


    var dataSource = [];
    for (var i = 0; i < collection.length; i++) {
        // console.log('text item')
        var item_code = collection[i].Item_code;
        var item_id = collection[i].item_id;
        var item_name = collection[i].item_name;
        var quantity = parseFloat(collection[i].quantity.toString().replace(/,/g, ''));
        var free_quantity = parseFloat(collection[i].free_quantity.toString().replace(/,/g, ''));
        var unit_of_measure = collection[i].unit_of_measure;
        var pack_size = collection[i].package_unit;
        var package_size = collection[i].package_size;
        var price = parseFloat(collection[i].price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        var price_ = parseFloat(collection[i].price);
        var cst = parseFloat(collection[i].cost_price);
        var discount_percentage = collection[i].discount_percentage;
        var discount_amount = collection[i].discount_amount;
        var values = parseFloat((quantity * cst) - discount_amount).toFixed(2);
        var whole_sale_price = collection[i].whole_sale_price;
        var retial_price = collection[i].retial_price;
        var purchase_order_item_id = collection[i].purchase_order_item_id;
        var manageBatch = collection[i].manage_batch;
        var is_new_price = collection[i].is_new_price;
        var addBonus = collection[i].additional_bonus;
     //   var batch = "";
        var manageExpireDate = collection[i].manage_expire_date;
        if(isNaN(free_quantity)){
            free_quantity = 0
        }
        if(isNaN(quantity)){
            quantity = 0
        }
        var total_qty_foc = parseFloat(quantity) + parseFloat(free_quantity);
       // var costPrice = (values / total_qty_foc).toFixed(2);
       var costPrice = collection[i].cost_price;
        var disabled_batch = "disabled";
        var disabled_expire_date = "disabled";
        var  style = "max-height:30px;text-align:right;width:80px;margin-right:10px;";
        if(manageBatch == 1){
            disabled_batch = "";
          //  batch = collection[i].Item_code;
        }
        if(manageExpireDate == 1){
            disabled_expire_date = "";
        }
        
        if(is_new_price == 1){
            style = "max-height:30px;text-align:right;width:80px;margin-right:10px;color:red;";
        }
        dataSource.push([
            { "type": "text", "class": "transaction-inputs", "value": item_code, "data_id": item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "","valuefrom": "datachooser","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": item_name, "data_id": purchase_order_item_id, "style": "max-height:30px;margin-left:10px", "event": "", "style": "width:370px", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": parseInt(quantity),"style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);calculation();checkPOqtyandFoc(this)" ,"compulsory":true},
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": parseInt(free_quantity), "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);checkPOqtyandFoc(this)" },
            { "type": "text", "class": "transaction-inputs", "value": addBonus, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "", "width": "*",  },
            { "type": "text", "class": "transaction-inputs", "value": package_size, "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": pack_size, "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": price_,"data_id": is_new_price, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*","thousand_seperator":true, },
            { "type": "text", "class": "transaction-inputs math-abs", "value": discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*","disabled":"disabled" },
            { "type": "text", "class": "transaction-inputs", "value": parseFloat(values).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value":price, "style": style, "event": "calValueandCostPrice(this);", "width": "*","thousand_seperator":true  },
            { "type": "text", "class": "transaction-inputs math-abs", "value":parseFloat(retial_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),"style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*","thousand_seperator":true  },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:150px;margin-right:10px;", "event":"", "width": "*","disabled":disabled_batch  },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "enableDate(this)", "width": "*","disabled":disabled_expire_date  },
          
            { "type": "text", "class": "transaction-inputs", "value": parseFloat(costPrice).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice()", "width": "*","disabled": "disabled" }, //allow edit cost price as instructed by sachin on 04/06 // do not allow to edit cost price as instructed by mr.janaka on 04/06
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-right:20px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs", "value": whole_sale_price, "style": "width:80px;", "event": "enableDate(this)", "width": "*", "disabled": "disabled" }, //date 14
            { "type": "text", "class": "transaction-inputs", "value": retial_price, "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": parseInt(quantity), "style": "width:80px;", "event": "enableDate(this)", "width": "*", "disabled": "disabled" }, 
            { "type": "text", "class": "transaction-inputs", "value": parseInt(free_quantity), "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },

        ]);
    }
    tableData.setDataSource(dataSource);
    getHeaderDetails(OrderID);
    calculation();
   /*  disablePurchasePriceandWholeSalePrice(tableData,value_for_radio_button); */

    
    //setOffAutoMatically(collection);
    pickOrderStatus = true;
    $('#txtSupplier').prop('disabled',true);
    $('#txtPurchaseORder').prop('disabled',true);

}



function getHeaderDetails(id) {
    $.ajax({
        type: "GET",
        url: "/prc/getheaderDetails/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (data) {
            var res = data.data
            

            $('#cmbLocation').val(res[0].location_id);
            $('#txtSupplier').val(res[0].supplier_code);
            $('#txtSupplier').attr('data-id',res[0].supplier_id);
            $('#lblSupplierName').text(res[0].supplier_name);
            $('#lblSupplierAddress').val(res[0].primary_address);
            $('#txtPurchaseORder').val(res[0].external_number);
            $('#txtPurchaseORder').attr('data-id',res[0].purchase_order_Id);
            $('#lblSupplierAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbPaymentType').val(res[0].payment_mode_id);
            $('#lblSupplierName').attr('data-id', res[0].supplier_id);
            $('#LblexternalNumber').attr('data-id', id);
            


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });

}


//complete function confimation
function completeOrder(id){
    bootbox.confirm({
        title: 'Confirmation',
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
                completeOrder_status(id)
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

}


function completeOrder_status(id){
    $.ajax({
        url: '/prc/completeOrderstatus/' + id,
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
           
        }, success: function (response) {
           console.log(response);
            var msg = response.message;
            var status = response.status;
            if(status){
               /*  showSuccessMessage("Order Completed"); */
              
                getPendingPurchaseOrder();
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })

}

function completeOrder_status_auto(id){
    console.log('in');
    $.ajax({
        url: '/prc/completeOrderstatus_auto/' + id,
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
           
        }, success: function (response) {
           console.log(response);
            var msg = response.message;
            var status = response.status;
            if(status){
               /*  showSuccessMessage("Order Completed"); */
              
               // getPendingPurchaseOrder();
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })

}


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}

function calPrices(event){
    if(value_for_radio_button == 'whole_sale'){
        calculatePurchasePrice(event)
    }else if(value_for_radio_button == 'cost_price'){
        calculateWholePrice(event)
    }
    calValueandCostPrice(event)
}
//calculate purchase price using wh price
function calculatePurchasePrice(event){
  
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var wh_price = parseFloat($($(cell[11]).children()[0]).val().replace(/,/g, ""));
    var p_price = parseFloat($($(cell[7]).children()[0]).val().replace(/,/g, ""));
    var discount = parseFloat($($(cell[8]).children()[0]).val().replace(/,/g, ""));
   
        // Check if the field values are not NaN or empty
        if (isNaN(wh_price)) {
            wh_price = 0;
        }
        if (isNaN(p_price)) {
            p_price = 0;
        }
        if (isNaN(discount)) {
            discount = 0;
        }
      /* console.log(wh_price);
      console.log(p_price); */

        
      var cost_price =  (parseFloat(wh_price) / (100 + parseFloat(discount))) * 100;
      
     // var whol_price = parseFloat(p_price) + (((parseFloat(p_price)) / 100) * parseFloat(discount));
        
     // $($(cell[11]).children()[0]).val(whol_price);
      $($(cell[7]).children()[0]).val(cost_price.toFixed(2));
    }


    //calculate whole sale price using p price
    function calculateWholePrice(event){
        var row = $($(event).parent()).parent();
        var cell = row.find('td');
        var wh_price = parseFloat($($(cell[11]).children()[0]).val().replace(/,/g, ""));
        var p_price = parseFloat($($(cell[7]).children()[0]).val().replace(/,/g, ""));
        var discount = parseFloat($($(cell[8]).children()[0]).val().replace(/,/g, ""));
   
        // Check if the field values are not NaN or empty
        if (isNaN(wh_price)) {
            wh_price = 0;
        }
        if (isNaN(p_price)) {
            p_price = 0;
        }
        if (isNaN(discount)) {
            discount = 0;
        }

         var whol_price = parseFloat(p_price) + (((parseFloat(p_price)) / 100) * parseFloat(discount));
        
         $($(cell[11]).children()[0]).val(whol_price.toFixed(2));
    }


       //calculate whole sale price using p price
       function cal_purchase_price(event){
        var row = $($(event).parent()).parent();
        var cell = row.find('td');
        var wh_price = parseFloat($($(cell[15]).children()[0]).val().replace(/,/g, "")); //cost
        var discount = parseFloat($($(cell[8]).children()[0]).val().replace(/,/g, ""));
   
        // Check if the field values are not NaN or empty
        if (isNaN(wh_price)) {
            wh_price = 0;
        }
       
        if (isNaN(discount)) {
            discount = 0;
        }

         var p_price = wh_price - (wh_price * (discount / 100));
        
         $($(cell[7]).children()[0]).val(p_price.toFixed(2));
    }
   
   

    
   
    
