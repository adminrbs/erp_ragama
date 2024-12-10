var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var stoscAdId = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
var foc_qty_threshold_from_pick_orders = undefined;
var hiddem_col_array = [];
$(document).ready(function () {
    $('#batchModelTitle').hide();
    $('#lblBalance').hide();
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();

    ItemList = loadItems();
    //loadPamentType();
    getServerTime();
    // suppliers = loadSupplierTochooser();

    // DataChooser.addCollection("Suppliers", ['Supplier Name', 'Supplier Code', '', '', ''], suppliers);
    DataChooser.addCollection("item", ['', '', '', '', ''], ItemList);

    $('#btnBack').hide();

    //back
    // $('#btnBack').on('click', function () {
    //     if (task == "approval") {
    //         var url = "/prc/goodReceiveReturnList";
    //         window.location.href = url;
    //     } else {
    //         var url = "/prc/goodReceiveReturnList";
    //         window.location.href = url;
    //     }


    // });

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    })


    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });

    getBranches();
    $('#cmbBranch').change();


    //setting datat to data chooser -supplier
    $('#txtSupplier').on('focus', function () {
        DataChooser.showChooser($(this), $(this), "Suppliers");
        $('#data-chooser-modalLabel').text('Suppliers');

    });

    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });
    hiddem_col_array = [];

    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        stoscAdId = param[0].split('=')[1].split('&')[0];
        // var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[2].split('&')[0];
        // task = param[0].split('=')[4].split('&')[0];

        if (action == 'edit') {
            $('#btnSave').text('Update');
            $('#btnSave').show();
            // $('#btnSaveDraft').hide();
            // $('#btnApprove').show();
            // $('#btnReject').show();
            $('#btnBack').show();
        }
        else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
            hiddem_col_array = [5, 9, 15, 14, 13, 12];
            disableComponents();

        }
        /*  getEachPurchasingOrder(reuqestID, status);
         getstock_adjustmentitem(reuqestID, status);
         getEachOther(reuqestID, status); */
        getstock_adjustment(stoscAdId, status);
        getstock_adjustmentitem(stoscAdId);

    }


    //item table

    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "", "valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:315px;", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs ", "value": "", "style": "width:55px;text-align:right;", "event": "calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);", "compulsory": true },
            // { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:55px;text-align:right;","event":"calValueandCostPrice(this);getItemID(this);itemsetOffontypeFunction(this);checkqtyandFoc_gr_rtn(this);" },
            // { "type": "text", "class": "transaction-inputs","value": "", "style": "width:60px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            // { "type": "text", "class": "transaction-inputs","value": "", "style": "width:100px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:40px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "thousand_seperator": true, "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:80px;", "event": "", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:50px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:55px;text-align:right;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:55px;text-align:right;", "event": "", "width": "*", },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation();", "width": 30 }
        ],
        "auto_focus": 0,

        "hidden_col": hiddem_col_array,

    });

    tableData.addRow();

    $('#tblData').on('input', 'input[type="text"]', function () {
        var cellIndex = $(this).closest('td').index();

        if (cellIndex === 11) {
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
        } else if (cellIndex === 2) {
            this.value = this.value.replace(/[^0-9.-]/g, '');

            // Remove any dots except the first one
            var dotCount = (this.value.match(/\./g) || []).length;
            if (dotCount > 1) {
                this.value = this.value.replace(/\.+$/, '');
            }

            // Allow only one minus sign at the beginning
            var firstChar = this.value.charAt(0);
            if (firstChar === '-') {
                this.value = '-' + this.value.slice(1).replace(/-/g, '');
            } else {
                // If '-' is not at the beginning, remove it
                this.value = this.value.replace(/-/g, '');
            }
            if (this.value < 0) {
                cellIndex === 5
            }
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
            if (arr[i][0].attr('data-id') == "undefined") {
                showWarningMessage("Please select a correct Item");
                arr[i][0].focus();
                return;
            } else if (arr[i][2].val() == "" || arr[i][2].val() == "0" || arr[i][2].val() == "undefined" || arr[i][2].val() == "null") {
                showWarningMessage("Quantity must be greter than 0");
                return;
            } else if (arr[i][2].val() > 0) {
                if (arr[i][4].val() == "" || arr[i][4].val() == "0" || arr[i][4].val() == "undefined" || arr[i][4].val() == "null") {
                    showWarningMessage("please Enter Cost Price");
                    return;
                } else if (arr[i][5].val() == "" || arr[i][5].val() == "0" || arr[i][5].val() == "undefined" || arr[i][5].val() == "null") {
                    showWarningMessage("please Enter whole sale price");
                    return;
                } else if (arr[i][6].val() == "" || arr[i][6].val() == "0" || arr[i][6].val() == "undefined" || arr[i][6].val() == "null") {
                    showWarningMessage("please Enter retail price");
                    return;
                }
                else {
                    collection.push(JSON.stringify({
                        "item_id": arr[i][0].attr('data-id'),
                        "item_name": arr[i][1].val(),
                        "qty": arr[i][2].val(),
                        "PackSize": arr[i][3].val(),
                        "cost_price": parseFloat(arr[i][4].val().replace(/,/g, '')),
                        "whole_sale_price": parseFloat(arr[i][5].val().replace(/,/g, '')),
                        "retial_price": parseFloat(arr[i][6].val().replace(/,/g, '')),
                        "value": parseFloat(arr[i][7].val().replace(/,/g, '')),
                        "batch_number": arr[i][8].val(),
                        "avl_qty": arr[i][9].val(),
                        "sett_off_qty": arr[i][10].val(),


                    }));
                }
            } else {
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "PackSize": arr[i][3].val(),
                    "cost_price": parseFloat(arr[i][4].val().replace(/,/g, '')),
                    "whole_sale_price": parseFloat(arr[i][5].val().replace(/,/g, '')),
                    "retial_price": parseFloat(arr[i][6].val().replace(/,/g, '')),
                    "value": parseFloat(arr[i][7].val().replace(/,/g, '')),
                    "batch_number": arr[i][8].val(),
                    "avl_qty": arr[i][9].val(),
                    "sett_off_qty": arr[i][10].val(),


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
                    if ($('#btnSave').text() == 'Save and Send') {
                        newReferanceID('stock_adjustments', 1500);
                        addstockadjustment(collection, stoscAdId);
                    } else if ($('#btnSave').text() == 'Update') {
                        updaStockadjustment(collection, stoscAdId);

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

});

function desablerow(event) {
    var e_qty = parseFloat($(event).val());
    if (e_qty > 0) {
        //$(event).removeAttr('disabled');
        var row = $($(event).parent()).parent();
        var cell = row.find('td');
         $($(cell[4]).children()[0]).removeAttr('disabled');
         $($(cell[5]).children()[0]).removeAttr('disabled');
         $($(cell[6]).children()[0]).removeAttr('disabled');

    }
    //var row_childs = event.getRowChilds();
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
        url: '/prc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $('#cmbLocation').append('<option value="">Select a location</option>');
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })

        },
    })
}



//add grn data
function addstockadjustment(collection) {
    
    
    var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));

    formData.append('collection', JSON.stringify(collection));
    formData.append('setOffArray', createSetoffCollection());
    formData.append('LblexternalNumber', referanceID);
    formData.append('stock_adjustment_date_time', $('#stock_adjustment_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtYourReference', $('#txtYourReference').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('lblNetTotal', total_amount);

    $.ajax({
        url: '/sc/addstockadjustment',
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
            //  $('#btnSave').prop('disabled', true);
        }, success: function (response) {
            console.log(response);
            $('#btnSave').prop('disabled', false);
            var status = response.status


            if (status) {
                showSuccessMessage("Successfully saved");
                resetForm();
                /* clearTableData();
                tableData.addRow(); */
                var url = "/sc/stock_adjustment_list";
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


function dataChooserEventListener(event, id, value) {
    var branch_id = $('#cmbBranch').val();
    var location_id_ = $('#cmbLocation').val();
    // if ($(event.inputFiled).attr('id') == 'txtSupplier') {
    //     loadSupplierOtherDetails(value);
    //     $('#lblSupplierName').text(id);
    // } else {
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
        url: '/prc/getItemInfotogrnReturn/' + branch_id + '/' + item_id + '/' + location_id_,
        type: 'get',
        success: function (response) {
            console.log(response);
           // var disabled = "";
            if(response[0].manage_batch == 1){
              //  alert();
                $(row_childs[8]).attr('disabled','disabled');
                $(row_childs[8]).val(response[0].Item_code);
            }

            $(row_childs[1]).val(response[0].item_Name);
            $(row_childs[3]).val(response[0].package_unit);
            $(row_childs[9]).val(response[0].Balance);
            // $(row_childs[5]).val(response[0].whole_sale_price);
            // $(row_childs[6]).val(response[0].retial_price);
            // $(row_childs[7]).val(response[0].previouse_purchase_price); 8
            
            $(row_childs[2]).focus();
        }
    })

    //}

}

//cal val and cost (change with qty, free qty)
function calValueandCostPrice(event) {

    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var qty = parseFloat($($(cell[2]).children()[0]));
    var price = parseFloat($($(cell[4]).children()[0]).val().replace(/,/g, ''));
    var discount_percentage = 0;
    var discount_amount = 0;
    var foc = 0;
    var cost_price = parseFloat($($(cell[4]).children()[0]).val());

   

    var e_qty = parseFloat($($(cell[2]).children()[0]).val());
   
    if (e_qty > 0) {
       
        var row = $($(event).parent()).parent();
        var cell = row.find('td');
         $($(cell[4]).children()[0]).removeAttr('disabled');
         $($(cell[5]).children()[0]).removeAttr('disabled');
         $($(cell[6]).children()[0]).removeAttr('disabled');

         var value = getDiscountAmount(e_qty, price, discount_percentage, discount_amount, foc, cost_price);
         $($(cell[7]).children()[0]).val(value);
 

    }

   
      




    //calculation();

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


        var discount_pres = parseFloat(arr[i][8].val().replace(/,/g, ""));



        // var price = calculationsetWholesalprice;
        // Check if the field values are not NaN or empty
        if (qty > 0) {
            var price = parseFloat(arr[i][5].val().replace(/,/g, ""));
        } else {
            var price = calculationsetWholesalprice;
        }
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

    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}



function getstock_adjustment(id) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sc/getstock_adjustment/' + id,
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

            var res = response.data;

            $('#LblexternalNumber').val(res.external_number);
            $('#stock_adjustment_date_time').val(res.date);
            $('#cmbBranch').val(res.branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(res.location_id);

            $('#txtRemarks').val(res.remarks);
            $('#txtYourReference').val(res.your_reference_number);


        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of GRN
function getstock_adjustmentitem(id) {
    $.ajax({
        url: '/sc/getstock_adjustmentitem/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);

            var dataSource = [];
            $.each(data, function (index, value) {
                var qty = Math.abs(parseFloat(value.quantity));
                var foc = Math.abs(parseFloat(value.free_quantity));
                var price = value.price;
                var disAmount = value.discount_amount;
                var valueS = (qty * price) - disAmount;
                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "DataChooser.showChooser(this,this)" },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "max-height:30px;margin-left:10px", "event": "", "style": "width:350px", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs math-round", "value": qty, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)" },
                    // { "type": "text", "class": "transaction-inputs math-abs math-round", "value": foc, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)" },
                    // { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    // { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.packsize, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.cost_price, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.whole_sale_price, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.retial_price, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
                    //{ "type": "text", "class": "transaction-inputs", "value": valueS.batch_number, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": value.batch_number, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", },
                    { "type": "button", "class": "btn btn-primary", "value": "Batch", "style": "max-height:30px;margin-left:10px;margin-right:20px;", "event": "setOffbybuton(this)", "width": 45 },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:20px;", "event": "removeRow(this)", "width": 30 }

                ]);





            });
            tableData.setDataSource(dataSource);
            calculation();
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}


//update GRN
function updaStockadjustment(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('stock_adjustment_date_time', $('#stock_adjustment_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('txtSupplier', $('#txtSupplier').data('id'));
    formData.append('lblSupplierName', $('#lblSupplierName').text());
    formData.append('lblSupplierAddress', $('#lblSupplierAddress').val());
    formData.append('txtPurchaseORder', $('#txtPurchaseORder').val());
    formData.append('txtSupplierInvoiceNumber', $('#txtSupplierInvoiceNumber').val());
    formData.append('dtPaymentDueDate', $('#dtPaymentDueDate').val());
    formData.append('cmbPaymentType', $('#cmbPaymentType').val());
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtAdjustmentAmount', $('#txtAdjustmentAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());

    $.ajax({
        url: '/prc/updaStockadjustment/' + id,
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
            var status = response.status
            var primaryKey = response.primaryKey;
            if (status) {
                showSuccessMessage("Successfully saved");
                /*  resetForm();
                clearTableData();
                tableData.addRow(); */
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
function getServerTime() {
    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#stock_adjustment_date_time').val(formattedDate);
        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_stockadjustment", table, doc_number);
    //  $('#LblexternalNumber').val(referanceID);
}

//calculations
function getDiscountAmount(qty, price, discount_percentage, discount_amount, foc_quantity, cost_price) {

    

    var quantity = parseFloat(qty);
    var unit_price = parseFloat(price);
    var percentage = parseFloat(discount_percentage);
    var amount = parseFloat(discount_amount);
    var foc = foc_quantity
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


    var quantity_price = quantity * unit_price;
    var percentage_price = (quantity_price / 100.00) * percentage;

    // if (discount_percentage.is(':focus')) {
    //     discount_amount.val(percentage_price);
    // } else if (discount_amount.is(':focus')) {
    //     var prc = (amount / quantity_price) * 100.0;
    //     discount_percentage.val(prc.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    // }

    var final_value = (quantity_price - percentage_price);
    var cost_value = (final_value / (quantity + foc));
   // cost_price.val(cost_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());

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


function dataChooserShowEventListener(event) {
    if (pickOrderStatus) {
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
function checkqtyandFoc_gr_rtn(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var foc = $($(cell[3]).children()[0]).val();



    if (parseInt(foc) > parseInt(global_foc_qty_)) {
        showWarningMessage('FOC should be equal or less than system foc');
        $($(cell[3]).children()[0]).val(global_foc_qty_);

    }
    calValueandCostPrice(event)


}