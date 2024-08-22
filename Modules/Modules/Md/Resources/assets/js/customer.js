let dropzoneSingle = undefined;
// array for load names


// Setup module
// ------------------------------

const AutocompleteInputs = function () {




    // Autocomplete
    const _componentAutocomplete = function () {
        if (typeof autoComplete == 'undefined') {
            console.warn('Warning - autocomplete.min.js is not loaded.');
            return;
        }

        // Demo data
        const autocompleteData = loadNames();

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



        // External empty array to save search results
        let history = [];
        const autocompleteRecent = new autoComplete({
            selector: "#autocomplete_recent",
            data: {
                src: autocompleteData
            },
            resultItem: {
                highlight: true
            },
            resultsList: {
                element: (list) => {
                    const recentSearch = history.reverse();
                    const historyLength = recentSearch.length;

                    // Check if there are recent searches
                    if (historyLength) {
                        const historyBlock = document.createElement("li");
                        historyBlock.classList.add('pe-none', 'border-bottom', 'pt-0', 'pb-2', 'mb-2');
                        historyBlock.innerHTML = '<div class="fw-semibold">Recent Searches</div>';
                        // Limit displayed searched to only last "2"
                        recentSearch.slice(0, 2).forEach((item) => {
                            const recentItem = document.createElement("div");
                            recentItem.classList.add('text-muted', 'mt-2')
                            recentItem.innerHTML = item;
                            historyBlock.append(recentItem);
                        });



                        list.prepend(historyBlock);
                    }
                }
            },
            events: {
                input: {
                    selection(event) {
                        const feedback = event.detail;
                        const input = autocompleteRecent.input;
                        // Get selected Value
                        const selection = feedback.selection.value;
                        // Add selected value to "history" array
                        history.push(selection);

                        autocompleteRecent.input.value = selection;
                    }
                }
            }
        });

        // Start with

    };

    // Return objects assigned to module
    return {
        init: function () {
            _componentAutocomplete();
        }
    }
}();


// Initialize module

document.addEventListener('DOMContentLoaded', function () {
    AutocompleteInputs.init();
});


var formData = new FormData();
$(document).ready(function () {
    //getTownId(1)
    getDeliveryRoutes();
    loadPamentTerm();
    getMarketingRoutes();
    //increasing the size of textarea
    $('#txtnote').on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });



    loadNames();
    $('#frmCustomer').trigger("reset"); // reset on refresh
    $('.select2').select2();



    $('input').on('focus', function () {
        lastFocusedInput = this;
    });


    //avoidng spaces
    $("#txtCustomerCode").on("keydown", function (event) {
        if (event.keyCode === 32) {
            return false;
        }


    });


    document.addEventListener('DOMContentLoaded', function () {
        AutocompleteInputs.init();
    });

   
    getCustomerGroup();
    getCustomerGrade();
    getDistrictId();
    

    $('#cmbDistrict').change(function () {

        var selectedValue = $(this).val();
        getTownId(selectedValue);
        getTownNonAdmin(selectedValue);

    });
    $('#cmbDistrict').trigger('change');


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
        getEachCustomer(Cusid);
        getEachCustomerContact(Cusid);
        getEachDeliveryPoint(Cusid);

    }

    const bbForm = document.querySelector('#bootbox_form');

    if (bbForm) {
        bbForm.addEventListener('click', function () {

            // create a new form element to hold the Dropzone
            const dropzoneForm = document.createElement('form');
            const dropzoneDiv = document.createElement('div');
            dropzoneDiv.classList.add('dropzone');
            dropzoneDiv.setAttribute('id', 'dropzone_single');
            dropzoneDiv.setAttribute('action', '#');
            dropzoneForm.appendChild(dropzoneDiv);

            bootbox.dialog({
                title: 'Upload a file.',
                message: '<form action="{{route("upload")}}">' +
                    '<label class="col-md-4 col-form-label">Description</label>' +
                    '<textarea rows="3" class="form-control" style="margin-bottom:5px;"></textarea>' +
                    '<div class="row mb-3">' +
                    '<div class="col-md-8">' +
                    dropzoneForm.innerHTML + // add the Dropzone form element here
                    '</div>' +
                    '</div>' +
                    '</form>',
                buttons: {
                    Upload: {
                        label: 'Upload',
                        className: 'btn-success',
                        callback: function () {
                            // manually process the Dropzone queue
                            //dropzoneSingle.processQueue();
                            return false;
                        }
                    },
                    Cancel: {
                        label: 'Cancel',
                        className: 'btn-danger',
                        callback: function () {
                            // remove any uploaded files
                            dropzoneSingle.removeAllFiles(true);
                            return true;
                        }
                    }
                }
            });

            // initialize the Dropzone after the modal has been created
            dropzoneSingle = new Dropzone("#dropzone_single", {

                paramName: "file",
                maxFilesize: 1,
                maxFiles: 1,
                acceptedFiles: ".jpg,.jpeg,.png,.pdf",
                dictDefaultMessage: 'Drop file to upload <span>or CLICK</span>',
                autoProcessQueue: false,
                addRemoveLinks: true,
                init: function () {
                    this.on('addedfile', function (file) {

                        if (this.fileTracker) {
                            this.removeFile(this.fileTracker);
                        }
                        this.fileTracker = file;
                    });
                    this.on("success", function (file, responseText) {
                        console.log(responseText);
                    });
                    this.on("complete", function (file) {

                        this.removeAllFiles(true);
                        console.log(file);
                    });
                }
            });

        });
    }


    $('#txtName').on('keyup', function () {
        //   loadNames(); 
    })

    $('#btnSave').on('click', function (event) {

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

                        addCustomer();
                    } else if ($('#btnSave').text() == 'Update') {
                        UpdateCustomer(Cusid);
                        deleteEachContact(Cusid);
                        AddContactData(Cusid);
                        deleteDeliveryPoint(Cusid);
                        addDeliveryPoint(Cusid);
                        closeCurrentTab();
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


    $('#frmCustomer').submit(function (e) {
        e.preventDefault();
        //  dropzoneSingle.processQueue();
    });


    $('#btnReset').on('click', function () {
        $('.validation-invalid-label').empty();
        $('#frmCustomer').trigger('reset');
    });

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    /**-------------------------------------------------------------------------------------------- */
    addContactRow(0);
    addDelivaerypointRow(0);

});

/* function redirectToCustomerList() {
    setTimeout(function () {
        window.location.href = '/customerList';
    }, 1000); // delay of 5000 milliseconds (5 seconds)
} */

function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);
}


//add contact row
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

    $('#customer_contact tbody').append(newRow);

    $(".remove").click(function () {
        $(this).closest("tr").remove();
    });

}


//add delivery point row
function addDelivaerypointRow(id) {

    $('#' + id).text("Remove");
    $('#' + id).attr('class', 'btn btn-danger remove');
    $('#' + id).attr('onclick', '');

    var btn_id = guidGenerator();
    var string_id = "'" + btn_id + "'";
    var newDeliverpointRow = '<tr id="' + id + '">' +
        '<td><input type="text" style=" width: 200px;" class="form-control form-control-sm validate" required></td>' +
        '<td><textarea rows="3" style="width: 200px;" class="form-control" required></textarea></td>' +
        '<td><input type="tel" style=" width: 130px;" class="form-control form-control-sm validate" name="numbers"></td>' +
        '<td><input type="tel" style=" width: 130px;" class="form-control form-control-sm validate" name="numbers"></td>' +
        '<td><textarea rows="3" style="width: 200px;" class="form-control" required></textarea></td>' +
        '<td><input type="text" style=" width: 200px;" class="form-control form-control-sm validate" required></td>' +
        '<td><button id="' + btn_id + '" type="button" class="btn btn-success" id="addDeliverypointbtn" onclick="addDelivaerypointRow(' + string_id + ')">Add</button></td></tr>';

    $('#customer_delivery_points tbody').append(newDeliverpointRow);

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


function addCustomer() {
    if (!validateInput()) {
        return;
    }
    if(containsValue('customers', 'customer_name', $('#txtName').val())){
        showWarningMessage('Customer name already exist');
        return;
     }
    var emailLength = $('#txtEMail').val().length;
    if (emailLength > 0 && $('#txtEMail').hasClass('is-invalid')) {
        showErrorMessage("Please check the email address");
    } else {
        var text = $('#cmbTown_onAdmin').find(":selected");
        var text_ = text.text();
        if(text_ == "Not Applicabled" || text_.length < 1){
            $('#cmbTown_onAdmin').addClass('is-invalid');
            showWarningMessage("Please select a town");
        }else{

        
        var chkSMSnotification = $('#chkSMSnotification').is(":checked") ? 1 : 0;
        var chkEmailnotification = $('#chkEmailnotification').is(":checked") ? 1 : 0;
        var chkWhatsAppnofification = $('#chkWhatsAppnofification').is(":checked") ? 1 : 0;
        var chkDelivertoprimaryaddess = $('#chkDelivertoprimaryaddess').is(":checked") ? 1 : 0;
        var chkCreditAllowed = $('#chkCreditAllowed').is(":checked") ? 1 : 0;
        var chkPDchequeAllowed = $('#chkPDchequeAllowed').is(":checked") ? 1 : 0;
       // var chkFreeofferAllowed = $('#chkFreeofferAllowed').is(":checked") ? 1 : 0;
        var chkPromotionAllowed = $('#chkPromotionAllowed').is(":checked") ? 1 : 0;

        formData.append('txtCustomerCode', $('#txtCustomerCode').val());
        formData.append('txtName', $('#txtName').val());
        formData.append('txtAddress', $('#txtAddress').val());
        formData.append('txtMobile', $('#txtMobile').val());
        formData.append('txtFixed', $('#txtFixed').val());
        formData.append('txtEMail', $('#txtEMail').val());
        formData.append('txtAlertcreaditamountlimit', $('#txtAlertcreaditamountlimit').val());
        formData.append('txtHoldcreditamountlimit', $('#txtHoldcreditamountlimit').val());
        formData.append('txtAlertcreditperiodlimit', $('#txtAlertcreditperiodlimit').val());
        formData.append('txtHoldcreditperiodlimit', $('#txtHoldcreditperiodlimit').val());
        formData.append('txtPDchequeamountlimit', $('#txtPDchequeamountlimit').val());
        formData.append('txtMaximumPdchequeperiod', $('#txtMaximumPdchequeperiod').val());
        formData.append('cmbDistrict', $('#cmbDistrict').val());
        formData.append('cmbTown', $('#cmbTown').val());
        formData.append('cmbCustomergroup', $('#cmbCustomergroup').val());
        formData.append('cmbCustomergrade', $('#cmbCustomergrade').val());
        formData.append('cmbCreditcontrolbytype', $('#cmbCreditcontrolbytype').val());
        formData.append('chkSMSnotification', chkSMSnotification);
        formData.append('chkEmailnotification', chkEmailnotification);
        formData.append('chkWhatsAppnofification', chkWhatsAppnofification);
        formData.append('chkDelivertoprimaryaddess', chkDelivertoprimaryaddess);
        formData.append('txtGooglemaplink', $('#txtGooglemaplink').val());
        formData.append('txtLicense', $('#txtLicense').val());
        formData.append('cmbCustomerStatus', $('#cmbCustomerStatus').val());
        formData.append('chkCreditAllowed', chkCreditAllowed);
        formData.append('chkPDchequeAllowed', chkPDchequeAllowed);
        formData.append('txtnote', $('#txtnote').val());
        formData.append('chkFreeofferAllowed', $('#cmbFreeOffer').val());
        formData.append('chkPromotionAllowed', chkPromotionAllowed);
        formData.append('cmbTown_onAdmin', $('#cmbTown_onAdmin').val());
        formData.append('cmbDeliveryRoutes', $('#cmbDeliveryRoutes').val());
        formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
        formData.append('cmbMarketingRoutes', $('#cmbMarketingRoutes').val());

        $.ajax({
            url: '/md/CustomerController/saveCustomer',
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
                    AddContactData(id);
                    addDeliveryPoint(id);
                    $('#frmCustomer')[0].reset();
                    /* resetForm();  */

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



}

//district getting function
function getDistrictId() {
    $.ajax({
        url: '/md/getDistrictId',
        method: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbDistrict').append('<option value="' + item.district_id + '">' + item.district_name + '</option>');
            });
            $('#cmbDistrict').change();
        }
    });


}

//town getting function
function getTownId(id) {
   
    $('#cmbTown').empty();
    $.ajax({
        url: '/md/getTownId/' + id,
        method: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbTown').append('<option value="' + item.town_id + '">' + item.town_name + '</option>');
            });

            $('#cmbTown').change();

        }
    })
}

function getCustomerGroup() {
    $.ajax({
        url: '/md/getCustomerGroupid',
        method: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbCustomergroup').append('<option value="' + item.customer_group_id + '">' + item.group + '</option>')
            })
        }
    })
}

function getCustomerGrade() {
    $.ajax({
        url: '/md/getCustomerGradeId',
        method: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbCustomergrade').append('<option value="' + item.customer_grade_id + '">' + item.grade + '</option>')
            })
        }
    })
}


function AddContactData(id) {

    var contactData = [];
    var table = document.getElementById('customer_contact'),
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
        url: '/md/saveContact/' + id,
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
};

function addDeliveryPoint(id) {
    var deliveryPointDetails = [];
    var table = document.getElementById('customer_delivery_points'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, deliveryPointId;

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
            "destination": cells[0].childNodes[0].value,
            "address": cells[1].childNodes[0].value,
            "mobile": cells[2].childNodes[0].value,
            "fixed": cells[3].childNodes[0].value,
            "instruction": cells[4].childNodes[0].value,
            "google_map_link": cells[5].childNodes[0].value
        };
        if (bool) {
            deliveryPointDetails.push(JSON.stringify(data));
        }
    }


    $.ajax({
        url: '/md/addDeliveryPoint/' + id,
        type: 'POST',
        data: {
            'deliverPoints': deliveryPointDetails,
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

function getEachCustomer(id) {
    $.ajax({
        url: '/md/getCustomer/' + id,
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

            getTownId(res.disctrict_id);

            $('#txtCustomerCode').val(res.customer_code);
            $('#txtName').val(res.customer_name);
            $('#txtAddress').val(res.primary_address);
            $('#txtMobile').val(res.primary_mobile_number);
            $('#txtFixed').val(res.primary_fixed_number);
            $('#txtEMail').val(res.primary_email);
            $('#cmbDistrict').val(res.disctrict_id).trigger('change');
            $('#cmbTown').val(res.town_id);
            $('#txtGooglemaplink').val(res.google_map_link);
            $('#cmbCustomergroup').val(res.customer_group_id);
            $('#chkDelivertoprimaryaddess').prop('checked', res.deliver_primary_address == 1);
            $('#txtAlertcreaditamountlimit').val(res.credit_amount_alert_limit);
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
            $('#cmbCustomerStatus').val(res.customer_status);
            $('#chkCreditAllowed').prop('checked', res.credit_allowed == 1);
            $('#chkPDchequeAllowed').prop('checked', res.pd_cheque_allowed == 1);
            $('#txtnote').val(res.note);
            $('#cmbFreeOffer').val(res.free_offer_allowed);
            $('#chkPromotionAllowed').prop('checked', res.promotion_allowed == 1);
            $('#cmbTown_onAdmin').val(res.town).trigger('change');
            $('#cmbDeliveryRoutes').val(res.route_id).trigger('change');
            $('#cmbPaymentTerm').val(res.payment_term_id).trigger('change');
            $('#cmbMarketingRoutes').val(res.marketing_route_id).trigger('change');
           



        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });


};

function UpdateCustomer(id) {
    if (!validateInput()) {
        return;
    }
    var emailLength = $('#txtEMail').val().length;
    if (emailLength > 0 && $('#txtEMail').hasClass('is-invalid')) {
        showErrorMessage("Please check the email address");
    } else {

        var chkSMSnotification = $('#chkSMSnotification').is(":checked") ? 1 : 0;
        var chkEmailnotification = $('#chkEmailnotification').is(":checked") ? 1 : 0;
        var chkWhatsAppnofification = $('#chkWhatsAppnofification').is(":checked") ? 1 : 0;
        var chkDelivertoprimaryaddess = $('#chkDelivertoprimaryaddess').is(":checked") ? 1 : 0;
        var chkCreditAllowed = $('#chkCreditAllowed').is(":checked") ? 1 : 0;
        var chkPDchequeAllowed = $('#chkPDchequeAllowed').is(":checked") ? 1 : 0;
      //  var chkFreeofferAllowed = $('#chkFreeofferAllowed').is(":checked") ? 1 : 0;
        var chkPromotionAllowed = $('#chkPromotionAllowed').is(":checked") ? 1 : 0;

        formData.append('txtCustomerCode', $('#txtCustomerCode').val());
        formData.append('txtName', $('#txtName').val());
        formData.append('txtAddress', $('#txtAddress').val());
        formData.append('txtMobile', $('#txtMobile').val());
        formData.append('txtFixed', $('#txtFixed').val());
        formData.append('txtEMail', $('#txtEMail').val());
        formData.append('txtAlertcreaditamountlimit', $('#txtAlertcreaditamountlimit').val());
        formData.append('txtHoldcreditamountlimit', $('#txtHoldcreditamountlimit').val());
        formData.append('txtAlertcreditperiodlimit', $('#txtAlertcreditperiodlimit').val());
        formData.append('txtHoldcreditperiodlimit', $('#txtHoldcreditperiodlimit').val());
        formData.append('txtPDchequeamountlimit', $('#txtPDchequeamountlimit').val());
        formData.append('txtMaximumPdchequeperiod', $('#txtMaximumPdchequeperiod').val());
        formData.append('cmbDistrict', $('#cmbDistrict').val());
        formData.append('cmbTown', $('#cmbTown').val());
        formData.append('cmbCustomergroup', $('#cmbCustomergroup').val());
        formData.append('cmbCustomergrade', $('#cmbCustomergrade').val());
        formData.append('cmbCreditcontrolbytype', $('#cmbCreditcontrolbytype').val());
        formData.append('chkSMSnotification', chkSMSnotification);
        formData.append('chkEmailnotification', chkEmailnotification);
        formData.append('chkWhatsAppnofification', chkWhatsAppnofification);
        formData.append('chkDelivertoprimaryaddess', chkDelivertoprimaryaddess);
        formData.append('txtGooglemaplink', $('#txtGooglemaplink').val());
        formData.append('txtLicense', $('#txtLicense').val());
        formData.append('cmbCustomerStatus', $('#cmbCustomerStatus').val());
        formData.append('chkCreditAllowed', chkCreditAllowed);
        formData.append('chkPDchequeAllowed', chkPDchequeAllowed);
        formData.append('txtnote', $('#txtnote').val());
        formData.append('chkFreeofferAllowed', $('#cmbFreeOffer').val());
        formData.append('chkPromotionAllowed', chkPromotionAllowed);
        formData.append('cmbTown_onAdmin', $('#cmbTown_onAdmin').val());
        formData.append('cmbDeliveryRoutes', $('#cmbDeliveryRoutes').val());
        formData.append('cmbPaymentTerm', $('#cmbPaymentTerm').val());
        formData.append('cmbMarketingRoutes', $('#cmbMarketingRoutes').val());

        $.ajax({
            url: '/md/updateCustomer/' + id,
            method: 'POST',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            },
            success: function (response) {
                console.log(response);
                var status = response.status;
                if (status) {
                    showSuccessMessage("Successfully updated");
                    $('#frmCustomer')[0].reset();
                    window.opener.location.reload();

                } else {
                    showErrorMessage("Something went wrong")
                }


            }, error: function (data) {

                console.log(data.responseText)

            }, complete: function () {

            }
        });
    }

}

function getEachCustomerContact(id) {
    $.ajax({
        url: '/md/getEachContactData/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var contactID;
            var contactDetailsRows = '';
            $.each(data, function (index, value) {
                contactID = value.customer_contacts_id;
                contactDetailsRows += '<tr>' +
                    '<td><input type="text" style=" width: 200px;" class="form-control form-control-sm validate" required value="' + value.contact_person + '"></td>' +
                    '<td><input type="text" style="width:150px" class="form-control form-control-sm validate" required value="' + value.designation + '"></td>' +
                    '<td><input type="tel" style="width:100px" class="form-control form-control-sm validate" name="numbers" required value="' + value.mobile + '"></td>' +
                    '<td><input type="tel" style="width:100px" class="form-control form-control-sm validate" name="numbers" required value="' + value.fixed + '"></td>' +
                    '<td><input type="email" style="width:180px" class="form-control form-control-sm validate" id="txtEmail" required value="' + value.email + '"></td>' +
                    '<td><button type="button" class="btn btn-danger remove" id="btnRemove">Remove</button></td>' +
                    '</tr>';
            });
            $('#customer_contact tbody').html(contactDetailsRows);
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

function deleteEachContact(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteCustomerContact/' + id,
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


function deleteDeliveryPoint(id) {
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteDeliveryPoint/' + id,
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

function getEachDeliveryPoint(id) {
    $.ajax({
        url: '/md/getEachDeliveryPoint/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var deliveryPointRows = '';
            $.each(data, function (index, value) {
                deliveryPointRows += '<tr>' +
                    '<td><input type="text" style="width:200px;" class="form-control form-control-sm validate" required value="' + value.destination + '"></td>' +
                    '<td><input type="text" style="width:150px" class="form-control form-control-sm validate" required value="' + value.address + '"></td>' +
                    '<td><input type="tel" style="width:100px" class="form-control form-control-sm validate" name="numbers" required value="' + value.mobile + '"></td>' +
                    '<td><input type="tel" style="width:100px" class="form-control form-control-sm validate" name="numbers" required value="' + value.fixed + '"></td>' +
                    '<td><textarea style="width:100px" class="form-control form-control-sm validate" id="txtRemark"  required>' + value.instruction + '</textarea></td>' +
                    '<td><input type="text" style="width:200px;" class="form-control form-control-sm validate" required value="' + value.google_map_link + '"></td>' +
                    '<td><button type="button" class="btn btn-danger remove" id="btnRemove">Remove</button></td>' +
                    '</tr>';
            });
            $('#customer_delivery_points tbody').html(deliveryPointRows);
            addDelivaerypointRow(0);
            $(".remove").click(function () {
                $(this).closest("tr").remove();
            });
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}

function loadNames() {
    var result = [];

    $.ajax({
        url: '/md/searchNames',
        method: 'GET',
        cache: false,
        timeout: 800000,
        success: function (data) {
            $.each(data, function (index, value) {

                result.push(value.customer_name);
            })

        }

    });
    console.log(result);
    return result;

}

//update contact
function UpdateContact(id) {

    var contactData = [];
    var table = document.getElementById('customer_contact'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, customerId;

    for (i = 0, j = rows.length; i < j; ++i) {
        cells = rows[i].getElementsByTagName('td');
        if (!cells.length) {
            continue;
        }
        var data = {

            "name": cells[0].childNodes[0].value,
            "designation": cells[1].childNodes[0].value,
            "mobile": cells[2].childNodes[0].value,
            "fixed": cells[3].childNodes[0].value,
            "email": cells[4].childNodes[0].value,

        };
        contactData.push(JSON.stringify(data));

    }

    console.log(contactData);
    $.ajax({
        url: '/md/saveContact',
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


function getTownNonAdmin(id) {
    
    $('#cmbTown_onAdmin').empty();
    $.ajax({
        url: '/md/getTownNonAdmin/' + id,
        method: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbTown_onAdmin').append('<option value="' + item.town_id + '">' + item.townName + '</option>');
            });

            $('#cmbTown_onAdmin').change();

        }
    })
}


function getDeliveryRoutes() {
    $('#cmbDeliveryRoutes').empty();
    $.ajax({
        url: '/md/getDeliveryRoutes/',
        method: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbDeliveryRoutes').append('<option value="' + item.route_id + '">' + item.route_name + '</option>');
            });

        }
    })
}

function getMarketingRoutes() {
    $('#cmbMarketingRoutes').empty();
    $.ajax({
        url: '/md/getMarketingRoutes/',
        method: 'GET',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbMarketingRoutes').append('<option value="' + item.marketing_route_id + '">' + item.route_name + '</option>');
            });

        }
    })
}


function validateInput() {
    if ($('#txtCustomerCode').val().trim().length === 0) {
        $('#txtCustomerCode').focus();
        $(window).scrollTop(0);
        showWarningMessage('Invalied Customer Code');
        return false;
    }
    if ($('#txtName').val().trim().length === 0) {
        $('#txtName').focus();
        $(window).scrollTop(0);
        showWarningMessage('Invalied Customer Name');
        return false;
    }

    return true;
}

//load payment term
function loadPamentTerm() {
    $.ajax({
        url: '/md/loadPamentTerm',
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

}