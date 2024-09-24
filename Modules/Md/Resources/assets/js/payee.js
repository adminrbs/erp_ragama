$(document).ready(function(){
    $('#btnSavePayee').on('click',function(){
        alert();
        if($(this).text() == 'Save'){
            savePayee();
        }else{

        }
    });
});

function savePayee(){
    let formData = new FormData();
    if($('#txtPayee').val().length < 1){
        showWarningMessage('Please enter payee name');
    }else{
        formData.append('payeeName',$('#txtPayee').val());
        $.ajax({
            method:'POST',
            URL:'/md/savePayee',
            data:formData,
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSave').prop('disabled', true);
            },
            success: function (response) {
                if(response.status){
                    showSuccessMessage('Record saved successfuly');
                }else{
                    showWarningMessage('Unable to save');
                }
            }
        })

    }
}