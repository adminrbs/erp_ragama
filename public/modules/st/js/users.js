var formData = new FormData();
$(document).ready(function () {
    $('#btnusersave').show();
    $('#btnupdate').hide();
    $('#empshow').hide();



    if ($('#cmbuserTypeRole').val() === '1') {
        $('#empshow').show();
    } else {

    }

    // Listen for changes on the "User Type" dropdown
    $('#cmbuserTypeRole').change(function () {
        if ($(this).val() === '1') {
            $('#empshow').show();
        } else {
            $('#empshow').hide();

        }
    });

    $(".select2").select2({
        dropdownParent: $("#form")

    });


    $('#btnusersave').on('click', function (e) {

        e.preventDefault();
        var emailLength = $('#txtEmail').val().length;
        if (emailLength > 0 && $('#txtEmail').hasClass('is-invalid')) {

            showErrorMessage("Please check the email address");
        } else {
            formData.append('txtname', $('#txtname').val());
            formData.append('txtEmail', $('#txtEmail').val());
            formData.append('txtPassword', $('#txtPassword').val());
            formData.append('txtConformPassword', $('#txtConformPassword').val());
            formData.append('cmbuserRole', $('#cmbuserRole').val());
            formData.append('cmbuserTypeRole', $('#cmbuserTypeRole').val());
            formData.append('cmbuEmployee', $('#cmbuEmployee').val());




            console.log(formData);


            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/st/savenewUser',
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


                    if (response.status) {
                        showSuccessMessage('Successfully saved');
                        resetForm();
                        employee();
                        userRole();
                        $('#cmbuserTypeRole').val('0').trigger('change');
                        console.log(response);
                    } else {
                        showErrorMessage('Password does not match');

                    }

                },
                error: function (error) {
                    showErrorMessage('Something went wrong');

                    console.log(error);

                },
                complete: function () {

                }

            });
        }


    });

    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var id = param[0].split('=')[1].split('&')[0];
        action = param[0].split('=')[2].split('&')[0];

        if (action == 'edit') {

            getusereditedata(id);


        }


    }
    $('#btnupdate').on('click', function (e) {

        var emailLength = $('#txtEmail').val().length;
        if (emailLength > 0 && $('#txtEmail').hasClass('is-invalid')) {

            showErrorMessage("Please check the email address");
        } else {

            var Password = $('#txtPassword').val();
            var confirmPassword = $('#txtConformPassword').val();
            if (Password == confirmPassword) {

                var id = $('#id').val();
                formData.append('txtname', $('#txtname').val());
                formData.append('txtEmail', $('#txtEmail').val());
                formData.append('txtPassword', $('#txtPassword').val());
                formData.append('cmbuserRole', $('#cmbuserRole').val());
                formData.append('cmbuserTypeRole', $('#cmbuserTypeRole').val());
                formData.append('cmbuEmployee', $('#cmbuEmployee').val());

                console.log(formData);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/st/updateUser/' + id,
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


                    if (response.status) {
                        showSuccessMessage('Successfully saved');
                        closeCurrentTab()
                       // window.location.href = '/userlist';
                       window.opener.location.reload();


                        console.log(response);
                    } else {
                        showErrorMessage('Incorrect password');


                    }

                },
                error: function (error) {
                    showErrorMessage('Password does not match');

                    console.log(error);

                },
                complete: function () {

                }

            });

            } else {
                showErrorMessage('Password does not match');
            }



        }

    });


});


function getusereditedata(id) {


    $.ajax({
        type: "GET",
        url: '/st/usersEdite/' + id,
        processData: false,
        contentType: false,
        cache: false,


        beforeSend: function () {

        },
        success: function (response) {
            $('#btnusersave').hide();
            $('#btnupdate').show();

            console.log(response);

            console.log(response);
            var users = response.user[0];
            $('#id').val(users.id);
            $('#txtname').val(users.name);
            $('#txtEmail').val(users.email);
            $('#cmbuserRole').val(users.role_id).trigger('change');
            $('#cmbuEmployee').val(users.employee_id).trigger('change');
            if (users.user_type == 'Guest') {
                $('#cmbuserTypeRole').val(0);
            } else if(users.user_type == 'Employee') {
                $('#cmbuserTypeRole').val(1);
                $('#empshow').show();
            }
            $('#txtPassword').val('');

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}

//....... user Role Loard



function userRole() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/st/userrole",

        success: function (data) {

            $.each(data, function (key, value) {

                data = data + "<option  id='' value=" + value.id + ">" + value.name + "</option>"
            })

            $('#cmbuserRole').html(data);
        }

    });

}
userRole();


//..Empolyeee loard



function employee() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/st/getemployee",

        success: function (data) {

          //  var options = "<option value=''>Select an employee</option>";
            $.each(data, function (key, value) {
                data += "<option value='" + value.employee_id + "'>" + value.employee_name + "</option>";
            });
            $('#cmbuEmployee').html(data);

        }

    });

}
employee();
$('#cmbuEmployee').val('default-value');

function resetForm() {

    $('#form').trigger('reset');

    $('#empshow').hide();


}

function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);
}
