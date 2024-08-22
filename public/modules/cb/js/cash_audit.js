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
       var table = $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
          
            
            columnDefs: [

                {
                    width: 100,
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
                    width: 200,
                    targets: 4,
                    orderable: false
                },
                {
                    width: 100,
                    targets: 3,
                    orderable: false
                },
                {
                    width: 150,
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
                    width: 200,
                    targets: 9,
                    orderable: false
                },
                {
                    width: 5,
                    targets: 5,
                    orderable: false
                },


            ],
            scrollX: true,
            scrollY: '300px',
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
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

            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });
        table.column(9).visible(false);

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
        load_cash_receipts_for_audit($(this).val(),global_collector_id);
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    });

    $('#cmbEmp').on('change', function () {
        global_collector_id = $(this).val();
        load_cash_receipts_for_audit(global_branch_id_,$(this).val());
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    });

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
                    update_audit_cash(check_box_array);
                    
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
        var text = $('#cmbBook option:selected').text();
        var book = $('#cmbBook').val();
        var page = $('#txtNumber').val();
        
        if(text == "Select Book"){
            showWarningMessage('Please select a book')
        }else if(page.length < 1){
            showWarningMessage('Please enter a page')
        }else{
            printTable(book,page,$('#cmbBranch').val(),$('#cmbEmp').val());
        }
        
            
        
        
    });
});

//load customer receipts for cash collecion by branch list
function load_cash_receipts_for_audit(br_id,collector_id_) {
    if(br_id < 1 || isNaN(br_id)){
        return;
    }
    if(collector_id_ < 1 || isNaN(collector_id_)){
        return;
    }
   
    console.log(br_id);
    $.ajax({
        url: '/cb/load_cash_receipts_for_audit/' + br_id +'/'+collector_id_,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            if(dt.length > 0){
                $('#cash_table_prntBtn').prop('disabled',false);
            }else{
                $('#cash_table_prntBtn').prop('disabled',true);
            }

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var cash_check_box = '<input class="form-check-input" type="checkbox" id="'+ dt[i].customer_receipt_id + '" onchange="update_status_calculation(this)">';
                if (dt[i].receipt_status == 1) {
                    cash_check_box = '<input class="form-check-input" type="checkbox" id="' + dt[i].customer_receipt_id + '" onchange="update_status_calculation(this)" checked>';
                }

                var inv_date = new Date(dt[i].receipt_date);
                var debt_date = new Date(dt[i].trans_date);
                var date_gap = debt_date - inv_date;
                var days = date_gap / (1000 * 60 * 60 * 24);
                var manuel_ = dt[i].manual_number;
                if(manuel_ == null){
                    manuel_ = dt[i].EX_num
                }
                data.push({
                    "ref_number": '<div data-id="' + dt[i].customer_receipt_id + '">' +dt[i].external_number+ '</div>',
                    "date": '<div data-id="' + dt[i].customer_receipt_id + '">' + dt[i].receipt_date + '</div>',
                    "invoice_number": '<div data-id="' + dt[i].debtors_ledger_id + '">' +manuel_,
                    "invoice_date": '<div data-id="' + dt[i].customer_receipt_setoff_data_id + '">' + dt[i].trans_date + '</div>',
                    "customer": shortenString(dt[i].customer_name, 40),
                    "date_gap": dt[i].Gap,

                    "town": dt[i].townName,
                    "amount": parseFloat(dt[i].set_off_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "action": cash_check_box,
                    "remark": '<div id="remark' + "|" + dt[i].customer_receipt_id + '" style="width:200px;"><input type="text" class="form-control"></div>',

                });

            }

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
       /*  total = parseFloat($('#sum_label').text().replace(/,/g, '')) + parseFloat(_amount.replace(/,/g, '')); // Add to total
        count = parseFloat($('#row_count').text()) + 1 */

        //setting for update status
        if (!check_box_array.includes(checkboxId)) {
            check_box_array.push(checkboxId);
        }


    } else {
        /* total = parseFloat($('#sum_label').text().replace(/,/g, '')) - parseFloat(_amount.replace(/,/g, '')); // Subtract from total
        count = parseFloat($('#row_count').text()) - 1
 */
       //remove from array
        var index = check_box_array.indexOf(checkboxId);
        if (index !== -1) {
            check_box_array.splice(index, 1);
        }
        console.log(check_box_array);
        
    }
    /* $('#sum_label').text(parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })).addClass('h4');
    $('#row_count').text(count); */

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
function update_audit_cash(check_array) {
    console.log($('#cmbEmp').val());
    var formData = new FormData();
      formData.append('values',JSON.stringify(check_array));
     
      console.log(formData);
      /* formData.append('book_id',$('#cmbBook').val());
      formData.append('page_no',$('#txtNumber').val()); */

     /*  var text = $('#cmbBook option:selected').text(); */

      /* if(text == "Select Book"){
        showWarningMessage('Please select a book');
        return;
      } */

      /* else if($('#txtNumber').val().length < 1){
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

        
    $.ajax({
        url: '/cb/update_audit_cash',
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
            if (message == 'used') {
                showWarningMessage('Unable to update');
                return;
            }
            if(status){
              showSuccessMessage('Record updated');
              load_cash_receipts_for_audit(global_branch_id_,global_collector_id);
           
                
            }else{
                showWarningMessage('Unable to update');
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
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

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
function printTable(book_id,pageNo,br_id,collector_id){

    if(isNaN(parseInt(br_id))){
        showWarningMessage('Branch should select');
    }else if(isNaN(parseInt(collector_id))){
        showWarningMessage('Collector should select');
    }
   
        /* var url = '/cb/printTable/'+br_id+'/'+collector_id+'/'+book_id+'/'+pageNo;
        location.href = url; */

        const newWindow = window.open('/cb/printTable/'+br_id+'/'+collector_id+'/'+book_id+'/'+pageNo);
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