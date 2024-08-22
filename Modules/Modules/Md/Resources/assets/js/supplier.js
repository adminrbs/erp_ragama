/* ---------------auto complete------------ */

const AutocompleteInputs = function () {

    // Autocomplete
    const _componentAutocomplete = function () {
        if (typeof autoComplete == 'undefined') {
            console.warn('Warning - autocomplete.min.js is not loaded.');
            return;
        }

        // Demo data
        const autocompleteData = loadSupplierNames();

        // Basic
        const autocompleteBasic = new autoComplete({
            selector: "#txtName",
            data: {
                src: autocompleteData
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: function (event) {
                        const selection = event.detail.selection.value;
                        autocompleteBasic.input.value = selection;
                    }
                }
            }
        });

    };

    // Return objects assigned to module
    
    return {
        init: function () {
            _componentAutocomplete();
        }
        
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    AutocompleteInputs.init();
});

/* -------------end of auto complete------ */



var formData = new FormData();
$(document).ready(function () {

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    getSupplyGroup();
    getSupplierGroup();
    addContactRow(1);
    var Cusid;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var Cusid = param[0].split('=')[1].split('&')[0];
        action = param[0].split('=')[2].split('&')[0];

        if (action == 'edit') {
            $('#btnSave').text('Update');
        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnReset').hide();
        }
        getEachSupplier(Cusid);
        getEachSupplierContact(Cusid);


    }

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
                console.log(result);
                if (result) {
                    if ($('#btnSave').text() == 'Save') {

                        addSupplier()
                    } else if ($('#btnSave').text() == 'Update') {
                        updateSupplier(Cusid);
                        deleteEachContact(Cusid);
                        addContactData(Cusid);
                        closeCurrentTab();
                     $('#frmSupplier')[0].reset();

                    window.opener.location.reload();
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


    });
    



})



//load names to item name text box
function loadSupplierNames() {
    var result = [];
    var data = $('#txtName').val();

    $.ajax({
        url: '/md/loadSupplierNames',
        method: 'GET',
        data: { data: data },
        success: function (data) {
            console.log(data);
            $.each(data, function (index, value) {

                result.push(value.item_Name);
                
            })

        }

    });
    console.log(result);
    return result;

}



//contact table
function addContactRow(id) {

    $('#' + id).text("Remove");
    $('#' + id).attr('class', 'btn btn-danger remove');
    $('#' + id).attr('onclick', '');

    var btn_id = guidGenerator();
    var string_id = "'" + btn_id + "'";
    var newRow = '<tr id="' + id + '">' +
        '<td><input type="text" style=" width: 210px;" class="form-control form-control-sm validate" required></td>' +
        '<td><input type="text" style="width:170px" class="form-control form-control-sm validate" required></td>' +
        '<td><input type="tel" style="width:130px" class="form-control form-control-sm validate" name="numbers" required></td>' +
        '<td><input type="tel" style="width:130px" class="form-control form-control-sm validate" name="numbers" required></td>' +
        '<td><input type="email" style="width:250px" class="form-control form-control-sm validate" id="txtEmail" required></td>' +
        '<td><button id="' + btn_id + '" type="button" class="btn btn-success" id="addRowBtn" onclick="addContactRow(' + string_id + ')">Add</button></td></tr>';

    $('#supplier_contact tbody').append(newRow);

    $(".remove").click(function () {
        $(this).closest("tr").remove();
    });

}

function guidGenerator() {
    var S4 = function () {
        return (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
    };
    return (S4() + S4());
}


//add supplier
function addSupplier() {

    var emailLength = $('#txtEMail').val().length;
    if (emailLength > 0 && $('#txtEMail').hasClass('is-invalid')) {
        showErrorMessage("Please check the email address");
    } else {
        var chkSMSnotification = $('#chkSMSnotification').is(":checked") ? 1 : 0;
        var chkEmailnotification = $('#chkEmailnotification').is(":checked") ? 1 : 0;
        var chkWhatsAppnofification = $('#chkWhatsAppnofification').is(":checked") ? 1 : 0;
        var chkPObySuppliersCode = $('#chkPObySuppliersCode').is(":checked") ? 1 : 0;
        var chkCreditAllowed = $('#chkCreditAllowed').is(":checked") ? 1 : 0;
        var chkPDchequeAllowed = $('#chkPDchequeAllowed').is(":checked") ? 1 : 0;

        formData.append('txtSupplierCode', $('#txtSupplierCode').val());
        formData.append('txtName', $('#txtName').val());
        formData.append('txtAddress', $('#txtAddress').val());
        formData.append('txtMobile', $('#txtMobile').val());
        formData.append('txtFixed', $('#txtFixed').val());
        formData.append('txtEMail', $('#txtEMail').val());
        formData.append('txtAlertcreditAmountLimit', $('#txtAlertcreditAmountLimit').val());
        formData.append('txtHoldcreditamountlimit', $('#txtHoldcreditamountlimit').val());
        formData.append('txtAlertcreditperiodlimit', $('#txtAlertcreditperiodlimit').val());
        formData.append('txtHoldcreditperiodlimit', $('#txtHoldcreditperiodlimit').val());
        formData.append('txtPDchequeamountlimit', $('#txtPDchequeamountlimit').val());
        formData.append('txtMaximumPdchequeperiod', $('#txtMaximumPdchequeperiod').val());
        formData.append('chkSMSnotification', chkSMSnotification);
        formData.append('chkEmailnotification', chkEmailnotification);
        formData.append('chkWhatsAppnofification', chkWhatsAppnofification);
        formData.append('chkPObySuppliersCode', chkPObySuppliersCode);
        formData.append('txtGooglemaplink', $('#txtGooglemaplink').val());
        formData.append('txtLicense', $('#txtLicense').val());
        formData.append('cmbSupplierStatus', $('#cmbSupplierStatus').val());
        formData.append('chkCreditAllowed', chkCreditAllowed);
        formData.append('chkPDchequeAllowed', chkPDchequeAllowed);
        formData.append('txtnote', $('#txtnote').val());
        formData.append('cmbSupplierGroup', $('#cmbSupplierGroup').val());
        formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());


        $.ajax({
            url: '/md/addSupplier',
            method: 'POST',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSave').prop('disabled', true);
            },
            success: function (response) {
                console.log(response);

                var id = response.primaryKey;

                var status = response.status;

                if (status) {
                    showSuccessMessage("Successfully saved");
                    addContactData(id)

                    $('#frmSupplier')[0].reset();


                } else {
                    showErrorMessage("Something went wrong");

                }

            }, error: function (data) {

            }, complete: function () {
                $('#btnSave').prop('disabled', false);
            }
        });

    }
}

function addContactData(id) {

    var contactData = [];
    var table = document.getElementById('supplier_contact'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, customerId;

    for (i = 0, j = rows.length; i < j; ++i) {
        cells = rows[i].getElementsByTagName('td');
        if (!cells.length) {
            continue;
        }

        var bool = true;
        for (var i2 = 0; i2 < cells.length - 1; i2++) {
            if (cells[i2].childNodes[0].value === "") {
                bool = false;
                break;
            }   

        }

        var data = {

            "name": cells[0].childNodes[0].value,
            "designation": cells[1].childNodes[0].value,
            "mobile": cells[2].childNodes[0].value,
            "fixed": cells[3].childNodes[0].value,
            "email": cells[4].childNodes[0].value,

        };

        if (bool) {
            contactData.push(JSON.stringify(data));
        }

        console.log(contactData);

    }
   
    console.log(contactData);
    $.ajax({
        url: '/md/addSupplierContact/' + id,
        type: 'POST',
        data: {
            'contact': contactData,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 800000,
        success: function (response) {
            console.log(response);

        }, error: function (data) {
            console.log(data)
        }

    })

}

//get each supplier to update
function getEachSupplier(id) {
    $.ajax({
        url: '/md/getEachSupplier/' + id,
        type: 'get',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (customerData) {
            var res = customerData.data;
            console.log(res);

            $('#txtSupplierCode').val(res.supplier_code);
            $('#txtName').val(res.supplier_name);
            $('#txtAddress').val(res.primary_address);
            $('#txtMobile').val(res.primary_mobile_number);
            $('#txtFixed').val(res.primary_fixed_number);
            $('#txtEMail').val(res.primary_email);
            $('#txtGooglemaplink').val(res.google_map_link);
            $('#cmbSupplierGroup').val(res.supplier_group_id);
            $('#chkPObySuppliersCode').prop('checked', res.supplier_product_code == 1);
            $('#txtAlertcreditAmountLimit').val(res.credit_amount_alert_limit);
            $('#txtHoldcreditamountlimit').val(res.credit_amount_hold_limit);
            $('#txtHoldcreditperiodlimit').val(res.credit_period_hold_limit);
            $('#txtPDchequeamountlimit').val(res.pd_cheque_limit);
            $('#cmbCreditcontrolbytype').val(res.credit_control_type);
            $('#chkSMSnotification').prop('checked', res.sms_notification == 1);
            $('#chkWhatsAppnofification').prop('checked', res.whatapp_notification == 1);
            $('#chkEmailnotification').prop('checked', res.email_notification == 1);
            $('#txtMaximumPdchequeperiod').val(res.pd_cheque_max_period);
            $('#cmbCustomergrade').val(res.customer_grade_id);
            $('#txtAlertcreditperiodlimit').val(res.credit_period_alert_limit);
            $('#txtLicense').val(res.license_no);
            $('#cmbSupplierStatus').val(res.supplier_status);
            $('#chkCreditAllowed').prop('checked', res.credit_allowed == 1);
            $('#chkPDchequeAllowed').prop('checked', res.pd_cheque_allowed == 1);
            $('#txtnote').val(res.note);
            $('#cmbSupplyGroup').val(res.supply_group_id);

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

//get each contact data
function getEachSupplierContact(id) {
    $.ajax({
        url: '/md/getEachSupplierContact/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var contactID;
            var contactDetailsRows = '';
            $.each(data, function (index, value) {
                contactID = value.supplier_contacts_id;
                contactDetailsRows += '<tr>' +
                    '<td><input type="text" style=" width: 200px;" class="form-control form-control-sm validate" required value="' + value.contact_person + '"></td>' +
                    '<td><input type="text" style="width:150px" class="form-control form-control-sm validate" required value="' + value.designation + '"></td>' +
                    '<td><input type="tel" style="width:100px" class="form-control form-control-sm validate" name="numbers" required value="' + value.mobile + '"></td>' +
                    '<td><input type="tel" style="width:100px" class="form-control form-control-sm validate" name="numbers" required value="' + value.fixed + '"></td>' +
                    '<td><input type="email" style="width:180px" class="form-control form-control-sm validate" id="txtEmail" required value="' + value.email + '"></td>' +
                    '<td><button type="button" class="btn btn-danger remove" id="btnRemove">Remove</button></td>' +
                    '</tr>';
            });
            $('#supplier_contact tbody').html(contactDetailsRows);
            addContactRow(id);
            $(".remove").click(function () {

                $(this).closest("tr").remove();

            });

        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}

//update suplier
function updateSupplier(id) {
    var emailLength = $('#txtEMail').val().length;
    if (emailLength > 0 && $('#txtEMail').hasClass('is-invalid')) {
        showErrorMessage("Please check the email address");
    } else {
        var chkSMSnotification = $('#chkSMSnotification').is(":checked") ? 1 : 0;
        var chkEmailnotification = $('#chkEmailnotification').is(":checked") ? 1 : 0;
        var chkWhatsAppnofification = $('#chkWhatsAppnofification').is(":checked") ? 1 : 0;
        var chkPObySuppliersCode = $('#chkPObySuppliersCode').is(":checked") ? 1 : 0;
        var chkCreditAllowed = $('#chkCreditAllowed').is(":checked") ? 1 : 0;
        var chkPDchequeAllowed = $('#chkPDchequeAllowed').is(":checked") ? 1 : 0;

        formData.append('txtSupplierCode', $('#txtSupplierCode').val());
        formData.append('txtName', $('#txtName').val());
        formData.append('txtAddress', $('#txtAddress').val());
        formData.append('txtMobile', $('#txtMobile').val());
        formData.append('txtFixed', $('#txtFixed').val());
        formData.append('txtEMail', $('#txtEMail').val());
        formData.append('txtAlertcreditAmountLimit', $('#txtAlertcreditAmountLimit').val());
        formData.append('txtHoldcreditamountlimit', $('#txtHoldcreditamountlimit').val());
        formData.append('txtAlertcreditperiodlimit', $('#txtAlertcreditperiodlimit').val());
        formData.append('txtHoldcreditperiodlimit', $('#txtHoldcreditperiodlimit').val());
        formData.append('txtPDchequeamountlimit', $('#txtPDchequeamountlimit').val());
        formData.append('txtMaximumPdchequeperiod', $('#txtMaximumPdchequeperiod').val());
        formData.append('chkSMSnotification', chkSMSnotification);
        formData.append('chkEmailnotification', chkEmailnotification);
        formData.append('chkWhatsAppnofification', chkWhatsAppnofification);
        formData.append('chkPObySuppliersCode', chkPObySuppliersCode);
        formData.append('txtGooglemaplink', $('#txtGooglemaplink').val());
        formData.append('txtLicense', $('#txtLicense').val());
        formData.append('cmbSupplierStatus', $('#cmbSupplierStatus').val());
        formData.append('chkCreditAllowed', chkCreditAllowed);
        formData.append('chkPDchequeAllowed', chkPDchequeAllowed);
        formData.append('txtnote', $('#txtnote').val());
        formData.append('cmbSupplierGroup', $('#cmbSupplierGroup').val());
        formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());

        $.ajax({
            url: '/md/updateSupplier/' + id,
            method: 'POST',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSave').prop('disabled', true);
            },
            success: function (response) {
                console.log(response);

                var id = response.primaryKey;

                var status = response.status;

                if (status) {
                    showSuccessMessage("Successfully saved");
                    

                 

                } else {
                    showErrorMessage("Something went wrong");
                    console.log(status);

                }

            }, error: function (data) {

            }, complete: function () {
                $('#btnSave').prop('disabled', false);
            }
        });

    }
}

//get supply group to combo box
function getSupplyGroup() {
    $.ajax({
        url: '/md/getSupplyGroup',
        method: 'GET',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbSupplyGroup').append('<option value="' + value.supply_group_id + '">' + value.supply_group + '</option>');

            })

        },
    })
}

//get supplier group
function getSupplierGroup() {
    $.ajax({
        url: '/md/getSupplierGroup',
        method: 'GET',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbSupplierGroup').append('<option value="' + value.supplier_group_id + '">' + value.supplier_group_name + '</option>');

            })

        },
    })

}

//delete contact
function deleteEachContact(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteSupplierContact/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {

            console.log(response);
            


        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000); 
}
