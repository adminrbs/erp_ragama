

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
var STATUS = "plan";
$(document).ready(function () {

    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        STATUS = param[0].split('=')[1].split('&')[0];
        if (STATUS == "delivered") {
            getDeliveryPlansDeliverd();
        } else {
            getDeliveryPlansNoneDeliverd();
        }
    }


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
    $('#btnView_non_allocated').on('click',function(){
        showNonAllocatedListModel();
    });


});








/*Non - allocated list model */
function showNonAllocatedListModel(){
    $('#modal_non_allocated_list').modal('show');
} 





