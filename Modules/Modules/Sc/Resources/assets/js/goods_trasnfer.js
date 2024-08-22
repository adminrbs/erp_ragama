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
$(document).ready(function () {
     $('#batchModelTitle').hide();
     $('#lblBalance').hide();
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();
   // $('#cmb_to_Branch').hide();
   
    ItemList = loadItems();
    loadPamentType();
    getServerTime();
    suppliers = loadSupplierTochooser();

    DataChooser.addCollection("Suppliers",['Supplier Name', 'Supplier Code', '', '',''], suppliers);
    DataChooser.addCollection("item",['', '', '', '',''], ItemList);

    $('#btnBack').hide();

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
        $('#cmb_to_Branch').val(id);
        $('#cmb_to_Branch').change();
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

    getBranches();
    $('#cmbBranch').change();
    $('#cmb_to_Branch').change();

     //item table
     hiddem_col_array = [10,11,12,13];
     
     tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "","valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:350px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:55px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);","compulsory":true },
            
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:70px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);checkqtyandFoc_gr_rtn(this);", "disabled": "disabled" },    
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "width:80px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
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
    

    
    hiddem_col_array = [6,7,8,9,10,11,12,13];
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
                "batch_number": arr[i][5].val(),
                "whole_sale_price": parseFloat(arr[i][11].val().replace(/,/g, '')),
                "retial_price": parseFloat(arr[i][12].val().replace(/,/g, '')),
                "cost_price": parseFloat(arr[i][13].val().replace(/,/g, '')),

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
                      newReferanceID('goods_transfers',1200);
                      add_goods_transfer(collection);
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
                rejectRequestGRReturn(GRNID);
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

//enable date
function enableDate(event) {
    
     var value = $(event).val();

    if (value.length === 4) {
        value += '-';
        $(event).val(value);
    }

    if (value.length === 7) {
        value += '-';
        $(event).val(value);
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
function add_goods_transfer(collection) {
   // alert(referanceID);
    if(parseInt(collection.length) <= 0){
        showWarningMessage('Unable to save without an item');
        return
    }
    
    if($('#cmbLocation').val() == $('#cmb_to_Location').val()){
        showWarningMessage('Goods transfer can not be done within the same branch');
        $('#cmbLocation').removeClass('is-valid');
        $('#cmb_to_Location').removeClass('is-valid');
        $('#cmbLocation').addClass('is-invalid');
        $('#cmb_to_Location').addClass('is-invalid');

    }else{
        var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
        formData.append('collection', JSON.stringify(collection));
        formData.append('setOffArray', createSetoffCollection());
        formData.append('LblexternalNumber',referanceID);
        formData.append('goods_transfer_date', $('#goods_received_date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('cmb_to_Branch', $('#cmb_to_Branch').val());
        formData.append('cmb_to_Location', $('#cmb_to_Location').val());
        formData.append('your_reference_number', $('#txtYourReference').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('lblNetTotal', total_amount);
        $.ajax({
            url: '/sc/add_goods_transfer',
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
                var primaryKey = response.primaryKey;
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
                    /* resetForm(); */
                    /* clearTableData();
                    tableData.addRow(); */
                    var url = "/sc/goods_transfer_list"; 
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
    var branch_id = $('#cmbBranch').val();
    var location_id_ = $('#cmbLocation').val();
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
            url: '/sc/getItemInfotogrnReturn/'+branch_id+'/' + item_id + '/' + location_id_,
            type: 'get',
            success: function (response) {
                console.log(response);

               

                $(row_childs[1]).val(response[0].item_Name);
             //   $(row_childs[4]).val(response[0].unit_of_measure);
                $(row_childs[3]).val(response[0].package_unit);
               // $(row_childs[4]).val(response[0].previouse_purchase_price);
                $(row_childs[6]).val(response[0].Balance);
               
              
                $(row_childs[2]).focus();
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
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.price, "style": "width:70px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);checkqtyandFoc_gr_rtn(this);", "disabled": "disabled" },    
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
            $('#goods_received_date_time').val(formattedDate);
            $('#dtPaymentDueDate').val(formattedDate);
        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table,doc_number) {
    referanceID = newID("../newReferenceNumber_Goods_transfer", table,doc_number);
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
  
  //foc calculation threshold (manually item insertion)
var global_foc_qty_ = 0;
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

    $.ajax({
        url: '/prc/getItem_foc_threshold_For_grnRtn/' + item_id + "/" + formatted_entered_qty + "/" + date,
        type: 'get',
        success: function (response) {
            // console.log(response);
            $.each(response, function (index, value) {
               
                global_foc_qty_ = value.Offerd_quantity;
                $($(item_row.children()[3]).children()[0]).val(value.Offerd_quantity)
               
            })

        }
    })

}

//foc calculation threshold (pick order insertion)
function foc_calculation_threshold_pick_order(item_id, entered_qty) {
    var date = $('#invoice_date_time').val();


    $.ajax({
        url: '/sd/getItem_foc_threshold_ForInvoice/' + item_id + "/" + entered_qty + "/" + date,
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



  //check foc with auto caled  FOC
  function checkqtyandFoc_gr_rtn(event){
    var row = $($(event).parent()).parent();
    var cell = row.find('td'); 
    var foc = $($(cell[3]).children()[0]).val();
    
   

    if(parseInt(foc) > parseInt(global_foc_qty_)){
        showWarningMessage('FOC should be equal or less than system foc');
        $($(cell[3]).children()[0]).val(global_foc_qty_);
        
    }
    calValueandCostPrice(event)


  }