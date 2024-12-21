

const DatatableFixedColumns = function () {


    //
    // Setup module components
    //

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
        var table = $('#goodreturnviewtable').DataTable({
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
                    width: 300,
                    targets: 1
                },
                {
                    width: 50,
                    targets: [2]
                },
                {
                    width: 50,
                    targets: 3
                },
                {
                    width: 50,
                    targets: 4
                },
                {
                    width: 100,
                    targets: 5
                },


            ],
            scrollX: true,
            //scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "itemCode" },
                { "data": "name" },
                { "data": "qty" },
                { "data": "foc" },
                { "data": "uom" },
                //{ "data": "packagesize" },
                { "data": "packsize" },
                { "data": "price" },
                { "data": "Disc" },
                //{ "data": "Discamount"},
                { "data": "value" },
                { "data": "batch" },
                { "data": "aviQty" },
                { "data": "setofqty" },
              

                                              

                

            ], "stripeClasses": ['odd-row', 'even-row'],
        });


        //
        // Fixed column with complex headers
        //

    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});


var tax=0;
var netTotal=0;
var totalDiscount=0;
var headerDiscountAmount=0;
var tableDiscount=0;
var grossTotal=0;
var task;
var suppliers = [];
var GRNID = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;

$(document).ready(function () {

    getBranches()
    getLocation()
    
    var hiddem_col_array = [5,9];
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
      /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        GRNID = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
    }

    getEachGR_rtn(GRNID, status);
        getEachproduct(GRNID);


        $('#btnBack').on('click',function(){
            if(task == "approval"){
               
            }else{
                var url = "/prc/goodReceiveReturnList"; 
                window.location.href = url;
            }
           
    
        });
});


function getEachGR_rtn(id, status) {
   
    /* formData.append('status', status); */
    $.ajax({
        url: '/prc/getEachGR_return/' + id + '/' + status,
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
            console.log(status);
        }, success: function (PurchaseRequestData) {
            console.log(PurchaseRequestData);
            var res = PurchaseRequestData.data;
            /*  getLocation(res[0].branch_id);  */
            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#goods_received_date_time').val(res[0].goods_received_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(res[0].location_id);
            $('#txtSupplier').val(res[0].supplier_code);
            $('#txtSupplier').attr('data-id',res[0].supplier_id);
            $('#lblSupplierName').text(res[0].supplier_name);
            $('#lblSupplierAddress').val(res[0].primary_address);
           /*  $('#txtPurchaseORder').val(res[0].purchase_order_id); */
            $('#txtSupplierInvoiceNumber').val(res[0].supppier_invoice_number);
          /*   $('#cmbPaymentType').val(res[0].payment_mode_id);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#txtAdjustmentAmount').val(res[0].adjustment_amount); */
            $('#txtRemarks').val(res[0].remarks);
            $('#txtYourReference').val(res[0].your_reference_number);
            /* $('#dtPaymentDueDate').val(res[0].payment_due_date); */
          /*   $('#dtPaymentDueDate').val(res[0].payment_due_date); */

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of GRN
function getEachproduct(id) {
    $.ajax({
        url: '/prc/getEachproductofGR_rtn/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (response) {

          
             
            calculateTotals(response);
            $('#lblGrossTotal').text(parseFloat(Math.abs(grossTotal)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#lblTotalDiscount').text(parseFloat(totalDiscount).toFixed(2));
            //$('#lblTotaltax').text(parseFloat(tax).toLocaleString()); // Uncomment this line if you have a tax element
            $('#lblNetTotal').text(parseFloat(Math.abs(netTotal)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            console.log("response",grossTotal);

            var dt = response

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var quantity =  parseFloat(dt[i].quantity);
                if(isNaN(quantity)){
                    quantity=0
                }
                var price = parseFloat(dt[i].price);
                if(isNaN(price)){
                    price=0
                }
                var discountAmount = parseFloat(dt[i].discount_amount);
                if(isNaN(discountAmount)){
                    discountAmount=0
                }
                var discountpres = parseFloat(dt[i].discount_percentage);
                if(isNaN(discountpres)){
                    discountpres=0
                }
               

                var value = (Math.abs(quantity) * price) - discountAmount;

                data.push({

                    "itemCode": dt[i].Item_code,
                    "name": dt[i].item_name,
                    "qty": Math.abs(dt[i].quantity),
                    "foc": Math.abs(dt[i].free_quantity),
                    "uom": dt[i].unit_of_measure,
                    //"packagesize":dt[i].package_size,
                    "packsize": dt[i].package_unit,
                    "price": dt[i].price,
                    "Disc": dt[i].discount_percentage,
                    //"Discamount": dt[i].discount_amount,
                    "value": parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "batch": dt[i].batch_number,
                    "aviQty": dt[i].batch_number,
                    "setofqty": dt[i].batch_number,
                   




                });
            }


            var table = $('#goodreturnviewtable').DataTable();
            table.clear();
            table.rows.add(data).draw();
          
          
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}

function calculateTotals(dt) {
    
    for (var i = 0; i < dt.length; i++) {

        var quantity = parseFloat(dt[i].quantity);
        if(isNaN(quantity)){
            quantity=0
        }
        var price = parseFloat(dt[i].price);
        if(isNaN(price)){
            price=0
        }
        var discountAmount = parseFloat(dt[i].discount_amount);
        if(isNaN(discountAmount)){
            discountAmount=0
        }
        var discountpres = parseFloat(dt[i].discount_percentage);
        if(isNaN(discountpres)){
            discountpres=0
        }

       

        var discount_amount = (quantity * price * discountpres) / 100;
        grossTotal += quantity * price;
        tableDiscount += discount_amount;
    }
    if (isNaN(headerDiscountAmount)) {
        headerDiscountAmount = 0;
    }


    totalDiscount = headerDiscountAmount + tableDiscount;
    netTotal = grossTotal - totalDiscount + tax;

   
}





//loading branches
function getBranches() {
    $.ajax({
        url: '/prc/getBranches_view',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })

        },
    })
}

//loading location
function getLocation() {

    $.ajax({
        url: '/prc/getviewLocation/',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })

        },
    })
}