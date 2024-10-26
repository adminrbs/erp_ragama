var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var customers = []
var Invoice_id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
var foc_qty_threshold_from_pick_orders_sales_return;
$(document).ready(function () {

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    
    getServerTime();
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();

   
    //ItemList = loadItems();
    /*  loadPamentTerm(); */
    //get sales rep code
    $('#cmbEmp').on('change', function () {

        var rep_id = $(this).val();
        get_rep_code(rep_id)
    });

    loademployees();

    $('#cmbEmp').change();


    loadReason();

    //load books
   // loadBookNumber();

    //back
    $('#btnBack').hide();

    $('#btnBack').on('click', function () {

        var url = "/sd/salesReturnList";
        window.location.href = url;


    });

    //close warning 
    $('#warningClose').on('click', function () {


        $('#warning_alert').removeClass('show').addClass('hide');
    });



    $('.modal').each(function () {
        $(this).find('.select2').select2({
            dropdownParent: $(this)
        });
    });

    $('#SalesReturnInvoiceModal').on('show.bs.modal', function () {
        branch_id = $('#cmbBranch').val();
        getCusrrentMonthDates();
        loademployeesInModel();
        loadCustomerToCMB();
        getInvoicesForReturn(branch_id);
    });


    //getting branch code
    $('#cmbBranch').on('change', function () {
        var branch_id_ = $(this).val();
        get_branch_code(branch_id_);
        checkReturnLocation(branch_id_);
    });


    customers = loadCustomerTOchooser();

    DataChooser.addCollection("Customer", ['Customer Name', 'Customer Code', 'Town', 'Route', ''], customers);
    //DataChooser.addCollection("item", ['', '', '', '', ''], ItemList);

    loademployeesInModel();
    loadCustomerToCMB();
    getCusrrentMonthDates();

    //loading locations
    /* $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    }) */

    getBranches();
    $('#cmbBranch').change();
    //gross totoal
    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });

    $('#txtCustomerID').on('click', function () {
        DataChooser.commitData();
        DataChooser.showChooser($(this), $(this), "Customer");
        $('#data-chooser-modalLabel').text('Customers');

    });

    $('#cmbSalesAnalysist').on('change',function(){

        ItemList = loadItems($(this).val());
        DataChooser.addCollection("item",['', '', '', '',''], ItemList);
    });

    loadSupplyGroupsAsSalesAnalyst()

    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });


    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        Invoice_id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            /*  $('#chkPrintReport').hide(); */
            $('#btnBack').show();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();

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

        getEachSalesReturn(Invoice_id, 'Original');
        getEachproduct(Invoice_id, 'Original');
    }


    //item table
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;margin-right:10px;", "event": "", "style": "width:370px", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this);foc_calculation_threshold(this)", "compulsory": true },
            { "type": "text", "class": "transaction-inputs math-abs math-round", "value": "", "style": "max-height:30px;width:80px;text-align:right;margin-right:10px;", "event": "calValueandCostPrice(this)" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:50px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:100px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "", "width": "*", },

            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "setPrice(this);calValueandCostPrice(this);", "width": "*", "thousand_seperator": true },

            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:80px;margin-right:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;width:150px;margin-right:10px;", "event": "", "width": "*", "disabled": "disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:right;", "event": "removeRow(this);calculation()", "width": 30 },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;margin-right:10px;", "event": "", "width": "10", "disabled": "disabled" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;text-align:right;margin-right:10px;", "event": "", "width": "10", "disabled": "disabled" },
        ],
        "auto_focus": 0,
        "hidden_col": [5, 10, 13, 14]


    });

    tableData.addRow();

    /*   $('#tblData').on('input', 'input[type="text"]', function () {
          var cellIndex = $(this).closest('td').index();
              // Allow only numbers and dots for other cell indexes
              this.value = this.value.replace(/[^0-9.]/g, '');
              var dotCount = (this.value.match(/\./g) || []).length;
              if (dotCount > 1) {
                  this.value = this.value.replace(/\.+$/, '');
              }
          
      }); */

    $('#tblData').on('input', 'input[type="text"]', function () {
        // Remove any consecutive dots
        var cellIndex = $(this).closest('td').index();
        this.value = this.value.replace(/\.+/g, '.');


        if (cellIndex == 8 || cellIndex == 7) {

            if ((this.value.match(/\./g) || []).length > 1) {
                // Split the value into parts
                var parts = this.value.split('.');

                // Reconstruct the value with only one dot
                this.value = parts.shift() + '.' + parts.join('').replace(/\./g, '');
            }
        } else {
            // Allow only numbers
            this.value = this.value.replace(/[^0-9]/g, '');
        }




    });


    $('#btnSave').on('click', function () {
        //item array
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

            } else if (arr[i][7].val() == "" || arr[i][7].val() == "0" || arr[i][7].val() == "undefined" || arr[i][7].val() == "null" || parseFloat(arr[i][7].val()) == 0) {
                showWarningMessage("Price must be greter than 0.00");
                return;
            } /* else if (arr[i][8].val() == "" || arr[i][8].val() == "0" || arr[i][8].val() == "undefined" || arr[i][8].val() == "null" || parseFloat(arr[i][8].val()) == 0) {
                showWarningMessage("Retail Price must be greter than 0.00");
                return;
            } */ else {
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "uom": arr[i][4].val(),
                    "PackUnit": arr[i][6].val(),
                    "PackSize": arr[i][5].val(),
                    "free_quantity": arr[i][3].val(),
                    "price": arr[i][7].val().replace(/,/g, ''),
                    "retail_price": arr[i][8].val().replace(/,/g, ''),
                    "discount_percentage": arr[i][9].val(),
                    "discount_amount": parseFloat(arr[i][10].val().replace(/,/g, '')),
                    "cost_price": parseFloat(arr[i][14].val().replace(/,/g, ''))


                }));


            }

            //validating set off array
            var setoff_array = [];
            var text_box_values = 0;
            var row_count = $('#set_off_table tr').length;
            var _net_total_val = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
            var total_balance = 0;
            var save_return = false;
            var total_balance = 0;
           // if (row_count > 1) {
                $('#set_off_table tr:not(:first)').each(function (index, row) {

                    var row_balance = parseFloat($(row).find('td:eq(3)').text().replace(/,/g, ''));

                    var set_off_amount_ = $(row).find('td:eq(5) input[type="number"]').val();

                    var set_off_amount = parseFloat(set_off_amount_.replace(/,/g, ''));

                    if (isNaN(set_off_amount)) {
                        set_off_amount = 0;
                    }

                    if (isNaN(row_balance)) {
                        row_balance = 0;
                    }

                    if (isNaN(set_off_amount)) {
                        set_off_amount = 0;
                    }
                    var actual_balance = row_balance - set_off_amount;
                    total_balance = total_balance + actual_balance;
                    var checkbox = $(row).find('td:eq(4) input[type="checkbox"]');

                    if (checkbox.prop('checked')) {
                        var text_val = $(row).find('td:eq(5) input[type="number"]').val();
                        var text_formatted_val = parseFloat(text_val.replace(/,/g, ''));
                        var textBox_id = $(row).find('td:eq(5) input[type="number"]').attr('id');
                        var id_parts = textBox_id.split('_');
                        var textBox_id_ = id_parts[1];
                        var id_and_value = textBox_id_ + '|' + text_formatted_val;
                        text_box_values = text_box_values + text_formatted_val;
                        setoff_array.push(id_and_value);
                    }
                });

                if (parseFloat(text_box_values) != parseFloat(_net_total_val)) {

                    var set_offed_balance = parseFloat(_net_total_val) - parseFloat(text_box_values)
                    if (parseFloat(set_offed_balance) <= parseFloat(total_balance)) {
                        save_return = false;

                    } else {
                        save_return = true;
                    }



                } else {
                    save_return = true;
                }

           // }



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
                    if ($('#btnSave').text() == 'Save and Send') {
                        /* if (save_return) { */
                            newReferanceID('sales_returns', '220')
                            addSalesReturn(collection, Invoice_id, setoff_array, text_box_values);
                        /* } else {
                            showWarningMessage('Set off amount mismatched');
                        } */

                    } else if ($('#btnSave').text() == 'Update') {
                        updateReturn(collection, Invoice_id);

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
            if (arr[i][0].attr('data-id') == "undefined") {
                showWarningMessage("Please select a correct Item");
                arr[i][0].focus();
                return;
            } else {
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
                        newReferanceID('sales_invoice_drafts', '220');
                        addSalesReturnDraft(collection);

                    } else if ($('#btnSaveDraft').text() == 'Update Draft') {


                        updateSalesReturnDraft(collection, Invoice_id);

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
                    approveRequestSalesInvReturn(Invoice_id);

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
                    rejectRequestSalesInvReturn(Invoice_id);
                } else {

                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');


    });



    $('#txtInvoiceID').keydown(function (e) {
        // Check if the pressed key is Enter (key code 13)
        if (e.which === 13) {

            var external_number = $('#txtInvoiceID').val();
            if (!external_number) {
                $('#txtInvoiceID').addClass('is-invalid');
                showWarningMessage('Please enter a valid Invoice Number');
                return;
            }
            path = "btn"
            getHeaderDetailsForInvoiceReturn(external_number, path);

        }
    });





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

        },
    })
}



//add SI
function addSalesReturn(collection, id, setoff_array, text_box_values) {
    if (parseInt(collection.length) <= 0) {
        showWarningMessage('Unable to save without an item');
        return
    }
    
    if (!$('#cmbLocation').val()) {
        showWarningMessage('Location should be set');
        return;
    }
    var return_result = _validation($('#txtCustomerID'), $('#lblCustomerName'));
    if (return_result) {
        showWarningMessage("Please fill all required fields");
        return;
    } else if ($('#txtRemarks').val().length < 1) {
        showWarningMessage("Please fill remarks");
        $('#txtRemarks').addClass('is-invalid')
        return;
    }else if($('#txtyourreferencenumber').val().length < 1){
        showWarningMessage("Please fill your reference number");
        $('#txtyourreferencenumber').addClass('is-invalid')
        
    } else {
        var total_amount = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
        formData.append('text_box_values', text_box_values);
        formData.append('collection', JSON.stringify(collection));
        formData.append('setoff_array', JSON.stringify(setoff_array));
        formData.append('LblexternalNumber', referanceID);
        formData.append('invoice_date_time', $('#invoice_date_time').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbLocation', $('#cmbLocation').val());
        formData.append('cmbEmp', $('#cmbEmp').val());
        formData.append('lblCustomerName', $('#lblCustomerName').val());
        formData.append('customerID', $('#lblCustomerName').data('id'));
        /*  formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val()); */
        formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
        formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
        formData.append('txtRemarks', $('#txtRemarks').val());
        /* formData.append('txtDeliveryInst', $('#txtDeliveryInst').val()); */
        formData.append('grandTotal', total_amount);
        formData.append('sales_invoice_id', $('#txtInvoiceID').data('id'));
        formData.append('cmbReason', $('#cmbReason').val());
        formData.append('code', $('#invoice_date_time').data('id'));
        formData.append('branch_code', $('#cmbBranch').data('id'));
       // formData.append('book_number', $('#cmbBookNumber').val());
        formData.append('page_number', $('#txtPageNumber').val());
        formData.append('txtyourreferencenumber', $('#txtyourreferencenumber').val());
        formData.append('sales_analyst_id',$('#cmbSalesAnalysist').val());

        $.ajax({
            url: '/sd/addSalesReturn/' + id,
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
                   

                    /*   clearTableData();
                      tableData.addRow(); */
                    resetForm();
                    url = "/sd/salesReturnList";
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

//add PO draft
function addSalesReturnDraft(collection) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', referanceID);
    formData.append('invoice_date_time', $('#invoice_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    /*  formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val()); */
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    /* formData.append('txtDeliveryInst', $('#txtDeliveryInst').val()); */
    formData.append('grandTotal', $('#lblTotal').text());

    $.ajax({
        url: '/sd/addSalesReturnDraft',
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
    DataChooser.setDataSourse(['', '', '', ''], ItemList);
    DataChooser.showChooser(event, event);
    $('#data-chooser-modalLabel').text('Items');
} */

function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.commitData();
        DataChooser.showChooser(event, event, "item");
        $('#data-chooser-modalLabel').text('Items');
    }
}


//load item
/* function loadItems() {
    var list = [];
    $.ajax({
        url: '/prc/loadItems',
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

    })
    return list;
} */

    function loadItems(id) {
        var list = [];
        var branch = $('#cmbBranch').val();
        var location = $('#cmbLocation').val();
        $.ajax({
            url: '/sd/loadItemsforsalesinvoice/'+id,
            type: 'get',
            async: false,
            data:{
                branch :branch,
                location :location
            },
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

//load item
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
        success: function (data) {
            console.log(data)
            var txt = data.data;
            /*  console.log(txt); */
            $('#lblCustomerAddress').val(txt[0].primary_address);
            var cusID = txt[0].customer_id;
            $('#lblCustomerName').attr('data-id', cusID);
            load_setoff_data_(cusID);


        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load payment term
/* function loadPamentTerm() {
    $.ajax({
        url: '/sd/loadPamentTerm',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentTerm').append('<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })

} */

function loademployees() {
    $.ajax({
        url: '/sd/loademployees',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');
                /*  $('#cmbSalesRep').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>'); */
            })

            // $('#cmbEmp').change();

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

        // validate transacation table and hashmap
        var item_array = [];

        var table_data_source = tableData.getDataSourceObject();
        for (var i = 0; i < table_data_source.length; i++) {
            var data_id = $(table_data_source[i][0]).attr('data-id');

            if (item_array.includes(data_id)) {
                showWarningMessage('Already exist');
                return;
            } else {
                item_array.push(data_id);
            }
        }





        // end of validate transacation table and hashmap

        resetTransactionTableRow(row_childs);

        $.ajax({
            url: '/sd/return_getItemInfo/' + item_id,
            type: 'get',
            success: function (response) {
                
                for(var i = 0; i < response.length; i++){
                    console.log(response[i][0].item_Name);
                    var expireDateManage = response[0].manage_expire_date;
                if (expireDateManage == 1) {
                    $(row_childs[14]).removeAttr('disabled');
                }

                $(row_childs[1]).val(response[i][0].item_Name);
                $(row_childs[4]).val(response[i][0].unit_of_measure);
                $(row_childs[6]).val(response[i][0].package_unit);
                $(row_childs[5]).val(response[i][0].package_size);
                 $(row_childs[7]).val(response[i][0].ih_wh_price);
                $(row_childs[8]).val(response[i][0].ih_rt_price);
                //$(row_childs[12]).val(response[i][0].ih_rt_price);
                $(row_childs[2]).focus();
                $(row_childs[2]).val('');
                $(row_childs[3]).val('');

                $(row_childs[10]).val('');

                calculation();

                }


                
            }
        })

    }

}

function setPrice(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var item_id = $(cell[0]).children().eq(0).attr('data-id');
    var retail = parseFloat($(event).val().replace(/,/g, ''));
    var brn_id = $('#cmbBranch').val();
    if (isNaN(parseFloat(retail))) {
        retail = 0
    }

    $.ajax({
        url: '/sd/setPrice/' + item_id + '/' + retail + '/' + brn_id,
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
            $.each(data, function (index, value) {
                var wh_price = value.whole_sale_price;
                var c_price = value.cost_price;

                if (isNaN(parseFloat(wh_price))) {
                    wh_price = 0;
                }

                if (isNaN(c_price)) {
                    c_price = 0;
                }
                $($(cell[7]).children()[0]).val(wh_price);
                $($(cell[14]).children()[0]).val(c_price);

            })

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

function calValueandCostPrice(event) {

    var row = $($(event).parent()).parent();
    var cell = row.find('td');

    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[7]).children()[0]);
    var discount_percentage = $($(cell[9]).children()[0]);
    var discount_amount = $($(cell[10]).children()[0]);

    var AMOUNT = getDiscountAmount(qty, price, discount_percentage, discount_amount);
    $($(cell[11]).children()[0]).val(AMOUNT);



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
        var discount_pres = parseFloat(arr[i][9].val().replace(/,/g, ""));


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


function getEachSalesReturn(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sd/getEachSalesReturn/' + id + '/' + status,
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

            getLocation(res[0].branch_id);

            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#invoice_date_time').val(res[0].order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerName').val(res[0].customer_name);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            /*  $('#cmbPaymentTerm').val(res[0].payment_term_id); */
            $('#txtRemarks').val(res[0].remarks);
            /*  $('#txtDeliveryInst').val(res[0].delivery_instruction); */
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            $('#txtInvoiceID').val(res[0].SI_ext);
            $('#cmbReason').val(res[0].return_reason_id);

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
        url: '/sd/getEachproductofSalesReturn/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);


            var dataSource = [];
            $.each(data, function (index, value) {
                console.log(value.Item_code);
                var qty = value.quantity;
                var price = value.price;
                var disAmount = value.discount_amount;
                var valueS = (qty * price) - disAmount;

                dataSource.push([



                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id": value.item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "", "valuefrom": "datachooser" },
                    { "type": "text", "class": "transaction-inputs", "value": value.item_name, "style": "max-height:30px;margin-left:10px;", "event": "", "style": "width:370px", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.quantity, "style": "max-height:30px;width:80px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)", "compulsory": true },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.free_quantity, "style": "max-height:30px;width:80px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this)" },
                    { "type": "text", "class": "transaction-inputs", "value": value.unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs", "value": value.package_unit, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.price, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                    { "type": "text", "class": "transaction-inputs", "value": valueS, "style": "max-height:30px;text-align:right;width:150px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);calculation()", "width": 30 }

                ]);
                tableData.setDataSource(dataSource);
                calculation();


            });

        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}


//update SI
function updateReturn(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('invoice_date_time', $('#invoice_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    /*  formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val()); */
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    /* formData.append('txtDeliveryInst', $('#txtDeliveryInst').val()); */
    formData.append('grandTotal', $('#lblTotal').text());

    $.ajax({
        url: '/sd/updateReturn/' + id,
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
                closeCurrentTab();

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


//update PO draft
function updateSalesReturnDraft(collection, id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('invoice_date_time', $('#invoice_date_time').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('cmbEmp', $('#cmbEmp').val());
    formData.append('lblCustomerName', $('#lblCustomerName').val());
    formData.append('customerID', $('#lblCustomerName').data('id'));
    /* formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val()); */
    formData.append('txtDiscountPrecentage', $('#txtDiscountPrecentage').val());
    formData.append('txtDiscountAmount', $('#txtDiscountAmount').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    /*  formData.append('txtDeliveryInst', $('#txtDeliveryInst').val()); */
    formData.append('grandTotal', $('#lblTotal').text());

    $.ajax({
        url: '/sd/updateSalesReturnDraft/' + id,
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
                closeCurrentTab();

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
function approveRequestSalesInvReturn(id) {
    $.ajax({
        url: '/sd/approveRequestSalesInvReturn/' + id,
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
function rejectRequestSalesInvReturn(id) {
    $.ajax({
        url: '/sd/rejectRequestSalesInvReturn/' + id,
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
    $("#txtInvoiceID").attr("data-id", "undefined");
    // $("#txtInvoiceID").removeAttr("data-id");

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
            $('#invoice_date_time').val(formattedDate);


        },
        error: function (error) {
            console.log(error);
        }
    });
}



// Function to format the date as "yyyy-mm-dd"
function formatDate(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    return year + '-' + month + '-' + day;
}


function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_SalesReturn", table, doc_number);
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
    } else {
        percentage_price = (quantity_price / 100.00) * percentage;
    }
    final_value = (quantity_price - percentage_price);


    return final_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString();
}

function loadReason() {
    $.ajax({
        url: '/sd/loadReason',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbReason').append('<option value="' + value.sales_return_reson_id + '">' + value.sales_return_resons + '</option>');

            })

        },


    })
}

//load customer to cmb
function loadCustomerToCMB() {

    $.ajax({
        url: '/sd/loadCustomers',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            var dt = response.data
            console.log(dt)
            $.each(dt, function (index, value) {

                $('#cmbCustomer').append('<option value="' + value.customer_id + '">' + value.customer_name + '</option>');

            })
        },
        error: function (error) {
            console.log(error);
        },

    })

}




function getCusrrentMonthDates() {

    $.ajax({
        url: '/sd/getMonthDates',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            var firstDate = response.first;
            var lastDate = response.last;

            /*  var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#invoice_date_time').val(formattedDate); */

            var parts_first = firstDate.split('/');
            var First_Date = parts_first[2] + '-' + parts_first[1] + '-' + parts_first[0];

            $('#from_date').val(First_Date);

            var parts_last = lastDate.split('/');
            var Last_Date = parts_last[2] + '-' + parts_last[1] + '-' + parts_last[0];

            $('#to_date').val(Last_Date);


        },
        error: function (error) {
            console.log(error);
        }
    });
}

//load emp to cmb in model
function loademployeesInModel() {
    $.ajax({
        url: '/sd/loademployeesInModel',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbSalesRep').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');
            })

        },
        error: function (error) {
            console.log(error);
        },

    })
}


function resetTransactionTableRow(row) {

    /* $(row[1]).val('');
    $(row[0]).attr('data-id', undefined); */
    $(row[1]).val('');
    $(row[2]).val('');
    $(row[3]).val('');
    $(row[4]).val('');
    $(row[5]).val('');
    $(row[6]).val('');
    $(row[7]).val('');
    $(row[8]).val('');
    $(row[9]).val('');
    $(row[10]).val('');


}


function dataChooserShowEventListener(event) {
    if (pickOrderStatus) {
        DataChooser.dispose();
        pickOrderStatus = false;
    }

}

//get sels rep code
function get_rep_code(id) {
    $.ajax({
        url: '/sd/get_rep_code/' + id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var res = data.data;
            $('#invoice_date_time').attr('data-id', res[0].code);

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//get branch code
function get_branch_code(id) {
    $.ajax({
        url: '/sd/get_branch_code/' + id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var res = data.data;
            $('#cmbBranch').attr('data-id', res[0].code);


        },
        error: function (error) {
            console.log(error);
        },

    })
}


//foc calculation threshold (manually item insertion)
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
        url: '/sd/getItem_foc_threshold_For_sales_return/' + item_id + "/" + formatted_entered_qty + "/" + date,
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



//foc calculation threshold (pick order insertion)
function foc_calculation_threshold_pick_order_sales_return(item_id, entered_qty) {
    var date = $('#invoice_date_time').val();


    $.ajax({
        url: '/sd/getItem_foc_threshold_For_sales_return/' + item_id + "/" + entered_qty + "/" + date,
        type: 'get',
        async: false,
        success: function (response) {
            $.each(response, function (index, value) {

                foc_qty_threshold_from_pick_orders_sales_return = value.Offerd_quantity;


            })

        }

    })
    return foc_qty_threshold_from_pick_orders_sales_return;
}

//check invoice foc
//check qty and with GRN qty and FOC
function checkqtyandFoc_sales_rtn(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    /*  var Bal_qty = $($(cell[19]).children()[0]).val(); */
    var Bal_foc = $($(cell[13]).children()[0]).val();
    /*  var qty = $($(cell[2]).children()[0]).val(); */
    var foc = $($(cell[3]).children()[0]).val();

    

    if (parseInt(foc) < parseInt(Bal_foc)) {
        showWarningMessage('FOC should be equal or less than Invoice FOC');
        $($(cell[3]).children()[0]).val(Bal_foc);

    }
    calValueandCostPrice(event)


}

//check retrun location type
function checkReturnLocation(br_id_) {
    $('#cmbLocation').empty();
    $.ajax({
        url: '/sd/checkReturnLocation/' + br_id_,
        type: 'get',
        success: function (response) {
            console.log(response);
            var dt = response.data
            if (dt.length < 1) {
                $('#warning_alert').removeClass('hide').addClass('show');
                $('#cmbLocation').empty();
                return;
            }

            $.each(dt, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            });
            $('#cmbLocation').trigger('change');
        }
    })

}

//load book number
/* function loadBookNumber() {
    $.ajax({
        url: '/sd/loadBookNumber/',
        type: 'get',
        async: false,
        success: function (response) {
            console.log(response);
            var dt = response.data

            $.each(dt, function (index, value) {
                $('#cmbBookNumber').append('<option value="' + value.book_id + '">' + value.book_name + '</option>');

            });
            $('#cmbBookNumber').trigger('change');
        }
    })

} */

//load set off data from DL using customer code - without invoice
function load_setoff_data_(id) {
    
    $.ajax({
        url: '/sd/load_setoff_data_/' + id,
        type: 'get',
        async: false,
        success: function (response) {

            var dt = response.data
            console.log(dt);
            var table = $('#set_off_table');
            var tableBody = $('#set_off_table tbody');
            tableBody.empty();
            $.each(dt, function (index, item) {
                var your_ref_num = "-"+item.your_reference_number;
                if(item.your_reference_number == "" || item.your_reference_number == null){
                    your_ref_num = ""
                }
                console.log(your_ref_num);
                var inv_number = item.manual_number + your_ref_num;
                var row = $('<tr>').css('height', '15px');
                row.append($('<td>').append($('<label>').attr('data-id', item.debtors_ledger_id).text(item.trans_date)));
                row.append($('<td>').text(inv_number));
                row.append($('<td>').text(item.age));
                row.append($('<td>').text(item.balance));
                row.append($('<td>').append($('<input>').attr({
                    'type': 'checkbox',
                    'class': 'form-check-input',
                    'name': 'select_dl',
                    'value': item.debtors_ledger_id
                }).on('change', function () {
                    set_off($(this));
                })));

                row.append(
                    $('<td>').css({
                        'width': '150px',
                        'height': '20px',

                    }).append(
                        $('<input style="text-align:right">').attr({
                            'type': 'number',
                            'class': 'form-control',
                            'id': 'txt_' + item.debtors_ledger_id,
                            'disabled': true,
                            'oninput': 'allow_num_validate(this)'

                        })
                    )
                );
                row.append($('<td>').text(item.balance));


                table.append(row);
            });


        }
    });

}


//load set off data from DL using customer code - with invoice
function load_setoff_data_invoice(id) {
    $.ajax({
        url: '/sd/load_setoff_data_invoice/' + id,
        type: 'get',
        async: false,
        success: function (response) {

            var dt = response.data
            console.log(dt);
            var table = $('#set_off_table');
            var tableBody = $('#set_off_table tbody');
            tableBody.empty();
            $.each(dt, function (index, item) {

                var your_ref_num = "-"+item.your_reference_number;
                if(item.your_reference_number == "" || item.your_reference_number == null){
                    your_ref_num = ""
                }
                console.log(your_ref_num);
                var inv_number = item.manual_number;
                var primary_id = $('#txtInvoiceID').attr('data-id')
                var checked = false;
                var textBox_val = '';
                var netTotalText = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
                var remainBal = item.balance;
                if (item.sales_invoice_Id == primary_id && netTotalText >= parseFloat(item.balance)) {
                    checked = true;
                    textBox_val = item.balance;
                    remainBal = "0.00";
                }
                var row = $('<tr>').css('height', '15px');
                row.append($('<td>').append($('<label>').attr('data-id', item.debtors_ledger_id).text(item.trans_date)));
                row.append($('<td>').append($('<label>').attr('data-id', item.sales_invoice_Id).text(inv_number)));
                row.append($('<td>').text(item.age));
                row.append($('<td>').text(item.balance));
                row.append($('<td>').append($('<input>').attr({
                    'type': 'checkbox',
                    'class': 'form-check-input',
                    'name': 'select_dl',
                    'value': item.debtors_ledger_id,
                    'checked': checked
                }).on('change', function () {
                    set_off($(this));
                })));

                row.append(
                    $('<td>').css({
                        'width': '150px',
                        'height': '20px',

                    }).append(
                        $('<input style="text-align:right">').attr({
                            'type': 'number',
                            'class': 'form-control',
                            'id': 'txt_' + item.debtors_ledger_id,
                            'disabled': true,
                            'oninput': 'allow_num_validate(this)',
                            'value': textBox_val

                        })
                    )
                );
                row.append($('<td style="text-align:right">').text(remainBal));


                table.append(row);
            });


        }
    });

}

//set off
function set_off(event) {
    var checkbox = $(event);
    var textbox = checkbox.closest('tr').find('td:eq(5) input[type="number"]');
    var textboxID = textbox.attr('id');
    var balance = checkbox.closest('tr').find('td:eq(3)').text().replace(/,/g, '');
    var net_total_ = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
    var remainTd = checkbox.closest('tr').find('td:eq(6)');

    if (checkbox.prop('checked')) {
        console.log(textboxID);
        $('#' + textboxID).prop('disabled', false);
        var table = checkbox.closest('table');
        var sum = 0;

        table.find('tr').each(function () {
            var inputElement = $(this).find('td input[type="number"]');

            if (inputElement.length > 0) {
                var value = parseFloat(inputElement.val().replace(/,/g, ''));

                if (!isNaN(value) && inputElement.val().length > 0) {
                    sum += value;
                }
            }
        });


        if (parseFloat(sum) < parseFloat(net_total_)) {
            if ((parseFloat(net_total_) - parseFloat(sum)) > parseFloat(balance)) {
                $('#' + textboxID).val(balance);
                remainTd.text('0.00');

            } else {
                var net_balance = parseFloat(net_total_) - parseFloat(sum)
                $('#' + textboxID).val(net_balance);
                remainTd.text(parseFloat(parseFloat(balance) - parseFloat(net_balance)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            }

        }


    } else {
        $('#' + textboxID).val('');
        $('#' + textboxID).prop('disabled', true);
        remainTd.text(parseFloat(balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

    }

}


//allwoing only numbers to textboxes and checking with balance also
/* function allow_num_validate(event) {
    //validate amount


    $(event).on('input', function (e) {

        var currentRow = $(this).closest('tr');
        var balance_cell = currentRow.find('td:eq(3)');
        var balance_val = balance_cell.text();
        var formatted_balance_val = parseFloat(balance_val.replace(/[^0-9]/g, ''));
        if (formatted_balance_val < parseFloat($(this).val())) {
            $(this).val('');
            showWarningMessage('Set off amount should be less than or equal to balance');
        }

        $(this).val(function (index, value) {
            return value.replace(/[^0-9]/g, '');
        });
    });


} */

    function allow_num_validate(event) {
        $(event).on('input', function (e) {
            var $this = $(this);
            var currentRow = $this.closest('tr');
            var balance_cell = currentRow.find('td:eq(3)');
            var balance_val = balance_cell.text();
            var formatted_balance_val = parseFloat(balance_val.replace(/[^0-9.]/g, ''));
    
            var input_val = $this.val();
            var formatted_input_val = parseFloat(input_val);
            var remainTd = $(event).closest('tr').find('td:eq(6)');
    
            if (formatted_balance_val < formatted_input_val) {
                $this.val('');
                showWarningMessage('Set off amount should be less than or equal to balance');
            } else {
                // Allow numbers and only one dot
                var new_val = input_val.replace(/[^0-9.]/g, '');  // Remove non-numeric characters except dot
    
                // Ensure there's only one dot
                var parts = new_val.split('.');
                if (parts.length > 2) {
                    // If there are more than one dot, remove all extra dots
                    new_val = parts[0] + '.' + parts.slice(1).join('');
                }
    
                // Limit to two decimal places
                parts = new_val.split('.');
                if (parts.length > 1) {
                    new_val = parts[0] + '.' + parts[1].slice(0, 2);
                }
    
                if (new_val !== input_val) {
                    $this.val(new_val);
                    remainTd.text(parseFloat(balance_val) - parseFloat(new_val))
                }
            }
        });
    }
    
    
    
    
    
    
    
    

function get_price_with_retail_price(event){
   // alert();
    var entered_retail_price = parseFloat($(event).val(0));
console.log(entered_retail_price);
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    
   /*  var Bal_foc = $($(cell[13]).children()[0]).val();
   
    var foc = $($(cell[3]).children()[0]).val(); */

    $.ajax({
        url:'/sd/get_wh_price_with_rt_price',
        data:entered_retail_price,
        type:'get',
        async: false,
        success: function (response) {
        
        
        
        
        }

    })

   
    calValueandCostPrice(event)

}

function loadSupplyGroupsAsSalesAnalyst(){
    $.ajax({
        url: '/loadSupplyGroupsAsSalesAnalyst',
        type: 'get',
        async: false,
        success: function (data) {
            $('#cmbSalesAnalysist').append('<option value="0">Select</option>');
            $.each(data, function (index, value) {
                $('#cmbSalesAnalysist').append('<option value="' + value.supply_group_id + '">' + value.supply_group + '</option>');

            });
            $('#cmbSalesAnalysist').trigger('change');
        },
    })
}
