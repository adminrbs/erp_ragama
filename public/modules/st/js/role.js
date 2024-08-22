var formData = new FormData();
$(document).ready(function () {


   $('#btnUserrole').on('click', function () {
        $('#btnSaveUserrole').show();
        $('#btnUpdateUserrole').hide();
        $('#id').val('');
        $("#txtUserRole").val('');


    });

    $("#btnCloserole").on("click", function(e) {
        // Prevent the default form submission behavior
        e.preventDefault();
        var formData = $("form").serialize();
       /*  $.ajax({
          type: "POST",
          url: '/st/close',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          data: formData,
          success: function(response) {
            $("#modalUserrole").modal("hide"); // This will close the modal
            var urlWithoutQuery = window.location.href.split('?')[0];
        },
          error: function(xhr, status, error) {

          }
        }); */

        $('#modalUserrole').modal('hide');
      });


      $('#btnSaveUserrole').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveUserRole();
    });

    $('#btnUpdateUserrole').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateUserRole();
    });
    $('#btnSaveUserrole').show();
    $('#btnUpdateUserrole').hide();



});


//...Suply Group Data
function userRoleAllData() {

    $.ajax({
        type: "GET",
        url: "/st/useroleAllData",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;
            console.log(dt);
            disabled = "disabled";


            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].status ? "checked" : "";

               data.push({

                   "id":dt[i].id,
                   "name":'<div data-id = "' + dt[i].id + '">' + dt[i].name + '</div>',
                   "edit":'<button class="btn btn-primary userRole" data-bs-toggle="modal" data-bs-target="#modalUserrole" id="' + dt[i].id  + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                   "delete":'&#160<button class="btn btn-danger" onclick="userroleDelete(' + dt[i].id + ')"'+disabled+'><i class="fa fa-trash" aria-hidden="true"></i></button>',
                   "status":'<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxUserRole" value="1" onclick="userrole('+ dt[i].id + ')" required '+isChecked+'></lable>',
               });
            }


            var table = $('#userRoleTable').DataTable();
                table.clear();
                table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })


}

userRoleAllData();



//.....suply Group Save.....

function saveUserRole(){

    formData.append('txtUserRole', $('#txtUserRole').val());

    console.log(formData);


    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/st/saveUserrole',
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
            userRoleAllData();
            $('#modalUserrole').modal('hide');
            if (response.status) {
            showSuccessMessage('Successfully saved');
           console.log(response);
            }else{
                showErrorMessage('Something went wrong');
$('#modalUserrole').modal('hide');
            }

        },
        error: function (error) {
showErrorMessage('Something went wrong');
$('#modalUserrole').modal('hide');
            console.log(error);

        },
        complete: function () {

        }

    });

}

//edit user Role


$(document).on('click', '.userRole', function(e) {
    e.preventDefault();
    let id  = $(this).attr('id');
    $.ajax({
        url: '/st/useroleEdite/'+  id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#btnSaveUserrole').hide();
            $('#btnUpdateUserrole').show();


            $('#id').val(response.id );
            $("#txtUserRole").val(response.name);


        }
    });
});

// User Role Update

function updateUserRole(){

    var id = $('#id').val();
    formData.append('txtUserRole', $('#txtUserRole').val());

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/st/userroleUpdate/'+id,
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

            userRoleAllData();
            $('#modalUserrole').modal('hide');

            showSuccessMessage('Successfully updated')

        }, error: function (error) {
            console.log(error);
            showErrorMessage('Something went wrong')
            $('#modalUserrole').modal('hide');
        }
    });
}

function userroleDelete(id) {

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
           if(result){
            deleteGroup(id);
           }else{

           }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

    }

    function deleteGroup(id) {

        $.ajax({
            type: 'DELETE',
            url: '/st/deleteUserole/' + id,
            data: {
                _token: $('input[name=_token]').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            },success:function(response){
                console.log(response);
                  userRoleAllData();

                showSuccessMessage('Successfully deleted')
            },error:function(xhr,status,error){
                console.log(xhr.responseText);
            }
        });
    }



// Status Save


function userrole(id) {
    var status = $('#cbxUserRole').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/st/updateUserRoleStatus/'+id,
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
        }
    });
}


/////////////////////////////////////////////////////////////////////////



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
                search: '<span class="me-3">Search:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',

                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });



        // Left and right fixed columns
        var table =  $('.datatable-fixed-both').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width:200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 100,
                    targets: [2]
                },

            ],
            scrollX: true,
            //scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "id"},
                { "data": "name" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "status" },



            ],"stripeClasses": [ 'odd-row', 'even-row' ],
        });table.column(0).visible(false);


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


function roleTable() {
    var table = $('#userRoleTable').DataTable();
    table.columns.adjust().draw();
}

function rolListTable() {
    var table = $('#roleListTable').DataTable();
    table.columns.adjust().draw();
}
