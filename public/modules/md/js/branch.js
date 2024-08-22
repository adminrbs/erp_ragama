var formData = new FormData();
$(document).ready(function () {
    $('#btnSave').show();
    $('#btnUpdate').hide();

    //allow only numbers to code
    $("#txtcode").on("input", function () {
        var inputValue = $(this).val();
        // Remove any non-integer characters using a regular expression
        var sanitizedValue = inputValue.replace(/[^0-9]/g, '');
        // Limit the length to 2 characters
        sanitizedValue = sanitizedValue.slice(0, 2);
        $(this).val(sanitizedValue);
    });


    // Listen for changes on the "User Type" dropdown

    var BRANCHID;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var BRANCHID = param[0].split('=')[1].split('&')[0];
        action = param[0].split('=')[2].split('&')[0];

        if (action == 'edit') {
            $('#btnSave').text('Update');

            getBranchdata(BRANCHID);

        } else if (action == 'view') {
            $('#btnSave').hide();
            $('.btnRese').hide();
            $('#btnUpdate').hide();
            $('input[type="text"]').prop('disabled', true);
            $('input[type="email"]').prop('disabled', true);
            $('input[type="checkbox"]').prop('disabled', true);

            getBranchview(BRANCHID);
        }

    }
    $('#btnSave').on('click', function (event) {

        bootbox.confirm({
            title: 'Save confirmation',
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
                console.log(result);
                if (result) {
                    if ($('#btnSave').text() == 'Save') {
                        saveBranch();

                    } else if ($('#btnSave').text() == 'Update') {
                        getBranchupdate(BRANCHID);
                       
                    }
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



    });


    function saveBranch() {


        var emailLength = $('#txtEmail').val().length;
        var codelength = $('#txtcode').val().length;
        if (emailLength > 0 && $('#txtEmail').hasClass('is-invalid')) {

            showErrorMessage("Please check the email address");
        } else if(codelength < 2){
            showWarningMessage("Code should have two digits");
            $('#txtcode').addClass('is-invalid');
        } else{
            var chkStatus = $('#chkStatus').is(":checked") ? 1 : 0;
            formData.append('txtName', $('#txtName').val());
            formData.append('txtAddress', $('#txtAddress').val());
            formData.append('txtFixed', $('#txtFixed').val());
            formData.append('txtEmail', $('#txtEmail').val());
            formData.append('chkStatus', chkStatus);
            formData.append('txtBranchPrefix',$('#txtBranchPrefix').val());
            formData.append('code',$('#txtcode').val());

            console.log(formData);


            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/md/savBranch',
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

                    var msg = response.message;
                    if(msg == "duplicated"){
                        showWarningMessage('Code can not be duplicated');
                        $('#txtcode').addClass('is-invalid');
                        return;

                    }else if (msg == "length"){
                        showWarningMessage('Code should have two digits');
                        $('#txtcode').addClass('is-invalid');
                        return;

                    }

                    if (response.status) {
                        showSuccessMessage('Successfully saved');
                        resetForm();

                        console.log(response);
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


    }



    function resetForm() {

        $('#frmBranch').trigger('reset');
    }


}
);

function getBranchupdate(id) {
    var chkStatus = $('#chkStatus').is(":checked") ? 1 : 0;

    formData.append('txtName', $('#txtName').val());
    formData.append('txtAddress', $('#txtAddress').val());
    formData.append('txtFixed', $('#txtFixed').val());
    formData.append('txtEmail', $('#txtEmail').val());
    formData.append('chkStatus', chkStatus);
    formData.append('txtBranchPrefix',$('#txtBranchPrefix').val());
    formData.append('code',$('#txtcode').val());

    console.log(formData);



    $.ajax({
        url: '/md/updatebranch/' + id,
        method: 'post',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            var status = response.status;
            if (status) {
                showSuccessMessage("Successfully updated");
                closeCurrentTab();
                window.opener.location.reload();
            } else {
                showErrorMessage("Something went wrong");
            }





        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });

}



function getBranchdata(id) {


    $.ajax({
        type: "GET",
        url: '/md/branchEdite/' + id,
        processData: false,
        contentType: false,
        cache: false,


        beforeSend: function () {

        },
        success: function (searchedLocation) {

            var res = searchedLocation.data;





            $('#txtName').val(res.branch_name);
            $('#txtAddress').val(res.address);
            $('#txtFixed').val(res.fixed_number);
            $('#txtEmail').val(res.email);
            $('#chkStatus').prop('checked', res.is_active == 1);
            $('#txtBranchPrefix').val(res.prefix);
            $('#txtcode').val(res.code);


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}


function getBranchview(id) {
    $.ajax({
        type: "GET",
        url: '/md/getBranchview/' + id,
        processData: false,
        contentType: false,
        cache: false,

        beforeSend: function () {

        },
        success: function (branch) {
            $('#btnSave').hide();
            $('#btnupdate').hide();
           
            var res = branch.data;

            $('#txtName').val(res.branch_name);
            $('#txtAddress').val(res.address);
            $('#txtFixed').val(res.fixed_number);
            $('#txtEmail').val(res.email);
            $('#chkStatus').prop('checked', res.is_active == 1);
            $('#txtBranchPrefix').val(res.prefix);
            $('#txtcode').val(res.code).prop('disabled',true);
            

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}

function getBranchPrefix(){
    
}




function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);


}
