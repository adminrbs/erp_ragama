
// Setup module
// ------------------------------

const AutocompleteInputs = function () {




    // Autocomplete
    const _componentAutocomplete = function () {
        if (typeof autoComplete == 'undefined') {
            console.warn('Warning - autocomplete.min.js is not loaded.');
            return;
        }

        // Demo data
        const autocompleteData = load_inv();

        // Basic
        const autocompleteBasic = new autoComplete({
            selector: "#txtInv",
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

        // Start with

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
var m_number = undefined;
$(document).ready(function(){

    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
        }
    });

    $('.modal').each(function () {
        $(this).find('.select2').select2({
            dropdownParent: $(this)
        });
    });

    getCusrrentMonthDates();
    loadCustomerToCMB();
    loademployeesInModel();
    //tabs navigation
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    load_inv();


    //initizilazing auto complete
    document.addEventListener('DOMContentLoaded', function () {
        AutocompleteInputs.init();
    });


    //calling inv data loading function
    $('#txtInv').on('input',function(){
       
       
       $(".val_table tbody").empty();
       $('.val_lbl').text('');
       
    })
    $('#btnSearch').on('click',function(){
        if($('#txtInv').val().length < 10){
            showWarningMessage("Please enter a invoice number");
        }else{
            load_invoice_details($('#txtInv').val());
        }
       
    });

    //tr click event
    $('#return_table').on('click', 'tr', function (e) {
        $("#return__item_table tbody").empty();
        
        $('#return_table tr').removeClass('highlight');
   
      $(this).addClass('highlight');
        var dataIdValue = $(this).find('td:eq(0)').attr('data-id');
        load_return_items(dataIdValue);

    });

    


    if (window.location.search.length > 0) {
        var urlParams = new URLSearchParams(window.location.search);
    
    
        var decodedManualNumber = base64Decode(urlParams.get('manual_number'));
      //  var action = urlParams.get('action');
        load_invoice_details(decodedManualNumber);
        $('#txtInv').val(decodedManualNumber);
        
        
    }


    getInvoices_inv_info();

    $('#cmbBranch').on('change',function(){
        branch_id = $(this).val();
    })
    
    $('#from_date').on('change', function () {
        getInvoices_inv_info();
       
    });

    $('#to_date').on('change', function () {
        getInvoices_inv_info();
    });

    $('#cmbCustomer').on('change', function () {
        getInvoices_inv_info();
    });

    $('#cmbSalesRep').on('change', function () {
        getInvoices_inv_info();
    });

     //tr click event on sales invoice table
     $('#getInvoicetable').on('click', 'tr', function (e) {

        $('#getInvoicetable tr').removeClass('selected');
        $(this).addClass('selected');
      
         m_number = $(this).find('td:eq(1)').text();
        
        
      

    });

    $('#bntLoadData').on('click',function(){
        $('#txtInv').val(m_number);
        load_invoice_details(m_number);
        $('#inv_info_search_modal').modal('hide');
        
    });

    
});

function base64Decode(str) {
    return decodeURIComponent(escape(atob(str)));
}

//load invoice external number
function load_inv() {
    var result = [];

    $.ajax({
        url: '/sd/load_inv',
        method: 'GET',
        cache: false,
        timeout: 800000,
        success: function (data) {
            $.each(data, function (index, value) {

                result.push(value.manual_number);
            })

        }

    });
    console.log(result);
    return result;

}

//delivery report
function print(id) {
    
   
    const newWindow = window.open("/sd/delivery_report/" + id);
  newWindow.onload = function() {
    newWindow.print();
  }
}

//show picking list report
function showPickingReport(delivery_plan_packing_list_id) {
    /* location.href= "/sd/pickinglist/"+delivery_plan_packing_list_id; */
    const newWindow = window.open("/sd/pickinglist/"+delivery_plan_packing_list_id);
    newWindow.onload = function() {
        newWindow.print();
      }
           
}


//load invoice details
function load_invoice_details(number){

    $(".val_table tbody").empty();
       $('.val_lbl').text('');

    $.ajax({
        url: '/sd/load_invoice_details/'+number,
        method: 'GET',
        cache: false,
        timeout: 800000,
        success: function (data) {
            if(data.header.length < 1){
                showWarningMessage('Please enter a correct invoice number')
            }else{

            
            var header = data.header;
            var item_data = data.item;
            var return_data = data.return_data;
            var customer_receipt_data = data.customer_receipt;
            var sfa_data = data.sfa;
            var delivery_plan = data.delivery_plan;
            var picking_list = data.picking_list;
            var delivery_confirmation_data = data.delivery_confirmation_data;
            console.log(delivery_confirmation_data);
            //header

            $.each(header, function (index, value) {
                $('#LblexternalNumber').text(number);
                $('#invoice_date_time').text(value.order_date_time);
                $('#txtBranch').text(value.branch_name);
                $('#txtlocation').text(value.location_name);
                $('#txtEmp').text(value.employee_name);
                $('#txtCustomerID').text(value.customer_name);
                if(value.so_number){
                    $('#lblSalesOrder').text(value.so_number);
                    $('#txtGap').text(value.date_gap);
                    $('#dt_order').text(value.s_order_date);
                }
                $('#txtTotal').text(parseFloat(value.amount).toLocaleString());
                $('#txtPaid').text(parseFloat(value.paidamount).toLocaleString());
                $('#txtBalance').text(parseFloat(value.balance).toLocaleString());

                
            });
            //appending invoice items
            $.each(item_data, function (index, value) {
                var newRow = $("<tr>");

            var value_ = (parseFloat(value.quantity) * parseFloat(value.price)) - ((parseFloat(value.quantity) * parseFloat(value.price)) * (parseFloat(value.discount_percentage) /100));
            newRow.append("<td>" + value.Item_code + "</td>");
            newRow.append("<td>" + value.item_name + "</td>");
            newRow.append("<td style='text-align: right;'>" + Math.abs(value.quantity) + "</td>");
            newRow.append("<td style='text-align: right;'>" + Math.abs(value.free_quantity) + "</td>");
            newRow.append("<td>" + value.unit_of_measure + "</td>");
            newRow.append("<td>" + value.package_size + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseFloat(value.price).toLocaleString() + "</td>");
            newRow.append("<td>" + value.discount_percentage + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseFloat(Math.abs(value_)).toLocaleString() + "</td>");
            $("#item_table tbody").append(newRow);
                
                
            });

            //appending returns
            $.each(return_data, function (index, value) {
                var newRow = $("<tr>");

           
            newRow.append("<td data-id= '"+value.sales_return_Id+"'>" + value.external_number + "</td>");
            newRow.append("<td>" + value.order_date + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseFloat(value.total_amount).toLocaleString() + "</td>");
            newRow.append("<td>"+value.name+"</td>");
            $("#return_table tbody").append(newRow);
                
                
            });

            //appending customer receipts
            $.each(customer_receipt_data, function (index, value) {
                var newRow = $("<tr>");

                var cheque_num= undefined;
                if(value.cheque_number){
                     cheque_num = value.cheque_number;
                }else{
                    cheque_num = "Cash"
                }
           
            newRow.append("<td>" + value.receipt_date + "</td>");
            newRow.append("<td>" + value.external_number + "</td>");
            newRow.append("<td>" + value.employee_name + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseFloat(value.set_off_amount).toLocaleString() + "</td>");
            newRow.append("<td>" + cheque_num + "</td>");
            newRow.append("<td style='text-align: right;'>" + Math.abs(value.Gap) + "</td>");
           
            $("#receipts_table tbody").append(newRow);
                
                
            });


            //appending sfa receipts
            $.each(sfa_data, function (index, value) {
                var newRow = $("<tr>");

                var cheque_num= undefined;
                if(value.cheque_number){
                     cheque_num = value.cheque_number;
                }else{
                    cheque_num = "Cash"
                }
           
            newRow.append("<td>" + value.receipt_date + "</td>");
            newRow.append("<td>" + value.external_number + "</td>");
            newRow.append("<td>" + value.employee_name + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseFloat(value.set_off_amount).toLocaleString() + "</td>");
            newRow.append("<td style='text-align: right;'>" + cheque_num + "</td>");
            newRow.append("<td style='text-align: right;'>" + Math.abs(value.Gap) + "</td>");
           
            $("#sfa_receipts_table tbody").append(newRow);
                
                
            });

            //appending delivery plan
            $.each(delivery_plan, function (index, value) {
                var newRow = $("<tr>");

                newRow.append("<td data-id= '"+value.delivery_plan_id+"'>" + value.external_number + "</td>");
                newRow.append("<td>" + value.vehicle_no + "</td>");
                newRow.append("<td>" + value.driver_name + "</td>");
                newRow.append("<td>" + value.helper_name + "</td>");
                newRow.append("<td>"+value.name+"</td>");
                newRow.append("<td style='display:none;'><a href='#' onclick='print("+value.delivery_plan_id+")' title='Delivery Report'>View</a></td>");
                
                $("#delivery_plan_table tbody").append(newRow);
                    
                
                
            });

            //appending picking list
            $.each(picking_list, function (index, value) {
                
                var newRow = $("<tr>");
                newRow.append("<td>" + value.created_date + "</td>");
                newRow.append("<td data-id= '"+value.delivery_plan_packing_list_id+"'>" + value.delivery_plan_packing_list_id + "</td>");
                
                newRow.append("<td style='display:none;'><a href='#' onclick='showPickingReport("+value.picking_list_id+")' title='Picking list Report'>View</a></td>");
               
                $("#picking_list_table tbody").append(newRow);
                    
                
                
            });

            //dlivery confirmation
            $.each(delivery_confirmation_data,function(index,value){
                var delivered_checked = '<input class="form-check-input" type="checkbox" disabled>';
                var signature_checked = '<input class="form-check-input" type="checkbox" disabled>';
                var seal_checked = '<input class="form-check-input" type="checkbox" disabled>';
                var cash_checked = '<input class="form-check-input" type="checkbox" disabled>';
                var check_checked = '<input class="form-check-input" type="checkbox" disabled>';
                var noSeal_checked = '<input class="form-check-input" type="checkbox" disabled>';
                var cancel = '<input class="form-check-input" type="checkbox">';

                if(value.delivered == 1){
                    delivered_checked = '<input class="form-check-input" type="checkbox" checked disabled>'; 
                }
                if(value.Signature == 1){
                    signature_checked = '<input class="form-check-input" type="checkbox" checked disabled>'; 
                }
                if(value.Seal == 1){
                    seal_checked = '<input class="form-check-input" type="checkbox" checked disabled>'; 
                }
            
                if(value.Cheque == 1){
                    check_checked = '<input class="form-check-input" type="checkbox" checked disabled>'; 
                }
                if(value.Cash == 1){
                    cash_checked = '<input class="form-check-input" type="checkbox" checked disabled>'; 
                }
                if(value.cancel == 1){
                    cancel = '<input class="form-check-input" type="checkbox" checked disabled>'; 
                }
                if(value.noSeal == 1){
                    noSeal_checked = '<input class="form-check-input" type="checkbox" checked disabled>'; 
                }
                var name = value.name;
                if(name == null){
                    name = "";
                }

                var newRow = $("<tr>");
                newRow.append("<td>" + delivered_checked + "</td>");
                newRow.append("<td>" + signature_checked + "</td>");
                newRow.append("<td>" + seal_checked + "</td>");
                newRow.append("<td>" + cash_checked + "</td>");
                newRow.append("<td>" + check_checked + "</td>");
                newRow.append("<td>" + noSeal_checked + "</td>");
                newRow.append("<td>" + cancel + "</td>");
                newRow.append("<td>"+name+"</td>");
                $('#delivery_confirmation_table tbody').append(newRow);
            });

            


        }

        }

    });
 
   


}

//sales return items
function load_return_items(id){
    $("#return__item_table tbody").empty();
    
    
    $.ajax({
        url: '/sd/load_return_items/'+id,
        method: 'GET',
        cache: false,
        timeout: 800000,
        async:false,
        success: function (data) {
            var items = data.items;
           
            //header
            $.each(items, function (index, value) {

                var newRow = $("<tr>");

            var value_ = (parseFloat(value.quantity) * parseFloat(value.price)) - ((parseFloat(value.quantity) * parseFloat(value.price)) * (parseFloat(value.discount_percentage) /100));
            newRow.append("<td>" + value.Item_code + "</td>");
            newRow.append("<td>" + value.item_name + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseInt(value.quantity) + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseInt(value.free_quantity) + "</td>");
            newRow.append("<td>" + value.unit_of_measure + "</td>");
            newRow.append("<td>" + value.package_unit + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseFloat(value.price).toLocaleString() + "</td>");
            newRow.append("<td style='text-align: right;'>" + value.discount_percentage + "</td>");
            newRow.append("<td style='text-align: right;'>" + parseFloat(value_).toLocaleString()+ "</td>");
           
            $("#return__item_table tbody").append(newRow);
                
                
                
            });
            

        }

    });
    


}


function getCusrrentMonthDates() {

    $.ajax({
        url: '/sd/getMonthDates',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            var firstDate = response.first;
            var lastDate = response.last;

            /*  var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#invoice_date_time').val(formattedDate); */

            var parts_first = firstDate.split('/');
            var First_Date = parts_first[2] + '-' + parts_first[1] + '-' + parts_first[0];

            $('#from_date').val(First_Date);

            var parts_last = lastDate.split('/');
            var Last_Date = parts_last[2] + '-' + parts_last[1] + '-' + parts_last[0];

            $('#to_date').val(Last_Date);


        },
        error: function (error) {
            console.log(error);
        }
    });
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


function getInvoices_inv_info() {
    formData.append('from_date', $('#from_date').val());
    formData.append('to_date', $('#to_date').val());
    formData.append('cmbCustomer', $('#cmbCustomer').val());
    formData.append('cmbSalesRep', $('#cmbSalesRep').val());

    var table = $('#getInvoicetable');
    var tableBody = $('#getInvoicetable tbody');
    tableBody.empty();

    $.ajax({
        url: "/sd/getInvoices_inv_info",
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

