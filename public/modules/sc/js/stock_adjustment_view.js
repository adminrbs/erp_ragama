

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
        var table = $('#stock_adjusment_table').DataTable({
            columnDefs: [

              
                {
                    width: 100,
                    targets: 0,
                    orderable:false
                },
                {
                    width: 300,
                    targets: 1,
                    orderable:false
                },
                {
                    orderable:false,
                    width: 50,
                    targets: 2,
                    
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;

                    }


                },
                {
                    orderable:false,
                    width: 50,
                    targets: 3
                },
                {
                    orderable:false,
                    width: 50,
                    targets: 4,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;

                    }
                },
                {
                    orderable:false,
                    width: 100,
                    targets: 5,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;

                    }

                },
                {
                    orderable:false,
                    width: 100,
                    targets: 6,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;

                    }

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
               
               
                { "data": "packsize" },
                { "data": "cost_price" },
                { "data": "wh_price" },
                { "data": "rt_price"},
                { "data": "value" },
                  

            ], "stripeClasses": ['odd-row', 'even-row'],
        });

        table.column(7).visible(false);
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
    
   
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
     
        GRNID = param[0].split('=')[1].split('&')[0];
      
    
        $('#cmbBranch').prop('disabled',true);
        $('#cmbLocation').prop('disabled',true);
        $('#txtYourReference').prop('disabled',true);
    }

    get_each_adjustment(GRNID);
       


        $('#btnBack').on('click',function(){
            if(task == "approval"){
               
            }else{
                var url = "/sc/stock_adjustment_list"; 
                window.location.href = url;
            }
           
    
        });
});


function get_each_adjustment(id) {
   
    /* formData.append('status', status); */
    $.ajax({
        url: '/sc/get_each_adjustment/' + id,
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
           
            var adjusment = data.adjusment;
            var item = data.items;
            console.log(item);
            $('#LblexternalNumber').val(adjusment.external_number);
            $('#stock_adjustment_date_time').val(adjusment.date);
            $('#cmbBranch').val(adjusment.branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(adjusment.location_id);
            $('#txtYourReference').val(adjusment.your_reference_number);


            var data_array = [];
            for(var i = 0;i < item.length; i++){
                var cost_ = parseFloat(item[i].cost_price);
                var wh_ = parseFloat(item[i].whole_sale_price);
                var ret_ = parseFloat(item[i].retial_price);
                if(isNaN(cost_)){
                    cost_ = 0;
                }
                if(isNaN(wh_)){
                    wh_ = 0
                }

                if(isNaN(ret_)){
                    ret_ = 0
                }

                if(parseFloat(item[i].quantity) < 0){
                    
                    cost_ = parseFloat(item[i].set_co);
                    wh_ = parseFloat(item[i].set_wh);
                    ret_ = parseFloat(item[i].set_rt);
                }
                    data_array.push({
                    
                        "itemCode": item[i].Item_code,
                        "name": item[i].item_Name,
                        "qty": item[i].quantity,
                        "packsize": item[i].packsize,
                        "cost_price": parseFloat(cost_).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                        "wh_price":parseFloat(wh_).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                        "rt_price":parseFloat(ret_).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                        "value":"",
                    }); 
               


            }
            var table = $('#stock_adjusment_table').DataTable();
            table.clear(); 
            table.rows.add(data_array).draw();

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
                    "foc": dt[i].free_quantity,
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