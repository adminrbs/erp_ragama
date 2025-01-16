const DatatableFixedColumns = function () {

    // Setup module components

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
            autoWidth: false,
            dom: '<"datatable-header justify-content-center"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill"><div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span>',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }


        });


        // Left and right fixed columns
        var table = $('#bin_card_table').DataTable({
            buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Bin Card',
                        text: 'Export to Excel',
                        exportOptions: {
                            columns: [ 0,1,2,3,4,5,6,7]
                        }
                    },
                    /* {
                        extend: 'pdfHtml5',
                        title: 'Purchase Order',
                        exportOptions: {
                            columns: [ 0,1,2,3,4,5,6,7]
                        }
                    } */
                ]
            },
            
            columnDefs: [
                

                {
                    orderable: false,
                    targets: 2
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
                    width: '100%',
                    targets: 2
                },
                {
                    width: 60,
                    targets: 3
                },
                {
                    width: 60,
                    targets: 4
                },
                {
                    width: 60,
                    targets: 5
                },
                {
                    width: 60,
                    targets: 6
                },
                {
                    width: 60,
                    targets: 7
                },
                {
                    width: 100,
                    targets: 8
                },

            ],

            fixedColumns: true,
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            /*  "autoWidth": false, */
            "pageLength": 100,
            "order": [],
            "columns": [
                /*  { "data": "itemcode" },
                 { "data": "itemname" }, */
                { "data": "date" },
                { "data": "referance_number" },
                { "data": "description" },
                { "data": "in_qty", className: "rightAlign" },
                { "data": "out_qty", className: "rightAlign" },
                { "data": "balance", className: "rightAlign" },
                { "data": "whole_sale_price", className: "rightAlign" },
                { "data": "retial_price", className: "rightAlign" },
                { "data": "referance_external" },
                // { "data": "user" }


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

$(document).ready(function () {
    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'DD/MM/YYYY',
        }
    });
    getServerTime(); 
    $('.select2').select2();
    /*  $('.select2').select2({
         matcher: function(params, data) {
             // The default matcher function uses a "contains" search.
             // You can customize it to match only the beginning letters.
             var term = $.trim(params.term);
             var text = data.text;
 
             // If the option's text starts with the search term, return it.
             if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
                 return data;
             }
 
             return null;
         }
     }); */


    $('#cmbBranch').on('change', function () {
        var val_ = $(this).val();
        getLocation(val_);
    });


    getBranches();
    $('#cmbBranch').change();

    getLocation($('#cmbBranch').val());
    $('#cmbLocation').change();
    getproduct();

    //setting current month date range
    /* const startDate = moment().startOf('month');
    const endDate = moment().endOf('month'); */
    /* $('input[name="date_range"]').daterangepicker({
        startDate,
        endDate,
        locale: {
            format: 'DD/MM/YYYY'
        }
    }); */
    
    
  


    $('#cmbBranch').on('change', function () {


        loadItemMovementHistoryData($('#cmbBranch'), $('#cmbItem'), $('#cmbLocation'));
    });

    $('#cmbLocation').on('change', function () {


        loadItemMovementHistoryData($('#cmbBranch'), $('#cmbItem'), $('#cmbLocation'));
    });

    $('#cmbItem').on('change', function () {


        loadItemMovementHistoryData($('#cmbBranch'), $('#cmbItem'), $('#cmbLocation'));
    });

    $('.daterange-single').on('change', function () {


        loadItemMovementHistoryData($('#cmbBranch'), $('#cmbItem'), $('#cmbLocation'));
    });

    loadItemMovementHistoryData($('#cmbBranch'), $('#cmbItem'), $('#cmbLocation'));
});

//load branches
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
                /*   loadCustomerReceipts_cash_branch($('#cmbBranch').val()); */

            } else if (data.length > 1) {
                htmlContent += "<option value='0'>Any</option>";

                $.each(data, function (key, value) {

                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });

                $('#cmbBranch').html(htmlContent);
            }


            $('#cmbBranch').change();
        },
    });
}


//load item
function getproduct() {
    $.ajax({
        type: 'get',
        url: "/sc/getproduct_binCard",
        async: false,
        success: function (data) {
            var dt = data.data
            console.log(dt);
            /*   htmlContent += "<option value=''>Select Product</option>"; */
            $('#cmbItem').append('<option value="">Select Item</option>');
            $.each(dt, function (key, value) {

                $('#cmbItem').append('<option value="' + value.item_id + '">' + value.item_Name + '</option>');


            });



        }

    });

}

//load item movement history data to the table
function loadItemMovementHistoryData(branch, item, locationid) {
    var branch_id_ = $(branch).val();
    var item_id = $(item).val();
   // var date_range_text = $(datePicker).val();
  /*   var date_parts = date_range_text.split('-');
    var from_date = date_parts[0];
    var to_date = date_parts[1]; */
    var loc_id = $(locationid).val();

    var from_date = $('#date_from').val();
    var to_date = $('#date_to').val();


    var formData = new FormData();
    formData.append('branch_id_', branch_id_);
    formData.append('from_date', from_date);
    formData.append('to_date', to_date);
    formData.append('item_id', item_id);
    formData.append('location_id', loc_id);
    $.ajax({
        type: "POST",
        url: "/sc/loadItemMovementHistoryData",
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,

        cache: false,
        async: false,
        timeout: 800000,
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function (data) {
            var dt = data.data
            console.log(dt);
            var data = [];

            $.each(dt, function (key, value) {
                var wh_ = parseFloat(value.whole_sale_price);
                var ret_ = parseFloat(value.retial_price);

                if(isNaN(wh_)){
                    wh_ = 0;
                }

                if(isNaN(ret_)){
                    ret_ = 0;
                }
                data.push({
                    /* "itemcode": value.Item_code,
                    "itemname": value.item_Name, */
                    "date": value.transaction_date,
                    "referance_number": value.reference_number,
                    "description": value.description,
                    "in_qty": parseInt(value.in_quantity),
                    "out_qty": parseInt(value.out_quantity),
                    "balance": parseInt(value.running_total),
                    "whole_sale_price": parseFloat(wh_).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "retial_price": parseFloat(ret_).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "referance_external": value.reference_external_number,
                    // "user": "",
                });

                /* { "data": "itemcode" },
                { "data": "itemname" },
                { "data": "description" },
                { "data": "in_qty" },
                { "data": "out_qty" },
                { "data": "balance" } */


            });

            var table = $('#bin_card_table').DataTable();
            table.clear();
            table.rows.add(data).draw();





        }

    });


}

//load location
function getLocation(id) {

    $('#cmbLocation').empty();
    $.ajax({
        url: '/prc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmbLocation').trigger('change');
        },
    })
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
            var currentDate = new Date(formattedDate);
            // Get the first date of the month
            var firstDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            var formattedFirstDate = formatDate(firstDateOfMonth);

            // Get the last date of the month
            var lastDateOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            var formattedLastDate = formatDate(lastDateOfMonth);
           
            $('#date_from').val(formattedFirstDate);
            $('#date_to').val(formattedLastDate);

        },
        error: function (error) {
            console.log(error);
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
