
$(document).ready(function () {

    //Loading data to the table
    loadAccountGroupLevelOne();
    //Level One calling save
    $('#btnSaveGroupLevelOne').on('click', function () {
        //alert();
        if ($('#txtAccountGroupLevelOne').val().length < 1) {
            showWarningMessage('Please enter a group level one name');
        } else {
            if ($(this).text() == 'Save') {
                saveAccountGroupLevelOne();
            }else{
                updateAccountGroupLevelOne()
            }
        }

    });

    //close modal
    $('#btnCloseLevelOne').on('click', function () {
        $('#txtAccountGroupLevelOne').val('');
        $('#modelLevelOne').modal('hide');
    });

    //on hide event of the level one modal
    $('#modelLevelOne').on('hide.bs.modal', function () {
        
        $('#txtAccountGroupLevelOne').prop('disabled',false);
        $('#btnSaveGroupLevelOne').show();
        $('#lblLevelOneHidden').val('');
        $('#btnSaveGroupLevelOne').text('Save');
        
    });
    
});

//Save Account Group Level One
function saveAccountGroupLevelOne() {

    let formData = new FormData();
    formData.append('AccountGroupLevelName', $('#txtAccountGroupLevelOne').val());
    console.log(formData);

    $.ajax({
        url: '/md/saveAccountLevelOne',
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelOne').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelOne').prop('disabled', false);
           /*  if (response.includes("Record duplicated")) {
                showWarningMessage("Record Duplicated");
            } else */ if (response.status) {
                showSuccessMessage("Record saved succesfully");
                $('#txtAccountGroupLevelOne').val('');
                $('#modelLevelOne').modal('hide');
                loadAccountGroupLevelOne();

            }
        },
    })
}

//Load data the table
function loadAccountGroupLevelOne() {
    $('#levelOneTable tbody').empty();
    $.ajax({
        url: '/md/loadAccountGroupLevelOne',
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            $.each(data, function (key, value) {
                let btnEdit = '<button class="btn btn-primary btn-sm" onclick="edit(' + value.account_group_level_one_id + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                let btnView = '<button class="btn btn-success btn-sm" onclick="view(' + value.account_group_level_one_id + ')" ><i class="fa fa-eye" aria-hidden="true" ></i></button>';
                let btnDelete = '<button class="btn btn-danger btn-sm" onclick="deleteRecord(' + value.account_group_level_one_id + ')" ><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                var row = '<tr>' +
                    '<td>' + value.account_group_level_one_name + '</td>' +
                    '<td>' + btnEdit + '</td>' +
                    '<td>' + btnView + '</td>' +
                    '<td>' + btnDelete + '</td>' +

                    '</tr>';


                $('#levelOneTable tbody').append(row);

            })



        }
    })
}

function view(id){
    $('#modelLevelOne').modal('show');
    $('#btnSaveGroupLevelOne').hide();
    loadEachAccountGroupLevelOne(id,'view')

}

function edit(id){
    $('#modelLevelOne').modal('show');
    $('#btnSaveGroupLevelOne').text('Update');
    loadEachAccountGroupLevelOne(id,'edit')

}

function deleteRecord(id){
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
                deleteLevelOne(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}
//load account group level one to view or edit
function loadEachAccountGroupLevelOne(id,type) {
    $.ajax({
        url: '/md/loadEachAccountGroupLevelOne/'+id,
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {   
            let data = response.data;
            $('#txtAccountGroupLevelOne').val(data.account_group_level_one_name);
            $('#lblLevelOneHidden').val(id)
            if(type == 'view'){
                $('#txtAccountGroupLevelOne').prop('disabled',true);
            }
            
        }
    })
}


//Update Account Group Level One
function updateAccountGroupLevelOne() {
    let id = $('#lblLevelOneHidden').val();
    let formData = new FormData();
    formData.append('AccountGroupLevelName', $('#txtAccountGroupLevelOne').val());
    console.log(formData);

    $.ajax({
        url: '/md/updateAccountGroupLevelOne/'+id,
        method: 'POST',
        data: formData,
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            $('#btnSaveLevelOne').prop('disabled', true);
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveLevelOne').prop('disabled', false);
            if (response) {
                showSuccessMessage("Record update succesfully");
                $('#txtAccountGroupLevelOne').val('');
                $('#modelLevelOne').modal('hide');
                loadAccountGroupLevelOne();

            }else{
                showWarningMessage('Unable to update');
            }
        },
    })
}

//Delete level one
function deleteLevelOne(id){
    $.ajax({
        url: '/md/deleteLevelOne/'+id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            
        },
        success: function (response) {
            if (response) {
                showSuccessMessage("Record deleted succesfully");
                loadAccountGroupLevelOne();

            }else{
                showWarningMessage('Unable to delete');
            }
        },
    })
}