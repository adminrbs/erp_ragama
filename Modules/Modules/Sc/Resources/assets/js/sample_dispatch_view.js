const DatatableFixedColumns = function () {

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
        var table = $('#tblData').DataTable({
            columnDefs: [
              
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 380,
                    targets: 2
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
                { "data": "code" },
                { "data": "name" },
                { "data": "qty" },
                { "data": "U_O_M" },
                { "data": "Pack" },
                { "data": "set_off_qty" },
       
            ],
            "stripeClasses": ['odd-row', 'even-row']

            

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
    DatatableFixedColumns.init();
});

var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var suppliers = [];
var customers = []
var Invoice_id = null;
var reuqestID;
var action = undefined;
var referanceID
var ItemList;
var locationID;
var totalQty = 0;
var qty_object = undefined;
var branchID;
var whole_sale_count = 0;
$(document).ready(function () {

    /* $('#batchModelTitle').hide(); */
    $('#lblBalance').hide();

    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide(); // need to edit


   

    
    getServerTime();
   
    $('#btnBack').hide();

    $('#btnBack').on('click', function () {

        var url = "/sc/sample_dispatch_list";
        window.location.href = url;


    });

  

    //getting branch code
    $('#cmbBranch').on('change', function () {
        var branch_id_ = $(this).val();
        
    });

    /* newReferanceID('sales_invoices','210'); */

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);

    });
    $('#cmbLocation').change();
    


    getBranches();
    $('#cmbBranch').change();

    //get location id
    $('#cmbLocation').on('change', function () {
        locationID = $(this).val();

    });

      
    
    $('#cmbEmp').change();

    //gross total
    $('#txtDiscountAmount').on('input', function () {
        calculation();

    });

    $('#si_model_btn').on('click',function(){
        $('#warning_alert').removeClass('show');
    });


    $('#txtCustomerID').on('focus', function () {
        
        DataChooser.showChooser($(this),$(this),"Customer");
        $('#data-chooser-modalLabel').text('Customers');



    })



    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });


    
   
   


    


   

    
    if (window.location.search.length > 0) {
        var urlParams = new URLSearchParams(window.location.search);
    
    
        var decodedNumber = base64Decode(urlParams.get('id'));
        var action = urlParams.get('action');

        if(action == 'view'){
           // alert(decodedNumber);
          //  disableComponents();
        }
      
        getBranches();
        
        get_each_sample_dispatch(decodedNumber);
      
        
    }



});


function base64Decode(str) {
    return decodeURIComponent(escape(atob(str)));
}

function clickx(id) {
    tableData.clear();
}

function transactionTableKeyEnterEvent(event, id) {

    if (id == 'tblData') {
        tableData.addRow();

    }

}

//loading branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
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
function getLocation(id) {

    $('#cmbLocation').empty();
    $.ajax({
        url: '/prc/getLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmbLocation').trigger('change');
        },
    })
}

























//grand total
function calculation() {
    var grossTotal = 0;
    var tableDiscount = 0;
    var tax = 0;
    var arr = tableData.getDataSourceObject();
    var headerDiscountAmount = parseFloat($('#txtDiscountAmount').val().replace(/,/g, ""));

    for (var i = 0; i < arr.length; i++) {
        var qty = parseFloat(arr[i][2].val().replace(/,/g, ""));
        var price = parseFloat(arr[i][7].val().replace(/,/g, ""));
        var discount_pres = parseFloat(arr[i][8].val().replace(/,/g, ""));


        // Check if the field values are not NaN or empty
        if (isNaN(qty)) {
            qty = 0;
        }
        if (isNaN(price)) {
            price = 0;
        }
        if (isNaN(discount_pres)) {
            discount_pres = 0;
        }
        discount_amount = (qty * price) * (discount_pres / 100);
        grossTotal += (qty * price);
        tableDiscount += discount_amount;

    }

    if (isNaN(headerDiscountAmount)) {
        headerDiscountAmount = 0;
    }

    var totalDiscount = (headerDiscountAmount + tableDiscount);
    var netTotal = (grossTotal - totalDiscount + tax);

    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}



function get_each_sample_dispatch(id) {

    $.ajax({
        url: '/sc/get_each_sample_dispatch/' + id,
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

        }, success: function (data) {
            var res = data.header;
            var dt = data.item;

            
         
            $('#LblexternalNumber').val(res[0].external_number);
            $('#invoice_date_time').val(res[0].order_date_time);
            $('#cmbBranch').val(res[0].branch_id);
            
            $('#cmbLocation').val(res[0].location_id);
            
            $('#cmbEmp').val(res[0].employee_id);
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtRemarks').val(res[0].remarks);
            $('#lblCustomerName').val(res[0].customer_name);



            var data_array = [];
            for (var i = 0; i < dt.length; i++) {
                
              // var str_primary = dt[i].sample_dispatch_id; 
                data_array.push({
                    "code": dt[i].Item_code,
                    "name": dt[i].item_name,
                    "qty": Math.abs(dt[i].quantity),
                    "U_O_M": dt[i].unit_of_measure,
                    "Pack": dt[i].package_unit,
                    "set_off_qty":parseInt(dt[i].set_off_qty),
                });  
                
                
             
               
            }

            var table = $('#tblData').DataTable();
            table.clear(); 
            table.rows.add(data_array).draw();
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


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
            $('#invoice_date_time').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}







function getItemID(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    ItemID = $(cell[0]).children().eq(0).attr('data-id');
    //console.log(ItemID);


}


function getDiscountAmount(qty, price, discount_percentage, discount_amount) {

    var quantity = parseFloat(qty.val().replace(/,/g, ""));
    var unit_price = parseFloat(price.val().replace(/,/g, ""));
    var percentage = parseFloat(discount_percentage.val().replace(/,/g, ""));
    var amount = parseFloat(discount_amount.val().replace(/,/g, ""));

    if (isNaN(quantity)) {
        quantity = 0;
    }
    if (isNaN(unit_price)) {
        unit_price = 0;
    }
    if (isNaN(percentage)) {
        percentage = 0;
    }
    if (isNaN(amount)) {
        amount = 0;
    }


    var quantity_price = (quantity * unit_price);
    var percentage_price = 0;
    var final_value = 0;


    if (discount_percentage.is(':focus')) {
        percentage_price = (quantity_price / 100.00) * percentage;
        discount_amount.val(percentage_price.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    } else if (discount_amount.is(':focus')) {
        var prc = ((amount / quantity_price) * 100.0);
        percentage_price = (quantity_price / 100.00) * prc;
        discount_percentage.val(prc.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    } else {
        percentage_price = (quantity_price / 100.00) * percentage;
    }
    final_value = (quantity_price - percentage_price);


    return final_value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString();
}

