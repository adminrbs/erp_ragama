
const DeliveryPlanRouteTable = function () {

    var route_table = undefined;
    const _DeliveryPlanRouteFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [1]
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
        route_table = $('.datatable-fixed-both-delivery-plan-route').DataTable({
            columnDefs: [
                {
                    width: '100%',
                    targets: 0
                },
                {
                    width: 0,
                    targets: 1
                },
                {
                    width: 80,
                    targets: 2
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '5px');
                    }
                },
            ],
            scrollX: true,
            scrollY: 300,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 1
            },
            "paging": false,
            "pageLength": 20,
            "order": [],
            "columns": [

                { "data": "route" },
                { "data": "route_id" },
                { "data": "remove" },

            ],
        });

        // Adjust columns on window resize
        setTimeout(function () {
            $(window).on('resize', function () {
                route_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanRouteFixedColumns();
        },
        refresh: function () {
            if (route_table != undefined) {
                route_table.columns.adjust();
            }
        },
        removeRow: function (row) {
            route_table.row(row).remove().draw();
        },
        rowCount: function () {
            var rowCount = route_table.rows().count();
            return rowCount;
        }
    }
}();



const DeliveryPlanTownTable = function () {

    var town_table = undefined;
    const _DeliveryPlanTownFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [1]
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
        town_table = $('.datatable-fixed-both-delivery-plan-town').DataTable({
            columnDefs: [
                {
                    width: 50,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 50,
                    targets: 2
                }
            ],
            scrollX: true,
            scrollY: 300,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1,
                rightColumns: 1
            },
            "pageLength": 20,
            "order": [],
            "columns": [

                { "data": "town_check" },
                { "data": "town" },
                { "data": "order", className: "editable", },

            ],
        });

        // Adjust columns on window resize
        setTimeout(function () {
            $(window).on('resize', function () {
                town_table.columns.adjust();
            });
        }, 100);

    };

    return {
        init: function () {
            _DeliveryPlanTownFixedColumns();
        },
        refresh: function () {
            if (town_table != undefined) {
                town_table.columns.adjust();
            }
        }
    }
}();

document.addEventListener('DOMContentLoaded', function () {
    DeliveryPlanRouteTable.init();
    DeliveryPlanTownTable.init();
});




/** append options to select2 tags */
function loadSelect2() {

    $.ajax({
        type: "GET",
        url: '/sd/loadDeliveryPlanSelect2',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                var select = response.data;
                var vehicle = select.vehicle;
                var salserep = select.salserep;
                var driver = select.driver;
                var helper = select.helper;
                var route = select.route;
                var district = select.district;
                var statuses = select.delviery_statuses;
                console.log(statuses);



                /** Append vehicle group */
                $('#cmbVehicle').empty();
                $('#cmbVehicle').append('<option value="0">To be filled later</option>');
                for (var i = 0; i < vehicle.length; i++) {
                    var vehicle_id = vehicle[i].vehicle_id;
                    var vehicle_name = vehicle[i].vehicle_name;
                    $('#cmbVehicle').append('<option value="' + vehicle_id + '">' + vehicle_name + '</option>');
                }
                /** End of vehicle group */



                /** Append salserep */
                /*$('#cmbSalesRep').empty();
                for (var i = 0; i < salserep.length; i++) {
                    var employee_id = salserep[i].employee_id;
                    var employee_name = salserep[i].employee_name;
                    $('#cmbSalesRep').append('<option value="' + employee_id + '">' + employee_name + '</option>');
                }*/
                /** End of salserep */



                /** Append driver */
                $('#cmbDriver').empty();
                $('#cmbDriver').append('<option value="0">To be filled later</option>');
                for (var i = 0; i < driver.length; i++) {
                    var employee_id = driver[i].employee_id;
                    var employee_name = driver[i].employee_name;
                    $('#cmbDriver').append('<option value="' + employee_id + '">' + employee_name + '</option>');
                }
                /** End of driver */

                /** Append helper */
                $('#cmbHelper').empty();
                $('#cmbHelper').append('<option value="0">To be filled later</option>');
                for (var i = 0; i < helper.length; i++) {
                    var employee_id = helper[i].employee_id;
                    var employee_name = helper[i].employee_name;
                    $('#cmbHelper').append('<option value="' + employee_id + '">' + employee_name + '</option>');
                }
                /** End of helper */


                /** Append route */
                $('#cmbRoute').empty();
                for (var i = 0; i < route.length; i++) {
                    var route_id = route[i].route_id;
                    var route_name = route[i].route_name;
                    $('#cmbRoute').append('<option value="' + route_id + '">' + route_name + '</option>');
                }
                //getTownsFromRoute($('#cmbRoute').val());
                /** End of route */


                /** Append district */
                $('#cmbDistrict').empty();
                for (var i = 0; i < district.length; i++) {
                    var district_id = district[i].district_id;
                    var district_name = district[i].district_name;
                    $('#cmbDistrict').append('<option value="' + district_id + '">' + district_name + '</option>');
                }
                getTowns($('#cmbDistrict').val());
                /** End of district */


                /**Append delivery statuses */
                $('#cmbStatus').empty();
                for (var i = 0; i < statuses.length; i++) {
                    var status_id = statuses[i].statuse_id;
                    var status_name = statuses[i].status;
                    $('#cmbStatus').append('<option value="' + status_id + '">' + status_name + '</option>');
                }
                /** End of delivery statuses */

            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}
/** end of append options to select2 tags */



/** Load Town from District */
function getTowns(district) {
    $.ajax({
        type: "GET",
        url: '/sd/loadLownsSelect2/' + district,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                var town = response.data;


                /** Append town */
                $('#cmbTown').empty();
                $('#cmbTown').append('<option value="disabled" selected>--Select Town--</option>');
                for (var i = 0; i < town.length; i++) {
                    var town_id = town[i].town_id;
                    var town_name = town[i].townName;
                    $('#cmbTown').append('<option value="' + town_id + '">' + town_name + '</option>');
                }
                /** End of town */
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}
/** End of Load Town from District */



function addTown() {



    var selected_option = $('#cmbTown option:selected').text();
    if (selected_option != '--Select Town--') {
        var sortTownArray = getSortTownArray();

        for (let i = 0; i < sortTownArray.length; i++) {
            if ($(sortTownArray[i][0]).text() == selected_option) {
                showWarningMessage('Already exist ' + selected_option);
                return;
            }
        }

        var new_data = [{
            "town_check": '<input type="checkbox" id="townCheck">',
            "town": '<label data-id = "' + $('#cmbTown').val() + '">' + $('#cmbTown option:selected').text() + '</label',
            "order": "",
        }];
        var table = $('#townTable').DataTable();
        table.rows.add(new_data).draw();
        $('.editable').attr('contenteditable', true);
    }

}


function sortTowns() {


    var data = [];
    var sortTownArray = getSortTownArray();

    for (let i = 0; i < sortTownArray.length; i++) {
        data.push({
            "town_check": '<input type="checkbox" id="townCheck' + $(sortTownArray[i][0]).attr('data-id') + '">',
            "town": '<label data-id = "' + $(sortTownArray[i][0]).attr('data-id') + '">' + $(sortTownArray[i][0]).text() + '</label',
            "order": sortTownArray[i][1],
        });
    }
    /*var table = $('#townTable').DataTable();
    table.clear();
    table.rows.add(data).draw();
    $('.editable').attr('contenteditable', true);*/
}


function getSortTownArray() {
    var sortTownArray = [];
    var table = document.getElementById('townTable'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, id;

    for (i = 0, j = rows.length; i < j; ++i) {
        cells = rows[i].getElementsByTagName('td');
        if (!cells.length) {
            continue;
        }


        if ($(cells[0].childNodes[0]).is(":checked")) {
            var valueData = [];
            var object0 = $(cells[1].childNodes[0]);
            var object1 = $(cells[2]);
            valueData.push($(object0));
            valueData.push($(object1).text());


            sortTownArray.push(valueData);
        }

    }

    return sortTownArray.sort();
}


function getRouteArray() {
    var routeArray = [];
    var table = document.getElementById('routeTable'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, id;

    for (i = 0, j = rows.length; i < j; ++i) {
        cells = rows[i].getElementsByTagName('td');
        if (!cells.length) {
            continue;
        }

        var object0 = $(cells[0].childNodes[0]);
        routeArray.push($(object0).attr('data-id'));


    }

    return routeArray.sort();
}


function getTownsFromRoute(route_id) {

    $.ajax({
        type: "GET",
        url: '/sd/getTownsFromRoute/' + route_id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var table = document.getElementById('townTable'),
                rows = table.getElementsByTagName('tr'),
                i, j, cells, id;
            var count = (1 + (rows.length - 2));
            var result = response.data;
            var data = [];
            for (let i = 0; i < result.length; i++) {
                data.push({
                    "town_check": '<input type="checkbox" id="townCheck' + (i + count) + '" checked>',
                    "town": '<label data-id = "' + result[i].town_id + '">' + result[i].townName + '</label',
                    "order": (i + count),
                });

            }

            var table = $('#townTable').DataTable();
            //table.clear();
            table.rows.add(data).draw();
            $('.editable').attr('contenteditable', true);

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}



function modalReset() {
    loadSelect2();
    $('#tabDeliveryPlan').trigger('click');
    $('#more_town').removeClass('show');
    $('#txtRefNo').val('');
    $('#btnAction').text('Save');
    $('#btnAction').show();
    $('#routeWarning').hide();
    var route_table = $('#routeTable').DataTable();
    route_table.clear().draw();
    var town_table = $('#townTable').DataTable();
    town_table.clear().draw();
   
}


function removeRouteFromDeliveryPlan(id, delivery_plan_id) {

    var bool = isInvoiceToRoute(delivery_plan_id, id);
    if (bool) {
        $('#routeWarning').show();
        return;
    }

    $.ajax({
        type: "DELETE",
        url: '/sd/removeRouteFromDeliveryPlan/' + id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                showSuccessMessage('Deleted..!');
                getDeliveryPlan(delivery_plan_id);
                //removeRoute(row);
            } else {
                showErrorMessage('Something went wrong');
            }

        },
        error: function (error) {
            console.log(error);
            showErrorMessage('Something went wrong');

        },
        complete: function () {

        }

    });
}


function removeRoute(id) {
    DeliveryPlanRouteTable.removeRow(id);


}


/** isInvoiceToRoute */
function isInvoiceToRoute(delivery_plan_id, route_id) {
    var bool = false;
    $.ajax({
        type: "GET",
        url: '/sd/isInvoiceToRoute/' + delivery_plan_id + '/' + route_id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                bool = true;
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
    return bool;
}
/** End of Load Town from District */


