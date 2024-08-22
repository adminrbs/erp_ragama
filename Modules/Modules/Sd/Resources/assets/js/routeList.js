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
                    width: 750,
                    targets: 0
                },
                {
                    width: 150,
                    targets: 1
                },
                {
                    width: 150,
                    targets: 2
                },
                {
                    width: 100,
                    targets: 3
                },
                {
                    width: 100,
                    targets: 4
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
                { "data": "order" },
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



const DualListboxes = function () {

    var option_array = [];
    var listBox = undefined;
    // Dual listbox
    const _componentDualListbox = function () {
        if (typeof DualListbox == 'undefined') {
            console.warn('Warning - dual_listbox.min.js is not loaded.');
            return;
        }

        // Buttons text
        const listboxSortingElement = document.querySelector(".listbox-sorting");
        const listboxSorting = new DualListbox(listboxSortingElement, {
            sortable: true,
            upButtonText: "<i class='ph-caret-up'></i>",
            downButtonText: "<i class='ph-caret-down'></i>",
          
           /*  options: option_array, */

            addEvent: function (value) {
                
            },
            removeEvent: function (value) {
               // alert(value);
                /* var selectedBranchId = $('#cmbEmployee').val();

                // alert(value);

                if (selectedBranchId && value) {

                    $.ajax({
                        url: '/sd/selectdeletuserBranch',
                        type: 'POST',
                        data: {
                            branchId: selectedBranchId,
                            eventValue: value
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {

                            showSuccessMessage('Successfully deleted');

                        },
                        error: function (error) {


                        }
                    });
                } else {
                    console.error('Some went wrong');
                } */

                //alert(selectedBranchId);
            },
            

          
            addButtonText: "<i class='ph-caret-right'></i>",
            removeButtonText: "<i class='ph-caret-left'></i>",
            addAllButtonText: "<i class='ph-caret-double-right'></i>",
            removeAllButtonText: "<i class='ph-caret-double-left'></i>",

        });

        listBox = listboxSorting;

        /*   const selectedValues = listboxButtons.getSelected;
          console.log(selectedValues); */

        /* const selectedValues = listboxButtons.selected.map(option => option.value);
        console.log(selectedValues); */



    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentDualListbox();
        },

        geOptionArray: function () {

            return option_array;
        },

        getSelectedOptions: function () {
            var selected_options = [];
            if (listBox != undefined) {
                var list = listBox.selected;
                for (var i = 0; i < list.length; i++) {
                    selected_options.push($(list[i]).attr('data-id'));
                }
            }
            return selected_options;
        },

        clear: function () {
            option_array = [];
        }
    }
}();

/** end of dual list box */



// Initialize module
document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
    DualListboxes.init();
});


/* --------------end of data table--------- */

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
        load_non_admin_towns($('#lblPrimary').val());
        loadSelectedtowns($('#lblPrimary').val());
    });

    $('#routeTownModel').on('hidden.bs.modal', function () {
        $('#cmbFilterData').empty();
        $('#cmbFilterData option').removeAttr('selected');
    });

   

    $('#btn_add_town').on('click',function(){
        add_route_town($('#lblPrimary').val());
    });

    $('#btnCloseGroup').on('click',function(){
        $('#routeModel').modal('hide');
    });

    $('#txtNOrderNum').on('input',function(){
        var inputValue = $(this).val();
        var numericValue = inputValue.replace(/[^0-9]/g, '');
        $(this).val(numericValue);

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

function show_route_town(event){
    
    $('#routeTownModel').modal('show');
    $('#lblPrimary').val($(event).attr('id'));
     
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
                btndelete = '<button class="btn btn-danger btn-sm" id="btnDelete_' + str_primary + '" onclick="_delete(' + str_primary + ')" disabled><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                btnTown = '<button class="btn btn-success btn-sm" id="'+str_primary+'"  onclick="show_route_town(this)"><i class="fa fa-plus" aria-hidden="true"></i></button>';
                data.push({
                    "name": dt[i].route_name,
                    "order":dt[i].route_order,
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
    formData.append('txtNOrderNum',$('#txtNOrderNum').val());
   
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
    formData.append('txtNOrderNum',$('#txtNOrderNum').val());
   
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
                $('#routeModel').modal('hide');


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
            $('#txtNOrderNum').val(response.route_order);
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

function load_non_admin_towns(id){
    $('.cmbFilterData').empty();
    $.ajax({
        type: "GET",
        url: "/sd/load_non_admin_towns/"+id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var data = response.data;

            if (DualListboxes.geOptionArray().length > 0) {
                DualListboxes.clear();
            }
            
            $.each(data, function (index, item) {
                $('#cmbFilterData').append('<option value="' + item.town_id + '">' + item.townName + '</option>');

                /* DualListboxes.geOptionArray().push({ text: item.townName, value: item.town_id,selected: false }); */

            });
            $('.cmbFilterData').remove();
            DualListboxes.init();

            
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}


//save route-town
function add_route_town(id){
    
    var townArray = DualListboxes.getSelectedOptions();
    console.log(townArray);
    
    if(townArray.length < 1){
        showWarningMessage('Please select a town');
    }else{
        $.ajax({
            url: '/sd/add_route_town/'+id,
            method: 'POST',
            data:{
                townArray:townArray
            },
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
    
            }, success: function (response) {
              var status = response.status;
              if(status){
                showSuccessMessage('Record Updated');
                $('#routeTownModel').modal('hide');
              }else{
                showWarningMessage('Unable to save');
              }
    
                
    
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
    
            }
        });

    }

    


}

//load selected item
function loadSelectedtowns(id){
    $('.cmbFilterData').remove();
    $.ajax({
        type: "GET",
        url: "/sd/loadSelectedtowns/"+id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var data = response.data;

            if (DualListboxes.geOptionArray().length > 0) {
                DualListboxes.clear();
            }
            
            $.each(data, function (index, item) {
                $('#cmbFilterData').append('<option value="' + item.town_id + '" selected>' + item.townName + '</option>');

                

            });
            $('.cmbFilterData').remove();
            DualListboxes.init();

            
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}