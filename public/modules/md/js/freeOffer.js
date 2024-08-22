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


        // table for offers
        var table = $('#offerTable').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 200,
                    targets: [2]
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "offer_id" },
                { "data": "name" },
                { "data": "description" },
                { "data": "start_date" },
                { "data": "end_date" },
                { "data": "apply_to" },
                { "data": "is_active" },
                { "data": "action" },


            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
        table.column(0).visible(false);


        //table for offer data
        var table = $('#offerDataTable').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: '100%',
                    targets: [2]
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "offer_data_id" },
                { "data": "offer_id" },
                { "data": "item_Name" },
                { "data": "offer_type" },
                { "data": "offer_redeem_as" },
                { "data": "is_active" },
                { "data": "action" },


            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
        table.column(0).visible(false);
        table.column(1).visible(false);

        //table thresholds for offer data
        var tableThreshold = $('#ThresholdsTable').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 200,
                    targets: [2]
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "free_offer_thresholds_id" },
                { "data": "offer_data_id" },
                { "data": "quantity" },
                { "data": "free_offer_quantity" },
                { "data": "maximum_quantity" },
                { "data": "free_offer_value" },
                { "data": "maximum_value" },
                { "data": "free_offer_another_item_id" },
                { "data": "Action" }

            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
        tableThreshold.column(0).visible(false);
        tableThreshold.column(1).visible(false);


        //table range for offer data
        var tableRange = $('#rangeTable').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 200,
                    targets: [2]
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "free_offer_range_id" },
                { "data": "offer_data_id" },
                { "data": "from" },
                { "data": "to" },
                { "data": "free_offer_quantity" },
                { "data": "maximum_quantity" },
                { "data": "free_offer_value" },
                { "data": "maximum_value" },
                { "data": "free_offer_another_item_id" },
                { "data": "Action" }

            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
        tableRange.column(0).visible(false);
        tableRange.column(1).visible(false);

        // table for customer offer
        var table = $('#free_offer_customer_table').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '20%',
                    targets: 1
                },
                {
                    width: '80%',
                    targets: 2
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "free_offer_customer_id" },
                { "data": "Select" },
                { "data": "Offer Name" },
                { "data": "Customer Name" },
            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
         table.column(0).visible(false);
         
         // table for location offer
        var table = $('#free_offer_location_table').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '20%',
                    targets: 1
                },
                {
                    width: '80%',
                    targets: 2
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "free_offer_location_id" },
                { "data": "Select" },
                { "data": "Offer Name" },
                { "data": "Location Name" },
            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
         table.column(0).visible(false); 

           // table for customer grade offer
        var table = $('#free_offer_customer_grade_table').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '20%',
                    targets: 1
                },
                {
                    width: '80%',
                    targets: 2
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "free_offer_customer_grade_id" },
                { "data": "Select" },
                { "data": "Offer Name" },
                { "data": "grade" },
            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
         table.column(0).visible(false); 

           // table for customer group offer
        var table = $('#free_offer_customer_group_table').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: '20%',
                    targets: 3
                },
                {
                    width: '20%',
                    targets: 1
                },
                {
                    width: '80%',
                    targets: 2
                },

            ],
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "free_offer_customer_group_id" },
                { "data": "Select" },
                { "data": "Offer Name" },
                { "data": "group" },
            ],
            "stripeClasses": ['odd-row', 'even-row']
        });
         table.column(0).visible(false); 



    };

    // Return objects assigned to module

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();


document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});


var FreeOfferQuantityRangeRequired = false;
var formData = new FormData();
var OfferID;
var offerType;
var offerDataID_for;
var redeemAs;
var requiredInputs = [];
var ApplyTotext_value;
$(document).ready(function () {

    $('#range_colap').hide();
    $('#threshold_colap').hide();
    $('.select2').select2();
    getAllOffers();
    getItems();


    //display save button
    $('#staticBackdrop').on('hidden.bs.modal', function () {
        $('#frmAddOffer')[0].reset();
        $('#btnSave').show();
    });

    //display save button in offer data
    $('#freeOfferDataModel').on('hidden.bs.modal', function () {
        $('#frmOfferData')[0].reset();
        $('#saveBTNofferData').show();
    });

    //display threshold add button
    $('#Thresholdsmodal').on('hidden.bs.modal', function () {
        $('#frmthresholdData')[0].reset();
        $('#btnThreshold').show();
    });

    //display btn save button
    $('#rangemodal').on('hidden.bs.modal', function () {
        $('#frmRange')[0].reset();
        $('#btnSaveRange').show();
    })


    //tabs
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    //save free offer when button is pressed
    $('#btnSave').on('click', function (e) {
        var btnTExt = $(this).text();
        var id = $('#hiddnLBLforID').val();

        if (btnTExt == "Add") {
            addFreeOffer();
            getAllOffers();
        } else {
            updateOffer(id);
        }

    })

    //save free offer data when button is pressed
    $('#saveBTNofferData').on('click', function (e) {
        var btnTExt = $(this).text();
        if (btnTExt == 'Add') {
            if (OfferID != null) {
                addOfferData(OfferID);
            } else {
                showErrorMessage("Please select a offer");
            }

        } else {
            var updateID = $('#lblofferDatahiddenForID').val();
            updateOfferData(updateID);

        }

    })

    //save threshold data when button is pressed

    $('#btnThreshold').on('click', function (e) {
        var btnTExt = $(this).text();
        if (btnTExt == 'Add') {
            addThreshold(offerDataID_for);
        } else {
            var updateID = $('#lblfreeOfferThresholds').val();
            updateThresholdData(updateID);
        }
    });

    $('#btnSaveRange').on('click', function (e) {
        var btnText = $(this).text();
        if (btnText == 'Add') {
            addRange(offerDataID_for);
        } else {
            var updateID = $('#lblHiddenIDRange').val();
            updateRangeData(updateID)
        }
    })



    //btn save text to 'Add'
    $('#btnModaladd').on('click', function (e) {
        $('#btnSave').text('Add');
    })

    //btn saveBTNofferData text to 'Add'
    $('#btnfreeOfferDataModel').on('click', function (e) {
        $('#saveBTNofferData').text('Add');
    })

    //btn threshold 'save' text to Add
    $('#btnThresholdModal').on('click', function (e) {
        $('#btnThreshold').text('Add');
        IS_REQUIRED.reset();
    })


    //ntm range save text to Add
    $('#btnRnageModalShow').on('click', function (e) {
        $('#btnSaveRange').text('Add');
        IS_REQUIRED.reset();
        /* $('#txtFreeOfferQuantityRange').prop('required',true); */
    })



    //tr on click

    $('#offerTable').on('click', 'tr', function (e) {
        $('#offerTable tr').removeClass('selected');
        $(this).addClass('selected');

        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children();
        var title = hiddenValue.text();

        var ApplyTotext = $(this).find('td:eq(4)');
        ApplyTotext_value = ApplyTotext.text();
        $('#lblOfferApplyModel').text(ApplyTotext_value);// setting H1 tag title

        childElements.each(function () {
            OfferID = $(this).attr('data-id');
            $('#staticBackdropLabelSecodName').text(title); //setting h1 tag title
            getOfferData(OfferID);

        });


        //hiding and show apply to collaps
        if (ApplyTotext_value == "Locations") {
            $('#li_Applyto').show(); // apply to tab
            $('#ApplyTo').show();
            $('#free_offer_location_collap_card').show();
            $('#free_offer_customer_collap_card').hide();
            $('#free_offer_customer_grade_collap_card').hide();
            $('#free_offer_customer_group_collap_card').hide();
            getAllOfferLocationData(OfferID);

        } else if (ApplyTotext_value == "Customer") {
            $('#li_Applyto').show(); // apply to tab
            $('#ApplyTo').show();
            $('#free_offer_location_collap_card').hide();
            $('#free_offer_customer_collap_card').show();
            $('#free_offer_customer_grade_collap_card').hide();
            $('#free_offer_customer_group_collap_card').hide();
            getAllOfferCustomerSData(OfferID);

        } else if (ApplyTotext_value == "Customer grade") {
            $('#li_Applyto').show(); // apply to tab
            $('#ApplyTo').show();
            $('#free_offer_location_collap_card').hide();
            $('#free_offer_customer_collap_card').hide();
            $('#free_offer_customer_grade_collap_card').show();
            $('#free_offer_customer_group_collap_card').hide();
            getAllCustomerGradeOfferData(OfferID);

        } else if (ApplyTotext_value == "Customer group") {
            $('#li_Applyto').show(); // apply to tab
            $('#ApplyTo').show();
            $('#free_offer_location_collap_card').hide();
            $('#free_offer_customer_collap_card').hide();
            $('#free_offer_customer_grade_collap_card').hide();
            $('#free_offer_customer_group_collap_card').show();
            getAllCustomerGroupOfferData(OfferID);
        }else{
            $('#ApplyTo').hide();
            $('#li_Applyto').hide(); // hiding apply to tab
        }


    });



    //getting data id and offer type
    $('#offerDataTable').on('click', 'tr', function (e) {
        $('#offerDataTable tr').removeClass('selected');

        // Add the selected class to the clicked row
        $(this).addClass('selected');
        var hiddenValue = $(this).find('td:eq(1)');
        var childElements = hiddenValue.children();
        var hiddenValueThreshold = $(this).find('td:eq(0)');
        var childElementsforRangeAndthreshold = hiddenValueThreshold.children();


        //get redeem as
        var GetredeemAs = $(this).find('td:eq(2)');
        redeemAs = GetredeemAs.text();
        // alert(redeemAs);


        childElements.each(function () {

            offerType = $(this).attr('data-id');

        });
        childElementsforRangeAndthreshold.each(function () {

            offerDataID_for = $(this).attr('data-id');

        });



        // hiding and showing collaps accoring to the type
        if (offerType == "Free Offering Thresholds") {
            if (redeemAs == "Free offer by given value") {

                requiredInputs = ["txtFreeofferValue"];

            } else if (redeemAs == "Free offer by quantity") {

                requiredInputs = ["txtFreeOfferQuantity"];

            } else if (redeemAs == "Free offer by another item") {

                requiredInputs = ["cmbFreeofferAnotherItem"];

            } else if (redeemAs = "Free offer by price") {

                requiredInputs = ["txtFreeOfferQuantity"];
            }

            $('#range_colap').hide();
            $('#threshold_colap').show();
            getallthresholds(offerDataID_for);
        } else {
            if (redeemAs == "Free offer by given value") {

                requiredInputs = ["txtFreeOfferValueRange"];

            } else if (redeemAs == "Free offer by quantity") {

                requiredInputs = ["txtFreeOfferQuantityRange"];

            } else if (redeemAs == "Free offer by another item") {

                requiredInputs = ["cmbFreeOfferAnotherItemIDRange"];

            } else if (redeemAs = "Free offer by price") {

                requiredInputs = ["txtFreeOfferQuantityRange"];
            }

            $('#threshold_colap').hide();
            $('#range_colap').show();
            GetRangeData(offerDataID_for);

        }
    });


    //setting hidden label value to offerID
    $('#btnfreeOfferDataModel').on('click', function (e) {
        $('#lblofferDatahidden').val(OfferID)

    });

});




//offer edit function
function Offeredit(id) {
    $('#staticBackdrop').modal('show');
    $('#hiddnLBLforID').val(id);
    $('#btnSave').text('Update');
    getEachOffer(id);

}

// offer view function
function Offerview(id) {
    $('#staticBackdrop').modal('show');
    $('#btnSave').hide();
    getEachOffer(id);

}

//edit offer data
function OfferDataedit(id) {
    $('#lblofferDatahiddenForID').val(id);
    $('#freeOfferDataModel').modal('show');
    $('#lblofferDatahidden').val(id);
    $('#saveBTNofferData').text('Update');
    getEachOfferData(id);
}

// offerdata view function
function Offerdataview(id) {
    $('#freeOfferDataModel').modal('show');
    $('#saveBTNofferData').hide();
    getEachOffer(id);

}

//edit threshold
function thresholdedit(id) {
    $('#Thresholdsmodal').modal('show');
    $('#lblfreeOfferThresholds').val(id);
    $('#btnThreshold').text('Update')
    geteachThreshold(id);

}

//view threshold
function thresholdview(id) {
    $('#Thresholdsmodal').modal('show');
    $('#btnThreshold').hide();
    geteachThreshold(id);
}

//edit range
function rangeEdit(id) {
    $('#rangemodal').modal('show');
    $('#lblHiddenIDRange').val(id);
    $('#btnSaveRange').text('Update');
    getEachRangeData(id);
}

//view range
function rangeView(id) {
    $('#rangemodal').modal('show');
    $('#btnSaveRange').hide();
    getEachRangeData(id);
}


//delete function (bootbox)
function offer_delete(id) {
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
                deleteOffer(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
}

//delete offer data bootbox message
function offerDatadelete(id) {
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
                deleteOfferData(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');


}


//dele threshold
function thresholddelete(id) {
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
                thresholdDelete(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function rangeDelete(id) {
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
                deleteRange(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}


//add free offer
function addFreeOffer() {
    var isActive = $('#chkActivate').is(":checked") ? 1 : 0;
    var start_date = $('#dtStartDate').val();
    var end_date = $('#dtEndDate').val();
    if (start_date < end_date) {
        formData.append('txtOfferName', $('#txtOfferName').val());
        formData.append('txtDescription', $('#txtDescription').val());
        formData.append('dtStartDate', $('#dtStartDate').val());
        formData.append('dtEndDate', $('#dtEndDate').val());
        formData.append('cmbApplyTo', $('#cmbApplyTo').val());
        formData.append('isActive', isActive);
        $.ajax({

            url: '/md/addFreeOffer',
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
                    $('#frmAddOffer')[0].reset();


                } else {
                    showErrorMessage("Something went wrong");

                }

            }, error: function (data) {

            }, complete: function () {
                $('#btnSave').prop('disabled', false);
            }
        });

    } else {
        showErrorMessage("Please check the selected dates");
    }

}


//get all offers to table
function getAllOffers() {

    $.ajax({
        type: "GET",
        url: "/md/getAllOffers",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            console.log(dt);
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var label =  '<label class="badge bg-danger">'+dt[i].is_active+'</label>';
                if(dt[i].is_active == "Yes"){
                    label =  '<label class="badge bg-success">'+dt[i].is_active+'</label>';
                }
                data.push({
                    "offer_id": dt[i].offer_id,
                    "name": '<div data-id = "' + dt[i].offer_id + '">' + dt[i].name + '</div>',
                    "description": dt[i].description,
                    "start_date": dt[i].start_date,
                    "end_date": dt[i].end_date,
                    "apply_to": dt[i].apply_to,
                    "is_active": label,
                    "action": '<button class="btn btn-primary" onclick="Offeredit(' + dt[i].offer_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success" onclick="Offerview(' + dt[i].offer_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger" onclick="offer_delete(' + dt[i].offer_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',

                });
            }
            var table = $('#offerTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

//delete offer
function deleteOffer(id) {
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteOffer/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response
            if (status) {
                showSuccessMessage("Successfully deleted");
            } else {
                showErrorMessage("Something went wrong")
            }
            getAllOffers();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

//delete offer data
function deleteOfferData(id) {
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteOfferData/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response
            if (status) {
                showSuccessMessage("Successfully deleted");
            } else {
                showErrorMessage("Something went wrong")
            }

            getOfferData(OfferID);

        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });


}


//get each offer data to boostrap modal
function getEachOffer(id) {
    $.ajax({
        url: '/md/getEachOfferData/' + id,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (searchedOffer) {
            var res = searchedOffer.data;
            $('#txtOfferName').val(res.name);
            $('#txtDescription').val(res.description);
            $('#dtStartDate').val(res.start_date);
            $('#dtEndDate').val(res.end_date);
            $('#cmbApplyTo').val(res.apply_to);
            $('#chkActivate').prop('checked', res.is_active == 1);

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });

}


//update offer
function updateOffer(id) {
    var isActive = $('#chkActivate').is(":checked") ? 1 : 0;
    var start_date = $('#dtStartDate').val();
    var end_date = $('#dtEndDate').val();
    if (start_date < end_date) {
        formData.append('txtOfferName', $('#txtOfferName').val());
        formData.append('txtDescription', $('#txtDescription').val());
        formData.append('dtStartDate', $('#dtStartDate').val());
        formData.append('dtEndDate', $('#dtEndDate').val());
        formData.append('cmbApplyTo', $('#cmbApplyTo').val());
        formData.append('isActive', isActive);
        $.ajax({
            url: '/md/updateOffer/' + id,
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
                    $('#frmAddOffer')[0].reset();
                    $('#staticBackdrop').modal('hide');
                    getAllOffers();


                } else {
                    showErrorMessage("Something went wrong");

                }


            }, error: function (data) {

            }, complete: function () {
                $('#btnSave').prop('disabled', false);
            }
        });

    } else {
        showErrorMessage("Please check the selected dates");
    }


}

//get each offer data tp update
function getEachOfferData(id) {
    $.ajax({
        url: '/md/getEachOfferDataDetails/' + id,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (searchedOfferData) {
            var res = searchedOfferData.data;
            console.log(res);
            $('#cmbofferType').val(res.offer_type);
            $('#cmbItem').val(res.item_id);
            $('#cmbRedeemas').val(res.offer_redeem_as);
            $('#chkActivate_offerData').prop('checked', res.is_active == 1);

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });

}

//update offer data
function updateOfferData(id) {

    var chkActivate_offerData = $('#chkActivate_offerData').is(":checked") ? 1 : 0;
    formData.append('cmbofferType', $('#cmbofferType').val());
    formData.append('cmbItem', $('#cmbItem').val());
    formData.append('cmbRedeemas', $('#cmbRedeemas').val());
    formData.append('chkActivate_offerData', chkActivate_offerData);


    $.ajax({
        url: '/md/updateOfferData/' + id,
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
                $('#frmOfferData')[0].reset();
                $('#freeOfferDataModel').modal('hide');


            } else {
                showErrorMessage("Something went wrong");

            }
            getOfferData(OfferID);

        }, error: function (data) {

        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }

    })



}

//getting items from fb to cmb
function getItems() {
    $.ajax({
        url: '/md/getIemstocmb',
        method: 'GET',
        async: false,
        success: function (data) {
            var dt = data.data;
            $.each(dt, function (index, value) {
                $('#cmbItem').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>');
                $('#cmbFreeofferAnotherItem').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>');
                $('#cmbFreeOfferAnotherItemIDRange').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>');

            })

        },
    })
}


//get offer datat to data table
function getOfferData(id) {
    $.ajax({
        type: "GET",
        url: "/md/getAllofferData/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var label =  '<label class="badge bg-danger">'+dt[i].is_active+'</label>';
                if(dt[i].is_active == "Yes"){
                    label =  '<label class="badge bg-success">'+dt[i].is_active+'</label>';
                }
                data.push({
                    "offer_data_id": dt[i].offer_data_id,
                    "offer_id": dt[i].offer_id,
                    "item_Name": '<div data-id = "' + dt[i].offer_data_id + '">' + dt[i].item_Name + '</div>',
                    "offer_type": '<div data-id = "' + dt[i].offer_type + '">' + dt[i].offer_type + '</div>',
                    "offer_redeem_as": dt[i].offer_redeem_as,
                    "is_active": label,
                    "action": '<button class="btn btn-primary" onclick="OfferDataedit(' + dt[i].offer_data_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success" onclick="Offerdataview(' + dt[i].offer_data_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger" onclick="offerDatadelete(' + dt[i].offer_data_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',

                });


            }
            var table = $('#offerDataTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

//get all thresholds
function getallthresholds(id) {
    $.ajax({
        type: "GET",
        url: "/md/getallthresholds/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            //  console.log(dt)
            var data = [];
            for (var i = 0; i < dt.length; i++) {

                data.push({
                    "free_offer_thresholds_id": dt[i].free_offer_thresholds_id,
                    "offer_data_id": dt[i].offer_data_id,
                    "quantity": dt[i].quantity,
                    "free_offer_quantity": dt[i].free_offer_quantity,
                    "maximum_quantity": dt[i].maximum_quantity,
                    "free_offer_value": dt[i].free_offer_value,
                    "maximum_value": dt[i].maximum_value,
                    "free_offer_another_item_id": dt[i].free_offer_another_item_id,
                    "Action": '<button class="btn btn-primary" onclick="thresholdedit(' + dt[i].free_offer_thresholds_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success" onclick="thresholdview(' + dt[i].free_offer_thresholds_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger" onclick="thresholddelete(' + dt[i].free_offer_thresholds_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                });


            }
            var table = $('#ThresholdsTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

//add offer data with offer id
function addOfferData(id) {

    var chkActivate_offerData = $('#chkActivate_offerData').is(":checked") ? 1 : 0;
    formData.append('cmbofferType', $('#cmbofferType').val());
    formData.append('cmbItem', $('#cmbItem').val());
    formData.append('cmbRedeemas', $('#cmbRedeemas').val());
    formData.append('chkActivate_offerData', chkActivate_offerData);

    $.ajax({
        url: '/md/addOfferData/' + id,
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
                $('#frmOfferData')[0].reset();
                $('#freeOfferDataModel').modal('hide');


            } else {
                showErrorMessage("Something went wrong");

            }
            getOfferData(OfferID);

        }, error: function (data) {

        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }

    })

}


// add thresholds with offer type
function addThreshold(id) {
    if (IS_REQUIRED.checkRequired(requiredInputs)) {
        return;
    }

    formData.append('txtQuantity', $('#txtQuantity').val());
    formData.append('txtFreeOfferQuantity', $('#txtFreeOfferQuantity').val());
    formData.append('txtMaximumQuantity', $('#txtMaximumQuantity').val());
    formData.append('txtFreeofferValue', $('#txtFreeofferValue').val());
    formData.append('txtMaximumValue', $('#txtMaximumValue').val());
    formData.append('cmbFreeofferAnotherItem', $('#cmbFreeofferAnotherItem').val());


    $.ajax({
        url: '/md/addThreshold/' + id,
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

                $('#frmthresholdData')[0].reset();
                $('#Thresholdsmodal').modal('hide');
                getallthresholds(offerDataID_for);


            } else {
                showErrorMessage("Something went wrong");

            }


        }, error: function (data) {

        }, complete: function () {
            // $('#btnSave').prop('disabled', false);
        }

    })

}


// get threshold details to update 
function geteachThreshold(id) {
    $.ajax({
        url: '/md/geteachThreshold/' + id,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (searchedThresholdData) {
            var res = searchedThresholdData.data;
            console.log(res);
            $('#txtQuantity').val(res.quantity);
            $('#txtFreeOfferQuantity').val(res.free_offer_quantity);
            $('#txtMaximumQuantity').val(res.maximum_quantity);
            $('#txtFreeofferValue').val(res.free_offer_value);
            $('#txtMaximumValue').val(res.maximum_value);
            $('#cmbFreeofferAnotherItem').val(res.free_offer_another_item_id);

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });

}

//update threshold data
function updateThresholdData(id) {
    if (IS_REQUIRED.checkRequired(requiredInputs)) {
        return;
    }
    formData.append('txtQuantity', $('#txtQuantity').val());
    formData.append('txtFreeOfferQuantity', $('#txtFreeOfferQuantity').val());
    formData.append('txtMaximumQuantity', $('#txtMaximumQuantity').val());
    formData.append('txtFreeofferValue', $('#txtFreeofferValue').val());
    formData.append('txtMaximumValue', $('#txtMaximumValue').val());
    formData.append('cmbFreeofferAnotherItem', $('#cmbFreeofferAnotherItem').val());


    $.ajax({
        url: '/md/updateThresholdData/' + id,
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
                $('#frmthresholdData')[0].reset();
                getallthresholds(offerDataID_for);
                $('#Thresholdsmodal').modal('hide');
            


            } else {
                showErrorMessage("Something went wrong");

            }

        }, error: function (data) {

        }, complete: function () {
            // $('#btnSave').prop('disabled', false);
        }

    })

}

function thresholdDelete(id) {
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteThresholdData/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response
            if (status) {
                showSuccessMessage("Successfully deleted");
                getallthresholds(offerDataID_for);
            } else {
                showErrorMessage("Something went wrong")
            }
            /* getOfferData(OfferID); */
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })
}


//add range function
function addRange(id) {
    if (IS_REQUIRED.checkRequired(requiredInputs)) {
        return;
    }

    formData.append('txtMaximumquantityRange', $('#txtMaximumquantityRange').val());
    formData.append('txtFreeOfferValueRange', $('#txtFreeOfferValueRange').val());
    formData.append('txtMaximumValueRange', $('#txtMaximumValueRange').val());
    formData.append('txtFreeOfferQuantityRange', $('#txtFreeOfferQuantityRange').val());
    formData.append('cmbFreeOfferAnotherItemIDRange', $('#cmbFreeOfferAnotherItemIDRange').val());
    formData.append('dtFromRange', $('#dtFromRange').val());
    formData.append('dtToRange', $('#dtToRange').val());

    if (FreeOfferQuantityRangeRequired && $('#txtFreeOfferQuantityRange').val().trim() == '') {
        $('#txtFreeOfferQuantityRange').css('border', '1px solid red');
        return;
    }

    $.ajax({
        url: '/md/addRange/' + id,
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
                $('#frmRange')[0].reset();
                GetRangeData(offerDataID_for);
                $('#rangemodal').modal('hide');

                


            } else {
                showErrorMessage("Something went wrong");

            }

        }, error: function (data) {

        }, complete: function () {
            // $('#btnSave').prop('disabled', false);
        }

    })

}

//get range data to data table
function GetRangeData(id) {
    $.ajax({
        type: "GET",
        url: "/md/GetRangeData/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            //  console.log(dt)
            var data = [];
            for (var i = 0; i < dt.length; i++) {

                data.push({
                    "free_offer_range_id": dt[i].free_offer_range_id,
                    "offer_data_id": dt[i].offer_data_id,
                    "from": dt[i].from,
                    "to": dt[i].to,
                    "free_offer_quantity": dt[i].free_offer_quantity,
                    "maximum_quantity": dt[i].maximum_quantity,
                    "free_offer_value": dt[i].free_offer_value,
                    "maximum_value": dt[i].maximum_value,
                    "free_offer_another_item_id": dt[i].free_offer_another_item_id,
                    "Action": '<button class="btn btn-primary" onclick="rangeEdit(' + dt[i].free_offer_range_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success" onclick="rangeView(' + dt[i].free_offer_range_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger" onclick="rangeDelete(' + dt[i].free_offer_range_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                });


            }
            var table = $('#rangeTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

//get each range datat to update
function getEachRangeData(id) {
    $.ajax({
        url: '/md/getEachRangeData/' + id,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (searchedRangeData) {
            var res = searchedRangeData.data;
            console.log(res);
            $('#txtMaximumquantityRange').val(res.maximum_quantity);
            $('#txtFreeOfferValueRange').val(res.free_offer_value);
            $('#txtMaximumValueRange').val(res.maximum_value);
            $('#txtFreeOfferQuantityRange').val(res.free_offer_quantity);
            $('#cmbFreeOfferAnotherItemIDRange').val(res.free_offer_another_item_id);
            $('#dtFromRange').val(res.from);
            $('#dtToRange').val(res.to);


        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });
}

//update range
function updateRangeData(id) {
    if (IS_REQUIRED.checkRequired(requiredInputs)) {
        return;
    }
    formData.append('txtMaximumquantityRange', $('#txtMaximumquantityRange').val());
    formData.append('txtFreeOfferValueRange', $('#txtFreeOfferValueRange').val());
    formData.append('txtMaximumValueRange', $('#txtMaximumValueRange').val());
    formData.append('txtFreeOfferQuantityRange', $('#txtFreeOfferQuantityRange').val());
    formData.append('cmbFreeOfferAnotherItemIDRange', $('#cmbFreeOfferAnotherItemIDRange').val());
    formData.append('dtFromRange', $('#dtFromRange').val());
    formData.append('dtToRange', $('#dtToRange').val());


    $.ajax({
        url: '/md/updateRangeData/' + id,
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
                $('#frmRange')[0].reset();
                GetRangeData(offerDataID_for);
                $('#rangemodal').modal('hide');

            } else {
                showErrorMessage("Something went wrong");

            }

        }, error: function (data) {

        }, complete: function () {
            // $('#btnSave').prop('disabled', false);
        }

    })

}

function deleteRange(id) {
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteRange/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response
            if (status) {
                showSuccessMessage("Successfully deleted");
                GetRangeData(offerDataID_for);
            } else {
                showErrorMessage("Something went wrong")
            }

        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })

}


function offerDataTableRefresh() {
    var table = $('#offerDataTable').DataTable();
    table.columns.adjust().draw();
}

function ThresholdsTableRefresh() {
    var table = $('#ThresholdsTable').DataTable();
    table.columns.adjust().draw();
}

function rangeDataTable() {
    var table = $('#rangeTable').DataTable();
    table.columns.adjust().draw();
}

function free_offer_customer_tableDataTable_refresh() {
    var table = $('#free_offer_customer_table').DataTable();
    table.columns.adjust().draw();
}

function free_offer_location_table_refresh() {
    var table = $('#free_offer_location_table').DataTable();
    table.columns.adjust().draw();
}

function free_offer_customer_grade_table_refresh() {
    var table = $('#free_offer_customer_grade_table').DataTable();
    table.columns.adjust().draw();
}

function free_offer_customer_group_table_refresh() {
    var table = $('#free_offer_customer_group_table').DataTable();
    table.columns.adjust().draw();
}




