$(document).ready(function(){
    loadPayee();
    $('#btnSavePayee').on('click',function(){
        
        if($(this).text() == 'Save'){
            savePayee();
        }else{
            updatePayee();
        }
    });
    $('#modelPayee').on('hide.bs.modal', function () {
        $('#txtPayee').val('');
    });

    $('#btnClosePayee',function(){
        $('#modelPayee').hide();
    });
});

function savePayee(){
    let formData = new FormData();
    if($('#txtPayee').val().length < 1){
        showWarningMessage('Please enter payee name');
    }else{
        formData.append('payeeName',$('#txtPayee').val());
        $.ajax({
            method:'post',
            url:'/md/savePayee',
            data:formData,
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSavePayee').prop('disabled', true);
            },
            success: function (response) {
                $('#btnSavePayee').prop('disabled', false);
                if(response.status){
                    showSuccessMessage('Record saved successfuly');
                    $('#modelPayee').hide();
                    loadPayee();
                }else{
                    showWarningMessage('Unable to save');
                }
            }
        })

    }
}


function updatePayee(){
    let id = $('#Payeehidden').val();
    let formData = new FormData();
    if($('#txtPayee').val().length < 1){
        showWarningMessage('Please enter payee name');
    }else{
        formData.append('payeeName',$('#txtPayee').val());
        $.ajax({
            method:'post',
            url:'/md/updatePayee/'+id,
            data:formData,
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSavePayee').prop('disabled', true);
            },
            success: function (response) {
                $('#btnSavePayee').prop('disabled', false);
                if(response.status){
                    showSuccessMessage('Record updated successfuly');
                    $('#modelPayee').hide();
                    loadPayee();
                }else{
                    showWarningMessage('Unable to update');
                }
            }
        })

    }
}
function view(id){
    $('#modelPayee').modal('show');
    $('#btnSavePayee').hide();
    loadEachPayee(id,'view')
    $('#Payeehidden').val($id)

}

function edit(id){
    $('#modelPayee').modal('show');
    $('#btnSavePayee').text('Update');
    loadEachPayee(id,'edit')
    $('#Payeehidden').val($id)
}

function loadEachPayee(id,type) {
    $.ajax({
        url: '/md/loadEachPayee/'+id,
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {   
            let data = response.data;
            $('#txtPayee').val(data.payee_name);
            $('#lblLevelOneHidden').val(id)
            if(type == 'view'){
                $('#txtPayee').prop('disabled',true);
            }
            
        }
    })
}



function loadPayee() {
    $('#payeeTable tbody').empty();
    $.ajax({
        url: '/md/loadPayee',
        method: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            $.each(data, function (key, value) {
                let btnEdit = '<button class="btn btn-primary btn-sm" onclick="edit(' + value.payee_id + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                let btnView = '<button class="btn btn-success btn-sm" onclick="view(' + value.payee_id + ')" ><i class="fa fa-eye" aria-hidden="true" ></i></button>';
                let btnDelete = '<button class="btn btn-danger btn-sm" onclick="deleteRecord(' + value.payee_id + ')" ><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                var row = '<tr>' +
                    '<td>' + value.payee_name + '</td>' +
                    
                    '<td>' + btnView + '</td>' +
                    '<td>' + btnEdit + '</td>' +
                    '<td>' + btnDelete + '</td>' +

                    '</tr>';

                $('#payeeTable tbody').append(row);

            });

        }
    })
}

function deletePayee(id){
    $.ajax({
        url: '/md/deletePayee/'+id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            
        },
        success: function (response) {
            if (response) {
                showSuccessMessage("Record deleted succesfully");
                loadPayee();

            }else{
                loadPayee('Unable to delete');
            }
        },
    })
}

function deleteRecord(id) {
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
                deletePayee(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}