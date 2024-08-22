var formData = new FormData();
$(document).ready(function () {
    function townDistrict() {
        $.ajax({
            type: "get",
            dataType: 'json',
            url: "/md/towndistrict",
            success: function (response) {
                var data = response.data;


                var options = '';
                $.each(data, function (key, value) {
                    options += "<option value='" + value.district_id + "'>" + value.district_name + "</option>";
                });

                $('#cmbDistrict').html(options);
            }
        });
    }

    townDistrict();
    allDataGroup();

    /* $('#btnSaveSupGroup').on('click',function(){
        alert('1111');
         addSupplierGroup(); 
    }) */
    $('#btnDistrict').on('click', function () {
        $('#btnSaveDistric').show();
        $('#btnUpdateDistrict').hide();
        $('#id').val('');
        $("#txtDistrict").val('');


    });
    $('#btnTown').on('click', function () {
        $('#btnSaveTown').show();
        $('#btnUpdateTown').hide();
        $('#id').val('');
        $("#txtTown").val('');
        $("#cmbDistrict").val('');


    });

    $('#btnGroup').on('click', function () {
       
        $('#btnSaveGroup').show();
        $('#btnUpdateGroup').hide();
        $('#id').val('');
        $("#txtGroup").val('');
        $("#txtPeriod").val('');


    });
    $('#btnGrade').on('click', function () {
        $('#btnSavegrade').show();
        $('#btnUpdateGrade').hide();
        $('#id').val('');
        $("#txtgrade").val('');



    });


    ///////////////////////////close//////////

    // close

    $("#btnCloseDistrict").on("click", function (e) {
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
                $("#modelDistric").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });


    $("#btnCloseTown").on("click", function (e) {
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
                $("#modelTown").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });


    $("#btnCloseGroup").on("click", function (e) {
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
                $("#modalGroup").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });


    $("#btnCloseGrade").on("click", function (e) {
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
                $("#modalGrade").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });





    ////////////////////////////////////////////

    $('#btnSaveDistric').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            alert("asd");
            return;
        }

        saveDistric();
    });
    $('#btnUpdateDistrict').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {

            return;
        }

        updateDistrict();
    });
    $('#btnUpdateDistrict').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateDistrict();
    });



    //...Town


    $('#btnSaveTown').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveTown();
    });

    $('#btnUpdateTown').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateTown();
    });


    //...Group


    $('#btnSaveGroup').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveGroup();
    });
    $('#btnUpdateGroup').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateGroup();
    });


    //...Grade


    $('#btnSavegrade').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveGrade();
    });


    $('#btnUpdateGrade').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateGrade();
    });





    $('#btnSaveDistric').show();
    $('#btnUpdateDistrict').hide();

    $('#btnSaveTown').show();
    $('#btnUpdateTown').hide();

    $('#btnSavegrade').show();
    $('#btnUpdateGrade').hide();

    $('#btnSaveGroup').hide();
    $('#btnUpdateGroup').hide();





});


function cbxStatus(district_id) {
    var status = $('#cbxDistricrStatus').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/updateDistrictStatus/' + district_id,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'status': status
        },
        success: function (response) {
            console.log("data save");
            showSuccessMessage('saved')
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            showErrorMessage('Not save')
        }
    });
}




$(document).on('click', '.editDistrict', function (e) {
    e.preventDefault();
    let district_id = $(this).attr('id');
    $.ajax({
        url: '/md/districtEdite/' + district_id,
        method: 'get',
        data: {
            //district_id: district_id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log(response);

            $('#btnSaveDistric').hide();
            $('#btnUpdateDistrict').show();
            $('#id').val(response.district_id);
            $("#txtDistrict").val(response.district_name);

        }
    });
});




function updateDistrict() {
    var id = $('#id').val();
    formData.append('txtDistrict', $('#txtDistrict').val());
    var cat1 = $('#txtDistrict').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{


    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/districtUpdate/' + id,
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

            allData();

            $('#distSearch').val('');
            $('#modelDistric').modal('hide');
            showSuccessMessage('Successfully updatet');
           
        }, error: function (error) {
            showErrorMessage('Something went wrong')
            $('#modelDistric').modal('hide');
            console.log(error);
        }
    });
}
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
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "district_id" },
                { "data": "district_name" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "status" },



            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false);



       /*    // Left and right fixed columns
          var table = $('#supplerGroupTable').DataTable({
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
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "supplier_group_id" },
                { "data": "supplier_group_name" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "is_active" },



            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false); */


       

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





function allData() {

    $.ajax({
        type: "GET",
        url: "/md/districtData",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;


            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";

                data.push({

                    "district_id": dt[i].district_id,
                    "district_name": dt[i].district_name,
                    "edit": '<button class="btn btn-primary editDistrict" data-bs-toggle="modal" data-bs-target="#modelDistric" id="' + dt[i].district_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                    "delete": '&#160<button class="btn btn-danger"  id="btndistrict" onclick="btndistrictDelete(' + dt[i].district_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                    "status": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxDistricrStatus" value="1" onclick="cbxStatus(' + dt[i].district_id + ')" required ' + isChecked + '></lable>',
                });
            }


            var table = $('#tableDistrict').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

allData();

function districtTableRefresh() {
    var table = $('#tableDistrict').DataTable();
    table.columns.adjust().draw();
}


function SupplierGroupTableRefresh() {
    var table = $('#supplierGRPtable').DataTable();
    table.columns.adjust().draw();
}






//........save......

function saveDistric() {

    formData.append('txtDistrict', $('#txtDistrict').val());

    if (formData.txtDistrict == '') {
        //alert('Please enter item category level 1');
        return false;
    }

    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveDistrict',
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
            console.log(response.district_id);
            allData();
            showSuccessMessage('Successfully saved');
            $('#modelDistric').modal('hide');

            if (response.status) {
            $('#modelDistric').modal('hide');
            showSuccessMessage('Successfully saved');
            }else{
               // $('#modelDistric').modal('hide');
                //showErrorMessage('Something went wrong');
            }


        },
        error: function (error) {
            // $('.district').text(error.responseJSON.message);
            console.log(error);
            $('#modelDistric').modal('hide');
            showErrorMessage('Something went wrong');


        },
        complete: function () {

        }

    });

}

function btndistrictDelete(id) {

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
                deleteDistrict(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteDistrict(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteDistrict/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                allData();
            
                showSuccessMessage('Successfully deleted');
            }else{
                showWarningMessage('Unable to Delete')
            }


           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
            showErrorMessage('Not deleted')
        }
    });
}






//############################.....Town.....#######################################################



const DatatableFixedColumnsTown = function () {


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

        var table = $('.datatable-fixed-both-town').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width:'100%',
                    targets: 0
                },
                {
                    width:300,
                    targets: 1
                },
                {
                    width: 600,
                    targets: 2
                },

            ],
            autoWidth: false,
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "town_id" },
                { "data": "district_id" },
                { "data": "town_name" },
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

    DatatableFixedColumnsTown.init();
});




//////
function loadDistrict() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/loadDistrict",

        success: function (data) {
            $('#cmbDistrict').empty();
            $.each(data, function (key, value) {
                $('#cmbDistrict').append('<option value="' + value.district_id + '">' + value.district_name + '</option>');
            })


        }

    });
}



function cbxTownStatus(town_id) {
    var status = $('#cbxTownStatus').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/townUpdateStatus/' + town_id,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'status': status
        },
        success: function (response) {
            showSuccessMessage('saved')
            console.log("data save");
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            showErrorMessage('Not saved')
        }
    });
}


function allDataTown() {
    $.ajax({
        type: "GET",
        url: "/md/twonAlldata",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            var data = [];

            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";

                data.push({
                    "town_id": dt[i].town_id,
                    "district_id": dt[i].district_name,
                    "town_name": dt[i].town_name,
                    "edit": '<button class="btn btn-primary editTwon" data-bs-toggle="modal" data-bs-target="#modelTown" id="' + dt[i].town_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                    "delete": '&#160<button class="btn btn-danger" id="btnTown" value="Delete" onclick="btnTownDelete(' + dt[i].town_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                    "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single" id="cbxTownStatus" value="1" onclick="cbxTownStatus(' + dt[i].town_id + ')" required ' + isChecked + '></lable>',
                });

            }


            var table = $('#tbodyTown').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}
allDataTown();
function townTableRefresh() {
    var table = $('#tbodyTown').DataTable();
    table.columns.adjust().draw();
}

//........save......

function saveTown() {

    formData.append('txtTown', $('#txtTown').val());
    formData.append('cmbDistrict', $('#cmbDistrict').val());

    if (formData.txtTown == '' && formData.cmbDistrict) {
        //alert('Please enter item category level 1');
        return false;
    }


    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveTown',
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
            console.log(response.district_id);
            allDataTown();


            if (response.status) {
            showSuccessMessage('Successfully save');
            $('#modelTown').modal('hide');
            }else{
                showErrorMessage('Error');
                $('#modelTown').modal('Something went wrong');
            }


        },
        error: function (error) {

            showErrorMessage('Error');
            $('#modelTown').modal('Something went wrong');

            console.log(error);

        },
        complete: function () {

        }

    });

}
//.......edit......

$(document).on('click', '.editTwon', function (e) {
    e.preventDefault();
    let town_id = $(this).attr('id');
    $.ajax({
        url: '/md/townEdite/' + town_id,
        method: 'get',
        data: {
            // id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            $('#btnSaveTown').hide();
            $('#btnUpdateTown').show();

            $('#id').val(response.town_id);
            $("#txtTown").val(response.town_name);
            $("#cmbDistrict").val(response.district_id);

        }
    });
});



function updateTown() {
    var id = $('#id').val();
    formData.append('cmbDistrict', $('#cmbDistrict').val());
    formData.append('txtTown', $('#txtTown').val());
    var cat1 = $('#txtTown').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{



    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/townUpdate/' + id,
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

            allDataTown();


            showSuccessMessage('Successfully updated');
            $('#modelTown').modal('hide');
            console.log(data);
        }, error: function (error) {
            console.log(error);

            showErrorMessage('Something went wrong');
            $('#modelTown').modal('hide');
        }
    });
}
}




function btnTownDelete(id) {

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
                deleteTown(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteTown(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteTown/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);

            if(response.success){
                allDataTown();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Unable to Delete')
           }
          

            
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}


//############################...Group.......#######################################################



function cbxGroupStatus(customer_group_id) {
    var status = $('#cbxGroupStatus').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/groupUpdateStatus/' + customer_group_id,
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



const DatatableFixedColumnsgroup = function () {


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

        var table = $('.datatable-fixed-both-group').DataTable({
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
                },

            ],
            autoWidth: false,
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "customer_group_id" },
                { "data": "group" },
                { "data": "credit" },
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
    DatatableFixedColumnsgroup.init();
});



function allDataGroup() {


    $.ajax({
        type: "GET",
        url: '/md/groupAlldata',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            if (response.hasOwnProperty('data')) {
                var dt = response.data;
                console.log(dt);
                var data = [];
                for (var i = 0; i < dt.length; i++) {
                    var isChecked = dt[i].is_active ? "checked" : "";
                    data.push({
                        "customer_group_id": dt[i].customer_group_id,
                        "group": dt[i].group,
                        "credit": dt[i].credit_preriod,
                        "edit": '<button class="btn btn-primary editGroup" data-bs-toggle="modal" data-bs-target="#modalGroup"  id="' + dt[i].customer_group_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                        "delete": '&#160<button class="btn btn-danger"  id="btnCategorylevel1" value="Delete" onclick="btnGroupDelete(' + dt[i].customer_group_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                        "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single" id="cbxGroupStatus" value="1" onclick="cbxGroupStatus(' + dt[i].customer_group_id + ')" required ' + isChecked + '></label>'
                    });
                }

                var table = $('#tbodyGroup').DataTable();
                table.clear();
                table.rows.add(data).draw();
            } else if (response.hasOwnProperty('error')) {
                console.log(response.error);
            } else {
                console.log('Invalid response format');
            }
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });




}

allDataGroup();

function groupTableRefresh() {
    var table = $('#tbodyGroup').DataTable();
    table.columns.adjust().draw();
}






//........save......

function saveGroup() {

    formData.append('txtGroup', $('#txtGroup').val());
    formData.append('txtPeriod', $('#txtPeriod').val());


    if (formData.txtGroup == '') {
        //alert('Please enter item category level 1');
        return false;
    }

    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveGroup',
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
            console.log(response.district_id);
            allDataGroup();

            $("#groupSearch").val('');
            $('#modalGroup').modal('hide');
            if (response.status) {
            showSuccessMessage('Successfully save');
            }else{
                showErrorMessage('Something went wrong');
                $('#modalGroup').modal('hide');
            }


        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modalGroup').modal('hide');
            console.log(error);

        },
        complete: function () {

        }

    });

}
//.......edit......

$(document).on('click', '.editGroup', function (e) {
    e.preventDefault();
    let customer_group_id = $(this).attr('id');

    $.ajax({
        url: '/md/groupEdite/' + customer_group_id,
        method: 'get',

        success: function (response) {
            $('#btnSaveGroup').hide();
            $('#btnUpdateGroup').show();

            $('#id').val(response.customer_group_id);
            $("#txtGroup").val(response.group);
            $("#txtPeriod").val(response.credit_preriod);

        }
    });
});





function updateGroup() {
    var id = $('#id').val();
    formData.append('txtGroup', $('#txtGroup').val());
    formData.append('credit_preriod', $('#txtPeriod').val());

    var cat1 = $('#txtGroup').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{


    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/groupUpdate/' + id,
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

            allDataGroup();

            $('#groupSearch').val('');
            $('#modalGroup').modal('hide');
            showSuccessMessage('Successfully updated');

            console.log(data);
        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modalGroup').modal('hide');
            console.log(error);
        }
    });
}
}


function btnGroupDelete(id) {

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
                deleteGroup(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteGroup(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteGroup/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            if(response.success){
                allDataGroup();
                $('#groupSearch').val('');
                showSuccessMessage('Successfully deleted');
            }else{
                showWarningMessage('Unable to Delete')
            }
           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




//############################...Grade.......#######################################################


var district_id = $('#cbxGradeStatus').attr('data-district-id');
$.ajax({
    url: '/md/updateStatusGrade/' + district_id,
    type: 'GET',
    dataType: 'json',
    success: function (response) {
        if (response.status == 'true') {
            $('#cbxGradeStatus').prop('checked', response.is_active == 1);
        } else {
            $('#cbxGradeStatus').prop('checked', response.is_active == 0);
        }
    },
    error: function (xhr, status, error) {
        console.log(xhr.responseText);
    }
});



function cbxGradeStatus(customer_grade_id) {
    var status = $('#cbxGradeStatus').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/gradeUpdateStatus/' + customer_grade_id,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'status': status
        },
        success: function (response) {
            showSuccessMessage('saved');
            console.log("data save");
        },
        error: function (xhr, status, error) {
            showErrorMessage('Something went wrong');
            console.log(xhr.responseText);
        }
    });
}


///////////////////////////////////////////////////////////////////////



const DatatableFixedColumnsgrade = function () {


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

        var table = $('.datatable-fixed-both-grade').DataTable({
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
                },

            ],
            autoWidth: false,
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "customer_grade_id" },
                { "data": "grade" },
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
    DatatableFixedColumnsgrade.init();
});



function allDataGrade() {



    $.ajax({
        type: "GET",
        url: "/md/gradeAlldata",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;


            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";


                data.push({

                    "customer_grade_id": dt[i].customer_grade_id,
                    "grade": dt[i].grade,
                    "edit": '<button class="btn btn-primary editGrade" data-bs-toggle="modal" data-bs-target="#modalGrade" id="' + dt[i].customer_grade_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                    "delete": '&#160<button class="btn btn-danger"   id="btnGrade" value="Delete" onclick="btnGradeDelete(' + dt[i].customer_grade_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                    "status": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxGradeStatus" value="1" onclick="cbxGradeStatus(' + dt[i].customer_grade_id + ')" required ' + isChecked + '></lable>',
                });
            }


            var table = $('#tabalGrade').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () { }
    })


}

allDataGrade();

function gradeTableRefresh() {
    var table = $('#tabalGrade').DataTable();
    table.columns.adjust().draw();
}

//........save......

function saveGrade() {

    formData.append('txtgrade', $('#txtgrade').val());



    if (formData.txtgrade == '') {
        //alert('Please enter item category level 1');
        return false;
    }
    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/savegrade',
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
            console.log(response.district_id);
            allDataGrade();
            if (response.status) {
            $('#modalGrade').modal('hide');
            showSuccessMessage('Successfully save');
            }else{
                showErrorMessage('Something went wrong');
                $('#modalGrade').modal('hide');
            }

        },
        error: function (error) {

            showErrorMessage('Something went wrong');
            $('#modalGrade').modal('hide');
            console.log(error);

        },
        complete: function () {

        }

    });

}
//.......edit......

$(document).on('click', '.editGrade', function (e) {
    e.preventDefault();
    let customer_grade_id = $(this).attr('id');
    $.ajax({
        url: '/md/gradeEdite/' + customer_grade_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            $('#btnSavegrade').hide();
            $('#btnUpdateGrade').show();

            $('#id').val(response.customer_grade_id);
            $("#txtgrade").val(response.grade);


        }
    });
});


function updateGrade() {
    var id = $('#id').val();
    formData.append('txtgrade', $('#txtgrade').val());

    var cat1 = $('#txtgrade').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{


    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/gradeUpdate/' + id,
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

            allDataGrade();

            $('#modalGrade').modal('hide');
            showSuccessMessage('Successfully updated');

            console.log(data);
        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modalGrade').modal('hide');
            console.log(error);
        }
    });
}
}


function btnGradeDelete(id) {

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
                deleteGrade(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteGrade(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteGrade/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            if(response.success){
                allDataGrade();
            
                showSuccessMessage('Successfully deleted');
            }else{
                showWarningMessage('Unable to Delete')
            }
            
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




//////////////////////////////////////////////////////////////////////////////////////////////








