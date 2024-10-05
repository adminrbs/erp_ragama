$(document).ready(function(){
    loadGlAccountAnalysis();
    $('#btnSaveGlAccountAnalysis').on('click',function(){
        
        if($(this).text() == 'Save'){
            saveAnalysis();
        }else{
            updateGlAccountAnalysis();
        }
    });
   

    $('#btnCloseGlAccountAnalysis').on('click',function(){
        $('#modelGlAccountAnalysis').hide();
    });

    $('#modelGlAccountAnalysis').on('hide.bs.modal', function () {
        $('#txtGlAccountANalysis').val('');
        $('#btnSaveGlAccountAnalysis').show();
    });
});

function saveAnalysis(){
    let formData = new FormData();
    if($('#txtGlAccountANalysis').val().length < 1){
        showWarningMessage('Please enter analysis name');
    }else{
        formData.append('analysis',$('#txtGlAccountANalysis').val());
        $.ajax({
            method:'post',
            url:'/md/saveAnalysis',
            data:formData,
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSaveGlAccountAnalysis').prop('disabled', true);
            },
            success: function (response) {
                $('#btnSaveGlAccountAnalysis').prop('disabled', false);
                if(response.status){
                    showSuccessMessage('Record saved successfuly');
                    $('#modelGlAccountAnalysis').hide();
                    loadGlAccountAnalysis();
                }else{
                    showWarningMessage('Unable to save');
                }
            }
        })

    }
}


function updateGlAccountAnalysis(){
    let id = $('#lblLevelOneHidden').val();
    let formData = new FormData();
    if($('#txtGlAccountANalysis').val().length < 1){
        showWarningMessage('Please enter name');
    }else{
        formData.append('analysis',$('#txtGlAccountANalysis').val());
        $.ajax({
            method:'post',
            url:'/md/updateGlAccountAnalysis/'+id,
            data:formData,
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSaveGlAccountAnalysis').prop('disabled', true);
            },
            success: function (response) {
                $('#btnSaveGlAccountAnalysis').prop('disabled', false);
                if(response.status){
                    showSuccessMessage('Record updated successfuly');
                    $('#modelGlAccountAnalysis').hide();
                    loadGlAccountAnalysis();
                }else{
                    showWarningMessage('Unable to update');
                }
            }
        })

    }
}
function view(id){
    $('#modelGlAccountAnalysis').modal('show');
    $('#btnSaveGlAccountAnalysis').hide();
    loadEachGlAccountAnalysis(id,'view')
    $('#lblLevelOneHidden').val(id)

}

function edit(id){
    $('#modelGlAccountAnalysis').modal('show');
    $('#btnSaveGlAccountAnalysis').text('Update');
    loadEachGlAccountAnalysis(id,'edit')
    $('#lblLevelOneHidden').val(id)
}

function loadEachGlAccountAnalysis(id,type) {
    $.ajax({
        url: '/md/loadEachGlAccountAnalysis/'+id,
        method: 'GET',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {   
            let data = response.data;
            $('#txtGlAccountANalysis').val(data.gl_account_analyse_name);
            $('#lblLevelOneHidden').val(id)
            if(type == 'view'){
                $('#txtGlAccountANalysis').prop('disabled',true);
            }else{
                $('#txtGlAccountANalysis').prop('disabled',false);
            }
            
        }
    })
}



function loadGlAccountAnalysis() {
    $('#glAnalysisTable tbody').empty();
    $.ajax({
        url: '/md/loadGlAccountAnalysis',
        method: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            let data = response.data;
            $.each(data, function (key, value) {
                let btnEdit = '<button class="btn btn-primary btn-sm" onclick="edit(' + value.gl_account_analyse_id + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                let btnView = '<button class="btn btn-success btn-sm" onclick="view(' + value.gl_account_analyse_id + ')" ><i class="fa fa-eye" aria-hidden="true" ></i></button>';
                let btnDelete = '<button class="btn btn-danger btn-sm" onclick="deleteRecord(' + value.gl_account_analyse_id + ')" ><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                var row = '<tr>' +
                    '<td>' + value.gl_account_analyse_name + '</td>' +
                    
                    '<td>' + btnView + '</td>' +
                    '<td>' + btnEdit + '</td>' +
                    '<td>' + btnDelete + '</td>' +

                    '</tr>';

                $('#glAnalysisTable tbody').append(row);

            });

        }
    })
}

function deleteAnalysis(id){
    $.ajax({
        url: '/md/deleteAnalysis/'+id,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, beforeSend: function () {
            
        },
        success: function (response) {
            if (response) {
                showSuccessMessage("Record deleted succesfully");
                loadGlAccountAnalysis();

            }else{
                loadGlAccountAnalysis('Unable to delete');
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
                deleteAnalysis(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}