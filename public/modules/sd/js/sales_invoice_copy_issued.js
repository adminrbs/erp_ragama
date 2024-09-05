
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
$(document).ready(function () {
    $('.select2').select2();


    loadEmp();


    load_inv();


    //initizilazing auto complete
    document.addEventListener('DOMContentLoaded', function () {
        AutocompleteInputs.init();
    });


    $('#btnSearch').on('click', function () {
        if ($('#txtInv').val().length < 10) {
            showWarningMessage("Please enter a invoice number");
        } else {

            load_invoice_details_for_invoie_copy($('#txtInv').val());
        }

    });

    $('#btnSave').on('click',function(){
        var collection = [];
        $("#invoiceDataTable tbody tr").each(function () {
             existingSalesInvoiceId = $(this).find("td:first").data("id");
             collection.push(existingSalesInvoiceId);
        });
        bootbox.confirm({
            title: 'Save confirmation',
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
                    //newReferanceID('sales_invoice_copy_issueds', '2750');
                    saveInvoiceCopyIssued(collection);
                  
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
    });

    if (window.location.search.length > 0) {
        var urlParams = new URLSearchParams(window.location.search);


        var decodedManualNumber = base64Decode(urlParams.get('manual_number'));
        //  var action = urlParams.get('action');
        load_invoice_details(decodedManualNumber);
        $('#txtInv').val(decodedManualNumber);


    }










});

function base64Decode(str) {
    return decodeURIComponent(escape(atob(str)));
}

//load invoice external number
function load_inv() {
    var result = [];

    $.ajax({
        url: '/sd/load_inv_for_copy_issued',
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
    newWindow.onload = function () {
        newWindow.print();
    }
}

//show picking list report
function showPickingReport(delivery_plan_packing_list_id) {
    /* location.href= "/sd/pickinglist/"+delivery_plan_packing_list_id; */
    const newWindow = window.open("/sd/pickinglist/" + delivery_plan_packing_list_id);
    newWindow.onload = function () {
        newWindow.print();
    }

}


//load invoice details
function load_invoice_details_for_invoie_copy(number) {
    $("#invoiceDataTable tbody tr").removeClass("highlight");
    $.ajax({
        url: '/sd/load_invoice_details_for_invoie_copy/' + number,
        method: 'GET',
        cache: false,
        timeout: 800000,
        success: function (data) {
            if (data.header.length < 1) {
                showWarningMessage('Please enter a correct invoice number');
            } else {
                var header = data.header;
                $.each(header, function (index, value) {
                    var salesInvoiceIdExists = false;

                    // Check if sales_invoice_Id already exists in the table
                    $("#invoiceDataTable tbody tr").each(function () {
                        var existingSalesInvoiceId = $(this).find("td:first").data("id");
                        if (existingSalesInvoiceId == value.sales_invoice_Id) {
                            salesInvoiceIdExists = true;
                            showWarningMessage("Duplicate Record");
                            $(this).addClass("highlight");
                            return false; // Exit the loop early if found
                        }
                    });

                    // If sales_invoice_Id does not exist, add the new row
                    if (!salesInvoiceIdExists) {
                        var newRow = $("<tr>");
                        newRow.append("<td data-id=" + value.sales_invoice_Id + ">" + number + "</td>");
                        newRow.append("<td>" + value.employee_name + "</td>");
                        newRow.append("<td data-id=" + value.customer_id + ">" + value.customer_name + "</td>");
                        newRow.append("<td>" + value.order_date_time + "</td>");
                        newRow.append("<td style='text-align: right;'>" + parseInt(value.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>");
                        newRow.append("<td style='text-align: right;'>" + parseInt(value.paidamount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>");
                        newRow.append("<td style='text-align: right;'>" + parseInt(value.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>");
                        newRow.append(
                            $('<button type="button" style="border: none; background-color: transparent;">')
                                .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                                .on('click', function () {
                                    remove_custom_line(this);
                                })
                        );

                        $("#invoiceDataTable tbody").append(newRow);
                    }
                });
            }
        }
    });
}


function remove_custom_line(button) {

    var row = button.closest('tr');
    row.remove();

}





function loadEmp() {
    $.ajax({
        url: '/sd/loadEmpforsalesInvoicecopyIssued',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            console.log(data);

            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');
            })

        },
        error: function (error) {
            console.log(error);
        },

    })
}


function saveInvoiceCopyIssued(collection) {
    if($('#txtRemark').val().length < 1){
        showWarningMessage('Please add remark');
    }else if(collection.length === 0){
        showWarningMessage('Please select select invoice');
    }else{
        var formData = new FormData();
        //formData.append('LblexternalNumber', referanceID);
        formData.append('collection', JSON.stringify(collection));
        formData.append('txtRemark',$('#txtRemark').val());
        formData.append('emp',$('#cmbEmp').val());
        $.ajax({
            url: '/sd/saveInvoiceCopyIssued',
            method: 'post',
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
            beforeSend: function () {
                
            }, success: function (response) {
    
                if(response.status){
                    showSuccessMessage('Record Saved');
                    $('#txtRemark').val('');
                    $('#txtInv').val('');
                    $('#invoiceDataTable tbody').empty();
                }else{
                    showWarningMessage('Unable to save');
                }
    
    
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
    
            }
        })
    }

    
}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_sales_invoice_copy_issued", table, doc_number);
   
}
