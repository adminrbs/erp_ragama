

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
        var table = $('#purchesorderview').DataTable({
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
                    className: 'dt-body-right', 
                    targets: 5
                },
                {
                    width: 100,
                    className: 'dt-body-right', 
                    targets: 6
                },
                {
                    width: 100,
                    className: 'dt-body-right', 
                    targets: 7
                },
                {
                    width: 100,
                    className: 'dt-body-right', 
                    targets: 8
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
                { "data": "add" },
                { "data": "uom" },
                
                //{ "data": "packagesize" },
              /*   { "data": "packsize" }, */
                { "data": "price" },
                { "data": "Disc" },
                //{ "data": "Discamount"},
                { "data": "value" }




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
var PO_id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
var pickOrderStatus = false;
$(document).ready(function () {
    getDeliveryTypes()
    getLocation()
    getBranches()
    loadPamentType()

    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        PO_id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];






    }


    getEachPO(PO_id, status);
    getEachproduct(PO_id, status);

    $('#btnBack').on('click', function () {
        if (task == "approval") {
            var url = "/prc/purchaseOrderApprovalList";
            window.location.href = url;
        } else {
            var url = "/prc/purchaseOrderList";
            window.location.href = url;
        }


    });
});





function getEachPO(id, status) {


    /* formData.append('status', status); */
    $.ajax({
        url: '/prc/getEachPO/' + id + '/' + status,
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

             headerDiscountAmount = res[0].discount_amount;

            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#LblexternalNumber').attr('data-id', res[0].internal_number);
            $('#purchase_order_date_time').val(res[0].purchase_order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#txtSupplier').attr('data-id', res[0].supplier_id);
            $('#txtSupplier').val(res[0].supplier_code);
            $('#lblSupplierName').text(res[0].supplier_name);
            $('#lblSupplierAddress').val(res[0].primary_address);
            $('#cmbPaymentType').val(res[0].payment_mode_id);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbDeliveryType').val(res[0].deliver_type_id);
            $('#txtRemarks').val(res[0].remarks);
            $('#deliveryDate').val(res[0].deliver_date_time);
            $('#txtDeliveryInst').val(res[0].delivery_instruction);
            $('#txtYourReference').val(res[0].your_reference_number);

           

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

/*
$('#lblGrossTotal').text(parseFloat(grossTotal)
                  $('#lblTotalDiscount').text(parseFloat(totalDiscount)
                  $('#lblTotaltax').text(parseFloat(tax.toLocaleString
                  $('#lblNetTotal').text(parseFloat(netTotal)
                 
                 */

//get each product of PO
function getEachproduct(id, status) {
    $.ajax({
        url: '/prc/getEachproductofPO/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            calculateTotals(response);
            $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#lblTotalDiscount').text(parseFloat(totalDiscount).toFixed(2));
            //$('#lblTotaltax').text(parseFloat(tax).toLocaleString()); // Uncomment this line if you have a tax element
            $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            console.log("response",grossTotal);

            var dt = response

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var quantity = parseFloat(dt[i].quantity);
                var price = parseFloat(dt[i].price);
                var discountAmount = parseFloat(dt[i].discount_amount);
                var discountpres = parseFloat(dt[i].discount_percentage);
                var cst = parseFloat(dt[i].cost_price);
               

                var value = (quantity * cst) - discountAmount;

                data.push({

                    "itemCode": dt[i].Item_code,
                    "name": dt[i].item_name,
                    "qty": '<div style="text-align:right;">'+parseInt(dt[i].quantity)+'</div>',
                    "foc": '<div style="text-align:right;">'+parseInt(dt[i].free_quantity)+'</div>',
                    "uom": dt[i].unit_of_measure,
                    "add":'<div style="text-align:right;">'+dt[i].additional_bonus+'</div>',
                    //"packagesize":dt[i].package_size,
                   /*  "packsize": dt[i].package_unit, */
                    "price": '<div style="text-align:right;">'+parseFloat(cst).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+'</div>',
                    "Disc": '<div style="text-align:right;">'+dt[i].discount_percentage+'</div>',
                    //"Discamount": dt[i].discount_amount,
                    "value": '<div style="text-align:right;">'+parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+'</div>',


                });
            }


            var table = $('#purchesorderview').DataTable();
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
        var price = parseFloat(dt[i].cost_price);
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

function getDeliveryTypes() {
    $.ajax({
        url: '/prc/getDeliveryTypes',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbDeliveryType').append('<option value="' + value.delivery_type_id + '">' + value.delivery_type_name + '</option>');

            })

        },
    })
}
//load payment type
function loadPamentType() {
    $.ajax({
        url: '/prc/loadPamentType',
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentType').append('<option value="' + value.supplier_payment_method_id + '">' + value.supplier_payment_method + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })


}


