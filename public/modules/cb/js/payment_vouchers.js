var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var GRNID = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
var got_from_pickOrder = false;
var value_for_radio_button = undefined;
var analysisTableArray = []
$(document).ready(function () {
    $('#rdoPayee').prop('checked',true);
    $('#txtSupplier').prop('disabled',true);
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
    DataChooser.addCollection("supplier",['', '', '', '',''], suppliers);
    DataChooser.addCollection("Accounts",['', '', '', '',''], ItemList);


    $('.rdo').on('change',function(){
        optionType(this)
    })

    //setting datat to data chooser -supplier
    $('#txtSupplier').on('focus', function () {
        DataChooser.showChooser($(this),$(this),"supplier");
        $('#data-chooser-modalLabel').text('Suppliers');
    });

    

    if (window.location.search.length > 0) {
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
            $('#btnSaveDraft').hide();
            $('#btnPickOrders').hide();
            $('#btnBack').show();
            $('#alert_div').hide();
           
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnPickOrders').hide();
            $('#btnBack').show();

        } else if (action == 'edit' && status == 'Draft') {
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
           /*  $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnPickOrders').hide();
            $('#btnBack').show();

        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnPickOrders').hide();
            $('#btnBack').show();
            disableComponents();

        }
       
        getEachGRN(GRNID, status);
        getEachproduct(GRNID, status);

    }
    const [count, setCount] = useState("");
    //item table
     tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "","valuefrom": "datachooser","thousand_seperator":false,"disabled": "" },
            { "type": "text", "class": "transaction-inputs", "value": count, "style": "width:370px;", "disabled": "disabled" },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:120px;text-align:right;", "event": "",  },
            { "type": "select", "class": "transaction-inputs", "value": analysisTableArray, "style": "width:150px;", "event": "",  },
            
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);", "width": 30 },
            
        ],
        "auto_focus": 0,
        "hidden_col": []

    });

    tableData.addRow();
   
 
 

});


function loadAccountAnalysisData(){
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




function itemEventListner(event){

    console.log(ItemList);
    DataChooser.setDataSourse(['','','',''],ItemList);
    DataChooser.showChooser(event,event);
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
                console.log(supplierData);
           /*   DataChooser.setDataSourse(['','','',''],supplierData); */
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
            /*  console.log(txt); */
            $('#lblSupplierAddress').val(txt[0].primary_address);
            $('#lblSupplierName').attr('data-id',supID);
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
        async:false,
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
        DataChooser.showChooser(event, event,"Accounts");
        $('#data-chooser-modalLabel').text('Accounts');
    }
}

//load payment type
function loadPamentType() {
    $.ajax({
        url: '/prc/loadPamentType',
        type: 'get',
        dataType: 'json',
        async:false,
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



        $.ajax({
            url: '/prc/getItemInfo/' + item_id,
            type: 'get',
            success: function (response) {
                console.log(response);
                var expireDateManage = response[0].manage_expire_date;
                var batchManage = response[0].manage_batch;
                if (expireDateManage == 1) {
                   
                    $(row_childs[14]).removeAttr('disabled');
                }
                if (batchManage == 1) {
                    $(row_childs[13]).val(response[0].Item_code);
                    $(row_childs[13]).removeAttr('disabled');
                }

                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[4]).val(response[0].unit_of_measure);
                $(row_childs[6]).val(response[0].package_unit);
                $(row_childs[5]).val(response[0].package_size);
                $(row_childs[7]).val(response[0].average_cost_price);
                $(row_childs[11]).val(response[0].whole_sale_price);
                $(row_childs[12]).val(response[0].retial_price);
                $(row_childs[2]).focus();
                $(row_childs[2]).val('');
                $(row_childs[3]).val('');
               
                $(row_childs[9]).val('');
                $(row_childs[10]).val('');
                $(row_childs[15]).val('');

                if($('#txtDiscountPrecentage').val().length > 0){
                    $(row_childs[8]).val($('#txtDiscountPrecentage').val());
                    
                }else{
                    $(row_childs[8]).removeAttr('disabled');
                }
                calculation();
                
            }
        })

    }

}


// clear table
function clearTableData() {
    dataSource = [];
    tableData.setDataSource(dataSource);

}






function newReferanceID(table,doc_number) {
     referanceID = newID("../newReferenceNumber_GRN_referenceId", table,doc_number);
  //  $('#LblexternalNumber').val(referanceID);
}

//clear labels

function dataChooserShowEventListener(event){

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

function optionType(event){
    if($(event).attr('id') == 'rdoPayee'){
       $('#txtSupplier').prop('disabled',true);
       $('#cmbPayee').prop('disabled',false);
    }else{
        $('#txtSupplier').prop('disabled',false);
        $('#cmbPayee').prop('disabled',true);
    }
}

function loadPayee(){
    $.ajax({
        url: '/cb/loadPayee',
        method: 'get',
        dataType: 'json',
        async:false,
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


function useState(initialValue) {
    let state = initialValue;
    
    const getState = () => state;
    
    const setState = (newValue) => {
      state = newValue;
    };
    
    return [getState, setState];
  }