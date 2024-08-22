const DatatableFixedColumns = function () {

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
        $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
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
            /*  scrollY: 600, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "distract" },
                { "data": "town" },
                { "data": "edit" },
                { "data": "delete" },


            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });

    };

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});
var formData = new FormData();
$(document).ready(function () {
    $('#btnUpdateTown').hide();

    $('#btnSaveTown').on('click', function () {
        addTown();
    });

    getTowns();

    $('#townModel').on('show.bs.modal', function () {
        getTowns();
        var id = $('#hiddenlbl').val();

      /*   getEachTown(parseInt(id)); */



    });

    
    $('#townmodelshowbtn').on('click',function(){
        
        $('#btnUpdateTown').hide();
        $('#btnSaveTown').show();
    });

    $('#btnUpdateTown').on('click',function(){
        var id = $('#hiddenlbl').val();
        updateTown(id);
    });


});

//deleting item
function _delete(id) {
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
                className: 'btn-link'
            }
        },
        callback: function (result) {
           console.log(result);
           if(result){
            
            deleteTown(id);
           }else{

           }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
   
}



//load districts
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

//add town
function addTown() {

    if(containsValue('town_non_administratives', 'townName',$('#txtTown').val())){
        showWarningMessage('Town already exist');
        return;
     }


    formData.append('txtTown', $('#txtTown').val());
    formData.append('cmbDistrict', $('#cmbDistrict').val());

    $.ajax({

        url: '/md/addTownNonAdministrative',
        method: 'POST',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        async: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);

            var status = response.status;

            if (status) {
                showSuccessMessage("Successfully saved");
                $('#frmTown')[0].reset();


            } else {
                showErrorMessage("Something went wrong");

            }
            getTowns();
              $('#townModel').modal('hide');

        }, error: function (data) {

        }, complete: function () {

        }
    });
    

}

function updateTown(id) {

    formData.append('txtTown', $('#txtTown').val());
    formData.append('cmbDistrict', $('#cmbDistrict').val());

    var cat1 = $('#txtTown').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{
    

    $.ajax({

        url: '/md/updateTownNonAdministrative/'+id,
        method: 'POST',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        async: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);

            var status = response.status;

            if (status) {
                showSuccessMessage("Successfully saved");
                $('#frmTown')[0].reset();


            } else {
                showErrorMessage("Something went wrong");

            }
            getTowns();
              $('#townModel').modal('hide');

        }, error: function (data) {

        }, complete: function () {

        }
    });
}

}


//get towns to list
function getTowns() {

    $.ajax({
        type: "GET",
        url: "/md/getTownList",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];

            for (var i = 0; i < dt.length; i++) {
                var str_primary = parseInt(dt[i].town_id);
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_primary + ')"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                btnDelete = '<button class="btn btn-danger btn-sm" id="btnDlt_' + str_primary + '" onclick="_delete('+ str_primary +')"><i class="fa fa-trash" aria-hidden="true"" ></i></button>';
                data.push({
                    "distract": dt[i].district_name,
                    "town": dt[i].townName,
                    "edit": btnEdit,
                    "delete": btnDelete,

                });
            }


            var table = $('#table_town').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

//get each town to model
function getEachTown(id) {

    $.ajax({
        type: "GET",
        url: "/md/getEachTowninfo/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            $('#cmbDistrict').val(response.district_id);
            $('#txtTown').val(response.townName);

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

function edit(primaryKey) {
    loadDistrict();
    getEachTown(primaryKey);
    
    $('#townModel').modal('show');
    $('#btnUpdateTown').show();
    $('#btnSaveTown').hide();
    $('#hiddenlbl').val(primaryKey);

}

function deleteTown(primaryID){
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteTownN/' + primaryID,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        },success:function(response){
            var status = response
            if(status){
                showSuccessMessage("Successfully deleted");
            }else{
                showErrorMessage("Something went wrong")
            }
          
        },error:function(xhr,status,error){
            console.log(xhr.responseText);
        }
    });

    getTowns();

}

