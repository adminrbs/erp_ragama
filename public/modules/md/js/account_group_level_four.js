$(document).ready(function () {

    //Loading data to the select tag
    loadAccountGroupLevelThreeforCMB();
    loadAccountGroupLevelFour();

    //Level Four calling save
    $('#btnSaveGroupLevelFour').on('click', function () {

        if ($('#txtAccountGroupLevelFour').val().length < 1) {
            showWarningMessage('Please enter a group level Three name');
        } else {
            if ($(this).text() == 'Save') {
                saveAccountGroupLevelFour();
            } else {
                updateAccountGroupLevelFour()
            }
        }

    });

    //close modal
    $('#btnCloseLevelFour').on('click', function () {
        $('#txtAccountGroupLevelFour').val('');
        $('#modelLevelFour').modal('hide');
    });

    //on hide event of the level Three modal
    $('#modelLevelFour').on('hide.bs.modal', function () {

        $('#txtAccountGroupLevelFour').prop('disabled', false);
        $('#cmbCategoryLevelThree').prop('disabled', false);
        $('#btnSaveGroupLevelFour').show();
        $('#lblLevelFourHidden').val('');
        $('#btnSaveGroupLevelFour').text('Save');

    });

});

//Save Account Group Level Three
function saveAccountGroupLevelFour() {

    let formData = new FormData();
    formData.append('AccountGroupLevelFour', $('#txtAccountGroupLevelFour').val());
    formData.append('AccountGroupLevelThree', $('#cmbCategoryLevelThree').val());
    console.log(formData);

    $.ajax({
        url: '/md/saveAccountLevelFour',
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelFour').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelFour').prop('disabled', false);
           /*  if (response.includes("Record duplicated")) {
                showWarningMessage("Record Duplicated");
            } else */ if (response.status) {
                showSuccessMessage("Record saved succesfully");
                $('#txtAccountGroupLevelFour').val('');
                $('#modelLevelFour').modal('hide');
                loadAccountGroupLevelFour();

            }
        },
    })
}

//Load data the table
function loadAccountGroupLevelFour() {
    $('#levelFourTable tbody').empty();
    $.ajax({
        url: '/md/loadAccountGroupLevelFour',
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            console.log(data);
            
            $.each(data, function (key, value) {
                let btnEdit = '<button class="btn btn-primary btn-sm" onclick="edit(' + value.account_group_level_four_id + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                let btnView = '<button class="btn btn-success btn-sm" onclick="view(' + value.account_group_level_four_id + ')" ><i class="fa fa-eye" aria-hidden="true" ></i></button>';
                let btnDelete = '<button class="btn btn-danger btn-sm" onclick="deleteRecordFour(' + value.account_group_level_four_id + ')" ><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                var row = '<tr>' +
                    '<td>' + value.account_group_level_three_name + '</td>' +
                    '<td>' + value.account_group_level_four_name + '</td>' +
                    '<td>' + btnEdit + '</td>' +
                    '<td>' + btnView + '</td>' +
                    '<td>' + btnDelete + '</td>' +

                    '</tr>';


                $('#levelFourTable tbody').append(row);

            })



        }
    })
}

//load level Three to select tag
function loadAccountGroupLevelThreeforCMB() {
    
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
            

            $('#cmbCategoryLevelThree').empty();
            const selectElement = $('#cmbCategoryLevelThree');
            $.each(response.data, function(index, item) {
                console.log(item.account_group_level_three_name);
                
               
                $('#cmbCategoryLevelThree').append('<option value="' + item.account_group_level_three_id + '">' + item.account_group_level_three_name + '</option>');
            });
        }
    });
}




function view(id) {
    $('#modelLevelFour').modal('show');
    $('#btnSaveGroupLevelFour').hide();
    loadEachAccountGroupLevelFour(id, 'view')

}

function edit(id) {
    $('#modelLevelFour').modal('show');
    $('#btnSaveGroupLevelFour').text('Update');
    loadEachAccountGroupLevelFour(id, 'edit')

}

function deleteRecordFour(id) {
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
                deleteLevelFour(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}
//load account group level Three to view or edit
function loadEachAccountGroupLevelFour(id, type) {
    $.ajax({
        url: '/md/loadEachAccountGroupLevelFour/' + id,
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            console.log(data);

            $.each(data, function (key, value) {

                $('#txtAccountGroupLevelFour').val(value.account_group_level_four_name);
                $('#cmbCategoryLevelThree').val(value.account_group_level_three_id);


            })
            $('#lblLevelFourHidden').val(id)
            if (type == 'view') {
                $('#txtAccountGroupLevelFour').prop('disabled', true);
                $('#cmbCategoryLevelThree').prop('disabled', true);
            }

        }
    })
}


//Update Account Group Level Three
function updateAccountGroupLevelFour() {
    let id = $('#lblLevelFourHidden').val();
    let formData = new FormData();
    formData.append('AccountGroupLevelFour', $('#txtAccountGroupLevelFour').val());
    formData.append('AccountGroupLevelThree', $('#cmbCategoryLevelThree').val());
    console.log(formData);

    $.ajax({
        url: '/md/updateAccountGroupLevelFour/' + id,
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelFour').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelFour').prop('disabled', false);
            if (response) {
                showSuccessMessage("Record update succesfully");
                $('#txtAccountGroupLevelFour').val('');
                $('#modelLevelFour').modal('hide');
                loadAccountGroupLevelFour();

            } else {
                showWarningMessage('Unable to update');
            }
        },
    })
}

//Delete level Three
function deleteLevelFour(id) {
    $.ajax({
        url: '/md/deleteLevelFour/' + id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {

        },
        success: function (response) {
            if (response) {
                showSuccessMessage("Record deleted succesfully");
                loadAccountGroupLevelFour();

            } else {
                showWarningMessage('Unable to delete');
            }
        },
    })
}