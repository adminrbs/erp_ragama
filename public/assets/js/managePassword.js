
var formData = new FormData();

$(document).ready(function () {
    //show password on check box
    /* $('#txtcurPW_eye').on('click',function () {
        showPassword($('#txtCurrentPW'),$('#txtcurPW_eye'));
        const icon = $(this);
        icon.toggleClass("fa-eye");
        icon.toggleClass("fa-eye-slash");
    }); */

    $('#txtNewPW_eye').on('click',function () {
        showPassword($('#txtNewPW'),$('#txtNewPW_eye'));
        const icon = $(this);
        icon.toggleClass("fa-eye");
        icon.toggleClass("fa-eye-slash");
    });

    $('#txtconfirmPW_eye').on('click',function () {
        showPassword($('#txtConfirmPW'),$('#txtconfirmPW_eye'));
        const icon = $(this);
        icon.toggleClass("fa-eye");
        icon.toggleClass("fa-eye-slash");
    });

    //clear text boxes on hidden
    $('#pw_changeModel').on('hidden.bs.modal', function() {
        $('#txtNewPW').val('');
        $('#txtConfirmPW').val('');
        $('#txtCurrentPW').val('');
    });

    //calling update function 
    $('#model_btnUpdatePW_').on('click',function(){
        confrim_box();
    });

    //remove is invalid class
    $('.p_field').on('input',function(){
        $(this).removeClass('is-invalid');
    })

});


//show pw change model
function showPw_change_model() {
    $('#pw_changeModel').modal('show');
    $('.modal-backdrop').css('background-color', 'transparent');
    $('#pw_changeModel').find('.modal-header').addClass('bg-warning text-white');
}

//show password
function showPassword(textfield,inputField) {

   
    if (inputField.hasClass('fa fa-eye')) {
        textfield.attr('type','text');
       
    } else {
        textfield.attr('type','password');
        
    }
}
//confirmation box
function confrim_box(){
    bootbox.confirm({
        title: 'Update confirmation',
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
                updatePassword();
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
}

//update password
function updatePassword() {
    
    if($('#txtNewPW').val() !== $('#txtConfirmPW').val()){
        showWarningMessage('New passwords are different')
        $('#txtNewPW').addClass('is-invalid');
        $('#txtConfirmPW').addClass('is-invalid');
        return;
    }

    formData.append('currentPassword', $('#txtCurrentPW').val());
    formData.append('newPassword', $('#txtNewPW').val());
    $.ajax({
        url: '/updatePassword',
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
            $('#model_btnUpdatePW_').prop('disabled', true);
        }, success: function (response) {
            $('#model_btnUpdatePW_').prop('disabled', false);
            var status = response.status;
            var msg = response.message;
            
            
            if (status) {
                showSuccessMessage("Password Successfully updated");
                $('#pw_changeModel').modal('hide');
                return;

            }else if(msg == "mismatched"){
                showWarningMessage("Current Password is incorrect");
                $('#txtCurrentPW').addClass('is-invalid');


            } else {

                showWarningMessage("Unable to update");
                
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}