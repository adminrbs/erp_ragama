var formData = new FormData();
var ROLE_ID = undefined;
$(document).ready(function () {


    $('#userRoleTable').on('click', 'tr', function (e) {
        $('#userRoleTable tr').removeClass('selected');

        $(this).addClass('selected');

        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children(); // or hiddenValue.find('*');
        childElements.each(function () {

            ROLE_ID = $(this).attr('data-id');

            userAllData(ROLE_ID);



        });
    });

});
const DatatableFixedColumn = function () {


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
        var table =  $('.datatable-fixed-both-rolelist').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
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
                    width: 400,
                    targets: 1,

                },
                {
                    width: '100%',
                    targets: [2],

                },

            ],
            scrollX: true,
           // scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "email" },
                { "data": "user_role" },
                { "data": "user_type" },



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
    DatatableFixedColumn.init();
});




function userAllData(id) {


    $.ajax({
        type: "GET",
        url: "/st/getuserData/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {



                data.push({

                    "id": dt[i].id,
                    "name": dt[i].name,
                    "email": dt[i].email,
                    "user_role": dt[i].role_name,
                    "user_type": dt[i].user_type,



                });
            }


            var table = $('#roleListTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

userAllData();

///.................................Edite Delete..........................................................
function edituser(id) {

    location.href = "/user?id=" + id + "&action=editu";
}



///....................................................................

function deleteuser(id) {
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
            deleteUser(id);
           }else{

           }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

    }

    function deleteUser(id) {
        $.ajax({
            type: 'DELETE',
            url: '/deleteusers/' + id,
            data: {
                _token: $('input[name=_token]').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            },success:function(response){
                console.log(response);
                userAllData(ROLE_ID)
                showSuccessMessage('Successfully deleted');
            },error:function(xhr,status,error){
                console.log(xhr.responseText);
            }
        });
    }

