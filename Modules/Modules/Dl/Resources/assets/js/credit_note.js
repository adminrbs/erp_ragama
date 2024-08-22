var formData = new FormData();
var customers = []
var action = undefined;
var referanceID;
var ItemList;

$(document).ready(function () {

    loadSalesRep();
    /*  $('#btnApprove').hide();
     $('#btnReject').hide(); */
    ItemList = loadItems();

    getServerTime();


    //back button
    $('#btnBack').hide();
    $('#btnBack').on('click', function () {

        var url = "/sd/getSalesOrderList";
        window.location.href = url;
    });



    getBranches();
    $('#cmbBranch').change();



    customers = loadCustomerTOchooser();

    DataChooser.addCollection("Customer", ['Customer Name', 'Customer Code', 'Town', 'Route', ''], customers);
    DataChooser.addCollection("item", ['', '', '', '', ''], ItemList);




    $('#txtCustomerID').on('focus', function () {


        DataChooser.showChooser($(this), $(this), "Customer");
        $('#data-chooser-modalLabel').text('Customers');

    });



    $('select').change(function () {

        validateSelectTag(this);

    });

    $('#txtAmount').on('input', function () {
        // Get the current value of the input
        let inputValue = $(this).val();

        // Remove any existing thousands separators (commas)
        inputValue = inputValue.replace(/,/g, '');

        // Replace multiple consecutive dots with a single dot
        inputValue = inputValue.replace(/(\.\d*?)\./g, '$1');

        // Remove any non-digit characters except for the first dot
        inputValue = inputValue.replace(/[^\d.]/g, '');

        // Format the input with thousands separators
        inputValue = formatNumberWithCommas(inputValue);

        // Update the input field with the formatted value
        $(this).val(inputValue);
    });





    //from list
    if (window.location.search.length > 0) {
        var urlParams = new URLSearchParams(window.location.search);


        var credit_note_id = urlParams.get('id');
        action = urlParams.get('action');

        if (action == "view") {
            $('#btnSave').hide();
            $('#cmbBranch').prop('disabled', true);
            $('#txtCustomerID').prop('disabled', true);
            $('#txtRemarks').prop('disabled', true);
            $('#txtNarration').prop('disabled', true);
            $('#txtAmount').prop('disabled', true);
        }

        getEachcreditNote(credit_note_id);


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
                //console.log('Confirmation result:', result);
                if (result) {

                    newReferanceID('credit_notes', '1700');
                    addCreditNote();

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

//thousand seperators
function formatNumberWithCommas(number) {
    // Convert the number to a string and add thousands separators
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}





function transactionTableKeyEnterEvent(event, id) {

    if (id == 'tblData') {
        tableData.addRow();

    }

}



//add credit order
function addCreditNote() {

    if ($('#txtNarration').val().length < 1) {
        showWarningMessage('Please enter narration');
        $('#txtNarration').addClass('is-invalid');
    } else if ($('#txtAmount').val().length < 1) {
        showWarningMessage('Please enter the amount');
        $('#txtAmount').addClass('is-invalid');
    } else {
        formData.append('LblexternalNumber', referanceID);
        formData.append('date', $('#order_date_time').val());
        formData.append('lblCustomerName', $('#lblCustomerName').val());
        formData.append('customerID', $('#lblCustomerName').data('id'));
        formData.append('txtRemarks', $('#txtRemarks').val());
        formData.append('grandTotal', parseFloat($('#txtAmount').val().replace(/,/g, '')));
        formData.append('narration', $('#txtNarration').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('sales_rep',$('#cmbSalesRep').val());


        $.ajax({
            url: '/dl/addCreditNote',
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


                    url = "/dl/credit_note_list";
                    setTimeout(function () {
                        window.location.href = url;
                    }, 2000);


                } else {

                    showWarningMessage("Unable to save");
                }



            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {

            }
        })

    }

    getServerTime();




}


function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event, "item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

function dataChooserShowEventListener(event) {

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
        async: false,
        success: function (data) {
            console.log(data)
            var txt = data.data;
            /*  console.log(txt); */
            $('#lblCustomerAddress').val(txt[0].primary_address);
            var cusID = txt[0].customer_id;
            var payment_term_id_ = txt[0].payment_term_id;
            $('#lblCustomerName').attr('data-id', cusID);



        },
        error: function (error) {
            console.log(error);
        },

    });
}



function getEachcreditNote(id) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/dl/getEachcreditNote/' + id,
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
            console.log(res.external_number);
            $('#LblexternalNumber').val(res.external_number);
            $('#order_date_time').val(res.trans_date);
            $('#cmbBranch').val(res.branch_id);
            $('#cmbSalesRep').val(res.employee_id);
            $('#txtCustomerID').val(res.customer_code);
            $('#lblCustomerName').val(res.customer_name);
            $('#lblCustomerAddress').val(res.primary_address);
            $('#txtRemarks').val(res.description);
            $('#txtNarration').val(res.narration_for_account);
            $('#txtAmount').val(res.amount);


        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


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








function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_credit_note", table, doc_number);
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

            })

        },
    })
}


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);
}


function dataChooserEventListener(event, id, value) {
    if ($(event.inputFiled).attr('id') == 'txtCustomerID') {
        loadCustomerOtherDetails(value);
        $('#lblCustomerName').val(id);

    } else {


    }

}


function loadSalesRep() {
    $.ajax({
        url: '/dl/getSalesRep',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            console.log(data)
            if (data.status) {
                var result = data.data;
                for(var i = 0; i < result.length; i++){
                    $('#cmbSalesRep').append('<option value="'+result[i].employee_id+'">'+result[i].employee_name+'</option>');
                }
            }
        },
        error: function (error) {
            console.log(error);
        },

    });
}


