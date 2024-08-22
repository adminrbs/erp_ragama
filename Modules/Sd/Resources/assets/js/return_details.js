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
        var table = $('.datatable-fixed-both').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    width:250,
                    targets: 3
                },
                {
                    width: 100,
                    targets: 0
                },
                {
                    width: 100,
                    targets: 1
                },
                {
                    width: 100,
                    targets: 2
                },
                {
                    orderable: false,
                    width:100,
                    targets: 4
                },
                {
                    orderable: false,
                    width:70,
                    targets: 5
                },
                {
                    orderable: false,
                    width:40,
                    targets: 6
                },
                {
                    orderable: false,
                    width:100,
                    targets: 7
                },
                {
                    orderable: false,
                    width:70,
                    targets: 8
                },
                {
                    orderable: false,
                    width:150,
                    targets: 9
                },
                {
                    orderable: false,
                    width:50,
                    targets: 10
                },
                {
                    orderable: false,
                    width:40,
                    targets: 11
                },
                {
                    orderable: false,
                    width:40,
                    targets: 12
                },
                {
                    orderable: false,
                    width:40,
                    targets: 13
                },
                {
                    orderable: false,
                    width:50,
                    targets: 14
                },


            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 10
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "rtn_date" },
                { "data": "reference" },
                { "data": "inv_number" },
                { "data": "customer" },
                /* { "data": "SO_manual_number" }, */
                { "data": "rep_name" },
              /*   { "data": "book_name" },
                { "data": "book_number" },
                { "data": "page_number" }, */
                { "data": "rtn_user" },
                { "data": "branch" },
                { "data": "location" },
                { "data": "reason" },
                { "data": "item_name" },
                { "data": "pack_size" },
                { "data": "qty" },
                { "data": "foc" },
                { "data": "total_qty" },
                { "data": "action" } 
       
            ],
            "stripeClasses": ['odd-row', 'even-row']

         

        });

        


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

$(document).ready(function(){
    get_sales_retrun_details();

    //calling rtn item status update function
    $('#btn_save').on('click',function(){

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
                    update_sales_return_item_status();
                    
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



//load data to table
function get_sales_retrun_details(){
    $.ajax({
        url:'/sd/get_sales_retrun_details_info',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {     
                var checkBox = '<input type="checkbox" class="form-check-input" name="" id="'+dt[i].sales_return_item_id+'">'     
                data.push({
                    "rtn_date": dt[i].order_date,
                    "reference": dt[i].sr_manual,
                    "inv_number": dt[i].si_manual,
                    "customer": dt[i].customer_name,
                   /*  "SO_manual_number": "", */
                    "rep_name":dt[i].rep_name,
                  /*   "book_name":dt[i].book_name,
                    "book_number":dt[i].book_number,
                    "page_number":dt[i].page_number, */
                    "rtn_user":dt[i].rtn_user,
                    "branch":dt[i].branch_name,
                    "location":dt[i].location_name,
                    "reason":dt[i].sales_return_resons,
                    "item_name":dt[i].item_name,
                    "pack_size":dt[i].package_unit,
                    "qty":parseInt(dt[i].quantity),
                    "foc":parseInt(dt[i].free_quantity),
                    "total_qty":parseInt(dt[i].total_qty),
                    "action":checkBox, 
                });
               
            }

            var table = $('#sales_return_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    
}

function update_sales_return_item_status(){
    var retrun_item_IDs_array = [];
    $('#sales_return_table tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.is(':checked')) {
            retrun_item_IDs_array.push($(checkbox).attr('id'));
        }
        
    });
    console.log(retrun_item_IDs_array);
    var formData = new FormData();
    formData.append('retrun_item_IDs_array',JSON.stringify(retrun_item_IDs_array));
    
    $.ajax({
        url: '/sd/update_sales_return_item_status',
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
            var message = response.message;
            var status = response.status
            if (message != 'success') {
                showWarningMessage('Unable to update');
                return;
            }
            if(status){
                showSuccessMessage('Record Updated');
                get_sales_retrun_details();
                return;
               
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
