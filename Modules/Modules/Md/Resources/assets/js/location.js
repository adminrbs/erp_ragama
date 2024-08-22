var formData = new FormData();
$(document).ready(function () {

    /* dropzoneSingle = new Dropzone("#dropzone_single", {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 1, // MB
        maxFiles: 1,
        acceptedFiles: ".png, .jpg, .jpeg",
        dictDefaultMessage: 'Drop file to upload <span>or CLICK</span>',
        autoProcessQueue: false,
        addRemoveLinks: true,
        init: function () {
            var thisDropzone = this;
            var mockFile = { name: 'Name Image', size: 12345, type: 'image/png' };
            thisDropzone.emit("addedfile", mockFile);
            thisDropzone.emit("success", mockFile);
            thisDropzone.emit("thumbnail", mockFile, "../images/profile.png")

            this.on("maxfilesexceeded", function (file) {
                this.removeFile(file);
            });
            this.on('addedfile', function (file) {
                formData.append("file", file);


                if (this.fileTracker) {
                    this.removeFile(this.fileTracker);
                }
                this.fileTracker = file;
            });
            this.on("success", function (file, responseText) {
                console.log(responseText); // console should show the ID you pointed to
            });
            this.on("complete", function (file) {

                this.removeAllFiles(true);
                console.log(file);
            });
        }
    }); */

    $('.select2').select2();
    $('#frmLocation').trigger("reset"); //reset on refresh

    $('#frmLocation').submit(function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }
    });
    getLocationTypes();
    getBranches();

    $('#cmbLocationType').on('change',function(){
        $(this).removeClass('is-invalid').addClass('is-valid');
    });

    var LocationID;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var LocationID = param[0].split('=')[1].split('&')[0];
        action = param[0].split('=')[2].split('&')[0];

        if (action == 'edit') {
            $('#btnSave').text('Update');
        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnReset').hide();
        }
        getEachLocationDetails(LocationID);

    }


    $('#btnReset').on('click', function () {
        $('.validation-invalid-label').empty();
        $('#frmLocation').trigger('reset');
    });


    

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
                        addLocation();

                    } else if ($('#btnSave').text() == 'Update') {
                        updateLocation(LocationID);
                        closeCurrentTab();
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




});

function redirectoLocationList() {
    setTimeout(function () {
        window.location.href = '/locationList';
    }, 1000); // delay of 5000 milliseconds (5 seconds)
}


function addLocation() {
    if($('#cmbLocationType').find(':selected').text() == 'Not Applicable'){
        showWarningMessage('Location is required');
        $('#cmbLocationType').addClass('is-invalid');
        return;
    }
    var chkStatus = $('#chkStatus').is(":checked") ? 1 : 0;

    formData.append('txtName', $('#txtName').val());
    formData.append('txtAddress', $('#txtAddress').val());
    formData.append('txtFixed', $('#txtFixed').val());
    formData.append('txtEmail', $('#txtEmail').val());
    formData.append('cmbLocationType', $('#cmbLocationType').val());
    formData.append('chkStatus', chkStatus);
    formData.append('cmbBranch',$('#cmbBranch').val());

    console.log(formData);


    $.ajax({
        url: '/md/addLocation',
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
            $('#btnSave').prop('disabled', true);

        }, success: function (response) {
            var status = response.status;
            if (status) {
                showSuccessMessage("Successfully saved");
                $('#frmLocation')[0].reset();

            } else {
                console.log(status);
                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }
    });
}

function getLocationTypes() {
    $.ajax({
        url: '/md/getLocationTypes',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbLocationType').append('<option value="' + item.location_type_id + '">' + item.location_type_name + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    })

}

function getEachLocationDetails(id) {

    $.ajax({
        url: '/md/getEachLocationDetails/' + id,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (searchedLocation) {
            var res = searchedLocation.data;
            $('#txtName').val(res.location_name);
            $('#txtAddress').val(res.address);
            $('#txtFixed').val(res.fixed_number);
            $('#txtEmail').val(res.email);
            $('#cmbLocationType').val(res.location_type_id);
            $('#chkStatus').prop('checked', res.Status == 1);
            $('#cmbBranch').val(res.branch_id).trigger('change');

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });

}

function updateLocation(id) {
    if($('#cmbLocationType').find(':selected').text() == 'Not Applicable'){
        showWarningMessage('Location is required');
        $('#cmbLocationType').addClass('is-invalid');
        return;
    }
    var chkStatus = $('#chkStatus').is(":checked") ? 1 : 0;

    formData.append('txtName', $('#txtName').val());
    formData.append('txtAddress', $('#txtAddress').val());
    formData.append('txtFixed', $('#txtFixed').val());
    formData.append('txtEmail', $('#txtEmail').val());
    formData.append('cmbLocationType', $('#cmbLocationType').val());
    formData.append('chkStatus', chkStatus);
    formData.append('cmbBranch',$('#cmbBranch').val());

    console.log(formData);



    $.ajax({
        url: '/md/updateLocation/' + id,
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

/* function loadBranches(){
    $.ajax({
        url: '/md/getBranches',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbBranch').append('<option value="' + item.location_type_id + '">' + item.location_type_name + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    });

} */

function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000); 


}


function getBranches(){
    $.ajax({
        url: '/md/getBranches',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbBranch').append('<option value="' + item.branch_id + '">' + item.branch_name + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }
    })
}

