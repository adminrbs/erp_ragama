
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

$(document).ready(function(){
    $('#btn_reprint').prop('disabled',true);
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
       $('#LblexternalNumber').removeAttr('data-id');
       $('#btn_reprint').prop('disabled',true);
       
    })
    $('#btnSearch').on('click',function(){
        if($('#txtInv').val().length < 10){
            showWarningMessage("Please enter a invoice number");
        }else{
            load_invoice_details_reprint($('#txtInv').val());
        }
       
    });

    //tr click event
    $('#return_table').on('click', 'tr', function (e) {
      
        $('#return_table tbody tr').removeClass('highlight');

      
      $(this).addClass('highlight');
        var dataIdValue = $(this).find('td:eq(0)').attr('data-id');
        load_return_items(dataIdValue);

    });

    $('#btn_reprint').on('click',function(){
        var inv_id = $('#LblexternalNumber').attr('data-id');
       
        reprint(inv_id);
    });
});


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

//reprint confrimation 
function reprint(id) {
    bootbox.confirm({
        title: 'Re-print confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-warning'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-link'
            }
        },
        callback: function (result) {
            //console.log('Confirmation result:', result);
            if (result) {
                allowReportin(id)
            } else {

            }
        },
        onShow: function () {
            $('#question-icon').addClass('swipe-question');
        },
        onHide: function () {
            $('#question-icon').removeClass('swipe-question');
        }
    });

    $('.bootbox').find('.modal-header').addClass('bg-warning text-white');
    
}

function allowReportin(id){
    $.ajax({
        url: '/sd/allowReportin/' + id,
        method: 'post',
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
           
        }, success: function (response) {
            var status = response.status
            var msg = response.message
            if (msg == 'granted') {
                showSuccessMessage("Request Sent");
                $('#btn_reprint').prop('disabled',true);
               
                return;

            }else if(msg == 'exist'){
                showWarningMessage("Re-print request already exist for this invoice");
                $('#btn_reprint').prop('disabled',true);
               
                return;

            } else{

                showWarningMessage("Request Failed");
               /*  $('#btn_reprint').val('Request Re-print').removeClass('btn-warning').addClass('btn-success');
                return; */
            }
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}

//load invoice details
function load_invoice_details_reprint(number){

   
       $('.val_lbl').text('');

    $.ajax({
        url: '/sd/load_invoice_details_reprint/'+number,
        method: 'GET',
        cache: false,
        timeout: 800000,
        success: function (data) {
            if(data.header.length < 1){
                showWarningMessage('Please enter a correct invoice number')
            }else{
                $('#btn_reprint').prop('disabled',false);
            
            var header = data.header;
            //header

            $.each(header, function (index, value) {
                $('#LblexternalNumber').text(number).attr('data-id', value.sales_invoice_Id);
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

                /* if(value.is_reprint_allowed != 0){
                    $('#btn_reprint').val('Revoke Re-print').removeClass('btn-success').addClass('btn-warning');
                }else{
                    $('#btn_reprint').val('Request Re-print').removeClass('btn-warning').addClass('btn-success');
                } */

                
            });
            
            
        }

        }

    });
   
   


}


