var customers = [];
var rowId = undefined;
var collector = undefined;
var action;
$(document).ready(function(){
    getServerTime();
    getBranches();

    load_dishonour_reasons();
    load_sales_rep();
    //date change event - validate future date
    $('#returned_on').on('change', function() {
        var selectedDate = new Date($(this).val());
        var currentDate = new Date();

        if (selectedDate > currentDate) {
            showWarningMessage('Future dates are not allowed');
            getServerTime(); 
        }
    });

    $('#btnDelete').hide();

    //load customers
    customers = loadCustomerTOchooser();
    DataChooser.addCollection("Customer",['Customer Name', 'Customer Code', 'Town', 'Route',''], customers);


    //show customer model
    $('#txtCustomerID').on('focus', function () {
       
        $('#data-chooser-modalLabel').text('Customers');
        DataChooser.showChooser($(this),$(this),"Customer");
        $('#data-chooser-modalLabel').text('Customers');

    });


    //edit
    /* if (window.location.search.length > 0) {
        var urlParams = new URLSearchParams(window.location.search);
        var record_id = urlParams.get('id');
        $('#btnDelete').show();
        $('#bt_save').hide();
        $('#txtChqNo').prop('disabled',true);
        $('#cmbBranch').prop('disabled',true);
        $('#returned_on').prop('disabled',true);
        $('#txtCustomerID').prop('disabled',true);
        $('#txtbank_charges').prop('disabled',true);
        $('#return_reason').prop('disabled',true);
        $('#cmbEmp').prop('disabled',true);
        $('#chk_redeposit').prop('disabled',true);
        $('#chk_pay_by_customer').prop('disabled',true);
         
    } */



    //row click event
    $('#checks_table').on('click', 'tr', function (e) {
          rowId = $(this).find('td:eq(0)').data('id');
          collector = $(this).find('td:eq(1)').data('id');

          $('#checks_table tr').removeClass('selected');
          $(this).addClass('selected');
          //alert(collector);
          var chq_no = $(this).find('td:eq(1)').text();
          $('#txtChqNo').val(chq_no);
          $('#cmbEmp').val(collector);
          $('#cmbEmp').change();
          $('#txtChqNo').prop('disabled',true);
          
    });



    //save event
    $('#btnSave').on('click', function () {
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
                   
                        newReferanceID('cheque_returns', '1900');
                        if(rowId == undefined){
                            $('#checks_table tbody tr').each(function() {
                                // Get the data-id attribute of the first cell in the row
                             rowId = $(this).find('td:first').data('id');
                                
                               
                              });
                        }
                        add_chq_return(rowId);
                     
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




    })


    $("#txtChqNo").keypress(function(event) {
        if (event.which === 13) {
            load_data_through_chq_no($("#txtChqNo").val());
        }
      });

});

//load serve time
function getServerTime() {
    $.ajax({
        url: '/sd/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#returned_on').val(formattedDate);
           

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//laod branches
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

function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event,"item");
        $('#data-chooser-modalLabel').text('Items');
    }
}

function dataChooserShowEventListener(event){

}

//load customers
function loadCustomerTOchooser() {

    var data = [];
    $.ajax({
        url: '/sd/loadCustomerTOchooser',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (response) {
            if (response) {
                var customerData = response.data;
                console.log(customerData);
                /*  DataChooser.setDataSourse(supplierData); */
                data = customerData;
            }
        },
        error: function (error) {
            console.log(error);
        },

    })
    return data;
}

function loadCustomerOtherDetails(id) {

    $.ajax({
        url: '/sd/loadCustomerOtherDetails/' + id,
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            console.log(data)
            var txt = data.data;
            /*  console.log(txt); */
            var cusID = txt[0].customer_id;
            $('#lblCustomerName').attr('data-id', cusID);
            load_cheques(cusID);

        },
        error: function (error) {
            console.log(error);
        },

    })
}


function dataChooserEventListener(event, id, value) {
    if ($(event.inputFiled).attr('id') == 'txtCustomerID') {
        loadCustomerOtherDetails(value);
        $('#lblCustomerName').val(id);

    } 

}

//load cheques
function load_cheques(cusID){
    if ($("#checks_table tbody").children().length < 1) {

        $.ajax({
            url: '/cb/load_cheques/' + cusID,
            type: 'get',
            dataType: 'json',
            async:false,
            success: function (data) {
                var dt = data.data;
                console.log(dt);
                $.each(dt, function (index, value) {
                    var newRow = $("<tr>");
    
                    /*  var value_ = (parseFloat(value.quantity) * parseFloat(value.price)) - ((parseFloat(value.quantity) * parseFloat(value.price)) * (parseFloat(value.discount_percentage) /100)); */
                  //  var balance_ = parseFloat(value.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, });
                    newRow.append("<td data-id='"+value.customer_receipt_cheque_id+"'>" + value.external_number + "</td>");
                    newRow.append("<td data-id='"+value.collector_id+"'>" + value.cheque_number + "</td>");
                    newRow.append("<td>" + value.receipt_date + "</td>");
                    newRow.append("<td>" + value.banking_date + "</td>");
                    newRow.append("<td>" + value.cheque_deposit_date + "</td>");
                    newRow.append("<td>"+ value.amount +"</td>");
                    $("#checks_table tbody").append(newRow);

                });      
    
            },
            error: function (error) {
                console.log(error);
            },
    
        })
       
    } 
   
}


//add return
function add_chq_return(id){
   
    var re_deposit = $('#chk_redeposit').is(":checked") ? 1 : 0;
    var pay_by_customer = $('#chk_pay_by_customer').is(":checked") ? 1 : 0;

    if(pay_by_customer == 1){
        if($('#txtbank_charges').val().length < 1){
            showWarningMessage('Please enter bank charges');
            return;
        }
    }

    if($('#txtChqNo').val().length < 1){
        showWarningMessage("Please select a cheque");

    }else{
        formData.append('LblexternalNumber', referanceID); //external number
        formData.append('returned_on', $('#returned_on').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('customerID', $('#lblCustomerName').data('id'));
        formData.append('txtbank_charges', $('#txtbank_charges').val());
        formData.append('return_reason', $('#return_reason').val());
        formData.append('txtChqNo', $('#txtChqNo').val());
        formData.append('re_deposit', re_deposit);
        formData.append('pay_by_customer', pay_by_customer);
        formData.append('cmbEmp', $('#cmbEmp').val());
        $.ajax({
            url: '/cb/add_chq_return/' + id,
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
                $('#btnSave').prop('disabled', true);
            }, success: function (response) {
                //console.log(response);
                $('#btnSave').prop('disabled', false);
                var status = response.status
               
                
                if (status) {
                    showSuccessMessage("Successfully returned");
                    
                   
                 /*    hash_map = new HashMap(); */
                    url = "/cb/cheque_dishonour_list";
                    window.location.href = url;
    
    
    
                } else {
    
                    showErrorMessage("Something went wrong");
                }
    
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
    
            }
        })

    }
   
}


function load_dishonour_reasons() {

    $.ajax({
        url: '/cb/load_dishonour_reasons/',
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            console.log(data);
            $.each(data, function (index, value) {
                
                $('#return_reason').append('<option value="' + value.cheque_dishonur_reason_id + '">' + value.cheque_dishonur_reason + '</option>');

            });
            

        },
        error: function (error) {
            console.log(error);
        },

    })
}

function load_sales_rep() {

    $.ajax({
        url: '/sd/loademployees/',
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            console.log(data);
            $.each(data, function (index, value) {
                
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            });
            

        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_chqReturn", table, doc_number);
    // $('#LblexternalNumber').val(referanceID);
}


function load_data_through_chq_no(check_no){
    $("#checks_table tbody").empty();
    $.ajax({
        url: '/cb/load_data_through_chq_no/'+check_no,
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (data) {
            var dt = data.data;
            if(dt.length < 1){
                showWarningMessage("Please enter correct cheque number");
            }
            $.each(dt, function (index, value) {
                var newRow = $("<tr>");
                    newRow.append("<td data-id='"+value.customer_receipt_cheque_id+"'>" + value.external_number + "</td>");
                    newRow.append("<td data-id='"+value.collector_id+"'>" + value.cheque_number + "</td>");
                    newRow.append("<td>" + value.receipt_date + "</td>");
                    newRow.append("<td>" + value.banking_date + "</td>");
                    newRow.append("<td>" + value.cheque_deposit_date + "</td>");
                    newRow.append("<td>"+ value.amount +"</td>");
                    $("#checks_table tbody").append(newRow);
               
                    $('#lblCustomerName').val(value.customer_name);
                    $('#txtCustomerID').val(value.customer_code);
                    $('#lblCustomerName').attr('data-id', value.customer_id);

            });
            

        },
        error: function (error) {
            console.log(error);
        },

    })

}

function load_cheque_return(id){

}