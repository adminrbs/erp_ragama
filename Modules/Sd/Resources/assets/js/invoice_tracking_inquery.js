

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
                /*  orderable: false,
                 width: 100,
                 targets: [2] */
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
        var table = $('#invoice_tracking_inquery_list').DataTable({
            /*  buttons: {            
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
                     
                 ]
             }, */

            columnDefs: [

                {
                    width: 10,
                    targets: 0
                },
                {
                    width: 80,
                    targets: 1
                },
                {
                    width: 80,
                    targets: 2
                },
                {
                    width: 80,
                    targets: 3
                },
                {
                    width: 200,
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
                { "data": "check" },
                { "data": "reference" },
                { "data": "date" },
                { "data": "amount" },
                { "data": "customer" },
                { "data": "sales_rep" },
                { "data": "route" },
                { "data": "town" },
                { "data": "balance" },
                { "data": "action" },
                { "data": "modal" },



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

    getServerTime(); //getting current month first date and last date
    load_invoices();

    $('#from_date, #to_date, #cmbAny').on('change', function () {
        load_invoices();
    });



    $('#btn_print').on('click', function () {
        printTable();
    });

    $('#btn_save').on('click', function () {

        conirm_statment_create($('#hiddenItem').val());
    });

});



//getting current month first date and last date
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

            $('#from_date').val(formattedFirstDate);
            $('#to_date').val(formattedLastDate);

            $('#from_date').trigger('change');
            $('#to_date').trigger('change');
            //load_invoices(); // loading invoices
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

//load invoices
function load_invoices() {
    var from_date = $('#from_date').val();
    var to_date = $('#to_date').val();
    var filter_by_id = $('#cmbAny').val();


    $.ajax({
        url: '/sd/load_invoices_for_invoice_tracking/',
        type: 'get',
        async: false,
        data: {
            from: from_date,
            to: to_date,
            filter_by_id: filter_by_id
        },
        success: function (data) {
            var dt = data.data;
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var chkBox = '<input type="checkbox" class="form-check-input" id="' + dt[i].sales_invoice_Id + '" onchange="selectRecord(this)">';
                var create_inquery = '<input type="button" class="btn btn-success" onclick="conirm_inquery_create(' + dt[i].sales_invoice_Id + ',this)" value="+Inquery">';
                if (dt[i].is_inquery_created == 1) {
                    create_inquery = '<input type="button" class="btn btn-success" onclick="conirm_inquery_create(' + dt[i].sales_invoice_Id + ',this)" value="+Inquery" disabled>';
                }
                var showModel_btn = '<button type="button" class="btn btn-success" title="Info" onclick="showModel(this)"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                if (dt[i].is_inquery_created != 1) {
                    showModel_btn = '<button type="button" class="btn btn-success" title="Info" onclick="showModel(this)" disabled><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                }
                data.push({
                    "check": chkBox,
                    "reference": dt[i].external_number,
                    "date": dt[i].order_date_time,
                    "amount": '<div style="text-align:right;">' + parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }) + '</div>',
                    "customer": dt[i].customer_name,
                    "sales_rep": dt[i].employee_name,
                    "route": dt[i].route_name,
                    "town": dt[i].townName,
                    "balance": '<div style="text-align:right;">' + parseFloat(dt[i].balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }) + '</div>',
                    "action": create_inquery,
                    "modal": showModel_btn

                });



                var table = $('#invoice_tracking_inquery_list').DataTable();
                table.clear();
                table.rows.add(data).draw();

            }



        }

    });
}
function conirm_inquery_create(id, event) {
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
                create_inquery(id, event)

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
function create_inquery(id, event) {




    $.ajax({
        url: '/sd/create_inquery/' + id,
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.msg == 'exist') {
                showWarningMessage('Inquery already has been created for this invoice');


            } else if (response.msg == 'success') {
                // load_invoices();
                $(event).prop('disabled', true);
                showSuccessMessage('Inquery Saved Successfuly');
                var primary_key = response.inq_id;

                var next_td = $(event).closest('td').next();
                var nxt_btn = $(next_td).find('button');
                $(nxt_btn).attr('id', primary_key);
                $(nxt_btn).prop('disabled',false);

            } else {
                showWarningMessage('Unable to save');
            }


        }
    })
}

function selectAll(event) {
    var table = $('#invoice_tracking_inquery_list').DataTable();
    if ($(event).prop('checked')) {
        table.rows().every(function () {
            var checkbox = $(this.node()).find('td:first-child input[type="checkbox"]');
            checkbox.prop('checked', true);
        });
    } else {
        table.rows().every(function () {
            var checkbox = $(this.node()).find('td:first-child input[type="checkbox"]');
            checkbox.prop('checked', false);
        });
    }
}


function selectRecord(event) {

    var table = $('#invoice_tracking_inquery_list').DataTable();
    if ($(event).prop('checked')) {

        var checkboxes = table.rows().nodes().to$().find('td input[type="checkbox"]');
        var allChecked = checkboxes.length > 0 && checkboxes.length === checkboxes.filter(':checked').length;
        if (allChecked) {
            $('#chkAll').prop('checked', true);
        }
    } else {
        $('#chkAll').prop('checked', false);
    }
}


function printTable() {
    var pageData = [];
    var table = $('#invoice_tracking_inquery_list').DataTable();
    var checkboxes = table.rows().nodes().to$().find('td input[type="checkbox"]');

    checkboxes.each(function () {
        if ($(this).prop('checked')) {
            var rowData = table.row($(this).closest('tr')).data();
            pageData.push(rowData);
        }
    });


    if (pageData.length > 0) {
        createPage(pageData);
    } else {
        showWarningMessage('Please select a record to print');
    }
}

function createPage(data) {
    var table = '<table style="border-collapse: collapse; width: 100%;"><thead><tr><th style="border: 1px solid black; padding: 8px;">Reference</th><th style="border: 1px solid black; padding: 8px;">Date</th><th style="border: 1px solid black; padding: 8px; width: 150px;">Amount</th><th style="border: 1px solid black; padding: 8px; width: 200px;">Customer</th><th style="border: 1px solid black; padding: 8px;">Sales Rep</th><th style="border: 1px solid black; padding: 8px;">Route</th><th style="border: 1px solid black; padding: 8px;">Town</th></tr></thead><tbody>';
    for (var i = 0; i < data.length; i++) {
        table += '<tr>';
        table += '<td style="border: 1px solid black; padding: 8px;">' + data[i].reference + '</td>';
        table += '<td style="border: 1px solid black; padding: 8px;">' + data[i].date + '</td>';
        table += '<td style="border: 1px solid black; padding: 8px;">' + data[i].amount + '</td>';
        table += '<td style="border: 1px solid black; padding: 8px;">' + data[i].customer + '</td>';
        table += '<td style="border: 1px solid black; padding: 8px;">' + data[i].sales_rep + '</td>';
        table += '<td style="border: 1px solid black; padding: 8px;">' + data[i].route + '</td>';
        table += '<td style="border: 1px solid black; padding: 8px;">' + data[i].town + '</td>';
        table += '</tr>';
    }
    table += '</tbody>';

    var printWindow = window.open('', '_blank');

    printWindow.document.write('<html><head><title></title><style>table, th, td { border: 1px solid black; border-collapse: collapse; }</style></head><body>');
    printWindow.document.write('<h1 style="text-align:center;">Invoice Tracking Inquiry</h1>');
    printWindow.document.write(table);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
}


function load_statments(id) {
    console.log(id);

    var tableBody = $('#pending_inv_data_list_table tbody'); // Selecting the table body

    $.ajax({
        url: '/sd/load_statments_with_inv/' + id,
        type: 'get',
        async: false,
        success: function (response) {
            var dt = response.data;

            // Empty the table body
            tableBody.empty();

            $.each(dt, function (index, row) {
                var created_at = row.created_at;
                var inquery_person_statment = row.inquery_person_statment;
                var employee_name = row.employee_name;
                var tableRow = '<tr>' +
                    '<td>' + created_at + '</td>' +
                    '<td>' + employee_name + '</td>' +
                    '<td>' + inquery_person_statment + '</td>' +
                    '</tr>';

                tableBody.append(tableRow);
            });
        }
    });
}



function loademployees() {
    $.ajax({
        url: '/sd/loademployees',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    })
}

function showModel(event) {
    loademployees();
    var id_ = $(event).attr('id');
    $('#pending_inv_data_list_model').modal('show');
    $('#hiddenItem').val(id_);
    load_statments(id_);

}

function conirm_statment_create(id) {
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
                create_inquery_statment(id)

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
function create_inquery_statment(id) {

    $.ajax({
        url: '/sd/create_inquery_statment/' + id,
        type: 'post',
        data: {
            statment: $('#txtStatement').val(),
            empID: $('#cmbEmp').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.status) {
                showSuccessMessage('Record Saved Successfuly');
                $('#txtStatement').val('');
            } else {
                showWarningMessage('Unable to save');
            }

            load_statments(id);
        }
    })
}

