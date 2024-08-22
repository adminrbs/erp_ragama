var formData = new FormData();
$(document).ready(function () {

    $('#btnCustomApp').on('click', function () {
        $('#btncustomeruserApp').show();
        $('#btnUpdatecustomeruserApp').hide();
        $('#passName').html("Password<span class='text-danger'>*</span>");
        $('#id').val('');
        $("#txtEmailcustomer").val('');
        $("#txtMobilphonecustomer").val('');
        $("#txtPasswordcustomer").val('');
        $('input[type="text"]').val('');

    });


    // Default initialization
    //$('.select').select2();
    $(".select2").select2({
        dropdownParent: $("#modalCustomerApp")

    });
    // End of Default initialization
    ///////////////////////////close//////////


    // close

    $("#btnCloseCustomerApp").on("click", function (e) {
        $('#modalCustomerApp').modal('hide');
    });

    // Customer user App


    $('#btncustomeruserApp').show();
    $('#btnUpdatecustomeruserApp').hide();

    $('#btncustomeruserApp').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveCustomeerUserapp();
    });

    //...Customer user App Update

    $('#btnUpdatecustomeruserApp').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateCustomeerUserapp();
    });

    $('#cmbcustomerApp').on('change', function () {
        var customerid = $(this).val();
        selectCustomer(customerid);
        townadmistrative(customerid);
    });


    loadBranches_cus_app();
    customeerUserappAllData();
});


//load branch
function loadBranches_cus_app(){
    $.ajax({
        type: "GET",
        url: "/sd/loadBranches_cus_app",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response;

           
            for (var i = 0; i < dt.length; i++) {
                var htmlContent = "";
                htmlContent += "<option value=''>Select Branch</option>";

                $.each(dt, function (key, value) {

                     htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });


            $('#cmbBranch').html(htmlContent);
            }

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}



function customeerUserappAllData() {


    $.ajax({
        type: "GET",
        url: "/sd/customeruserApp",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].status_id ? "checked" : "";

                data.push({

                    "customer_app_user_id": dt[i].customer_app_user_id,
                    "customer_id": dt[i].customer_name,
                    "email": dt[i].email,
                    "mobile": dt[i].mobile,
                    "branch": dt[i].branch_name,
                    "edit": '<button class="btn btn-primary customerEdit" data-bs-toggle="modal" data-bs-target="#modalCustomerApp" id="' + dt[i].customer_app_user_id + '"><i class="fa fa-pencil-square-o"  aria-hidden="true"></i></button>',
                    "delete": '&#160<button class="btn btn-danger" onclick="btnCustommerAppDelete(' + dt[i].customer_app_user_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                    "status": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxCustomerApp" value="1" onclick="cbxCustomerappStatus(' + dt[i].customer_app_user_id + ')" required ' + isChecked + '></lable>',
                });
            }


            var table = $('#customerAppTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

customeerUserappAllData();



//.....customer app user Save.....
function saveCustomeerUserapp() {

    var password = $('#txtPasswordcustomer').val();
    var confirm_pass = $('#txtConfirmPassword').val();

    var emailLength = $('#txtEmailcustomer').val().length;
    var mobile = $('#txtMobilphonecustomer').val().length;
    var customerName = $('#cmbcustomerApp').val();
    var branch_id = $('#cmbBranch').val();


    if(password != confirm_pass){
        showWarningMessage("Please re-enter the confirm password");
        $('#txtConfirmPassword').addClass('is-invalid');
        return;
    }

    if (customerName == null || customerName == "") {
        showWarningMessage("Please Enter Customer Name");
    } else {

    if (emailLength > 0) {

        if ($('#txtEmailcustomer').hasClass('is-invalid')) {

            showWarningMessage("Please check the email address");
        } else if (password === "") {
            showWarningMessage("Please Enter Password");

        } else if (password.length < 6) {
            showWarningMessage("Minimum Charactors Of Password Should be 7");
        } else if(branch_id == ""){
            showWarningMessage("Branch should be selected");
        
        }else {

            formData.append('cmbcustomerApp', $('#cmbcustomerApp').val());
            formData.append('txtEmailcustomer', $('#txtEmailcustomer').val());
            formData.append('txtMobilphonecustomer', $('#txtMobilphonecustomer').val());
            formData.append('txtPasswordcustomer', $('#txtPasswordcustomer').val());
            formData.append('cmbcustomercode', $('#cmbcustomercode').val());
            formData.append('cmbaddress', $('#cmbaddress').val());
            formData.append('cmbadTown', $('#cmbadTown').val());
            formData.append('branch_id',branch_id);

            console.log(formData);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/sd/saveCustomeerUserapp',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 800000,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    // Perform any tasks before sending the request
                },
                success: function (response) {
                    customeerUserappAllData();
                    $('#modalCustomerApp').modal('hide');

                    if (response.status) {
                        showSuccessMessage('Successfully saved');
                    } else {
                        showErrorMessage("Something went worng");
                    }

                    console.log(response);
                },
                error: function (error) {
                    showErrorMessage('Something went wrong');
                    console.log(error);
                }
            });
        }


    }

    else if (mobile >= 10 && mobile <= 10) {
        if (password === "") {
            showWarningMessage("Please Enter Password");

        } else if (password.length < 6) {
            showWarningMessage("Minimum Charactors Of Password Should be 7");

            /*} else if (mobile < 10) {
                showWarningMessage("Please Enter Correct Mobile Number");*/
        } else {


            formData.append('cmbcustomerApp', $('#cmbcustomerApp').val());
            formData.append('txtEmailcustomer', $('#txtEmailcustomer').val());
            formData.append('txtMobilphonecustomer', $('#txtMobilphonecustomer').val());
            formData.append('txtPasswordcustomer', $('#txtPasswordcustomer').val());
            formData.append('cmbcustomercode', $('#cmbcustomercode').val());
            formData.append('cmbaddress', $('#cmbaddress').val());
            formData.append('cmbadTown', $('#cmbadTown').val());

            console.log(formData);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/sd/saveCustomeerUserapp',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                timeout: 800000,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    // Perform any tasks before sending the request
                },
                success: function (response) {
                    customeerUserappAllData();
                    $('#modalCustomerApp').modal('hide');

                    if (response.status) {
                        showSuccessMessage('Successfully saved');
                    } else {
                        showErrorMessage("Something went worng");
                    }

                    console.log(response);
                },
                error: function (error) {
                    showErrorMessage('Something went wrong');
                    console.log(error);
                }
            });
        }


    } else {
        showWarningMessage("Please Enter Email Or Mobile Number");
    }
}

    /*
        if ($('#txtEmailcustomer').hasClass('is-invalid')) {
    
            showWarningMessage("Please check the email address");
        } else if (password === "") {
            showWarningMessage("Please Enter Password");
    
        } else if (password.length < 6) {
            showWarningMessage("Minimum Charactors Of Password Should be 7");
    
        } else if (mobile < 10) {
            showWarningMessage("Please Enter Correct Mobile Number");
    
        } else {*/


}

//.......edit......

$(document).on('click', '.customerEdit', function (e) {

    e.preventDefault();
    let customer_app_user_id = $(this).attr('id');
    $.ajax({
        url: '/sd/customerEdit/' + customer_app_user_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            $('#btncustomeruserApp').hide();
            $('#btnUpdatecustomeruserApp').show();


            $('#id').val(response.customer_app_user_id);
            $("#cmbcustomerApp").val(response.customer_id).trigger('change');
            $("#txtEmailcustomer").val(response.email);
            $("#txtMobilphonecustomer").val(response.mobile);

            $("#txtPasswordcustomer").val("");
            $("#cmbBranch").val(response.branch_id);
            $('#passName').html("Update Password");



        }
    });
});


//....customer app user Update


function updateCustomeerUserapp() {

    var id = $('#id').val();
    var password = $('#txtPasswordcustomer').val()

    var emailLength = $('#txtEmailcustomer').val().length;
    var mobile = $('#txtMobilphonecustomer').val().length;
    var customerName = $('#cmbcustomerApp').val();
    if (customerName == null || customerName == "") {
        showWarningMessage("Please Enter Customer Name");
    } else {





        if (emailLength > 0) {

            if ($('#txtEmailcustomer').hasClass('is-invalid')) {

                showWarningMessage("Please check the email address");
            } else if (password === "") {
                showWarningMessage("Please Enter Password");

            } else if (password.length < 6) {
                showWarningMessage("Minimum Charactors Of Password Should be 7");
            } else {

                formData.append('cmbcustomerApp', $('#cmbcustomerApp').val());
                formData.append('txtEmailcustomer', $('#txtEmailcustomer').val());
                formData.append('txtMobilphonecustomer', $('#txtMobilphonecustomer').val());
                formData.append('txtPasswordcustomer', $('#txtPasswordcustomer').val());
                formData.append('cmbcustomercode', $('#cmbcustomercode').val());
                formData.append('cmbaddress', $('#cmbaddress').val());
                formData.append('cmbadTown', $('#cmbadTown').val());
                var branch_id = $('#cmbBranch').val();
                formData.append('branch_id',branch_id);

                console.log(formData);
                $.ajax({
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    url: '/sd/customerAppUpdate/' + id,
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

                        customeerUserappAllData();
                        $('#modalCustomerApp').modal('hide');

                        showSuccessMessage('Successfully updated');



                    }, error: function (error) {
                        showErrorMessage('Something went wrong');
                        //$('#modalCustomerApp').modal('hide');
                        console.log(error);
                    }
                });
            }

        }

        else if (mobile >= 10 && mobile <= 10) {
            if (password === "") {
                showWarningMessage("Please Enter Password");

            } else if (password.length < 6) {
                showWarningMessage("Minimum Charactors Of Password Should be 7");

                /*} else if (mobile < 10) {
                    showWarningMessage("Please Enter Correct Mobile Number");*/
            } else {


                formData.append('cmbcustomerApp', $('#cmbcustomerApp').val());
                formData.append('txtEmailcustomer', $('#txtEmailcustomer').val());
                formData.append('txtMobilphonecustomer', $('#txtMobilphonecustomer').val());
                formData.append('txtPasswordcustomer', $('#txtPasswordcustomer').val());
                formData.append('cmbcustomercode', $('#cmbcustomercode').val());
                formData.append('cmbaddress', $('#cmbaddress').val());
                formData.append('cmbadTown', $('#cmbadTown').val());

                console.log(formData);
                $.ajax({
                    type: 'POST',
                    enctype: 'multipart/form-data',
                    url: '/sd/customerAppUpdate/' + id,
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

                        customeerUserappAllData();
                        $('#modalCustomerApp').modal('hide');

                        showSuccessMessage('Successfully updated');



                    }, error: function (error) {
                        showErrorMessage('Something went wrong');
                        //$('#modalCustomerApp').modal('hide');
                        console.log(error);
                    }
                });
            }


        } else {
            showWarningMessage("Please Enter Email Or Mobile Number");
        }
    }


}

function btnCustommerAppDelete(id) {

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
                className: 'btn-info'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                deleteCustomApp(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteCustomApp(id) {

    $.ajax({
        type: 'DELETE',
        url: '/sd/deletecustomerApp/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            customeerUserappAllData();
            $('#customerAppSearch').val('');
            showSuccessMessage('Successfully deleted');
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




function cbxCustomerappStatus(customer_app_user_id) {
    var status = $('#cbxCustomerApp').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/sd/customerAppStatus/' + customer_app_user_id,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'status': status
        },
        success: function (response) {
            console.log("data save");
            showSuccessMessage('saved');
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




function customeername() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/customername",

        success: function (data) {
            var htmlContent = "";
            htmlContent += "<option value=''>Select Customer</option>";

            $.each(data, function (key, value) {

                htmlContent += "<option value='" + value.customer_id + "'>" + value.customer_name + "</option>";
            });


            $('#cmbcustomerApp').html(htmlContent);



        }

    });

}

customeername();

function townadmistrative(id) {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/townadmistrative/" + id,

        success: function (response) {


            $.each(response, function (key, value) {

                response = response + "<option  id='' value=" + value.town_id + ">" + value.townName + "</option>"


            })

            $('#cmbadTown').html(response);

        }

    });

}
function selectCustomer(id) {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/selectCustomer/" + id,

        success: function (response) {

            $("#cmbcustomercode").val(response.customer_code);
            $("#cmbaddress").val(response.primary_address);
            $("#cmbadTown").val(response.town);

        }

    });
}



///////////////////////////////////////////////////////////////////////



const DatatableFixedColumns = function () {


    //
    // Setup module components
    //

    // Basic Datatable examples
    const _componentDatatableFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            columnDefs: [{
                orderable: false,

                width: 100,
                targets: [2]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });



        // Left and right fixed columns
        var table = $('.datatable-fixed-both').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2,


                },
                {
                    width: 200,
                    targets: 0,

                },
                {
                    width: '100%',
                    targets: 1,


                },
                {
                    width: 300,
                    targets: [2]
                },
                {
                    width: 200,
                    targets: [3]
                },

            ],

            scrollX: true,
            /*    scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "customer_app_user_id" },
                { "data": "customer_id" },
                { "data": "email" },
                { "data": "mobile" },
                { "data": "branch" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "status" },



            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false);


        //
        // Fixed column with complex headers
        //

    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});
//.............................Auto Complete.............

