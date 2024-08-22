var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var customers = []
var sales_order_Id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
$(document).ready(function () {
  
    getDeliveryTypes();
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();
    ItemList = loadItems();
    loadPamentTerm();
    loademployees();
    getServerTime();
    getPaymentTerm();

    //back button
    $('#btnBack').hide();
    $('#btnBack').on('click',function(){
        
            var url = "/sd/getSalesOrderList"; 
            window.location.href = url;
    });


    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);

    })
    getBranches();
    $('#cmbBranch').change();


    //gross total
    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });
    customers = loadCustomerTOchooser();

    DataChooser.addCollection("Customer",['Customer Name', 'Customer Code', 'Town', 'Route',''], customers);
    DataChooser.addCollection("item",['', '', '', '',''], ItemList);


    //loading locations
    /*     $('#cmbBranch').change(function () {
            var id = $(this).val();
            getLocation(id);
        })
     */
    $('#txtCustomerID').on('focus', function () {
        /* DataChooser.emptyTable();
        DataChooser.setDataSourse(['Customer Name','Customer Code','Town','Route'],customers);
        DataChooser.showChooser($(this));
        $('#data-chooser-modalLabel').text('Customers');

        // Create an "Up Arrow" key press event
        var upArrowEvent = $.Event('keydown', { keyCode: 38 });
        $(this).trigger(upArrowEvent); */

        DataChooser.showChooser($(this),$(this),"Customer");
        $('#data-chooser-modalLabel').text('Customers');

    });

    

    $('select').change(function () {

        validateSelectTag(this);

    });

var hidden_array = [5,9];
    //from list
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        sales_order_Id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
         var order_type_status = checkSalesOrderType(sales_order_Id);
         var is_order_status = order_check_status(sales_order_Id);
         
         hidden_array = [5,9];
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#chk').hide();
            $('#btnBack').show();
            
        }
        else if (action == 'edit' && status == 'Original') {
            if(parseInt(order_type_status) != 0){
              
                showWarningMessage('Unauthorized Access');
                var url = "/sd/getSalesOrderList"; 
                window.location.href = url;
                return;
             }else if(parseInt(is_order_status) != 1){
                showWarningMessage('Unauthorized Access');
                var url = "/sd/getSalesOrderList"; 
                window.location.href = url;
                return;
             }else{
             
                $('#btnSave').text('Update');
                $('#btnSaveDraft').hide();
                $('#btnApprove').hide();
                $('#btnReject').hide();
                $('#btnBack').show();
             }
          

        } else if (action == 'edit' && status == 'Draft') {
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
          /*   $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();

        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
            disableComponents();

        }

        getEachSalesOrder(sales_order_Id, status);
        getEachproduct(sales_order_Id, status);
    }


    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "","valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;margin-right:10px;", "event": "clickx(1)", "style": "width:370px", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);foc_calculation_threshold(this)" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this)","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*","thousand_seperator":true  },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
            /*   { "type": "text", "value": "", "style": "max-height:30px;text-align:right;width:150px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" }, */
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 }
        ],
        "auto_focus": 0,
        "hidden_col": hidden_array


    });

    tableData.addRow();

    $('#tblData').on('input', 'input[type="text"]', function () {
        // Remove any consecutive dots
        this.value = this.value.replace(/\.+/g, '.');
    
        // Remove any dots except the first one
        if ((this.value.match(/\./g) || []).length > 1) {
            var parts = this.value.split('.');
            this.value = parts.shift() + '.' + parts.join('').replace(/\./g, '');
        }
    
        // Allow only numbers and a single dot
        this.value = this.value.replace(/[^0-9.]/g, '');
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
                showWarningMessage("Quantity must be gretaer than 0");
                return;

            }else if(arr[i][7].val() == "" || arr[i][7].val() == "0" || arr[i][7].val() == "undefined" || arr[i][7].val() == "null" || parseFloat(arr[i][7].val() ) == 0){
                showWarningMessage("Price must be gretaer than 0.00");
                return;
            }else{
            collection.push(JSON.stringify({
                "item_id": arr[i][0].attr('data-id'),
                "item_name": arr[i][1].val(),
                "qty": parseFloat(arr[i][2].val().replace(/,/g, '')),
                "PackUnit": arr[i][6].val(),
                "PackSize": arr[i][5].val(),
                "free_quantity":parseFloat(arr[i][3].val().replace(/,/g, '')), 
                "uom":arr[i][4].val(), 
                "price":parseFloat(arr[i][7].val().replace(/,/g, '')),
                "discount_percentage": arr[i][8].val(),
                "discount_amount":parseFloat(arr[i][9].val().replace(/,/g, ''))


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
                        newReferanceID('sales_orders', '200');
                        addSalesOrder(collection, sales_order_Id);
                    } else if ($('#btnSave').text() == 'Update') {
                        updateOrder(collection, sales_order_Id);

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
            }));


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
                        newReferanceID('sales_order_drafts', '200');
                        addSalesOderDraft(collection);

                    } else if ($('#btnSaveDraft').text() == 'Update Draft') {


                        updateSalesOrderDraft(collection, sales_order_Id);

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
                    approveSalesOrder(sales_order_Id);

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
                    rejectSalesOrder(Invoice_id);
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



//add Sales order
function addSalesOrder(collection, id) {
    if(parseInt(collection.length) <= 0){
        showWarningMessage('Unable to save without an item');
        return
    }
    var return_result = _validation($('#txtCustomerID'),$('#lblCustomerName'));
    if(return_result){
        showWarningMessage("Please fill all required fields");
        return;
    }else if($('#txtYourReference').val().length < 1){
        showWarningMessage("Please fill all required fields");
        $('#txtYourReference').addClass('is-invalid');
    }else{

        var brnch = $('#cmbBranch').val();
        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', referanceID);
        formData.append('order_date_time', $('#order_date_time').val());
        /*  formData.append('cmbOrderType', $('#cmbOrderType').val()); */
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('cmbEmp', $('#cmbEmp').val());
        formData.append('lblCustomerName', $('#lblCustomerName').val());
        formData.append('customerID', $('#lblCustomerName').data('id'));
        formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
        formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
        formData.append('grandTotal', parseFloat($('#lblNetTotal').text().replace(/,/g, '')));
        formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
        formData.append('cmbDeliverType', $('#cmbDeliverType').val());
        formData.append('delivery_date_time', $('#delivery_date_time').val());
        formData.append('txtYourReference',$('#txtYourReference').val());
        formData.append('cmbBranch',brnch);
        console.log(formData);

        $.ajax({
            url: '/sd/addSalesOrder/' + id,
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
                    salesorderReport(primaryKey);
                    showSuccessMessage("Successfully saved");
                    resetForm();
                  
                    url = "/sd/getSalesOrderList";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 3000); 
                    
    
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

//add SO draft
function addSalesOderDraft(collection) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', referanceID);
    formData.append('order_date_time', $('#order_date_time').val());
    /* formData.append('cmbOrderType', $('#cmbOrderType').val()); */
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
    formData.append('grandTotal', parseFloat($('#lblNetTotal').text().replace(/,/g, '')));
    formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
    formData.append('cmbDeliverType', $('#cmbDeliverType').val());
    formData.append('delivery_date_time', $('#delivery_date_time').val());
    formData.append('txtYourReference',$('#txtYourReference').val());

    $.ajax({
        url: '/sd/addSalesOderDraft',
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
                resetForm();
                clearTableData();
                tableData.addRow();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}


//item event listner

/* function itemEventListner(event) {

    console.log(ItemList);
    DataChooser.setDataSourse(['','','',''],ItemList);
    DataChooser.showChooser(event, event);
    $('#data-chooser-modalLabel').text('Items');
} */

function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event,"item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

function dataChooserShowEventListener(event){

}

//load item
/* function loadItems() {
    $.ajax({
        url: '/sd/loadItems',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var itemData = response.data;
                DataChooser.setDataSourse(itemData);
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
} */

function loadItems() {
    var list = [];
    $.ajax({
        url: '/sd/loadItems',
        type: 'get',
        async: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;

            }
        },
        error: function (error) {
            console.log(error);
        },

    });
    return list;
}
//load custoners
function loadCustomerTOchooser() {

    var data = [];
    $.ajax({
        url: '/sd/loadCustomerTOchooser',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response) {
                var customerData = response.data;
                console.log(customerData);
                /*  DataChooser.setDataSourse(supplierData); */
                data = customerData;
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return data;
}
//load supplier other details
function loadCustomerOtherDetails(id) {

    $.ajax({
        url: '/sd/loadCustomerOtherDetails/' + id,
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            console.log(data)
            var txt = data.data;
            /*  console.log(txt); */
            $('#lblCustomerAddress').val(txt[0].primary_address);
            var cusID = txt[0].customer_id;
            var payment_term_id_ = txt[0].payment_term_id;
            $('#lblCustomerName').attr('data-id', cusID);
            $('#cmbPaymentTerm').val(payment_term_id_);
            $('#cmbDeliverType').focus();


        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load payment term
function loadPamentTerm() {
    $.ajax({
        url: '/sd/loadPamentTerm',
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentTerm').append('<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })

}

function loademployees() {
    $.ajax({
        url: '/sd/loademployees',
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })
}




function dataChooserEventListener(event, id, value) {
    if ($(event.inputFiled).attr('id') == 'txtCustomerID') {
        loadCustomerOtherDetails(value);
        $('#lblCustomerName').val(id);

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
            url: '/sd/getItemInfo_sales_order/' + item_id,
            type: 'get',
            success: function (response) {
                console.log(response);
                var expireDateManage = response[0].manage_expire_date;
                if (expireDateManage == 1) {
                    $(row_childs[14]).removeAttr('disabled');
                }

               

                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[4]).val(response[0].unit_of_measure);
                $(row_childs[6]).val(response[0].package_unit);
                $(row_childs[5]).val(response[0].package_size);
                $(row_childs[11]).val(response[0].average_cost_price);
                $(row_childs[7]).val(response[0].item_price);
                $(row_childs[12]).val(response[0].retial_price);
                $(row_childs[2]).focus();

                $(row_childs[2]).val('');
                $(row_childs[3]).val('');
              
               
                calculation();
            }
        })

    }

}





function calValueandCostPrice(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');

    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[7]).children()[0]);
    var discount_percentage = $($(cell[8]).children()[0]);
    var discount_amount = $($(cell[9]).children()[0]);

    var AMOUNT = getDiscountAmount(qty, price, discount_percentage, discount_amount);
   /*  $($(cell[10]).children()[0]).val(AMOUNT); */



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

    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}



function getEachSalesOrder(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sd/getEachSalesOrder/' + id + '/' + status,
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

        }, success: function (salesInv) {
            console.log(salesInv);
            var res = salesInv.data;
           

            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#order_date_time').val(res[0].order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerName').val(res[0].customer_name);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbPaymentTerm').val(res[0].payment_term_id);
            $('#cmbDeliverType').val(res[0].deliver_type_id);
            $('#txtRemarks').val(res[0].remarks);
            $('#delivery_date_time').val(res[0].expected_date_time);
            $('#txtDeliveryInst').val(res[0].delivery_instruction);
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            $('#txtYourReference').val(res[0].your_reference_number);

            /* var cusID = txt[0].customer_id;
            $('#lblCustomerName').attr('data-id',cusID); */

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of SI
function getEachproduct(id, status) {
    $.ajax({
        url: '/sd/getEachproductofSalesOder/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);


            var dataSource = [];
            $.each(data, function (index, value) {
                console.log(value.Item_code);

                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "","valuefrom": "datachooser" },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "max-height:30px;margin-right:10px;width:370px;", "event": "clickx(1)", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;","event": "calValueandCostPrice(this);foc_calculation_threshold(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.free_quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;","event": "calValueandCostPrice(this)","disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_unit, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.price, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "width": "*","event": "calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;","width": "*","event": "calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 }

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


//update Sales Order
function updateOrder(collection, id) {
    var brnch = $('#cmbBranch').val();
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', referanceID);
    formData.append('order_date_time', $('#order_date_time').val());
    /*  formData.append('cmbOrderType', $('#cmbOrderType').val()); */
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
    formData.append('grandTotal', parseFloat($('#lblNetTotal').text().replace(/,/g, '')));
    formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
    formData.append('cmbDeliverType', $('#cmbDeliverType').val());
    formData.append('delivery_date_time', $('#delivery_date_time').val());
    formData.append('txtYourReference',$('#txtYourReference').val());
    formData.append('cmbBranch',brnch);

    $.ajax({
        url: '/sd/updateSalesOrder/' + id,
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
            console.log(response);
            $('#btnSave').prop('disabled', false);
            var status = response.status
            var primaryKey = response.primaryKey;
            var msg = response.message;
            if(msg == "invoiced"){
                showWarningMessage('Unable to update');
                return;
            }
            if (status) {
                showSuccessMessage("Successfully saved");
                resetForm();
               /*  clearTableData();
                tableData.addRow();
 */
                url = "/sd/getSalesOrderList";
               
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


//update sales order draft
function updateSalesOrderDraft(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('order_date_time', $('#order_date_time').val());
    /*  formData.append('cmbOrderType', $('#cmbOrderType').val()); */
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtDeliveryInst', $('#txtDeliveryInst').val());
    formData.append('grandTotal', $('#lblTotal').text());
    formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
    formData.append('cmbDeliverType', $('#cmbDeliverType').val());
    formData.append('delivery_date_time', $('#delivery_date_time').val());
    formData.append('txtYourReference',$('#txtYourReference').val());


    $.ajax({
        url: '/sd/updateSalesOrderDraft/' + id,
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
                resetForm();
                clearTableData();
                tableData.addRow();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}


//approve
function approveSalesOrder(id) {
    $.ajax({
        url: '/sd/approveSalesOrder/' + id,
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
            var status = response.status
            console.log(status);
            if (status) {
                showSuccessMessage("Record approved");

                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
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

//reject
function rejectSalesOrder(id) {
    $.ajax({
        url: '/sd/rejectSalesOrder/' + id,
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
            var status = response.status
            console.log(status);
            if (status) {
                showSuccessMessage("Request rejected");

                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
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
    $('#lblGrossTotal').text('0.00');
    $('#lblNetTotal').text('0.00');
    $('#lblTotalDiscount').text('0.00');
    $('#lblTotaltax').text('0.00');


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
        url: '/sd/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#order_date_time').val(formattedDate);
            $('#delivery_date_time').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}




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

function getDeliveryTypes() {

    $.ajax({
        url: '/sd/getDeliveryTypes',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbDeliverType').append('<option value="' + value.delivery_type_id + '">' + value.delivery_type_name + '</option>');

            })

        },
    })
}

function getPaymentTerm() {
    $.ajax({
        url: '/sd/getPaymentTerm',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentTerm').append('<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>');

            })

        },
    })

}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_SalesOrder", table, doc_number);
    // $('#LblexternalNumber').val(referanceID);
}

function getDiscountAmount(qty, price, discount_percentage, discount_amount) {
    var quantity = parseFloat(qty.val().replace(/,/g, ""));
    var unit_price = parseFloat(price.val().replace(/,/g, ""));
    var percentage = parseFloat(discount_percentage.val().replace(/,/g, ""));
    var amount = parseFloat(discount_amount.val().replace(/,/g, ""));

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


    return final_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString();
}


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


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000); 
}


//check order type befire to edit
function checkSalesOrderType(val){
    
    var order_status = 0;
    $.ajax({
        url: '/sd/checkSalesOrderType/'+val,
        type: 'get',
        async: false,
        success: function (data) {
           order_status = data.data;
           console.log(data);
          

        },
    })

    return order_status;
}


//check order type before to edit
function order_check_status(val){
    
    var is_order_status = 0;
    $.ajax({
        url: '/sd/order_check_status/'+val,
        type: 'get',
        async: false,
        success: function (data) {
            is_order_status = data.data;
           console.log(data);
          

        },
    })

    return is_order_status;
}

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
    var date = $('#order_date_time').val();
    var cu_id = $('#lblCustomerName').data('id');

    $.ajax({
        url: '/sd/getItem_foc_threshold_ForInvoice/'+ cu_id + "/" + item_id + "/" + formatted_entered_qty + "/" + date,
        type: 'get',
        success: function (response) {
            // console.log(response);
            $.each(response, function (index, value) {
                /*  var qty = parseFloat(value.quantity);
                 var foc = parseFloat(value.free_offer_quantity);
                 var calculated_foc = (foc / qty) * formatted_entered_qty;
                 var final_foc = parseInt(calculated_foc);
                 console.log(final_foc);
                 if (isNaN(final_foc)) {
                     final_foc = 0;
                 } */
                //  $($(item_cell[3]).children()[0]).val(value.Offerd_quantity);
                $($(item_row.children()[3]).children()[0]).val(value.Offerd_quantity)

            })

        }
    })

}