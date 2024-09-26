$(document).ready(function(){
    loadPayee();
    $('#btnSavePayee').on('click',function(){
        
        if($(this).text() == 'Save'){
            savePayee();
        }else{

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

function view(id){
    $('#modelPayee').modal('show');
    $('#btnSavePayee').hide();
    loadEachPayee(id,'view')

}

function edit(id){
    $('#modelPayee').modal('show');
    $('#btnSavePayee').text('Update');
    loadEachPayee(id,'edit')

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
                    '<td>' + btnEdit + '</td>' +
                    '<td>' + btnView + '</td>' +
                    '<td>' + btnDelete + '</td>' +

                    '</tr>';

                $('#payeeTable tbody').append(row);

            });

        }
    })
}