var formData = new FormData();
$(document).ready(function () {

    branch();

    // Date Range Basic initialization
    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
        }
    });

    $('#txtRemarks').on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    $('#btnvehicle').on('click', function () {
        $('#btnSaveVehicle').show();
        $('#btnUpdateVehicle').hide();
        $('#id').val('');
        $("#txtvehicleNo").val('');
        $("#txtVehicleName").val('');
        $("#txtDescription").val('');
        $("#txtRemarks").val('');
        $('input[type="date"]').val('');





    });

    // close

    $("#btnCloseVehicle").on("click", function (e) {
        // Prevent the default form submission behavior
        e.preventDefault();
        var formData = $("form").serialize();
        $.ajax({
            type: "POST",
            url: '/md/close',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function (response) {
                $("#modalVehicle").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];

            },
            error: function (xhr, status, error) {

            }
        });
    });



    $('#btnSaveVehicle').show();
    $('#btnUpdateVehicle').hide();

    $('#btnSaveVehicle').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        savevehicle();
    });

    //...Customer user App Update

    $('#btnUpdateVehicle').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateVehicle();
    });



});



const DatatableFixedColumnss = function () {


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
        var table = $('#vehicleTable').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 200,
                    targets: [2]
                }

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [

                { "data": "vehicle_id" },
                { "data": "vehicle_no" },
                { "data": "vehicle_name" },
                { "data": "vtype" },
                { "data": "branch" },
                { "data": "lexpierdate" },
                { "data": "inexpierdate" },
                //{ "data": "description" },
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
    DatatableFixedColumnss.init();
});



function vehicaleAllData() {


    $.ajax({
        type: "GET",
        url: "/md/getvehicaleAlldata",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;
            console.log(dt);
            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].status_id ? "checked" : "";

                data.push({

                    "vehicle_id": dt[i].vehicle_id,
                    "vehicle_no": dt[i].vehicle_no,
                    "vehicle_name": dt[i].vehicle_name,
                    "vtype": dt[i].vehicle_type,
                    "branch": dt[i].branch_name,
                    "lexpierdate": dt[i].licence_expire_date,
                    "inexpierdate": dt[i].insurance_expire_date,
                    //"description": dt[i].description,
                    "edit": '<button class="btn btn-primary vehicaleEdit" data-bs-toggle="modal" data-bs-target="#modalVehicle" id="' + dt[i].vehicle_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                    "delete": '&#160<button class="btn btn-danger" onclick="btnVehicaleDelete(' + dt[i].vehicle_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                    "status": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxVehicle" value="1" onclick="cbxVehicleStatus(' + dt[i].vehicle_id + ')" required ' + isChecked + '></lable>',
                });
            }



            var table = $('#vehicleTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

vehicaleAllData();



//..... Save.....
function savevehicle() {

    formData.append('txtvehicleNo', $('#txtvehicleNo').val());
    formData.append('txtVehicleName', $('#txtVehicleName').val());
    formData.append('txtDescription', $('#txtDescription').val());
    formData.append('cmbVehicleType', $('#cmbVehicleType').val());
    formData.append('txtLicenceExpire', $('#txtLicenceExpire').val());
    formData.append('txtInsuranceExpire', $('#txtInsuranceExpire').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('cmbbranch', $('#cmbbranch').val());

    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/savevehicle',
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
            vehicaleAllData();
            $('#modalVehicle').modal('hide');

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

//.......edit......

$(document).on('click', '.vehicaleEdit', function (e) {

    e.preventDefault();
    let vehicle_id = $(this).attr('id');
    $.ajax({
        url: '/md/getVehicaleEdit/' + vehicle_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            $('#btnSaveVehicle').hide();
            $('#btnUpdateVehicle').show();


            $('#id').val(response.vehicle_id);
            $("#txtvehicleNo").val(response.vehicle_no);
            $("#txtVehicleName").val(response.vehicle_name);
            $("#txtDescription").val(response.description);
            $("#cmbVehicleType").val(response.vehicle_type_id);
            $("#txtLicenceExpire").val(response.licence_expire_date);
            $("#txtInsuranceExpire").val(response.insurance_expire_date);
            $("#txtRemarks").val(response.remarks);
            $("#cmbbranch").val(response.branch_id);



        }
    });
});


//....Update


function updateVehicle() {

    var id = $('#id').val();

    formData.append('txtvehicleNo', $('#txtvehicleNo').val());
    formData.append('txtVehicleName', $('#txtVehicleName').val());
    formData.append('txtDescription', $('#txtDescription').val());
    formData.append('cmbVehicleType', $('#cmbVehicleType').val());
    formData.append('txtLicenceExpire', $('#txtLicenceExpire').val());
    formData.append('txtInsuranceExpire', $('#txtInsuranceExpire').val());
    formData.append('cmbbranch', $('#cmbbranch').val());
    formData.append('txtRemarks', $('#txtRemarks').val());


    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/vehicaleupdate/' + id,
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

            vehicaleAllData();
            $('#modalVehicle').modal('hide');

            showSuccessMessage('Successfully updated');



        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modalVehicle').modal('hide');
            console.log(error);
        }
    });
}
function formatDate(dateString) {
    var dateParts = dateString.split('/');
    // Assuming the date format is 'MM/DD/YYYY'
    var formattedDate = dateParts[2] + '-' + dateParts[0] + '-' + dateParts[1];
    return formattedDate;
}

function btnVehicaleDelete(id) {
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
                deletevehicle(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deletevehicle(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteVehicale/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            vehicaleAllData();

            showSuccessMessage('Successfully deleted');
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




function cbxVehicleStatus(vehicle_id) {
    var status = $('#cbxVehicle').is(':checked') ? 1 : 0;
    $.ajax({
        url: '/md/vehicleStatus/' + vehicle_id,
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




///////////////////////////////////////////////////////////////////////








function vehicaleType() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/vehicalTypename",
        success: function (data) {
            $.each(data, function (key, value) {

                var isChecked = "";
                if (value.status_id) {
                    isChecked = "checked";
                }
                data = data + "<option  id='' value=" + value.vehicle_type_id + ">" + value.vehicle_type + "</option>"
            })
            $('#cmbVehicleType').html(data);
        }
    });
}
vehicaleType();

function branch() {
    
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/getBranches",
        success: function (data) {
            console.log(data);
            $.each(data, function (key, value) {

                var isChecked = "";
                if (value.status_id) {
                    isChecked = "checked";
                }
                
                $('#cmbbranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
            });
           
        }
    });
}