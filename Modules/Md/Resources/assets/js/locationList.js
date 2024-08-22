const DatatableFixedColumns = function () {

    // Setup module components

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
                searchPlaceholder: 'Press enter to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }


        });


        // Left and right fixed columns
        var table = $('.datatable-fixed-both').DataTable({
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/md/getLocationDetails',
               
            },
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: '20%',
                    targets: 2
                },
                {
                    width: '200px',
                    targets: 0,
                    
                },
                {
                    width: '50%',
                    targets: 1,
                    
                },
   

            ],
            
            fixedColumns: true,
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            "info":false,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
           /*  "autoWidth": false, */
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "branch_name" },
                { "data": "location_name" },
                { "data": "location_type_name" },
                { "data": "address" }, 
                { "data": "statusLabel" },
                { "data": "buttons" },


            ],
            "stripeClasses": ['odd-row', 'even-row']
        });


    };

    // Return objects assigned to module

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


$(document).ready(function () {

    getLocationDetails();

});


function edit(id) {

    url = "/md/location?id=" + id + "&action=edit";
    window.open(url, "_blank");



}

function view(id) {
    url = "/md/location?id=" + id + "&action=view";
    window.open(url, "_blank");
}


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
            if (result) {
                deleteLocation(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');


}

// getting location details to list
function getLocationDetails() {
    $.ajax({
        type: "GET",
        url: "/md/getLocationDetails",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            console.log(dt);
            var data = [];
            disabled = "disabled";

            for (var i = 0; i < dt.length; i++) {
                var label = '<label class="badge bg-danger">' + dt[i].is_active + '</label>';
                if (dt[i].is_active == "Yes") {
                    label = '<label class="badge bg-success">' + dt[i].is_active + '</label>';
                }

                data.push({
                    "location_name": dt[i].location_name,
                    "address": dt[i].address,
                    "location_type": dt[i].location_type_name,
                    "active": label,
                    "action": '<button class="btn btn-primary" onclick="edit(' + dt[i].location_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success" onclick="view(' + dt[i].location_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger" onclick="_delete(' + dt[i].location_id + ')"'+disabled+'><i class="fa fa-trash" aria-hidden="true"></i></button>',
                });


                /*  status = "off";
                 data.push({
                     "location_name": dt[i].location_name,
                     "Status": status,
                     "action":'<button class="btn btn-primary" onclick="edit(' + dt[i].location_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success" onclick="view(' + dt[i].location_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger" onclick="_delete(' + dt[i].location_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                 }); */

                /* { "data": "location_name" },
            { "data": "address" },
            { "data": "location_type" },
            { "data": "Status" },
            { "data": "action" }, */


            }

            var table = $('#locationTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function deleteLocation(id) {
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteLocation/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            var status = response;
            if (status) {
                showSuccessMessage("Successfully deleted");
                getLocationDetails();
            } else {
                showErrorMessage("Something went wrong");
            }
            getLocationDetails();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}