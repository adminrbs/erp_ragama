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
                    orderable:false,
                    width: 80,
                    targets: 0
                },
                {
                    orderable:false,
                    width: 80,
                    targets: 1
                },
                {
                    orderable:false,
                    width: 80,
                    targets: 2
                },
                {
                    orderable:false,
                    width: 50,
                    targets:4 
                },
                {
                    orderable:false,
                    width: 80,
                    targets:5
                },
                {
                    orderable:false,
                    width: 25,
                    targets:6
                },
                {
                    orderable:false,
                    width: 50,
                    targets:7,
                    

                },
                {
                    orderable:false,
                    width: 25,
                    targets:8
                },
                {
                    orderable:false,
                    width: 100,
                    targets:3,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    }
 
                },
                {
                    orderable:false,
                    width: 10,
                    targets:9 
                },
                {
                    orderable:false,
                    width: 20,
                    targets:10 
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
                { "data": "chq_date" },
                { "data": "bank" },
                { "data": "branch" },
                
                { "data": "book" },
                { "data": "page" },
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

$(document).ready(function(){
    getServerTime();
   /*  $('#top_border').on('scroll touchmove mousewheel', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }); */

    getBranches();
    $('#cmbBranch').change();


    $('#cmbBranch').on('change',function(){
        loadCustomerReceipts_cheque_ho($(this).val());
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    })

    //calling status update function
    $('#btn_chq_ho_save').on('click',function(){

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
                    update_chq_ho();;
                    
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

//load customer receipts for cash collecion by branch list
function loadCustomerReceipts_cheque_ho(br_id){
    console.log(br_id);
    $.ajax({
            url:'/cb/loadCustomerReceipts_cheque_ho/'+br_id,
            type:'get',
            cache: false,
            timeout: 800000,
            beforeSend: function () { },
            success: function (response) {
                var dt = response.data;
    
                var data = [];
                for (var i = 0; i < dt.length; i++) {
                   var cash_check_box = '<input class="form-check-input" type="checkbox" id="'+dt[i].customer_receipt_cheque_id + '" onchange="update_status_calculation_cheque_ho(this)">';
                   
                    data.push({
                        "date": dt[i].receipt_date,
                        "ref_number": '<div data-id="'+dt[i].customer_receipt_id+'">'+dt[i].external_number+'</div>',
                        "customer":'<div title="'+dt[i].customer_name+'">'+shortenString(dt[i].customer_name,13)+'</div',
                        "amount":parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "cheque_no":dt[i].cheque_number,
                        "chq_date":dt[i].banking_date,
                        "bank":dt[i].bank_code,
                        "branch":dt[i].bank_branch_code,
                        "book":'<div title="'+dt[i].customer_name+'">'+shortenString(dt[i].book,10)+'</div>',
                        "page":dt[i].page_no,
                        "action": cash_check_box,
                      
                    });  
                   
                }
    
                var table = $('#cheque_collection_by_ho_table').DataTable();
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
            if(data.length <=1 ){
                $.each(data, function (key, value) {

                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });
                $('#cmbBranch').html(htmlContent);
                $('#cmbBranch').prop('disabled',true);
                loadCustomerReceipts_cheque_ho($('#cmbBranch').val());
                
            }else if(data.length > 1){
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

function update_status_calculation_cheque_ho(event){
    var total = 0.0;
    var count = 0;
          /*   var check_box_values = {}; */
            var $row = $(event).closest('tr');
           /*  var checkBoxId = $(event).attr('id'); */

            // Receipt id
            var $second_cell = $row.find('td:eq(1)');
            var $div = $second_cell.find('div');
            var receipt_id = $div.data('id');

            var third_cell = $row.find('td:eq(3)');
            var _amount = third_cell.text();

            var status_cell = $row.find('td:eq(5) input[type="checkbox"]');
            var status = status_cell.is(':checked') ? 1:0;

            if ($(event).prop('checked')) {
                total = parseFloat($('#sum_label').text().replace(/,/g, '')) +  parseFloat(_amount.replace(/,/g, '')); // Add to total
                count = parseFloat($('#row_count').text()) + 1
            } else {
                total = parseFloat($('#sum_label').text().replace(/,/g, '')) - parseFloat(_amount.replace(/,/g, '')); // Subtract from total
                count = parseFloat($('#row_count').text()) - 1
            }
            $('#sum_label').text(parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })).addClass('h4');
            $('#row_count').text(count);
            return;
            var formData = new FormData();
            formData.append('status',status);
           
            
              console.log(formData);
              
        
            $.ajax({
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
            })            
    }

     //update chq branch
     function update_chq_ho(){
        var cheque_id_array = [];
        $('#cheque_collection_by_ho_table tbody tr').each(function () {
            var checkbox = $(this).find('input[type="checkbox"]');
            if (checkbox.is(':checked')) {
                var chk_id = $(checkbox).attr('id')
                cheque_id_array.push(chk_id);
                     
            }   
        });
        
        if(cheque_id_array.length < 1){
            showWarningMessage('Please select a reocrd');
            return;
        }
        console.log(cheque_id_array);
        
        var formData = new FormData();
        formData.append('cheque_id_array',JSON.stringify(cheque_id_array));
        console.log(formData);
    
                $.ajax({
                    url: '/cb/update_chq_ho',
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
                        
                      var message = response.message;
                      if(response.status){
                        showSuccessMessage('Record updated');
                        loadCustomerReceipts_cheque_ho($('#cmbBranch').val());
                        $('#sum_label').text(0.00).addClass('h4');
                        $('#row_count').text(0);
                      }else{
                        showWarningMessage('Unable to update')
                      }
            
                    }, error: function (data) {
                        console.log(data.responseText)
                    }, complete: function () {
            
                    }
                })            
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
            $('#cashDate').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}
   

