$(document).ready(function () {

    //Loading data to the select tag
    loadAccountGroupLevelTwoforCMB();
    loadAccountGroupLevelThree();

    //Level Three calling save
    $('#btnSaveGroupLevelThree').on('click', function () {

        if ($('#txtAccountGroupLevelThree').val().length < 1) {
            showWarningMessage('Please enter a group level Two name');
        } else {
            if ($(this).text() == 'Save') {
                saveAccountGroupLevelThree();
            } else {
                updateAccountGroupLevelThree()
            }
        }

    });

    //close modal
    $('#btnCloseLevelThree').on('click', function () {
        $('#txtAccountGroupLevelThree').val('');
        $('#modelLevelThree').modal('hide');
    });

    //on hide event of the level Two modal
    $('#modelLevelThree').on('hide.bs.modal', function () {

        $('#txtAccountGroupLevelThree').prop('disabled', false);
        $('#cmbCategoryLevelTwo').prop('disabled', false);
        $('#btnSaveGroupLevelThree').show();
        $('#lblLevelThreeHidden').val('');
        $('#btnSaveGroupLevelThree').text('Save');

    });

});

//Save Account Group Level Two
function saveAccountGroupLevelThree() {

    let formData = new FormData();
    formData.append('AccountGroupLevelThree', $('#txtAccountGroupLevelThree').val());
    formData.append('AccountGroupLevelTwo', $('#cmbCategoryLevelTwo').val());
    console.log(formData);

    $.ajax({
        url: '/md/saveAccountLevelThree',
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelThree').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelThree').prop('disabled', false);
           /*  if (response.includes("Record duplicated")) {
                showWarningMessage("Record Duplicated");
            } else */ if (response.status) {
                showSuccessMessage("Record saved succesfully");
                $('#txtAccountGroupLevelThree').val('');
                $('#modelLevelThree').modal('hide');
                loadAccountGroupLevelThree();

            }
        },
    })
}

//Load data the table
function loadAccountGroupLevelThree() {
    $('#levelThreeTable tbody').empty();
    $.ajax({
        url: '/md/loadAccountGroupLevelThree',
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            console.log(data);
            
            $.each(data, function (key, value) {
                let btnEdit = '<button class="btn btn-primary btn-sm" onclick="edit(' + value.account_group_level_three_id + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                let btnView = '<button class="btn btn-success btn-sm" onclick="view(' + value.account_group_level_three_id + ')" ><i class="fa fa-eye" aria-hidden="true" ></i></button>';
                let btnDelete = '<button class="btn btn-danger btn-sm" onclick="deleteRecordThree(' + value.account_group_level_three_id + ')" ><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                var row = '<tr>' +
                    '<td>' + value.account_group_level_two_name + '</td>' +
                    '<td>' + value.account_group_level_three_name + '</td>' +
                    '<td>' + btnEdit + '</td>' +
                    '<td>' + btnView + '</td>' +
                    '<td>' + btnDelete + '</td>' +

                    '</tr>';


                $('#levelThreeTable tbody').append(row);

            })



        }
    })
}

//load level Two to select tag
function loadAccountGroupLevelTwoforCMB() {
    $.ajax({
        url: '/md/loadAccountGroupLevelTwo',
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            console.log(data);
            

           // $('#cmbCategoryLevelTwo').empty();
            const selectElement = $('#cmbCategoryLevelTwo');
            $.each(response.data, function(index, item) {
                const option = $('<option></option>')
                    .attr('value', item.account_group_level_two_id)
                    .text(item.account_group_level_two_name);
                selectElement.append(option);
            });
        }
    });
}


function view(id) {
    $('#modelLevelThree').modal('show');
    $('#btnSaveGroupLevelThree').hide();
    loadEachAccountGroupLevelThree(id, 'view')

}

function edit(id) {
    $('#modelLevelThree').modal('show');
    $('#btnSaveGroupLevelThree').text('Update');
    loadEachAccountGroupLevelThree(id, 'edit')

}

function deleteRecordThree(id) {
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
                deleteLevelThree(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}
//load account group level Two to view or edit
function loadEachAccountGroupLevelThree(id, type) {
    $.ajax({
        url: '/md/loadEachAccountGroupLevelThree/' + id,
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            console.log(data);

            $.each(data, function (key, value) {

                $('#txtAccountGroupLevelThree').val(value.account_group_level_three_name);
                $('#cmbCategoryLevelTwo').val(value.account_group_level_two_id);


            })
            $('#lblLevelThreeHidden').val(id)
            if (type == 'view') {
                $('#txtAccountGroupLevelThree').prop('disabled', true);
                $('#cmbCategoryLevelTwo').prop('disabled', true);
            }

        }
    })
}


//Update Account Group Level Two
function updateAccountGroupLevelThree() {
    let id = $('#lblLevelThreeHidden').val();
    let formData = new FormData();
    formData.append('AccountGroupLevelThree', $('#txtAccountGroupLevelThree').val());
    formData.append('AccountGroupLevelTwo', $('#cmbCategoryLevelTwo').val());
    console.log(formData);

    $.ajax({
        url: '/md/updateAccountGroupLevelThree/' + id,
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelThree').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelThree').prop('disabled', false);
            if (response) {
                showSuccessMessage("Record update succesfully");
                $('#txtAccountGroupLevelThree').val('');
                $('#modelLevelThree').modal('hide');
                loadAccountGroupLevelThree();

            } else {
                showWarningMessage('Unable to update');
            }
        },
    })
}

//Delete level Two
function deleteLevelThree(id) {
    $.ajax({
        url: '/md/deleteLevelThree/' + id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {

        },
        success: function (response) {
            if (response) {
                showSuccessMessage("Record deleted succesfully");
                loadAccountGroupLevelThree();

            } else {
                showWarningMessage('Unable to delete');
            }
        },
    })
}