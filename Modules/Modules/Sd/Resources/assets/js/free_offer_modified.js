
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
        const listboxButtonsElement = document.querySelector(".listbox-buttons");
        const listboxButtons = new DualListbox(listboxButtonsElement, {
            options: option_array,

            addEvent: function (value) {
                //alert(value);
            },
            removeEvent: function (value) {
                var selectedBranchId = $('#cmbEmployee').val();

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
                }

                //alert(selectedBranchId);
            },


            addButtonText: "<i class='ph-caret-right'></i>",
            removeButtonText: "<i class='ph-caret-left'></i>",
            addAllButtonText: "<i class='ph-caret-double-right'></i>",
            removeAllButtonText: "<i class='ph-caret-double-left'></i>",

        });

        listBox = listboxButtons;

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


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DualListboxes.init();
});



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
var free_qty_hashMap = new Map();
var public_checkboxId = undefined;
$(document).ready(function () {
    $('#dtEndDate,#dtStartDate').on('focusout', function () {
        
        $(this).removeClass('is-valid');
    });
    $('#dtEndDate,#dtStartDate').on('change', function () {
        
        $(this).removeClass('is-valid');
    });

    getServerTime();
    $('.select2').select2();
    //tabs
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    //load items
    loadItems_freeOffer()

    //loadi tems to table wit hsup grp
    $('#cmbSupplyGroup').on('change', function () {

        getItemsForSupGRP($(this).val());

    });

    //add item to table without sup grp
    $('#cmbItem').on('change', function () {
        var selectedOption = $(this).find('option:selected');
        var id = selectedOption.val();
        var code = selectedOption.data('id');
        var name = selectedOption.text();

        addItems_table(id, code, name);

    });

    $('#btn_remove_item').on('click', function () {
        remove_ItemList();
    });

    $('#btn_remove_customer').on('click', function () {
        remove_customer();
    });


    getSupllyGroup();

    $('#selectAll').prop('checked', false);


    /* $('#selectAll').on('change', function () {

        selectAll_customer($(this),'customer');
    }); */

      //load data to dual list box
      $('#cmbFilterBy').on('change', function () {
        get_offer_data_apply_to($(this).val());
      /*   if ($(this).val() == 2) {

            get_offer_data_apply_to($(this).val());


        } else {
            get_offer_data_apply_to($(this).val());
        } */

    });

    $('#offer_customer_model').on('show.bs.modal', function () {
        if ($('#cmbApplyTo').val() == 3) {
            // Clear existing options
            $('#cmbFilterBy').empty();
            
            // Append specific options
            $('#cmbFilterBy').append('<option value="3">Customer Group</option>');
            $('#cmbFilterBy').change();
        } else {
            // Clear existing options
            $('#cmbFilterBy').empty();
            
            // Append other options
            $('#cmbFilterBy').append('<option value="0" selected>Select</option>');
            $('#cmbFilterBy').append('<option value="2">Customer</option>');
            $('#cmbFilterBy').append('<option value="3">Customer Group</option>');
        }
    });
    

    //show customer pick model
    $('#btn_pick').on('click', function () {
        $('#offer_customer_model').modal('show');
    });



    /** Initilization of threshold table */
    tableDataThreshold = $('#AddThresholdsTable').transactionTable({
        "columns": [

            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:85px;text-align:right;", "event": "" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:85px;text-align:right;", "event": "" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "delete_from_hashMap(this);removeRow(this)", "width": 30 }
        ],
        "auto_focus": 0,
        "hidden_col": []

    });

    tableDataThreshold.addRow();


    $('#AddThresholdsTable').on('input', 'input[type="text"]', function () {
        // Allow only numbers (integer)
        this.value = this.value.replace(/[^0-9]/g, '');
    });


    //save threshold data when button is pressed

    $('#save_data').on('click', function (e) {
        var arr = tableDataThreshold.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {

            collection.push(JSON.stringify({
                "qty": parseFloat(arr[i][0].val().replace(/,/g, '')),
                "foc": parseFloat(arr[i][1].val().replace(/,/g, '')),

            }));


        }

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
                    if ($('#save_data').val() == 'Save') {
                        add_free_offer_new(free_qty_hashMap);

                    } else if ($('#save_data').val() == 'Update') {

                        update_free_offer_new(free_qty_hashMap,offerID)
                    }
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


    });


  
    //apply to
    $('#cmbApplyTo').on('change', function () {
        if ($(this).val() == 1) {
            $('#btn_pick').hide();
            $('#customer_li').hide();

        } else if($(this).val() == 2) {
            
            $('#btn_pick').show();
            $('#customer_li').show();
            $('#customer_group_li').hide();

        }else{
            
            $('#btn_pick').show();
            $('#customer_li').hide();
            $('#customer_group_li').show();
        }
    });

    //hide theshold table
    $('#customer_li').on('click', function () {
        $('#threshold_div').hide();
    });

    //show theshold table
    $('#item_li').on('click', function () {
        $('#threshold_div').show();
    });

    $('#cmbApplyTo').change();



    //add customers to table
    $('#btn_add_cus').on('click', function () {
        add_selected_customers();
        $('#offer_customer_model').modal('hide');

    });



    //edit
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        offerID = param[0].split('=')[1].split('&')[0];
        var offerDataID = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];



        if (action == 'edit') {
            
            $('#save_data').val('Update');
            /* $('#cmbSupplyGroup').prop('disabled',true); */
        } else if (action == 'view') {
            $('#cmbSupplyGroup').prop('disabled', true);
            $('#txtOfferName').prop('disabled', true);
            $('#cmbApplyTo').prop('disabled', true);
            $('#txtDescription').prop('disabled', true);
            $('#dtStartDate').prop('disabled', true);
            $('#dtEndDate').prop('disabled', true);
            $('#cmbItem').prop('disabled', true);
            $('#chkActivate').prop('disabled',true);
            $('#btn_pick').hide();
            $('#btn_remove_item').hide();
            $('#btn_reset_threshold').hide();
            $('#save_data').hide();
            $('#btn_apply_threshold').hide();
            $('#btn_reset_threshold').hide();
            
            

        }


        get_each_offer(offerID);


    }


    //add hashMap
    $('#btn_apply_threshold').on('click', function () {
        add_to_item_hashMap();
    });

    $('#offerItemtable').on('click', 'tr td:nth-child(n+1):nth-child(-n+3)', function (e) {
        // Remove 'selected' class from all rows and add it to the clicked row
        $('#offerItemtable tr').removeClass('selected');
        $(this).closest('tr').addClass('selected');

        $('#offerItemtable input[type="checkbox"]').prop('checked', false); // Uncheck all checkboxes
        $(this).closest('tr').find('input[type="checkbox"]').prop('checked', true); // Check the checkbox in the clicked row

        // Call read_hashMap only if the clicked cell is within the allowed indexes
        read_hashMap($(this).closest('tr'), free_qty_hashMap);
    });

    $('#btn_reset_threshold').on('click', function () {
        reset_hashMap();
    });

    

});





function transactionTableKeyEnterEvent(event, id) {

    tableDataThreshold.addRow();


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

//ad item by sup grp
function getItemsForSupGRP(id) {


    $.ajax({
        url: '/sd/getItemsForSupGRP/' + id,
        method: 'GET',
        async: false,
        success: function (data) {
            var dt = data;
            console.log(dt);
            var table = $('#offerItemtable');
            var tableBody = $('#offerItemtable tbody');
            tableBody.empty();

            $.each(dt, function (index, item) {
                var active_badge = '<label class="badge badge-pill bg-success">Active</label>';
                if (item.is_active != 1) {
                    var active_badge = '<label class="badge badge-pill bg-warning">Inactive</label>';
                }
                var row = $('<tr>').css('height', '15px');

                row.append($('<td>').append($('<label>').attr('data-id', item.item_id).text(item.Item_code)));
                row.append($('<td>').text(item.item_Name));
                row.append($('<td>').append('<i class="fa fa-check hide_icon" aria-hidden="true"></i>'));
                row.append($('<td>').append($('<input>').attr({
                    'type': 'checkbox',
                    'class': 'form-check-input',
                    'name': 'select_item_chk',
                    'id': item.item_id
                }).on('change', function () {
                    unset_select_all(this)
                })));
                row.append(
                    $('<button type="button" style="border: none; background-color: transparent;">')
                        .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                        .on('click', function () {
                            remove_item_line(this);
                        })
                );

                table.append(row);

            })

        },
    })

}

//add item to table without sup grp
function addItems_table(id, code, name) {
    var active_badge = '<label class="badge badge-pill bg-success">Active</label>';
    var table = $('#offerItemtable');
    var tableBody = $('#offerItemtable tbody');
    var is_duplicated = validate_item_duplication(code);


    if (is_duplicated) {
        showWarningMessage('Unable to duplicate items');
    } else {
        if (name != "Select Item") {
            var row = $('<tr>').css('height', '15px');
            row.append($('<td>').append($('<label>').attr('data-id', id).text(code)));
            row.append($('<td>').text(name));
            row.append($('<td>').append('<i class="fa fa-check hide_icon" aria-hidden="true"></i>'));
            row.append($('<td>').append($('<input>').attr({
                'type': 'checkbox',
                'class': 'form-check-input',
                'name': 'select_item_chk',
                'id': id
            }).on('change', function () {
                unset_select_all(this)
            })));
            row.append(
                $('<button type="button" style="border: none; background-color: transparent;">')
                    .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                    .on('click', function () {
                        remove_item_line(this);
                    })
            );

            table.append(row);

        }
    }



}

//validate wether same item has been added or not
function validate_item_duplication(code) {
    var status_ = false;
    var table = $('#offerItemtable');
    table.find('tbody tr').each(function () {
        var cellData = $(this).find('td:eq(0)').text();

        if (cellData === code) {
            status_ = true;
        }
    });

    return status_;
}

function loadItems_freeOffer() {


    $.ajax({
        url: '/sd/loadItems_freeOffer',
        method: 'GET',
        async: false,
        success: function (data) {
            var dt = data;
            console.log(dt);

            $('#cmbItem').append('<option value="" data-id="">Select Item</option>');
            $.each(dt, function (index, item) {
                $('#cmbItem').append('<option value="' + item.item_id + '" data-id="' + item.Item_code + '">' + item.item_Name + '</option>');



            })

        },
    })

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




// clear table
function clearTableData() {
    dataSource = [];
    tableDataThreshold.setDataSource(dataSource);


}

//select all - item table
function selectAll_item(event) {
    if ($(event).is(':checked')) {
        $('#offerItemtable tbody tr').each(function () {
            var checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', true);

        });
    } else {
        $('#offerItemtable tbody tr').each(function () {
            var checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', false);

        });
    }

}

//select all - customer table
function selectAll_customer(event,table) {
    if(table == 'customer'){
        if ($(event).is(':checked')) {
            $('#offer_customer tbody tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', true);
    
            });
        } else {
            $('#offer_customer tbody tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', false);
    
            });
        }
    }else{
        if ($(event).is(':checked')) {
            $('#offer_customer_group tbody tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', true);
    
            });
        } else {
            $('#offer_customer_group tbody tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', false);
    
            });
        }
    }
    

}

//clear select all when one check box is unchecked
function unset_select_all(event) {
    if ($(event).is(':checked')) {



    } else {

        $('#chkAll').prop('checked', false);
    }
}


//clear select all when one check box is unchecked
function unset_select_all_cus(event) {
    if ($(event).is(':checked')) {



    } else {

        $('#selectAll').prop('checked', false);
    }
}
//remove items form the table
function remove_ItemList() {
    var table = $('#offerItemtable');
    table.find('tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');

        if (checkbox.prop('checked')) {

            $(this).remove();
        }

    });

}

//remove customers form the table
function remove_customer() {
    var table = $('#offer_customer');
    table.find('tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');

        if (checkbox.prop('checked')) {

            $(this).remove();
        }

    });

}

//remove each line - customer
function remove_custom_line(button) {

    var row = button.closest('tr');
    row.remove();

}

//remove each line - item
function remove_item_line(button) {

    var row = button.closest('tr');
    row.remove();

}

//add new offer - latest_view
function add_free_offer_new(free_qty_hashMap) {


    var apply_to = $('#cmbApplyTo').val();
    if (apply_to == 2) {
        //adding customer ids to array
        var table_cus = $('#offer_customer');
        var cus_id_array = [];
        table_cus.find('tbody tr').each(function () {
            var checkbox_cus = $(this).find('input[type="checkbox"]');
            var checkboxId_cus = checkbox_cus.attr('id');
            cus_id_array.push(checkboxId_cus);


        });

    }else if(apply_to == 3){
         //adding group ids to array
         var table_cus = $('#offer_customer_group');
         var grp_id_array = [];
         table_cus.find('tbody tr').each(function () {
             var checkbox_cus = $(this).find('input[type="checkbox"]');
             var checkboxId_cus = checkbox_cus.attr('id');
             grp_id_array.push(checkboxId_cus);
 
 
         });
    }

    console.log(grp_id_array);




    //adding item ids to array
    var table = $('#offerItemtable');
    var item_id_array = [];


    table.find('tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        var checkboxId = checkbox.attr('id');
        var has_in_hashMap_array = free_qty_hashMap.get(checkboxId);
        if (has_in_hashMap_array) {
            item_id_array.push(checkboxId);
        }



    });





    if ($('#txtOfferName').val().length < 1) {
        showWarningMessage('Offer Name is required');
        $('#txtOfferName').addClass('is-invalid');
        return;
    }

    var start_date = $('#dtStartDate').val();
    var end_date = $('#dtEndDate').val();
    var isActive = $('#chkActivate').is(":checked") ? 1 : 0;
    if (start_date <= end_date) {

        formData.append('txtOfferName', $('#txtOfferName').val());
        formData.append('txtDescription', $('#txtDescription').val());
        formData.append('dtStartDate', $('#dtStartDate').val());
        formData.append('dtEndDate', $('#dtEndDate').val());
        formData.append('cmbApplyTo', $('#cmbApplyTo').val());
        formData.append('isActive', isActive);
        const object = Object.fromEntries(free_qty_hashMap);
        console.log(object);
        formData.append('free_qty_hashMap', JSON.stringify(object));
        formData.append('item_id_array', JSON.stringify(item_id_array));
        if (apply_to == 2) {
            formData.append('cus_id_array', JSON.stringify(cus_id_array));
            console.log(cus_id_array);
        }else if(apply_to == 3){
            formData.append('grp_id_array', JSON.stringify(grp_id_array));
        }        
            $.ajax({
            url: '/sd/addFreeOffer_latest',
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
              //  $('#save_data').prop('disabled', true);
            },
            success: function (response) {
                $('#save_data').prop('disabled', false);
                console.log(response);
                var status = response.status;
                var message = response.message;

                if (status) {
                    showSuccessMessage('Offer Saved Successfully');
                    $('#frmAddOffer').trigger("reset");

                    $('#offerItemtable tbody').empty();
                    $('#offer_customer tbody').empty();
                    $('#selectAll').prop('checked', false);
                    $('#chkAll').prop('checked', false);
                    dataSource = [];
                    tableDataThreshold.setDataSource(dataSource);
                    tableDataThreshold.addRow();
                    getServerTime();
                    free_qty_hashMap.clear();
                    $('#btn_apply_threshold').val('Apply');
                    // loadItems_freeOffer()
                } else {
                    if (message == "item_empty") {
                        showWarningMessage('At least one item should be selected');
                        return;
                    } else if (message == "customer_empty") {
                        showWarningMessage('At least one customer should be selected');
                        return;
                    } else if (message == "date overlap") {
                        showWarningMessage('Offer dates are overlapped of a selected item');
                        var itm_id_ = response.item_id;
                        var cus = response.cus_id
                        overlapped_item(itm_id_);
                        if($('#cmbApplyTo').val() == 2){
                           
                            overlapped_customer(cus);
                        }
                    } else {
                        showWarningMessage('Unable to save');
                    }

                }

            }, error: function (data) {

            }, complete: function () {

            }
        });

    } else {

        showWarningMessage("Please check the selected dates");
        $('#dtStartDate').val(end_date).addClass('is-invalid');
        $('#dtEndDate').val(start_date).addClass('is-invalid');

    }




}

//update free offer - latest
function update_free_offer_new(free_qty_hashMap,id) {


    var apply_to = $('#cmbApplyTo').val();
    if (apply_to == 2) {
        //adding customer ids to array
        var table_cus = $('#offer_customer');
        var cus_id_array = [];
        table_cus.find('tbody tr').each(function () {
            var checkbox_cus = $(this).find('input[type="checkbox"]');
            var checkboxId_cus = checkbox_cus.attr('id');
            cus_id_array.push(checkboxId_cus);


        });

    }else if(apply_to == 3){
        //adding group ids to array
        var table_cus = $('#offer_customer_group');
        var grp_id_array = [];
        table_cus.find('tbody tr').each(function () {
            var checkbox_cus = $(this).find('input[type="checkbox"]');
            var checkboxId_cus = checkbox_cus.attr('id');
            grp_id_array.push(checkboxId_cus);


        });
   }


    //adding item ids to array
    var table = $('#offerItemtable');
    var item_id_array = [];


    table.find('tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        var checkboxId = checkbox.attr('id');
       
        var has_in_hashMap_array = free_qty_hashMap.get(parseInt(checkboxId));
        if (has_in_hashMap_array) {
            item_id_array.push(checkboxId);
        }



    });
    console.log(item_id_array);






    if ($('#txtOfferName').val().length < 1) {
        showWarningMessage('Offer Name is required');
        $('#txtOfferName').addClass('is-invalid');
        return;
    }

    var start_date = $('#dtStartDate').val();
    var end_date = $('#dtEndDate').val();
    var isActive = $('#chkActivate').is(":checked") ? 1 : 0;
    
    if (start_date <= end_date) {

        formData.append('txtOfferName', $('#txtOfferName').val());
        formData.append('txtDescription', $('#txtDescription').val());
        formData.append('dtStartDate', $('#dtStartDate').val());
        formData.append('dtEndDate', $('#dtEndDate').val());
        formData.append('cmbApplyTo', $('#cmbApplyTo').val());
        formData.append('isActive', isActive);
        const object = Object.fromEntries(free_qty_hashMap);
        console.log(object);
        formData.append('free_qty_hashMap', JSON.stringify(object));
        formData.append('item_id_array', JSON.stringify(item_id_array));
        if (apply_to == 2) {
            formData.append('cus_id_array', JSON.stringify(cus_id_array));
            console.log(cus_id_array);
        }else if(apply_to == 3){
            formData.append('grp_id_array', JSON.stringify(grp_id_array));
        }

        $.ajax({
            url: '/sd/update_free_offer_new/'+id,
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
               // $('#save_data').prop('disabled', true);
            },
            success: function (response) {
             //   $('#save_data').prop('disabled', false);
                var status = response.status;
                var message = response.message;

                if (status) {
                    showSuccessMessage('Offer Updated Successfully');
                    $('#frmAddOffer').trigger("reset");

                    $('#offerItemtable tbody').empty();
                    $('#offer_customer tbody').empty();
                    $('#selectAll').prop('checked', false);
                    $('#chkAll').prop('checked', false);
                    dataSource = [];
                    tableDataThreshold.setDataSource(dataSource);
                    tableDataThreshold.addRow();
                    getServerTime();
                    free_qty_hashMap.clear();
                    $('#btn_apply_threshold').val('Apply');
                    url = "/sd/freeOfferListView";
                    window.location.href = url;
                    // loadItems_freeOffer()
                } else {
                    if (message == "item_empty") {
                        showWarningMessage('At least one item should be selected');
                        return;
                    } else if (message == "customer_empty") {
                        showWarningMessage('At least one customer should be selected');
                        return;
                    } else if (message == "date overlap") {
                        showWarningMessage('Offer dates are overlapped of a selected item');
                        var itm_id_ = response.item_id;
                        var cus = response.cus_id
                        overlapped_item(itm_id_);
                        if($('#cmbApplyTo').val() == 2){
                            overlapped_customer(cus);
                        }
                    } else {
                        showWarningMessage('Unable to save');
                    }

                }

            }, error: function (data) {

            }, complete: function () {

            }
        });

    } else {

        showWarningMessage("Please check the selected dates");
        $('#dtStartDate').val(end_date).addClass('is-invalid');
        $('#dtEndDate').val(start_date).addClass('is-invalid');

    }




}

//find overlapped item
function overlapped_item(id) {
    $it_table = $('#offerItemtable');
    var checkboxSelector = '#' + id;
    var row = $(checkboxSelector).closest('tr');
    row.removeClass('selected');
    row.addClass('overlapped_row');
}

//find overlapped customer
function overlapped_customer(id) {
    var checkboxSelector = '#' + id;
    $('#offer_customer tbody tr').each(function() {
        var checkbox = $(this).find(checkboxSelector);

        if (checkbox.length > 0) {
            
            row = $(this);
            row.removeClass('selected');
            row.addClass('overlapped_row');
            
        }
    })
   
}

//load customers
function get_offer_data_apply_to(type) {
    $.ajax({
        url: '/sd/get_offer_data_apply_to/' + type,
        method: 'get',
        datatype: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            console.log(data);
            //make an empty array if array has data 
            if (DualListboxes.geOptionArray().length > 0) {
                DualListboxes.clear();
            }

            $.each(data, function (index, item) {


                DualListboxes.geOptionArray().push({ text: item.name, value: item.id });

            });
            $('.cmbFilterData').remove();
            DualListboxes.init();

        }, error: function (data) {
            console.log(data)
        }

    });


}

//add selected customers to the tabe
function add_selected_customers() {
    var datatype;
    
    if($('#cmbApplyTo').val() == 3){
        load_selected_groups();
    }else{
        var value = $('#cmbFilterBy').val();
        if (value == 2) {
            load_selected_customers();
        } else if (value == 3) {
            load_grp_customers();
        }
    }
  

}

function load_selected_customers() {
    $cus_array = DualListboxes.getSelectedOptions();

    $.ajax({
        url: '/sd/load_selected_customers',
        method: 'get',
        data: {
            option_array: $cus_array
        },

        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            var table = $('#offer_customer');
            var tableBody = $('#offer_customer tbody');
            var dt = response.data;

            for (var i = 0; i < dt.length; i++) {

                for (var j = 0; j < dt[i].length; j++) {

                    var id = dt[i][j].customer_id;
                    var cus_is_duplicated = validate_customer_duplication(dt[i][j].customer_code);
                    if (!cus_is_duplicated) {
                        var row = $('<tr>').css('height', '15px');
                        row.append($('<td>').append($('<label>').attr('data-id', dt[i][j].customer_id).text(dt[i][j].customer_code)));
                        row.append($('<td>').text(dt[i][j].customer_name));
                        row.append($('<td>').text(dt[i][j].townName));
                        row.append($('<td>').append($('<input>').attr({
                            'type': 'checkbox',
                            'class': 'form-check-input',
                            'name': 'select_item_chk',
                            'id': id
                        }).on('change', function () {
                            unset_select_all_cus(this);
                        })));
                        row.append(
                            $('<button type="button" style="border: none; background-color: transparent;">')
                                .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                                .on('click', function () {
                                    remove_custom_line(this);
                                }));




                        table.append(row);

                    } else {
                        showWarningMessage(' Customer can not be duplicated');
                    }

                }

            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });

}


//load customer according to selected grp
function load_grp_customers() {
    $cus_grp_array = DualListboxes.getSelectedOptions();
    $.ajax({
        url: '/sd/load_grp_customers',
        method: 'get',
        enctype: 'multipart/form-data',
        data: {
            option_array: $cus_grp_array
        },

        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            var table = $('#offer_customer');
            var tableBody = $('#offer_customer tbody');
            var dt = response.data;

            for (var i = 0; i < dt.length; i++) {

                for (var j = 0; j < dt[i].length; j++) {

                    var id = dt[i][j].customer_id;
                    var cus_is_duplicated = validate_customer_duplication(dt[i][j].customer_code);
                    if (!cus_is_duplicated) {
                        var row = $('<tr>').css('height', '15px');
                        row.append($('<td>').append($('<label>').attr('data-id', dt[i][j].customer_id).text(dt[i][j].customer_code)));
                        row.append($('<td>').text(dt[i][j].customer_name));
                        row.append($('<td>').text(dt[i][j].townName));
                        row.append($('<td>').append($('<input>').attr({
                            'type': 'checkbox',
                            'class': 'form-check-input',
                            'name': 'select_item_chk',
                            'id': id
                        }).on('change', function () {
                            unset_select_all_cus(this);
                        })));
                        row.append(
                            $('<button type="button" style="border: none; background-color: transparent;">')
                                .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                                .on('click', function () {
                                    remove_custom_line(this);
                                }));

                        table.append(row);

                    } else {
                        showWarningMessage('Customer can not be duplicated');
                    }

                }

            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });

}


//load selected customer groups to the table
function load_selected_groups() {
    $cus_grp_array = DualListboxes.getSelectedOptions();
    $.ajax({
        url: '/sd/load_selected_groups',
        method: 'get',
        enctype: 'multipart/form-data',
        data: {
            option_array: $cus_grp_array
        },

        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            var table = $('#offer_customer_group');
            var tableBody = $('#offer_customer_group tbody');
            var dt = response.data;
            console.log(dt);
            for (var i = 0; i < dt.length; i++) {

                for (var j = 0; j < dt[i].length; j++) {

                    var id = dt[i][j].customer_group_id;
                    var cus_is_duplicated = validate_customer_group_duplication(dt[i][j].group);
                    if (!cus_is_duplicated) {
                        
                        var row = $('<tr>').css('height', '15px');
                        row.append($('<td>').append($('<label>').attr('data-id', dt[i][j].customer_group_id).text(dt[i][j].group)));
                       
                        
                        row.append($('<td>').append($('<input>').attr({
                            'type': 'checkbox',
                            'class': 'form-check-input',
                            'name': 'select_item_chk',
                            'id': id
                        }).on('change', function () {
                            unset_select_all_cus(this);
                        })));
                        row.append(
                            $('<button type="button" style="border: none; background-color: transparent;">')
                                .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                                .on('click', function () {
                                    remove_custom_line(this);
                                }));

                        table.append(row);

                    } else {
                        showWarningMessage('Customer group can not be duplicated');
                    }

                }

            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });

}


//validate wether same customer has been added or not
function validate_customer_duplication(code) {
    var status_ = false;
    var table = $('#offer_customer');
    table.find('tbody tr').each(function () {
        var cellData = $(this).find('td:eq(0)').text();

        if (cellData === code) {
            status_ = true;
        }
    });

    return status_;
}

//validate whether same customer group has been added or not
function validate_customer_group_duplication(code) {
    var status_ = false;
    var table = $('#offer_customer_group');
    table.find('tbody tr').each(function () {
        var cellData = $(this).find('td:eq(0)').text();

        if (cellData === code) {
            status_ = true;
        }
    });

    return status_;
}


function get_each_offer(id) {
    $.ajax({
        url: '/sd/get_each_offer/' + id,
        method: 'get',
        enctype: 'multipart/form-data',
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response.supply_group);
            //appending offer details
            var offer_details = response.offer;
            if(offer_details[0].is_active == 1){
                $('#chkActivate').prop('checked',true);
            }else{
                $('#chkActivate').prop('checked',false);
            }
            $('#txtOfferName').val(offer_details[0].name);
            $('#cmbApplyTo').val(offer_details[0].apply_to);
            $('#txtDescription').val(offer_details[0].description);
            $('#dtStartDate').val(offer_details[0].start_date);
            $('#dtEndDate').val(offer_details[0].end_date);
            $('#cmbApplyTo').change();

            //appending items - datas
            var item_table = $('#offerItemtable');
            var item_tableBody = $('#offerItemtable tbody');
            var datas_ = response.offer_Data;

            for (var i = 0; i < datas_.length; i++) {
                var id = datas_[i].item_id;
                var item_is_duplicated = validate_item_duplication(datas_[i].Item_code);
                var active_badge = '<label class="badge badge-pill bg-success">Active</label>';
                if (datas_[i].is_active == 2) {
                    active_badge = '<label class="badge badge-pill bg-danger">Inactive</label>';
                }

                if (!item_is_duplicated) {
                    var row = $('<tr>').css('height', '15px');
                    row.append($('<td>').append($('<label>').attr('data-id', datas_[i].item_id).text(datas_[i].Item_code)));
                    row.append($('<td>').text(datas_[i].item_Name));
                    row.append($('<td>').append('<i class="fa fa-check" aria-hidden="true"></i>'));
                    row.append($('<td>').append($('<input>').attr({
                        'type': 'checkbox',
                        'class': 'form-check-input',
                        'name': 'select_item_chk',
                        'id': id
                    }).on('change', function () {
                        unset_select_all(this);
                    })));
                    row.append(
                        $('<button type="button" style="border: none; background-color: transparent;">')
                            .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                            .on('click', function () {
                                remove_item_line(this);
                            })
                    );
                    item_table.append(row);

                } else {
                    showWarningMessage('Item can not be duplicated');
                }



            }


            //appending customers
            var table = $('#offer_customer');
            var tableBody = $('#offer_customer tbody');
            var dt = response.customers;

            for (var i = 0; i < dt.length; i++) {
                var id = dt[i].customer_id;
                var cus_is_duplicated = validate_customer_duplication(dt[i].customer_code);
                if (!cus_is_duplicated) {
                    var row = $('<tr>').css('height', '15px');
                    row.append($('<td>').append($('<label>').attr('data-id', dt[i].customer_id).text(dt[i].customer_code)));
                    row.append($('<td>').text(dt[i].customer_name));
                    row.append($('<td>').text(dt[i].townName));
                    row.append($('<td>').append($('<input>').attr({
                        'type': 'checkbox',
                        'class': 'form-check-input',
                        'name': 'select_item_chk',
                        'id': id
                    }).on('change', function () {
                        unset_select_all_cus(this);
                    })));
                    row.append(
                        $('<button type="button" style="border: none; background-color: transparent;">')
                            .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                            .on('click', function () {
                                remove_custom_line(this);
                            }));
                    table.append(row);

                } else {
                    showWarningMessage('Customer can not be duplicated');
                }



            }


            //appending customer groups
            var table = $('#offer_customer_group');
            var tableBody = $('#offer_customer_group tbody');
            var dt = response.supply_group;

            for (var i = 0; i < dt.length; i++) {
               
               
               
                var id = dt[i].customer_group_id;
                  var cus_is_duplicated = validate_customer_group_duplication(dt[i].group);
                  if (!cus_is_duplicated) {
                      var row = $('<tr>').css('height', '15px');
                      row.append($('<td>').append($('<label>').attr('data-id', dt[i].customer_group_id).text(dt[i].group)));
                      row.append($('<td>').append($('<input>').attr({
                          'type': 'checkbox',
                          'class': 'form-check-input',
                          'name': 'select_item_chk',
                          'id': id
                      }).on('change', function () {
                          unset_select_all_cus(this);
                      })));
                      row.append(
                          $('<button type="button" style="border: none; background-color: transparent;">')
                              .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                              .on('click', function () {
                                  remove_custom_line(this);
                              }));

                      table.append(row);

                } else {
                    showWarningMessage('Customer group can not be duplicated');
                }



            }

            //appending threshold data to hashmap
            var threshold_data = response.item;
            
            for (var i = 0; i < threshold_data.length; i++) {
                var threshold_data_array = [];
                var key = undefined;
                for (var j = 0; j < threshold_data[i].length; j++) {
                    console.log();
                    threshold_data_array.push({
                        qty: threshold_data[i][j].quantity,
                        foc: threshold_data[i][j].free_offer_quantity,
                    });
                    key = threshold_data[i][j].key_value;

                }
                
                free_qty_hashMap.set(key, threshold_data_array);
                console.log(free_qty_hashMap);

            }





        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }



    });

}




function add_to_item_hashMap() {
    var added = false;
    var table = $('#offerItemtable');
    var arr = tableDataThreshold.getDataSourceObject();
    var tbl_qty = undefined;
    var is_duplicated_ = false;
    var is_not_numeric_value = false;
    for (var i = 0; i < arr.length; i++) {
        tbl_qty = arr[i][0].val();
        for (var j = i + 1; j < arr.length; j++) {
            if (tbl_qty == arr[j][0].val()) {
                is_duplicated_ = true;
                showWarningMessage('Record duplicated');
                break;
            } else if (arr[j][0].val() == "0" || arr[j][0].val() == 0 || arr[j][0].val() == "" || isNaN(parseFloat(arr[j][0].val()))) {
                showWarningMessage('Qty should be numeric value');
                break
            } else if (arr[j][1].val() == "0" || arr[j][1].val() == 0 || arr[j][1].val() == "" || isNaN(parseFloat(arr[j][1].val()))) {
                showWarningMessage('FOC should be numeric value');
                is_not_numeric_value = true;
                break
            }


        }


    }



    if (!is_duplicated_) {
        
        if (!is_not_numeric_value) {
            
            table.find('tbody tr').each(function () {
                var checkbox = $(this).find('input[type="checkbox"]');

                if (checkbox.prop('checked')) {
                    
                    var checkboxId = checkbox.attr('id');
                    var freeQtyArray = free_qty_hashMap.get(checkboxId) || [];

                    var duplicateFound = false;
                    var newFreeQtyArray = [];

                    for (var i = 0; i < arr.length; i++) {
                       

                        newFreeQtyArray.push({
                            qty: arr[i][0].val(),
                            foc: arr[i][1].val(),
                        });

                    }

                    if(action == 'edit' || action == 'view'){
                        free_qty_hashMap.set(parseInt(checkboxId), newFreeQtyArray);
                        added = true;
                    }else{
                        free_qty_hashMap.set(checkboxId, newFreeQtyArray);
                        added = true
                    }
                   

                }
            });
            triger_tick(table);
        }
    }


    console.log(free_qty_hashMap);
    if(added){
        if($('#btn_apply_threshold').val() == "Update"){
            showSuccessMessage('Record updated')
        }
    }

}


function triger_tick(table) {
    table.find('tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.prop('checked')) {

            $(this).find('td:eq(2) i').removeClass('hide_icon');
        }
    });

}


//read hash map
function read_hashMap(row, free_qty_hashMap) {

    var checkbox = $(row).find('td:eq(3) input[type="checkbox"]');


    if (checkbox.length > 0) {

        var checkboxId = checkbox.attr('id');
        public_checkboxId = checkboxId;
       
       if(action == 'edit' || action == 'view'){
            var freeQtyArray = free_qty_hashMap.get(parseInt(checkboxId));
       }else{
            var freeQtyArray = free_qty_hashMap.get(checkboxId);
       }

        if (freeQtyArray) {
            
            dataSource = [];
            for (var i = 0; i < freeQtyArray.length; i++) {

                console.log("qty:", freeQtyArray[i].qty, "foc:", freeQtyArray[i].foc);
                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": parseInt(freeQtyArray[i].qty), "data_id": "", "style": "width:85px;text-align:right;", "event": "" },
                    { "type": "text", "class": "transaction-inputs", "value": parseInt(freeQtyArray[i].foc), "style": "width:85px;text-align:right;", "event": "" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "delete_from_hashMap(this);removeRow(this);", "width": 30 }

                ]);


            }

            tableDataThreshold.setDataSource(dataSource);
            $('#btn_apply_threshold').val('Update');
        } else {
            
            dataSource = []
            tableDataThreshold.setDataSource(dataSource);
            $('#btn_apply_threshold').val('Apply');
            tableDataThreshold.addRow();
        }


    }

}

//delete from hash map
function delete_from_hashMap(event) {
    var key = public_checkboxId;
    if(action == 'edit' || action == 'view'){
        key = parseInt(public_checkboxId);
    }
    var row = $($(event).parent()).parent();

    var cell = row.find('td');
    var qty_ = parseFloat($(cell[0]).children().eq(0).val());
    var foc_ = parseFloat($(cell[1]).children().eq(0).val());

    var freeQtyArray = free_qty_hashMap.get(key);

    if (freeQtyArray) {
        for (var i = 0; i < freeQtyArray.length; i++) {
            if (freeQtyArray[i].qty == qty_ && freeQtyArray[i].foc == foc_) {
                freeQtyArray.splice(i, 1);
                console.log(freeQtyArray);
                break;
            }
        }

        if (freeQtyArray.length === 0) {
            free_qty_hashMap.delete(key); // Delete the entry if the array is empty
        } else {
            free_qty_hashMap.set(key, freeQtyArray); // Update the hashMap with the modified array

        }
    }

}

//reset hasmap

function reset_hashMap() {
    var table = $('#offerItemtable');
    table.find('tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.prop('checked')) {
            var check_id = checkbox.attr('id');
            free_qty_hashMap.delete(check_id);
            $(this).find('td:eq(2) i').addClass('hide_icon');

        }
    });

    dataSource = []
    tableDataThreshold.setDataSource(dataSource);
    $('#btn_apply_threshold').val('Apply');
    tableDataThreshold.addRow();

}