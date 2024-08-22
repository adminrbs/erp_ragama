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
                searchPlaceholder: 'Type to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });


        // Left and right fixed columns
        var table = $('#item_tbl').DataTable({
            "paging": true,
            "pageLength": 50,
            columnDefs: [
                
                {
                    width:80,
                    targets: 0,
                    orderable:false
                },
                {
                    width: 80,
                    targets: 1,
                    orderable:false
                },
                {
                    width: 200,
                    targets: 2,
                    orderable:false
                },
                {
                    targets: 3,
                    orderable:false
                },
                {
                    targets: 4,
                    orderable:false
                },
                {
                    targets: 5,
                    orderable:false
                },
                {
                    targets: 6,
                    orderable:false
                },
                {
                    targets: 7,
                    orderable:false
                },
              
                
         
            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            info:false,
             fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            }, 
            
           
          
            "columns": [
                { "data": "item_code" },
                { "data": "item_name" },
                { "data": "pack_size" },
                
                { "data": "from_b_stock" },
                { "data": "to_b_stock" },
                { "data": "avg_sale" },
                { "data": "re_order" },
                { "data": "qty" },
              
   
/* 
                "item_code": it[i].Item_code,
                "item_name": it[i].item_name,
                "pack_size": it[i].	package_unit,
                "from_b_stock": it[i].from_branch_stock,
                "to_b_stock": it[i].	to_branch_stock,
                "avg_sale": it[i].avg_sales,
                "re_order": it[i].reorder_level,
                "qty": it[i].quantity, */
              

            ],
            "stripeClasses": [ 'odd-row', 'even-row' ]
            
        });

        table.column(0).visible(false);


    };

    // Return objects assigned to module

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});






var formData = new FormData();
var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;

var sales_order_Id = null;
var reuqestID;
var action = undefined;
var referanceID;
var ItemList;
$(document).ready(function () {
    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
   
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#btnSaveDraft').hide();
   
  
    getServerTime();
   

    //back button
    $('#btnBack').hide();
    $('#btnBack').on('click',function(){
        
            var url = "/sd/getSalesOrderList"; 
            window.location.href = url;
    });


   
    getBranches();
    $('#cmbBranch').change();


   

   
   
   

    

   

    //from list
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        /*   reuqestID = param[0].split('=')[1].split('&')[0]; */
        sales_order_Id = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
         
        
         
        
        if (action == 'edit' && status == 'Original' && task == 'approval') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#chk').hide();
            $('#btnBack').show();
        }
        else if (action == 'edit' && status == 'Original') {
            if(parseInt(order_type_status) != 0){
              
                showWarningMessage('Unauthorized Access');
                var url = "/sd/getSalesOrderList"; 
                window.location.href = url;
                return;
             }else if(parseInt(is_order_status) != 1){
                showWarningMessage('Unauthorized Access');
                var url = "/sd/getSalesOrderList"; 
                window.location.href = url;
                return;
             }else{
             
                $('#btnSave').text('Update');
                $('#btnSaveDraft').hide();
                $('#btnApprove').hide();
                $('#btnReject').hide();
                $('#btnBack').show();
             }
          

        } else if (action == 'edit' && status == 'Draft') {
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
          /*   $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();

        } else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            $('#btnBack').show();
           // disableComponents();

        }

        getEachInternalOrder(sales_order_Id);
        
    }




});






function getEachInternalOrder(id) {

    /* formData.append('status', status); */
    $.ajax({
        url: '/sc/getEachInternalOrder/' + id ,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (response) {
            
            var res = response.data;
            var it = response.items;
           
            console.log(res);
           var data = [];
            $('#LblexternalNumber').val(res.external_number);
            $('#order_date_time').val(res.order_date_time);
            $('#cmbBranch').val(res.from_branch_id);
            $('#ToBranch').val(res.to_branch_id);
            $('#from_date').val(res.from_date);
            $('#to_date').val(res.to_date);

            for (var i = 0; i < it.length; i++) {
            data.push({
                "item_code": it[i].Item_code,
                "item_name": it[i].item_name,
                "pack_size": it[i].	package_unit,
                "from_b_stock": Math.abs(it[i].from_branch_stock),
                "to_b_stock": Math.abs(it[i].to_branch_stock),
                "avg_sale": it[i].avg_sales,
                "re_order": it[i].reorder_level,
                "qty": Math.abs(it[i].quantity),
                
            });
        }
        var table = $('#item_tbl').DataTable();
        table.clear();
        table.rows.add(data).draw();  

          

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}




//reset form
function resetForm() {
    $('.validation-invalid-label').empty();
    $('#form').trigger('reset');
    $('#lblGrossTotal').text('0.00');
    $('#lblNetTotal').text('0.00');
    $('#lblTotalDiscount').text('0.00');
    $('#lblTotaltax').text('0.00');


}

// clear table
function clearTableData() {
    dataSource = [];
    tableData.setDataSource(dataSource);

}


function closeCurrentTab() {
    setTimeout(function () {
        window.close();
    }, 1000);

   
}

//get server time
function getServerTime() {
    $.ajax({
        url: '/sd/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#order_date_time').val(formattedDate);
            


            var currentDate = new Date(formattedDate);
            // Get the first date of the month
            var firstDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            var formattedFirstDate = formatDate(firstDateOfMonth);

            // Get the last date of the month
            var lastDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            var formattedLastDate = formatDate(lastDateOfMonth);
           console.log(lastDateOfMonth);
            $('#from_date').val(formattedFirstDate);
            $('#to_date').val(formattedLastDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}









function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#ToBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
            })

        },
    })
}



function formatDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1; // Months are zero-based
    var year = date.getFullYear();

    // Pad day and month with leading zeros if needed
    day = day < 10 ? '0' + day : day;
    month = month < 10 ? '0' + month : month;

    return day + '/' + month + '/' + year;
}