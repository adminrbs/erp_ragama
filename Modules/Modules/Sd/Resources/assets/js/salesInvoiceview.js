

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
        var table = $('#salesinvoicetable').DataTable({
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
                {
                    width: 100,
                    className: 'dt-body-right', 
                    targets: [6] 
                },
                {
                    className: 'dt-body-right', 
                    targets: [7] 
                },
                {
                    className: 'dt-body-right', 
                    targets: [8] 
                }


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
               // { "data": "Discamount"},
                { "data": "value" },
                /* { "data": "avlqty" },
                { "data": "setofqty" },
                */
                

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
var Invoice_id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;

$(document).ready(function () {
    getLocation()
    getBranches()
    
    loademployees()
loadPamentTerm()
getPaymentMethods()

if (window.location.search.length > 0) {
    var sPageURL = window.location.search.substring(1);
    var param = sPageURL.split('?');
    /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
    Invoice_id = param[0].split('=')[1].split('&')[0];
    var status = param[0].split('=')[2].split('&')[0];
    action = param[0].split('=')[3].split('&')[0];
    task = param[0].split('=')[4].split('&')[0];
    }

    getEachSalesInvoice(Invoice_id, status);
        getEachproduct(Invoice_id, status);

        $('#btnBack').on('click',function(){
            if(task == "approval"){
               
            }else{
                var url = "/sd/salesInvoiceList"; 
                window.location.href = url;
            }
           
    
        });
});


function getEachSalesInvoice(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sd/getEachSalesInvoice/' + id + '/' + status,
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

        }, success: function (salesInv) {
            //console.log(salesInv);
            var res = salesInv.data;
            var cus_name = res[0].customer_name;
            var cus_town = res[0].town_name;
            var cusFulName = cus_name + "-" + cus_town;

            /* $('#LblexternalNumber').val(res[0].external_number); */
            $('#LblexternalNumber').val(res[0].manual_number);
            $('#invoice_date_time').val(res[0].order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbPaymentTerm').val(res[0].payment_term_id);
            $('#txtRemarks').val(res[0].remarks);
            $('#txtDeliveryInst').val(res[0].delivery_instruction);
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            $('#txtYourReference').val(res[0].your_reference_number);
            $('#lblCustomerName').val(cusFulName);
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of SI
function getEachproduct(id, status) {
    $.ajax({
        url: '/sd/getEachproductofSalesInv/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            console.log(response);

             
            calculateTotals(response);
            $('#lblGrossTotal').text(parseFloat(grossTotal).toFixed(2));
            $('#lblTotalDiscount').text(parseFloat(totalDiscount).toFixed(2));
            //$('#lblTotaltax').text(parseFloat(tax).toLocaleString()); // Uncomment this line if you have a tax element
            $('#lblNetTotal').text(parseFloat(netTotal).toFixed(2));
            console.log("response",grossTotal);

            var dt = response

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var quantity = parseFloat(dt[i].quantity);
                var price = parseFloat(dt[i].price);
                var discountAmount = parseFloat(dt[i].discount_amount);
                var discountpres = parseFloat(dt[i].discount_percentage);
               

                var value = (quantity * price) - discountAmount;

                data.push({

                    "itemCode": dt[i].Item_code,
                    "name": dt[i].item_name,
                    "qty": Math.abs(dt[i].quantity),
                    "foc": Math.abs(dt[i].free_quantity),
                    "uom": dt[i].unit_of_measure,
                    //"packagesize":dt[i].package_size,
                    "packsize": dt[i].package_unit,
                    "price": parseFloat(dt[i].price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "Disc": dt[i].discount_percentage,
                    //"Discamount": dt[i].discount_amount,
                    "value": parseFloat(Math.abs(value)).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    /* "avlqty": dt[i].whole_sale_price,
                    "setofqty": dt[i].retial_price,
                     */


                });
            }


            var table = $('#salesinvoicetable').DataTable();
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

        
        var discount_amount = (Math.abs(quantity) * price) * (discountpres / 100);
        grossTotal += Math.abs(quantity) * price;
        tableDiscount += discount_amount;
    }
    if (isNaN(headerDiscountAmount)) {
        headerDiscountAmount = 0;
    }


    totalDiscount = headerDiscountAmount + tableDiscount;
    netTotal = grossTotal - totalDiscount + tax;

   
}







//load payment type
function loadPamentType() {
    $.ajax({
        url: '/prc/loadPamentType',
        type: 'get',
        dataType: 'json',
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


//load payment term
function loadPamentTerm() {
    $.ajax({
        url: '/sd/loadPamentTerm',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentTerm').append('<option value="' + value.payment_term_id + '">' + value.payment_term_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })

}

function loademployees() {
    $.ajax({
        url: '/sd/loademployees',
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load payment methods to cmb
function getPaymentMethods() {
    $.ajax({
        url: '/sd/getPaymentMethods',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbPaymentMethod').append('<option value="' + value.supplier_payment_method_id + '">' + value.supplier_payment_method + '</option>');

            });
            $('#cmbPaymentMethod').val(data[2].supplier_payment_method_id);

        },
        error: function (error) {
            console.log(error);
        },

    })
}