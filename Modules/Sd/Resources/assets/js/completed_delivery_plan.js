

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
                    visible: true

                },
                {
                    width: 40,
                    height: '100%',
                    targets: 6,
                    visible: false

                },
                {
                    width: 40,
                    height: 20,
                    targets: 7,

                },
                {
                    width: 80,
                    targets: [9]
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '2px');
                    }
                },


            ],
            autoWidth: false,
            scrollX: true,
            scrollY: 250,
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
                    "data": "invoice",
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

        // Left and right fixed columns
        invoice_table = $('#invoiceTable_non').DataTable({
            columnDefs: [
                {
                    width: 60,
                    targets: 0
                },
                {
                    width: 80,
                    targets: 1
                },
                {
                    width: 180,
                    targets: 2
                },
                {
                    width: 50,
                    targets: 4
                },
                {
                    width: 50,
                    targets: 5
                },
                {
                    width: 50,
                    targets: 6
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '0px');
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
            "pageLength": 20,
            "order": [],
            "columns": [

                { "data": "date" },
                { "data": "invoice_no" },
                { "data": "customer" },
                { "data": "town" },
                { "data": "amount" },
                { "data": "order_date" },
                { "data": "order_no" },

            ],
        });
          // Adjust columns on window resize
          setTimeout(function () {
            $(window).on('resize', function () {
                invoice_table.columns.adjust();
            });
        }, 100);

        _postpone_table = $('#postpond_table').DataTable({
            columnDefs: [
                {
                    width: 50,
                    targets: 0
                },
                {
                    width: 100,
                    targets: 1
                },
                {
                    width: 200,
                    targets: 2
                },
                {
                    width: 50,
                    targets: 4
                },
                {
                    width: 40,
                    targets: 5
                },
                {
                    width: 180,
                    targets: 6
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
            "pageLength": 20,
            "order": [],
            "columns": [

                { "data": "date" },
                { "data": "invoice_no" },
                { "data": "customer" },
                { "data": "town" },
                { "data": "amount" },
                { "data": "postpone" },
                { "data": "reason" },
            ],

        });

        // Adjust columns on window resize
        setTimeout(function () {
            $(window).on('resize', function () {
                _postpone_table.columns.adjust();
            });
        }, 100);

    




      

    };

    return {
        init: function () {
            _componentDatatableDeliveryPlanFixedColumns();
        },
        refresh: function () {
            if (invoice_table != undefined) {
                invoice_table.columns.adjust();
            }
            if (_postpone_table != undefined) {
                _postpone_table.columns.adjust();
            }
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
var STATUS = "plan";
$(document).ready(function () {

    /* if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        STATUS = param[0].split('=')[1].split('&')[0];
        if (STATUS == "delivered") {
            $('#pageName').text('Delivery History');
            getDeliveryPlansDeliverd();
           
        } else {
            $('#pageName').text('Delivery Plan');
            getDeliveryPlansNoneDeliverd();
            
            
        }
    }
 */
    getVehicleOutDeliveryPlans();
    loadNonAllocatedInvoice_all();
    showPostponeDeliveryAll();
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
        $("#tabAllocate").trigger("click");
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
        $('#tabPackingList').trigger('click');
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

    //view non - allocated model
    $('#btnView_non_allocated').on('click', function () {
        showNonAllocatedListModel();
    });
    //view postpond invoices
    $('#btnView_postpond').on('click', function () {
        showPostpondModel();
    });


});




function newReferanceID(table, doc_number) {

    REFERANCE_ID = newID("/sd/delivery_plan/new_referance_id", table, doc_number);
    $('#txtRefNo').val('New Delivery Plan');

}

function print(id) {
    
   
    const newWindow = window.open("/sd/delivery_report/" + id);
  newWindow.onload = function() {
    newWindow.print();
  }
}

function confirm_finish_plan(id){


    bootbox.confirm({
        title: 'Save confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-warning'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-link'
            }
        },
        callback: function (result) {
            //console.log('Confirmation result:', result);
            if (result) {
                finish_plan(id)
            } else {

            }
        },
        onShow: function () {
            $('#question-icon').addClass('swipe-question');
        },
        onHide: function () {
            $('#question-icon').removeClass('swipe-question');
        }
    });

    $('.bootbox').find('.modal-header').addClass('bg-warning text-white');
    
}


function finish_plan(id){
    $.ajax({
        url: '/sd/finish_plan/'+id,
        method: 'post',
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            if(response.status){
                showSuccessMessage('Record updated');
            }else{
                showWarningMessage('Unable to update');
            }

            getVehicleOutDeliveryPlans();

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
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
        var status_name = "Schedule";
        if (result[i].status == 2) {
            status_name = "Preparig";
        } else if (result[i].status == 3) {
            status_name = "Vehicle Out";
        } else if (result[i].status == 4) {
            status_name = "Finished Deliverymy";
        }
        var menu = '<div class="dropdown position-static" style=" z-index: 1000;">';
        menu += '<a href="#" class="dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-list" aria-hidden="true"></i></a >';
        menu += '<div class="dropdown-menu dropdown-menu-end">';
        if (result[i].status != 4) {
            menu += '<a class="dropdown-item" href="#"  onclick="showInvoice(' + str_delivery_plan_id + ',' + str_route_id + ')">Allocate Invoice</a>';
        }
        //menu += '<a class="dropdown-item" href="/sd/pickinglist/' + delivery_plan_id + '">Picking List</a>';
        menu += '<a class="dropdown-item" href="#" onclick="showPickingListModal(' + str_delivery_plan_id + ',' + str_external_no + ',' + str_route_id + ')">Picking List</a>';
      /*   menu += '<a class="dropdown-item" href="/sd/delivery_report/' + delivery_plan_id + '" >Delivery Report</a>'; */
      menu += '<a class="dropdown-item" href="#" onclick="print('+delivery_plan_id+')">Delivery Report</a>';
        if (result[i].status != 4) {
            menu += '<a class="dropdown-item" href="#" onclick="showPostponeDelivery(' + delivery_plan_id + ')" >Postpone delivery</a>';
        }
        menu += '</div>';



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
            "invoice": result[i].invoice,
            "date_from_to": '<div class="row"><div class="col-md-12">From ' + result[i].date_from + '</div><div class="col-md-12">To&nbsp;&nbsp;&nbsp;&nbsp; ' + result[i].date_to + '</div></div>',
            "status": status_name,
            "action": '&nbsp;&nbsp;<button title="View"  class="btn btn-primary" onclick="confirm_finish_plan(' + str_delivery_plan_id + ')"><i class="fa fa-tasks" aria-hidden="true"></i></i></button>' + '&nbsp;&nbsp;<button title="View"  class="btn btn-success" onclick="view(' + str_delivery_plan_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></i></button>&nbsp;<button title="Delete" class="btn btn-danger" disabled hidden><i class="fa fa-trash" aria-hidden="true"></i></button>',
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
                if (STATUS == "delivered") {
                    getDeliveryPlansDeliverd();
                } else {
                    getDeliveryPlansNoneDeliverd();
                }
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



function getDeliveryPlansDeliverd() {

    $.ajax({
        type: "GET",
        url: '/sd/getDeliveryPlansDeliverd',
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

function getVehicleOutDeliveryPlans() {

    $.ajax({
        type: "GET",
        url: '/sd/getVehicleOutDeliveryPlans',
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



function getDeliveryPlansNoneDeliverd() {

    $.ajax({
        type: "GET",
        url: '/sd/getDeliveryPlansNoneDeliverd',
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
                $('#txtRefNo').val(delivery_plan.external_number);
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
                    var str_id = '"' + routes[i].delivery_plan_route_list_id + '"';
                    var str_delivery_plan_id = '"' + delivery_plan_id + '"';
                    data_routes.push({
                        "route": '<label data-id = "' + routes[i].route_id + '">' + routes[i].route_name + '</label',
                        "route_id": "",
                        "remove": "<button class='btn btn-danger' type='button' onclick='removeRouteFromDeliveryPlan(" + str_id + "," + str_delivery_plan_id + ")'>Remove</button>"
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
                if (STATUS == "delivered") {
                    getDeliveryPlansDeliverd();
                } else {
                    getDeliveryPlansNoneDeliverd();
                }
            } else {
                showErrorMessage('Something went wrong');
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

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
                if (STATUS == "delivered") {
                    getDeliveryPlansDeliverd();
                } else {
                    getDeliveryPlansNoneDeliverd();
                }
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
        "remove": "<button class='btn btn-danger' type='button' onclick='removeRoute(" + DeliveryPlanRouteTable.rowCount() + ")'>Remove</button>"
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

/**------------------------------------------------------------------------------------------------------------------- */
/*Non - allocated list model 15-11-2023 */
function showNonAllocatedListModel() {
    $('#modal_non_allocated_list').modal('show');
   
}


//load non allocated all invoices
function loadNonAllocatedInvoice_all() {
    $.ajax({
        type: "GET",
        url: '/sd/loadNonAllocatedInvoice_all',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var result = response.data;
            var data = [];
            for (let i = 0; i < result.length; i++) {
                var sales_invoice_id = result[i].sales_invoice_Id;
                data.push({
                    "date": result[i].date,
                    "invoice_no": result[i].manual_number,
                    "customer": result[i].customer_name,
                    "town": result[i].townName,
                    "amount": result[i].total_amount,
                    "order_date": result[i].order_date_time,
                    "order_no": result[i].order_no,

                });
            }
            var table = $('#invoiceTable_non').DataTable();
            table.clear();
            table.rows.add(data).draw();
            var len = data.length;
            var round = "<span class='badge bg-yellow text-black translate-middle-middle rounded-pill' style='padding: 8px;'>" + len + "</span>";
            $('#btnView_non_allocated').html('Non-Allocated Invoices&nbsp'+' '+round);



        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}

/*End of Non - allocated list model 15-11-2023 */
/*-------------------------------------------------------------------------------------------------------------/
/** postpond list model 15-11-2023 */

function showPostpondModel() {
    $('#modal_postpond_list').modal('show');
   
}

function showPostponeDeliveryAll() {
   /*  $('#modalDeliveryPostponeList').modal('toggle'); */
    $.ajax({
        type: "GET",
        url: '/sd/showPostponeDeliveryAll',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var result = response.data;
            var data = [];
            for (let i = 0; i < result.length; i++) {
                var sales_invoice_id = result[i].sales_invoice_Id;
                var remark = result[i].remarks;
                if(result[i].remarks == null){
                    remark = '';
                }
                data.push({
                    "date": result[i].date,
                    "invoice_no": result[i].manual_number,
                    "customer": result[i].customer_name,
                    "town": result[i].townName,
                    "amount": result[i].total_amount,
                    "postpone": result[i].postpone_by,
                    "reason":  remark 
                    //"check": '<input id="invoiceCheck' + i + '" data-delivery_plan_id="' + delivery_plan_id + '" data-sales_invoice_id="' + sales_invoice_id + '" name="invoiceCheck" type="checkbox" id="selectAll">',
                });
            }
            var table = $('#postpond_table').DataTable();
            table.clear();
            table.rows.add(data).draw();
            var lenth_ = data.length;
            var round_ = "<span class='badge bg-yellow text-black translate-middle-middle rounded-pill' style='padding: 8px;'>" + lenth_ + "</span>";
            $('#btnView_postpond').html('Postponed Invoices&nbsp'+' '+round_);

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });

}


