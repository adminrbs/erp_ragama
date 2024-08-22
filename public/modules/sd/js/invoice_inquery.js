

/* ----------data table---------------- */
const DatatableDeliveryPlanFixedColumns = function () {

    // Basic Datatable examples
    const _componentDatatableDeliveryPlanFixedColumns = function () {
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
                paginate: {
                    'first': 'First',
                    'last': 'Last',
                    'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                    'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
                }
            }

        });

        // Left and right fixed columns
        $('.datatable-fixed-both-delivery-plan').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "20px");
            },
            columnDefs: [
                {
                    width: 50,
                    height: 20,
                    targets: 0
                },
                {
                    width: 80,
                    height: 20,
                    targets: 1,

                },
                {
                    width: 100,
                    height: 20,
                    targets: 2,

                },
                {
                    width: 100,
                    height: 20,
                    targets: 3,

                },
                {
                    width: 20,
                    height: 20,
                    targets: 4,

                },
                {
                    width: 40,
                    height: '100%',
                    targets: 5,

                },
                {
                    width: 40,
                    height: 20,
                    targets: 6,

                },
                {
                    width: 80,
                    targets: [8]
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '2px');
                    }
                },


            ],
            scrollX: true,
            //scrollY: 200,
            scrollCollapse: false,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                {
                    "data": "referance"
                },
                {
                    "data": "vehicle"
                },
                {
                    "data": "driver",
                    className: "col-width",

                },
                {
                    "data": "helper",
                    className: "right-align",
                },
                {
                    "data": "no_of_invoice",
                    className: "center-align",
                },
                {
                    "data": "route",
                },
                {
                    "data": "date_from_to",
                    className: "right-align",

                },
                {
                    "data": "status",
                    className: "center-align",

                },
                {
                    "data": "action",
                    className: "right-align",

                }

            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });

    };

    return {
        init: function () {
            _componentDatatableDeliveryPlanFixedColumns();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function () {
    DatatableDeliveryPlanFixedColumns.init();
});
/* --------------end of data table--------- */

var action = undefined;
var DELIVERY_PLAN_ID = undefined;
var REFERANCE_ID = undefined;
$(document).ready(function () {

    //loadSelect2();
    getDeliveryPlans();

    // Single picker
    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
        }
    });
    // End of Single picker

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
        DeliveryPlanRouteTable.refresh();
        DeliveryPlanTownTable.refresh();
        DeliveryPlanInvoiceTable.refresh();
        DeliveryPlanAllocatedInvoiceTable.refresh();
        DeliveryPlanNonPickingTable.refresh();
        DeliveryPlanPickingTable.refresh();
    });

    $('.select2').select2();

    $('#cmbDistrict').on('change', function () {
        getTowns($(this).val());
    });

    $('#btnAddTown').on('click', function () {
        addTown();
    });

    $('#cmbRoute').on('change', function () {
        //getTownsFromRoute($(this).val());
    });

    $('#btnAddroute').on('click', function () {
        addRoute($('#cmbRoute').val(), $('#cmbRoute option:selected').text());
    });

    $('#townTable').focusout(function () {
        sortTowns();
    });

    $('#btnAdd').on('click', function () {
        modalReset();
        $('#modalDeliveryPlan').modal('show');
        newReferanceID('delivery_plans', 510);
    });

    $('#btnAction').on('click', function () {
        if ($(this).text() === 'Save') {
            saveDeliveryPlan();
        } else if ($(this).text() === 'Update') {
            updateDeliveryPlan();
        }

    });

    $('#modalDeliveryPlanInvoice').on('shown.bs.modal', function () {
        showActionInvoice();
        DeliveryPlanInvoiceTable.refresh();
        DeliveryPlanAllocatedInvoiceTable.refresh();
        $("#mainInvoiceCheck").prop('checked', false);
    });

    $('#modalDeliveryPlanInvoiceList').on('shown.bs.modal', function () {
        DeliveryPlanAllocatedInvoiceListTable.refresh();
    });

    $('#modalDeliveryPostponeList').on('shown.bs.modal', function () {
        DeliveryPostponeTable.refresh();
    });


    $('#modalDeliveryPlanPackingList').on('shown.bs.modal', function () {
        hideActionPickingListInvoice();
        DeliveryPlanNonPickingTable.refresh();
        DeliveryPlanPickingTable.refresh();
    });

    $('#modalDeliveryPlan').on('shown.bs.modal', function () {

        $(this).find('.select2').select2({
            dropdownParent: $(this)
        });

    });



    $('#mainInvoiceCheck').on('click', function () {
        $("input[name='invoiceCheck']").prop('checked', $(this).is(':checked'));
    });

    $('#mainNonPickingCheckCheck').on('click', function () {
        $("input[name='nonPickingCheck']").prop('checked', $(this).is(':checked'));
    });

    $('#btnActionInvoice').on('click', function () {
        saveDeliveryPlanInvoice();
    });

    $('#btnActionPickingListInvoice').on('click', function () {
        var external_no = $('#hid_delivery_plan_external_no').val();
        saveDeliveryPlanNonPickingInvoice(external_no);
    });

    $('#btnUpdateAllocatedRemark').on('click', function () {
        updateAllocatedRemark();
    });

    $('#btnUpdateDeliveryPostpone').on('click', function () {
        updatePostponeDelivery();
    });


});




function newReferanceID(table, doc_number) {

    REFERANCE_ID = newID("/sd/delivery_plan/new_referance_id", table, doc_number);
    $('#txtRefNo').val('New Delivery Plan');

}



function appendRow(result) {
    console.log(result);
    var data = [];
    for (let i = 0; i < result.length; i++) {
        var external_no = result[i].external_number;
        var str_external_no = "'" + external_no + "'";
        var delivery_plan_id = result[i].delivery_plan_id;
        var str_delivery_plan_id = "'" + delivery_plan_id + "'";
        //var str_sales_rep_id = "'" + result[i].sales_rep_id + "'";
        var str_route_id = "'" + result[i].route_id + "'";
        var menu = '<div class="dropdown position-static" style=" z-index: 1000;">';
        menu += '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i></a >';
        menu += '<div class="dropdown-menu dropdown-menu-end">';
        menu += '<a class="dropdown-item" href="#"  onclick="showInvoice(' + str_delivery_plan_id + ',' + str_route_id + ')">Allocate Invoice</a>';
        //menu += '<a class="dropdown-item" href="/sd/pickinglist/' + delivery_plan_id + '">Picking List</a>';
        menu += '<a class="dropdown-item" href="#" onclick="showPickingListModal(' + str_delivery_plan_id + ',' + str_external_no + ',' + str_route_id + ')">Picking List</a>';
        menu += '<a class="dropdown-item" href="/sd/delivery_report/' + delivery_plan_id + '" >Delivery Report</a>';
        menu += '<a class="dropdown-item" href="#" onclick="showPostponeDelivery(' + delivery_plan_id + ')" >Postpone delivery</a></div>';



        var status_name = "Schedule";
        if (result[i].status == 2) {
            status_name = "Preparig";
        } else if (result[i].status == 3) {
            status_name = "Delivered";
        }
        var driver = "";
        if (result[i].driver) {
            driver = shortenString(result[i].driver, 15);
        }
        var helper = "";
        if (result[i].helper) {
            helper = shortenString(result[i].helper, 15);
        }
        data.push({
            "referance": result[i].delivery_ref_no,
            "vehicle": result[i].vehicle_name,
            //"sales_rep": result[i].sales_rep,
            "driver": driver,
            "helper": helper,
            "no_of_invoice": '<lable>' + result[i].invoice_count + '<a href="#" onclick="loadAllocatedInvoiceList(' + str_delivery_plan_id + ')">&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a></label>',
            "route": result[i].route,
            "date_from_to": '<div class="row"><div class="col-md-12">From ' + result[i].date_from + '</div><div class="col-md-12">To&nbsp;&nbsp;&nbsp;&nbsp; ' + result[i].date_to + '</div></div>',
            "status": status_name,
            "action": '&nbsp;&nbsp;' + menu + '&nbsp;&nbsp;<button title="Invoice" class="btn btn-warning" onclick="showInvoice(' + str_delivery_plan_id + ',' + str_route_id + ')"><i class="fa fa-file-text" aria-hidden="true"></i></button>&nbsp;<button title="Edit" class="btn btn-primary" onclick="edit(' + str_delivery_plan_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&nbsp;<button title="View"  class="btn btn-success" onclick="view(' + str_delivery_plan_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></i></button>&nbsp;<button title="Delete" class="btn btn-danger" disabled><i class="fa fa-trash" aria-hidden="true"></i></button>',
        });
    }


    var table = $('#deliveryPlanTable').DataTable();
    table.clear();
    table.rows.add(data).draw();
    $('.editable').attr('contenteditable', true);


}




/** save delevery plan */
function saveDeliveryPlan() {

    if ($('#txtRefNo').val().trim().length === 0) {
        $('#tabDeliveryPlan').trigger('click');
        $('#txtRefNo').focus();
        showWarningMessage('Invalied Ref No');
        return;
    }


    if ($('#txtDateFrom').val().trim().length === 0) {
        $('#tabDeliveryPlan').trigger('click');
        $('#txtDateFrom').focus();
        showWarningMessage('Invalied Date From');
        return;
    }

    if ($('#txtDateTo').val().trim().length === 0) {
        $('#tabDeliveryPlan').trigger('click');
        $('#txtDateTo').focus();
        showWarningMessage('Invalied Date To');
        return;
    }

    /*if ($('#cmbVehicle').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbVehicle').focus();
        showWarningMessage('Invalied Vehicle');
        return;
    }

    /*if ($('#cmbSalesRep').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbSalesRep').focus();
        showWarningMessage('Invalied Sales Rep');
        return;
    }

    if ($('#cmbDriver').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbDriver').focus();
        showWarningMessage('Invalied Driver');
        return;
    }

    if ($('#cmbHelper').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbHelper').focus();
        showWarningMessage('Invalied Helper');
        return;
    }*/

    if ($('#cmbRoute').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbRoute').focus();
        showWarningMessage('Invalied Route');
        return;
    }

    if (REFERANCE_ID == undefined) {
        showWarningMessage('Invalied Ref No');
        return;
    }

    $.ajax({
        url: '/sd/saveDeliveryPlan',
        method: 'post',
        data: createFormData(),
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);

            if (response.status) {
                $('#modalDeliveryPlan').modal('hide');
                showSuccessMessage('Data saved');
                getDeliveryPlans();
            }


        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}
/**end of save delevery plan */




/** create form data */
function createFormData() {

    var data = new FormData();
    data.append("delivery_ref_no", REFERANCE_ID);
    data.append("vehicle_id", $('#cmbVehicle').val());
    //data.append("sales_rep_id", $('#cmbSalesRep').val());
    data.append("driver_id", $('#cmbDriver').val());
    data.append("helper_id", $('#cmbHelper').val());
    //data.append("route_id", $('#cmbRoute').val());
    data.append("date_from", $('#txtDateFrom').val());
    data.append("date_to", $('#txtDateTo').val());
    data.append("status", $('#cmbStatus').val());
    data.append("district_id", $('#cmbDistrict').val());
    var routeArray = getRouteArray();

    var sortTownArray = getSortTownArray();
    var towns_array = [];
    for (let i = 0; i < sortTownArray.length; i++) {
        towns_array.push(JSON.stringify(
            {
                "town_id": $(sortTownArray[i][0]).attr('data-id'),
                "order": sortTownArray[i][1]
            }
        ));
    }
    data.append("routes", JSON.stringify(routeArray));
    data.append("towns", JSON.stringify(towns_array));
    return data;
}
/**end of create form data */



function getDeliveryPlans() {

    $.ajax({
        type: "GET",
        url: '/sd/getDeliveryPlans',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var result = response.data;
            appendRow(result);

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}


function showInvoice(delivery_plan_id, route_id) {
    loadNonAllocatedInvoice(delivery_plan_id);
    loadAllocatedInvoice(delivery_plan_id);
    $('#modalDeliveryPlanInvoice').modal('show');

}


function edit(delivery_plan_id) {

    DELIVERY_PLAN_ID = delivery_plan_id;
    modalReset();
    $('#btnAction').text('Update');
    getDeliveryPlan(delivery_plan_id);
    $('#modalDeliveryPlan').modal('show');
}


function view(delivery_plan_id) {
    modalReset();
    $('#btnAction').hide();
    getDeliveryPlan(delivery_plan_id);
    $('#modalDeliveryPlan').modal('show');
}




/** Load Deliveryplan to the modal */
function getDeliveryPlan(delivery_plan_id) {
    $.ajax({
        type: "GET",
        url: '/sd/getDeliveryPlan/' + delivery_plan_id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.status) {
                var delivery_plan = response.data;
                $('#txtRefNo').val(delivery_plan.delivery_ref_no);
                $('#cmbVehicle').val(delivery_plan.vehicle_id);
                //$('#cmbSalesRep').val(delivery_plan.sales_rep_id);
                $('#cmbDriver').val(delivery_plan.driver_id);
                $('#cmbHelper').val(delivery_plan.helper_id);
                //$('#cmbRoute').val(delivery_plan.route_id);
                $('#txtDateFrom').val(delivery_plan.date_from);
                $('#txtDateTo').val(delivery_plan.date_to);
                $('#cmbStatus').val(delivery_plan.status);
                // $('#cmbDistrict').val(delivery_plan.);

                /** Append row route list */
                var routes = delivery_plan.delivery_plan_route_list;
                var data_routes = [];
                for (let i = 0; i < routes.length; i++) {
                    data_routes.push({
                        "route": '<label data-id = "' + routes[i].route_id + '">' + routes[i].route_name + '</label',
                        "route_id": "",
                    });

                }

                var table_route = $('#routeTable').DataTable();
                table_route.clear();
                table_route.rows.add(data_routes).draw();
                /** End of Append row route list */



                /** Append row town list */
                var towns = delivery_plan.delivery_plan_town_list;
                var data = [];
                for (let i = 0; i < towns.length; i++) {
                    data.push({
                        "town_check": '<input type="checkbox" id="townCheck' + i + '" checked>',
                        "town": '<label data-id = "' + towns[i].town_id + '">' + towns[i].townName + '</label',
                        "order": towns[i].order,
                    });

                }

                var table = $('#townTable').DataTable();
                table.clear();
                table.rows.add(data).draw();
                $('.editable').attr('contenteditable', true);
                /** End of Append row town list */
            }
        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}
/** Load Deliveryplan to the modal */




/** Update Delivery Plan and Town list */
function updateDeliveryPlan() {

    if (DELIVERY_PLAN_ID === undefined) {
        showWarningMessage('Invalied Delivery Plan..');
        return;
    }

    if ($('#txtRefNo').val().trim().length === 0) {
        $('#tabDeliveryPlan').trigger('click');
        $('#txtRefNo').focus();
        showWarningMessage('Invalied Ref No');
        return;
    }


    if ($('#txtDateFrom').val().trim().length === 0) {
        $('#tabDeliveryPlan').trigger('click');
        $('#txtDateFrom').focus();
        showWarningMessage('Invalied Date From');
        return;
    }

    if ($('#txtDateTo').val().trim().length === 0) {
        $('#tabDeliveryPlan').trigger('click');
        $('#txtDateTo').focus();
        showWarningMessage('Invalied Date To');
        return;
    }

    if ($('#cmbVehicle').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbVehicle').focus();
        showWarningMessage('Invalied Vehicle');
        return;
    }

    /*if ($('#cmbSalesRep').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbSalesRep').focus();
        showWarningMessage('Invalied Sales Rep');
        return;
    }*/

    if ($('#cmbDriver').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbDriver').focus();
        showWarningMessage('Invalied Driver');
        return;
    }

    if ($('#cmbHelper').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbHelper').focus();
        showWarningMessage('Invalied Helper');
        return;
    }

    if ($('#cmbRoute').val() === null) {
        $('#tabDeliveryPlan').trigger('click');
        $('#cmbRoute').focus();
        showWarningMessage('Invalied Route');
        return;
    }

    $.ajax({
        url: '/sd/updateDeliveryPlan/' + DELIVERY_PLAN_ID,
        method: 'POST',
        data: createFormData(),
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            if (response.status) {
                $('#modalDeliveryPlan').modal('hide');
                showSuccessMessage('Delivery Plan has been updated');
                getDeliveryPlans();
            } else {
                showErrorMessage('Something went wrong');
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            //getDeliveryPlans();
        }
    });
}
/** End of Update Delivery Plan and Town list */



/** Save delivery plan invoice */
function saveDeliveryPlanInvoice() {

    var selectedInvoice = getSelectedInvoice();
    if (selectedInvoice.length == 0) {
        showWarningMessage('Please select invoice..');
        return;
    }

    var formData = new FormData();
    formData.append("invoice", JSON.stringify(selectedInvoice));

    $.ajax({
        url: '/sd/saveDeliveryPlanInvoice',
        method: 'post',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);

            if (response.status) {
                $('#modalDeliveryPlanInvoice').modal('hide');
                showSuccessMessage('Invoice saved');
                getDeliveryPlans();
            }


        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}
/** End of Save delivery plan invoice */



/** Add selected routes to route table */
function addRoute(id, route) {

    var routeData = [];
    var table = document.getElementById('routeTable'),
        rows = table.getElementsByTagName('tr'),
        i, j, cells, id;

    for (i = 0, j = rows.length; i < j; ++i) {
        cells = rows[i].getElementsByTagName('td');
        if (!cells.length) {
            continue;
        }



        var route_id = $(cells[0].childNodes[0]).attr('data-id');
        routeData.push(route_id);

    }

    if (routeData.includes(id)) {
        showWarningMessage('Already exist route');
        return;
    }

    var new_data = [{
        "route": '<label data-id = "' + id + '">' + route + '</label',
        "route_id": "",
    }];
    var table = $('#routeTable').DataTable();
    table.rows.add(new_data).draw();

    getTownsFromRoute(id);
}
/** End of Add selected routes to route table */





/** Save delivery plan NonPicking invoice */
function saveDeliveryPlanNonPickingInvoice(external_no) {

    var selectedInvoice = getSelectedNonPickingInvoice();
    if (selectedInvoice.length == 0) {
        showWarningMessage('Please select invoice..');
        return;
    }

    var formData = new FormData();
    formData.append("invoice", JSON.stringify(selectedInvoice));
    formData.append("external_no", external_no);

    $.ajax({
        url: '/sd/saveDeliveryPlanNonPickingInvoice',
        method: 'post',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);

            if (response.status) {
                $('#modalDeliveryPlanPackingList').modal('hide');
                showSuccessMessage('Invoice saved');
            }


        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}
/** End of Save delivery plan NonPicking invoice */

function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}






