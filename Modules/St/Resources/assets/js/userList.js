


const DatatableFixedColumns = function () {


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
                    width: 350,
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
            "pageLength": 10,
            "order": [],
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "email" },
                { "data": "user_role" },
                { "data": "user_type" },
                { "data": "edit" },
                { "data": "delete" },

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




function userAllData() {


    $.ajax({
        type: "GET",
        url: "/st/getuserAllData",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;
            disabled = "disabled";

            var data = [];
            for (var i = 0; i < dt.length; i++) {



                data.push({

                    "id": dt[i].id,
                    "name": dt[i].name,
                    "email": dt[i].email,
                    "user_role": dt[i].role_name,
                    "user_type": dt[i].user_type,


                    "edit": '<button class="btn btn-primary btn-sm"  onclick="edit(' + dt[i].id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true fa-sm"></i></button>',
                    "delete": '&#160<button class="btn btn-danger btn-sm" onclick="deleteuser(' + dt[i].id + ')"'+disabled+'><i class="fa fa-trash" aria-hidden="true fa-sm"></i></button>',

                });
            }


            var table = $('#userListTable').DataTable();
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



function edit(id) {

    url= "/st/user?id=" + id + "&action=edit";
    window.open(url,"_blank");

    //location.href = "/user?id=" + id + "&action=edit";
}




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
            url: '/st/deleteusers/' + id,
            data: {
                _token: $('input[name=_token]').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            },success:function(response){
                console.log(response);
                userAllData()
                showSuccessMessage('Successfully deleted');
            },error:function(xhr,status,error){
                console.log(xhr.responseText);
            }
        });
    }

