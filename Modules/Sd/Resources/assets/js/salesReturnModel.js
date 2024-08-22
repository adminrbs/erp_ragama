var formData = new FormData();
var branch_id;
var invoice_ID;
var headerDataPath;
var pickOrderStatus = false;
$(document).ready(function () {
    $('#return_item_tab').hide();
    //initializing datetime pickers
    /*  $('.daterange-single').daterangepicker({
         parentEl: '.content-inner',
         singleDatePicker: true
     }); */

    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
        }
    });

    $('#cmbBranch').change(function () {
        branch_id = $(this).val();
    })

    getInvoicesForReturn(branch_id);

    $('#cmbBranch').on('change', function () {
        branch_id = $(this).val();
    })

    $('#from_date').on('change', function () {
        getInvoicesForReturn(branch_id);
        console.log(branch_id);
    });

    $('#to_date').on('change', function () {
        getInvoicesForReturn(branch_id);
        console.log(branch_id);
    });

    $('#cmbCustomer').on('change', function () {
        getInvoicesForReturn(branch_id);
        console.log(branch_id);
    });

    $('#cmbSalesRep').on('change', function () {
        getInvoicesForReturn(branch_id);
        console.log(branch_id);
    });

    //tr click event on sales invoice table
    $('#getInvoicetable').on('click', 'tr', function (e) {

        $('#getInvoicetable tr').removeClass('selected');
        $(this).addClass('selected');

        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children();

        childElements.each(function () {
            invoice_ID = $(this).attr('data-id');
            getInvoiceItems(invoice_ID);


        });
        /*  //select all
         $('#gettableItems th input[type="checkbox"]').click(function () {
             // Get the checked state of the header checkbox
             var isChecked = $(this).prop('checked');
             $('#gettableItems td input[type="checkbox"]').prop('checked', isChecked);
         }); */

    });

    //calling function  to load items to invoice return table
    $('#bntLoadData').on('click', function () {
        path = "model"
        getHeaderDetailsForInvoiceReturn(invoice_ID, path);
    });

    //load sales invoice details using get btn
    $('#btnReturnGetData').on('click', function () {
        var external_number = $('#txtInvoiceID').val();
        if (!external_number) {
            $('#txtInvoiceID').addClass('is-invalid');
            showWarningMessage('Please enter a valid Invoice Number');
            return;
        }
        path = "btn"
        getHeaderDetailsForInvoiceReturn(external_number, path);

    })


    //select all
    $('#gettableItems th input[type="checkbox"]').click(function () {
        // Get the checked state of the header checkbox
        var isChecked = $(this).prop('checked');
        $('#gettableItems td input[type="checkbox"]').prop('checked', isChecked);
    });


    $('#lblNetTotal').on('DOMSubtreeModified', function () {
        var invoice = $('#txtInvoiceID').attr('data-id');

        resetoff(invoice, 'edit');
    });


    //filter
    $('#txtInv').on('input', function () {
        //alert();
        var searchTerm = $(this).val().toLowerCase(); // Get the search term and convert to lowercase
        var hasResults = false; // Flag to check if there are results

        $('#getInvoicetable tbody tr').each(function () {
            // Check if the row's text matches the search term
            if ($(this).text().toLowerCase().indexOf(searchTerm) > -1) {
                $(this).show(); // Show the row
                hasResults = true; // Set the flag to true if there are results
            } else {
                $(this).hide(); // Hide the row
            }
        });


    });
});





//get invoice data to table in model (filter)
function getInvoicesForReturn(id) {
    formData.append('from_date', $('#from_date').val());
    formData.append('to_date', $('#to_date').val());
    formData.append('cmbCustomer', $('#cmbCustomer').val());
    formData.append('cmbSalesRep', $('#cmbSalesRep').val());

    var table = $('#getInvoicetable');
    var tableBody = $('#getInvoicetable tbody');
    tableBody.empty();

    $.ajax({
        url: "/sd/getInvoicesForReturn/" + id,
        method: 'POST',
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
        beforeSend: function () { },
        success: function (data) {
            var dt = data.data
            console.log(dt);
            $.each(dt, function (index, item) {
                console.log(item.sales_invoice_Id);
                var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.sales_invoice_Id).text(item.order_date_time)));
                row.append($('<td>').text(item.external_number));
                row.append($('<td>').text(item.customer_name));
                row.append($('<td>').text(item.employee_name));
                row.append($('<td>').text(parseFloat(item.total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                $(table).append(row);
            });

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}

//load invoice items to gettableItems table in model with tr click event
function getInvoiceItems(id) {
    var table = $('#gettableItems');
    var tableBody = $('#gettableItems tbody');
    tableBody.empty();
    $.ajax({
        type: "GET",
        url: "/sd/getInvoiceItems/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (data) {
            var dt = data.data
            console.log(dt);
            $.each(dt, function (index, item) {
                var qty = Math.abs(item.quantity);
                var foc = Math.abs(item.free_quantity);
                var price = item.price;
                var discAmount = item.discount_amount
                var value = parseFloat((qty * price) - discAmount);
                var row = $('<tr>');
                row.append($('<td>').append($('<label>').attr('data-id', item.item_id).text(item.Item_code)));
                row.append($('<td>').text(item.item_name));
                row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(qty)));
                row.append($('<td>').append($('<input class="transaction-inputs" style="background-color:white;width:50px;">').attr('type', 'text').val(foc)));
                row.append($('<td>').text(item.unit_of_measure));
                row.append($('<td>').text(item.package_unit));
                row.append($('<td>').text(item.package_size));
                row.append($('<td>').text(parseFloat(item.price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').text(item.discount_percentage));
                row.append($('<td>').text(item.discount_amount));
                row.append($('<td>').text(parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').append($('<input>').attr('type', 'checkbox').val(item.item_id).prop('checked', true)));
                table.append(row);
            });

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}


//get selected data from DB
function selectedData(invoiceID, path) {
    console.log(invoiceID)

    var branch_id = $('#cmbBranch').val();
    var selectedIds = [];

    $('#gettableItemsbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.prop('checked')) {
            var dataId = $(this).find('label').data('id');
            selectedIds.push(dataId);
        }
    });


    $.ajax({
        type: "get",
        url: "/sd/getInvoiceItemsToreturnTable/" + branch_id + "/" + invoiceID + "/" + path,
        data: { 'Item_ids': JSON.stringify(selectedIds) },
        async: false,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);

            var dt = response.data;
            console.log(dt);

            setItems(dt);



        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {

        }
    });
    calculation();

}



function setItems(collection) {


    var dataSource = [];
    foc_qty = 0;
    var table = $('#returned_items_table');
    var tableBody = $('#returned_items_table tbody');
    tableBody.empty();
    for (var i = 0; i < collection.length; i++) {
        // console.log('text item')
        var item_code = collection[i].Item_code;
        var item_id = collection[i].item_id;
        var item_name = collection[i].item_name;
        var inv_id = collection[i].sales_invoice_id;
        var quantity = Math.abs(collection[i].quantity);
        var free_quantity = Math.abs(collection[i].free_quantity);
        var unit_of_measure = collection[i].unit_of_measure;
        var pack_size = collection[i].package_unit;
        var package_size = collection[i].package_size;
        var price = parseFloat(collection[i].price).toFixed(2);
        var discount_percentage = collection[i].discount_percentage;
        var discount_amount = collection[i].discount_amount;
        var values = parseFloat((quantity * price) - discount_amount).toFixed(2);
        var retail_pr = parseFloat(collection[i].retial_price);
        var wh_price = parseFloat(collection[i].whole_sale_price);
        var cost_pr = parseFloat(collection[i].cost_price);
        var rtn_qty = parseFloat(collection[i].rtn_qty);
        foc_qty = foc_calculation_threshold_pick_order_sales_return(item_id, parseFloat(quantity));
        if (quantity > 0) {



            /*  console.log($(collection[i][0].parentElement));  */
            dataSource.push([
                { "type": "text", "class": "transaction-inputs", "value": item_code, "data_id": item_id, "style": "max-height:30px;width:100px;margin-right:10px;", "event": "" },
                { "type": "text", "class": "transaction-inputs", "value": item_name, "style": "max-height:30px;margin-left:10px", "event": "", "style": "width:350px", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs", "value": quantity, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this);foc_calculation_threshold(this)" },
                { "type": "text", "class": "transaction-inputs math-abs", "value": free_quantity, "style": "max-height:30px;width:50px;text-align:right;margin-left:10px;", "event": "calValueandCostPrice(this);checkqtyandFoc_sales_rtn(this)" },
                { "type": "text", "class": "transaction-inputs", "value": unit_of_measure, "style": "max-height:30px;text-align:right;width:50px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": package_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": pack_size, "style": "max-height:30px;text-align:right;width:100px;margin-left:10px;", "event": "clickx(1)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", },
                { "type": "text", "class": "transaction-inputs math-abs", "value": parseFloat(retail_pr).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", }, //retail
                { "type": "text", "class": "transaction-inputs math-abs", "value": discount_percentage, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs math-abs", "value": discount_amount, "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "calValueandCostPrice(this)", "width": "*", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": parseFloat(values).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), "style": "max-height:30px;text-align:right;width:80px;margin-left:10px;", "event": "", "width": "*", "disabled": "disabled" },
                { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:20px;", "event": "removeRow(this);calculation()", "width": 30 },
                { "type": "text", "class": "transaction-inputs", "value": free_quantity, "style": "max-height:30px;text-align:right;margin-right:10px;", "event": "", "width": "10", "disabled": "disabled" },
                { "type": "text", "class": "transaction-inputs", "value": cost_pr, "style": "max-height:30px;text-align:right;margin-right:10px;", "event": "", "width": "10", "disabled": "disabled" }
            ]);


            calculation();
        } else {
            $('#return_item_tab').show();
            var returned_foc = 0;
            $.ajax({
                type: "get",
                url: "/sd/get_returned_items_details/" + inv_id + "/" + item_id,
                async: false,
                beforeSend: function () { },
                success: function (response) {
                    // console.log(response.total_quantity);

                    returned_foc = Math.abs(response.total_quantity);


                },
                error: function (error) {
                    console.log(error);
                },
                complete: function () {

                }
            });

            var row = $('<tr>');
            row.append($('<td>').append($('<label>').attr('data-id', item_id).text(item_code)));
            row.append($('<td>').text(item_name));
            row.append($('<td>').text(rtn_qty));
            row.append($('<td>').text(returned_foc));
            row.append($('<td>').text(unit_of_measure));
            /*   row.append($('<td>').text(package_size)); */
            row.append($('<td>').text(pack_size));
            row.append($('<td>').text(parseFloat(price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
            /*  row.append($('<td>').text(parseFloat(retail_pr).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))); */
            /* row.append($('<td>').text(parseFloat(wh_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))); */
            /* row.append($('<td>').text(discount_percentage)); */
            /*    row.append($('<td>').text(discount_amount)); */
            /* row.append($('<td>').text(parseFloat(values).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
            row.append($('<td>').text(parseFloat(cost_pr).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))); */
            table.append(row);
        }
    }
    tableData.setDataSource(dataSource);
    $('#SalesReturnInvoiceModal').modal('hide');

    pickOrderStatus = true;

    var numberOfRows = $('#returned_items_table tbody tr').length;

    if (numberOfRows < 1) {
        $('#return_item_tab').hide();
    }



}



function getHeaderDetailsForInvoiceReturn(id, path) {
    $.ajax({
        type: "GET",
        url: "/sd/getHeaderDetailsForInvoiceReturn/" + id + "/" + path,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (data) {
            var res = data.data
            var length = res.length;
            console.log(length);
            if (length === 0) {
                showWarningMessage('Please enter a valid Invoice Number');
                $('#txtInvoiceID').addClass('is-invalid')
                return;
            }
            if (path != "model") {
                $('#cmbBranch').val(res[0].branch_id);
                $('#cmbBranch').change();
            }

            /*  $('#cmbLocation').val(res[0].location_id); */
            $('#cmbEmp').val(res[0].employee_id);
            $('#cmbEmp').change();
            $('#txtCustomerID').val(res[0].customer_code);
            $('#lblCustomerName').val(res[0].customer_name);
            $('#lblCustomerAddress').val(res[0].primary_address);
            $('#txtDiscountPrecentage').val(res[0].discount_percentage);
            $('#txtDiscountAmount').val(res[0].discount_amount);
            $('#cmbPaymentTerm').val(res[0].payment_term_id);
            $('#lblCustomerName').attr('data-id', res[0].customer_id);
            /* $('#LblexternalNumber').attr('data-id', id); */
            /*  $('#txtInvoiceID').val(res[0].external_number); */
            /* $('#txtInvoiceID').val(id); */
            $('#txtInvoiceID').val(res[0].external_number);
            $('#txtyourreferencenumber').val(res[0].external_number).prop('disabled', true);
            $('#txtInvoiceID').attr('data-id', res[0].sales_invoice_Id);

            var _num = res[0].manual_number
            selectedData(id, path)
            /* load_setoff_data_(res[0].customer_id) */
            load_setoff_data_invoice(res[0].external_number);

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });

}


//re set off
function resetoff(id, mode) {
    console.log(id);
    // mode use for to get clear whether the event is new insertion or edit
    var new_net_total = parseFloat($('#lblNetTotal').text().replace(/,/g, ''));
    if (new_net_total > 0) {
        if (mode == 'edit') {
            var table = $('#set_off_table');

            console.log('5');
            table.find('input[type="checkbox"]').prop('checked', false);
            table.find('input[type="number"]').val('');
            table.find('tr').each(function () {
                console.log('1');
                //  console.log(new_net_total);
                var rowDataId = $(this).find('td:eq(0) label').attr('data-id');
                console.log("row" + rowDataId + " id:" + id);
                if (rowDataId == id) {
                    var row_balance = parseFloat($(this).find('td:eq(3)').text().replace(/,/g, ''));


                    if (row_balance >= new_net_total) {
                        $(this).find('input[type="checkbox"]').prop('checked', true);
                        $(this).find('input[type="number"]').val(new_net_total);
                        new_net_total = new_net_total - new_net_total;
                    } else {

                        $(this).find('input[type="checkbox"]').prop('checked', true);
                        $(this).find('input[type="number"]').val(row_balance);
                        new_net_total = new_net_total - row_balance;
                    }

                }
            });
        }
    }

}

