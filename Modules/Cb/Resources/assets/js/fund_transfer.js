var referanceID = undefined;

$(document).ready(function () {
    getGLAccounts();
    getBranches();

    $('#btnApproval').hide();
    $('#btnReject').hide();
    if (action == 'edit') {
        $('#btnAction').text('Update');
    } else if (action == 'view') {
        $('#btnAction').hide();
        $('#btnApproval').hide();
        $('#btnReject').hide();
    } else if (action == 'approval') {
        $('#btnAction').hide();
        $('#btnApproval').show();
        $('#btnReject').show();
    }



    $('#btnAction').on('click', function () {

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
                    if (action == 'edit') {
                        updateFundTransfer(fund_transfer_id);
                    } else {
                        newReferanceID('fund_transfers', '2900');
                        saveFundTransfer();
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

    $('#btnApproval').on('click', function () {

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
                console.log(result);
                if (result) {
                    approvalFundTransfer(fund_transfer_id,1);

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

    $('#btnReject').on('click', function () {

        bootbox.confirm({
            title: 'Reject confirmation',
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
                    approvalFundTransfer(fund_transfer_id,2);

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

    getFundTransfer(fund_transfer_id);
});


function getGLAccounts() {
    var list = [];
    $.ajax({
        url: '/cb/getGLAccounts',
        type: 'get',
        async: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;

            }
            console.log(response.data);
            $.each(response.data, function (index, value) {
                $('#cmbSourceAccount').append('<option value="' + value.hidden_id + '">' + value.id + '</option>');
                $('#cmbDestinationAccount').append('<option value="' + value.hidden_id + '">' + value.id + '</option>');
            })
        },
        error: function (error) {
            console.log(error);
        },

    })
    return list;
}



//loading branches
function getBranches() {
    $.ajax({
        url: '/cb/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbSourceBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#cmbDestinationBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })

        },
    })
}


function saveFundTransfer() {
    $.ajax({
        url: '/cb/saveFundTransfer',
        method: 'post',
        enctype: 'multipart/form-data',
        data: getFormData(),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnAction').prop('disabled', true);
        }, success: function (response) {

            if (response.success) {
                showSuccessMessage("Successfuly saved");
                window.location.href = "/cb/fund_transfer_list";
            } else {
                showWarningMessage("Unable to save");
            }
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnAction').prop('disabled', false);
        }
    })
}




function updateFundTransfer(id) {
    $.ajax({
        url: '/cb/updateFundTransfer/' + id,
        method: 'post',
        enctype: 'multipart/form-data',
        data: getFormData(),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnAction').prop('disabled', true);
        }, success: function (response) {

            if (response.success) {
                showSuccessMessage("Successfuly saved");
                window.location.href = "/cb/fund_transfer_list";
            } else {
                showWarningMessage("Unable to save");
            }
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnAction').prop('disabled', false);
        }
    })
}



function getFormData() {
    var formData = new FormData();
    //formData.append("reference_no", $('#txtReferanceNo').val());
    formData.append('external_number', referanceID); 
    formData.append("date", $('#txtDate').val());
    formData.append("amount", $('#txtAmount').val());
    formData.append("source_account", $('#cmbSourceAccount').val());
    formData.append("destination_account", $('#cmbDestinationAccount').val());
    formData.append("source_branch", $('#cmbSourceBranch').val());
    formData.append("destination_branch", $('#cmbDestinationBranch').val());
    formData.append("description", $('#txtDescription').val());
    formData.append("created_by", 0);
    formData.append("approved_by", 0);
    formData.append("approval_status", 0);
    return formData;
}


function getFundTransfer(id) {
    $.ajax({
        url: '/cb/getFundTransfer/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            console.log(data);
            var header = data.header;
            referanceID = header.external_number;
            $('#txtReferanceNo').val(header.external_number);
            $('#txtDate').val(header.transaction_date);
            $('#txtAmount').val(header.amount);
            $('#cmbSourceAccount').val(header.destination_account_id);
            $('#cmbDestinationAccount').val(header.source_account_id);
            $('#cmbSourceBranch').val(header.source_branch_id);
            $('#cmbDestinationBranch').val(header.destination_branch_id);
            $('#txtDescription').val(header.description);

        },
    })
}


function approvalFundTransfer(id, status) {

    $.ajax({
        url: '/cb/approvalFundTransfer/' + id,
        method: 'put',
        enctype: 'multipart/form-data',
        data: { status: status },
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnApproval').prop('disabled', true);
            $('#btnReject').prop('disabled', true);
        }, success: function (response) {

            if (response.success) {
                showSuccessMessage("Successfuly updated");
                window.location.href = "/cb/fund_transfer_list";
            } else {
                showWarningMessage("Unable to update");
            }
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnApproval').prop('disabled', false);
            $('#btnReject').prop('disabled', false);
        }
    })
}


function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_FundTransfer", table, doc_number);
   
}
