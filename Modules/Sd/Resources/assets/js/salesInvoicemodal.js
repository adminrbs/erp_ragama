/* 
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

        // Initialize DataTable
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
                }
            ],
            autoWidth: false,
            scrollX: true,
            scrollY: '600', 
            scrollCollapse: true, 
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "info": true,
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
        table.column(7).visible(false);

        // Adjust DataTable dimensions when modal is shown or resized
        $('#exampleModal').on('shown.bs.modal', function () {
            table.columns.adjust().draw();
        });

        $(window).on('resize', function() {
            table.columns.adjust().draw();
        });
    };

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    };
}();



document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
}); */
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

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    //displaying slaes order model    
    $('#exampleModal').on('shown.bs.modal', function () {
        //$('.datatable-fixed-both-getdata .dataTables_scrollBody').css('max-height', '600px');
        getSalesOrderDetailsForInvice();

        // $("#gettableItems th:nth-child(5)").hide();
        $('#branch_name').text('Branch :'+' '+$('#cmbBranch option:selected').text())
       // TableRefresh();

    })


    $('#exampleModal').on('hide.bs.modal', function () {

        var tableBody = $('#gettableItems tbody');
        tableBody.empty();

    })

    $('#bntLoadData').on('click', function () {
        selectedData(OrderID);
    });

    $('#btnReject_order').on('click',function(){
        confirm_reject(OrderID)
    });
    

    //table click
    //getting data id and offer type
    $('#gettable').on('click', 'tr', function (e) {

        var tableBody = $('#gettableItems tbody');
        tableBody.empty();

        /* if ($(e.target).closest('td').index() === 4 || $(e.target).closest('td').index() === 5 || $(e.target).closest('td').index() === 6) {
            return; 
        } */
        $('#gettable tr').removeClass('selected');
        /*  $('#gettableItems tnody').empty(); */

        // Add the selected class to the clicked row
        $(this).addClass('selected');
        var hiddenValue = $(this).find('td:eq(1)');
        var childElements = hiddenValue;
        var dateVal = $($(this).find('td:eq(6)')).text();

        var hidden_rep = $(this).find('td:eq(4)');
        rep_id_for_cus_block = hidden_rep.attr('data-id');

         cus_id_for_cus_block = $(this).find('td:eq(3)').attr('data-id');
       
        
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

        $('a[href="#items"]').click();

    });

    $('a[href="#items"]').on('click',function(){
        $('#table_search').hide();
    });

    $('a[href="#orders"]').on('click',function(){
        $('#table_search').show();
    });

    $('#table_search').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        $('#gettable tbody tr').each(function() {
            var rowData = $(this).text().toLowerCase();
            if (rowData.indexOf(searchText) === -1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

});


function getSalesOrderDetailsForInvice() {
    $('body').css('cursor', 'wait');
    var branchID = $('#cmbBranch').val();
    $.ajax({
        type: "GET",
        url: "/sd/getSalesOrderDetailsForInvice/" + branchID,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            
            var dt = response.data;
            var block_data = response.block_data;
            console.log(block_data);
            var data = [];

            /* for (var i = 0; i < dt.length; i++) {
                data.push({
                    "id": dt[i].sales_order_Id,
                    "date": '<div data-id = "' + dt[i].sales_order_Id + '">' + dt[i].order_date_time + '</div>',
                    "Order_no": shortenString(dt[i].external_number,15),
                    "customer_name": '<div data-id = "' + dt[i].customer_id + '">' +shortenString(dt[i].customer_name,20).trim()+ '</div>',
                    "rep_name": '<div data-id = "' + dt[i].employee_id + '">' +dt[i].employee_name + '</div>',
                    "amount": parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "deliver_date": dt[i].expected_date_time,
                    "action": '<div class="dropdown position-static">' +
                        '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i>' +
                        '</a>' +
                        '<div class="dropdown-menu dropdown-menu-end">' +
                        '<a class="dropdown-item"  onclick="selectedData(' + dt[i].sales_order_Id + ')">Create Invoice</a>' +
                        '<a class="dropdown-item"  onclick="confirm_reject(' + dt[i].sales_order_Id + ')">Reject</a>' +
                        '</div>'
                });
            } */

            // Assuming dt is your array of data and row is the jQuery object representing the row in your table
            for (var i = 0; i < dt.length; i++) {
                var item = dt[i];
                var newRow = $('<tr>').css('height', '20px'); // Set row height to 20px
            
                // Append each cell with respective data
                newRow.append($('<td>').css('display', 'none').append('<div>' + item.sales_order_Id + '</div>'));
                newRow.append($('<td>').attr('data-id', item.sales_order_Id).css({'height': '20px', 'padding': '2px'}).append('<div style="height: 20px; overflow: hidden; padding: 2px;">' + item.order_date_time + '</div>'));
                newRow.append($('<td>').css({'height': '20px', 'padding': '2px'}).append('<div style="height: 20px; overflow: hidden; padding: 2px;">' + shortenString(item.external_number, 15) + '</div>'));
                newRow.append($('<td>').attr('data-id', item.customer_id).css({'height': '20px', 'padding': '2px'}).append('<div style="height: 20px; overflow: hidden; padding: 2px;">' + shortenString(item.customer_name, 20).trim() + '</div>'));
                newRow.append($('<td>').attr('data-id', item.employee_id).css({'height': '20px', 'padding': '2px'}).append('<div style="height: 20px; overflow: hidden; padding: 2px;">' + item.employee_name + '</div>'));
                newRow.append($('<td>').css({'height': '20px', 'padding': '2px'}).append('<div style="height: 20px; overflow: hidden; padding: 2px;text-align:right">' + parseFloat(item.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</div>'));
                newRow.append($('<td>').css({'height': '20px', 'padding': '2px'}).append('<div style="height: 20px; overflow: hidden; padding: 2px;text-align:center">' + item.expected_date_time + '</div>'));
            
                // Create action cell
                var actionCell = $('<td>').css('display', 'none').css({'height': '20px', 'padding': '2px'}).append('<div class="dropdown position-static">' +
                    '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i>' +
                    '</a>' +
                    '<div class="dropdown-menu dropdown-menu-end">' +
                    '<a class="dropdown-item" onclick="selectedData(' + item.sales_order_Id + ')">Create Invoice</a>' +
                    '<a class="dropdown-item" onclick="confirm_reject(' + item.sales_order_Id + ')">Reject</a>' +
                    '</div></div>');
                newRow.append(actionCell);
            
                // Append the new row to the table
                $('#gettable').append(newRow);
            }
            
            
            
            

            
            

          /*   var table = $('#gettable').DataTable();
            table.clear();
            table.rows.add(data).draw(); */
            var table = $('#gettable');
$('body').css('cursor', 'default');

// Assuming block_data is an object containing keys and values
for (var key in block_data) {
    if (block_data.hasOwnProperty(key)) {
        var value = block_data[key];
        if (value == 1) {
            // Iterate through each row of the table
            table.find('tbody tr').each(function() {
                // Find the cell in the third column
                var cellHtml = $(this).find('td:eq(3)').attr('data-id');
                console.log('Cell HTML:', cellHtml);
                console.log('Key:', key);
                if (cellHtml == key) {
                    console.log('Match found for key: ' + key);
                    $(this).removeClass().addClass('highlight-row');
                    // If you want to preserve other classes, use $(this).addClass('highlight-row');
                }
            });
        }
    }
}



          //  TableRefresh();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function getHeaderDetails(id) {
    /* var table = $('#gettableItems');
    var tableBody = $('#gettableItems tbody');
    tableBody.empty(); */
    $.ajax({
        type: "GET",
        url: "/sd/getheaderDetails/" + id,
        cache: false,
        timeout: 800000,
        async: false,
        beforeSend: function () { },
        success: function (data) {
            var res = data.data
            // alert(res[0].employee_id);
            //  $('#cmbBranch').val(res[0].branch_id);
            //   $('#cmbBranch').change();
            //    $('#cmbLocation').val(res[0].location_id);
            if (res[0].employee_id == undefined) {
                $('#cmbEmp').val(1);
            } else {
                $('#cmbEmp').val(res[0].employee_id);
            }
            $('#cmbEmp').change();
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerName').val(res[0].customer_name);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbPaymentTerm').val(res[0].payment_term_id);
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            //   $('#cmbPaymentMethod').val(res[0].payment_term_id);

            $('#LblexternalNumber').attr('data-id', id);

            loadReturnRequest(res[0].customer_id);
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });

}

//load items to html table
function getorderItems(cus_,id, date) {

    var table = $('#gettableItems');
    var tableBody = $('#gettableItems tbody');
    var branch_id = $('#cmbBranch').val();
    var location_id = $('#cmbLocation').val();
    tableBody.empty();
    $.ajax({
        type: "GET",
        url: "/sd/getorderItems/" + id + "/" + date + "/" + cus_ + "/" + branch_id +'/' + location_id,
        cache: false,
        timeout: 800000,
        async:false,
        beforeSend: function () { },
        success: function (data) {
            var dt = data.data;
            var count = data.count;
            if(count == undefined){
                count = 0;
            }
            $('#lblCount').text("Multiple orders count:"+count);
            $.each(dt, function (index, item) {
                var totalQty = 0;
                var qty = item.quantity;
                var price = item.price;
                var discAmount = item.discount_amount
                var value = parseFloat((qty * price) - discAmount);
                totalQty = parseFloat(item.quantity) + parseFloat(item.free_quantity);
                var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.item_id).text(item.Item_code)));
                row.append($('<td>').css('width', '250px').append('<div>' + item.item_name + '</div>'));
                row.append($('<td>').text(item.Balance));
                row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;" onchange="cal_model_foc('+cus_+','+item.item_id+',this)">').attr('type', 'text').val(item.quantity)));
                row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;" disabled>').attr('type', 'text').val(item.free_quantity)));
                row.append($('<td>').text(item.unit_of_measure));
                
                row.append($('<td style="text-align:right;">').text(item.price));
               
                row.append($('<td style="text-align:right;">').text(parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').append($('<input>').attr('type', 'checkbox').val(item.item_id).prop('checked', true)));
                row.append($('<td>').text(item.all_Balance));
                row.append($('<td>').text(item.supply_group_id));
                if (parseFloat(totalQty) > parseFloat(item.Balance) &&  parseFloat(totalQty) <= parseFloat(item.all_Balance)) {
                    row.css('color', 'rgb(255, 0, 0)');
                    row.find('.transaction-inputs').css('color', 'rgb(255, 0, 0)');
                }
                table.append(row);
            });

            table.find('tr td:last-child').hide();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}

//add selected data to array
var checkedRows = [];


/* //using check box
function selectedData() {

    var table = document.getElementById('gettableItemsbody'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, id;


    for (i = 0, j = rows.length; i < j; ++i) {
        cells = rows[i].getElementsByTagName('td');
        if (!cells.length) {
            continue;
        }

        var data = [];
        for (i2 = 0; i2 < cells.length; i2++) {

            var object = $(cells[i2].childNodes[0]);
            data.push(object);
            if (object.attr('type') == "checkbox" && object.is(':checked')) {
                colleaction.push(data);
                data = [];
            }

        }

    }
    getHeaderDetails(OrderID);

    setItems(colleaction);
    $('#exampleModal').modal('hide');
    calculation();
}
 */
//load order items to SI item table
function selectedData(orderID) {
    var is_insufficeint = 0;
    var is_supply_group_mismatched = 0;
   var block_status_ = checkBlockStatus(rep_id_for_cus_block,cus_id_for_cus_block,orderID);
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
            var avlQ = $(this).find('td:eq(2)').text();
            var _qty = $(this).find('td:eq(3) input[type="text"]').val();
            var _foc = $(this).find('td:eq(4) input[type="text"]').val();
            var row_sup_group_id = $(this).find('td:eq(10)').text();
           
            if(parseFloat(avlQ) < parseFloat(_qty)){
                showWarningMessage("Insufficient Balance");
                $(this).removeAttr('class').addClass('highlight-row');
                is_insufficeint = 1
               
            }

            if(parseInt(row_sup_group_id) != $('#cmbSalesAnalysist').val()){
                is_supply_group_mismatched = 1;
                showWarningMessage("Supply Group Mismatched");
                $(this).removeAttr('class').addClass('highlight-row');
                return;
            }
            var dataId = $(this).find('label').data('id');
           // selectedIds.push(dataId);
           selectedIds.push(JSON.stringify({'id':dataId,'qty':_qty, 'foc':_foc}));
        }
        
    });

console.log(selectedIds);

    if(is_insufficeint != 1 && is_supply_group_mismatched != 1){
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
    }else{
        selectedIds = [];
    }
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
         
        var item_code = collection[i].Item_code;
        var item_id = collection[i].item_id;
        var item_name = collection[i].item_name;
        var quantity = parseInt(collection[i].quantity);
         var free_quantity = collection[i].free_quantity;
        var unit_of_measure = collection[i].unit_of_measure;
        var pack_size = collection[i].package_unit;
        var package_size = collection[i].package_size;
        var price = parseFloat(collection[i].price);
        var discount_percentage = collection[i].discount_percentage;
        var discount_amount = collection[i].discount_amount;
        var values = parseFloat((quantity * price) - discount_amount);

        var balance = parseInt(collection[i].Balance);
        var cu_id = collection[i].customer_id;
        
        
      //  foc_qty = foc_calculation_threshold_pick_order(cu_id,item_id, parseFloat(quantity));



        if (balance <= 0 || (parseFloat(quantity) + parseFloat(foc_qty)) > parseFloat(balance)) {
            
            continue;
        } else {
            
            global_discount_amount = discount_amount
            global_qty_amount = quantity;

            dataSource.push([
                { "type": "text", "class": "transaction-inputs", "value": item_code, "data_id": item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "" },
                { "type": "text", "class": "transaction-inputs", "value": item_name, "style": "max-height:30px;margin-right:10px", "event": "", "style": "width:370px", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs math-round", "value": quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "checkavailableQty(this);itemsetOffontypeFunction(this);foc_calculation_threshold(this);calValueandCostPrice(this);" },
                { "type": "text", "class": "transaction-inputs math-abs math-round", "value": free_quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "checkavailableQty(this);itemsetOffontypeFunction(this);check_foc_qty(this)" },
                { "type": "text", "class": "transaction-inputs", "value": unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": package_size, "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": pack_size, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": price, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*","disabled":"disabled" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "discountAmount(this)", "width": "*", },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": balance, "style": "max-height:30px;text-align:right;width:60px;margin-right:10px;", "event": "", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                { "type": "button", "class": "btn btn-primary", "value": "Batch&nbsp<span class='badge bg-yellow text-black translate-middle-middle  rounded-pill'style='padding:4px;'>"+whole_sale_count+"</span>", "style": "max-height:30px;margin:0px;", "event": "setOffbybuton(this)", "width": "*" },
                { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this);calculation()", "width": "*" },
                { "type": "text", "class": "transaction-inputs", "value": foc_qty, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },

            ]);



        }


    }
    tableData.setDataSource(dataSource);
    getHeaderDetails(OrderID);
    $('#exampleModal').modal('hide');
    pickOrderStatus = true;


    //setOffAutoMatically(collection);

}

function confirm_reject(order){
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
                rejectSaleOrderForInvoice(order)
              
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


//reject
function rejectSaleOrderForInvoice(id) {
    $.ajax({
        url: '/sd/rejectSalesOrderForInvocie/' + id,
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
            var msg = response.message;
            var status = response.status;
            if(msg == 'no'){
                showWarningMessage('Unable to reject');
                return;
            }

            if (status) {
                showSuccessMessage("Order rejected");

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })

    getSalesOrderDetailsForInvice();
}



function TableRefresh() {
    var table = $('#gettable').DataTable({
        scrollX: true,
        scrollY: 'auto',
        scrollCollapse: true
    });
    table.columns.adjust().draw();
    $('.dataTables_scrollBody').css('height', '1000px');
}


/* function TableRefreshBatchTable() {
   // alert('vall');
    var table = $('#batchTable').DataTable();
    table.columns.adjust().draw();
   
} */
function clearTableData() {
    dataSource = [];
    tableData.setDataSource(dataSource);
}


//update status of order when invoice is created 
function updateStatusOfOrder(id) {
    $.ajax({
        url: '/sd/updateStatusOfOrder/' + id,
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

            var status = response.status
            console.log(status);

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })
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
        row += '<td><input type="number" style="text-align:right;" class="transaction-inputs" value="' + setoffObject.getSetoffQuantity() + '" oninput="resetoff(this,' + str_item_id + ',' + str_history_primary_id + ')" onkeyup="allowOnlyNumbers(this)"></td>';
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
    // $('#lblBalance').text(setOff_blnc);
    //  totalQty = 0;
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



//auto set off
/*  function autoSetOff(totalQty){

    var qtyBalance;
    var table = $('#batchTable').DataTable();
    table.rows().every(function(rowIndex,tableLoop,rowLoop){
        var rowData = this.cell(rowIndex,5).data();
        var ablQty = parseFloat(rowData);
        console.log(ablQty);
        if(totalQty < ablQty){
            var cell = table.cell(rowIndex, 6).node();
            var input = $(cell).find('input');
            input.val(totalQty);
           
        }
        
    })
} 
 */

/*  function autoSetOff(totalQty) {
  var tableRows = document.querySelectorAll("#batchTableData tbody tr");

  tableRows.forEach(function (row) {
    var cellData = row.cells[5].textContent.trim();
    var ablQty = parseFloat(cellData);

    if (!isNaN(ablQty)) {
      console.log(ablQty);

      if (totalQty < ablQty) {
        var cell = row.cells[6];
        var input = cell.querySelector("input");
        if (input) {
          input.value = totalQty;
        }
      }
    }
  });
}
 */


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}

function cal_model_foc(cu_id,item_id,event){
    var quantity = $(event).val();
    var foc_cell = $(event).closest('tr').find('td').eq(4);
    var avl_cell_val = $(event).closest('tr').find('td').eq(2).text();
    var qty_foc = foc_calculation_threshold_pick_order(cu_id,item_id, parseFloat(quantity));
    if(parseInt(avl_cell_val) < (parseInt(qty_foc) + parseInt(quantity))){
        showWarningMessage('Insufficient avaible quantity');
        $(event).closest('tr').css({
            'color': 'red', 
              
        });
    }else{
        $(event).closest('tr').css({
            'color': 'black', 
              
        });
    }
    $(foc_cell).val(qty_foc);
}
