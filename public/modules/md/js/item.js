
/* ---------------auto complete------------ */

const AutocompleteInputs = function () {



    // Autocomplete
    const _componentAutocomplete = function () {
        if (typeof autoComplete == 'undefined') {
            console.warn('Warning - autocomplete.min.js is not loaded.');
            return;
        }

        // Demo data
        const autocompleteData = loadItemNames();

        // Basic
        const autocompleteBasic = new autoComplete({
            selector: "#txtName",
            data: {
                src: autocompleteData
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: function (event) {
                        const selection = event.detail.selection.value;
                        autocompleteBasic.input.value = selection;
                    }
                }
            }
        });



        // External empty array to save search results
        let history = [];
        const autocompleteRecent = new autoComplete({
            selector: "#autocomplete_recent",
            data: {
                src: autocompleteData
            },
            resultItem: {
                highlight: true
            },
            resultsList: {
                element: (list) => {
                    const recentSearch = history.reverse();
                    const historyLength = recentSearch.length;

                    // Check if there are recent searches
                    if (historyLength) {
                        const historyBlock = document.createElement("li");
                        historyBlock.classList.add('pe-none', 'border-bottom', 'pt-0', 'pb-2', 'mb-2');
                        historyBlock.innerHTML = '<div class="fw-semibold">Recent Searches</div>';
                        // Limit displayed searched to only last "2"
                        recentSearch.slice(0, 2).forEach((item) => {
                            const recentItem = document.createElement("div");
                            recentItem.classList.add('text-muted', 'mt-2')
                            recentItem.innerHTML = item;
                            historyBlock.append(recentItem);
                        });


                        list.prepend(historyBlock);
                    }
                }
            },
            events: {
                input: {
                    selection(event) {
                        const feedback = event.detail;
                        const input = autocompleteRecent.input;
                        // Get selected Value
                        const selection = feedback.selection.value;
                        // Add selected value to "history" array
                        history.push(selection);

                        autocompleteRecent.input.value = selection;
                    }
                }
            }
        });


    };



    // Return objects assigned to module


    return {
        init: function () {
            _componentAutocomplete();
        }

    }
}();

document.addEventListener('DOMContentLoaded', function () {
    AutocompleteInputs.init();
});

/* -------------end of auto complete------ */

var formData = new FormData();
var term = [];
$(document).ready(function () {
    $('.select2').select2();


    $('.select-multiple-search-disabled').select2();

    $('.select-multiple-search-disabled').on('select2:opening select2:closing', function (event) {
        const $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    });
    getSupplyGroupId();
    getCategoryLevel_1();
    getCategoryLevel_2(1);
    getCategoryLevel_3(1);
    getIIN();
    loadPamentTerm();

   /*  $('#cmbPaymentTerm').on('focus',function(){
        if($(this).prop('disbaled')){
            showWarningMessage("Please enable allow payment term");
        }
    }); */
    $('#cmbPaymentTerm').prop('disabled', true);
    $('#chkAllowPaymentTerm').on('change', function() {
        if (!$(this).prop('checked')) {
            $('#cmbPaymentTerm').prop('disabled', true);
        } else {
            $('#cmbPaymentTerm').prop('disabled', false);
        }
    });
    
    $('#txtnote').on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    $('#frmItem').trigger("reset");

    $("#txtItemCode").on("keydown", function (event) {
        if (event.keyCode === 32) {
            return false;
        }
    });

    $('#cmbCategoryLevel1').change(function () {
        var cat_lvl_one_type = $(this).val();
        getCategoryLevel_2(cat_lvl_one_type);

    });

    $('#cmbCategoryLevel2').change(function () {
        var cat_lvl_two_type = $(this).val();
        getCategoryLevel_3(cat_lvl_two_type);
    })



    $('#txtName').on('input', function () {
        loadItemNames();
    });


    //loadItemNames


    // extracting IDs from url
    var ItemID;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var ItemID = param[0].split('=')[1].split('&')[0];
        action = param[0].split('=')[2].split('&')[0];

        if (action == 'edit') {
            $('#btnSave').text('Update');
        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnReset').hide();
        }
        getEachItem(ItemID);


    }



    //tabs
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    //reset form
    $('#btnReset').on('click', function () {
        $('.validation-invalid-label').empty();
        $('#frmItem').trigger('reset');
    });


    //form save
    $('#btnSave').on('click', function (event) {
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
                console.log(result);
                if (result) {
                    if ($('#btnSave').text() == 'Save') {
                        addItem();
                    } else if ($('#btnSave').text() == 'Update') {
                        updateItem(ItemID);
                        closeCurrentTab();
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


    $('#frmItem').submit(function (e) {
        e.preventDefault();

    });


});

//add new item
function addItem() {
    if ($('#txtPackageUnit').val().length < 1) {
        showWarningMessage("Please enter package unit");
        $("#txtPackageUnit").addClass("is-invalid");
        return;
    }else if($('#txtItemCode').val().length < 1){
        showWarningMessage("Item code is required");
        $("#txtItemCode").addClass("is-invalid");
        return;
    }else if($('#cmbSupplyGroup option:selected').text() == "Not Applicable"){
        showWarningMessage("Supply group is required");
        $("#cmbSupplyGroup").addClass("is-invalid");
        return;
    } else {
        $('#btnSave').prop('disabled', true);
        var chkActive = $('#chkActive').is(":checked") ? 1 : 0;
        var chkManageBatch = $('#chkManageBatch').is(":checked") ? 1 : 0;
        var chkManageExpireDate = $('#chkManageExpireDate').is(":checked") ? 1 : 0;
        var chkAllowedFreeQuantity = $('#chkAllowedFreeQuantity').is(":checked") ? 1 : 0;
        var chkAllowedDiscount = $('#chkAllowedDiscount').is(":checked") ? 1 : 0;
        var chlAllowedPromotion = $('#chlAllowedPromotion').is("checked") ? 1 : 0;
        var PaymentTerms = undefined;
        if($('#chkAllowPaymentTerm').prop('checked')){
            PaymentTerms = $('#cmbPaymentTerm').val();
            formData.append('PaymentTerms',PaymentTerms);
        }



        formData.append('txtItemCode', $('#txtItemCode').val());
        formData.append('txtName', $('#txtName').val());
        formData.append('txtSKU', $('#txtSKU').val());
        formData.append('txtBarcode', $('#txtBarcode').val());
        formData.append('txtUnitOfMeasure', $('#txtUnitOfMeasure').val());
        formData.append('txtPackageSize', $('#txtPackageSize').val());
        formData.append('txtPackageUnit', $('#txtPackageUnit').val());
        formData.append('txtStorageRequirements', $('#txtStorageRequirements').val());
        formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());
        formData.append('cmbCategoryLevel1', $('#cmbCategoryLevel1').val());
        formData.append('cmbCategoryLevel2', $('#cmbCategoryLevel2').val());
        formData.append('cmbCategoryLevel3', $('#cmbCategoryLevel3').val());
        formData.append('chkActive', chkActive);
        formData.append('txtMinimumOrderQquantity', $('#txtMinimumOrderQquantity').val());
        formData.append('txtMaximumOrderQuantity', $('#txtMaximumOrderQuantity').val());
        formData.append('txtReorderLevel', $('#txtReorderLevel').val());
        formData.append('txtReorderQuantity', $('#txtReorderQuantity').val());
        formData.append('chkManageBatch', chkManageBatch);
        formData.append('chkManageExpireDate', chkManageExpireDate);
        formData.append('chkAllowedFreeQuantity', chkAllowedFreeQuantity);
        formData.append('chkAllowedDiscount', chkAllowedDiscount);
        formData.append('txtnote', $('#txtnote').val());

        formData.append('txtDescription', $('#txtDescription').val());
        formData.append('cmbInn', $('#cmbInn').val());
        formData.append('txtWholeSalePrice', $('#txtWholeSalePrice').val());
        formData.append('txtRetailPrice', $('#txtRetailPrice').val());
        formData.append('txtAverageCostPrice', $('#txtAverageCostPrice').val());
        if($('#txtMinimum_margin').val().length < 1){
            formData.append('txtMinimum_margin',0);
        }else{
            formData.append('txtMinimum_margin',$('#txtMinimum_margin').val());

        }

        
       
        /* formData.append('') Picture */
        formData.append('chlAllowedPromotion', chlAllowedPromotion);

        $.ajax({
            url: '/md/addItem',
            method: 'post',
            enctype: 'multipart/form-data',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                $('#btnSave').prop('disabled', true);
            }, success: function (response) {
                $('#btnSave').prop('disabled', false);
                var status = response.status
                var itemID = response.primaryKey;
                if (status) {
                    showSuccessMessage("Successfully saved");
                    // addPrice(itemID);
                  //
                    $('#frmItem')[0].reset();
                   // url = "/md/itemList";
                  //  window.location.href = url;
                } else {
                    console.log(response);
                    showErrorMessage("Something went wrong");
                }

            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
                $('#btnSave').prop('disabled', false);
            }
        });

    }




}


/* function addPrice(id){

    formData.append('txtPriceDescription',$('#txtPriceDescription').val());
    formData.append('txtWholeSalePrice',$('#txtWholeSalePrice').val());
    formData.append('txtRetailPrice',$('#txtRetailPrice').val());

    $.ajax({
        url: '/addPrice/'+id,
        method: 'post',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnSave').prop('disabled', true);
        }, success: function (response) {
            $('#btnSave').prop('disabled', false);
            var status = response.status
            if (status) {
                showSuccessMessage("Successfully saved");
                $('#frmItem')[0].reset();
            } else {
                console.log(response);
                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }

    })

} */


//getting supply group to select 2 tag
function getSupplyGroupId() {
    $.ajax({
        url: '/md/getSupplyGroup',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbSupplyGroup').append('<option value="' + item.supply_group_id + '">' + item.supply_group + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    })
}



//getting category_level_1 to select 2 tag
function getCategoryLevel_1() {
    $.ajax({
        url: '/md/getCategoryLevelOne',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbCategoryLevel1').append('<option value="' + item.item_category_level_1_id + '">' + item.category_level_1 + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    })
}


//getting category_level_2 to select 2 tag
function getCategoryLevel_2(Cat_lvl_1_id) {
    $('#cmbCategoryLevel2').empty();
    $.ajax({
        url: '/md/getCategoryLevelTwo/' + Cat_lvl_1_id,
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbCategoryLevel2').append('<option value="' + item.Item_category_level_2_id + '">' + item.category_level_2 + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    })
}



//getting category_level_3 to select 2 tag
function getCategoryLevel_3(cat_lvl_2_id) {
    $('#cmbCategoryLevel3').empty();
    $.ajax({
        url: '/md/getCategoryLevelThree/' + cat_lvl_2_id,
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbCategoryLevel3').append('<option value="' + item.Item_category_level_3_id + '">' + item.category_level_3 + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    })
}



//update item
function updateItem(id) {
    var chkActive = $('#chkActive').is(":checked") ? 1 : 0;
    var chkManageBatch = $('#chkManageBatch').is(":checked") ? 1 : 0;
    var chkManageExpireDate = $('#chkManageExpireDate').is(":checked") ? 1 : 0;
    var chkAllowedFreeQuantity = $('#chkAllowedFreeQuantity').is(":checked") ? 1 : 0;
    var chkAllowedDiscount = $('#chkAllowedDiscount').is(":checked") ? 1 : 0;
    var chlAllowedPromotion = $('#chlAllowedPromotion').is("checked") ? 1 : 0;
    if($('#chkAllowPaymentTerm').prop('checked')){
        PaymentTerms = $('#cmbPaymentTerm').val();
        formData.append('PaymentTerms',PaymentTerms);
    }

    formData.append('txtItemCode', $('#txtItemCode').val());
    formData.append('txtName', $('#txtName').val());
    formData.append('txtSKU', $('#txtSKU').val());
    formData.append('txtBarcode', $('#txtBarcode').val());
    formData.append('txtUnitOfMeasure', $('#txtUnitOfMeasure').val());
    formData.append('txtPackageSize', $('#txtPackageSize').val());
    formData.append('txtPackageUnit', $('#txtPackageUnit').val());
    formData.append('txtStorageRequirements', $('#txtStorageRequirements').val());
    formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());
    formData.append('cmbCategoryLevel1', $('#cmbCategoryLevel1').val());
    formData.append('cmbCategoryLevel2', $('#cmbCategoryLevel2').val());
    formData.append('cmbCategoryLevel3', $('#cmbCategoryLevel3').val());
    formData.append('chkActive', chkActive);
    formData.append('txtMinimumOrderQquantity', $('#txtMinimumOrderQquantity').val());
    formData.append('txtMaximumOrderQuantity', $('#txtMaximumOrderQuantity').val());
    formData.append('txtReorderLevel', $('#txtReorderLevel').val());
    formData.append('txtReorderQuantity', $('#txtReorderQuantity').val());
    formData.append('chkManageBatch', chkManageBatch);
    formData.append('chkManageExpireDate', chkManageExpireDate);
    formData.append('chkAllowedFreeQuantity', chkAllowedFreeQuantity);
    formData.append('chkAllowedDiscount', chkAllowedDiscount);
    formData.append('txtnote', $('#txtnote').val());

    formData.append('txtDescription', $('#txtDescription').val());
    formData.append('cmbInn', $('#cmbInn').val());
    formData.append('txtWholeSalePrice', $('#txtWholeSalePrice').val());
    formData.append('txtRetailPrice', $('#txtRetailPrice').val());
    formData.append('txtAverageCostPrice', $('#txtAverageCostPrice').val());
    if($('#txtMinimum_margin').val().length < 1){
        formData.append('txtMinimum_margin',0);
    }else{
        formData.append('txtMinimum_margin',$('#txtMinimum_margin').val());

    }

    /* formData.append('') Picture */
    formData.append('chlAllowedPromotion', chlAllowedPromotion);


    $.ajax({
        url: '/md/updateItem/' + id,
        method: 'post',
        enctype: 'multipart/form-data',
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
            var status = response.status;
            if (status) {
                showSuccessMessage("Successfully updated");
                window.opener.location.reload();
            } else {
                showErrorMessage("Something went wrong");
            }
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}


//get each item details to update or view
function getEachItem(id) {

    $.ajax({
        url: '/md/geteachItem/' + id,
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

        }, success: function (ItemData) {
            var res = ItemData.data;
            /* console.log(res); */
            $('#txtName').val(res.item_Name);
            $('#txtSKU').val(res.sku);
            $('#txtBarcode').val(res.barcode);
            $('#txtUnitOfMeasure').val(res.unit_of_measure);
            $('#txtPackageSize').val(res.package_size);
            $('#txtPackageUnit').val(res.package_unit);
            $('#txtStorageRequirements').val(res.storage_requirements);
            $('#cmbCategoryLevel1').val(res.category_level_1_id);
            $('#cmbCategoryLevel2').val(res.category_level_2_id);
            $('#cmbCategoryLevel3').val(res.category_level_3_id);
            $('#txtMinimumOrderQquantity').val(res.minimum_order_quantity);
            $('#txtMaximumOrderQuantity').val(res.maximum_order_quantity);
            $('#txtReorderLevel').val(res.reorder_level);
            $('#txtReorderQuantity').val(res.reorder_quantity);
            $('#chkStatus').prop('checked', res.status == 1);
            $('#chkManageBatch').prop('checked', res.manage_batch == 1);
            $('#chkManageExpireDate').prop('checked', res.manage_expire_date == 1);
            $('#chkAllowedFreeQuantity').prop('checked', res.allowed_free_quantity == 1);
            $('#chkAllowedDiscount').prop('checked', res.allowed_discount == 1);
            $('#cmbSupplyGroup').val(res.supply_group_id);
            $('#txtItemCode').val(res.Item_code);
            $('#txtnote').val(res.note);
            $('#txtDescription').val(res.item_description);
            $('#cmbInn').val(res.item_altenative_name_id).trigger('change');
            $('#txtWholeSalePrice').val(res.whole_sale_price);
            $('#txtRetailPrice').val(res.retial_price);
            $('#txtAverageCostPrice').val(res.average_cost_price);
            $('#chkActive').prop('checked', res.is_active == 1);
            $('#chlAllowedPromotion').prop('checked', res.allowed_promotion == 1);
            $('#txtMinimum_margin').val(res.minimum_margin);
            var term_ = ItemData.terms;
            //$('#cmbPaymentTerm').val()
            if(ItemData.terms){
                $('#cmbPaymentTerm').val(term_.payment_term_id);
            }
                
               
               
           
           




        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });

}


/* function getEachItemPrice(id){

    
     $.ajax({
        url: '/geteachItemPrice/' + id,
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

        }, success: function (ItemData) {
            var res = ItemData.data;
            console.log(ItemData);


            $('#txtWholeSalePrice').val(res.whole_sale_price);
            $('#txtPriceDescription').val(res.description);
            $('#txtWholeSalePrice').val(res.wholesale_price);
            $('#txtRetailPrice').val(res.retail_price);
          
        
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    }); 

}
 */

//load names to item name text box
function loadItemNames() {
    var result = [];
    var data = $('#txtName').val();

    $.ajax({
        url: '/md/searchItemNames',
        method: 'GET',
        data: { data: data },
        success: function (data) {
            console.log(data);
            $.each(data, function (index, value) {

                result.push(value.item_Name);

            })

        }

    });
    console.log(result);
    return result;

}


function getIIN() {
    $.ajax({
        url: '/md/getInn',
        method: 'GET',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbInn').append('<option value="' + value.item_altenative_name_id + '">' + value.item_altenative_name + '</option>');

            })

        },

    })
}


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);
}


function loadPamentTerm() {
    $.ajax({
        url: '/sd/loadPamentTerm',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                //$('#cmbPaymentTerm').append('<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>');
                var optionHtml = '<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>';
                $('#cmbPaymentTerm').append(optionHtml);

                // Push the option HTML string into the options array
                term.push(optionHtml);
                
            })

        },
        error: function (error) {
            console.log(error);
        },

    })

}