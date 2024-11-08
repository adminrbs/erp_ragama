

var action = undefined;
var CUSTOMER_RECEIPT_ID = undefined;
var REFERANCE_ID = undefined;
var hidden_columns = "";
var rcptAmountforcheckbox = null;
var total_set_off_Amount = 0;
$(document).ready(function () {
    getCollectors_and_Cashiers();
    getBank();
    getBranch();
    getReceiptMethod();

    $("#tab-single-cheque").attr("hidden", true);
    $("#tab-bank-slip").attr("hidden", true);


    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var id = param[0].split('=')[1].split('&')[0];
        CUSTOMER_RECEIPT_ID = id;
        var action = param[0].split('=')[2].split('&')[0];
        if (action == 'view') {
            // getCollectors_and_Cashiers();
            $('#btnAction').hide();
            $('#btnAutomaticSetoff').hide();
            $('#txtRefNo').prop('disabled', true);
            $('#txtDate').prop('disabled', true);
            $('#txtCustomerID').prop('disabled', true);
            $('#txtCustomerName').prop('disabled', true);
            $('#txtCustomerID').prop('disabled', true);
            $('#cmbCollector').prop('disabled', true);
            $('#cmbCashier').prop('disabled', true);
            $('#cmbGLAccount').prop('disabled', true);
            $('#cmbReceiptMethod').prop('disabled', true);
            $('#txtAmount').prop('disabled', true);
            $('#txtDiscount').prop('disabled', true);
            $('#txtRound_up').prop('disabled', true);
            $('#cmbBranch').prop('disabled', true);
            $('#checkAdvancePayment').prop('disabled', true);
            //$('.hide_col').hide();

        } else if (action == 'edit') {
            hidden_columns = "";
            $('#btnAction').show();
            $('#btnAction').text('Update');
        }
        getCustomerReceipt(id);
    } else {
        $('#btnAction').show();
        $('#btnAutomaticSetoff').show();
        $('#btnAction').text('Save');
    }



    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });


    $('input[name="date"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD',
        }
    });

    $('input[name="chequeValidDate"]').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'YYYY-MM-DD',
        }
    });

    $('input[name="chequeValidDate"]').on('apply.daterangepicker', function (ev, picker) {
        $('#txtChequeAmount').focus();
        $('#txtChequeAmount').select();

    });

    $('#btnMultiChequeAdd').on('click', function () {

        if ($('#txtMultiChequeRefNo').val().trim().length === 0) {
            $('#txtMultiChequeRefNo').focus();
            $('#txtMultiChequeRefNo').css('borderColor', "red");
            showWarningMessage('Invalied Referance No');
            return;
        } else {
            $('#txtMultiChequeRefNo').css('borderColor', "#059669");
        }

        if ($('#txtMultiChequeNo').val().trim().length === 0) {
            $('#txtMultiChequeNo').focus();
            $('#txtMultiChequeNo').css('borderColor', "red");
            showWarningMessage('Invalied Cheque No');
            return;
        } else {
            $('#txtMultiChequeNo').css('borderColor', "#059669");
        }


        if ($('#txtMultiChequeValidDate').val().trim().length === 0) {
            $('#txtMultiChequeValidDate').focus();
            $('#txtMultiChequeValidDate').css('borderColor', "red");
            showWarningMessage('Invalied Date');
            return;
        } else {
            $('#txtMultiChequeValidDate').css('borderColor', "#059669");
        }

        if ($('#txtMultiChequeAmount').val().trim().length === 0) {
            $('#txtMultiChequeAmount').focus();
            $('#txtMultiChequeAmount').css('borderColor', "red");
            showWarningMessage('Invalied Amount');
            return;
        } else {
            $('#txtMultiChequeAmount').css('borderColor', "#059669");
        }

        if ($('#txtMultiChequeBank').val().trim().length === 0) {
            $('#txtMultiChequeBank').focus();
            $('#txtMultiChequeBank').css('borderColor', "red");
            showWarningMessage('Invalied Bank');
            return;
        } else {
            $('#txtMultiChequeBank').css('borderColor', "#059669");
        }

        if ($('#txtMultiChequeBankBranch').val().trim().length === 0) {
            $('#txtMultiChequeBankBranch').focus();
            $('#txtMultiChequeBankBranch').css('borderColor', "red");
            showWarningMessage('Invalied Branch');
            return;
        } else {
            $('#txtMultiChequeBankBranch').css('borderColor', "#059669");
        }
        addMultiChequeRow();
    });

    var customer_data_source = getCustomers();
    DataChooser.setTitle('Customer');
    DataChooser.addCollection("customers", ['Customer Name', 'Customer Code', 'Town', 'Route', ''], customer_data_source);
    $('#txtCustomerID').on('click', function () {
        /* DataChooser.showChooser($(this)); */
        DataChooser.showChooser($(this), $(this), "customers");
        $('#data-chooser-modalLabel').text('customers');
    });

    $('.select2-single-checque-bank').select2();
    $('.select2-multi-checque-bank').select2();
    $('.select2-single-checque-branch').select2();
    $('.select2-multi-checque-branch').select2();

    $('#btnAction').on('click', function (event) {
        event.preventDefault();
        if ($(this).text() == 'Save') {
            newReferanceID('customer_receipts', 500);
            saveReceipt();
        } else if ($(this).text() == 'Update') {
            updateReceipt();
        }

    });

    $('#txtAmount').on('focus', function () {
        $(this).select();
    });

    $('#txtDiscount').on('focus', function () {
        $(this).select();
    });

    $('#txtRound_up').on('focus', function () {
        $(this).select();
    });

    $('#txtAmount').on('focusout', function () {
        var number = parseFloat($(this).val());
        $(this).val(number.toFixed(2));
    });

    $('#txtDiscount').on('focusout', function () {
        var number = parseFloat($(this).val());
        $(this).val(number.toFixed(2));
    });

    $('#txtRound_up').on('focusout', function () {
        var number = parseFloat($(this).val());
        $(this).val(number.toFixed(2));
    });

    $('#btnAutomaticSetoff').on('click', function () {
        resetSetoffTable();
        var amount = automaticSetoff();
        if (amount <= 0) {
            $('#txtAmount').focus();
            showWarningMessage('Invalied setoff amount');
            return;
        }
        setoffAmount(amount, 0);
        lockInputsSetoff();
    });

    $('#txtAmount').on('input', function () {
        setoffAmountOnInput(0);
    });

    $('#txtDiscount').on('input', function () {
        setoffAmountOnInput(0);
    });

    $('#txtRound_up').on('input', function () {
        setoffAmountOnInput(0);
    });

    $('#cmbChequeBank').on('change', function () {
        getBankBranch($(this).val());
    });




    $('#txtBankCode').on('keydown', function (event) {
        console.log("Code: " + event.which);
        if (event.which != 8) {
            if ($(this).val().length === 4) {
                $(this).val($(this).val() + "-");
            }
        }
    });


    $('#txtBankCode').on('keyup', function (event) {

        if ($(this).val().length >= 8) {
            //$(this).val($(this).val().substr(0, $(this).val().length - 1));
            var bank_code = $(this).val().split("-")[0];
            var branch_code = $(this).val().split("-")[1];
            getAutoSelectBankBranch(bank_code, branch_code);
            $('#txtChequeValidDate').focus();
            $('#txtChequeValidDate').val($('#txtChequeValidDate').val().substr(0, $('#txtChequeValidDate').val().length - 1));
        }


    });

    $('.math-abs').keypress(function (event) {
        // Get the current input value
        var inputValue = $(this).val();

        // Check if the pressed key is a number, decimal point, or backspace
        if (
            (event.which != 46 || inputValue.indexOf('.') != -1) &&
            (event.which < 48 || event.which > 57) &&
            event.which != 8
        ) {
            event.preventDefault(); // Prevent the keypress event
        }
    });


    $('#txtRound_up').keypress(function (event) {
        // Get the current input value
        var inputValue = $(this).val();

        // Check if the pressed key is a number, decimal point, minus sign, or backspace
        if (
            (event.which != 46 || inputValue.indexOf('.') != -1) &&
            (event.which != 45 || inputValue.indexOf('-') != -1) &&
            (event.which < 48 || event.which > 57) &&
            event.which != 8
        ) {
            event.preventDefault(); // Prevent the keypress event
        }
    });


    $('#cmbReceiptMethod').on('change', function () {
        if ($(this).val() == '2') {
            $("#tab-single-cheque").attr("hidden", false);
            $("#bankSlip").attr("hidden", true);
            $("#tab-bank-slip").attr("hidden", true);
        } else if ($(this).val() == '7') {
            $("#bankSlip").attr("hidden", false);
            $("#tab-bank-slip").attr("hidden", false);

            $("#tab-single-cheque").attr("hidden", true);
        } else {
            $("#tab-single-cheque").attr("hidden", true);
            $("#tab-setoff").trigger('click');
            $('#txtChequeRefNo').val('');
            $('#txtChequeNo').val('');
            $('#txtBankCode').val('');
            $('#txtChequeAmount').val('');
            $("#bankSlip").attr("hidden", true);
            $("#tab-bank-slip").attr("hidden", true);
        }
    });


    $('#txtChequeAmount').on('input', function () {
        $('#txtAmount').val($(this).val());
    });




});


function removeMultiCheck(event) {

    if ($(event).text() == 'Remove') {
        $(event).closest("tr").remove();
    }
}



function addMultiChequeRow() {
    var ref_no = $('#txtMultiChequeRefNo').val();
    var cheque_no = $('#txtMultiChequeNo').val();
    var valid_date = $('#txtMultiChequeValidDate').val();
    var amount = $('#txtMultiChequeAmount').val();
    var bank_name = $('#txtMultiChequeBank').val();
    var bank_branch = $('#txtMultiChequeBankBranch').val();
    var remove_btn = '<button type="button" class="btn btn-danger" onclick="removeMultiCheck(this)" style="max-height:30px;">Remove</button>';
    var row = '<tr>';
    row += '<td>';
    row += ref_no;
    row += '</td>';
    row += '<td>';
    row += cheque_no;
    row += '</td>';
    row += '<td>';
    row += valid_date;
    row += '</td>';
    row += '<td>';
    row += amount;
    row += '</td>';
    row += '<td>';
    row += bank_name;
    row += '</td>';
    row += '<td>';
    row += bank_branch;
    row += '</td>';
    row += '<td>';
    row += remove_btn;
    row += '</td>';
    row += '</tr>';

    $('#tblMulyiCheque').append(row);

    $('#txtMultiChequeRefNo').val('');
    $('#txtMultiChequeNo').val('');
    $('#txtMultiChequeValidDate').val('');
    $('#txtMultiChequeAmount').val('');
    $('#txtMultiChequeBank').val('');
    $('#txtMultiChequeBankBranch').val('');
}





function getCustomers() {


    var customer_source = [];
    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getCustomers',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                customer_source = response.data;
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });

    return customer_source;
}




function getCollectors_and_Cashiers() {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getEmployees',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var collectors = response.data;
                console.log(collectors);

                $('#cmbCollector').empty();
                $('#cmbCashier').empty();
                for (var i = 0; i < collectors.length; i++) {
                    var id = collectors[i].employee_id;
                    var name = collectors[i].employee_name;
                    $('#cmbCollector').append('<option value="' + id + '">' + name + '</option>');
                    if (collectors[i].desgination_id == 9) {
                        $('#cmbCashier').append('<option value="' + id + '">' + name + '</option>');
                    }

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




function getBranch() {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getBranch',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var collectors = response.data;
                $('#cmbBranch').empty();
                for (var i = 0; i < collectors.length; i++) {
                    var id = collectors[i].branch_id;
                    var name = collectors[i].branch_name;
                    $('#cmbBranch').append('<option value="' + id + '">' + name + '</option>');
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





function getBank() {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getBank',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var banks = response.data;
                $('#cmbChequeBank').empty();
                for (var i = 0; i < banks.length; i++) {
                    var id = banks[i].bank_id;
                    var name = banks[i].bank_name;
                    $('#cmbChequeBank').append('<option value="' + id + '">' + name + '</option>');
                }

                getBankBranch($('#cmbChequeBank').val());
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}




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
                $('#cmbReceiptMethod').empty();
                for (var i = 0; i < method.length; i++) {
                    var id = method[i].customer_payment_method_id;
                    var name = method[i].customer_payment_method;
                    $('#cmbReceiptMethod').append('<option value="' + id + '">' + name + '</option>');
                }

                getBankBranch($('#cmbReceiptMethod').val());
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}





function getBankBranch(bank_id) {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getBankBranch/' + bank_id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var banks = response.data;
                $('#cmbChequeBankBranch').empty();
                for (var i = 0; i < banks.length; i++) {
                    var id = banks[i].bank_branch_id;
                    var name = banks[i].bank_branch_name;
                    $('#cmbChequeBankBranch').append('<option value="' + id + '">' + name + '</option>');
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




function saveReceipt() {
    total_set_off_Amount = 0;
    if ($('#txtRefNo').val().trim().length === 0) {
        $('#txtRefNo').focus();
        $(window).scrollTop(0);
        $('#txtRefNo').css('borderColor', "red");
        showWarningMessage('Invalied Ref No.');
        return;
    } else {
        $('#txtRefNo').css('borderColor', "#059669");
    }

    if ($('#txtDate').val().trim().length === 0) {
        $('#txtDate').focus();
        $(window).scrollTop(0);
        $('#txtDate').css('borderColor', "red");
        showWarningMessage('Invalied Date');
        return;
    } else {
        $('#txtDate').css('borderColor', "#059669");
    }

    if ($('#txtCustomerID').val().trim().length === 0) {
        $('#txtCustomerID').focus();
        $(window).scrollTop(0);
        $('#txtCustomerID').css('borderColor', "red");
        showWarningMessage('Invalied Customer');
        return;
    } else {
        $('#txtCustomerID').css('borderColor', "#059669");
    }


    if ($('#cmbCollector').val() === null) {
        $('#cmbCollector').focus();
        $(window).scrollTop(0);
        $('#cmbCollector').css('borderColor', "red");
        showWarningMessage('Invalied Collector');
        return;
    } else {
        $('#cmbCollector').css('borderColor', "#059669");
    }


    if ($('#cmbCashier').val() == null) {
        $('#cmbCashier').focus();
        $(window).scrollTop(0);
        $('#cmbCashier').css('borderColor', "red");
        showWarningMessage('Invalied Cashier');
        return;
    } else {
        $('#cmbCashier').css('borderColor', "#059669");
    }



    if ($('#cmbGLAccount').val() === null) {
        $('#cmbGLAccount').focus();
        $(window).scrollTop(0);
        $('#cmbGLAccount').css('borderColor', "red");
        showWarningMessage('Invalied GL Account');
        return;
    } else {
        $('#cmbGLAccount').css('borderColor', "#059669");
    }

    if ($('#cmbReceiptMethod').val() === null) {
        $('#cmbReceiptMethod').focus();
        $(window).scrollTop(0);
        $('#cmbReceiptMethod').css('borderColor', "red");
        showWarningMessage('Invalied Receipt Method');
        return;
    } else {
        $('#cmbReceiptMethod').css('borderColor', "#059669");
    }

    if ($('#cmbBranch').val() === null) {
        $('#cmbBranch').focus();
        $(window).scrollTop(0);
        $('#cmbBranch').css('borderColor', "red");
        showWarningMessage('Invalied Branch');
        return;
    } else {
        $('#cmbBranch').css('borderColor', "#059669");
    }



    if ($('#txtAmount').val().trim().length === 0) {
        $('#txtAmount').val('0.00');
    }

    if ($('#txtDiscount').val().trim().length === 0) {
        $('#txtDiscount').val('0.00');
    }

    if ($('#txtRound_up').val().trim().length === 0) {
        $('#txtRound_up').val('0.00');
    }


    if (parseFloat($('#txtAmount').val()) < 0) {
        $('#txtAmount').focus();
        $('#txtAmount').select();
        $(window).scrollTop(0);
        $('#txtAmount').css('borderColor', "red");
        showWarningMessage('Invalied Amount ' + $('#txtAmount').val());
        return;
    } else {
        $('#txtAmount').css('borderColor', "#059669");
    }

    if (parseFloat($('#txtDiscount').val()) < 0) {
        $('#txtDiscount').focus();
        $('#txtDiscount').select();
        $(window).scrollTop(0);
        $('#txtDiscount').css('borderColor', "red");
        showWarningMessage('Invalied Discount ' + $('#txtDiscount').val());
        return;
    } else {
        $('#txtDiscount').css('borderColor', "#059669");
    }

    var advane = 0;
    if ($('#checkAdvancePayment').is(':checked')) {
        advane = 1;
    }


    /*var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    for (var i = 0; i < rowCount; i++) {
        var setoff = parseFloat($('#txtSetoff' + i).val());
        if (isNaN(setoff)) {
            setoff = 0;
        }
        $('#txtSetoff' + i).val(setoff.toFixed(2));

    }*/


    $('#txtChequeNo').css('borderColor', "#059669");
    $('#txtChequeAmount').css('borderColor', "#059669");
    $('#txtChequeRefNo').css('borderColor', "#059669");
    $('#txtBankCode').css('borderColor', "#059669");
    if ($('#txtChequeRefNo').val().trim().length > 0) {
        if ($('#txtChequeNo').val().trim().length === 0) {
            $('#txtChequeNo').focus();
            $('#txtChequeNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeAmount').val().trim().length === 0) {
            $('#txtChequeAmount').focus();
            $('#txtChequeAmount').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtBankCode').val().trim().length === 0) {
            $('#txtBankCode').focus();
            $('#txtBankCode').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }

    }
    if ($('#txtChequeNo').val().trim().length > 0) {
        if ($('#txtChequeRefNo').val().trim().length === 0) {
            $('#txtChequeRefNo').focus();
            $('#txtChequeRefNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeAmount').val().trim().length === 0) {
            $('#txtChequeAmount').focus();
            $('#txtChequeAmount').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtBankCode').val().trim().length === 0) {
            $('#txtBankCode').focus();
            $('#txtBankCode').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
    }
    if ($('#txtChequeAmount').val().trim().length > 0) {
        if ($('#txtChequeRefNo').val().trim().length === 0) {
            $('#txtChequeRefNo').focus();
            $('#txtChequeRefNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeNo').val().trim().length === 0) {
            $('#txtChequeNo').focus();
            $('#txtChequeNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtBankCode').val().trim().length === 0) {
            $('#txtBankCode').focus();
            $('#txtBankCode').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
    }

    if ($('#cmbReceiptMethod').val() == '3') {

        if ($('#txtBankCode').val().trim().length > 0) {
            if ($('#txtChequeRefNo').val().trim().length === 0) {
                $('#txtChequeRefNo').focus();
                $('#txtChequeRefNo').css('borderColor', "red");
                $("#tabs a[href='#single_cheque']").click();
                showWarningMessage('Invalied Cheque details');
                return;
            }
            if ($('#txtChequeNo').val().trim().length === 0) {
                $('#txtChequeNo').focus();
                $('#txtChequeNo').css('borderColor', "red");
                $("#tabs a[href='#single_cheque']").click();
                showWarningMessage('Invalied Cheque details');
                return;
            }
            if ($('#txtChequeAmount').val().trim().length === 0) {
                $('#txtChequeAmount').focus();
                $('#txtChequeAmount').css('borderColor', "red");
                $("#tabs a[href='#single_cheque']").click();
                showWarningMessage('Invalied Cheque details');
                return;
            }
        }

        if ($('#cmbChequeBank').val() === null) {
            $('#cmbChequeBank').focus();
            $('#cmbChequeBank').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque Bank');
            return;
        }

        if ($('#cmbChequeBankBranch').val() === null) {
            $('#cmbChequeBankBranch').focus();
            $('#cmbChequeBankBranch').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque Bank branch');
            return;
        }

        if ($('#txtChequeAmount').val() < $('#txtAmount').val()) {
            showWarningMessage('Invalied Cheque amount');
            return;
        }
    }



    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    for (var i = 0; i < rowCount; i++) {

        if ($('#txtSetoff' + i).val() == '') {
            //$('#txtSetoff' + i).val(0);
            $("#tab-setoff").trigger('click');
            $('#txtSetoff' + i).focus();
            showWarningMessage('Invalied Setoff');
            return;
        }

    }


    if (REFERANCE_ID == undefined) {
        showWarningMessage('Invalied New Referance No');
        return;
    }
    let amount = parseFloat($('#txtAmount').val().replace(/,/g, '') || 0);
    let discount = parseFloat($('#txtDiscount').val().replace(/,/g, '') || 0);
    let round_up = parseFloat($('#txtRound_up').val().replace(/,/g, '') || 0);
    var receipt_data_set = JSON.stringify(getSetoffTableData());
    if ($('#checkAdvancePayment').prop('checked')) {
        if (((amount - discount) + round_up) < total_set_off_Amount) {
            showWarningMessage("Advance payment should be greater than total set off amount");
            return false;
        } else {
            receiptSaveRequest(amount, discount, round_up, total_set_off_Amount, advane, receipt_data_set);
        }
    } else {
        receiptSaveRequest(amount, discount, round_up, total_set_off_Amount, advane, receipt_data_set);
    }

}

function receiptSaveRequest(amount, discount, round_up, total_set_off_Amount, advane, receipt_data_set) {
    /* if(((amount - discount) + round_up) == total_set_off_Amount){ */

    $.ajax({
        url: '/cb/customer_receipt/saveCustomerReceipt',
        method: 'post',
        enctype: 'multipart/form-data',
        data: {
            "external_number": REFERANCE_ID,
            "customer_id": $('#txtCustomerID').attr('data-id'),
            "customer_code": $('#txtCustomerID').val(),
            "receipt_date": $('#txtDate').val(),
            "collector_id": $('#cmbCollector').val(),
            "cashier_id": $('#cmbCashier').val(),
            "gl_account_id": $('#cmbGLAccount').val(),
            "receipt_method_id": $('#cmbReceiptMethod').val(),
            "amount": $('#txtAmount').val(),
            "discount": $('#txtDiscount').val(),
            "round_up": $('#txtRound_up').val(),
            "branch_id": $('#cmbBranch').val(),
            "advance": advane,
            "receipt_data": receipt_data_set,
            "single_cheque": JSON.stringify(getSingleCheque()),
            "payment_slip": JSON.stringify(getSlip()),
            "your_ref": $('#txtYourReference').val(),
            "total_set_off_amount": total_set_off_Amount

        },
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnAction').prop('disabled', true);
        }, success: function (response) {
            $('#btnAction').prop('disabled', false);
            console.log(response);
            if (response.msg == 'advanceError') {
                showWarningMessage('Setoff off amount mismatch')
            }
            else if (response.duplicate == "duplicate") {
                showWarningMessage("Cheque number duplicated");
            }
            else if (response.data[0] == true && response.data[1] == true && response.data[2] == true && response.data[3] == true && response.data[4] == true) {
                showSuccessMessage('Receipt has been saved');
                location.href = 'customer_receipt';
            } else {
                showErrorMessage('Something went wrong');
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
    /* }else{
       
        console.log("amount : " +amount);
        console.log("discount : " +discount);
        console.log("round_up : " +round_up);
        console.log("total_set_off_Amount : " +total_set_off_Amount);
        
        showWarningMessage('Amount mismatch');
    } */
}

function updateReceipt() {

    if (CUSTOMER_RECEIPT_ID == undefined) {
        showWarningMessage('Invalied Customer Receipt');
        return;
    }

    if ($('#txtRefNo').val().trim().length === 0) {
        $('#txtRefNo').focus();
        $(window).scrollTop(0);
        $('#txtRefNo').css('borderColor', "red");
        showWarningMessage('Invalied Ref No.');
        return;
    } else {
        $('#txtRefNo').css('borderColor', "#059669");
    }

    if ($('#txtDate').val().trim().length === 0) {
        $('#txtDate').focus();
        $(window).scrollTop(0);
        $('#txtDate').css('borderColor', "red");
        showWarningMessage('Invalied Date');
        return;
    } else {
        $('#txtDate').css('borderColor', "#059669");
    }

    if ($('#txtCustomerID').val().trim().length === 0) {
        $('#txtCustomerID').focus();
        $(window).scrollTop(0);
        $('#txtCustomerID').css('borderColor', "red");
        showWarningMessage('Invalied Customer');
        return;
    } else {
        $('#txtCustomerID').css('borderColor', "#059669");
    }


    if ($('#cmbCollector').val() === null) {
        $('#cmbCollector').focus();
        $(window).scrollTop(0);
        $('#cmbCollector').css('borderColor', "red");
        showWarningMessage('Invalied Collector');
        return;
    } else {
        $('#cmbCollector').css('borderColor', "#059669");
    }


    if ($('#cmbCashier').val() == null) {
        $('#cmbCashier').focus();
        $(window).scrollTop(0);
        $('#cmbCashier').css('borderColor', "red");
        showWarningMessage('Invalied Cashier');
        return;
    } else {
        $('#cmbCashier').css('borderColor', "#059669");
    }



    if ($('#cmbGLAccount').val() === null) {
        $('#cmbGLAccount').focus();
        $(window).scrollTop(0);
        $('#cmbGLAccount').css('borderColor', "red");
        showWarningMessage('Invalied GL Account');
        return;
    } else {
        $('#cmbGLAccount').css('borderColor', "#059669");
    }

    if ($('#cmbReceiptMethod').val() === null) {
        $('#cmbReceiptMethod').focus();
        $(window).scrollTop(0);
        $('#cmbReceiptMethod').css('borderColor', "red");
        showWarningMessage('Invalied Receipt Method');
        return;
    } else {
        $('#cmbReceiptMethod').css('borderColor', "#059669");
    }

    if ($('#cmbBranch').val() === null) {
        $('#cmbBranch').focus();
        $(window).scrollTop(0);
        $('#cmbBranch').css('borderColor', "red");
        showWarningMessage('Invalied Branch');
        return;
    } else {
        $('#cmbBranch').css('borderColor', "#059669");
    }



    if ($('#txtAmount').val().trim().length === 0) {
        $('#txtAmount').val('0.00');
    }

    if ($('#txtDiscount').val().trim().length === 0) {
        $('#txtDiscount').val('0.00');
    }

    if ($('#txtRound_up').val().trim().length === 0) {
        $('#txtRound_up').val('0.00');
    }


    if (parseFloat($('#txtAmount').val()) < 0) {
        $('#txtAmount').focus();
        $('#txtAmount').select();
        $(window).scrollTop(0);
        $('#txtAmount').css('borderColor', "red");
        showWarningMessage('Invalied Amount ' + $('#txtAmount').val());
        return;
    } else {
        $('#txtAmount').css('borderColor', "#059669");
    }

    if (parseFloat($('#txtDiscount').val()) < 0) {
        $('#txtDiscount').focus();
        $('#txtDiscount').select();
        $(window).scrollTop(0);
        $('#txtDiscount').css('borderColor', "red");
        showWarningMessage('Invalied Discount ' + $('#txtDiscount').val());
        return;
    } else {
        $('#txtDiscount').css('borderColor', "#059669");
    }

    var advane = 0;
    if ($('#checkAdvancePayment').is(':checked')) {
        advane = 1;
    }


    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    for (var i = 0; i < rowCount; i++) {
        var setoff = parseFloat($('#txtSetoff' + i).val());
        if (isNaN(setoff)) {
            setoff = 0;
        }
        $('#txtSetoff' + i).val(setoff.toFixed(2));

    }


    $('#txtChequeNo').css('borderColor', "#059669");
    $('#txtChequeAmount').css('borderColor', "#059669");
    $('#txtChequeRefNo').css('borderColor', "#059669");
    $('#txtBankCode').css('borderColor', "#059669");
    if ($('#txtChequeRefNo').val().trim().length > 0) {
        if ($('#txtChequeNo').val().trim().length === 0) {
            $('#txtChequeNo').focus();
            $('#txtChequeNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeAmount').val().trim().length === 0) {
            $('#txtChequeAmount').focus();
            $('#txtChequeAmount').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtBankCode').val().trim().length === 0) {
            $('#txtBankCode').focus();
            $('#txtBankCode').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }

    }
    if ($('#txtChequeNo').val().trim().length > 0) {
        if ($('#txtChequeRefNo').val().trim().length === 0) {
            $('#txtChequeRefNo').focus();
            $('#txtChequeRefNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeAmount').val().trim().length === 0) {
            $('#txtChequeAmount').focus();
            $('#txtChequeAmount').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtBankCode').val().trim().length === 0) {
            $('#txtBankCode').focus();
            $('#txtBankCode').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
    }
    if ($('#txtChequeAmount').val().trim().length > 0) {
        if ($('#txtChequeRefNo').val().trim().length === 0) {
            $('#txtChequeRefNo').focus();
            $('#txtChequeRefNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeNo').val().trim().length === 0) {
            $('#txtChequeNo').focus();
            $('#txtChequeNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtBankCode').val().trim().length === 0) {
            $('#txtBankCode').focus();
            $('#txtBankCode').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
    }

    if ($('#txtBankCode').val().trim().length > 0) {
        if ($('#txtChequeRefNo').val().trim().length === 0) {
            $('#txtChequeRefNo').focus();
            $('#txtChequeRefNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeNo').val().trim().length === 0) {
            $('#txtChequeNo').focus();
            $('#txtChequeNo').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
        if ($('#txtChequeAmount').val().trim().length === 0) {
            $('#txtChequeAmount').focus();
            $('#txtChequeAmount').css('borderColor', "red");
            $("#tabs a[href='#single_cheque']").click();
            showWarningMessage('Invalied Cheque details');
            return;
        }
    }





    $.ajax({
        url: '/cb/customer_receipt/updateCustomerReceipt/' + CUSTOMER_RECEIPT_ID,
        method: 'PUT',
        enctype: 'multipart/form-data',
        data: {
            "external_number": $('#txtRefNo').val(),
            "customer_id": $('#txtCustomerID').attr('data-id'),
            "customer_code": $('#txtCustomerID').val(),
            "receipt_date": $('#txtDate').val(),
            "collector_id": $('#cmbCollector').val(),
            "cashier_id": $('#cmbCashier').val(),
            "gl_account_id": $('#cmbGLAccount').val(),
            "receipt_method_id": $('#cmbReceiptMethod').val(),
            "amount": $('#txtAmount').val(),
            "discount": $('#txtDiscount').val(),
            "round_up": $('#txtRound_up').val(),
            "branch_id": $('#cmbBranch').val(),
            "advance": advane,
            "receipt_data": JSON.stringify(getSetoffTableData()),
            "single_cheque": JSON.stringify(getSingleCheque()),

        },
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            if (response.status) {
                showSuccessMessage('Receipt has been updated');
                location.href = 'customer_receipt_list';
            } else {
                showErrorMessage('Something went wrong');
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}

function dataChooserEventListener(event, id, value) {
    $('#txtCustomerName').val(id);
    var customer_id = $('#txtCustomerID').attr('data-id');
    loadSetoffTable(customer_id);

}



function loadSetoffTable(customer_id) {


    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/loadSetoffTable/' + customer_id,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var tableData = response.data;
                $('#tblCustomerReceiptSetoff').empty();
                for (var i = 0; i < tableData.length; i++) {

                    var str_id = "'" + i + "'";
                    var hidden_col = '<label id="lblDataID' + i + '" data-internal_number="' + tableData[i].internal_number + '" data-external_number="' + tableData[i].external_number + '" data-reference_internal_number="' + tableData[i].reference_internal_number + '"  data-reference_external_number="' + tableData[i].reference_external_number + '"  data-reference_document_number="' + tableData[i].reference_document_number + '"></label>'
                    var date = '<label id="lblDate' + i + '">' + tableData[i].trans_date + '</label>';
                    var document_ref_no = '<label id="lblDocumentRefNo' + i + '">' + tableData[i].external_number + '</label>';
                    var description = '<label id="lblDescription' + i + '">' + tableData[i].description + '</label>';
                    var amount = '<label id="lblSetoffAmount' + i + '">' + parseFloat(tableData[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '</label>';
                    var paid_amount = '<label id="lblPaidAmount' + i + '">' + parseFloat(tableData[i].paid_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '</label>';
                    var return_amount = '<label id="lblReturnAmount' + i + '">'+parseFloat(tableData[i].return_amount)+'</label>';
                    var balance = '<label id="lblBalance' + i + '">' + parseFloat(tableData[i].balance_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '</label>';
                    var setoff = '<input type="number" id="txtSetoff' + i + '" class="form-control form-control-sm math-abs"  style="text-align:right;max-width: 80px;" onclick="selectAll(this)"  oninput="setoffAmountOnInput(' + str_id + ')" value="0">';
                    // var setoff = '<input type="number" id="txtSetoff' + i + '" class="form-control form-control-sm math-abs" style="text-align:right; max-width: 80px;" onclick="selectAll(this)" onfocusin="onFocusInChangeValue(this)" onfocusout="onFocusOutChangeValue(this)" oninput="setoffAmountOnInput(' + str_id + ')" value="0">';

                    var age = '<label id="lblAge' + i + '"data-id="' + tableData[i].debtors_ledger_id + '"> - </label>';
                    var chkBox = '<input type="checkbox" id="chkbox' + i + '" onclick="selectRecordToSetOff(this)">'

                    appendReceiptData(hidden_col, date, document_ref_no, description, amount, paid_amount, return_amount, balance, setoff, age, chkBox);


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

/* function selectRecordToSetOff(event) {
    var set_amount = 0
    var txtAmount = $('#txtAmount').val();
    set_amount = forSetOffAmount() || 0;
    if (rcptAmountforcheckbox === null) {
        rcptAmountforcheckbox = parseFloat(txtAmount);
    }
    $('#txtAmount').prop('disabled',true);

    if ($(event).prop('checked')) {
        if (!txtAmount || parseFloat(txtAmount) === 0) {
            showWarningMessage('Please enter a receipt amount');
            $(event.target).prop('checked', false);
            return false;
        } else {
            


           if(set_amount < 0){
            console.log(set_amount);
            
            $(event).prop('checked',false);
           }else{

            var tr = $($($(event).parent()).parent());


            var labelIn8thTd = tr.find('td:eq(7) label').text();

            var setoffbox = $(tr.find('td:eq(8) input[type=number]'));

            if (rcptAmountforcheckbox == 0) {
                showWarningMessage('Insuficent Balance');
                $(event).prop('checked', false);
                return false;
            }
            rcptAmountforcheckbox = rcptAmountforcheckbox - set_amount;

            if (rcptAmountforcheckbox > labelIn8thTd.replace(/,(?=.*\.\d+)/g, '')) {
                setoffbox.val(labelIn8thTd.replace(/,(?=.*\.\d+)/g, ''));
                rcptAmountforcheckbox = rcptAmountforcheckbox - labelIn8thTd.replace(/,(?=.*\.\d+)/g, '')
            } else {
                setoffbox.val(rcptAmountforcheckbox);
                rcptAmountforcheckbox = rcptAmountforcheckbox - rcptAmountforcheckbox
            }
           
           }


        }
    } else {
        var tr = $($($(event).parent()).parent());
        var labelIn8thTd = tr.find('td:eq(7) label').text();
        var setoffbox = $(tr.find('td:eq(8) input[type=number]'));
      
        rcptAmountforcheckbox = parseFloat(rcptAmountforcheckbox)  + set_amount;
       
        setoffbox.val(0);

    }

} */

function selectRecordToSetOff(event) {
    var set_amount = 0
    var txtAmount = $('#txtAmount').val();
    set_amount = forSetOffAmount() || 0;
    var txtAmount = $('#txtAmount').val() || 0;
    var discount = $('#txtDiscount').val() || 0;
    var round_up = $('#txtRound_up').val() || 0;
    var calc = (parseFloat(txtAmount) - parseFloat(discount)) + parseFloat(round_up);
    if (rcptAmountforcheckbox === null) {
        rcptAmountforcheckbox = parseFloat(txtAmount);
    }
    $('#txtAmount').prop('disabled', true);

    if ($(event).prop('checked')) {
        if (!txtAmount || parseFloat(txtAmount) === 0) {
            showWarningMessage('Please enter a receipt amount');
            $(event).prop('checked', false);
            return false;
        } else {
            var tr = $(event).closest('tr');
            var rowBalance = parseFloat(tr.find('td:eq(7) label').text().replace(/,/g, ''));
            var setoffbox = tr.find('td:eq(8) input[type=number]');
            if (rcptAmountforcheckbox <= 0) {
                showWarningMessage("Insufient Balance");
                $(event).prop('checked', false);
            } else if (rcptAmountforcheckbox > (parseFloat(rowBalance) + parseFloat(set_amount))) {
                setoffbox.val(rowBalance);
                console.log(parseFloat(rowBalance) + parseFloat(set_amount));
                rcptAmountforcheckbox = rcptAmountforcheckbox - rowBalance;
            } else if (rcptAmountforcheckbox < (parseFloat(rowBalance) + parseFloat(set_amount))) {
                setoffbox.val(rcptAmountforcheckbox);
                console.log(parseFloat(rowBalance) + parseFloat(set_amount));
                rcptAmountforcheckbox = rcptAmountforcheckbox - rcptAmountforcheckbox
            } else if (rcptAmountforcheckbox == (parseFloat(rowBalance) + parseFloat(set_amount))) {
                setoffbox.val(rcptAmountforcheckbox);
                console.log(parseFloat(rowBalance) + parseFloat(set_amount));
                
                rcptAmountforcheckbox = rcptAmountforcheckbox - rcptAmountforcheckbox
            } else {
                showWarningMessage("Insufient Balance");
                $(event).prop('checked', false);
            }

        }

    } else {
        var tr = $(event).closest('tr');
        var rowBalance = parseFloat(tr.find('td:eq(7) label').text().replace(/,/g, ''));
        var setoffbox = tr.find('td:eq(8) input[type=number]');
        var setoffValue = parseFloat(setoffbox.val().replace(/,/g, '')) || 0;
        rcptAmountforcheckbox += setoffValue;
        setoffbox.val(0);
    }
}



function onFocusInChangeValue(event) {
    var tr = $($($(event).parent()).parent());
    var setoffbox = $(tr.find('td:eq(8) input[type=number]'));
    var setoffValue = parseFloat(setoffbox.val().replace(/,/g, '')) || 0;

    if (rcptAmountforcheckbox != null) {
        rcptAmountforcheckbox - setoffValue;
    }
    console.log(rcptAmountforcheckbox);
}

function onFocusOutChangeValue(event) {
    var tr = $($($(event).parent()).parent());
    var setoffbox = $(tr.find('td:eq(8) input[type=number]'));
    var setoffValue = parseFloat(setoffbox.val().replace(/,/g, '')) || 0;

    if (rcptAmountforcheckbox != null) {
        rcptAmountforcheckbox + setoffValue;
    }
    console.log(rcptAmountforcheckbox);

}


function changeAmount() {

    var txtAmount = $('#txtAmount').val();

    rcptAmountforcheckbox = parseFloat(txtAmount);

    /*  $('#tblCustomerReceiptSetoff tr').each(function() {
         var checkbox = $(this).find('td:last-child input[type="checkbox"]'); 
         var textbox = $(this).find('td:eq(8) input[type="text"]'); 
         checkbox.prop('checked', false);
         textbox.val(0);
     }); */
}


function selectAll(event) {
    $(event).select();

}

function automaticSetoff() {
    if ($('#txtAmount').val().trim().length === 0) {
        //$('#txtAmount').val('0.00');
    }

    if ($('#txtDiscount').val().trim().length === 0) {
        //$('#txtDiscount').val('0.00');
    }

    if ($('#txtRound_up').val().trim().length === 0) {
        //$('#txtRound_up').val('0.00');
    }

    if (parseFloat($('#txtAmount').val()) < 0) {
        $('#txtAmount').focus();
        $('#txtAmount').select();
        $(window).scrollTop(0);
        $('#txtAmount').css('borderColor', "red");
        showWarningMessage('Invalied Amount ' + $('#txtAmount').val());
        return;
    } else {
        $('#txtAmount').css('borderColor', "#059669");
    }

    if (parseFloat($('#txtDiscount').val()) < 0) {
        $('#txtDiscount').focus();
        $('#txtDiscount').select();
        $(window).scrollTop(0);
        $('#txtDiscount').css('borderColor', "red");
        showWarningMessage('Invalied Discount ' + $('#txtDiscount').val());
        return;
    } else {
        $('#txtDiscount').css('borderColor', "#059669");
    }

    $('#btnAction').prop('disabled', false);
    var amount = parseFloat($('#txtAmount').val());
    var discount = parseFloat($('#txtDiscount').val());
    var roundup = parseFloat($('#txtRound_up').val());

    return (amount - discount) + roundup;

}


function setoffAmount(amount, index) {

    var remain_quantity = 0;
    var setoffAmount = parseFloat($('#lblSetoffAmount' + index).text().replace(/,(?=.*\.\d+)/g, ''));
    var paid_amount = parseFloat($('#lblPaidAmount' + index).text().replace(/,(?=.*\.\d+)/g, ''));
    setoffAmount = (setoffAmount - paid_amount);

    if (amount <= setoffAmount) {
        $('#txtSetoff' + index).val(amount.toFixed(2));
    } else {
        remain_quantity = (amount - setoffAmount);
        $('#txtSetoff' + index).val(setoffAmount.toFixed(2));
    }

    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    for (var i = 0; i < rowCount; i++) {

        if (index != i) {
            setoffAmount = parseFloat($('#lblSetoffAmount' + i).text().replace(/,(?=.*\.\d+)/g, ''));
            if (remain_quantity <= setoffAmount) {
                $('#txtSetoff' + i).val(remain_quantity.toFixed(2));
                remain_quantity = 0;
                break;
            } else {
                remain_quantity = (remain_quantity - setoffAmount);
                $('#txtSetoff' + i).val(setoffAmount.toFixed(2));
            }

        }

    }
}




function setoffAmountOnInput(index) {

    var header_amount = automaticSetoff();

    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    var total_amount = 0;
    for (var i = 0; i < rowCount; i++) {
        total_amount += parseFloat($('#lblSetoffAmount' + index).text().replace(/,(?=.*\.\d+)/g, ''));
    }

    var setoff = parseFloat($('#txtSetoff' + index).val());
    if (isNaN(setoff)) {
        setoff = 0;
    }

    var amount = parseFloat($('#lblSetoffAmount' + index).text().replace(/,(?=.*\.\d+)/g, ''));

    if (amount < setoff) {
        $('#btnAction').prop('disabled', true);
        showWarningMessage('insufficient Amount : ' + amount);
        for (var i = 0; i < rowCount; i++) {
            if (index == i) {
                $('#lblSetoffAmount' + i).css('color', "red");
                break;
            }
        }
        return;
    }

    var total_setoff = 0;
    for (var i = 0; i < rowCount; i++) {
        var setoff_val = parseFloat($('#txtSetoff' + i).val());
        if (isNaN(setoff_val)) {
            setoff_val = 0;
        }
        total_setoff += setoff_val;
    }

    //alert(total_setoff);
    if ((header_amount < total_setoff) || (header_amount === 0)) {
        $('#txtAmount').css('borderColor', "red");
        $('#txtDiscount').css('borderColor', "red");
        $('#txtRound_up').css('borderColor', "red");
        $('#btnAction').prop('disabled', true);
        $(window).scrollTop(0);
        showWarningMessage('insufficient Amount : ' + header_amount);
        return;
    }
    $('#txtAmount').css('borderColor', "#059669");
    $('#txtDiscount').css('borderColor', "#059669");
    $('#txtRound_up').css('borderColor', "#059669");

    $('#btnAction').prop('disabled', false);
    for (var i = 0; i < rowCount; i++) {
        $('#lblSetoffAmount' + i).css('color', "black");
    }

}



function getSetoffTableData() {
    var setoffData = [];
    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    for (var i = 0; i < rowCount; i++) {

        if (parseFloat($('#txtSetoff' + i).val().replace(/,(?=.*\.\d+)/g, '')) < 0) {
            $('#txtSetoff' + i).focus();
            showWarningMessage('Invalied Setoff amount..');
            break;
        }
        setoffData.push(JSON.stringify({
            "internal_number": $('#lblDataID' + i).attr('data-internal_number'),
            "external_number": $('#lblDataID' + i).attr('data-external_number'),
            "reference_internal_number": $('#lblDataID' + i).attr('data-reference_internal_number'),
            "reference_external_number": $('#lblDataID' + i).attr('data-reference_external_number'),
            "reference_document_number": $('#lblDocumentRefNo' + i).text(),
            "amount": $('#lblSetoffAmount' + i).text().replace(/,(?=.*\.\d+)/g, ''),
            "paid_amount": $('#lblPaidAmount' + i).text().replace(/,(?=.*\.\d+)/g, ''),
            "return_amount": $('#lblReturnAmount' + i).text().replace(/,(?=.*\.\d+)/g, ''),
            "balance": $('#lblBalance' + i).text().replace(/,(?=.*\.\d+)/g, ''),
            "set_off_amount": $('#txtSetoff' + i).val().replace(/,(?=.*\.\d+)/g, '') || '0',
            "date": $('#lblDate' + i).text(),
            "debtors_ledger_id": $('#lblAge' + i).attr('data-id')
        }));

        total_set_off_Amount = total_set_off_Amount + (parseFloat($('#txtSetoff' + i).val().replace(/,(?=.*\.\d+)/g, '')) || 0);

    }
    console.log(setoffData);

    return setoffData;

}

function forSetOffAmount() {

    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    var _set_off_amount = 0
    for (var i = 0; i < rowCount; i++) {


        _set_off_amount =+ (parseFloat($('#txtSetoff' + i).val().replace(/,(?=.*\.\d+)/g, '')) || 0);


    }

    return _set_off_amount;

}

function getSingleCheque() {
    return {
        "cheque_referenceNo": $('#txtChequeRefNo').val(),
        "cheque_number": $('#txtChequeNo').val(),
        "bank_code": $('#txtBankCode').val(),
        "banking_date": $('#txtChequeValidDate').val(),
        "amount": $('#txtChequeAmount').val(),
        "bank_id": $('#cmbChequeBank').val(),
        "bank_branch_id": $('#cmbChequeBankBranch').val(),
        "cheque_status": "0",
        "cheque_deposit_date": "",
        "cheque_dishonoured_date": ""
    };
}


function getSlip() {
    if ($('#cmbReceiptMethod').val() == 7) {
        if ($('#txtSlipRef').val() == '') {
            showWarningMessage('Please enter reference details');
        } else {
            return {
                "cheque_referenceNo": $('#txtSlipRef').val(),
                "slip_time": $('#tmSliptime').val(),
                "slip_date": $('#dtSLipDate').val(),
            }
        }
    }

}
function newReferanceID(table, doc_number) {
    REFERANCE_ID = newID("/cb/customer_receipt/new_referance_id", table, doc_number);
    $('#txtRefNo').val('New Receipt');
}



function getCustomerReceipt(id) {
    $.ajax({
        url: '/cb/customer_receipt/getCustomerReceipt/' + id,
        type: 'GET',
        cache: false,
        async: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var result = response.data;
            console.log(result);
            $('#txtRefNo').val(result.external_number);
            $('#txtDate').val(result.receipt_date);
            $('#txtCustomerID').val(result.customer_code);
            $('#txtCustomerName').val(result.customer_name);
            $('#txtCustomerID').attr('data-id', result.customer_id);
            $('#cmbCollector').val(result.collector_id);
            $('#cmbCashier').val(result.cashier_id);
            $('#cmbGLAccount').val(result.gl_account_id);
            $('#cmbReceiptMethod').val(result.receipt_method_id);

            $('#txtAmount').val(result.amount);
            $('#txtDiscount').val(result.discount);
            $('#txtRound_up').val(result.round_up);
            $('#cmbBranch').val(result.branch_id);

            if (result.receipt_method_id == '2') {
                $("#tab-single-cheque").attr("hidden", false);
            } else if (result.receipt_method_id == '7') {
                $("#tab-bank-slip").attr("hidden", false);
            }
            var cashier_user = result.cashier_user_id;
            console.log(cashier_user);
            //$('#cmbCashier').val(result.cashier_user_id);

            var receipt_cheque = result.receipt_cheque;
            for (var i = 0; i < receipt_cheque.length; i++) {
                $('#txtChequeRefNo').val(receipt_cheque[i].cheque_referenceNo);
                $('#txtChequeNo').val(receipt_cheque[i].cheque_number);
                $('#txtBankCode').val(receipt_cheque[i].bank_code);
                $('#txtChequeValidDate').val(receipt_cheque[i].banking_date);
                $('#txtChequeAmount').val(receipt_cheque[i].amount);
                $('#cmbChequeBank').val(receipt_cheque[i].bank_id);
                getBankBranch(receipt_cheque[i].bank_id);
                $('#cmbChequeBankBranch').val(receipt_cheque[i].bank_branch_id);

            }



            var receipt_data = result.receipt_data;
            console.log(receipt_data);

            for (var i = 0; i < receipt_data.length; i++) {
                var rtn_amount = receipt_data[i].return_amount;
                if (isNaN(parseFloat(rtn_amount))) {
                    rtn_amount = 0;
                }
                var Balance_ = parseFloat(receipt_data[i].amount) - ((parseFloat(receipt_data[i].paid_amount) + parseFloat(rtn_amount)));
                var str_id = "'" + i + "'";
                var hidden_col = '<label id="lblDataID' + i + '" data-internal_number="' + receipt_data[i].internal_number + '" data-external_number="' + receipt_data[i].external_number + '" data-reference_internal_number="' + receipt_data[i].reference_internal_number + '"  data-reference_external_number="' + receipt_data[i].reference_external_number + '"></label>'
                var date = '<label id="lblDate' + i + '">' + receipt_data[i].date + '</label>';
                var document_ref_no = '<label id="lblDocumentRefNo' + i + '">' + receipt_data[i].reference_external_number + '</label>';
                var description = '<label id="lblDescription' + i + '">Sales Invoice</label>';
                var amount = '<label id="lblSetoffAmount' + i + '">' + parseFloat(receipt_data[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '</label>';
                var paid_amount = '<label id="lblPaidAmount' + i + '">' + parseFloat(receipt_data[i].paid_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '</label>';
                var return_amount = '<label id="lblReturnAmount' + i + '">' + parseFloat(rtn_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '</label>';
                var balance = '<label id="lblBalance' + i + '">' + parseFloat(Balance_).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '</label>';
                var setoff = '<input  id="txtSetoff' + i + '" class="form-control form-control-sm math-abs"  style="text-align:right;max-width: 120px;" oninput="setoffAmountOnInput(' + str_id + ')" value="' + parseFloat(receipt_data[i].set_off_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString() + '" disabled>';
                var age = '<label id="lblAge' + i + '">0.00</label>';
                appendReceiptData(hidden_col, date, document_ref_no, description, amount, paid_amount, return_amount, balance, setoff, age);

            }

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}


function appendReceiptData(hidden_col, date, document_ref_no, description, amount, paid_amount, return_amount, balance, setoff, age, chkBox) {
    var row = '<tr>';
    row += '<td hidden>';
    row += hidden_col;
    row += '</td>';
    row += '<td style="max-width: 100px;">';
    row += date;
    row += '</td>';
    row += '<td style="max-width: 80px;">';
    row += document_ref_no;
    row += '</td>';
    row += '<td style="max-width: 100%;">';
    row += description;
    row += '</td>';
    row += '<td style="text-align:right;max-width: 80px;">';
    row += amount;
    row += '</td>';
    row += '<td style="text-align:right;max-width: 80px;" ' + hidden_columns + '>';
    //row += '<td style="text-align:right;max-width: 80px;" >'; 
    row += paid_amount;
    row += '</td>';
    row += '<td style="text-align:right;max-width: 80px;" ' + hidden_columns + '>';
    //row += '<td style="text-align:right;max-width: 80px;" >';
    row += return_amount;
    row += '</td>';
    row += '<td style="text-align:right;max-width: 80px;" ' + hidden_columns + '>';
    //row += '<td style="text-align:right;max-width: 80px;">';
    row += balance;
    row += '</td>';
    row += '<td style="max-width: 80px;">';
    row += setoff;
    row += '</td>';
    row += '<td style="text-align:right;max-width: 80px;" ' + hidden_columns + '>';
    //row += '<td style="text-align:right;max-width: 80px;">';
    row += age;
    row += '</td>';
    row += '<td style="text-align:right;max-width: 80px;">'
    row += chkBox
    row += '</td>';
    row += '</tr>';

    $('#tblCustomerReceiptSetoff').append(row);

    $('.math-abs').keypress(function (event) {
        // Get the current input value
        var inputValue = $(this).val();

        // Check if the pressed key is a number, decimal point, or backspace
        if (
            (event.which != 46 || inputValue.indexOf('.') != -1) &&
            (event.which < 48 || event.which > 57) &&
            event.which != 8
        ) {
            event.preventDefault(); // Prevent the keypress event
        }
    });
}




function resetSetoffTable() {
    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    for (var i = 0; i < rowCount; i++) {
        $('#txtSetoff' + i).val('0.00');
    }
}



function getAutoSelectBankBranch(bank_code, branch_code) {
    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getAutoSelectBankBranch/' + bank_code + '/' + branch_code,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            $('.select2-single-checque-bank').val(0).trigger('change');
            $('#cmbChequeBankBranch').val(0).trigger('change');
            if (response.status) {
                var bank_branch = response.data;
                console.log(bank_branch);
                var bank_id = bank_branch.bank_id;
                getBankBranch(bank_id);
                var bank_name = bank_branch.bank_name;
                var branch_id = bank_branch.branch_id;
                var branch_name = bank_branch.branch_name;
                $('.select2-single-checque-bank').val(bank_id).trigger('change');
                $('#cmbChequeBankBranch').val(branch_id).trigger('change');
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}


function lockInputsSetoff() {
    $('#txtDiscount').prop("disabled", true);
    $('#txtRound_up').prop("disabled", true);

    var rowCount = $('#tblCustomerReceiptSetoff >tr').length;
    for (var i = 0; i < rowCount; i++) {

        $('#txtSetoff' + i).prop("disabled", true);

    }
}

function dataChooserShowEventListener(event) {

}