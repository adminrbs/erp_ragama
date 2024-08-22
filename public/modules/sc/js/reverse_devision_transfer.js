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

    ItemList = loadItems();

    getServerTime();
   /*  suppliers = loadSupplierTochooser(); */

  /*   DataChooser.addCollection("Suppliers", ['Supplier Name', 'Supplier Code', '', '', ''], suppliers); */
    DataChooser.addCollection("item", ['', '', '', '', ''], ItemList);

    //$('#div_back_to_list').hide();

    //back
  /*   $('#btnBack').on('click', function () {
        if (task == "approval") {
            var url = "/prc/goodReceiveReturnList";
            window.location.href = url;
        } else {
            var url = "/prc/goodReceiveReturnList";
            window.location.href = url;
        }


    }); */

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

    getBranches();
    $('#cmbBranch').change();
    $('#cmb_to_Branch').change();

    //item table
    hiddem_col_array = [10];

    tableData = $('#dispatch_receive_item').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "", "valuefrom": "datachooser", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:350px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:55px;text-align:right;", "event": "calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);", "compulsory": true },

            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:70px;text-align:right;", "event": "calValueandCostPrice(this);getItemID(this);", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" }
        ],
        "auto_focus": 0,
        "hidden_col": hiddem_col_array /* [10,11,12,13]
 */

    });

    tableData.addRow();



    hiddem_col_array = [6, 7, 8, 9, 10, 11, 12, 13];
    if (window.location.search.length > 0) {
        $('#si_model_btn').hide();
        $('#div_info').hide();
        $('#cmb_to_Branch').hide();
        $('#cmb_to_Location').hide();
        $('.info').hide();
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        GRNID = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
          //  $('#btnBack').show();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
         //   $('#btnBack').show();

        } else if (action == 'edit' && status == 'Draft') {
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
            /*   $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
        } else if (action == 'view') {
             $('#btnSave').hide();
            /*$('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
           
            disableComponents(); */

        }


        get_revese_division_transfer(GRNID);

    }







    $('#btnSave').on('click', function () {
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            if (arr[i][2].val() == "" || arr[i][2].val() == "0" || arr[i][2].val() == 0) {
                showWarningMessage("Please enter quantity");
                arr[i][2].focus();
                return;
            
            } else {
                collection.push(JSON.stringify({
                    "item_id": arr[i][1].attr('data-id'),
                    "dispatch_item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "PackUnit": arr[i][3].val(),
                    "price": parseFloat(arr[i][4].val().replace(/,/g, '')),
                    "value": arr[i][5].val(),
                    "whole_sale_price": parseFloat(arr[i][6].val().replace(/,/g, '')),
                    "retial_price": parseFloat(arr[i][7].val().replace(/,/g, '')),
                    "cost_price": parseFloat(arr[i][8].val().replace(/,/g, '')),
                   

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
                    
                        newReferanceID('reverse_devision_transfers', 2100);
                        reverse_dispatch(collection);
                    
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
                    
                    approval_request("Approve",GRNID);

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
                console.log(result);
                if (result) {
                    approval_request("reject",GRNID)
                } else {

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
            $('#cmb_to_Location').change();
        },
    })
}




//add  data
function reverse_dispatch(collection) {
    
   
    formData.append('collection', JSON.stringify(collection));
    formData.append('txtYourReference', $('#txtYourReference').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('LblexternalNumber', referanceID);
    formData.append('lblNetTotal',$('#lblNetTotal').text().replace(/,/g, ''));

    
    var dispatch_id = $('#LblexternalNumber').data('id');
    
    $.ajax({
        url: '/sc/reverse_dispatch/'+dispatch_id,
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

            var status = response.status;
            if(status){
                showSuccessMessage('Record saved successfully');
                var url = "/sc/reverse_trasnfer_list"; 
                window.location.href = url; 
            }else{
                showWarningMessage('Unable to save record');
            }

            
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })

}


function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event, "item");
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
        async: false,
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
            $('#txtSupplier').attr('data-id', supID);
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
        resetTransactionTableRow(row_childs);
       

        $.ajax({
            url: '/sc/getItemInfotogrnReturn/' + branch_id + '/' + item_id + '/' + location_id_,
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



    var value = getDiscountAmount(qty, price, discount_percentage, discount_amount, foc, cost_price);
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



    var totalDiscount = tableDiscount;
    var netTotal = (grossTotal - totalDiscount + tax);

    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}


//get reverse transfer
function get_revese_division_transfer(id){
    $.ajax({
        url: '/sc/get_revese_division_transfer/' + id,
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
            
        }, success: function (response) {
        
            var res = response.header;
            var data = response.items;

            $('#LblexternalNumber').val(res.external_number);
            $('#LblexternalNumber').attr('data-id', res.reverse_devision_transfer_id);
            $('#dispatch_Date_time').val(res.trans_date);
            $('#cmbBranch').val(res.branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(res.location_id);
           /*  $('#cmb_to_Branch').val(res[0].branch_id);
            $('#cmb_to_Branch').change();
            $('#cmb_to_Location').val(res[0].location_id); */
           
            $('#txtYourReference').val(res.your_reference_number);
            $('#txtRemarks').val(res.remarks);

            var dataSource = [];
            $.each(data, function (index, value) {
                var qty = value.quantity;
                var price = value.price;
                var valueS = (qty * price);
                dataSource.push([
            { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "style": "width:100px;", "event": "", "valuefrom": "datachooser", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": value.item_Name, "style": "width:350px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.quantity, "style": "width:55px;text-align:right;", "event": "calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);", "compulsory": true,"disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value":value.	package_unit, "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value":price, "style": "width:70px;text-align:right;", "event": "calValueandCostPrice(this);getItemID(this);", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": parseFloat(valueS), "style": "width:100px;text-align:right;", "event": "", "width": "*","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": value.whole_sale_price, "style": "width:80px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": value.retial_price, "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": value.cost_price, "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" }
                    

                ]);


            });
            tableData.setDataSource(dataSource);
            calculation();
         

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });

}

//approve
function approval_request(type,id){
    $.ajax({
        url:'/sc/approval_request/'+id+'/'+type,
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
          
           //var status = response.status; 
            var msg = response.msg;
            var url = "/sc/reverse_trasnfer_approval_list"; 
           
            if(msg == "approved"){
                showSuccessMessage("Record approved successfully");
                window.location.href = url;  
            }else if(msg == "rejected"){
                showSuccessMessage("Record rejected successfully");
                window.location.href = url;  
            }else{
                showWarningMessage("Unable to update the record");
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
function getServerTime() {
    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#dispatch_Date_time').val(formattedDate);
            // $('#dtPaymentDueDate').val(formattedDate);
        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_reverse_dispatch", table, doc_number);
    //  $('#LblexternalNumber').val(referanceID);
}

//calculations
function getDiscountAmount(qty, price, discount_percentage, discount_amount, foc_quantity, cost_price) {

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
    var cost_value = (final_value / (quantity + foc));
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


function dataChooserShowEventListener(event) {
    if (pickOrderStatus) {
        DataChooser.dispose();
        pickOrderStatus = false;
    }

}





