
var m_number = undefined;
var is_called = 0; // use in customer search
var balance = undefined; // used to load set off balance
var selected_balance_cell = undefined;
var selected_dl_id = undefined;
var referanceID;
var action;
var pre_setoff_amount = 0;
$(document).ready(function () {


    customers = loadCustomerTOchooser();

    DataChooser.addCollection("Customer", ['Customer Name', 'Customer Code', 'Town', 'Route', ''], customers);


    $('#txt_customer').on('focus', function () {
        is_called = 0;
        DataChooser.showChooser($(this), $(this), "Customer");
        $('#data-chooser-modalLabel').text('Customers');

    });





    //tr click event
    $('#set_off_data_table').on('click', 'tr', function (e) {

        $('#set_off_data_table tr').removeClass('highlight')
        $(this).addClass('highlight');
        balance = parseFloat($(this).find('td:eq(5)').text().replace(/,/g, ''));
        selected_balance_cell = $(this).find('td:eq(5)');
        selected_dl_id = $(this).find('td:eq(6)').text();



    });






    //tr click event on transaction_table table
    $('#transaction_table').on('dblclick', 'tr', function (e) {

        var current_set_off_amount = $(this).find('td:eq(6)').text(); // to check whether alreadyset oof or not

        if (isNaN(parseFloat(current_set_off_amount))) {
            if (balance > 0) {
                console.log(balance);
                $('#transaction_table tr').removeClass('highlight');
                $(this).addClass('highlight');
                var transaction_balance = parseFloat($(this).find('td:eq(5)').text().replace(/,/g, ''));

                if (balance <= transaction_balance) {

                    $(this).find('td:eq(6)').text(parseFloat(balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }));
                    // $(this).find('td:eq(6) input[type="text"]').val(parseFloat(balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    balance = balance - balance
                    $('#set_off_data_table tr.highlight td:eq(5)').text(parseFloat(balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }));

                } else {

                    $(this).find('td:eq(6)').text(parseFloat(transaction_balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }));
                    //$(this).find('td:eq(6) input[type="text"]').val(parseFloat(transaction_balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                    balance = balance - transaction_balance
                    $('#set_off_data_table tr.highlight td:eq(5)').text(parseFloat(balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }));
                }
                $(this).find('td:eq(8)').text(selected_dl_id)
            } else {
                showWarningMessage('Balance should be greater than 0.00');

            }

        } else {
            showWarningMessage('This record has been already setoff');
        }



    });


    $('#bntLoadData').on('click', function () {
        $('#txtInv').val(m_number);
        load_invoice_details(m_number);
        $('#inv_info_search_modal').modal('hide');

    });

    //trigger save function
    $('#btnSave').on('click', function () {

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
                    if ($('#btnSave').val() == 'Save') {
                        newReferanceID('customer_transaction_alocations', '1400');
                        save_customer_transaction_allocation(referanceID);
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


});




function base64Decode(str) {
    return decodeURIComponent(escape(atob(str)));
}







//load invoice details
function load_customer_data(code) {

    if (is_called == 0) {
        $(".val_table tbody").empty();
        $('.val_lbl').text('');

        $.ajax({
            url: '/dl/load_customer_data/' + code,
            method: 'GET',
            cache: false,
            timeout: 800000,
            success: function (data) {
                if (data.header.length < 1) {
                    showWarningMessage('Please enter a correct invoice number')
                } else {


                    var header = data.header;
                    var transaction = data.transaction;
                    var set_off_Data = data.set_off_Data;
                    var customer_receipt_data = data.customer_receipt;
                    var sfa_data = data.sfa;
                    var delivery_plan = data.delivery_plan;
                    var picking_list = data.picking_list;
                    console.log(transaction);
                    //header

                    $.each(header, function (index, value) {
                        var outstanding_ = value.outstanding;
                        if (outstanding_ == 0) {
                            outstanding_ = 0.00
                        }
                        $('#lblName').text(value.customer_name);
                        $('#lblAddress').text(value.primary_address);
                        $('#lblTown').text(value.townName);
                        $('#lblRoute').text(value.route_name);
                        $('#lblOustanding').text(parseFloat(outstanding_).toLocaleString());




                    });
                    //appending transaction
                    $.each(transaction, function (index, value) {
                        var newRow = $("<tr>");

                        /*  var value_ = (parseFloat(value.quantity) * parseFloat(value.price)) - ((parseFloat(value.quantity) * parseFloat(value.price)) * (parseFloat(value.discount_percentage) /100)); */
                        var balance_ = parseFloat(value.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, });
                        newRow.append("<td>" + value.trans_date + "</td>");
                        newRow.append("<td>" + value.external_number + "</td>");
                        newRow.append("<td>" + value.description + "</td>");
                        newRow.append("<td style='text-align: right;display:none;'>" + value.amount + "</td>");
                        newRow.append("<td style='text-align: right;display:none;'>" + value.paidamount + "</td>");
                        newRow.append("<td style='text-align: right;'>" + balance_ + "</td>");
                        newRow.append("<td style='text-align: right; max-width: 150px;max-height: 45px;' class='editable right-align form-control' onfocus='getpreviouseamount(this)' oninput='validateStoff(this)'></td>");
                        newRow.append("<td style='display:none'>" + value.debtors_ledger_id + "</td>");
                        newRow.append("<td style='display:none'></td>");
                        newRow.append("<td style='display:none'>" + value.branch_id + "</td>");
                        $("#transaction_table tbody").append(newRow);



                    });
                    $('.editable').attr('contenteditable', true);

                    //appending setoff data
                    $.each(set_off_Data, function (index, value) {
                        var newRow = $("<tr>");

                        var balance_ = parseFloat(Math.abs(value.balance)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, });
                        var amount_ = parseFloat(Math.abs(value.amount)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, });
                        newRow.append("<td>" + value.trans_date + "</td>");
                        newRow.append("<td>" + value.external_number + "</td>");
                        newRow.append("<td>" + value.description + "</td>");
                        newRow.append("<td style='text-align: right;display:none;'>" + amount_ + "</td>");
                        newRow.append("<td style='text-align: right;'>" + balance_ + "</td>");
                        newRow.append("<td style='text-align: right;'>" + balance_ + "</td>");
                        newRow.append("<td style='display:none;'>" + value.debtors_ledger_id + "</td>");
                        $("#set_off_data_table tbody").append(newRow);


                    });




                }

            }

        });

    }


    is_called = 1; // used to avoid repeting data by data chooser


}



//load customers
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

                data = customerData;
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return data;
}


function dataChooserEventListener(event, id, value) {
    if ($(event.inputFiled).attr('id') == 'txt_customer') {
        load_customer_data(value);


    }

}

function dataChooserShowEventListener(event) {

}

//save customer transaction allocation 
function save_customer_transaction_allocation(referanceID) {
    console.log(referanceID);
    var formData = new FormData();

    collection = [];
    $('#transaction_table tbody tr').each(function (index, row) {

        var set_off_amount = parseFloat($(row).find('td:eq(6)').text().replace(/,/g, ''));
        if (!isNaN(set_off_amount)) {
            var dl_id = $(row).find('td:eq(7)').text();
            var dl_set_off_id = $(row).find('td:eq(8)').text();
            var branch_id = $(row).find('td:eq(9)').text();
            collection.push({
                "dl_id": dl_id,
                "dl_set_off_id": dl_set_off_id,
                "set_off_amount": set_off_amount,
                "branch_id": branch_id,

            });
        }

    });


    formData.append('collection', JSON.stringify(collection));
    formData.append('LblexternalNumber', referanceID);


    $.ajax({
        url: '/dl/save_customer_transaction_allocation',
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
            if (status) {
                showSuccessMessage('Successfully Saved');
                $(".val_table tbody").empty();
                $('.val_lbl').text('');
                url = "/dl/transaction_allocation_list";
                window.location.href = url;

            } else {
                showWarningMessage('Unable to save record');
            }


        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })


}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_customer_transaction_allocation", table, doc_number);

}

//get previouse set off amount from transaction table
function getpreviouseamount(event) {
    pre_setoff_amount = 0;
    pre_setoff_amount = parseFloat($(event).text());



}

//validate set off
function validateStoff(event) {
    var trElement = $(event).closest('tr');
    var refreince_dl_id = trElement.find('td:eq(8)').text().trim();
    var new_set_off_value = parseFloat($(event).text().replace(/,/g, ''));


    //getting set off table tr
    var reference_tr = $('#set_off_data_table tr:has(td:eq(6):contains("' + refreince_dl_id + '"))');
    var reference_remain_balance = parseFloat($(reference_tr.find('td:eq(5)')).text().replace(/,/g, ''));
    var reference_balance_amount = parseFloat($(reference_tr.find('td:eq(4)')).text().replace(/,/g, ''));

    if (reference_balance_amount == pre_setoff_amount) {
        balance = reference_balance_amount;
        $(reference_tr.find('td:eq(5)')).text(balance);
        new_set_off_value = parseFloat($(event).text().replace(/,/g, ''));
        reference_remain_balance = parseFloat($(reference_tr.find('td:eq(5)')).text().replace(/,/g, ''));
        if (new_set_off_value > reference_remain_balance) {
            showWarningMessage('Insufficient balance');
            $(event).text(reference_remain_balance);
            $(reference_tr.find('td:eq(5)')).text('0');
        } else {
            var new_reference_setoff_amount = parseFloat(reference_remain_balance) - parseFloat(new_set_off_value)
            $(reference_tr.find('td:eq(5)')).text(new_reference_setoff_amount);
            balance = new_reference_setoff_amount;

        }


    } else {
        new_set_off_value = parseFloat($(event).text().replace(/,/g, ''));
        reference_remain_balance = parseFloat($(reference_tr.find('td:eq(5)')).text().replace(/,/g, ''));
       /*  balance = reference_remain_balance;
        $(reference_tr.find('td:eq(5)')).text(balance); */
        if (parseFloat(new_set_off_value) == 0 || isNaN(new_set_off_value)) {

            balance = pre_setoff_amount;
            console.log(balance);
            $(reference_tr.find('td:eq(5)')).text(balance);
        } else if (new_set_off_value > reference_remain_balance) {
            showWarningMessage('Insufficient balance');
            $(event).text(reference_remain_balance);
            $(reference_tr.find('td:eq(5)')).text('0');
        } else {
            var new_reference_setoff_amount = parseFloat(reference_remain_balance) - parseFloat(new_set_off_value)
            $(reference_tr.find('td:eq(5)')).text(new_reference_setoff_amount);
            balance = new_reference_setoff_amount;

        }


    }








}