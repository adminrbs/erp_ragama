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
                    targets: 0,
                    orderable: false
                },
                {
                    width: 110,
                    targets: 1,
                    orderable: false
                },
                {
                    width: 80,
                    targets: 2,
                    orderable: false
                },
                {
                    width: 30,
                    targets:3,
                    orderable: false 
                },
                {
                    width: 120,
                    targets:4,
                    orderable: false 
                },
                {
                    width: 100,
                    targets:5,
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
               
                { "data": "date" },
                { "data": "ref_number" },   
                { "data": "Cashier" },
                { "data": "amount" },
                { "data": "branch" },
                { "data": "action" }
               
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
   /*  $('#top_border').on('scroll touchmove mousewheel', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    }); */
    
    //getServerTime();

    $('#cmbBranch').on('change',function(){
        load_direct_cash_create_bundle( $('#cmbBranch').val());
    }); 
   
    load_direct_cash_create_bundle(0);
    

    $('#btn_cash_ho_save').on('click',function(){

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
                    update_directcash_ho();
                    
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

    

    /* $('#cmbBranch').on('change',function(){
        loadCustomerReceipts_cash_branch($(this).val());
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    }) */

        getBranches();
});
//validate remark textbox
function validate(event){
    var inputValue = $(event).val();
    if (inputValue.includes('|')) {
        $(event).val(inputValue.replace('|', ''));
        showWarningMessage('Use valid symbols');
    }

}
//load direct customer receipts for cash collecion - ho
function load_direct_cash_create_bundle(br_id){
    
    $.ajax({
            url:'/cb/load_direct_cash_create_bundle/'+br_id,
            type:'get',
            cache: false,
            timeout: 800000,
            beforeSend: function () { },
            success: function (response) {
                var dt = response.data;
    console.log(dt);
  
                var data = [];
                for (var i = 0; i < dt.length; i++) {
                    console.log(dt[i].book);
                   var cash_check_box = '<input class="form-check-input" type="checkbox" id="'+dt[i].customer_receipt_id+'" onchange="update_labels(this)">';
                
                    data.push({
                        "date": '<div data-id="'+dt[i].customer_receipt_id+'">' + dt[i].receipt_date + '</div>',
                        "ref_number":'<div data-id="'+dt[i].customer_receipt_id+'">' + dt[i].external_number + '</div>',
                        "Cashier":shortenString(dt[i].name,50),
                        "amount":parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "branch":dt[i].branch_name,
                        "action": cash_check_box,
                      
                    });  

                 
                   
                }
    
                var table = $('#cash_collection_by_ho_table').DataTable();
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

                htmlContent += "<option value=''>Select branch</option>";

                $.each(data, function (key, value) {
    
                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });
    
                $('#cmbBranch').html(htmlContent);
            
            
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

function update_labels(event){
    var total = 0.0;
    var count = 0;
          /*   var check_box_values = {}; */
            var $row = $(event).closest('tr');
           /*  var checkBoxId = $(event).attr('id'); */

            // Receipt id
            var $second_cell = $row.find('td:eq(1)');
            var $div = $second_cell.find('div');
            var receipt_id = $div.data('id');

            //cash bundle id
            var $zero_cell = $row.find('td:eq(0)');
            var $div_ = $zero_cell.find('div');
            var cash_bundle_id = $div_.data('id');

            var third_cell = $row.find('td:eq(3)');
            var _amount = third_cell.text();

            

            var divElement_remark= $row.find('td:nth-child(6) div');
            var textbox = divElement_remark.find('input[type="text"]');
            var textboxValue = textbox.val();

            if ($(event).prop('checked')) {
                total = parseFloat($('#sum_label').text().replace(/,/g, '')) +  parseFloat(_amount.replace(/,/g, '')); // Add to total
                count = parseFloat($('#row_count').text()) + 1
            } else {
                total = parseFloat($('#sum_label').text().replace(/,/g, '')) - parseFloat(_amount.replace(/,/g, '')); // Subtract from total
                count = parseFloat($('#row_count').text()) - 1
            }
            $('#sum_label').text(parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })).addClass('h4');
            $('#row_count').text(count);
           
            
    }


    //update cash ho
    function update_directcash_ho(){
        var cash_id_array = [];
        $('#cash_collection_by_ho_table tbody tr').each(function () {
            var checkbox = $(this).find('input[type="checkbox"]');
            if (checkbox.is(':checked')) {
                var textbox = $(this).find('input[type="text"]');
                
                var chk_id = $(checkbox).attr('id')
                cash_id_array.push(chk_id);
               
               
            }
           
            
        });
        
        if(cash_id_array.length < 1){
            showWarningMessage('Please select a reocrd');
            return;
        }
        console.log(cash_id_array);
        
        var formData = new FormData();
        formData.append('cash_id_array',JSON.stringify(cash_id_array));
        console.log(formData);
    
                $.ajax({
                    url: '/cb/update_directcash_ho',
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
                        load_direct_cash_ho($('#cmbBranch').val());
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
   







