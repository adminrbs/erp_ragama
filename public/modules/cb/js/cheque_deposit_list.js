/* ----------data table---------------- */
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
        $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            columnDefs: [
              
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: 100,
                    targets: 1
                },
                {
                    width: '80%',
                    targets: 2
                },
                {
                    width: 50,
                    targets:4 
                },
                {
                    width: 100,
                    targets:3,
                    className: 'dt-body-right',
                    orderable:false
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
            "info":false,
            "paging":false,
            "columns": [
               
                { "data": "receiptDate"},
                { "data": "date" },
                { "data": "ref_number" },
                { "data": "customer" },
                { "data": "amount" },
                { "data": "cheque_no" },
                { "data": "action" },
              

               

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
document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});


/* --------------end of data table--------- */
var check_box_array = [];
$(document).ready(function(){
    getServerTime();
    getAccount();
    $('#cmbAccountNumber').change();

    load_cheques_for_deposit($('#dtBankingDate').val());

    $('#dtBankingDate').on('change',function(){
        var currentDate = new Date();
        var selected_Date = new Date($(this).val());
        if(selected_Date > currentDate){
            showWarningMessage('Future date can not be selected');
            $(this).val(formatDate(currentDate));
            return;
        }else{
            load_cheques_for_deposit($(this).val());
        }
       
    });

    $('#btn_deposit_cheque').on('click',function(){
        if(check_box_array.length < 1 ){
            showWarningMessage('Please select a cheque');
            return;
        }else if(!$('#cmbAccountNumber').val()){
            showWarningMessage('Please select a account');
            return;
        }

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
                    deposit_cheque(check_box_array,$('#cmbAccountNumber').val());
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
  
});

//setting date
function formatDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1; // Months are zero-based
    var year = date.getFullYear();

    // Format as 'YYYY-MM-DD', you can adjust the format as needed
    return year + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
}

//load customer receipts for cash collecion by branch list
function load_cheques_for_deposit(date_){
    
    $.ajax({
            url:'/cb/load_cheques_for_deposit/',
            type:'get',
            data: {
                date: date_,
            },
            cache: false,
            timeout: 800000,
            async: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () { },
            success: function (response) {
                var dt = response.data;
    
                var data = [];
                for (var i = 0; i < dt.length; i++) {
                   var _check_box = '<input class="form-check-input" type="checkbox" id="'+dt[i].customer_receipt_cheque_id + '" onchange="update_status_calculation(this)">';
                   
                    data.push({
                        "receiptDate":dt[i].receipt_date,
                        "date": dt[i].banking_date,
                        "ref_number": '<div data-id="'+dt[i].customer_receipt_cheque_id+'">'+dt[i].external_number+'</div>',
                        "customer":shortenString(dt[i].customer_name,50),
                        "amount":parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "cheque_no":dt[i].cheque_number,
                        "action": _check_box,
                      
                    });  
                   
                }
    
                var table = $('#cheque_deposit_table').DataTable();
                table.clear(); 
                table.rows.add(data).draw();
    
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () { }
        })

}

//get acccount for cmb
function getAccount() {
    $.ajax({
        url: '/cb/getAccount',
        type: 'get',
        async: false,
        success: function (data) {
            var htmlContent = "";
            
                $.each(data, function (key, value) {

                    htmlContent += "<option value='" + value.account_id + "'>" + value.account_title + "</option>";
                });
                $('#cmbAccountNumber').html(htmlContent);
                $('#cmbAccountNumber').change();
                
           
        },
    })
}


function shortenString(inputString, maxLength) {
if(inputString != null){
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}else{
    return "";
}
    
}

function update_status_calculation(event){
    var count = 0;
        
            var $row = $(event).closest('tr');
            var checkBoxId = $(event).attr('id');

          
            if ($(event).prop('checked')) {
                count = parseFloat($('#row_count').text()) + 1
                check_box_array.push(checkBoxId)
            } else {
                count = parseFloat($('#row_count').text()) - 1
                var index = check_box_array.indexOf(checkBoxId);
                if (index !== -1) {
                    check_box_array.splice(index, 1); 
                }
            }
            $('#row_count').text(count);
    
           /*  var formData = new FormData();
            formData.append('ids',check_box_array);
            console.log(formData); */
              
        
              
           /*  $.ajax({
                url: '/cb/update_status_calculation_cheque_ho/'+receipt_id,
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
            })         */    
    }
//deposit check
function deposit_cheque(array,account_id_){
    var formData = new FormData();
    formData.append('ids',JSON.stringify(array));
    console.log(formData);

    $.ajax({
        url: '/cb/deposit_cheque/'+account_id_,
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
          var status = response.status;
          if(status){
            showSuccessMessage('Cheque deposited');
            
            $('#row_count').text('0');
            load_cheques_for_deposit($('#dtBankingDate').val());
            console.log($('#dtBankingDate').val());
            return;
          }else if(!status){
            showWarningMessage('Unble to deposit');
            return;
          }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });             

}
      //get server time
function getServerTime() {
    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        async:false,
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#dtBankingDate').val(formattedDate);
            /* $('#dtBankingDate').prop('disabled',true); */

        },
        error: function (error) {
            console.log(error);
        },

    })
}




