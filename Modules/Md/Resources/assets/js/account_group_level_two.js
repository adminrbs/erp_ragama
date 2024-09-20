$(document).ready(function () {

    //Loading data to the select tag
    loadAccountGroupLevelOneforCMB();
    loadAccountGroupLevelTwo();

    //Level Two calling save
    $('#btnSaveGroupLevelTwo').on('click', function () {

        if ($('#txtAccountGroupLevelTwo').val().length < 1) {
            showWarningMessage('Please enter a group level one name');
        } else {
            if ($(this).text() == 'Save') {
                saveAccountGroupLevelTwo();
            } else {
                updateAccountGroupLevelTwo()
            }
        }

    });

    //close modal
    $('#btnCloseLevelTwo').on('click', function () {
        $('#txtAccountGroupLevelTwo').val('');
        $('#modelLevelTwo').modal('hide');
    });

    //on hide event of the level one modal
    $('#modelLevelTwo').on('hide.bs.modal', function () {

        $('#txtAccountGroupLevelTwo').prop('disabled', false);
        $('#cmbCategoryLevelOne').prop('disabled', false);
        $('#btnSaveGroupLevelTwo').show();
        $('#lblLevelTwoHidden').val('');
        $('#btnSaveGroupLevelTwo').text('Save');

    });

});

//Save Account Group Level One
function saveAccountGroupLevelTwo() {

    let formData = new FormData();
    formData.append('AccountGroupLevelTwo', $('#txtAccountGroupLevelTwo').val());
    formData.append('AccountGroupLevelOne', $('#cmbCategoryLevelOne').val());
    console.log(formData);

    $.ajax({
        url: '/md/saveAccountLevelTwo',
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelTwo').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelTwo').prop('disabled', false);
           /*  if (response.includes("Record duplicated")) {
                showWarningMessage("Record Duplicated");
            } else */ if (response.status) {
                showSuccessMessage("Record saved succesfully");
                $('#txtAccountGroupLevelTwo').val('');
                $('#modelLevelTwo').modal('hide');
                loadAccountGroupLevelTwo();

            }
        },
    })
}

//Load data the table
function loadAccountGroupLevelTwo() {
    $('#levelTwoTable tbody').empty();
    $.ajax({
        url: '/md/loadAccountGroupLevelTwo',
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            $.each(data, function (key, value) {
                let btnEdit = '<button class="btn btn-primary btn-sm" onclick="edit(' + value.account_group_level_two_id + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                let btnView = '<button class="btn btn-success btn-sm" onclick="view(' + value.account_group_level_two_id + ')" ><i class="fa fa-eye" aria-hidden="true" ></i></button>';
                let btnDelete = '<button class="btn btn-danger btn-sm" onclick="deleteRecordTwo(' + value.account_group_level_two_id + ')" ><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                var row = '<tr>' +
                    '<td>' + value.account_group_level_one_name + '</td>' +
                    '<td>' + value.account_group_level_two_name + '</td>' +
                    '<td>' + btnEdit + '</td>' +
                    '<td>' + btnView + '</td>' +
                    '<td>' + btnDelete + '</td>' +

                    '</tr>';


                $('#levelTwoTable tbody').append(row);

            })



        }
    })
}

//load level one to select tag
function loadAccountGroupLevelOneforCMB() {
    $.ajax({
        url: '/md/loadAccountGroupLevelOne',
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;


            $('#cmbCategoryLevelOne').empty();
            $.each(data, function (key, value) {
                let option = $('<option></option>')
                    .val(value.account_group_level_one_id)
                    .text(value.account_group_level_one_name);

                $('#cmbCategoryLevelOne').append(option);
            });
        }
    });
}


function view(id) {
    $('#modelLevelTwo').modal('show');
    $('#btnSaveGroupLevelTwo').hide();
    loadEachAccountGroupLevelTwo(id, 'view')

}

function edit(id) {
    $('#modelLevelTwo').modal('show');
    $('#btnSaveGroupLevelTwo').text('Update');
    loadEachAccountGroupLevelTwo(id, 'edit')

}

function deleteRecordTwo(id) {
    bootbox.confirm({
        title: 'Delete confirmation',
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
                deleteLevelTwo(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}
//load account group level one to view or edit
function loadEachAccountGroupLevelTwo(id, type) {
    $.ajax({
        url: '/md/loadEachAccountGroupLevelTwo/' + id,
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            console.log(data);

            $.each(data, function (key, value) {

                $('#txtAccountGroupLevelTwo').val(value.account_group_level_two_name);
                $('#cmbCategoryLevelOne').val(value.account_group_level_one_id);


            })
            $('#lblLevelTwoHidden').val(id)
            if (type == 'view') {
                $('#txtAccountGroupLevelTwo').prop('disabled', true);
                $('#cmbCategoryLevelOne').prop('disabled', true);
            }

        }
    })
}


//Update Account Group Level One
function updateAccountGroupLevelTwo() {
    let id = $('#lblLevelTwoHidden').val();
    let formData = new FormData();
    formData.append('AccountGroupLevelTwo', $('#txtAccountGroupLevelTwo').val());
    formData.append('AccountGroupLevelOne', $('#cmbCategoryLevelOne').val());
    console.log(formData);

    $.ajax({
        url: '/md/updateAccountGroupLevelTwo/' + id,
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelTwo').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelTwo').prop('disabled', false);
            if (response) {
                showSuccessMessage("Record update succesfully");
                $('#txtAccountGroupLevelTwo').val('');
                $('#modelLevelTwo').modal('hide');
                loadAccountGroupLevelTwo();

            } else {
                showWarningMessage('Unable to update');
            }
        },
    })
}

//Delete level one
function deleteLevelTwo(id) {
    $.ajax({
        url: '/md/deleteLevelTwo/' + id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {

        },
        success: function (response) {
            if (response) {
                showSuccessMessage("Record deleted succesfully");
                loadAccountGroupLevelTwo();

            } else {
                showWarningMessage('Unable to delete');
            }
        },
    })
}