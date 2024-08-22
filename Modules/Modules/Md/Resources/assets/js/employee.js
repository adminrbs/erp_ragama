
var formData = new FormData();
let dropzoneSingle = undefined;
var file = file;

$(document).ready(function () {
    $('#emp_div').hide();
    dropzoneSingle = new Dropzone("#dropzone_single", {
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 2, // MB
        maxFiles: 1,
        acceptedFiles: ".jpeg,.jpg,.png,",
        dictDefaultMessage: 'Drop file to upload <span>or CLICK</span> (File formats: jpeg,jpg,png)',
        autoProcessQueue: false,
        addRemoveLinks: true,
        selectedImage: undefined,
        imageIcon: undefined,
        init: function () {
            var thisDropzone = this;
            var mockFile = { name: 'Name Image', size: 12345, type: 'image/png' };
            thisDropzone.emit("addedfile", mockFile);
            thisDropzone.emit("success", mockFile);
            thisDropzone.emit("thumbnail", mockFile, "../images/profile.png")
            this.on('addedfile', function (file) {
                console.log(file);
                this.selectedImage = file;

                const reader = new FileReader();
                reader.onload = () => {
                    const width_px = 225;
                    const height_px = 300;
                    const base64 = reader.result; // This is the Data URL of the uploaded file
                    const image = new Image();
                    image.crossOrigin = 'anonymous';
                    image.src = base64;
                    image.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        canvas.height = height_px;
                        canvas.width = width_px;
                        ctx.drawImage(image, 0, 0, width_px, height_px);
                        const dataUrl = canvas.toDataURL();
                        this.imageIcon = dataUrl;
                        //console.log(this.resizeImage);
                    }
                };
                /*if (ACTION == 'save') {
                    reader.readAsDataURL(file);
                }*/
                reader.readAsDataURL(file);
                if (this.fileTracker) {
                    this.removeFile(this.fileTracker);
                }
                this.fileTracker = file;
            });
            this.on('removedfile', function (file) {
                //this.selectedImage = undefined;
            });
            this.on("success", function (file, responseText) {
                console.log(responseText); // console should show the ID you pointed to
            });
            this.on("complete", function (file) {

                this.removeAllFiles(true);
                console.log(file);
            });
            this.on('getSelectedImage', function () {
                return "file"
            });
        }
    });




    empreport();
    employeestatus();
    empdesgnation();
    $('#code_div').hide();
    //showing code text box on sales rep designation
    $('#cmbDesgination').on('change', function () {
        var selected_item = $(this).find('option:selected').text();
        if (selected_item == "Sales Representative") {
            $('#code_div').show();
        } else {
            $('#code_div').hide();
        }



    });
    $('#btnupdate').hide();
    //allow only numbers to code
    $("#txtCode").on("input", function () {
        var inputValue = $(this).val();
        // Remove any non-integer characters using a regular expression
        var sanitizedValue = inputValue.replace(/[^0-9]/g, '');
        // Limit the length to 2 characters
        sanitizedValue = sanitizedValue.slice(0, 3);
        $(this).val(sanitizedValue);
    });

    //getusarname();
    $('#txtNote').on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $(".select2").select2({

        //dropdownParent: $("#frmEmployee")

    });



    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
        }
    });

    $('#btnReset').on('click', function () {
        resetForm();
    });

    //   catch data
    var public_emp_id;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var id = param[0].split('=')[1].split('&')[0];
        public_emp_id = id
        action = param[0].split('=')[2].split('&')[0];

        if (action == 'edit') {

            getEmployeedata(id);
            $('#btnSave').hide();
            $('#btnupdate').show()


        } else if (action == 'view') {
            getEmployeeview(id);


        }
        $('#emp_div').show();

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


                    saveEmployee();
                    //  closeCurrentTab()
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


    $('#btnupdate').on('click', function (event) {
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

                    getEmployeeupdate(public_emp_id);

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


function getusarname(action, id) {
    var userName = $('#txtNameinitial').val();

    formData.append('userName', userName);
    //console.log("username", userName);
    var bool = false;

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/getusarname/' + action + '/' + id,
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        async: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            /* if (response.result === true) {
                 showWarningMessage("Username exists");
                 alert("Username exists")
             } */

            bool = response.result
        },
    });

    return bool;
}



//........... employee save..........
function saveEmployee() {
    var userName_length = $('#txtNameinitial').val().length;

    if ($('#cmbDesgination').val() == 7) {

        var codelength = $('#txtCode').val();
        if (codelength.length < 3) {
            showWarningMessage("Employee code should have three digits");
            return;
        }
        if (userName_length < 3) {
            showWarningMessage("Please enter valid user name");
            return;
        }
        if (getusarname('save', null)) {
            showWarningMessage("Username exists");

            return;
        }

    }
    //validate emp code
    var employee_code_ = $('#txtEmployeeCode').val().length;
    if (employee_code_ < 1) {
        showWarningMessage("Employee code should be entered");
        $('#txtEmployeeCode').addClass('is-invalid');
        return;
    }
    var dateofbirth = $('#txtdateofbirth').val().length;

    if (dateofbirth < 7) {
        showWarningMessage("Please enter valid Date of Birth");

        return;
    }


    var joinDate = $('#txtDateofjoined').val();


    var dateofresign = $('#txtDateofresign').val();

    var emailLength = $('#txtofficeemail').val().length;
    var emailLength = $('#txtPersionalemail').val().length;

    if (emailLength > 0 && $('#txtofficeemail').hasClass('is-invalid')) {

        showErrorMessage("Please check the email address");
    } else if (emailLength > 0 && $('#txtPersionalemail').hasClass('is-invalid')) {
        showErrorMessage("Please check the email address");

    } else if ((dateofresign == "" && joinDate != "") || (joinDate <= dateofresign)) {



        formData.append('file', dropzoneSingle.selectedImage);
        formData.append('imageIcon', dropzoneSingle.imageIcon);

        formData.append('txtEmployeeCode', $('#txtEmployeeCode').val());
        formData.append('txtNameinitial', $('#txtNameinitial').val());
        formData.append('txtNamefull', $('#txtNamefull').val());
        formData.append('txtNamenick', $('#txtNamenick').val());
        formData.append('txtnic', $('#txtnic').val());
        formData.append('txtemagcontact', $('#txtemagcontact').val());
        formData.append('txttown', $('#txttown').val());
        formData.append('txtgps', $('#txtgps').val());
        formData.append('txtdateofbirth', $('#txtdateofbirth').val());
        formData.append('txtcertificatefile', $('#txtcertificatefile').val());
        formData.append('txtfileno', $('#txtfileno').val());
        formData.append('txtOfficemobileno', $('#txtOfficemobileno').val());


        formData.append('txtofficeemail', $('#txtofficeemail').val());
        formData.append('txtPersionalmobile', $('#txtPersionalmobile').val());
        formData.append('txtPersionalfixedno', $('#txtPersionalfixedno').val());
        formData.append('txtPersionalemail', $('#txtPersionalemail').val());
        formData.append('txtAddress', $('#txtAddress').val());
        formData.append('cmbDesgination', $('#cmbDesgination').val());
        formData.append('cmbReport', $('#cmbReport').val());
        formData.append('txtDateofjoined', $('#txtDateofjoined').val());
        formData.append('txtDateofresign', $('#txtDateofresign').val());
        formData.append('cmbempStatus', $('#cmbempStatus').val());
        formData.append('txtNote', $('#txtNote').val());
        formData.append('txtAlertcreaditamountlimit', $('#txtAlertcreaditamountlimit').val());
        formData.append('txtHoldcreditamountlimit', $('#txtHoldcreditamountlimit').val());
        formData.append('txtAlertcreditperiodlimit', $('#txtAlertcreditperiodlimit').val());
        formData.append('txtHoldcreditperiodlimit', $('#txtHoldcreditperiodlimit').val());
        formData.append('txtPDchequeamountlimit', $('#txtPDchequeamountlimit').val());
        formData.append('txtMaximumPdchequeperiod', $('#txtMaximumPdchequeperiod').val());
        formData.append('txtSalesTarget', $('#txtSalesTarget').val());
        if ($('#cmbDesgination').val() == 7) {
            formData.append('code', $('#txtCode').val());
        }


        

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/md/saveEmployee',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            timeout: 800000,
            beforeSend: function () {

            },
            success: function (response) {
                console.log(response);
                var msg = response.message;
                if (msg == "duplicated") {
                    showWarningMessage('Code can not be duplicated');
                    $('#txtcode').addClass('is-invalid');
                    return;

                } else if (msg == "code_duplicated") {
                    showWarningMessage('Employee Code can not be duplicated');
                    $('#txtEmployeeCode').addClass('is-invalid');
                    return;

                }
                if (response.status) {

                    resetForm();
                    showSuccessMessage('Successfully saved');
                    url = "/md/employeeList";
                    window.location.href = url;
                    // window.opener.location.reload();
                } else {
                    showErrorMessage('Something went wrong');
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


//...........load Data....


function getEmployeedata(id) {

    $.ajax({
        type: "GET",
        url: '/md/getEmployeedata/' + id,
        processData: false,
        contentType: false,
        cache: false,
        async: false,

        beforeSend: function () {

        },
        success: function (response) {
            /*   $('#btnSave').hide();
              $('#btnupdate').show(); */

            console.log(response);
            var employee = response.employee;
            userPassword = employee.mobile_app_password
            $('#id').val(employee.employee_id);
            $('#txtEmployeeCode').val(employee.employee_code);
            $('#txtNameinitial').val(employee.employee_name);
            $('#txtNamefull').val(employee.full_name);
            $('#txtNamenick').val(employee.nick_name);
            $('#txtnic').val(employee.nic_no);
            $('#txtemagcontact').val(employee.emergency_contact_number);
            $('#txttown').val(employee.from_town);
            $('#txtgps').val(employee.gps);
            $('#txtdateofbirth').val(employee.date_of_birth);
            $('#txtcertificatefile').val(employee.certificate_file_no);
            $('#txtfileno').val(employee.file_no);


            $('#txtOfficemobileno').val(employee.office_mobile);
            $('#txtofficeemail').val(employee.office_email);
            $('#txtPersionalmobile').val(employee.persional_mobile);
            $('#txtPersionalfixedno').val(employee.persional_fixed);
            $('#txtPersionalemail').val(employee.persional_email);
            $('#txtAddress').val(employee.address);
            $('#cmbDesgination').val(employee.desgination_id);
            $('#cmbDesgination').change();
            $('#cmbReport').val(employee.report_to);
            $('#txtDateofjoined').val(employee.date_of_joined);
            $('#txtDateofresign').val(employee.date_of_resign);
            $('#cmbempStatus').val(employee.status_id);

            $('#txtNote').val(employee.note);

            $('#txtAlertcreaditamountlimit').val(employee.credit_amount_alert_limit);
            $('#txtHoldcreditamountlimit').val(employee.credit_amount_hold_limit);
            $('#txtAlertcreditperiodlimit').val(employee.credit_period_alert_limit);
            $('#txtHoldcreditperiodlimit').val(employee.credit_period_hold_limit);
            $('#txtPDchequeamountlimit').val(employee.pd_cheque_limit);
            $('#txtMaximumPdchequeperiod').val(employee.pd_cheque_max_period);
            $('#txtSalesTarget').val(employee.sales_target);
            $('#txtCode').val(employee.code);
            $('.dz-image').find('img').attr('src', employee.employee_attachments);

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}


//........... employee update..........


function getEmployeeupdate(id) {
    //validate emp code
    var employee_code_ = $('#txtEmployeeCode').val().length;
    if (employee_code_ < 1) {
        showWarningMessage("Employee code should be entered");
        $('#txtEmployeeCode').addClass('is-invalid');
        return;
    }
    var userName_length = $('#txtNameinitial').val().length;

    if ($('#cmbDesgination').val() == 7) {

        var codelength = $('#txtCode').val();
        if (codelength.length < 3) {
            showWarningMessage("Employee code should have three digits");
            return;
        }
        if (userName_length < 3) {
            showWarningMessage("Please enter valid user name");
            return;
        }

    }

    var joinDate = $('#txtDateofjoined').val();

    var dateofresign = $('#txtDateofresign').val();
    /* var emailLength = $('#txtuserName').val().length; */
    if(dateofresign != ""){
        if (joinDate <= dateofresign) {
            formData.append('txtEmployeeCode', $('#txtEmployeeCode').val());
            formData.append('txtNameinitial', $('#txtNameinitial').val());
            formData.append('txtNamefull', $('#txtNamefull').val());
            formData.append('txtNamenick', $('#txtNamenick').val());
            formData.append('txtnic', $('#txtnic').val());
            formData.append('txtemagcontact', $('#txtemagcontact').val());
            formData.append('txttown', $('#txttown').val());
            formData.append('txtgps', $('#txtgps').val());
            formData.append('txtdateofbirth', $('#txtdateofbirth').val());
            formData.append('txtcertificatefile', $('#txtcertificatefile').val());
            formData.append('txtfileno', $('#txtfileno').val());
            formData.append('txtOfficemobileno', $('#txtOfficemobileno').val());
            formData.append('txtofficeemail', $('#txtofficeemail').val());
            formData.append('txtPersionalmobile', $('#txtPersionalmobile').val());
            formData.append('txtPersionalfixedno', $('#txtPersionalfixedno').val());
            formData.append('txtPersionalemail', $('#txtPersionalemail').val());
            formData.append('txtAddress', $('#txtAddress').val());
            formData.append('cmbDesgination', $('#cmbDesgination').val());
            formData.append('cmbReport', $('#cmbReport').val());
            formData.append('txtDateofjoined', $('#txtDateofjoined').val());
            formData.append('txtDateofresign', $('#txtDateofresign').val());
            formData.append('cmbempStatus', $('#cmbempStatus').val());
    
            formData.append('txtNote', $('#txtNote').val());
            formData.append('txtAlertcreaditamountlimit', $('#txtAlertcreaditamountlimit').val());
            formData.append('txtHoldcreditamountlimit', $('#txtHoldcreditamountlimit').val());
            formData.append('txtAlertcreditperiodlimit', $('#txtAlertcreditperiodlimit').val());
            formData.append('txtHoldcreditperiodlimit', $('#txtHoldcreditperiodlimit').val());
            formData.append('txtPDchequeamountlimit', $('#txtPDchequeamountlimit').val());
            formData.append('txtMaximumPdchequeperiod', $('#txtMaximumPdchequeperiod').val());
            formData.append('txtSalesTarget', $('#txtSalesTarget').val());
            if ($('#cmbDesgination').val() == 7) {
                formData.append('code', $('#txtCode').val());
            }
    
            formData.append('file', dropzoneSingle.selectedImage);
            formData.append('imageIcon', dropzoneSingle.imageIcon);
    
            console.log(formData);
    
            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
                url: '/md/Employee/update/' + id,
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
    
                    var status = response.status;
                    var msg = response.message;
                    if (status) {
                        showSuccessMessage('Updated');
                        window.opener.location.reload();
    
                        closeCurrentTab();
                    } else if (msg == "code_duplicated") {
                        showWarningMessage('Employee code can not be duplicated');
                        return;
                    } else {
                        showWarningMessage('Unable to update');
                        return;
                    }
    

                }, error: function (error) {
                    showErrorMessage('Something went wrong');
                    // window.opener.location.reload();
                    console.log(error);
                }
            });
        } else {
            showErrorMessage("Please enter the valide date");
        }

    }else{

            formData.append('txtEmployeeCode', $('#txtEmployeeCode').val());
            formData.append('txtNameinitial', $('#txtNameinitial').val());
            formData.append('txtNamefull', $('#txtNamefull').val());
            formData.append('txtNamenick', $('#txtNamenick').val());
            formData.append('txtnic', $('#txtnic').val());
            formData.append('txtemagcontact', $('#txtemagcontact').val());
            formData.append('txttown', $('#txttown').val());
            formData.append('txtgps', $('#txtgps').val());
            formData.append('txtdateofbirth', $('#txtdateofbirth').val());
            formData.append('txtcertificatefile', $('#txtcertificatefile').val());
            formData.append('txtfileno', $('#txtfileno').val());
            formData.append('txtOfficemobileno', $('#txtOfficemobileno').val());
            formData.append('txtofficeemail', $('#txtofficeemail').val());
            formData.append('txtPersionalmobile', $('#txtPersionalmobile').val());
            formData.append('txtPersionalfixedno', $('#txtPersionalfixedno').val());
            formData.append('txtPersionalemail', $('#txtPersionalemail').val());
            formData.append('txtAddress', $('#txtAddress').val());
            formData.append('cmbDesgination', $('#cmbDesgination').val());
            formData.append('cmbReport', $('#cmbReport').val());
            formData.append('txtDateofjoined', $('#txtDateofjoined').val());
            formData.append('txtDateofresign', $('#txtDateofresign').val());
            formData.append('cmbempStatus', $('#cmbempStatus').val());
    
            formData.append('txtNote', $('#txtNote').val());
            formData.append('txtAlertcreaditamountlimit', $('#txtAlertcreaditamountlimit').val());
            formData.append('txtHoldcreditamountlimit', $('#txtHoldcreditamountlimit').val());
            formData.append('txtAlertcreditperiodlimit', $('#txtAlertcreditperiodlimit').val());
            formData.append('txtHoldcreditperiodlimit', $('#txtHoldcreditperiodlimit').val());
            formData.append('txtPDchequeamountlimit', $('#txtPDchequeamountlimit').val());
            formData.append('txtMaximumPdchequeperiod', $('#txtMaximumPdchequeperiod').val());
            formData.append('txtSalesTarget', $('#txtSalesTarget').val());
            if ($('#cmbDesgination').val() == 7) {
                formData.append('code', $('#txtCode').val());
            }
    
            formData.append('file', dropzoneSingle.selectedImage);
            formData.append('imageIcon', dropzoneSingle.imageIcon);
    
            console.log(formData);
    
            $.ajax({
                type: 'POST',
                enctype: 'multipart/form-data',
                url: '/md/Employee/update/' + id,
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
    
                    var status = response.status;
                    var msg = response.message;
                    if (status) {
                        showSuccessMessage('Updated');
                        window.opener.location.reload();
    
                        closeCurrentTab();
                    } else if (msg == "code_duplicated") {
                        showWarningMessage('Employee code can not be duplicated');
                        return;
                    } else {
                        showWarningMessage('Unable to update');
                        return;
                    }
    

                }, error: function (error) {
                    showErrorMessage('Something went wrong');
                    // window.opener.location.reload();
                    console.log(error);
                }
            });
        

    }
    

}

//..........employee view......

function getEmployeeview(id) {
    $.ajax({
        type: "GET",
        url: '/md/getEmployeeview/' + id,
        processData: false,
        contentType: false,
        cache: false,

        beforeSend: function () {

        },
        success: function (response) {
            $('#btnSave').hide();
            $('#btnupdate').hide();
            $('#btnReset').hide();
            console.log(response);
            var employee = response.employee;

            $('#txtEmployeeCode').val(employee.employee_code);
            $('#id').val(employee.employee_id);
            $('#txtEmployeeCode').val(employee.employee_code);
            $('#txtNameinitial').val(employee.employee_name);
            $('#txtNamefull').val(employee.full_name);
            $('#txtNamenick').val(employee.nick_name);
            $('#txtnic').val(employee.nic_no);
            $('#txtemagcontact').val(employee.emergency_contact_number);
            $('#txttown').val(employee.from_town);
            $('#txtgps').val(employee.gps);
            $('#txtdateofbirth').val(employee.date_of_birth);
            $('#txtcertificatefile').val(employee.certificate_file_no);
            $('#txtfileno').val(employee.file_no);

            $('#txtOfficemobileno').val(employee.office_mobile);
            $('#txtofficeemail').val(employee.office_email);
            $('#txtPersionalmobile').val(employee.persional_mobile);
            $('#txtPersionalfixedno').val(employee.persional_fixed);
            $('#txtPersionalemail').val(employee.persional_email);
            $('#txtAddress').val(employee.address);
            $('#cmbDesgination').val(employee.desgination_id);
            $('#cmbDesgination').change();
            $('#cmbReport').val(employee.report_to);
            $('#txtDateofjoined').val(employee.date_of_joined);
            $('#txtDateofresign').val(employee.date_of_resign);
            $('#cmbempStatus').val(employee.status_id);
            $('#txtNote').val(employee.note);

            $('#txtAlertcreaditamountlimit').val(employee.credit_amount_alert_limit);
            $('#txtHoldcreditamountlimit').val(employee.credit_amount_hold_limit);
            $('#txtAlertcreditperiodlimit').val(employee.credit_period_alert_limit);
            $('#txtHoldcreditperiodlimit').val(employee.credit_period_hold_limit);
            $('#txtPDchequeamountlimit').val(employee.pd_cheque_limit);
            $('#txtMaximumPdchequeperiod').val(employee.pd_cheque_max_period);
            $('#txtCode').val(employee.code);
            $('.dz-image').find('img').attr('src', employee.employee_attachments);

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}

function resetForm() {
    $('.validation-invalid-label').empty();
    $('#frmEmployee').trigger('reset');
}

function btnCustommerAppDelete(id) {

    if (confirm("Do you want to delete this record?")) {
        $.ajax({
            type: 'DELETE',
            url: "/md/deletecustomerApp/" + id,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _token: $("input[name=_token]").val()
            },

            success: function (response) {
                customeerUserappAllData();
                $('#customerAppSearch').val('');


            }
        });

    }
}




//..................combo box loard...................

function empdesgnation() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/empdesgnation",
        async: false,
        success: function (data) {


            $.each(data, function (key, value) {

                var isChecked = "";
                if (value.status_id) {
                    isChecked = "checked";
                }


                data = data + "<option id='' value=" + value.employee_designation_id + ">" + value.employee_designation + "</option>"


            })

            $('#cmbDesgination').html(data);

        }

    });

}



function empreport() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/empreport",

        success: function (response) {
            var data = response

            $.each(data, function (key, value) {


                data = data + "<option id='' value=" + value.employee_id + ">" + value.employee_name + "</option>"


            })

            $('#cmbReport').html(data);

        }

    });

}




function employeestatus() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/getemployeestatus",
        success: function (data) {
            $.each(data, function (key, value) {

                var isChecked = "";
                if (value.status_id) {
                    isChecked = "checked";
                }
                data = data + "<option  id='' value=" + value.employee_status_id + ">" + value.employee_status + "</option>"
            })
            $('#cmbempStatus').html(data);
        }
    });
}

function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);
}
