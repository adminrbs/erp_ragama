var formData = new FormData();
var tableData = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var GRNID = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
var foc_qty_threshold_from_pick_orders = undefined;
var pickOrderStatus = false;
var hash_map = new HashMap();
var order_id_for_dis_br = undefined;
$(document).ready(function () {

    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
     $('#batchModelTitle').hide();
     $('#lblBalance').hide();
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();
   
    ItemList = loadItems();


    $('#exampleModal').on('show.bs.modal', function () {
        loadinternalOrders();
        TableRefresh();

    });
    
    getServerTime();
    suppliers = loadSupplierTochooser();

    DataChooser.addCollection("Suppliers",['Supplier Name', 'Supplier Code', '', '',''], suppliers);
    DataChooser.addCollection("item",['', '', '', '',''], ItemList);

    $('#btnBack').hide();

    $('#bntLoadData').on('click', function () {
        selectedData(order_id_for_dis_br);

    });

    $('#btnReject_order').on('click',function(){
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
                    reject_internal_Order(order_id_for_dis_br);
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
        
    });
    
    //back
    $('#btnBack').on('click',function(){
        if(task == "approval"){
            var url = "/prc/goodReceiveReturnList"; 
            window.location.href = url;
        }else{
            var url = "/prc/goodReceiveReturnList"; 
            window.location.href = url;
        }
       

    });

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    });

    $('#cmb_to_Branch').change(function () {
        var id = $(this).val();
        get_to_Location(id);
    })


    


    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });

    //getting data id and offer type
    $('#gettable').on('click', 'tr', function (e) {
        //var orderID = undefined;
        //var tableBody = $('#gettableItems tbody');
      //  tableBody.empty();

       
        $('#gettable tr').removeClass('selected');
      

        // Add the selected class to the clicked row
        $(this).addClass('selected');
        var hiddenValue = $(this).find('td:eq(0) div').attr('data-id');
        order_id_for_dis_br = hiddenValue;
        loadOrderItems(hiddenValue);
       
    
       


    });

    getBranches();
    $('#cmbBranch').change();
    $('#cmb_to_Branch').change();

     //item table
     hiddem_col_array = [13,14,15,16];
     
     tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "","valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:350px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:55px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);","compulsory":true },
            
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:70px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);", "disabled": "disabled" },    
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            
           
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            
        
            { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
        ],
        "auto_focus": 0,
        "hidden_col":hiddem_col_array /* [10,11,12,13]
 */

    });

    tableData.addRow();
    

    
   // hiddem_col_array = [6,7,8,9,10,11,12,13];
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
      /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        GRNID = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if(action == 'edit' && status == 'Original' && task == 'approval'){
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#btnBack').show();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
        
        } else if(action == 'edit' && status == 'Draft' ){
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
          /*   $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
        }else if (action == 'view') {
            /* $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
           
            disableComponents(); */
            
        }
       
       
        get_each_transfer(GRNID);

    }


   

    
   

    $('#btnSave').on('click', function () {
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            if(arr[i][0].attr('data-id') == "undefined"){
                showWarningMessage("Please select a correct Item");
                arr[i][0].focus();
                return;
            }else if(arr[i][2].val() == "" || arr[i][2].val() == "0" || arr[i][2].val() == "undefined" || arr[i][2].val() == "null"){
                showWarningMessage("Quantity must be greter than 0");
                return;
            }else if(arr[i][4].val() == "" || arr[i][4].val() == "0" || arr[i][4].val() == "undefined" || arr[i][4].val() == "null" || parseFloat(arr[i][4].val() ) == 0){
                showWarningMessage("Price must be greter than 0");
                return;
            }else{
            collection.push(JSON.stringify({
                "item_id": arr[i][0].attr('data-id'),
                "item_name": arr[i][1].val(),
                "qty": arr[i][2].val(),
                "PackUnit": arr[i][3].val(),
                "price": parseFloat(arr[i][4].val().replace(/,/g, '')),
                "from_loc_rd_sale": arr[i][6].val(),
                "to_loc_rd_sale": arr[i][7].val(),
                "from_loc_qoh": arr[i][8].val(),
                "to_loc_qoh": arr[i][9].val(),
                "whole_sale_price": parseFloat(arr[i][14].val().replace(/,/g, '')),
                "retial_price": parseFloat(arr[i][15].val().replace(/,/g, '')),
                "cost_price": parseFloat(arr[i][16].val().replace(/,/g, '')),

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
                    if ($('#btnSave').text() == 'Save') {
                      newReferanceID('dispatch_to_branches',1800);
                      add_dispatch_to_branch(collection,order_id_for_dis_br);
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
                   // alert();
                    approveRequest(GRNID);
                    
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
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#cmb_to_Branch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                

            })

        },
    })
}


//loading from location
function getLocation(id) {
    $('#cmbLocation').empty();
    $.ajax({
        url: '/sc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmbLocation').change();
           // alert($('#cmbLocation').val());
        },
    })
}
//get to location
function get_to_Location(id) {
    $('#cmb_to_Location').empty();
    $.ajax({
        url: '/sc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmb_to_Location').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })

        },
    })
}




//add grn data
function add_dispatch_to_branch(collection,order_id) {
   // alert(referanceID);
   console.log(collection);

    if(parseInt(collection.length) <= 0){
        showWarningMessage('Unable to save without an item');
        return
    }
    
    if($('#cmbBranch').val() == $('#cmb_to_Branch').val()){
        showWarningMessage('Inter branch dispatch can not be done within the same branch');
        $('#cmbBranch').removeClass('is-valid');
        $('#cmb_to_Branch').removeClass('is-valid');
        $('#cmbBranch').addClass('is-invalid');
        $('#cmb_to_Branch').addClass('is-invalid');

    }else{
        var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
        formData.append('collection', JSON.stringify(collection));
        formData.append('setOffArray', createSetoffCollection());
        formData.append('LblexternalNumber',referanceID);
        formData.append('dispatch_Date_time', $('#dispatch_Date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('cmb_to_Branch', $('#cmb_to_Branch').val());
        formData.append('cmb_to_Location', $('#cmb_to_Location').val());
        formData.append('your_reference_number', $('#txtYourReference').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('lblNetTotal', total_amount);
        formData.append('from_date',$('#from_date').val());
        formData.append('to_date',$('#to_date').val());

        $.ajax({
            url: '/sc/add_dispatch_to_branch/'+order_id,
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
                /* $('#btnSave').prop('disabled', true); */
            }, success: function (response) {
               
                $('#btnSave').prop('disabled', false);
                var status = response.status
               // var primaryKey = response.primaryKey;
                var msg = response.message
                if (msg == 'insuficent') {
                    showWarningMessage("Insufficent Balance");
                    return;
                }else if(msg == 'qty_zero'){
                    showWarningMessage("Quantity should be greater than 0");
                    return;
                }else if(msg == 'set_off_zero'){
                    showWarningMessage("Set off quantity should be greater than 0");
                    return;
                }
                if (msg == 'insuficent') {
                    showWarningMessage("Insufficent Balance");
                    return;
                }
                if (status) {
                    showSuccessMessage("Successfully saved");
                    
                    var url = "/sc/dispatch_to_branch_list"; 
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
        url: '/prc/loadItems',
        type: 'get',
        processData: false,
        contentType: false,
        cache: false,
        async:false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;
                /* DataChooser.setDataSourse(itemData); */
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return list;
}

//load item
function loadSupplierTochooser() {

    var data = [];
    $.ajax({
        url: '/prc/loadSupplierTochooser',
        type: 'get',
        dataType: 'json',
        processData: false,
        contentType: false,
        cache: false,
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
            $('#lblSupplierAddress').val(txt[0].primary_address);
            var supID = txt[0].supplier_id;
            $('#txtSupplier').attr('data-id',supID);
            $('#txtSupplierInvoiceNumber').focus();

        },
        error: function (error) {
            console.log(error);
        },

    })
}


function dataChooserEventListener(event, id, value) {
    var branch_id = $('#cmbBranch').val();
    var location_id_ = $('#cmbLocation').val();
    var fromdate = $('#from_date').val();
    var todate = $('#to_date').val();
    var to_branch = $('#cmb_to_Branch').val();
    var to_location = $('#cmb_to_Location').val();
    if ($(event.inputFiled).attr('id') == 'txtSupplier') {
        loadSupplierOtherDetails(value);
        $('#lblSupplierName').text(id);
    } else {
        console.log(event.inputFiled);
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
     //var pr_price_ = get_Pr_price(item_id)

        $.ajax({
            url: '/sc/getItemInfotodivisiontransferentry/'+branch_id+'/' + item_id + '/' + location_id_+'/'+to_branch+'/'+to_location,
            type: 'get',
            data:{
                fromD : fromdate,
                toD : todate
            },
            success: function (response) {
                console.log(response);

               

                $(row_childs[1]).val(response[0].item_Name);
           
                $(row_childs[3]).val(response[0].package_unit);
              
                $(row_childs[6]).val(Math.abs(response[0].from_sales));
                $(row_childs[7]).val(Math.abs(response[0].to_sales));
                $(row_childs[8]).val(Math.abs(response[0].from_balance));
                $(row_childs[9]).val(Math.abs(response[0].to_balance));
                $(row_childs[2]).focus();
                $('#from_date').prop('disabled',true);
                $('#to_date').prop('disabled',true);
                $('#cmbBranch').prop('disabled',true);
                $('#cmbLocation').prop('disabled',true);
                $('#cmb_to_Branch').prop('disabled',true);
                $('#cmb_to_Location').prop('disabled',true);
            }
        })

    }

}




//cal val and cost (change with qty, free qty)
function calValueandCostPrice(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[7]).children()[0]);
    var discount_percentage = $($(cell[8]).children()[0]);
    var discount_amount = $($(cell[9]).children()[0]);
    var foc = $($(cell[3]).children()[0]);
    var cost_price = $($(cell[15]).children()[0]);



    var value = getDiscountAmount(qty, price, discount_percentage, discount_amount,foc,cost_price);
    $($(cell[10]).children()[0]).val(value);
   
 
    calculation();

}


//grand total
function calculation() {
    var grossTotal = 0;
    var tableDiscount = 0;
    var tax = 0;
    var arr = tableData.getDataSourceObject();
    

    for (var i = 0; i < arr.length; i++) {
        var qty = parseFloat(arr[i][2].val().replace(/,/g, ""));
        var price = parseFloat(arr[i][4].val().replace(/,/g, ""));
        var discount_pres = 0;
        console.log(price);

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

    

    var totalDiscount =  tableDiscount;
    var netTotal = (grossTotal - totalDiscount + tax);

    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}


/* getEachGR_rtn */
function get_each_transfer(id) {
   
    /* formData.append('status', status); */
    $.ajax({
        url: '/sc/get_each_transfer/' + id,
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
            
        }, success: function (data) {
            console.log(data);
            var GTR = data.gtr;
            var GTR_item = data.gtr_item;
            $('#LblexternalNumber').val(GTR.external_number);
            $('#goods_received_date_time').val(GTR.goods_transfer_date);
            $('#txtYourReference').val(GTR.your_reference_number);
            $('#cmbBranch').val(GTR.from_branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(GTR.from_location_id);
            $("#cmb_to_Branch").val(GTR.to_branch_id);
            $("#cmb_to_Branch").change()
            $('#cmb_to_Location').val(GTR.to_location_id);

           

            /*  */
            var dataSource = [];
            $.each(GTR_item, function (index, value) {
              dataSource.push([
            { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "style": "width:100px;", "event": "","valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": value.item_Name, "style": "width:350px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.quantity, "style": "width:55px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);","compulsory":true },
            { "type": "text", "class": "transaction-inputs","value": value.package_unit, "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.price, "style": "width:70px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);", "disabled": "disabled" },    
            { "type": "text", "class": "transaction-inputs","value": value.batch_number, "style": "width:100px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:80px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs math-abs","value": "", "style": "width:80px;text-align:right;", "event":"calValueandCostPrice(this)", "width": "*","thousand_seperator":true,"disabled": "disabled"},

                ]);


            });
            
            tableData.setDataSource(dataSource);
          
                    
                  
            

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}





//approve
/* function approveRequest(id){
    $.ajax({
        url:'/sc/approve_goods_transfer/'+id,
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
           
        }, success: function (response) {
          
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
} */

//reject
function rejectRequestGRReturn(id){
    $.ajax({
        url:'/prc/rejectRequestGRReturn/'+id,
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
            $('#dispatch_Date_time').val(formattedDate);

            var currentDate = new Date(formattedDate);
            // Get the first date of the month
            var firstDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            var formattedFirstDate = formatDate(firstDateOfMonth);

            // Get the last date of the month
            var lastDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            var formattedLastDate = formatDate(lastDateOfMonth);
           console.log(lastDateOfMonth);
            $('#from_date').val(formattedFirstDate);
            $('#to_date').val(formattedLastDate);
          
        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table,doc_number) {
    referanceID = newID("../newReferenceNumber_dispatch_to_branch", table,doc_number);
 //  $('#LblexternalNumber').val(referanceID);
}

//calculations
function getDiscountAmount(qty, price, discount_percentage, discount_amount,foc_quantity,cost_price) {

    var quantity = parseFloat(qty.val().replace(/,/g, ""));
    var unit_price = parseFloat(price.val().replace(/,/g, ""));
    var percentage = parseFloat(discount_percentage.val().replace(/,/g, ""));
    var amount = parseFloat(discount_amount.val().replace(/,/g, ""));
    var foc = parseFloat(foc_quantity.val().replace(/,/g, ""));

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
    if (isNaN(foc)) {
        foc = 0;
    }


    var quantity_price = (quantity * unit_price);
    var percentage_price = (quantity_price / 100.00) * percentage;

    if (discount_percentage.is(':focus')) {
        discount_amount.val(percentage_price);
    } else if (discount_amount.is(':focus')) {
        var prc = (amount / quantity_price) * 100.0;
        discount_percentage.val(prc.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    }

    var final_value = (quantity_price - percentage_price);
    var cost_value = (final_value/(quantity+foc));
    cost_price.val(cost_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());

    return final_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString();
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


 function  dataChooserShowEventListener(event){
    if(pickOrderStatus){
      DataChooser.dispose();
      pickOrderStatus = false;
    }
      
  }
  

  function formatDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1; // Months are zero-based
    var year = date.getFullYear();

    // Pad day and month with leading zeros if needed
    day = day < 10 ? '0' + day : day;
    month = month < 10 ? '0' + month : month;

    return day + '/' + month + '/' + year;
}


//load internal orders to model table
function loadinternalOrders(){
    var from_br = $('#cmbBranch').val(); // stock sending branch
   // var from_loc = $('#cmbLocation').val();
    var to_br =  $('#cmb_to_Branch').val(); // stock receiving branch
    $.ajax({
        type: "GET",
        url: "/sc/loadinternalOrders/" + from_br + "/" + to_br,
        cache: false,
        async:false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            
            var dt = response.data;
           /*  var block_data = response.block_data;
            console.log(block_data); */
            var data = [];

            for (var i = 0; i < dt.length; i++) {
                data.push({
                   
                    "date": '<div data-id = "' + dt[i].internal_orders_id + '">' + dt[i].order_date_time + '</div>',
                    "Order_no": dt[i].external_number,
                  
                    "from_branch": '<div data-id = "' + dt[i].from_branch_id + '">' +dt[i].branch_name + '</div>',
                    
                   
                });


             


            }
            

            var table = $('#gettable').DataTable();
            table.clear();
            table.rows.add(data).draw();
           // table.refresh();
            //table.ajax.reload();
            
            

            

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { 
            TableRefresh();
        }
    })

}

//load order items
function loadOrderItems(id){
    var table = $('#gettableItems');
    var tableBody = $('#gettableItems tbody');
    var branch_id = $('#cmbBranch').val();
    var location_id = $('#cmbLocation').val();
    tableBody.empty();
    $.ajax({
        type: "GET",
        url: "/sc/loadOrderItems/" + id + "/" + branch_id +'/' + location_id,
        cache: false,
        timeout: 800000,
        async:false,
        beforeSend: function () { },
        success: function (data) {
            var dt = data.data;
           
           
           
            $.each(dt, function (index, item) {
                var totalQty = 0;
               
               
              
                totalQty = parseFloat(item.quantity)
                var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.item_id).text(item.Item_code)));
                row.append($('<td>').css('width', '150px').append('<div>' + item.item_name + '</div>'));
                row.append($('<td>').text(item.Balance));
                row.append($('<td>').text(item.quantity));
                
                row.append($('<td>').text(item.package_unit));
                row.append($('<td>').append($('<input>').attr({
                    'type': 'checkbox',
                    'id': item.internal_order_items_id // Replace 'yourDesiredId' with the desired ID value
                }).val(item.internal_order_items_id).prop('checked', true)));
                

                if (parseFloat(totalQty) > parseFloat(item.Balance)) {
                    row.css('color', 'rgb(255, 0, 0)');
                    row.find('.transaction-inputs').css('color', 'rgb(255, 0, 0)');
                }
                table.append(row);
            });

            

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}


//reject order
function reject_internal_Order(order_id){

    
    $.ajax({
        url: '/sc/reject_internal_Order/'+order_id,
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
            /* $('#btnSave').prop('disabled', true); */
        }, success: function (response) {
           
            var status = response.success;
            if(status){
                showSuccessMessage('Order rejected successfully');
            }else{
                showWarningMessage('Unable to reject');
            }
            $('#exampleModal').modal('hide');

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })

}