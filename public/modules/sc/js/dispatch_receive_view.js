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
        var table = $('#dispatch_receive_view_item').DataTable({
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
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "item_code" },
                { "data": "item_name" },
                { "data": "qty" },
                { "data":"pacs" },
                { "data": "price" },
                { "data": "value" },
                { "data": "wh_price" },
                { "data": "rt_price" },
                { "data": "cost_price" },
            
       
            ],
            "stripeClasses": ['odd-row', 'even-row']

            

        });

        table.column(5).visible(false);
        table.column(6).visible(false);
        table.column(7).visible(false);
        table.column(8).visible(false);

    };

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
var reuqestID;
var action = undefined;
$(document).ready(function () {
    $('#batchModelTitle').hide();
    $('#lblBalance').hide();
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();

  

    getServerTime();
  

    

   // $('#btnBack').hide();

    //back
    $('#btnBack').on('click', function () {
        
            var url = "/sc/dispatch_receive_list_view";
            window.location.href = url;
        

    });

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    });

    $('#cmb_to_Branch').change(function () {
        var id = $(this).val();
        get_to_Location(id);
    })





    //add is-valid class
    $('select').change(function () {

        validateSelectTag(this);

    });

    getBranches();
    $('#cmbBranch').change();
    $('#cmb_to_Branch').change();




    
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        GRNID = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#btnBack').show();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();

        } else if (action == 'edit' && status == 'Draft') {
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
            /*   $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
        } else if (action == 'view') {
             $('#btnSave').hide();
           /* $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
           
            disableComponents(); */

        }


        load_dispatch_receive_items_view(GRNID);

    }







});






//loading branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#cmb_to_Branch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');


            })

        },
    })
}


//loading from location
function getLocation(id) {
    $('#cmbLocation').empty();
    $.ajax({
        url: '/sc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmbLocation').change();
            // alert($('#cmbLocation').val());
        },
    })
}
//get to location
function get_to_Location(id) {
    $('#cmb_to_Location').empty();
    $.ajax({
        url: '/sc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmb_to_Location').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmb_to_Location').change();
        },
    })
}






function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event, "item");
        $('#data-chooser-modalLabel').text('Items');
    }
}











//cal val and cost (change with qty, free qty)
function calValueandCostPrice(event) {
    var row = $($(event).parent()).parent();
    var cell = row.find('td');
    var qty = $($(cell[2]).children()[0]);
    var price = $($(cell[7]).children()[0]);
    var discount_percentage = $($(cell[8]).children()[0]);
    var discount_amount = $($(cell[9]).children()[0]);
    var foc = $($(cell[3]).children()[0]);
    var cost_price = $($(cell[15]).children()[0]);



    var value = getDiscountAmount(qty, price, discount_percentage, discount_amount, foc, cost_price);
    $($(cell[10]).children()[0]).val(value);


    calculation();

}


//grand total
function calculation() {
    var grossTotal = 0;
    var tableDiscount = 0;
    var tax = 0;
    var arr = tableData.getDataSourceObject();


    for (var i = 0; i < arr.length; i++) {
        var qty = parseFloat(arr[i][2].val().replace(/,/g, ""));
        var price = parseFloat(arr[i][4].val().replace(/,/g, ""));
        var discount_pres = 0;
        console.log(price);

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



    var totalDiscount = tableDiscount;
    var netTotal = (grossTotal - totalDiscount + tax);

    $('#lblGrossTotal').text(parseFloat(grossTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotalDiscount').text(parseFloat(totalDiscount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblTotaltax').text(parseFloat(tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString()));
    $('#lblNetTotal').text(parseFloat(netTotal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}




function load_dispatch_receive_items_view(id){
    //var selectedIds = [];
   
    
    var collection;
    $.ajax({
        type: "get",
        url: "/sc/https://vvr1.kfdlanka.lk/sc/dispatch_receive_view?id=5&paramS=null&action=view&task=null/"+ id,
        async: false,
        beforeSend: function () { },
        success: function (response) {
            //  console.log(response);
         collection = response.data;
       var  header = response.dispatch;
    console.log(response);
         $('#LblexternalNumber').val(header.external_number);
         $('#LblexternalNumber').attr('data-id',id);
         $('#dispatch_Date_time').val(header.trans_date);
         $('#txtYourReference').val(header.your_reference_number);
         $('#cmbBranch').val(header.from_branch_id);
         $('#cmbLocation').val(header.from_location_id);
         $('#cmb_to_Branch').val(header.to_branch_id);
         $('#cmb_to_Branch').change();
         $('#cmb_to_Location').val(header.to_location_id);


         var data = [];
         var total_value = 0;
         console.log(collection);
        $.each(collection, function (index, value) {
            var price = parseFloat(value.price);
            var quantity = parseInt(value.quantity);
            var values = parseFloat(quantity * price);
            var rt_price = value.retial_price;
            var ct_price = value.cost_price;
           
            console.log(price);
            if(value.retial_price === null || isNaN(value.retial_price) ){
               
                rt_price = 0;
            }

            if(value.cost_price === null || isNaN(value.cost_price) ){
               
                ct_price = 0;
            }

           
            ct_price = parseFloat(ct_price);
            rt_price = parseFloat(rt_price);
            total_value = total_value + values;
            console.log(total_value);
            data.push({
               
                "item_code": value.Item_code,
                "item_name": value.item_Name,
                "qty": Math.abs(value.quantity),
                "pacs": value.package_unit,
                "price":parseFloat(value.price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                "value":parseFloat(values).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                "wh_price":parseFloat(value.whole_sale_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                "rt_price":parseFloat(rt_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                "cost_price":parseFloat(ct_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),


            });

            var table = $('#dispatch_receive_view_item').DataTable();
            table.clear();
            table.rows.add(data).draw();

            $('#lblGrossTotal').text(parseFloat(total_value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            $('#lblNetTotal').text(parseFloat(total_value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));


            

        });
            
           

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }
    });
   
    
}













function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);
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
            $('#dispatch_Date_time').val(formattedDate);
            // $('#dtPaymentDueDate').val(formattedDate);
        },
        error: function (error) {
            console.log(error);
        },

    })
}













