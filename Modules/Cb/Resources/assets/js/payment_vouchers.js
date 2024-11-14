var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var p_voucher_id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
var got_from_pickOrder = false;
var value_for_radio_button = undefined;
var analysisTableArray = [];
var commonDiscription = "";
var table_ = undefined;
$(document).ready(function () {
    $('#rdoPayee').prop('checked', true);
    $('#txtSupplier').prop('disabled', true);
    $('#cmbPayee').on('change',function(){
        if($(this).val() == 1){
            $('#txtNotApplicalePayee').prop('disabled',false);
        }else{
            $('#txtNotApplicalePayee').prop('disabled',true);
        }
    });
    getServerTime();
    getBranches();
    getReceiptMethod();
    loadPayee();
    $('.select2').select2();
    $('#cmbBranch').change();
    loadAccountAnalysisData();

    loadPamentType();
    suppliers = loadSupplierTochooser();
    ItemList = loadAccounts();
    DataChooser.addCollection("supplier", ['', '', '', '', ''], suppliers);
    DataChooser.addCollection("Accounts", ['', '', '', '', ''], ItemList);


    $('.rdo').on('change', function () {
        optionType(this)
    })

    //setting datat to data chooser -supplier
    $('#txtSupplier').on('focus', function () {
        DataChooser.showChooser($(this), $(this), "supplier");
        $('#data-chooser-modalLabel').text('Suppliers');
    });

    $('#btnSave').on('click',function(){
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            collection.push(JSON.stringify({
                "account_id": arr[i][0].attr('data-id'),
                "description": arr[i][1].val(),
                "amount": arr[i][2].val(),
                "analysis":arr[i][3].val(),
                
                
            }));
        }
        console.log(collection);
      
        
        newReferanceID('payment_vouchers',2750);
        saveVoucher(collection);
    });

    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "", "valuefrom": "datachooser", "thousand_seperator": false, "disabled": "" },
            { "type": "text", "class": "transaction-inputs", "value": commonDiscription, "style": "width:370px;" },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:120px;text-align:right;", "event": "calTotal(this)", },
            { "type": "select", "class": "transaction-inputs", "value": analysisTableArray, "style": "width:150px;", "event": "", },

            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);", "width": 30 },

        ],
        "auto_focus": 0,
        "hidden_col": []

    });

    tableData.addRow();
    table_ = tableData;
   
if (window.location.search.length > 0) {
   
    var sPageURL = window.location.search.substring(1);

   
    var params = sPageURL.split('&'); 
    
  
    var p_voucher_id = '';
    var action = '';

   
    for (var i = 0; i < params.length; i++) {
        var param = params[i].split('=');
        if (param[0] === 'id') {
            p_voucher_id = param[1];
        }
        if (param[0] === 'action') {
            action = param[1];
        }
    }

    // Log extracted parameters
    console.log(p_voucher_id);
    console.log(action);

    // Handle the action logic
    if (action == 'edit') {
        $('#btnSave').text('Update');
    } else if (action == 'view') {
        $('#btnSave').hide();
        $('#btnSaveDraft').hide();
        $('#btnApprove').hide();
        $('#btnReject').hide();
        $('#btnPickOrders').hide();
        $('#btnBack').show();
       // disableComponents();
    }

    // Call function to fetch payment voucher details
    getEachPaymentVoucher(p_voucher_id);
}

    


    $('#txtDescription').on('input', function () {
        commonDiscription = $(this).val();
        var rowObjects = tableData.getDataSourceObject();
        for (var i = 0; i < rowObjects.length; i++) {
            var desc_cell = rowObjects[i][1];
            desc_cell.val(commonDiscription);

        }

    });

});

function getEachPaymentVoucher(id){
    $.ajax({
        url: '/cb/getEachPaymentVoucher/'+id,
        type: 'get',
        async: false,
        success: function (data) {
            console.log(data.pv_item);
            $('#LblexternalNumber').val(data.pv.external_number);
            $('#invoice_date_time').val(data.pv.transaction_date);
            $('#cmbBranch').val(data.pv.branch_id);
            if(data.pv.supplier_id == null){
                $('#rdoPayee').prop('checked',true);
                $('#cmbPayee').val(data.pv.payee_id);
            }else{
                $('#rdoSup').prop('checked',true);
                $('#txtSupplier').val(data.sup_code.supplier_code);
                $('#txtSupplier').attr('data-id',data.pv.supplier_id);
            }
            $('#txtDescription').val(data.pv.description);
            $('#txtRemarks').val(data.pv.remarks);



            var dataSource = [];
            $.each(data.pv_item, function (index, value) {
              
                console.log(tableData);
                
                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.account_code, "style": "width:100px;", "event": "", "valuefrom": "datachooser", "thousand_seperator": false, "disabled": "" },
                    { "type": "text", "class": "transaction-inputs", "value": value.description, "style": "width:370px;" },
                    { "type": "number", "class": "transaction-inputs math-abs math-round", "value": value.amount, "style": "width:120px;text-align:right;", "event": "calTotal(this)", },
                    { "type": "select", "class": "transaction-inputs", "value": analysisTableArray,"selected_option":value.gl_account_analysis_id, "style": "width:150px;", "event": "", },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);", "width": 30 },

                ]);

            });
           
            tableData.setDataSource(dataSource);
            
            
        }
    });
}



function calTotal(event) {
    var total_Sum = 0;
    var arr = table_.getDataSourceObject();
    for (var i = 0; i < arr.length; i++) {
        console.log(arr[i][2].val());
        
       // total_Sum += parseFloat(arr[i][2].val().replace(/,/g, ''));
       if(!isNaN(parseFloat(arr[i][2].val()))){
        total_Sum += parseFloat(arr[i][2].val());
       }
       
    }
    
    
    $('#lblGrossTotal').text(parseFloat(total_Sum).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblNetTotal').text(parseFloat(total_Sum).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());    

}

function loadAccountAnalysisData() {
    $.ajax({
        url: '/cb/loadAccountAnalysisData',
        type: 'get',
        async: false,
        success: function (data) {
            var analysis = data.data;
            console.log(analysis);

            $.each(analysis, function (index, value) {
                analysisTableArray.push({
                    value: value.gl_account_analyse_id,
                    text: value.gl_account_analyse_name
                });
            });
        },
    })
}




function itemEventListner(event) {

    console.log(ItemList);
    DataChooser.setDataSourse(['', '', '', ''], ItemList);
    DataChooser.showChooser(event, event);
    $('#data-chooser-modalLabel').text('Items');
}

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

            })

        },
    })
}



//load item
function loadSupplierTochooser() {

    var data = [];
    $.ajax({
        url: '/prc/loadSupplierTochooser',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response) {
                var supplierData = response.data;
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
            var supID = txt[0].supplier_id;
            $('#lblSupplierAddress').val(txt[0].primary_address);
            $('#lblSupplierName').attr('data-id', supID);
            $('#txtSupplierInvoiceNumber').focus();

        },
        error: function (error) {
            console.log(error);
        },

    })
}

function loadAccounts() {
    var list = [];
    $.ajax({
        url: '/cb/loadAccounts',
        type: 'get',
        async: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;

            }
            console.log(response.data);
            $.each(response.data, function (index, value) {
                //console.log(value.hidden_id);

                $('#cmbGlAccount').append('<option value="' + value.hidden_id + '">' + value.id + '</option>');

            })
        },
        error: function (error) {
            console.log(error);
        },

    })
    return list;
}

function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event, "Accounts");
        $('#data-chooser-modalLabel').text('Accounts');
    }
}

//load payment type
function loadPamentType() {
    $.ajax({
        url: '/prc/loadPamentType',
        type: 'get',
        dataType: 'json',
        async: false,
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
    if ($(event.inputFiled).attr('id') == 'txtSupplier') {
        loadSupplierOtherDetails(value);
        $('#lblSupplierName').text(id);
    } else {
        console.log(event.inputFiled);
        var selected = event.getSelected();
        var item_id = selected.hidden_id;
        var row_childs = event.getRowChilds();
        var hash_map = [];
        var arr = tableData.getDataSource();
        for (var i = 0; i < arr.length - 1; i++) {
            hash_map.push(arr[i][0]);
        }

        console.log(hash_map);
        if (hash_map.includes(value)) {

            showErrorMessage('Already exist ' + value);
            /* alert('Already exist '+value); */
            event.inputFiled.val('');
            return;
        }



       

    }

}


// clear table
function clearTableData() {
    dataSource = [];
    tableData.setDataSource(dataSource);

}


function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_paymentVoucher_referenceId", table, doc_number);
    //  $('#LblexternalNumber').val(referanceID);
}

//clear labels

function dataChooserShowEventListener(event) {

}


//load payment term
function getReceiptMethod() {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getReceiptMethod',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var method = response.data;
                $('#cmbPaymentTerm').empty();
                for (var i = 0; i < method.length; i++) {
                    var id = method[i].customer_payment_method_id;
                    var name = method[i].customer_payment_method;
                    $('#cmbPaymentMethod').append('<option value="' + id + '">' + name + '</option>');
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

        },
        error: function (error) {
            console.log(error);
        },

    })
}

function optionType(event) {
    if ($(event).attr('id') == 'rdoPayee') {
        $('#txtSupplier').prop('disabled', true);
        $('#cmbPayee').prop('disabled', false);
        if($('#cmbPayee').val() == 1){
            $('#txtNotApplicalePayee').prop('disabled', false);
        }
    } else {
        $('#txtSupplier').prop('disabled', false);
        $('#cmbPayee').prop('disabled', true);
        $('#txtNotApplicalePayee').prop('disabled', true);
    }
}

function loadPayee() {
    $.ajax({
        url: '/cb/loadPayee',
        method: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            $.each(data.data, function (index, value) {
                $('#cmbPayee').append('<option value="' + value.payee_id + '">' + value.payee_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })
}


function saveVoucher(collection) {
    console.log(referanceID);
    
var payee = true

    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an account');
        return
    }

        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', referanceID); 
        formData.append('cmbBranch', $('#cmbBranch').val()); 
        formData.append('cmbPaymentMethod',$('#cmbPaymentMethod').val());
        formData.append('cmbGlAccount',$('#cmbGlAccount').val());
       
        if($('#rdoPayee').prop('checked')){
            formData.append('option',1);
            formData.append('payee',$('#cmbPayee').val());
            if($('#cmbPayee').val() == 1){
                if($('#txtNotApplicalePayee').val() == ""){
                    showWarningMessage("Please enter payee name");
                    payee = false
                }else{
                    payee = true;
                    formData.append('notApplicablePayee',$('#txtNotApplicalePayee').val());
                }
            }
        }else{
            formData.append('option',2);
            formData.append('supplier',$('#txtSupplier').attr('data-id'));
        }
        formData.append('description',$('#txtDescription').val());
        formData.append('remarks',$('#txtRemarks').val());
    
        if(payee){
            $.ajax({
                url: '/cb/saveVoucher',
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
                    if(response.success){
                        showSuccessMessage("Successfuly saved");
                        window.location.href = "/cb/payment_voucher_list";
                    }else{
                        showWarningMessage("Unable to save");
                    }
                }, error: function (data) {
                    console.log(data.responseText)
                }, complete: function () {
    
                }
            })
        }
        getServerTime();

    

}

function updateVoucher(collection) {
    var id = p_voucher_id;

    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an account');
        return
    }

        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', referanceID); 
        formData.append('cmbBranch', $('#cmbBranch').val()); 
        formData.append('cmbPaymentMethod',$('#cmbPaymentMethod').val());
        formData.append('cmbGlAccount',$('#cmbGlAccount').val());
       
        if($('#rdoPayee').prop('checked')){
            formData.append('option',1);
            formData.append('payee',$('#cmbPayee').val());
        }else{
            formData.append('option',2);
            formData.append('supplier',$('#txtSupplier').attr('data-id'));
        }
        formData.append('description',$('#txtDescription').val());
        formData.append('remarks',$('#txtRemarks').val());
    
        $.ajax({
            url: '/cb/updateVoucher/'+id,
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
                if(response.success){
                    showSuccessMessage("Successfuly updated");
                    window.location.href = "/cb/payment_voucher_list";
                }else{
                    showWarningMessage("Unable to update");
                }
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
                
            }
        })
        getServerTime();

    

}