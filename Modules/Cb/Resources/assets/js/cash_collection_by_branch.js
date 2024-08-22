/* ----------data table---------------- */
const DatatableFixedColumns = function () {

    // Basic Datatable examples
    const _componentDatatableFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

      
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
        $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
          
            
            columnDefs: [

                {
                    width: 80,
                    targets: 0,
                    orderable: false
                },
                {
                    width: 100,
                    targets: 1,
                    orderable: true

                },
                {
                    width: 20,
                    targets: 2,
                    orderable: false
                },
                {
                    width: 150,
                    targets: 4,
                    orderable: false
                },
                {
                    width: 100,
                    targets: 3,
                    orderable: false
                },
                {
                    width: 80,
                    targets: 6,
                    orderable: false
                },
                {
                    width: 20,
                    targets: 7,
                    orderable: false
                },
                {

                    targets: 8,
                    orderable: false
                },
                {
                    width: 190,
                    targets: 9,
                    orderable: false
                },
                {
                    width: 5,
                    targets: 5,
                    orderable: false
                },
                {
                    width: 150,
                    targets: 10,
                    orderable: false
                },



            ],
            scrollX: true,
            scrollY: '700px;',
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "info":false,
            "columns": [
                { "data": "ref_number" },
                { "data": "date" },
                { "data": "invoice_number" },
                { "data": "invoice_date" },
                { "data": "customer" },
                { "data": "date_gap" },

                { "data": "town" },
                { "data": "amount" },
                { "data": "action" },
                { "data": "remark" },
                { "data": "rep" },





            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });

    };

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function() {
    DatatableFixedColumns.init();
});


/* --------------end of data table--------- */
var global_branch_id_ = undefined;
var global_collector_id = undefined;
var action = undefined;
var referanceID;
$(document).ready(function () {
    loadBookNumber();
    $('#cash_table_prntBtn').prop('disabled',true);
    $('.select2').select2();
    getServerTime();


    getBranches();
    $('#cmbBranch').change();

    loademployees();
    $('#cmbEmp').change();



    $('#cmbBranch').on('change', function () {
        global_branch_id_ = $(this).val();
        loadCustomerReceipts_cash_branch($(this).val(),global_collector_id);
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    }); 

    $('#cmbEmp').on('change', function () {
        
        global_collector_id = $(this).val();
        loadCustomerReceipts_cash_branch(global_branch_id_,$(this).val());
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    });

    $('#cmbEmp').trigger('change');

    //calling sae function 
    $("#btn_cash_branch_save").on('click',function(){
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
                console.log(result);
                if (result) {
                    add_cash_collection_branch(check_box_array);
                    
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


    //calling table print function
    $('#cash_table_prntBtn').on('click',function(){
       /*  var text = $('#cmbBook option:selected').text();
        var book = $('#cmbBook').val();
        var page = $('#txtNumber').val(); */
        
        /* if(text == "Select Book"){
            showWarningMessage('Please select a book')
        }else if(page.length < 1){
            showWarningMessage('Please enter a page')
        }else{ */
           // printTable(book,page,$('#cmbBranch').val(),$('#cmbEmp').val());
            printTable($('#cmbBranch').val(),$('#cmbEmp').val());
       /*  } */
        
            
        
        
    });
});

//load customer receipts for cash collecion by branch list
function loadCustomerReceipts_cash_branch(br_id,collector_id_) {
   
    console.log(br_id);
    $.ajax({
        url: '/cb/loadCustomerReceipts_cash_branch/' + br_id +'/'+collector_id_,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
            if(dt.length > 0){
                $('#cash_table_prntBtn').prop('disabled',false);
            }else{
                $('#cash_table_prntBtn').prop('disabled',true);
            }

            var data = [];
            var unselected_total = 0;
            for (var i = 0; i < dt.length; i++) {

                var cash_check_box = '<input class="form-check-input" type="checkbox" id="'+ dt[i].customer_receipt_id + '" onchange="update_status_calculation(this)">';
                if (dt[i].receipt_status == 1) {
                    cash_check_box = '<input class="form-check-input" type="checkbox" id="' + dt[i].customer_receipt_id + '" onchange="update_status_calculation(this)" checked>';
                }

                var inv_date = new Date(dt[i].receipt_date);
                var debt_date = new Date(dt[i].trans_date);
                var date_gap = debt_date - inv_date;
                var days = date_gap / (1000 * 60 * 60 * 24);
                unselected_total += parseFloat(dt[i].set_off_amount);
                data.push({
                    "ref_number": '<div data-id="' + dt[i].customer_receipt_id + '">' +dt[i].external_number+ '</div>',
                    "date": '<div data-id="' + dt[i].customer_receipt_id + '">' + dt[i].receipt_date + '</div>',
                    "invoice_number": '<div data-id="' + dt[i].debtors_ledger_id + '">' +dt[i].manual_number,
                    "invoice_date": '<div data-id="' + dt[i].customer_receipt_setoff_data_id + '">' + dt[i].trans_date + '</div>',
                    "customer": '<div title="'+dt[i].customer_name+'">'+shortenString(dt[i].customer_name,18)+'</div>',
                    "date_gap": dt[i].Gap,

                    "town": dt[i].townName,
                    "amount": parseFloat(dt[i].set_off_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "action": cash_check_box,
                    "remark": '<div id="remark' + "|" + dt[i].customer_receipt_id + '" style="width:200px;"><input type="text" class="form-control"></div>',
                    "rep":dt[i].rep
                });

            }
console.log(unselected_total);
            $('#unselecteted_lbl').text(parseFloat(unselected_total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            var table = $('#cash_collection_by_branch_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}


function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            var htmlContent = "";
            if (data.length <= 1) {
                $.each(data, function (key, value) {

                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });
                $('#cmbBranch').html(htmlContent);
                $('#cmbBranch').prop('disabled', true);
                loadCustomerReceipts_cash_branch($('#cmbBranch').val());
                

            } else if (data.length > 1) {
                htmlContent += "<option value=''>Select branch</option>";

                $.each(data, function (key, value) {

                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });

                $('#cmbBranch').html(htmlContent);
            }


            $('#cmbBranch').change();
        },
    })
}


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}

//check box select function
var check_box_array = [];
function update_status_calculation(event) {
    var total = 0.0;
    var count = 0;
    var reduse_amount = 0;
    /*   var check_box_values = {}; */
    var $row = $(event).closest('tr');
    /*  var checkBoxId = $(event).attr('id'); */

    // Receipt id
    var $second_cell = $row.find('td:eq(1)');
    var $div = $second_cell.find('div');
    var receipt_id = $div.data('id');

    var amount_cell = $row.find('td:eq(7)');
    var _amount = amount_cell.text();

    var status_cell = $row.find('td:eq(4) input[type="checkbox"]');
    var status = status_cell.is(':checked') ? 1 : 0;

    var checkboxId = $(event).attr('id');

    if ($(event).prop('checked')) {
        var rowData = {};
        total = parseFloat($('#sum_label').text().replace(/,/g, '')) + parseFloat(_amount.replace(/,/g, '')); // Add to total
        count = parseFloat($('#row_count').text()) + 1
        reduse_amount = parseFloat($('#unselecteted_lbl').text().replace(/,/g, '')) - parseFloat(_amount.replace(/,/g, ''));

        //setting for update status
        if (!check_box_array.includes(checkboxId)) {
            check_box_array.push(checkboxId);
        }


    } else {
        total = parseFloat($('#sum_label').text().replace(/,/g, '')) - parseFloat(_amount.replace(/,/g, '')); // Subtract from total
        count = parseFloat($('#row_count').text()) - 1
        reduse_amount = parseFloat($('#unselecteted_lbl').text().replace(/,/g, '')) + parseFloat(_amount.replace(/,/g, ''));
       //remove from array
        var index = check_box_array.indexOf(checkboxId);
        if (index !== -1) {
            check_box_array.splice(index, 1);
        }
        console.log(check_box_array);
        
    }
    $('#sum_label').text(parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })).addClass('h4');
    $('#row_count').text(count);
    $('#unselecteted_lbl').text(parseFloat(reduse_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })).addClass('h4');

    /*   var formData = new FormData();
      formData.append('status',status);
     
      
        console.log(formData); */


    /*   $.ajax({
          url: '/cb/update_status_calculation/'+receipt_id,
          method: 'post',
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
              getServerTime();
            var message = response.message;
            if(message == 'error'){
              showWarningMessage('Unable to update');
              return;
            }
  
          }, error: function (data) {
              console.log(data.responseText)
          }, complete: function () {
  
          }
      })             */
}


//save cash collection by branch -update customer receipt status - bundle data
function add_cash_collection_branch(check_array) {
    console.log($('#cmbEmp').val());
    var formData = new FormData();
      formData.append('values',JSON.stringify(check_array));
     
      console.log(formData);
      /* formData.append('book_id',$('#cmbBook').val());
      formData.append('page_no',$('#txtNumber').val()); */

      var text = $('#cmbBook option:selected').text();

     /*  if(text == "Select Book"){
        showWarningMessage('Please select a book');
        return;
      }

      else if($('#txtNumber').val().length < 1){
        showWarningMessage('Please enter page number');
        return;
        
      } */
      
       if($('#cmbBranch').val() == "" || $('#cmbBranch').val() == null){
            showWarningMessage('Please select a branch');
            return;
            
        }
       else if($('#cmbEmp').val() == "" || $('#cmbEmp').val() == null){
            showWarningMessage('Please select a collector');
            return;
        }

        if(check_array.length < 1){
            showWarningMessage('Please select a record');
            return;
        }else{
            formData.append('branch_id',$('#cmbBranch').val());
        
    $.ajax({
        url: '/cb/update_status_calculation',
        method: 'post',
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
            $('#btn_cash_branch_save').prop('disabled',true);
        }, success: function (response) {
            
            $('#btn_cash_branch_save').prop('disabled',false);
            getServerTime();
            var message = response.message;
            var status = response.status
            if (message == 'error') {
                showWarningMessage('Unable to update');
                return;
            }
            if(status){
              //  showSuccessMessage('Record Updated');
            //  add_cash_bundle();
            newReferanceID('cash_bundles', '900');
            add_cash_bundle();
            loadCustomerReceipts_cash_branch(global_branch_id_,global_collector_id);
                
            }else{
                showWarningMessage('Unable to update')
                return;
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}
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
          /*   $('#cashDate').val(formattedDate); */

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load collectors
function loademployees() {
    $.ajax({
        url: '/sd/loademployees',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
                $('#cmbEmp').append('<option value="0">Any</option>');
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

            $('#cmbEmp').trigger('change');

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//save cash bundle - bundle & datas
function add_cash_bundle() {
    var dataArray = [];

    // Loop through each row in the table
    $('#cash_collection_by_branch_table tbody tr').each(function () {
        // Find the checkbox within the current row
        var checkbox = $(this).find('input[type="checkbox"]');

        
        if (checkbox.is(':checked')) {
            var rowData = {};

            
            var row = $(this);

           
           //customer receipt id
            var divElement_customer_receipt_id = row.find('td:nth-child(1) div');
            var dataId_cus_receipt = divElement_customer_receipt_id.data('id');
            

            //customer receipt set off data id
            var divElement_customer_receipt__set_off_data_id = row.find('td:nth-child(4) div');
            var dataId_cus_receipt_set_off_data_id = divElement_customer_receipt__set_off_data_id.data('id');

            //amount
            var t_amount = row.find('td:nth-child(8)').text();
           // var t_amount = row.find('td:eq(7)');

            //sales invoice id
            var divElement_dl_id = row.find('td:nth-child(3) div');
            var dataId_dl = divElement_dl_id.data('id'); //debtor ledger id

            //remark
            var divElement_remark= row.find('td:nth-child(10) div');
            var textbox = divElement_remark.find('input[type="text"]');
            var textboxValue = textbox.val();

          
            rowData.branch_id = $('#cmbBranch').val();
            rowData.customer_receipt_id = dataId_cus_receipt;
            rowData.customer_receipt_set_of_id = dataId_cus_receipt_set_off_data_id;
            rowData.amount = t_amount;
            rowData.collector_id = $('#cmbEmp').val();
            rowData.debtor_ledger_id = dataId_dl;
            rowData.remark = textboxValue;
            dataArray.push(rowData);
        }

    });

    console.log(dataArray);
    var formData = new FormData();
      formData.append('dataArray',JSON.stringify(dataArray));
      formData.append('LblexternalNumber',referanceID)
      formData.append('book_id',$('#cmbBook').val());
      formData.append('page_no',$('#txtNumber').val());
      formData.append('branch_id',$('#cmbBranch').val());
    
      console.log(referanceID);
    $.ajax({
        url: '/cb/add_cash_bundle',
        method: 'post',
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
            console.log(response);
            getServerTime();
            var message = response.message;
            var status = response.status
            if (message == 'error') {
                showWarningMessage('Unable to update');
                return;
            }
            if(status){
                showSuccessMessage('Record Updated');
                loadCustomerReceipts_cash_branch(global_branch_id_);
                $('#sum_label').text('0.00');
                $('#row_count').text('0');
            }else{
                showWarningMessage('Unable to update')
                return;
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}


function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_cash_bundles", table, doc_number);
    // $('#LblexternalNumber').val(referanceID);
}

/* function newReferanceID_customer_receipt(table, doc_number) {
    REFERANCE_ID = newID("/cb/customer_receipt/new_referance_id", table, doc_number);
    $('#txtRefNo').val('New Receipt');
} */


//table print function
function printTable(br_id,collector_id){

    if(isNaN(parseInt(br_id))){
        showWarningMessage('Branch should select');
    }else if(isNaN(parseInt(collector_id))){
        showWarningMessage('Collector should select');
    }
   
        /* var url = '/cb/printTable/'+br_id+'/'+collector_id+'/'+book_id+'/'+pageNo;
        location.href = url; */

        var checkedIds = [];

        $('#cash_collection_by_branch_table tbody tr').each(function() {
            
            var checkbox = $(this).find('td:eq(8) input[type="checkbox"]');

            
            if (checkbox.is(':checked')) {
                
                checkedIds.push(checkbox.attr('id'));
            }
        });
       // var cash_id_array = JSON.stringify(checkedIds)
        var cash_id_encoded_array = encodeURIComponent(checkedIds)
        const newWindow = window.open('/cb/printCashTable/'+cash_id_encoded_array+'/'+collector_id);
  newWindow.onload = function() {
    newWindow.print();
  }
        
        
}



function loadBookNumber() {
    $.ajax({
        url: '/cb/load_cash_BookNumber/',
        type: 'get',
        async: false,
        success: function (response) {
            console.log(response);
            var dt = response.data

            $.each(dt, function (index, value) {
                $('#cmbBook').append('<option value="' + value.book_id + '">' + value.book_name + '</option>');

            });
            $('#cmbBook').trigger('change');
        }
    })

}