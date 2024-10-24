

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
        var table = $('#salesinvoicereturnTable').DataTable({
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
               // { "data": "Discamount"},
                { "data": "value" },
               

                
             

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
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    getLocation();
    getBranches();
    loadBookNumber();
    loademployees();
   
   
    loadReason();
    loadCustomerToCMB();
    loademployeesInModel();


    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        Invoice_id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
    }
    getEachSalesReturn(Invoice_id, 'Original');
    getEachproduct(Invoice_id,'Original');
    getEachSetOff(Invoice_id);

    $('#btnBack').on('click',function(){
        if(task == "approval"){
           
        }else{
            var url = "/sd/salesReturnList"; 
            window.location.href = url;
        }
       

    });
});



function getEachSalesReturn(id, status) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sd/getEachSalesReturn/' + id + '/' + status,
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
            console.log(salesInv);
            var res = salesInv.data;
           
            getLocation(res[0].branch_id);
           // console.log(res[0].order_date_time);
            
            /* ('#lblSupplierAddress').text(txt[0].primary_address); */
            $('#LblexternalNumber').val(res[0].external_number);
            $('#invoice_date_time').val(res[0].order_date);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].location_id);
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerName').val(res[0].customer_name);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            /*  $('#cmbPaymentTerm').val(res[0].payment_term_id); */
            $('#txtRemarks').val(res[0].remarks);
            /*  $('#txtDeliveryInst').val(res[0].delivery_instruction); */
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            $('#txtInvoiceID').val(res[0].SI_ext);
            $('#cmbReason').val(res[0].return_reason_id);
            $('#cmbBookNumber').val(res[0].book_id);
            $('#txtPageNumber').val(res[0].page_number);
            /* var cusID = txt[0].customer_id;
            $('#lblCustomerName').attr('data-id',cusID); */

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of SI
function getEachproduct(id, status) {
    $.ajax({
        url: '/sd/getEachproductofSalesReturn/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
             calculateTotals(response);
            $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            //$('#lblTotaltax').text(parseFloat(tax).toLocaleString()); // Uncomment this line if you have a tax element
            $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
          

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
                    "qty": parseInt(dt[i].quantity),
                    "foc": parseInt(dt[i].free_quantity),
                    "uom": dt[i].unit_of_measure,
                    //"packagesize":dt[i].package_size,
                    "packsize": dt[i].package_unit,
                    "price": parseFloat(dt[i].price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "Disc": dt[i].discount_percentage,
                    //"Discamount": dt[i].discount_amount,
                    "value": parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                   
                   

                });
            }


            var table = $('#salesinvoicereturnTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}

function getEachSetOff(id) {
    $.ajax({
        url: '/sd/getEachSetOffSalesReturn/' + id ,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            var dt = response.data
           
            
            

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var formattedAmount = parseFloat(dt[i].setoff_amount).toLocaleString();

                // Create a new row with the formatted setoff_amount
                var newRow = `
                    <tr>
                        <td>${dt[i].trans_date}</td>
                        <td>${dt[i].external_number}</td>
                        <td style="text-align:right">${formattedAmount}</td>
                    </tr>
                `;
            
            // Append the new row to the table body
            $('#set_off_table tbody').append(newRow);
                
                
            }

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


function loadReason() {
    $.ajax({
        url: '/sd/loadReason',
        type: 'get',
        async: false,
        success: function (data) {
            console.log("lll",data);
            $.each(data, function (index, value) {
                $('#cmbReason').append('<option value="' + value.sales_return_reson_id + '">' + value.sales_return_resons + '</option>');

            })

        },


    })
}

//load customer to cmb
function loadCustomerToCMB() {

    $.ajax({
        url: '/sd/loadCustomers',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            var dt = response.data
            console.log(dt)
            $.each(dt, function (index, value) {

                $('#cmbCustomer').append('<option value="' + value.customer_id + '">' + value.customer_name + '</option>');

            })
        },
        error: function (error) {
            console.log(error);
        },

    })

}


//load emp to cmb in model
function loademployeesInModel() {
    $.ajax({
        url: '/sd/loademployeesInModel',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbSalesRep').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');
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

  //load book number
  function loadBookNumber(){
    $.ajax({
        url: '/sd/loadBookNumber/',
        type: 'get',
        async:false,
        success: function (response) {
             console.log(response);
             var dt = response.data
           
            $.each(dt, function (index, value) {
                $('#cmbBookNumber').append('<option value="' + value.book_id + '">' + value.book_name + '</option>');

            });
            $('#cmbBookNumber').trigger('change');
        }
    })

  }