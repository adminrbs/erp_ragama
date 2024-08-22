var formData = new FormData();
$(document).ready(function () {
    loadImage();
$('#erros_box').hide();

    $('#btnLogout').click(function (event) {
        event.preventDefault();
        // Make a POST request to the logout route using jQuery AJAX
        $.ajax({
            url: '/logout',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                // Redirect the user to the login page
                window.location.href = '/submitForm';
            },
            error: function (error) {
                console.log(error);
            }
        });
    });




    $('#submitform').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property


        submit();
    });

    //remove is invalid class
    $('#txtEmail').on('input',function(){
        $(this).removeClass('is-invalid');
        $(this).addClass('is-valid');
    });

    $('#txtPassword').on('input',function(){
        $(this).removeClass('is-invalid');
        $(this).addClass('is-valid');
    });

           


});

function submit(){


    formData.append('txtEmail', $('#txtEmail').val());
    formData.append('txtPassword', $('#txtPassword').val());

    console.log(formData);
$.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/submitForm',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
           if(response.status == 200){
            location.href = '/dashboard';
           }else if(response == "201"){
            /* showWarningMessage('Incorrect user name or password'); */
            $('#txtEmail').addClass('is-invalid')
            $('#txtPassword').addClass('is-invalid')
            $('#erros_box').removeAttr('hidden');
            $('#erros_box').fadeIn(500);
            

            setTimeout(function() {
                $('#erros_box').fadeOut(500); // Fade out with animation (adjust the duration as needed)
              }, 3000);
           }


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}


function loadImage(){
    
    $.ajax({
        url: '/get_logo_path', 
        type: 'GET',
        success: function(response) {
            console.log(response.path);
            $('#loggingLogo').attr('src', response.logo_path);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}