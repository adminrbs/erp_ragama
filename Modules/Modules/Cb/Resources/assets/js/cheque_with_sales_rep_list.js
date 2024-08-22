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
                    targets: 0,
                    orderable:false
                },
                {
                    width: 100,
                    targets: 1,
                    orderable:false
                },
                {
                    width: 50,
                    targets: 2,
                    orderable:false
                }
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
               
                { "data": "rep" },
                { "data": "cheque_in_hand" },
                { "data": "late_cash" },
                { "data": "info" }

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
    
    load_cheque_with_sales_rep();
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });


    
  
});

function load_cheque_with_sales_rep(){
    $.ajax({
        url:'/cb/load_cheque_with_sales_rep/',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.cheque;
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                info = '<a href="#" onclick="viewInfo(\'' + dt[i].employee_id + '\', \'' + dt[i].employee_name + '\'); return false;" title="Information"><i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';


                 data.push({
                     "rep": dt[i].employee_name,
                     "cheque_in_hand": '<div data-id="'+dt[i].employee_id+'" style="text-align:right;">'+parseFloat(dt[i].total_cheque).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+'</div>',
                     "late_cash":'<div style="text-align:right;">'+parseFloat(dt[i].total_late).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })+'</div>',  
                     "info":info
                    });  
                
             }

             var table = $('#cheque_with_sales_rep_table').DataTable();
             table.clear(); 
             table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { 

         }
    });

        
    
}

function viewInfo(id,empName){
    $('#cheque_with_Sales_rep_model').modal('show');
    $('#txtHiddenId').val(id);
    $('#empNamelbl').text("SFA Receipts - " + empName)
    load_cheque_with_rep_data(id)
}

function load_cheque_with_rep_data(id) {
    var total_table = $('#total_cheque_table');
    var total_tbody = $('#total_cheque_table tbody');
    var late_table = $('#late_cheque_table');
    var late_tbody = $('#late_cheque_table tbody');

    $.ajax({
        url: '/cb/load_cheque_with_rep_data/' + id,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () {},
        success: function (response) {
           // console.log(response);
            var total_cheque = response.total;
            var late_cheque = response.late;

            // Clear existing data
            total_tbody.empty();
            late_tbody.empty();

           
            // Append new data for total cash
            for (var i = 0; i < total_cheque.length; i++) {
                var row = '<tr>';
                row += '<td>' + total_cheque[i].external_number + '</td>';
                row += '<td>' + total_cheque[i].created_at + '</td>';
                row += '<td>' + total_cheque[i].customer_name + '</td>';
                row += '<td>' + total_cheque[i].bank_code + '</td>';
                row += '<td>' + total_cheque[i].bank_branch_code + '</td>';
                row += '<td>' + total_cheque[i].cheque_number + '</td>';
                row += '<td style="text-align:right;">' + parseFloat(total_cheque[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
                row += '</tr>';
                total_tbody.append(row);
            }

            // Append new data for late cash
            for (var i = 0; i < late_cheque.length; i++) {
                var row = '<tr>';
                row += '<td>' + late_cheque[i].external_number + '</td>';
                row += '<td>' + late_cheque[i].created_at + '</td>';
                row += '<td>' + late_cheque[i].customer_name + '</td>';
                row += '<td>' + late_cheque[i].bank_code + '</td>';
                row += '<td>' + late_cheque[i].bank_branch_code + '</td>';
                row += '<td>' + late_cheque[i].cheque_number + '</td>';
                row += '<td style="text-align:right;">' + parseFloat(late_cheque[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>';
                row += '<td>' + late_cheque[i].age + '</td>';
                row += '</tr>';
                late_tbody.append(row);
            }
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () {}
    });
}
