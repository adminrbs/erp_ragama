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
        var table = $('#gettable').DataTable({
           
            columnDefs: [
                {
                    width: 10,
                    targets: 0
                },
                {
                    width: 200,
                    targets: 1
                },
                {
                    width: 200,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 3
                },
                {
                    width: 200,
                    targets: 4
                },
                {
                    width: 200,
                    targets: 5
                },
                {
                    width: 150,
                    targets: 6
                },{
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '10px');
                    }
                }
               
             

            ],
            autoWidth: false,
            scrollX: true,
            scrollY: true,
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
                { "data": "reference" },
                { "data": "from_br" },
                { "data": "from_lo" },
                { "data": "amount" },
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


var dispatchId = undefined;
$(document).ready(function () {
    
    //show pick model and load data
    $('#si_model_btn').on('click',function(){
       
        get_dispatches($('#cmb_to_Branch').val(),$('#cmb_to_Location').val());
    });


    $('#gettable tbody').on('click', 'tr', function() {
        $('#gettable tr').removeClass('selected');
        $(this).addClass('selected');
         dispatchId =  $(this).find('td:first div').data('id');
        get_dispatch_items(dispatchId);
      });

      $('#bntLoadData').on('click',function(){
        load_dispatch_items(dispatchId);
      });
      
});

//load dispatches to model table
function get_dispatches(branch_id,location_id){
   // alert();
    $.ajax({
        url: '/sc/get_dispatches/'+branch_id+'/'+location_id,
        type: 'get',
        async: false,
        success: function (data) {
            var dt = data.data;
            var info = [];
            console.log(dt);
            $.each(dt, function (index, value) {
                info.push({
                    "id": value.dispatch_to_branch_id,
                    "date":'<div data-id="'+value.dispatch_to_branch_id+'">'+value.trans_date+'</div>',
                    "reference": value.external_number,
                    "from_br": value.branch_name,
                    "from_lo": value.location_name,
                    "amount": value.total_amount,
                    /* "approvalStatus": label_approval, */
                    "action": '<div class="dropdown position-static">' +
                    '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i>' +
                    '</a>' +
                    '<div class="dropdown-menu dropdown-menu-end">' +
                    '<a class="dropdown-item"  onclick="load_dispatch_items(' + value.dispatch_to_branch_id + ')">Load Items</a>' +
                    '</div>'
                });

                

            });
            var table = $('#gettable').DataTable();
            table.clear();
            table.rows.add(info).draw();

        },
    })

}

//load dispatch to html table - i model
function get_dispatch_items(id) {

    var table = $('#gettableItems');
    var tableBody = $('#gettableItems tbody');
    tableBody.empty();
    $.ajax({
        type: "GET",
        url: "/sc/get_dispatch_items/" + id,
        cache: false,
        timeout: 800000,
        async:false,
        beforeSend: function () { },
        success: function (data) {
            var dt = data.data
            console.log(dt);
            $.each(dt, function (index, item) {
                var totalQty = 0;
                var qty = item.quantity;
                var price = item.price;
                //var discAmount = item.discount_amount
                var value = parseFloat(qty * price);
                totalQty = parseFloat(item.quantity) + parseFloat(item.free_quantity);
                var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.dispatch_to_branch_item_id).text(item.Item_code)));
                row.append($('<td>').css('width', '150px').append('<div>' + item.item_Name + '</div>'));
                row.append($('<td>').text(item.remain_qty));
                row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(item.quantity)));
                
                row.append($('<td style="display:none;">').text(item.package_size));
               
                row.append($('<td>').text(item.price));
                row.append($('<td>').text(parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').append($('<input>').attr('type', 'checkbox').val(item.item_id).prop('checked', true)));
                table.append(row);
            });

          //  table.find('tr td:last-child').hide();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });



    
}

//load selected items on model to dispatch receive table
function load_dispatch_items(id){


    var selectedIds = [];

    $('#gettableItems tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.prop('checked')) {
            var dataId = $(this).find('label').data('id');
            if(dataId != undefined){
                selectedIds.push(dataId);
            }
            
        }
    });
    var collection;
    $.ajax({
        type: "get",
        url: "/sc/load_dispatch_items/"+ id,
        data: { 'Item_ids': JSON.stringify(selectedIds) },
        async: false,
        beforeSend: function () { },
        success: function (response) {
            //  console.log(response);
         collection = response.data;
         header = response.dispatch;
    console.log(header);
         $('#LblexternalNumber').val(header.external_number);
         $('#LblexternalNumber').attr('data-id',id);
         $('#dispatch_Date_time').val(header.trans_date);
         $('#txtYourReference').val(header.your_reference_number);
         $('#cmbBranch').val(header.from_branch_id);
         $('#cmbLocation').val(header.from_location_id);
         $('#cmb_to_Branch').val(header.to_branch_id);
         $('#cmb_to_Location').val(header.to_location_id);
            
           

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }
    });
    calculation();
    $('#exampleModal').modal('hide');
    

    var dataSource = [];
    
    for (var i = 0; i < collection.length; i++) {
         console.log('text item')
        var item_code = collection[i].Item_code;
        var dispach_item_id = collection[i].dispatch_to_branch_item_id;
        var item_i = collection[i].item_id;
        var item_name = collection[i].item_Name;
        var quantity = parseInt(collection[i].remain_qty);
        // var free_quantity = collection[i].free_quantity;
       // var unit_of_measure = collection[i].unit_of_measure;
        var pack_size = collection[i].package_unit;
      //  var package_size = collection[i].package_size;
        var price = parseFloat(collection[i].price);
       /*  var discount_percentage = collection[i].discount_percentage;
        var discount_amount = collection[i].discount_amount; */
        var values = parseFloat(quantity * price);
        var wh_price_ = collection[i].whole_sale_price;
        var rt_price_ = collection[i].retial_price;
        var cost_price_ = collection[i].cost_price;
       // var balance = parseInt(collection[i].Balance);
      //  var cu_id = collection[i].customer_id;
        
        
     //   foc_qty = foc_calculation_threshold_pick_order(cu_id,item_id, parseFloat(quantity));



        if ( parseFloat(quantity) <= 0) {
            
            continue;
        } else {
            
          /*   global_discount_amount = discount_amount
            global_qty_amount = quantity; */

            dataSource.push([
                { "type": "text", "class": "transaction-inputs", "value": item_code, "data_id": dispach_item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "","disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": item_name, "data_id":item_i, "style": "max-height:30px;margin-right:10px", "event": "", "style": "width:370px", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs math-round", "value": quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);" },
              
               
                { "type": "text", "class": "transaction-inputs", "value": pack_size, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": price, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*","disabled":"disabled" },
                { "type": "text", "class": "transaction-inputs", "value": values, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this);", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": wh_price_, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "discountAmount(this)", "width": "*","disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": rt_price_, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": cost_price_, "style": "max-height:30px;text-align:right;width:60px;margin-right:10px;", "event": "", "disabled": "disabled" },
               
                
                { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this);calculation()", "width": "*" },
                { "type": "text", "class": "transaction-inputs", "value": quantity, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" }

            ]);



        }


    }
    tableData.setDataSource(dataSource);
    calculation();
    //getHeaderDetails(OrderID);
    $('#exampleModal').modal('hide');
    
}