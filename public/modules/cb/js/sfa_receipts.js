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
                    width: 150,
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
                    width: 5,
                    targets: 5,
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
               
                { "data": "customer" },
                { "data": "amount" },
                { "data": "rep" },
                { "data": "type" },
                { "data": "chequeNo" },
                { "data": "status"},
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
document.addEventListener('DOMContentLoaded', function() {
    DatatableFixedColumns.init();
});


/* --------------end of data table--------- */
var global_branch_id_ = undefined;
var global_collector_id = undefined;
$(document).ready(function () {
   
    $('.select2').select2();
   

    getBranches();
    $('#cmbBranch').change();

    loademployees();
    $('#cmbEmp').change();



    $('#cmbBranch').on('change', function () {
        global_branch_id_ = $(this).val();
        load_sfa_receipts($(this).val(),global_collector_id);
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    }); 

    $('#cmbEmp').on('change', function () {
        
        global_collector_id = $(this).val();
        load_sfa_receipts(global_branch_id_,$(this).val());
        $('#sum_label').text('0.00').addClass('h4');
        $('#row_count').text('0');
    });

    $('#cmbEmp').trigger('change');

   

    $('#cmbBank').on('change', function () {
        getBankBranch($(this).val());
    });


});

//load all sfa receipts for edit and reject
function load_sfa_receipts(br_id,collector_id_) {
   
    console.log(br_id);
    $.ajax({
        url: '/cb/load_sfa_receipts/' + br_id +'/'+collector_id_,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;
          

            var data = [];
           
            for (var i = 0; i < dt.length; i++) {

                btn = '<button class="btn btn-success btn-sm" onclick="showModel(this)" id="'+dt[i].customer_receipt_id+'"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                data.push({
                    "ref_number": '<div data-id="' + dt[i].customer_receipt_id + '">' +dt[i].external_number+ '</div>',
                    "date": '<div data-id="' + dt[i].customer_receipt_id + '">' + dt[i].receipt_date + '</div>',
                    "customer": '<div title="'+dt[i].customer_name+'">'+shortenString(dt[i].customer_name,18)+'</div>',
                    "amount": parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "rep":dt[i].employee_name,
                    "type":dt[i].payment_method,
                    "chequeNo":dt[i].cheque_number,
                    "status":dt[i].status,
                    "action": btn
                   
                });     

            }
            var table = $('#sfa_receipts_table').DataTable();
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
                load_sfa_receipts($('#cmbBranch').val(),$('#cmbEmp').val());
                

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


function showModel(event){
    $("#receipt_modal").modal("show");
    $('#hiddenItem').val($(event).attr('id'));
    load_sfa_reciepts_for_change($(event).attr('id'))
    
}

function load_sfa_reciepts_for_change(id) {
    var table = $('#receipts_table');
    var tableBody = $('#receipts_table tbody');
    tableBody.empty();

    $.ajax({
        type: "GET",
        url: "/cb/load_sfa_reciepts_for_change/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            console.log(dt);
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').text(item.receipt_date));
                row.append($('<td>').text(item.external_number));
                row.append($('<td>').attr('style', 'text-align: right;').text(parseFloat(item.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').text(item.payment_method));
                row.append($('<td>').text(item.change_to));

                var banks = $('<select id="cmbBank" class="form-select" onchange="getBankBranch(this)" style="width: 200px;"></select>');
                var branches = $('<select id="cmbBankBranch" class="form-select" style="width: 200px;"></select>');
                var textBox_chq_no = $('<input class="form-control" style="width: 70px;">', { type: 'text', class: 'form-control' }); 
                if(item.change_to == "Cash"){
                    banks = $('<select id="cmbBank" class="form-select" onchange="getBankBranch(this)" style="width: 200px;" disabled></select>');
                    branches = $('<select id="cmbBankBranch" class="form-select" style="width: 200px;" disabled></select>');
                    textBox_chq_no = $('<input class="form-control" style="width: 70px;" disabled>', { type: 'text', class: 'form-control' }); 
                }
                
                row.append($('<td>').append(banks));
                

               /*  var banks = $('<select id="cmbBank"></select>').on('change', function() {
                    var selectedValue = $(this).val();
                    getBankBranch(selectedValue); 
                }); */
                
                row.append($('<td>').append(branches));

                
                row.append($('<td>').append(textBox_chq_no));

                var textBox_remark = $('<input class="form-control" style="width: 200px;" id="txtRemark">', { type: 'text', class: 'form-control' }); 
               
                
                table.append(row);
            });

            getBank();
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}


function getBank() {

    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getBank',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
                var banks = response.data;
                $('#cmbBank').empty();
                for (var i = 0; i < banks.length; i++) {
                    var id = banks[i].bank_id;
                    var name = banks[i].bank_name;
                    $('#cmbBank').append('<option value="' + id + '">' + name + '</option>');
                }

              // getBankBranch($('#cmbBank').val());
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}

function getBankBranch(event) {
    var bank_id = $(event).val()
   
    $.ajax({
        type: "GET",
        url: '/cb/customer_receipt/getBankBranch/' + bank_id,
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            if (response.status) {
               
                var banks = response.data;
                console.log(banks);
                
                $('#cmbBankBranch').empty();
                for (var i = 0; i < banks.length; i++) {
                    var id = banks[i].bank_branch_id;
                    var name = banks[i].bank_branch_name;
                    $('#cmbBankBranch').append('<option value="' + id + '">' + name + '</option>');
                }
            }else{
                
            }

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}

function changeType(){
    var rcpt_id = $('#hiddenItem').val();
    var formData = new FormData(); // Ensure this is a valid FormData object
 
    var firstRow = $('#receipts_table tbody tr:first');
    var cellText = firstRow.find('td').eq(4).text();
    console.log(cellText);
    
    if(cellText != "Cash"){
         formData.append('bank_id', $('#cmbBank').val());
         formData.append('bank_branch_id', $('#cmbBankBranch').val());
         formData.append('cheque_no', $('#txtChqNo').val());
        
    }
    formData.append('remark', $('#txtRemark').val());
    $.ajax({
         url: '/cb/changeType/' + rcpt_id,
         type: 'POST',
         data: formData,
         processData: false,  // Prevent jQuery from processing the data
         contentType: false,  // Prevent jQuery from overriding the content type
         cache: false,
         timeout: 800000,
         beforeSend: function () { },
         success: function (response) {
             console.log('Success:', response);
         },
         error: function (error) {
             console.log('Error:', error);
         },
         complete: function () { }
     });
 }
 

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

        },
        error: function (error) {
            console.log(error);
        },

    })
}




