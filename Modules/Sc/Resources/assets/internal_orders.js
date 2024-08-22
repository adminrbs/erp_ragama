var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;

var sales_order_Id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
$(document).ready(function () {
    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
   
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();
    ItemList = loadItems();
  
    getServerTime();
   

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
    

   
    DataChooser.addCollection("item",['', '', '', '',''], ItemList);


    //loading locations
    /*     $('#cmbBranch').change(function () {
            var id = $(this).val();
            getLocation(id);
        })
     */
    $('#txtCustomerID').on('focus', function () {
       

        DataChooser.showChooser($(this),$(this),"Customer");
        $('#data-chooser-modalLabel').text('Customers');

    });

    

    $('select').change(function () {

        validateSelectTag(this);

    });


    //from list
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        sales_order_Id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
         
        
         
        
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
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "","disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*","thousand_seperator":true,"disabled": "disabled"  },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 }
        ],
        "auto_focus": 0,
        "hidden_col": []


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
            }else if(arr[i][7].val() == "" || arr[i][7].val() == "0" || arr[i][7].val() == "undefined" || arr[i][7].val() == "null"){
                showWarningMessage("Quantity must be gretaer than 0");
                return;

            }else{
            collection.push(JSON.stringify({
                "item_id": arr[i][0].attr('data-id'),
                "item_name": arr[i][1].val(),
                "qty": parseFloat(arr[i][7].val().replace(/,/g, '')),
                "PackSize": arr[i][2].val(),
                "from_branch_stock": arr[i][3].val(),
                "to_branch_stock": arr[i][4].val(),
                "avg_sales": arr[i][5].val(),
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
                   
                        newReferanceID('internal_orders', '2400');
                        addInternalOrders(collection);
                    
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
function addInternalOrders(collection) {
    if(parseInt(collection.length) <= 0){
        showWarningMessage('Unable to save without an item');
        return
    }
   else{

        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', referanceID);
        formData.append('order_date_time', $('#order_date_time').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('ToBranch', $('#ToBranch').val());
        formData.append('from_date', $('#from_date').val());
        formData.append('to_date', $('#to_date').val());
      
        $.ajax({
            url: '/sc/addInternalOrders',
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
              
                if (status) {
                  
                    showSuccessMessage("Successfully saved");
                  //  resetForm();
                  
                  /*   url = "/sd/getSalesOrderList";
                    setTimeout(function() {
                        window.location.href = url;
                    }, 3000);  */
                    
    
                } else {
    
                    showWarningMessage("Unable to save");
                }

               
    
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
    
            }
        })
        getServerTime();
        
    }

   
}





function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event,"item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

function dataChooserShowEventListener(event){

}


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

        var from_branch_id = $('#cmbBranch').val();
        var to_branch = $('#ToBranch').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        
        $.ajax({
            url: '/sc/getItemInfo_internal_order/' + item_id +'/'+from_branch_id+'/'+to_branch,
            type: 'get',
            data:{
                from_date:from_date,
                to_date:to_date
            },
            success: function (response) {
                
                $(row_childs[1]).val(response[0].item_Name);
                $(row_childs[2]).val(response[0].package_unit);
                $(row_childs[3]).val(response[0].from_balance);
                $(row_childs[4]).val(response[0].to_balance);
                $(row_childs[5]).val(Math.abs(response[0].avg_sales));
                $(row_childs[6]).val(response[0].reorder_level);
                
            }
           
        });
        $('#from_date').prop('disabled',true);
        $('#to_date').prop('disabled',true);

    }

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
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "DataChooser.showChooser(this,this);itemEventListner(this);" },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "max-height:30px;margin-right:10px;width:370px;", "event": "clickx(1)", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;","event": "" },
                    { "type": "text", "class": "transaction-inputs math-abs math-round", "value": value.free_quantity, "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;","event": "","disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_unit, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.price, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "width": "*","event": "" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;","width": "*","event": "" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", },
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
    referanceID = newID("../newReferenceNumber_InternalOrders", table, doc_number);
    // $('#LblexternalNumber').val(referanceID);
}




function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#ToBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
            })

        },
    })
}


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000); 
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