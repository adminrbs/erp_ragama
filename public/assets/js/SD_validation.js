//validating all transaction when inserting 
function _validation(code,id){
    var bool = false;
    var code_len = $(code).val().length;
    var id_ = $(id).attr('data-id');
    //looping select tags and validate
    $("select").each(function() {
        var selectedItem = $(this).find(':selected').text();
        if (selectedItem == "Not Applicable" || selectedItem == undefined || selectedItem == "") {
            var selectId = $(this).attr('id');
            if(selectId != "cmbPaymentTerm"){
                $(this).addClass('is-invalid');
                bool = true
            }
           
        }
        
    });
    //looping date time pickers to validate
    $("input[type='date']").each(function() {
        var date = $(this).val();
        var parsedDate = new Date(date);

        // Check if the parsed date is valid
        if (isNaN(parsedDate.getTime())) {
            bool = true;
            $(this).addClass('is-invalid');
        } 
        
    });
    

    //validating supplier or customer pop up search
    if(code_len < 1 || id_ == undefined){
        $(code).addClass('is-invalid');
        bool = true;
    }

    //checking purchasing order txt box and validate
    /* if ($("input#txtPurchaseORder").length > 0) {
        
        var PO_id = $('#txtPurchaseORder').val().length;
       if(PO_id <= 0){
        $('#txtPurchaseORder').addClass('is-invalid');
            bool = true;

       } 
    } */

    //checking supplier invoice txt box and validate
    if ($("input#txtSupplierInvoiceNumber").length > 0) {
        
        var PO_id = $('#txtSupplierInvoiceNumber').val().length;
       if(PO_id <= 0){
        $('#txtSupplierInvoiceNumber').addClass('is-invalid');
            bool = true;

       } 
    }
    
    return bool;
}



//adding is-valid class when select item
function validateSelectTag(cmb){
    if($(cmb).find(':selected').text() != "Not Applicable"){
        $(cmb).removeClass('is-invalid');
        $(cmb).addClass('is-valid');
    }
   
}
