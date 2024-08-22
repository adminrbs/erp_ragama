/* --------------------------------------Data table--------------------------------------------     
                        

                    *****All Apply to data tables are in freeoffer.js******            

--------------------------------------------------------------------------------------------*/


/* -------------------------------------List box-------------------------------------------- */
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
            addButtonText: "<i class='ph-caret-right'></i>",
            removeButtonText: "<i class='ph-caret-left'></i>",
            addAllButtonText: "<i class='ph-caret-double-right'></i>",
            removeAllButtonText: "<i class='ph-caret-double-left'></i>",

        });

        listBox = listboxButtons;
       
    };

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

document.addEventListener('DOMContentLoaded', function () {
    DualListboxes.init();
});

var ApplyTotext_value_id;
$(document).ready(function () {
    
    //setting value to filter byx combo box
    $('#offer_applying_modal').on('show.bs.modal', function() {
        if(ApplyTotext_value == "Locations"){
            ApplyTotext_value_id = 1
        }else if(ApplyTotext_value == "Customer"){
            ApplyTotext_value_id = 2
        }else if(ApplyTotext_value == "Customer grade"){
            ApplyTotext_value_id = 3
        }else if(ApplyTotext_value == "Customer group"){
            ApplyTotext_value_id = 4
        }
        $('#cmbFilterByOffer').val(ApplyTotext_value_id);
        getFilteredData(ApplyTotext_value_id);
        
    })

    //delete offer customer
    $('#btnCusOfferDelete').on('click',function(){
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
                    deleteofferCustomer();
                } else {
    
                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
        
    })
    //delete location offer
    $('#btnLocationOfferdelete').on('click',function(){
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
                    deleteOfferLocation();
                } else {
    
                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

    })

    //delete customer grade
    $('#btnOffer_cus_grade_delete').on('click',function(){
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
                    deleteOfferCustomerGrade();
                } else {
    
                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
        

    })

    //delte offer customer group
    $('#btnDeleteOfferCusGroup').on('click',function(){
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
                    DeleteOfferCusGroup();
                } else {
    
                }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

    })
    
      //calling add applyTo function
    $('#btnAddApplyTo').on('click',function(){
    
        addApplyTo(OfferID,ApplyTotext_value_id);
    })


});


//getting data from DB and appending to list box
 function getFilteredData(filterBy) {
    const dualListBox = document.getElementById('cmbFilterData');

    $.ajax({
        url: '/sd/getOptions/' + filterBy,
        method: 'get',
        datatype: 'json',
        success: function (data) {

            //make an empty array if array has data 
            if (DualListboxes.geOptionArray().length > 0) {
                DualListboxes.clear();
            }

            $.each(data, function (index, item) {
                if (filterBy == 1) {

                    DualListboxes.geOptionArray().push({ text: item.location_name, value: item.location_id });

                } else if (filterBy == 2) {

                    DualListboxes.geOptionArray().push({ text: item.customer_name, value: item.customer_id });

                } else if(filterBy == 3){

                    DualListboxes.geOptionArray().push({ text: item.grade, value: item.customer_grade_id });

                } else if(filterBy == 4){

                    DualListboxes.geOptionArray().push({ text: item.group, value: item.customer_group_id });

                }

            });
            $('.cmbOfferFilterData').remove();
            DualListboxes.init();

        }, error: function (data) {
            console.log(data)
        }

    });


}


function addApplyTo(id,ApplyTotext_value_id){
     console.log(DualListboxes.getSelectedOptions()); 
        $.ajax({   
            url: '/sd/addApplyTo/'+id,
            method: 'POST',
            enctype: 'multipart/form-data',
            data: {
                ApplyTotext_value_id: ApplyTotext_value_id,
                option_array: JSON.stringify(DualListboxes.getSelectedOptions())
            },
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
                    getAllOfferCustomerSData(OfferID);
                    getAllOfferLocationData(OfferID);
                    getAllCustomerGradeOfferData(OfferID);
                    getAllCustomerGroupOfferData(OfferID);
                    $('#offer_applying_modal').modal('hide');
                

                } else {
                    showErrorMessage("Something went wrong");
                    

                }

            }, error: function (data) {

            }, complete: function () {
                /* $('#btnSave').prop('disabled', false); */
            }
        })
    }


function getAllOfferCustomerSData(id){
    $.ajax({
        type: "GET",
        url: "/sd/getAllOfferCustomerSData/"+id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                data.push({
                    "free_offer_customer_id": dt[i].free_offer_customer_id,
                    "Select": '<input class="form-check-input" type="checkbox" name="record[]" value="' + dt[i].free_offer_customer_id + '">',  
                    "Offer Name": dt[i].name,
                    "Customer Name": dt[i].customer_name       
                    
                });
            }

            var table = $('#free_offer_customer_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}


function deleteofferCustomer(){
    var selectedRecords = [];
    $('input[name="record[]"]:checked').each(function () {
        selectedRecords.push($(this).val());
    });

    if (selectedRecords.length === 0) {
        alert('No records selected.');
        return;
    }
    console.log(selectedRecords);
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/sd/deleteofferCustomer', // Replace with your Laravel route URL
        type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token // Include the CSRF token in the headers
        },
        data: {
            records: selectedRecords
        },
        success: function (response) {
            if(response.status){
                
                showSuccessMessage("Successfully saved");

            }else{
                showErrorMessage("Something went wrong");
            }
            getAllOfferCustomerSData(OfferID); // Refresh the table after deletion
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}


function getAllOfferLocationData(id){
    $.ajax({
        type: "GET",
        url: "/sd/getAllOfferLocationData/"+id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                data.push({
                    "free_offer_location_id": dt[i].free_offer_location_id,
                    "Select": '<input class="form-check-input" type="checkbox" name="record_offerLocation[]" value="' + dt[i].free_offer_location_id + '">',  
                    "Offer Name": dt[i].name,
                    "Location Name": dt[i].location_name
                          
                });

            }

            var table = $('#free_offer_location_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function deleteOfferLocation(){
    var selectedRecords = [];
    $('input[name="record_offerLocation[]"]:checked').each(function () {
        selectedRecords.push($(this).val());
    });

    if (selectedRecords.length === 0) {
        alert('No records selected.');
        return;
    }
    console.log(selectedRecords);
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/sd/deleteOfferLocation', // Replace with your Laravel route URL
        type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token // Include the CSRF token in the headers
        },
        data: {
            records: selectedRecords
        },
        success: function (response) {
            if(response.status){
                
                showSuccessMessage("Successfully saved");

            }else{
                showErrorMessage("Something went wrong");
            }
            getAllOfferLocationData(OfferID); // Refresh the table after deletion
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function getAllCustomerGradeOfferData(id){
    $.ajax({
        type: "GET",
        url: "/sd/getAllCustomerGradeOfferData/"+id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                data.push({
                    "free_offer_customer_grade_id": dt[i].free_offer_customer_grade_id,
                    "Select": '<input class="form-check-input" type="checkbox" name="record_offerCusGrade[]" value="' + dt[i].free_offer_customer_grade_id + '">',  
                    "Offer Name": dt[i].name,
                    "grade": dt[i].grade
                          
                });

            }

            var table = $('#free_offer_customer_grade_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function deleteOfferCustomerGrade(){
    var selectedRecords = [];
    $('input[name="record_offerCusGrade[]"]:checked').each(function () {
        selectedRecords.push($(this).val());
    });

    if (selectedRecords.length === 0) {
        alert('No records selected.');
        return;
    }
    console.log(selectedRecords);
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/sd/deleteOfferCusGrade', // Replace with your Laravel route URL
        type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token // Include the CSRF token in the headers
        },
        data: {
            records: selectedRecords
        },
        success: function (response) {
            if(response.status){
                
                showSuccessMessage("Successfully saved");

            }else{
                showErrorMessage("Something went wrong");
            }
            getAllCustomerGradeOfferData(OfferID); // Refresh the table after deletion
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });

}

function getAllCustomerGroupOfferData(id){
    $.ajax({
        type: "GET",
        url: "/sd/getAllCustomerGroupOfferData/"+id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                data.push({
                    "free_offer_customer_group_id": dt[i].free_offer_customer_group_id,
                    "Select": '<input class="form-check-input" type="checkbox" name="record_offerCusGroup[]" value="' + dt[i].free_offer_customer_group_id + '">',  
                    "Offer Name": dt[i].name,
                    "group": dt[i].group
                          
                });

            }

            var table = $('#free_offer_customer_group_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function DeleteOfferCusGroup(){
    var selectedRecords = [];
    $('input[name="record_offerCusGroup[]"]:checked').each(function () {
        selectedRecords.push($(this).val());
    });

    if (selectedRecords.length === 0) {
        alert('No records selected.');
        return;
    }
    console.log(selectedRecords);
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/sd/DeleteOfferCusGroup', // Replace with your Laravel route URL
        type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token // Include the CSRF token in the headers
        },
        data: {
            records: selectedRecords
        },
        success: function (response) {
            if(response.status){
                
                showSuccessMessage("Successfully saved");

            }else{
                showErrorMessage("Something went wrong");
            }
            getAllCustomerGroupOfferData(OfferID); // Refresh the table after deletion
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}










