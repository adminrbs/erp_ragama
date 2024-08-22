const DatatableFixedColumnsx = function () {

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
        var table = $('.datatable-fixed-both-offerDataTable').DataTable({
          
            
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 100,
                    targets: 0
                },
                {
                    width: 400,
                    targets: 1
                },
                
                {
                    width: 500,
                    targets: 1
                },
                {
                    width: 400,
                    targets: [2]
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "item_code" },
                { "data": "item" },
                { "data": "offer_type" },
                { "data": "offer_redeem_as" },
                { "data": "activate" },
                { "data": "action" }
       
            ],
            "stripeClasses": ['odd-row', 'even-row'],
              

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
    DatatableFixedColumnsx.init();
});

//.....................free ofer for give a quntity............

const DatatableFixedColumnfq = function () {

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
        var table = $('#AddThresholdsTable').DataTable({
          
            
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 100,
                    targets: 0
                },
                {
                    width: 400,
                    targets: 1
                },
                
                {
                    width: 500,
                    targets: 1
                },
                {
                    width: 400,
                    targets: [2]
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "qty" },
                { "data": "freeoferqty" },
                { "data": "maxqty" },
                { "data": "totaloferqty" },
               
       
            ],
            "stripeClasses": ['odd-row', 'even-row'],
              

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
    DatatableFixedColumnfq.init();
});


// End



var ItemList;
var FreeOfferQuantityRangeRequired = false;
var formData = new FormData();
var OfferID;
var offerType;
var offerDataID_for;
var redeemAs;
var requiredInputs = [];
var ApplyTotext_value;
var offerType_text;
var checkBox_offerData_id_array = [];
var Offer_redeem_as = [];
var checkBox_offer_redeem = [];
var checkboxValuesArray = [];
var tableDataThreshold;
var checkboxSelectAllArray = [];
var tableData;
var checkBoxObj = undefined;
var action;
var offerID;
var column_index = [];

$(document).ready(function () {
    getServerTime();
    /* var columnIndexToHide = 2;
    $('#AddThresholdsTable th:eq(' + columnIndexToHide + '), #AddThresholdsTable td:nth-child(' + (columnIndexToHide + 1) + ')').hide(); */

    $('#range_colap').hide();
    $('#threshold_colap').hide();
    $('#cmbofferType').prop('disabled', true);
    $('#cmbRedeemas').prop('disabled',true);

    ItemList = loadItems();
    //initilaizing select2 in model
    $('.card').each(function () {
        $(this).find('.select2').select2({
            dropdownParent: $(this)
        });
    });

    getItemsForSupGRP(1);
    $('#cmbSupplyGroup').on('change', function () {
        var id = $(this).val();
        console.log(id);
        var text = $(this).find(":selected");
        var text_ = text.text();
        getItemsForSupGRP(id);
        if (text_ == "Not Applicable") {
            $('#cmbItem').prop('disabled', false);
            $('#item_div').show();
        } else {
            getItemsForSupGRP(id);
            $('#cmbItem').prop('disabled', true);
            $('#item_div').hide();       
        }

    });



    getSupllyGroup();
    getItems();

    /**checking same offer type records */



    $(document).on('change', '.checkbox-item', function () {

        checkBoxObj = $(this);
        selectRecord($(this));
        /**gettig offer data ID */
        checkThreshold();
       


    });

    $('#selectAll').prop('checked', false);

    /** select all (checkboxes) */


    $('#selectAll').on('change', function () {

        selectAll($(this));
    });





   


    $('#AddThresholdsTable').on('input', 'input[type="text"]', function () {
        var cellIndex = $(this).closest('td').index();
        if (cellIndex != 14) {
            this.value = this.value.replace(/[^0-9.]/g, ''); // allow numbers only
            var dotCount = (this.value.match(/\./g) || []).length;
            if (dotCount > 1) {
                this.value = this.value.replace(/\.+$/, '');
            }
        } else {
            this.value = this.value.replace(/[^0-9.-]/g, ''); // Allow numbers, dots, and minus sign
            var dotCount = (this.value.match(/\./g) || []).length;
            var minusCount = (this.value.match(/-/g) || []).length;
            if (dotCount > 1 || minusCount > 2) {
                this.value = this.value.replace(/\.+$/, '');
                this.value = this.value.replace(/-+$/, '');
            }
        }


    });


    /** End of threshold */



    //display btn save button
    $('#rangemodal').on('hidden.bs.modal', function () {
        $('#frmRange')[0].reset();
        $('#btnSaveRange').show();
    })





    //save free offer when button is pressed
    $('#btnSave').on('click', function (e) {
        var btnTExt = $(this).text();
        var id = $('#hiddnLBLforID').val();
        console.log(id);

        if (btnTExt == "Add") {
            addFreeOffer();

            //  getAllOffers();
        } else {
            updateOffer(offerID);
        }

    })

    //save free offer data when button is pressed
    $('#saveBTNofferData').on('click', function (e) {
        $('#offerTable tbody tr:first').click();
        /*  $('#offerTable tbody tr:first').addClass('selected'); */

        // Add the selected class to the clicked row
        /*  $(this).addClass('selected'); */
        var supply_grp_id = $('#cmbSupplyGroup').val();
        var btnTExt = $(this).text();
        if (btnTExt == 'Add') {
            if (OfferID != null) {
                if (supply_grp_id != 1) {

                    addOfferDataSupplyGroup(OfferID)
                } else {
                    addOfferData(OfferID);
                }

            } else {
                showWarningMessage("Please select a offer");
            }

        } else {
            var updateID = $('#lblofferDatahiddenForID').val();
            updateOfferData(updateID);

        }

    })

    //save threshold data when button is pressed

    $('#btnThreshold').on('click', function (e) {
        var arr = tableDataThreshold.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            /* for(var j = 0; j < collection.length; j++){
                if(collection[0][7] === arr[i][0].val()){
                    showWarningMessage('Duplicated');
                    return;
                }else{
                    alert(collection[0][7]);
                    alert(arr[i][0].val());
                }
            } */
            collection.push(JSON.stringify({
                "qty": parseFloat(arr[i][0].val().replace(/,/g, '')),
                "foc": parseFloat(arr[i][1].val().replace(/,/g, '')),
                "mxQty": parseFloat(arr[i][3].val().replace(/,/g, '')),
                "fov": parseFloat(arr[i][2].val().replace(/,/g, '')),
                "mxVlv": parseFloat(arr[i][4].val().replace(/,/g, '')),
                "toq": parseFloat(arr[i][5].val().replace(/,/g, '')),
                "tov": parseFloat(arr[i][6].val().replace(/,/g, '')),
                "free_offer_another_item_id": arr[i][7].attr('data-id')
            }));


        }
        if(action == 'edit'){
           
            update_thresholdTable(collection)
        }else{
            addThreshold(collection);
        }

        $('#threshold_colap').hide();
        

    });

    $('#btnSaveRange').on('click', function (e) {
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
           

            collection.push(JSON.stringify({
                "from": parseFloat(arr[i][0].val().replace(/,/g, '')),
                "to": parseFloat(arr[i][1].val().replace(/,/g, '')),
                "foc": parseFloat(arr[i][2].val().replace(/,/g, '')),
                "fov": parseFloat(arr[i][3].val().replace(/,/g, '')),
                "mxQty": parseFloat(arr[i][4].val().replace(/,/g, '')),
                "mxVlv": parseFloat(arr[i][5].val().replace(/,/g, '')),
                "toq": parseFloat(arr[i][6].val().replace(/,/g, '')),
                "tov": parseFloat(arr[i][7].val().replace(/,/g, '')),
                "free_offer_another_item_id": arr[i][8].attr('data-id')
            }));


        }


        addRange(collection);

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

    })



    //tr on click

    $('#offerTable').on('click', 'tr', function (e) {
        $('#offerTable tr').removeClass('selected');
        $(this).addClass('selected');

        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children();
        var title = hiddenValue.text();

        var ApplyTotext = $(this).find('td:eq(4)');
        ApplyTotext_value = ApplyTotext.text();// getting apply to type
        $('#lblOfferApplyModel').text(ApplyTotext_value);// setting H1 tag title

        childElements.each(function () {
            OfferID = $(this).attr('data-id');
            $('#staticBackdropLabelSecodName').text(title); //setting h1 tag title
            $('#settingLbl').text("Add Items for" + " " + title);
            $('#lblApplyTo').text("Apply to" + " " + title)  //setting h1 tag title
            $('#lblOfferApplyModel').text(ApplyTotext_value + " - " + title)
            // getOfferData(OfferID);

        });



    });

    //delete offer data with check boxes
    $('#deleteBTNofferData').on('click', function () {

        offerDatadelete();
    })



    //getting data id and offer type
    $('#offerDataTable').on('click', 'tr', function (e) {
        $('#offerDataTable tr').removeClass('selected');

        // Add the selected class to the clicked row
        $(this).addClass('selected');
       
        var hiddenValue = $(this).find('td:eq(2)');
        var childElements = hiddenValue.children();
        var hiddenValueThreshold = $(this).find('td:eq(0)');
        var childElementsforRangeAndthreshold = hiddenValueThreshold.children();


        //get redeem as
        var GetredeemAs = $(this).find('td:eq(3)');

        redeemAs = GetredeemAs.text();
      
        offerType_text = hiddenValue.text();

        childElementsforRangeAndthreshold.each(function () {

            offerDataID_for = $(this).attr('data-id');


        });

        // hiding and showing collaps accoring to the type
        if (offerType_text == "Free offer for given a quantity") {

           // $('#range_colap').hide();
           // $('#threshold_colap').show();
            /*   getallthresholds(offerDataID_for);  */
        } else {


          //  $('#threshold_colap').hide();
           // $('#range_colap').show();
            /* GetRangeData(offerDataID_for); */

        }
    });


    //setting hidden label value to offerID
    $('#btnfreeOfferDataModel').on('click', function (e) {
        $('#lblofferDatahidden').val(OfferID)

    });


    //edit
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        offerID = param[0].split('=')[1].split('&')[0];
        var offerDataID = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        
        $("#offerTable > tbody").addClass("selected");
 
        if (action == 'edit') {
            $('#btnSave').text('Update');
            $('#cmbSupplyGroup').prop('disabled',true);
        } else if (action == 'view') {
             $('#cmbSupplyGroup').prop('disabled',true);
            $('#btnSave').hide();
            $('#saveBTNofferData').hide();
            $('#btnThreshold').hide();
            $('#deleteBTNofferData').hide();

        }

        getallOfferData_updateView(offerID);
        getEachOffer(offerID);
        getAddedOffers(offerID);
        getEachOfferData(offerDataID);
        
       

    }


});


function loadItems() {
    var list = [];
    $.ajax({
        url: '/sd/loadItems',
        type: 'get',
        async: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;
                /* DataChooser.setDataSourse(itemData); */
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return list;
}

function transactionTableKeyEnterEvent(event, id) {

    if (id == 'AddThresholdsTable') {



        tableDataThreshold.addRow();
        if (checkBoxObj == undefined) {
            selectAll($('#selectAll'));
        }else{
           // selectRecord($(checkBoxObj));
           hideColumnsOnThreshold(Offer_redeem_as);
        }

    }

    if (id == 'AddrangeTable') {

        tableData.addRow();
        if (checkBoxObj == undefined) {
            selectAll($('#selectAll'));
        }else{
            /* selectRecord($(checkBoxObj)); */
            hideColumnsOnThreshold(Offer_redeem_as);
        }

    }

}


function itemEventListner(event) {


    DataChooser.setDataSourse(ItemList);
    DataChooser.showChooser(event, event);
}


function dataChooserEventListener(event, id, value) {
    if ($(event.inputFiled).attr('id') == 'txtSupplier') {
        loadSupplierOtherDetails(value);
        $('#lblSupplierName').text(id);
    } else {
        console.log(event.inputFiled);
        var selected = event.getSelected();
        var item_id = selected.hidden_id;
        var row_childs = event.getRowChilds();
        var hash_map = [];
        var arr = tableData.getDataSource();
        for (var i = 0; i < arr.length - 1; i++) {
            hash_map.push(arr[i][0]);
        }

        console.log(hash_map);
        if (hash_map.includes(value)) {

            showErrorMessage('Already exist ' + value);
            /* alert('Already exist '+value); */
            event.inputFiled.val('');
            return;
        }



        $.ajax({
            url: '/prc/getItemInfo/' + item_id,
            type: 'get',
            success: function (response) {
                console.log(response);
                var expireDateManage = response[0].manage_expire_date;

                if (offerType_text == "Free offer for given a quantity") {
                    $(row_childs[8]).val(response[0].item_Name);
                } else {
                    $(row_childs[9]).val(response[0].item_Name);
                }


            }
        })

    }

}

//offer edit function
function Offeredit(id) {
    /*  $('#staticBackdrop').modal('show'); */
    $('#hiddnLBLforID').val(id);
    $('#btnSave').text('Update');
    getEachOffer(id);

}

// offer view function
function Offerview(id) {
    /*  $('#staticBackdrop').modal('show'); */
    $('#btnSave').hide();
    getEachOffer(id);

}

//edit offer data
function OfferDataedit(id) {
    $('#lblofferDatahiddenForID').val(id);
    /*  $('#freeOfferDataModel').modal('show'); */
    $('#lblofferDatahidden').val(id);
    $('#saveBTNofferData').text('Update');
    getEachOfferData(id);
}

// offerdata view function
function Offerdataview(id) {
    /* $('#freeOfferDataModel').modal('show'); */
    $('#saveBTNofferData').hide();
    getEachOffer(id);

}

//edit threshold
function thresholdedit(id) {
    /*  $('#Thresholdsmodal').modal('show'); */
    $('#lblfreeOfferThresholds').val(id);
    $('#btnThreshold').text('Update')
    geteachThreshold(id);

}

//view threshold
function thresholdview(id) {
    /*  $('#Thresholdsmodal').modal('show'); */
    $('#btnThreshold').hide();
    geteachThreshold(id);
}

//edit range
function rangeEdit(id) {
    /*  $('#rangemodal').modal('show'); */
    $('#lblHiddenIDRange').val(id);
    $('#btnSaveRange').text('Update');
    getEachRangeData(id);
}

//view range
function rangeView(id) {
    /*  $('#rangemodal').modal('show'); */
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
function offerDatadelete() {

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
                deleteSelectedOfferData(checkBox_offerData_id_array);
                checkBox_offerData_id_array = [];
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


function hideForm() {

    $('#frmAddOffer').hide();
}

var offerName;
$('#saveBTNofferData').on('click', function (e) {
    $('#offerTable tbody tr:first').click();
    offerName = $('#offerTable tbody tr:first').text();
    console.log(offerName);
    $('#offerNamePlaceholder').text(offerName);
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
function getAddedOffers(id) {
    var table = $('#offerTable')


    $.ajax({
        type: "GET",
        url: "/sd/getAddedOffers/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            console.log(dt);
            $.each(dt, function (index, item) {
                var label = '<label class="badge bg-danger">' + item.is_active + '</label>';
                if (item.is_active == "Yes") {
                    label = '<label class="badge bg-success">' + item.is_active + '</label>';
                }
                let editButton = $('<button class="btn btn-primary" onclick="Offeredit(' + item.offer_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>');
                let viewButton = $('<button class="btn btn-success" onclick="Offerview(' + item.offer_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>');
                let deleteButton = $('<button class="btn btn-danger" onclick="offer_delete(' + item.offer_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>');
                var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.offer_id).text(item.name)));
                row.append($('<td>').text(item.description));
                row.append($('<td>').text(item.start_date));
                row.append($('<td>').text(item.end_date));
                row.append($('<td>').text(item.apply_to));
                row.append($('<td>').append(label));
                /*     row.append($('<td>').append(editButton).append(viewButton).append(deleteButton)); */
                table.append(row);
            });


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
        url: '/sd/deleteOffer/' + id,
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
            /*  getAllOffers(); */

        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

//delete offer data - post method used on delete becuse of formData obj.
function deleteSelectedOfferData(DeleteList) {
    var list_length = DeleteList.length;
    console.log(list_length);
    if (list_length == 0) {
        showWarningMessage("Please select a record");
        return;
    }
    formData.append('deleteList', JSON.stringify(DeleteList))
    $.ajax({

        url: '/sd/deleteSelectedOfferData',
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

        }, success: function (response) {
            var status = response.status
            if (status) {
                showSuccessMessage("Successfully deleted");
                removeDeletedOfferData(DeleteList)
            } else {
                showErrorMessage("Something went wrong")
            }

            /*   getOfferData(OfferID); */

        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });


}

//remove from the table
function removeDeletedOfferData(list) {

    $("#offerDataTable tbody tr").each(function () {
        var rowId = $(this).find('td:eq(0) label').data('id');

        if (list.includes(rowId)) {
            $(this).remove();
        }
    });
}


//get each offer data to boostrap modal
function getEachOffer(id) {
    $.ajax({
        url: '/sd/getEachOfferData/' + id,
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
    if (start_date <= end_date) {
        formData.append('txtOfferName', $('#txtOfferName').val());
        formData.append('txtDescription', $('#txtDescription').val());
        formData.append('dtStartDate', $('#dtStartDate').val());
        formData.append('dtEndDate', $('#dtEndDate').val());
        formData.append('cmbApplyTo', $('#cmbApplyTo').val());
        formData.append('isActive', isActive);
        $.ajax({
            url: '/sd/updateOffer/' + id,
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
                 //   $('#frmAddOffer')[0].reset();
                    $('#staticBackdrop').modal('hide');
                    /*  getAllOffers(); */
                    $('#btnSave').text('Add');
                    $('#offerTable tbody').empty();
                    getAddedOffers(id);
                    $('#btnSave').text('Update');


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
        url: '/sd/getEachOfferDataDetails/' + id,
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
        url: '/sd/updateOfferData/' + id,
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
                /* $('#freeOfferDataModel').modal('hide'); */
                $('#saveBTNofferData').text('Add');


            } else {
                showErrorMessage("Something went wrong");

            }
            /*  getOfferData(OfferID);
  */
        }, error: function (data) {

        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }

    })



}

//getting items from fb to cmb
function getItems() {
    $.ajax({
        url: '/sd/getIemstocmb',
        method: 'GET',
        async: false,
        success: function (data) {
            var dt = data.data;
            $.each(dt, function (index, value) {
                /*  $('#cmbItem').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>'); */
                $('#cmbFreeofferAnotherItem').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>');
                $('#cmbFreeOfferAnotherItemIDRange').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>');

            })

        },
    })
}

//load supply groups
function getSupllyGroup() {
    $.ajax({
        url: '/sd/getSupllyGroup',
        method: 'GET',
        async: false,
        success: function (data) {
            var dt = data;

            $.each(dt, function (index, value) {
                $('#cmbSupplyGroup').append('<option value="' + value.supply_group_id + '">' + value.supply_group + '</option>');

            })

        },
    })

}

function getItemsForSupGRP(id) {
    $('#cmbItem').empty();
    $.ajax({
        url: '/sd/getItemsForSupGRP/' + id,
        method: 'GET',
        async: false,
        success: function (data) {
            var dt = data;
            console.log(dt);
            $.each(dt, function (index, value) {
                $('#cmbItem').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>');;

            })

        },
    })

}

//get offer datat to data table
function getOfferData(id) {
    $.ajax({
        type: "GET",
        url: "/sd/getAllofferData/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var label = '<label class="badge bg-danger">' + dt[i].is_active + '</label>';
                if (dt[i].is_active == "Yes") {
                    label = '<label class="badge bg-success">' + dt[i].is_active + '</label>';
                }
                data.push({
                    "offer_data_id": dt[i].offer_data_id,
                    "offer_id": dt[i].offer_id,
                    "Item_code": '<div data-id = "' + dt[i].offer_data_id + '">' + dt[i].Item_code + '</div>',
                    "item_Name": dt[i].item_Name,
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

//get newly added offer data
function getNewOfferData(id) {
    var table = $('#offerDataTable')
    var rowCount;
    rowCount = table.find('tr').length;


    $.ajax({
        type: "GET",
        url: "/sd/getNewAddedofferData/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            var data = [];
            $.each(dt, function (index, item) {
               // var chkBox = $('<input>').attr('type', 'checkbox').val(item.offer_type).prop('checked', false).addClass('checkbox-item')
                /*  if(rowCount == 1){
                     var chkBox = $('<input>').attr('type', 'checkbox').val(item.offer_type).prop('checked', true).addClass('checkbox-item')
                 } */
                var label = '<label class="badge bg-danger">' + item.is_active + '</label>';
                if (item.is_active == "Yes") {
                    label = '<label class="badge bg-success">' + item.is_active + '</label>';
                }
                /* let editButton = $('<button class="btn btn-primary" onclick="Offeredit(' + item.offer_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>');
                let viewButton = $('<button class="btn btn-success" onclick="Offerview(' + item.offer_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>');
                let deleteButton = $('<button class="btn btn-danger" onclick="offer_delete(' + item.offer_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>'); */
                /* var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.offer_data_id).text(item.Item_code)));
                row.append($('<td>').append($('<label>').attr('data-id', item.offer_id).text(item.item_Name)));
                row.append($('<td>').append($('<label>').attr('data-id', item.item_id).text(item.offer_type)));
                row.append($('<td>').text(item.offer_redeem_as));
                row.append($('<td>').append(label));
                row.append($('<td>').append(chkBox));
                table.append(row); */
                data.push({
                    "item_code": '<div data-id = "' + item.offer_data_id + '">' + item.Item_code,
                    "item": '<div data-id = "' + item.offer_id + '">' + item.item_Name,
                    "offer_type": '<div data-id = "' + item.item_id + '">' + item.offer_type + '</div>',
                    "offer_redeem_as": item.offer_redeem_as,
                    "activate": label,
                    "action": '<input type="checkbox" class="checkbox-item" value="'+item.offer_type+'">',
                   
                });

            var table = $('#offerDataTable').DataTable();
           // table.clear(); 
            table.rows.add(data).draw();
 

            });


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}


//get all offer data to edit or view to table
function getallOfferData_updateView(id) {
    var table = $('#offerDataTable')
    var rowCount;
    rowCount = table.find('tr').length;


    $.ajax({
        type: "GET",
        url: "/sd/getAllofferData/" + id,
        cache: false,
        async:false,
        beforeSend: function () { },
        success: function (response) {
           /*  alert(); */
            console.log(response);
            var dt = response.data;
            var data = [];
            $.each(dt, function (index, item) {
                var chkBox = $('<input>').attr('type', 'checkbox').val(item.offer_type).prop('checked', false).addClass('checkbox-item')
                /*  if(rowCount == 1){
                     var chkBox = $('<input>').attr('type', 'checkbox').val(item.offer_type).prop('checked', true).addClass('checkbox-item')
                 } */
                var label = '<label class="badge bg-danger">' + item.is_active + '</label>';
                if (item.is_active == "Yes") {
                    label = '<label class="badge bg-success">' + item.is_active + '</label>';
                }


                data.push({
                    "item_code": '<div data-id = "' + item.offer_data_id + '">' + item.Item_code,
                    "item": '<div data-id = "' + item.offer_id + '">' + item.item_Name,
                    "offer_type": '<div data-id = "' + item.item_id + '">' + item.offer_type + '</div>',
                    "offer_redeem_as": item.offer_redeem_as,
                    "activate": label,
                    "action": '<input type="checkbox" class="checkbox-item" value="'+item.offer_type+'">',
                   
                });

            var table = $('#offerDataTable').DataTable();
            table.clear(); 
            table.rows.add(data).draw();   
            });



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
        url: "/sd/getallthresholds/" + id,
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
//get added threshold
function getaddedthresholds(id) {
    var table = $('#ThresholdsTable').DataTable();
    table.clear();
    $.ajax({
        type: "GET",
        url: "/sd/getaddedthresholds/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
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

    var activate = 1;
    /*  var chkActivate_offerData = $('#chkActivate_offerData').is(":checked") ? 1 : 0; */
    formData.append('cmbofferType', $('#cmbofferType').val());
    formData.append('cmbItem', $('#cmbItem').val());
    formData.append('cmbRedeemas', $('#cmbRedeemas').val());
    formData.append('chkActivate_offerData', activate);

    $.ajax({
        url: '/sd/addOfferData/' + id,
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
            var message = response.message;
            if (message == "duplicate") {
                showWarningMessage("Item already exist for the selected offer");
                return;
            }else if(message == "date overlap"){
                showWarningMessage("Selected item already has a offer");
                return;
            }

            var status = response.status;
            var primaryKey = response.primaryKey;
            console.log(primaryKey);


            if (status) {
                showSuccessMessage("Successfully saved");
                $('#frmOfferData')[0].reset();
                $('#freeOfferDataModel').modal('hide');


            } else {
                showErrorMessage("Something went wrong");

            }
            getNewOfferData(primaryKey);



        }, error: function (data) {

        }, complete: function () {
            /*  $('#btnSave').prop('disabled', false); */

        }

    })

}

//add offer data with supply group
function addOfferDataSupplyGroup(id) {
    var activate = 1;
    formData.append('cmbofferType', $('#cmbofferType').val());
    formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());
    formData.append('cmbRedeemas', $('#cmbRedeemas').val());
    formData.append('chkActivate_offerData', activate);

    $.ajax({
        url: '/sd/addOfferDatawithSupplyGroup/' + id,
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
            var message = response.message;
            var item = response.item;
            if (message == "duplicate") {
                showWarningMessage("Item already exist for the selected offer" + item);
                return;
            }

            var status = response.status;


            if (status) {
                showSuccessMessage("Successfully saved");
                $('#frmOfferData')[0].reset();
                $('#freeOfferDataModel').modal('hide');


            } else {
                showErrorMessage("Something went wrong");

            }

            $.each(response.primaryKey, function (index, Key) {

                getNewOfferData(Key);

            });




        }, error: function (data) {

        }, complete: function () {
            /*  $('#btnSave').prop('disabled', false); */

        }
    })

}


// add thresholds with offer type
function addThreshold(collection) {
    var selectedIds = [];

    $('#offerDataTable tr').each(function () {

        var checkbox = $(this).find('input.checkbox-item[type="checkbox"]');
       

        if (checkbox.prop('checked')) {

            var dataId = $(this).closest('tr').find('td:eq(0)').find('div').attr('data-id');
           /*  $(checkbox).closest('tr').find('td:eq(0)').find('div').attr('data-id'); */
            selectedIds.push(dataId);

        }
    });

    formData.append('selectedIDs', JSON.stringify(selectedIds));
    formData.append('collection', JSON.stringify(collection));

    $.ajax({
        url: '/sd/addThreshold',
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
            var primaryKey = response.primaryKey;
            var msg = response.message;

            if(msg == "exist"){
                showWarningMessage("Offer can't be duplicated");
                return;
            }

            if (status) {
                showSuccessMessage("Successfully saved");

                $('#frmthresholdData')[0].reset();
                $('#Thresholdsmodal').modal('hide');
                /*  getallthresholds(offerDataID_for); */
                /*  getaddedthresholds(primaryKey); */
                clearTableData();
                tableDataThreshold.addRow();
                hideColumnsOnThreshold(Offer_redeem_as);
                resetCheckBoxAndArrays();


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
        url: '/sd/geteachThreshold/' + id,
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
            $('#txtTotalOfferQuantity').val(res.total_offer_quantity);
            $('#txtTotalOfferValue').val(res.total_offer_value);

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
    formData.append('txtTotalOfferQuantity', $('#txtTotalOfferQuantity').val());
    formData.append('txtTotalOfferValue', $('#txtTotalOfferValue').val());


    $.ajax({
        url: '/sd/updateThresholdData/' + id,
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
                /*  getallthresholds(offerDataID_for); */
                /*  $('#Thresholdsmodal').modal('hide'); */
                $('#btnThreshold').text('Add')



            } else {
                showErrorMessage("Something went wrong");

            }

        }, error: function (data) {

        }, complete: function () {
            // $('#btnSave').prop('disabled', false);
        }

    })

}





//get newly added range data to data table
function GetaddedRangeData(id) {
    var table = $('#rangeTable').DataTable();
    table.clear();
    $.ajax({
        type: "GET",
        url: "/sd/GetaddedRangeData/" + id,
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
        url: '/sd/getEachRangeData/' + id,
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
            $('#txtFromRange').val(res.from);
            $('#txtToRange').val(res.to);
            $('#txtTotalOfferQuantityRange').val(res.total_offer_quantity);
            $('#txtTotalOfferValueRange').val(res.total_offer_value);

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });
}


// hiding columns on threhsold table - relate to line 52
function hideColumnsOnThreshold(array) {
    var redeem_as;
    column_index = [];


    $.each(array, function (index, value) {
        redeem_as = value;
        console.log(redeem_as);
        if (redeem_as == 'Free offer by quantity' || redeem_as == 'Free offer by price') {
            column_index = [2, 4, 6, 7, 8];

        } else if (redeem_as == 'Free offer by given value') {
            column_index = [1, 3, 5, 7, 8];

        } else if (redeem_as == 'Free offer by another item') {
            column_index = [1, 2, 3, 4, 5, 6];

        }
    })

    $.each(column_index, function (index, value) {
        var index = value;
        $('#AddThresholdsTable th:eq(' + index + '), #AddThresholdsTable td:nth-child(' + (index + 1) + ')').hide();
    })

}

function hideColumnsOnRange(array) {
    var redeem_as;

    var column_indexe_range = [];
    $.each(array, function (index, value) {
        redeem_as = value;
        if (redeem_as == 'Free offer by quantity' || redeem_as == 'Free offer by price') {

            column_indexe_range = [3, 5, 7, 8, 9]
        } else if (redeem_as == 'Free offer by given value') {

            column_indexe_range = [2, 4, 6, 8, 9];
        } else if (redeem_as == 'Free offer by another item') {

            column_indexe_range = [2, 3, 4, 5, 6, 7];
        }
    })

    $.each(column_indexe_range, function (index, value) {
        var index = value;
        $('#AddrangeTable th:eq(' + index + '), #AddrangeTable td:nth-child(' + (index + 1) + ')').hide();
    })



}

//shwoing columns
function showColumnsOnThreshold(array) {

    var redeem_as;
    var column_index = [];

    $.each(array, function (index, value) {
        redeem_as = value;
        if (redeem_as == 'Free offer by quantity' || redeem_as == 'Free offer by price') {
            column_index = [2, 4, 6, 7, 8];

        } else if (redeem_as == 'Free offer by given value') {
            column_index = [1, 3, 5, 7, 8];

        } else if (redeem_as == 'Free offer by another item') {
            column_index = [1, 2, 3, 4, 5, 6];

        }
    })

    $.each(column_index, function (index, value) {
        var index = value;
        $('#AddThresholdsTable th:eq(' + index + '), #AddThresholdsTable td:nth-child(' + (index + 1) + ')').show();
    })




}


function showColumnsOnRange(array) {
    var redeem_as;
    var column_indexe_range = [];
    $.each(array, function (index, value) {
        redeem_as = value;
        if (redeem_as == 'Free offer by quantity' || redeem_as == 'Free offer by price') {

            column_indexe_range = [3, 5, 7, 8, 9]
        } else if (redeem_as == 'Free offer by given value') {

            column_indexe_range = [2, 4, 6, 8, 9];
        } else if (redeem_as == 'Free offer by another item') {

            column_indexe_range = [2, 3, 4, 5, 6, 7];
        }
    })

    $.each(column_indexe_range, function (index, value) {
        var index = value;
        $('#AddrangeTable th:eq(' + index + '), #AddrangeTable td:nth-child(' + (index + 1) + ')').show();
    })



}

//check threshold
function checkThreshold(){
    var selectedIds = [];

    $('#offerDataTable tr').each(function () {

        var checkbox = $(this).find('input.checkbox-item[type="checkbox"]');
       

        if (checkbox.prop('checked')) {

            var dataId = $(this).closest('tr').find('td:eq(0)').find('div').attr('data-id');
            selectedIds.push(dataId);

        }
    });

    formData.append('selectedIDs', JSON.stringify(selectedIds));
   

    $.ajax({
        url: '/sd/checkThresholExist',
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
            var primaryKey = response.primaryKey;
            var msg = response.message;

            if(msg == "exist"){
                if(action == "edit" || action == "view"){
                    $('#btnThreshold').text('Update');
                }else{
                    $('#btnThreshold').text('Add');
                }
              
            }else{
                $('#btnThreshold').text('Add');
            }



        }, error: function (data) {

        }, complete: function () {
            // $('#btnSave').prop('disabled', false);
        }

    })

}


function selectRecord(checkbox) {
    console.log(checkbox);
    var isValueAlreadyPresent = checkboxValuesArray.includes($(checkbox).val());
    var valueArrayLenth = checkboxValuesArray.length;
    var dataIdValue = $(checkbox).closest('tr').find('td:eq(0)').find('div').attr('data-id');
  
    
    var redeem_as = $(checkbox).closest('tr').find('td:eq(3)').text();
    var isRedeemAsExist = Offer_redeem_as.includes(redeem_as);

   
    $('#threshold_colap').show();
    if (valueArrayLenth == 0) {
        checkboxValuesArray.push($(checkbox).val());
        checkBox_offerData_id_array.push(dataIdValue);
        Offer_redeem_as.push(redeem_as);
      
        if ($(checkbox).val() == 'Free offer for given a quantity') {
           
            if(action == 'edit'){
                getthreshold_data(dataIdValue);
                
            }else if(action == 'view'){
                getthreshold_data(dataIdValue);
            }else{
                hideColumnsOnThreshold(Offer_redeem_as);
            }
            
           
           
        } else {
            hideColumnsOnRange(Offer_redeem_as)
        }


    } else {

        if ($(checkbox).prop('checked')) {
            if (isValueAlreadyPresent && isRedeemAsExist) {

                checkboxValuesArray.push($(checkbox).val());
                checkBox_offerData_id_array.push(dataIdValue)
                Offer_redeem_as.push(redeem_as);
                if ($(checkbox).val() == 'Free offer for given a quantity') {
                    hideColumnsOnThreshold(Offer_redeem_as);
                    if(action == 'edit'){
                        getthreshold_data(dataIdValue);
                        
                    }else if(action == 'view'){
                        getthreshold_data(dataIdValue);
                    }else{
                        hideColumnsOnThreshold(Offer_redeem_as);
                    }
                  
                } else {
                    hideColumnsOnRange(Offer_redeem_as);

                }
            } else {

                showWarningMessage('Different Offer Type')

                $(checkbox).prop('checked', false);
            }
        } else {

            if (isValueAlreadyPresent && isRedeemAsExist) {
                var index = checkboxValuesArray.indexOf($(checkbox).val());
                var ind = checkBox_offerData_id_array.indexOf(dataIdValue);
                var ind_redeem_as = Offer_redeem_as.indexOf(redeem_as);
                if (index !== -1) {
                    checkboxValuesArray.splice(index, 1);
                    checkBox_offerData_id_array.splice(ind, 1);
                    
                    if ($(checkbox).val() == 'Free offer for given a quantity') {
                        showColumnsOnThreshold(Offer_redeem_as);
                    } else {
                        showColumnsOnRange(Offer_redeem_as);
                    }
                    Offer_redeem_as.splice(ind_redeem_as, 1);
                }

            }
            $('#threshold_colap').hide();
            
        }

    }

    console.log(checkboxValuesArray);
    console.log(Offer_redeem_as);
}

//select all
function selectAll(checkbox) {
    var isChecked = $(checkbox).prop('checked');
    if (isChecked) {
        $('.checkbox-item').each(function () {
            var checkboxValue = $(this).val();
            var valueArrayLength = checkboxSelectAllArray.length;
            var isValueAlreadyPresent_val = checkboxSelectAllArray.includes(checkboxValue);

            var redeem_as = $(this).closest('tr').find('td:eq(3)').text();
            var isRedeemAsExist_val = checkBox_offer_redeem.includes(redeem_as);



            if (valueArrayLength === 0) {

                checkboxSelectAllArray.push(checkboxValue);
                checkBox_offer_redeem.push(redeem_as);
                $(this).prop('checked', true);
                if ($(this).val() == 'Free offer for given a quantity') {
                    hideColumnsOnThreshold(checkBox_offer_redeem);
                } else {
                    hideColumnsOnRange(checkBox_offer_redeem);

                }

            } else {
                if (isValueAlreadyPresent_val && isRedeemAsExist_val) {

                    checkboxSelectAllArray.push(checkboxValue);
                    checkBox_offer_redeem.push(redeem_as);
                    $(this).prop('checked', true);
                    if ($(this).val() == 'Free offer for given a quantity') {
                        hideColumnsOnThreshold(checkBox_offer_redeem);
                    } else {
                        hideColumnsOnRange(checkBox_offer_redeem);

                    }


                } else {

                    showWarningMessage('Different Offer Types');
                    checkboxSelectAllArray = [];
                    checkBox_offer_redeem = []
                    $(checkbox).prop('checked', false);
                    $('.checkbox-item').each(function () {
                        $(this).prop('checked', false);
                    })
                    return false;

                }
            }

            if (checkboxValue == "Free offer for given a quantity") {

                $('#range_colap').hide();
                $('#threshold_colap').show();
                /*   getallthresholds(offerDataID_for);  */
            } else {
    
    
                $('#threshold_colap').hide();
                $('#range_colap').show();
                /* GetRangeData(offerDataID_for); */
    
            }
        });

       
    } else {
        $('.checkbox-item').each(function () {
            $(this).prop('checked', false);
        })
        checkboxSelectAllArray = [];
        Offer_redeem_as = []
        $('#threshold_colap').hide();
    }
}




//get server time
function getServerTime() {
    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#dtStartDate').val(formattedDate);
            $('#dtEndDate').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}


function getthreshold_data(offerDataid){
    $.ajax({
        url: '/sd/getthreshold_data/' + offerDataid,
        type: 'GET',
        dataType: 'json',
        success: function (response) {

            var dt = response

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                

                data.push({

                    "qty": dt[i].quantity,
                    "freeoferqty": dt[i].free_offer_quantity,
                    "maxqty": dt[i].maximum_quantity,
                    "totaloferqty": dt[i].total_offer_quantity,
                    
                   
                   

                });
            }


            var table = $('#AddThresholdsTable').DataTable();
            table.clear();
            table.rows.add(data).draw();






           
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}


// clear table
function clearTableData() {
    dataSource = [];
    tableDataThreshold.setDataSource(dataSource);
    

}

function resetCheckBoxAndArrays(){
    $('.checkbox-item').each(function () {
            $(this).prop('checked', false);
        })

    $('#selectAll').prop('checked',false);


 checkboxSelectAllArray = [];
 checkBox_offer_redeem = [];
 column_indexe_range = [];
}