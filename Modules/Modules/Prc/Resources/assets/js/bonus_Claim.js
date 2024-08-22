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

$(document).ready(function () {

  
     $('#cmbBranch').change(function () {
      
         var id = $(this).val();
         getLocation(id);
     })
     $('#btnBack').hide();
     //back

     //close warning
     $('#warningClose').on('click',function(){
        /* $('#warning_alert').css('display', 'none'); */
       
        $('#warning_alert').removeClass('show');
    });


     $('#btnBack').on('click',function(){
        if(task == "approval"){
            var url = "/prc/bonus_claim_List"; 
            window.location.href = url;
        }else{
            var url = "/prc/bonus_claim_List"; 
            window.location.href = url;
        }
       

    });
 
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();
  
    getBranches();
    $('#cmbBranch').change();
    ItemList =  loadItems();
    loadPamentType();
    getServerTime();
    suppliers = loadSupplierTochooser();

    //DataChooser.addCollection("supplier",['', '', '', '',''], suppliers);
    DataChooser.addCollection("item",['', '', '', '',''], ItemList);


    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });

    //thousans seperators for invoice amount textbox

    $('#txtInvoiceAmount').on('input', function() {
        // Get the current value of the input
        let inputValue = $(this).val();

        // Remove any existing thousands separators (commas)
        inputValue = inputValue.replace(/,/g, '');

        // Replace multiple consecutive dots with a single dot
        inputValue = inputValue.replace(/(\.\d*?)\./g, '$1');

        // Remove any non-digit characters except for the first dot
        inputValue = inputValue.replace(/[^\d.]/g, '');
        inputValue = formatNumberWithCommas(inputValue);
        $(this).val(inputValue);
    });
   


    //setting datat to data chooser -supplier
    // $('#txtSupplier').on('focus', function () {
    //     DataChooser.showChooser($(this),$(this),"supplier");
    //     $('#data-chooser-modalLabel').text('Suppliers');
    // });


    $.ajax({
        url: '/prc/txtSupplier',
        type: 'get',
        async: false,
        success: function (data) {
console.log(data);
            // $('#txtSupplier').val(data[0].supplier_code);
            //  $('#lblSupplierName').val(data[0].supplier_name);
            // $('#lblSupplierAddress').val(data[0].primary_address);

        },
    })



    $('#txtDiscountAmount').on('input', function () {
        calculation();

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
            //disableComponents();

        }
        /*  getEachPurchasingOrder(reuqestID, status);
         getEachproduct(reuqestID, status);
         getEachOther(reuqestID, status); */
        getEacchBonusclaim(GRNID, status);
        getEachproduct(GRNID, status);

    }

    //item table
     tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "","valuefrom": "datachooser","thousand_seperator":false,},
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:370px;",  },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "compulsory":true },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:80px;text-align:right;", "event": "","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled"},
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*","thousand_seperator":true },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "class": "transaction-inputs ", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:150px;", "event": "", "width": "*","disabled": "disabled" }, //batch 13
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "enableDate(this)", "width": "*", "disabled": "disabled" }, //date 14
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "", "width": "*", "disabled": "disabled" }, 
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "", "width": "*", "disabled": "disabled" }, 
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" }
        ],
        "auto_focus": 0,
        "hidden_col": [3,5,8,9,13,14,17,18,19,20]

    });

    tableData.addRow();
    /* $('#rdoWholeSalePrice').prop('checked',true);
    disablePurchasePriceandWholeSalePrice(tableData,'whole_sale'); */
    value_for_radio_button = 'whole_sale';
 
      /* $('#tblData').on('input', 'input[type="text"]', function () {
        var cellIndex = $(this).closest('td').index();
        console.log(cellIndex);
        var dotCount = (cellIndex.value.match(/\./g) || []).length;
            if (dotCount > 1) {
                this.value = this.value.replace(/\.+$/, '');
            } */
       /*  if (cellIndex != 14) {
           // this.value = this.value.replace(/[^0-9]/g, ''); // allow numbers only
            var dotCount = (this.value.match(/\./g) || []).length;
            if (dotCount > 1) {
                this.value = this.value.replace(/\.+$/, '');
            }
        }  else {
            this.value = this.value.replace(/[^0-9-]/g, ''); // Allow numbers, dots, and minus sign
            var dotCount = (this.value.match(/\./g) || []).length;
            var minusCount = (this.value.match(/-/g) || []).length;
            if (dotCount > 1 || minusCount > 2) {
                this.value = this.value.replace(/\.+$/, '');
                this.value = this.value.replace(/-+$/, '');
            }
        }  */


 /*    });   
 */
/*  $('#tblData').on('input', 'input[type="text"]', function () {
    var cellIndex = $(this).closest('td').index();

    if (cellIndex === 13) {
        // Allow numbers, '.', and letters for cell index 13
        this.value = this.value.replace(/[^0-9A-Za-z.]/g, '');
        var dotCount = (this.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            this.value = this.value.replace(/\.+$/, '');
        }
    } else if (cellIndex === 14) {
        // Allow numbers and hyphens for cell index 14
        this.value = this.value.replace(/[^0-9-]/g, '');
    } else {
        // Allow only numbers and dots for other cell indexes
        this.value = this.value.replace(/[^0-9.]/g, '');
        var dotCount = (this.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            this.value = this.value.replace(/\.+$/, '');
        }
    }
}); */

$('#tblData').on('input', 'input[type="text"]', function () {
    var cellIndex = $(this).closest('td').index();

    if (cellIndex === 13) {
        // Allow numbers, '.', and letters for cell index 13
        this.value = this.value.replace(/[^0-9A-Za-z.]/g, '');
        var dotCount = (this.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            this.value = this.value.replace(/\.+$/, '');
        }

         // Remove any dots except the first one
         if ((this.value.match(/\./g) || []).length > 1) {
            var parts = this.value.split('.');
            this.value = parts.shift() + '.' + parts.join('').replace(/\./g, '');
        }
    } else if (cellIndex === 14) {
        // Allow numbers and hyphens for cell index 14
        this.value = this.value.replace(/[^0-9-]/g, '');
    } else {
        // Allow only numbers and dots for other cell indexes
        this.value = this.value.replace(/[^0-9.]/g, '');
        var dotCount = (this.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            this.value = this.value.replace(/\.+$/, '');
        }

         // Remove any dots except the first one
         if ((this.value.match(/\./g) || []).length > 1) {
            var parts = this.value.split('.');
            this.value = parts.shift() + '.' + parts.join('').replace(/\./g, '');
        }
    }
});


    

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
            }else if(arr[i][7].val() == "" || arr[i][7].val() == "0" || arr[i][7].val() == "undefined" || arr[i][7].val() == "null" || parseFloat(arr[i][7].val() ) == 0){
                showWarningMessage("Price must be greter than 0");
                return;
            }else if(arr[i][1].val() == ""){
                showWarningMessage("Please select a item correctly");
                return;
            }
            else{
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": parseFloat(arr[i][2].val().replace(/,/g, '')),
                    "addBonus": arr[i][4].val(),
                    "PackUnit": arr[i][6].val(),
                    "PackSize": arr[i][5].val(),
                    "free_quantity": parseFloat(arr[i][3].val().replace(/,/g, '')),
                    "price": parseFloat(arr[i][7].val().replace(/,/g, '')),
                    "discount_percentage": arr[i][8].val(),
                    "discount_amount": parseFloat(arr[i][9].val().replace(/,/g, '')), 
                    "whole_sale_price":parseFloat(arr[i][11].val().replace(/,/g, '')),
                    "retial_price": parseFloat(arr[i][12].val().replace(/,/g, '')),
                    "batch_number":arr[i][13].val(), 
                    "expire_date":arr[i][14].val(), 
                    "cost_price":parseFloat( arr[i][15].val().replace(/,/g, '')),
                    "purchase_order_item_id":arr[i][1].attr('data-id'),
                    "previouse_whole_sale_price":parseFloat(arr[i][17].val().replace(/,/g, '')),
                    "previouse_retial_price": parseFloat(arr[i][18].val().replace(/,/g, ''))

        
                }));

            }
           


        }
        calculation();
        console.log(collection);
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
                    var inv_amount = parseFloat($('#txtInvoiceAmount').val().replace(/,/g, ''));
                    var lbl_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
                   if(inv_amount != lbl_amount){
                    showWarningMessage('Invoice amount should be eqaul to net total');
                    $('#txtInvoiceAmount').addClass('is-invalid');
                    return;
                   }
            
                    if ($('#btnSave').text() == 'Save and Send') {
                        newReferanceID('bonus_claims',2700);
                        addBonusClaim(collection, GRNID);
                        closeCurrentTab()
                        // if(got_from_pickOrder){
                            
                        //     newReferanceID('goods_received_notes',120);
                        //     addGRN(collection, GRNID);
                        // }else{
                           
                        //     showWarningMessage('Kindly pick a PO from PO list');
                        //     return;
                        // }
                       
                    } else if ($('#btnSave').text() == 'Update') {
                        updateGRN(collection, GRNID);
                        closeCurrentTab()

                    }
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

    $('#btnSaveDraft').on('click', function () {
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            if(arr[i][0].attr('data-id') == "undefined"){
                showWarningMessage("Please select a correct Item");
                arr[i][0].focus();
                return;
            }else{
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "uom": arr[i][4].val(),
                    "PackUnit": arr[i][6].val(),
                    "PackSize": arr[i][5].val(),
                    "free_quantity": arr[i][3].val(),
                    "price": arr[i][7].val(),
                    "discount_percentage": arr[i][8].val(),
                    "discount_amount": arr[i][9].val(),
                    "whole_sale_price": arr[i][11].val(),
                    "retial_price": arr[i][12].val(),
                    "batch_number": arr[i][13].val(),
                    "expire_date": arr[i][14].val(),
                    "cost_price": arr[i][15].val(),
                    
                }));

            }
           


        }
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
                    if ($('#btnSaveDraft').text() == 'Save Draft') {
                        // newReferanceID('goods_received_note_drafts',120);
                        // addGRNDraft(collection);
                        getServerTime();

                    } else if ($('#btnSaveDraft').text() == 'Update Draft') {


                        updateGRNDraft(collection, GRNID);
                        getServerTime();

                    }
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
                    rejectRequestGRN(GRNID);
                } else {

                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');


    })

 /*    $('#rdoWholeSalePrice').on('change',function(){
        if ($(this).is(':checked')) {
            disablePurchasePriceandWholeSalePrice(tableData,'whole_sale');
            value_for_radio_button = 'whole_sale';
          }
    });

    $('#rdoCostPrice').on('change',function(){
        if ($(this).is(':checked')) {
            disablePurchasePriceandWholeSalePrice(tableData,'cost_price');
            value_for_radio_button = 'cost_price';
          }
    });
  */

});




//add thousand seperators to invoice amount
function formatNumberWithCommas(number) {
    // Convert the number to a string and add thousands separators
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
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

//enable date
function enableDate(event) {
    /*  var length = $(event).val().replace('-', '').length;

   if ((length <= 4) && (length % 2 == 0)) {
       $(event).val($(event).val() + '-');
   } 
 */
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

            })
           
        },
    })
}


//loading location
function getLocation(id) {
    $('#cmbLocation').empty();
    $.ajax({
        url: '/prc/getLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmbLocation').trigger('change');
        },
    })
}



//add grn data
function addBonusClaim(collection, id) {

    if(parseInt(collection.length) <= 0){
        showWarningMessage('Unable to save without an item');
        return
    }
    // var return_result = _validation($('#txtSupplier'),$('#lblSupplierName'));
    // if (return_result) {
    //     showWarningMessage("Please fill all required fields");
    //     return;
    // } 
  else {
  
    var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
    
    
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber',referanceID);
    formData.append('bonus_claim_date_time', $('#bonus_claim_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    //  formData.append('txtSupplier', 1)//$('#lblSupplierName').val());
    //  formData.append('lblSupplierName', $('#lblSupplierName').val());
    //  formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
    formData.append('txtPurchaseORder', $('#txtPurchaseORder').data('id'));
    formData.append('txtSupplierInvoiceNumber', $('#txtSupplierInvoiceNumber').val());
    formData.append('dtPaymentDueDate', $('#dtPaymentDueDate').val());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtAdjustmentAmount', $('#txtAdjustmentAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtYourReference', $('#txtYourReference').val());
    formData.append('lblNetTotal',total_amount);
    formData.append('txtInvoiceAmount',parseFloat($('#txtInvoiceAmount').val().replace(/,/g, '')));

    //console.log(formData);

    $.ajax({
        url: '/prc/addbonusClaim/' + id,
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
            console.log(response);
            var status = response.status
            var primaryKey = response.primaryKey;
            var message = response.message;
            var PO_id = response.PO_id;
            
            if(message == "null"){
                showWarningMessage("Whole sale price can not be null or 0.00");
                return;
            }else if(message == "null retail"){
                showWarningMessage("Retail price can not be null or 0.00");
                return;
            }else if(message == "null cost"){
                showWarningMessage("Cost price can not be null 0.00");
                return;
            }else if(message == "null price"){
                showWarningMessage("Price can not be null 0.00");
                return;
            }else if(message == "completed"){
                showWarningMessage("PO already completed");
                return;
            }else if(message == 'margin_gaps'){
                var ids = response.margin_gaps;
                validate_table(ids);
                return;
            }


            if (status) {
                showSuccessMessage("Successfully saved");
            
            // completeOrder_status_auto(PO_id)
                
                clearTableData();
                tableData.addRow();
                clearLabels();
                getServerTime();
                resetForm();
                url = "/prc/bonus_claim_List";
                window.location.href = url; 

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })

    getServerTime();
}
}

// //add GRN draft
// function addGRNDraft(collection) {
//     formData.append('collection', JSON.stringify(collection));
//     formData.append('LblexternalNumber',referanceID);
//     formData.append('bonus_claim_date_time', $('#bonus_claim_date_time').val());
//     formData.append('cmbBranch', $('#cmbBranch').val());
//     formData.append('cmbLocation', $('#cmbLocation').val());
//     formData.append('txtSupplier', $('#lblSupplierName').data('id'));
//     formData.append('lblSupplierName', $('#lblSupplierName').text());
//     formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
//     formData.append('txtPurchaseORder', $('#txtPurchaseORder').val());
//     formData.append('txtSupplierInvoiceNumber', $('#txtSupplierInvoiceNumber').val());
//     formData.append('dtPaymentDueDate', $('#dtPaymentDueDate').val());
//     formData.append('cmbPaymentType', $('#cmbPaymentType').val());
//     formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
//     formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
//     formData.append('txtAdjustmentAmount', $('#txtAdjustmentAmount').val());
//     formData.append('txtRemarks', $('#txtRemarks').val());
//     formData.append('txtYourReference', $('#txtYourReference').val());

//     $.ajax({
//         url: '/prc/addGRNDraft',
//         method: 'post',
//         enctype: 'multipart/form-data',
//         data: formData,
//         processData: false,
//         contentType: false,
//         cache: false,
//         async: false,
//         timeout: 800000,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         beforeSend: function () {
//             $('#btnSave').prop('disabled', true);
//         }, success: function (response) {
//             $('#btnSave').prop('disabled', false);
//             var status = response.status
//             var primaryKey = response.primaryKey;
//             if (status) {
//                 showSuccessMessage("Successfully saved");
//                 resetForm();
//                 clearTableData();
//                 tableData.addRow();
//                 getServerTime();

//             } else {

//                 showErrorMessage("Something went wrong");
//             }

//         }, error: function (data) {
//             console.log(data.responseText)
//         }, complete: function () {

//         }
//     })
// }
//load item
function loadItems() {
    var list = [];
    $.ajax({
        url: '/prc/loadItems',
        type: 'get',
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

function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event,"item");
        $('#data-chooser-modalLabel').text('Items');
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
                $(row_childs[4]).val();
                $(row_childs[6]).val(response[0].package_unit);
                $(row_childs[5]).val(response[0].package_size);
                //$(row_childs[7]).val(response[0].average_cost_price);
                $(row_childs[7]).val(response[0].previouse_purchase_price);
                $(row_childs[11]).val(response[0].whole_sale_price);
                $(row_childs[12]).val(response[0].retial_price);
                $(row_childs[2]).focus();
                $(row_childs[2]).val('');
                $(row_childs[3]).val('');
                $(row_childs[8]).val('');
                $(row_childs[9]).val('');
                $(row_childs[10]).val('');
                $(row_childs[15]).val('');

                calculation();
                
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
    var headerDiscountAmount = parseFloat($('#txtDiscountAmount').val().replace(/,/g, ""));
   
    for (var i = 0; i < arr.length; i++) {
        var qty = parseFloat(arr[i][2].val().replace(/,/g, ""));
        var price = parseFloat(arr[i][7].val().replace(/,/g, ""));
        var discount_pres = parseFloat(arr[i][8].val().replace(/,/g, ""));

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

    if (isNaN(headerDiscountAmount)) {
        headerDiscountAmount = 0;
    }

    var totalDiscount = (headerDiscountAmount + tableDiscount);
    var netTotal = (grossTotal - totalDiscount + tax);
    console.log(totalDiscount);
    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}




function getEacchBonusclaim(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/prc/getEacchBonusclaim/' + id + '/' + status,
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
            console.log(status);
        }, success: function (PurchaseRequestData) {
            console.log(PurchaseRequestData);
            var res = PurchaseRequestData.data;
console.log(res[0].supplier_name);
var name = res[0].supplier_name;
            var str_addre = res[0].primary_address;
          //  getLocation(res[0].branch_id);
console.log($('#txtPurchaseORder').val(res[0].p_external_number));
            $('#LblexternalNumber').val(res[0].external_number);
            $('#LblexternalNumber').attr('data-id', res[0].goods_received_Id);
            $('#bonus_claim_date_time').val(res[0].bonus_claim_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(res[0].location_id);
            $('#txtSupplier').val(res[0].supplier_code);
            $('#txtSupplier').data('id',res[0].supplier_id);
            $('#lblSupplierName').val(name);
            $('#lblSupplierAddress').val(str_addre);   
            $('#txtPurchaseORder').val(res[0].p_external_number);
            $('#txtPurchaseORder').data('id', res[0].purchase_order_id);
            $('#txtSupplierInvoiceNumber').val(res[0].supppier_invoice_number);
            $('#cmbPaymentType').val(res[0].payment_mode_id);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#txtAdjustmentAmount').val(res[0].adjustment_amount);
            $('#txtRemarks').val(res[0].remarks);
            $('#bonus_claim_date_time').val(res[0].bonus_claim_date_time);
            $('#dtPaymentDueDate').val(res[0].payment_due_date);
            $('#txtYourReference').val(res[0].your_reference_number);
            $('#txtInvoiceAmount').val(parseFloat(res[0].invoice_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });

}

//get each product of GRN
function getEachproduct(id, status) {
    $.ajax({
        url: '/prc/getEachbonusclaimitem/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);

            var dataSource = [];
            $.each(data, function (index, value) {
                var qty = value.quantity;
                var price = value.price;
                var disAmount = value.discount_amount;
                var valueS = (qty * price) - disAmount;
                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "width:100px;margin-right:10px;padding:5px;background-color:#EBFFFF;", "event": "","valuefrom": "datachooser"  },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "width:370px;", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(qty).toFixed(0), "style": "width:80px;", "compulsory":true,"event":"calValueandCostPrice(this);checkPOqtyandFoc(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(value.free_quantity).toFixed(0), "style": "width:80px","event":"calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "width:60px", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "width:100px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_unit, "style": "width:80px", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs ", "value": parseFloat(value.price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "width:80px;", "event": "", "width": "*","event":"calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_percentage, "style": "width:80px;", "event": "", "width": "*","event":"calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_amount, "style": "width:80px;", "event": "discountAmount(this)", "width": "*","event":"calValueandCostPrice(this)","disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": parseFloat(valueS).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "width:80px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(value.whole_sale_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "width:80px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(value.retial_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "width:80px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": value.batch_number, "style": "width:150px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": value.expire_date, "style": "width:80px", "event": "enableDate(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(value.cost_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "width:80px", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 },
                    

                ]);


            });
            tableData.setDataSource(dataSource);
            calculation();

            /*  { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "itemEventListner(this)","valuefrom": "datachooser","thousand_seperator":false },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:370px;", "disabled": "disabled" },
            { "type": "number", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "compulsory":true },
            { "type": "number", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "class": "transaction-inputs ", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:150px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "enableDate(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 } */

        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
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
            $('#bonus_claim_date_time').val(formattedDate);
            $('#dtPaymentDueDate').val(formattedDate);
            

        },
        error: function (error) {
            console.log(error);
        },

    })
}



function newReferanceID(table,doc_number) {
    var brid=  $('#cmbBranch').val()
     referanceID = newID("../newReferenceNumber_BonusClaim_referenceId", table,doc_number,brid);
  //  $('#LblexternalNumber').val(referanceID);
}

//clear labels
function clearLabels(){
    $('#lblGrossTotal').text('0.00');
    $('#lblTotalDiscount').text('0.00');
    $('#lblTotaltax').text(parseFloat('0.00'));
    $('#lblNetTotal').text(parseFloat('0.00'));
}

function getDiscountAmount(qty, price, discount_percentage, discount_amount,foc_quantity,cost_price) {

    var quantity = parseFloat(qty.val().replace(/,/g, ""));
    var unit_price = parseFloat(price.val().replace(/,/g, ""));
    var percentage = parseFloat(discount_percentage.val().replace(/,/g, ""));
    var amount = parseFloat(discount_amount.val().replace(/,/g, ""));
    var foc = parseFloat(foc_quantity.val().replace(/,/g, ""));
console.log("quantity",quantity);
console.log("unit_price",unit_price);
console.log("percentage",percentage);
console.log("amount",amount);
console.log("foc",foc);

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
    var percentage_price = 0;
    var final_value = 0;


    if (discount_percentage.is(':focus')) {
        percentage_price = (quantity_price / 100.00) * percentage;
        discount_amount.val(percentage_price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    } else if (discount_amount.is(':focus')) {
        var prc = ((amount / quantity_price) * 100.0);
        percentage_price = (quantity_price / 100.00) * prc;
        discount_percentage.val(prc.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    }else{
        percentage_price = (quantity_price / 100.00) * percentage;
    }
    final_value = (quantity_price - percentage_price);
    var cost_value = (final_value/(quantity+foc));
    cost_price.val(cost_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());

console.log(final_value);
    
    return final_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString();
}


function dataChooserShowEventListener(event){
    // if(pickOrderStatus){
    //   DataChooser.dispose();
    //   pickOrderStatus = false;
    // }
      
  }

  //check PO qty and with GRN qty and FOC
  function checkPOqtyandFoc(event){
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var Bal_qty = $($(cell[19]).children()[0]).val();
    var Bal_foc = $($(cell[20]).children()[0]).val(); 
    var qty = $($(cell[2]).children()[0]).val();
    var foc = $($(cell[3]).children()[0]).val();
    
    if(isNaN(qty)){
        qty = 0;
    }

    if(isNaN(foc)){
        foc = 0;
    }

    if(isNaN(Bal_qty)){
        Bal_qty = 0;
    }

    if(isNaN(Bal_foc)){
        Bal_foc = 0;
    }

    if(parseInt(qty) > parseInt(Bal_qty)){
        showWarningMessage('Qty should be equal or less than PO qty');
        $($(cell[2]).children()[0]).val(Bal_qty);

    }

    if(parseInt(foc) > parseInt(Bal_foc)){
        showWarningMessage('FOC should be equal or less than PO FOC');
        $($(cell[3]).children()[0]).val(Bal_foc);
        
    }
    calValueandCostPrice(event)


  }

  function disablePurchasePriceandWholeSalePrice(table,type){
    var arr_ = table.getDataSourceObject();
    for(var i=0; i<arr_.length;i++){
        if(type == 'whole_sale'){
            var p_price = arr_[i][7];
            var w_price = arr_[i][11];
            $(p_price).prop('disabled',true);
            $(w_price).prop('disabled',false);
            $(p_price).val('');
            $(w_price).val('');
            
        }else{
            var p_price = arr_[i][7];
            var w_price = arr_[i][11];
            $(p_price).prop('disabled',false);
            $(w_price).prop('disabled',true);
            $(p_price).val('');
            $(w_price).val('');
        }
        
       
        
    }
   
}

//highlighting the table
function validate_table(ids){
    var itemUl = $('#item_ul');
    itemUl.empty();
    for(var i = 0;i < ids.length; i++){
        var listItem = '<li>' + ids[i] + '</li>';
        itemUl.append(listItem);
    }
    $('#warning_alert').addClass('show');
    $('html, body').animate({ scrollTop: 0 }, 'slow');
}

