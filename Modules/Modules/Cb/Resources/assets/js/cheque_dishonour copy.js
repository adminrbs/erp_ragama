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
                    width: 100,
                    targets: 0
                },
                {
                    width: 120,
                    targets: 1
                },
                {
                    width: 300,
                    targets: 2
                },
                {
                    width: 120,
                    targets: 4
                },
                {
                    width: 150,
                    targets: 5
                },
                {
                    width: 120,
                    targets: 6
                },
                {
                    width: 120,
                    targets: 7
                },
                {
                    width: 110,
                    targets: 3
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
                { "data": "dis_reason" },
                { "data": "charges" },
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
$(document).ready(function () {
    $(function () {
        $(".tooltip-target").tooltip();
    });
    getServerTime();
    /* getAccount(); */
    $('#cmbAccountNumber').change();

    load_deposited_cheques_for_dishonor();

    /* $('#btn_dishonur_cheque').on('click',function(){
        if(check_box_array.length < 1 ){
            showWarningMessage('Please select a cheque');
            return;
        }

      
       
       
    }); */


});

function coinfirmReturn(event,id_) {
    bootbox.confirm({
        title: 'Dishonor confirmation',
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
                dishonour_cheque_return(event,id_);
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

//load customer receipts for cash collecion by branch list
function load_deposited_cheques_for_dishonor() {

    $.ajax({
        url: '/cb/load_deposited_cheques_for_dishonor/',
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            var id = '';
            var id_array = []
            for (var i = 0; i < dt.length; i++) {
                var _check_box = '<input class="form-check-input tooltip-target" type="checkbox" id="' + dt[i].customer_receipt_cheque_id + '" onchange="update_status_calculation(this)" title="Show return button">';
                var return_button = '<button class="btn btn-danger btn-sm tooltip-target rtnBtn" onclick="coinfirmReturn(this'+','+ dt[i].customer_receipt_cheque_id + ')" id="' + dt[i].customer_receipt_cheque_id + '" title="Dishonor cheque" hidden>Dishonour</button>';
                var reason ='<select class="form-select tooltip-target" id="cmb'+dt[i].customer_receipt_cheque_id+'" title="Dishonour reasons"><option value="0">Select Reason</option></select>';
                var charges ='<input type="number" class="form-control tooltip-target" id="'+dt[i].customer_receipt_cheque_id+'" title="Dishonour charges">';
               // load_dishonour('cmb'+dt[i].customer_receipt_cheque_id);
               id = dt[i].customer_receipt_cheque_id;
               id_array.push(id);
                data.push({
                    "date": dt[i].cheque_deposit_date,
                    "ref_number": '<div data-id="' + dt[i].customer_receipt_cheque_id + '">' + dt[i].external_number + '</div>',
                    "customer": shortenString(dt[i].customer_name, 50),
                    "amount": parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "cheque_no": dt[i].cheque_number,
                    "dis_reason":reason,
                    "charges":charges,
                    "action": _check_box + ' ' + return_button,

                });
                
                
            }

            var table = $('#cheque_dishonur_table').DataTable();
            table.clear();
            table.rows.add(data).draw();
            for(var j =0; j < id_array.length; j++){
               
                    load_dishonour_reasons('cmb'+id_array[j]);
            }
           
        
            
           

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

//load dishonour reasons
function load_dishonour_reasons(cmb){
    $.ajax({
        url: '/cb/load_dishonour_reasons',
        type: 'get',
        async: false,
        success: function (data) {
            var dt = data.data;
            var htmlContent = "";

        /*     $.each(dt, function (index, value) {
                $(cmb).append('<option value="' + value.cheque_dishonur_reason_id + '">' + value.cheque_dishonur_reason + '</option>');
           
            }); */

            $.each(dt, function (key, value) {

                htmlContent += "<option value='" + value.cheque_dishonur_reason_id + "'>" + value.cheque_dishonur_reason + "</option>";
            });
            $('#'+cmb).html(htmlContent);
           /*  console.log(htmlContent); */
            $(cmb).change();
           


        },
    });

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
    });
}


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}

function update_status_calculation(event) {
    /* var count = 0; */

    var $row = $(event).closest('tr');
    var checkBoxId = $(event).attr('id');
    var button = $row.find('.rtnBtn');

    if ($(event).prop('checked')) {
        $(button).removeAttr('hidden');
    } else {
        $(button).attr('hidden', 'true');
    }


    /*   if ($(event).prop('checked')) {
          count = parseFloat($('#row_count').text()) + 1
          check_box_array.push(checkBoxId)
      } else {
          count = parseFloat($('#row_count').text()) - 1
          var index = check_box_array.indexOf(checkBoxId);
          if (index !== -1) {
              check_box_array.splice(index, 1); 
          }
      } */
    /*  $('#row_count').text(count); */

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
//dishonour check
function dishonour_cheque_return(event,id) {
   console.log(event);
    var currentRow = $(event).closest("tr");
    console.log(currentRow);
    var selectValue = currentRow.find('td:eq(5) select').val();
    var inputValue = currentRow.find('td:eq(6) input[type="number"]').val();
    if(isNaN(parseFloat(inputValue))){
        showWarningMessage('Dishonoured charges needs to be entered');
        return;
    }
    if(inputValue == 0){
        showWarningMessage('You entered 0.00 dishonoured charges');
    }
   
    $.ajax({
        url: '/cb/dishonour_cheque_return_controller/'+id,
        method: 'get',
        data: {
            selectVal: selectValue,
            inputVal: inputValue
        },
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
            if (status) {
                showSuccessMessage('Cheque dishonoured');
                load_deposited_cheques_for_dishonor();
                /*   $('#row_count').text('0'); */
                return;
            } else {
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
            $('#dtBankingDate').prop('disabled', true);

        },
        error: function (error) {
            console.log(error);
        },

    })
}


