var formData = new FormData();
$(document).ready(function () {

    $('#btnCategory1').on('click', function () {
        $('#btnSaveCategorylevel1').show();
        $('#btnUpdateCategorylevel1').hide();
        $('#id').val('');
        $("#txtCategorylevel1").val('');
        $("#categoryLevel1Search").val('');


    });

    $('#btnCategory2').on('click', function () {
        $('#btnSaveCategorylevel2').show();
        $('#btnUpdateCategorylevel2').hide();
        $('#id').val('');
        $("#cmbLeve1").val('');
        $("#txtCategorylevel2").val('');


    });


    $('#btnCategory3').on('click', function () {
        $('#btnSaveCategorylevel3').show();
        $('#btnUpdateCategorylevel3').hide();
        $('#id').val('');
        $("#cmbLeve2").val('');
        $("#txtCategorylevel3").val('');
    });


    $('#btnDesgination').on('click', function () {
        $('#btnSaveDesgination').show();
        $('#btnUpdateDesgination').hide();
        $('#id').val('');
        $("#txtDesgination").val('');



    });
    $('#btnStatuss').on('click', function () {
        $('#btnSaveStatus').show();
        $('#btnUpdateStatus').hide();
        $("#txtStatus").val('');
        $('#id').val('');



    });

    $('#btnVehicle').on('click', function () {
        $('#btnSaveVehicletype').show();
        $('#btnUpdateVehicletype').hide();
        $('#id').val('');
        $("#txtVehicletype").val('');
    });

   
    ///////////////////////////close//////////

    // close

    $("#btnClose1").on("click", function (e) {
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
                $("#modelcategoryLevel").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });


    $("#btnClose2").on("click", function (e) {
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
                $("#modelcategoryLeve2").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });


    $("#btnClose3").on("click", function (e) {
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
                $("#modelcategoryLeve3").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });


    $("#btnClose4").on("click", function (e) {
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
                $("#modelDesgination").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });




    $("#btnClose5").on("click", function (e) {
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
                $("#modelStatus1").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });


    $("#btnCloseV").on("click", function (e) {
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
                $("#modeVehicletype").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });

    $("#btnClose").on("click", function (e) {
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
                $("#modelcategoryLevel").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];
            },
            error: function (xhr, status, error) {

            }
        });
    });




    $('#btnSaveCategorylevel1').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveCategoryLevel1();
    });

    //...level 1 Update

    $('#btnUpdateCategorylevel1').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateCategory1();
    });

    $('#btnSaveCategorylevel1').show();
    $('#btnUpdateCategorylevel1').hide();



    //##########################  Level 2  ################



    $('#btnSaveCategorylevel2').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveCategoryLevel2();
    });

    //...level 1 Update

    $('#btnUpdateCategorylevel2').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateCategory2();
    });

    $('#btnSaveCategorylevel2').show();
    $('#btnUpdateCategorylevel2').hide();



    //##########################  Level 3  ################



    $('#btnSaveCategorylevel3').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveCategoryLevel3();
    });

    //...level 1 Update

    $('#btnUpdateCategorylevel3').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateCategory3();
    });

    $('#btnSaveCategorylevel3').show();
    $('#btnUpdateCategorylevel3').hide();



    //##.......Distination..........

    $('#btnSaveDesgination').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveDesgination();
    });

    //...Distination Update

    $('#btnUpdateDesgination').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateDesgination();
    });

    $('#btnSaveDesgination').show();
    $('#btnUpdateDesgination').hide();



    //##.......Status..........

    $('#btnSaveStatus').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveStatus();
    });

    //...level 1 Update

    $('#btnUpdateStatus').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateStatus();
    });



    $('#btnSaveStatus').show();
    $('#btnUpdateStatus').hide();




    //##.......Vehicle Type..........

    $('#btnSaveVehicletype').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveVehicletype();
    });


    $('#btnUpdateVehicletype').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateVehicletype();
    });



    $('#btnSaveVehicletype').show();
    $('#btnUpdateVehicletype').hide();




});



const DatatableFixedColumnsl = function () {


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

        var table = $('.datatable-fixed-both-lsa').DataTable({
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
                { "data": "item_category_level_1_id" },
                { "data": "category_level_1" },
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
    DatatableFixedColumnsl.init();
});


//...Category load Data
function Category1AllData() {

    $.ajax({
        type: "GET",
        url: '/md/getCategorylevelOne',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            if (true) {
                var dt = response.data;

                var data = [];

                for (var i = 0; i < dt.length; i++) {
                    var isChecked = dt[i].is_active ? "checked" : "";



                    data.push({
                        "item_category_level_1_id": dt[i].item_category_level_1_id,
                        "category_level_1": dt[i].category_level_1,
                        "edit": '<button class="btn btn-primary categorylevel1" data-bs-toggle="modal" data-bs-target="#modelcategoryLevel"  id="' + dt[i].item_category_level_1_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                        "delete": '&#160<button class="btn btn-danger"  id="btnCategorylevel1" value="Delete" onclick="btnCategorylevel1Delete(' + dt[i].item_category_level_1_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                        "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single"id="cbxCategorylevel1" value="1" onclick="cbxCategorylevel1Status(' + dt[i].item_category_level_1_id + ')" required ' + isChecked + '></label>'
                    });

                }


                var table = $('#categoryLevell').DataTable();
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

Category1AllData();

function categorytabal1TableRefresh() {
    var table = $('#categoryLevell').DataTable();
    table.columns.adjust().draw();
}



//.....saveCategoryLevel1 Save.....

function saveCategoryLevel1() {

    formData.append('txtCategorylevel1', $('#txtCategorylevel1').val());

    console.log(formData);
    if (formData.txtCategorylevel1 == '') {
        //alert('Please enter item category level 1');
        return false;
    }

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveCategoryLevel1',
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
            Category1AllData();
            $('#modelcategoryLevel').modal('hide');

            showSuccessMessage('Successfully saved');
            console.log(response);


        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modelcategoryLevel').modal('hide');

            console.log(error);

        },
        complete: function () {

        }

    });

}



//.......edit......

$(document).on('click', '.categorylevel1', function (e) {
    e.preventDefault();
    let category_level_1_id = $(this).attr('id');
    $.ajax({
        url: '/md/categorylevel1Edite/' + category_level_1_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            $('#btnSaveCategorylevel1').hide();
            $('#btnUpdateCategorylevel1').show();

            $('#id').val(response.item_category_level_1_id);
            $("#txtCategorylevel1").val(response.category_level_1);


        }
    });
});


//....lavel1 Update


function updateCategory1() {

    var id = $('#id').val();
    formData.append('txtCategorylevel1', $('#txtCategorylevel1').val());
    var cat1 = $('#txtCategorylevel1').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{

    
    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/txtCategorylevel1Update/' + id,
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

            Category1AllData();

            $('#modelcategoryLevel').modal('hide');
            showSuccessMessage('Successfully updated');


        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modelcategoryLevel').modal('hide');
            console.log(error);
        }
    });
}
}


function btnCategorylevel1Delete(id) {

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
                deleteLevel1(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteLevel1(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deletelevel1/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                Category1AllData();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
          
           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}



//##############################....Category Level 2.......######################################

function loadcategory2() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/loadcategory2",

        success: function (data) {
            console.log("dddd",data);
            $('#cmbLeve1').empty();
            $.each(data, function (key, value) {
                $('#cmbLeve1').append('<option value="' + value.item_category_level_1_id + '">' + value.category_level_1 + '</option>');
            })


        }

    });
}
loadcategory2()
///////////////////////////////////////////////////////////////////////



const DatatableFixedColumnsll = function () {


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

        var table = $('.datatable-fixed-bothll').DataTable({

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
                    width: 400,
                    targets: 1
                },
                {
                    width: 600,
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
                { "data": "Item_category_level_2_id" },
                { "data": "Item_category_level_1_id" },
                { "data": "category_level_2" },
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
    DatatableFixedColumnsll.init();
});

function Category2AllData() {
    $.ajax({
        type: "GET",
        url: '/md/categoryLevel2Data',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            console.log('xxxxxxxx');
            if (true) {
                var dt = response.data;
                console.log(dt);

                var data = [];
                for (var i = 0; i < dt.length; i++) {
                    
                        var isChecked = dt[i].is_active ? "checked" : "";
                        data.push({
                            "Item_category_level_2_id": dt[i].Item_category_level_2_id,
                            "Item_category_level_1_id": dt[i].category_level_1,
                            "category_level_2": dt[i].category_level_2,
                            "edit": '<button class="btn btn-primary categorylevel2" data-bs-toggle="modal" data-bs-target="#modelcategoryLeve2" id="' + dt[i].Item_category_level_2_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                            "delete": '&#160<button class="btn btn-danger" id="btnCategorylevel2" value="Delete" onclick="btnCategorylevel2Delete(' + dt[i].Item_category_level_2_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                            "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single" id="cbxCategorylevel2" value="1" onclick="cbxCategorylevel2Status(' + dt[i].Item_category_level_2_id + ')" required ' + isChecked + '></label>'
                        });
                    
                }

                var table = $('#categoryLevel2Table').DataTable();
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

Category2AllData();

function categorytabal2TableRefresh() {
    var table = $('#categoryLevel2Table').DataTable();
    table.columns.adjust().draw();
}



//.....saveCategoryLevel2 Save.....

function saveCategoryLevel2() {

    formData.append('cmbLeve1', $('#cmbLeve1').val());
    formData.append('txtCategorylevel2', $('#txtCategorylevel2').val());

    if (formData.cmbLeve1 == '' && formData.txtCategorylevel2 == '') {
        alert('Please enter item category level 1');
        return false;
    }


    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveCategoryLevel2',
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
            Category2AllData();
            if (response.status) {
            $('#modelcategoryLeve2').modal('hide');
            showSuccessMessage('Succeessfully saved');
            console.log(response);
            }else{
                showErrorMessage('Something went wrong');
                $('#modelcategoryLeve2').modal('hide');

            }

        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modelcategoryLeve2').modal('hide');

            console.log(error);



        },
        complete: function () {

        }

    });

}



//.......edit......

$(document).on('click', '.categorylevel2', function (e) {
    e.preventDefault();
    let category_level_2_id = $(this).attr('id');
    $.ajax({
        url: '/md/categorylevel2Edite/' + category_level_2_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            console.log("sdsd",response);
            $('#btnSaveCategorylevel2').hide();
            $('#btnUpdateCategorylevel2').show();

            $('#id').val(response.Item_category_level_1_id);
            $("#cmbLeve1").val(response.Item_category_level_1_id);
            $("#txtCategorylevel2").val(response.category_level_2);


        }
    });
});


//....lavel2 Update


function updateCategory2() {

    var id = $('#id').val();
    formData.append('cmbLeve1', $('#cmbLeve1').val());
    formData.append('txtCategorylevel2', $('#txtCategorylevel2').val());

    var cat1 = $('#txtCategorylevel2').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{


    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/txtCategorylevel2Update/' + id,
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

            Category2AllData();

            $('#modelcategoryLeve2').modal('hide');
            showSuccessMessage('Successfully updated');


        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modelcategoryLeve2').modal('hide');
            console.log(error);
        }
    });
}
}

function btnCategorylevel2Delete(id) {

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
                deleteLevel2(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteLevel2(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deletelevel2/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                Category2AllData();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
            
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}



//############## Level 3   ###############################


function loadcategory3() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/loadcategory3",

        success: function (data) {
            $('#cmbLeve2').empty();
            $.each(data, function (key, value) {
                $('#cmbLeve2').append('<option value="' + value.Item_category_level_2_id + '">' + value.category_level_2 + '</option>');
            })


        }

    });
}
loadcategory3()




///////////////////////////////////////////////////////////////////////



const DatatableFixedColumnsls = function () {


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

        var table = $('.datatable-fixed-both-l3').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: '100%',
                    targets: 0
                },
                {
                    width:300,
                    targets: 1
                },
                {
                    width: 600,
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
                { "data": "Item_category_level_3_id" },
                { "data": "category_level_2" },
                { "data": "category_level_3" },
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
    DatatableFixedColumnsls.init();
});



function Category3AllData() {
    $.ajax({
        type: "GET",
        url: '/md/categoryLevel3Data',
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
                            "Item_category_level_3_id": dt[i].Item_category_level_3_id,
                            "category_level_2": dt[i].category_level_2,
                            "category_level_3": dt[i].category_level_3,
                            "edit": '<button class="btn btn-primary categorylevel3" data-bs-toggle="modal" data-bs-target="#modelcategoryLeve3" id="' + dt[i].Item_category_level_3_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                            "delete": '&#160<button class="btn btn-danger" id="btnCategorylevel3" value="Delete" onclick="btnCategorylevel3Delete(' + dt[i].Item_category_level_3_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                            "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single" id="cbxCategorylevel3" value="1" onclick="cbxCategorylevel3Status(' + dt[i].Item_category_level_3_id + ')" required ' + isChecked + '></label>'
                        });
                    
                }

                var table = $('#tabalCategoryLevel3').DataTable();
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

Category3AllData();

function categorytabal3TableRefresh() {
    var table = $('#tabalCategoryLevel3').DataTable();
    table.columns.adjust().draw();
}


//.....saveCategoryLevel3 Save.....

function saveCategoryLevel3() {

    formData.append('cmbLeve2', $('#cmbLeve2').val());
    formData.append('txtCategorylevel3', $('#txtCategorylevel3').val());

    console.log(formData);
    if (formData.cmbLeve2 == '' && formData.txtCategorylevel3 == '') {
        // alert('Please enter item category level 1');
        return false;
    }
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveCategoryLevel3',
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
            Category3AllData();


            if (response.status) {
            showSuccessMessage('Successfully saved');
            $('#modelcategoryLeve3').modal('hide');
            console.log(response);
            }else{
                showErrorMessage('Something went wrong');
                $('#modelcategoryLeve3').modal('hide');
            }

        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modelcategoryLeve3').modal('hide');
            console.log(error);

        },
        complete: function () {

        }

    });

}



//.......edit......

$(document).on('click', '.categorylevel3', function (e) {
    e.preventDefault();
    let category_level_3_id = $(this).attr('id');
    $.ajax({
        url: '/md/categorylevel3Edite/' + category_level_3_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            $('#btnSaveCategorylevel3').hide();
            $('#btnUpdateCategorylevel3').show();

            $('#id').val(response.Item_category_level_3_id);
            $("#cmbLeve2").val(response.Item_category_level_2_id);
            $("#txtCategorylevel3").val(response.category_level_3);



        }
    });
});


//....lavel3 Update


function updateCategory3() {

    var id = $('#id').val();
    formData.append('cmbLeve2', $('#cmbLeve2').val());
    formData.append('txtCategorylevel3', $('#txtCategorylevel3').val());

    var cat1 = $('#txtCategorylevel3').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/Categorylevel3Update/' + id,
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

            Category3AllData();

            $('#modelcategoryLeve3').modal('hide');
            showSuccessMessage('Successfully updated');


        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modelcategoryLeve3').modal('hide');
            console.log(error);
        }
    });
}
}

//########################################################################################################3

//status update  Level 1


function cbxCategorylevel1Status(item_category_level_1_id) {
    var status = $('#cbxCategorylevel1').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/updateCatLevel1tStatus/' + item_category_level_1_id,
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
            showErrorMessage('Error')
        }
    });
}



//status update  Level 2


function cbxCategorylevel2Status(Item_category_level_2_id) {
    var status = $('#cbxCategorylevel2').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/updateCatLevel2tStatus/' + Item_category_level_2_id,
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
            showErrorMessage('Error')
            console.log(xhr.responseText);
        }
    });
}



//status update  Level 3


function cbxCategorylevel3Status(Item_category_level_3_id) {
    var status = $('#cbxCategorylevel3').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/updateCatLevel3tStatus/' + Item_category_level_3_id,
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
            showErrorMessage('Error')
            console.log(xhr.responseText);
        }
    });
}

//...............delete......

function btnCategorylevel3Delete(id) {

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
                deleteLevel3(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteLevel3(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deletelevel3/' + id,
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
                Category3AllData();
                $('#categoryLevel3Search').val('');
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
           
        }, error: function (xhr, status, error) {
            showWarningMessage('Not Deleted');
            console.log(xhr.responseText);
        }
    });
}


//#####################..Disgination...........

const DatatableFixedColumnslsl = function () {


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
        var table = $('.datatable-fixed-both-des').DataTable({
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
                {
                    width: 200,
                    targets: [3]
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
                { "data": "employee_designation_id " },
                { "data": "employee_designation" },
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
    DatatableFixedColumnslsl.init();
});

// all data
function allDesgination() {

    $.ajax({
        type: "GET",
        url: '/md/disginationData',
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
                        "employee_designation_id ": dt[i].employee_designation_id,
                        "employee_designation": dt[i].employee_designation,
                        "edit": '<button class="btn btn-primary editDesgination" data-bs-toggle="modal" data-bs-target="#modelDesgination" id="' + dt[i].employee_designation_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                        "delete": '&#160<button class="btn btn-danger" id="btnDesgination" value="Delete" onclick="btnDesginationDelete(' + dt[i].employee_designation_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                        "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single" id="cbxDesginationStatus" value="1" onclick="cbxDesgination(' + dt[i].employee_designation_id + ')" required ' + isChecked + '></label>'
                    });
                }

                var table = $('#tabalDesgination').DataTable();
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

allDesgination();

function desginationTableRefresh() {
    var table = $('#tabalDesgination').DataTable();
    table.columns.adjust().draw();
}


//....save Disgination

function saveDesgination() {

    formData.append('txtDesgination', $('#txtDesgination').val());

    if (formData.txtDesgination == '') {
        //alert('Please enter item category level 1');
        return false;
    }
    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveDesgination',
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
            $('#modelDesgination').modal('hide');
            if (response.status) {
                showSuccessMessage('Successfully saved');
                allDesgination();


                console.log(response);
            }else{
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
//edite desgination



$(document).on('click', '.editDesgination', function (e) {

    e.preventDefault();
    let employee_designation_id = $(this).attr('id');

    $.ajax({
        url: '/md/desginationEdite/' + employee_designation_id,
        method: 'get',
        data: {

            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log(response);
            $('#btnSaveDesgination').hide();
            $('#btnUpdateDesgination').show();

            $('#id').val(response.employee_designation_id);
            $("#txtDesgination").val(response.employee_designation);

        }
    });
});


// Update desgination



function updateDesgination() {
    var id = $('#id').val();
    formData.append('txtDesgination', $('#txtDesgination').val());
    var cat1 = $('#txtDesgination').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/desginationtUpdate/' + id,
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


            allDesgination();
            $('#modelDesgination').modal('hide');
            showSuccessMessage('Successfully Updated');

            console.log(data);
        }, error: function (error) {
            $('#modelDesgination').modal('hide');
            showErrorMessage('Something went wrong');
            console.log(error);
        }
    });
}
}


//desgination status


function cbxDesgination(employee_designation_id) {
    var status = $('#cbxDesginationStatus').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/updateDesginationStatus/' + employee_designation_id,
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

// desgination Delete

function btnDesginationDelete(id) {

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
                deleteDesgination(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteDesgination(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deletedesgination/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                allDesgination();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
          
           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




//#####################..Employee Status...........

const DatatableFixedColumnslsll = function () {


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

        var table = $('.datatable-fixed-both-st').DataTable({
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
                { "data": "employee_status_id" },
                { "data": "employee_status" },
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
    DatatableFixedColumnslsll.init();
});

// all data
function allempStatus() {

    $.ajax({
        type: "GET",
        url: '/md/empStatusData',
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
                        "employee_status_id": dt[i].employee_status_id,
                        "employee_status": dt[i].employee_status,
                        "edit": '<button class="btn btn-primary editEmpStatus" data-bs-toggle="modal" data-bs-target="#modelStatus1" id="' + dt[i].employee_status_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                        "delete": '&#160<button class="btn btn-danger" id="btnEmpStatus" value="Delete" onclick="btnEmpStatusDelete(' + dt[i].employee_status_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                        "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single" id="cbxEmpStatus" value="1" onclick="cbxEmpStatus(' + dt[i].employee_status_id + ')" required ' + isChecked + '></label>'
                    });
                }

                var table = $('#tabalStatus1').DataTable();
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

allempStatus();

function employeestatusTableRefresh() {
    var table = $('#tabalStatus1').DataTable();
    table.columns.adjust().draw();
}
//....save Disgination

function saveStatus() {

    formData.append('txtStatus', $('#txtStatus').val());

    if (formData.txtStatus == '') {
        $('.status').text('Please enter item category level 1');
        return false;
    }
    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/empSaveStatus',
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


            $('#modelStatus1').modal('hide');
            allempStatus();
            if (response.status) {
            showSuccessMessage('Successfully saved');
            console.log(response);
            }else{
                showErrorMessage('Error');
                $('#modelStatus1').modal('hide');
            }

        },
        error: function (error) {
            showErrorMessage('Error');
            $('#modelStatus1').modal('hide');
            console.log(error);


        },
        complete: function () {

        }

    });

}
//edite desgination



$(document).on('click', '.editEmpStatus', function (e) {

    e.preventDefault();
    let employee_status_id = $(this).attr('id');

    $.ajax({
        url: '/md/empStatusEdite/' + employee_status_id,
        method: 'get',
        data: {

            _token: '{{ csrf_token() }}'
        },
        success: function (response) {
            console.log(response);
            $('#btnSaveStatus').hide();
            $('#btnUpdateStatus').show();

            $('#id').val(response.employee_status_id);
            $("#txtStatus").val(response.employee_status);

        },
        error: function (error) {


            console.log(error);


        },
    });
});


// Update desgination



function updateStatus() {
    var id = $('#id').val();
    formData.append('txtStatus', $('#txtStatus').val());

    var cat1 = $('#txtStatus').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/empStatusUpdate/' + id,
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

            allempStatus();
            $('#modelStatus1').modal('hide');
            showSuccessMessage('Successfully updated');
            console.log(data);
        },
        error: function (error) {
            showErrorMessage('Something went wrong');

            $('#modelStatus1').modal('hide');
            console.log(error);


        },
    });
}
}


//desgination status


function cbxEmpStatus(employee_status_id) {
    var status = $('#cbxEmpStatus').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/updateempStatus/' + employee_status_id,
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
        }
    });
}

// desgination Delete

function btnEmpStatusDelete(id) {

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
                deleteStatus(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteStatus(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteempStatus/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                allempStatus();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
          
           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




///////////////////////////////////////////////////////////////////////

//...........Vehicle type...............


const DatatableFixedColumnsvehicle = function () {


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

        var table = $('.datatable-fixed-both_vehicle').DataTable({
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
                { "data": "vehicle_type_id" },
                { "data": "vehicle_type" },
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
    DatatableFixedColumnsvehicle.init();
});


//...vehicle type load Data
function vehicletypeAllData() {

    $.ajax({
        type: "GET",
        url: '/md/getVehicletype',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            if (true) {
                var dt = response.data;

                var data = [];

                for (var i = 0; i < dt.length; i++) {
                    var isChecked = dt[i].is_active ? "checked" : "";



                    data.push({
                        "vehicle_type_id": dt[i].vehicle_type_id,
                        "vehicle_type": dt[i].vehicle_type,
                        "edit": '<button class="btn btn-primary editevehicletype" data-bs-toggle="modal" data-bs-target="#modeVehicletype"  id="' + dt[i].vehicle_type_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                        "delete": '&#160<button class="btn btn-danger"  id="btnvehicleType" value="Delete" onclick="btnVehicleTypeDelete(' + dt[i].vehicle_type_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                        "status": '<label class="form-check form-switch"><input type="checkbox" class="form-check-input" name="switch_single"id="cbxVehicleType" value="1" onclick="cbxVehicleTypeStatus(' + dt[i].vehicle_type_id + ')" required ' + isChecked + '></label>'
                    });

                }


                var table = $('#tabalVehicle').DataTable();
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

vehicletypeAllData();

function vehicletypeTableRefresh() {
    var table = $('#tabalVehicle').DataTable();
    table.columns.adjust().draw();
}



//.....saveCategoryLevel1 Save.....

function saveVehicletype() {

    formData.append('txtVehicletype', $('#txtVehicletype').val());

    console.log(formData);


    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveVehicleType',
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
            vehicletypeAllData();
            $('#modeVehicletype').modal('hide');
            if (response.status) {
            showSuccessMessage('Successfully saved');
            console.log(response);
            }else{
                showErrorMessage('Something went wrong');
                $('#modeVehicletype').modal('hide');
            }

        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modeVehicletype').modal('hide');

            console.log(error);

        },
        complete: function () {

        }

    });

}



//.......edit......

$(document).on('click', '.editevehicletype', function (e) {
    e.preventDefault();
    let vehicle_type_id  = $(this).attr('id');
    $.ajax({
        url: '/md/vehicletypeEdite/' + vehicle_type_id ,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

    $('#btnSaveVehicletype').hide();
    $('#btnUpdateVehicletype').show();

            $('#id').val(response.vehicle_type_id );
            $("#txtVehicletype").val(response.vehicle_type);


        }
    });
});


//....lavel1 Update


function updateVehicletype() {

    var id = $('#id').val();
    formData.append('txtVehicletype', $('#txtVehicletype').val());

    var cat1 = $('#txtVehicletype').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/vehicleTypeUpdate/' + id,
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

            vehicletypeAllData();

            $('#modeVehicletype').modal('hide');
            showSuccessMessage('Successfully updated');


        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#modeVehicletype').modal('hide');
            console.log(error);
        }
    });
}
}


//status update  Level 1


function cbxVehicleTypeStatus(item_category_level_1_id) {
    var status = $('#cbxVehicleType').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/updateVehicletypeStatus/' + item_category_level_1_id,
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
            showErrorMessage('Error')
        }
    });
}



//status update  Level 2



function btnVehicleTypeDelete(id) {

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
                deletevehicleType(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deletevehicleType(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteVehicletype/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                vehicletypeAllData();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
          
           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}



//...........End vehicle type..........



/////////////////////////////////////////////////////////////
/*
function category2() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/category2",
        success: function (data) {

            console.log("dfdgd",data);
            $.each(data, function (key, value) {

                var isChecked = "";
                if (value.status_id) {
                    isChecked = "checked";
                }
                data = data + "<option id='' value=" + value.item_category_level_1_id + ">" + value.category_level_1 + "</option>"
            })
            $('#cmbLeve1').html(data);
        }
    });
}
category2()


function category3() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/category3",
        success: function (data) {
            $.each(data, function (key, value) {

                var isChecked = "";
                if (value.status_id) {
                    isChecked = "checked";
                }
                data = data + "<option id='' value=" + value.Item_category_level_2_id + ">" + value.category_level_2 + "</option>"
            })
            $('#cmbLeve2').html(data);
        }
    });
}
category3()


*/
/////////////////////delivery Type  //////////////////////////////////////////////////




//.....saveCategoryLevel1 Save.....
