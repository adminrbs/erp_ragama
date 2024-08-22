/* ----------data table---------------- */
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
                    width: 50,
                    targets: 2
                },
                {
                    width: 1000,
                    targets: 0
                },
                {
                    width: 50,
                    targets: 1
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
                { "data": "name" },
                { "data": "town"},
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

/* ----------------------------------------------------------------------------- */
// Setup module dual list box
// ------------------------------






/** end of dual list box */



// Initialize module
document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
   // DualListboxes.init();
});


/* --------------end of data table--------- */
var h = undefined;
var formData = new FormData();
$(document).ready(function () {
    /* getSalesOrderDetails(); */
    $('#btnUpdateRoute').hide();

    $('#btnSaveRoutes').on('click',function(){
        addRoute();
    })

    $('#btnUpdateRoute').on('click',function(){
        var id = $('#hiddenlbl').val();
        update(id);
    })

    $('#btnRouteModel').on('click',function(){
       
        $('#btnUpdateRoute').hide();
        $('#btnSaveRoutes').show();
    });

    getRoutes();

    $('#routeTownModel').on('shown.bs.modal', function () {
        $('.cmbFilterData').remove();

        // Options
         const listboxOptionsElement = document.querySelector(".listbox-sorting");
        const listboxOptions = new DualListbox(listboxOptionsElement, {
            options: [
                { text: "Classical mechanics", value: "option1", selected: true },
                { text: "Electromagnetism", value: "option2" },
                { text: "Relativity", value: "option3" },
                { text: "Quantum mechanics", value: "option4", selected: true },
                { text: "Astrophysics", value: "option5" },
                { text: "Biophysics", value: "option6", selected: true },
                { text: "Chemical physics", value: "option7" },
                { text: "Econophysics", value: "option8" },
                { text: "Geophysics", value: "option9" },
                { text: "Medical physics", value: "option10" },
                { text: "Physical chemistry", value: "option11" },
                { text: "Continuum mechanics", value: "option12", selected: true },
                { text: "Electrodynamics", value: "option13" },
                { text: "Quantum field theory", value: "option14", selected: true },
                { text: "Scattering theory", value: "option15" },
                { text: "Chaos theory", value: "option16", selected: true },
                { text: "Newton's laws of motion", value: "option17", selected: true },
                { text: "Thermodynamics", value: "option18" },
                { text: "Option 2", value: "option19" },
                { text: "Option 1", value: "option20" },
                { text: "Option 2", value: "option21" },
                { text: "Option 1", value: "option22" },
                { text: "Option 2", value: "option23" }
            ],
            sortable: true,
            upButtonText: "<i class='ph-caret-up'></i>",
            downButtonText: "<i class='ph-caret-down'></i>"
        }); 
        
        load_non_admin_towns();
    });
    
});

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
                deleteRoute(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function show_route_town(){
    
    $('#routeTownModel').modal('show');
}





function getRoutes() {
    $.ajax({
        type: "GET",
        url: "/sd/getRoutes",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];

            for (var i = 0; i < dt.length; i++) {

                var str_primary = dt[i].route_id;
              
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_primary + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                btndelete = '<button class="btn btn-danger btn-sm" id="btnDelete_' + str_primary + '" onclick="_delete(' + str_primary + ')"><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                btnTown = '<button class="btn btn-success btn-sm id="btnTown" onclick="show_route_town()"><i class="fa fa-plus" aria-hidden="true"></i></button>';
                data.push({
                    "name": dt[i].route_name,
                    "town": btnTown,
                    "edit": btnEdit,
                    "delete": btndelete,
                    
                   
                })
             
            }


            var table = $('#route_list').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function deleteRoute(id) {
   
    $.ajax({
        url: '/sd/deleteRoute/' + id,
        type: 'delete',
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response;
            if (status) {
                showSuccessMessage("Successfully deleted");

            } else {
                showErrorMessage("Something went wrong")
            }

            getRoutes();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })
}

//add route
function addRoute() {

    if(containsValue('routes', 'route_name', $('#txtRoute').val())){
        showWarningMessage('Route already exist');
        return;
     }

    formData.append('txtRoute', $('#txtRoute').val());
   
    $.ajax({

        url: '/sd/addRoute',
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
                $('#frmRoute')[0].reset();


            } else {
                showErrorMessage("Something went wrong");

            }
            getRoutes();

        }, error: function (data) {

        }, complete: function () {
           
        }
    });

} 

//update

function update(id) {

    formData.append('txtRoute', $('#txtRoute').val());
   
    $.ajax({

        url: '/sd/updateRoute/'+id,
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
                $('#frmRoute')[0].reset();


            } else {
                showErrorMessage("Something went wrong");

            }
            getRoutes();

        }, error: function (data) {

        }, complete: function () {
           
        }
    });

} 



function getEachRoute(id) {

    $.ajax({
        type: "GET",
        url: "/sd/getEachRoute/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            $('#txtRoute').val(response.route_name);
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}


function edit(primaryKey) {
    
    getEachRoute(primaryKey); 
    
    $('#routeModel').modal('show');
    $('#btnUpdateRoute').show();
    $('#btnSaveRoutes').hide();
    $('#hiddenlbl').val(primaryKey);
   

}

function load_non_admin_towns(){
    $.ajax({
        type: "GET",
        url: "/sd/load_non_admin_towns",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var data = response.data;

           /*  if (DualListboxes.geOptionArray().length > 0) {
                DualListboxes.clear();
            } */

            $.each(data, function (index, item) {


                /* DualListboxes.geOptionArray().push({ text: item.townName, value: item.town_id,selected: false }); */

            });
          /*   $('.cmbFilterData').remove();
            DualListboxes.init(); */

            
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}