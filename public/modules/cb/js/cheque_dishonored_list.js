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
                    width: 50,
                    targets: 0,
                    orderable:false
                },
                {
                    width: 80,
                    targets: 1,
                    orderable:false
                },
                {
                    width: 50,
                    targets: 2,
                    orderable:false
                },
                {
                    width: 80,
                    targets:4,
                    orderable:false 
                },
                {
                    width: 120,
                    targets:3,
                    orderable:false,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    }

                },
                {
                    width: 80,
                    targets:5,
                    orderable:false 
                },
                {
                    width: 100,
                    targets:6,
                    orderable:false 
                },
                {
                    width: 120,
                    targets:8,
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
            "columns": [
               
                { "data": "date" },
                { "data": "ref_number" },
                { "data": "customer" },
                { "data": "amount" },
                { "data": "cheque_no" },
                { "data": "bank" },
                { "data": "branch" },
               
                { "data": "cheque_date" },
                { "data": "dep_date" },
                { "data": "reason" },
                { "data": "dis_by" },
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
    /* getAccount(); */
    $('#cmbAccountNumber').change();

    load_dishonoured_cheques();
    $(function() {
        $(".tooltip-target").tooltip();
    });

    $('#btn_dishonur_cheque').on('click',function(){
        if(check_box_array.length < 1 ){
            showWarningMessage('Please select a cheque');
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
                    cheque_dishonour(check_box_array);
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

function edit(id){
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
                cancel_return(id);
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

function load_dishonoured_cheques(){
    
    $.ajax({
            url:'/cb/load_dishonoured_cheques',
            type:'get',
            cache: false,
            timeout: 800000,
            beforeSend: function () { },
            success: function (response) {
                var dt = response.data;
    
                var data = [];
                for (var i = 0; i < dt.length; i++) {
                   var edit = '<button class="btn btn-warning btn-sm" onclick="edit('+dt[i].cheque_returns_id+')" title="Cancel"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>'
                    if(dt[i].is_cancelled != 0){
                        edit = '<button class="btn btn-warning btn-sm" onclick="edit('+dt[i].cheque_returns_id+')" title="Cancel" disabled><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>'
                    }
                   data.push({
                        "date": dt[i].returned_date,
                        "ref_number": '<div data-id="'+dt[i].customer_receipt_cheque_id+'">'+dt[i].external_number+'</div>',
                        "customer":shortenString(dt[i].customer_name,60),
                        "amount":parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "cheque_no":dt[i].cheque_number,
                        "bank":shortenString(dt[i].bank_name,15),
                        "branch":dt[i].bank_branch_name,
                        "cheque_date":dt[i].receipt_date,
                        "dep_date":dt[i].cheque_deposit_date,
                        "reason":dt[i].cheque_dishonur_reason,
                        "dis_by":dt[i].name,
                        "action": edit,
                      
                    });  



                    
                   
                }
    
                var table = $('#cheque_dishonur_list_table').DataTable();
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
/* function getAccount() {
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
} */


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
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
function cheque_dishonour(array){
    var formData = new FormData();
    formData.append('ids',JSON.stringify(array));
    console.log(formData);

    $.ajax({
        url: '/cb/cheque_dishonour/',
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
         
          var status = response.status;
          if(status){
            showSuccessMessage('Cheque dishonoured');
            load_deposited_cheques_for_dishonor();
            return;
          }else if(!status){
            showWarningMessage('Unble to dishonoured');
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
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#dtBankingDate').val(formattedDate);
            $('#dtBankingDate').prop('disabled',true);

        },
        error: function (error) {
            console.log(error);
        },

    });
}
   

function cancel_return(id) {
    $.ajax({
        url: '/cb/cancel_return/'+id,
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
            var status = response.status;
            var msg = response.msg;

            if(msg == "used"){
                showWarningMessage("Unable to Cancel")
            }else if(status){
                showSuccessMessage("Successfully Canceled");
            }else{
                showWarningMessage("Unable to Cancel");
            }

            load_dishonoured_cheques();

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });       
   

}
   