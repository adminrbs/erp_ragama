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
        var table = $('#payment_voucher').DataTable({
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Goods Received List',
                        text: 'Export to Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    /* {
                        extend: 'pdfHtml5',
                        title: 'Purchase Order',
                        exportOptions: {
                            columns: [ 0,1,2,3,4,5,6]
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
                    width: 200,
                    targets: 0
                },
                {
                    width: '30%',
                    targets: 1
                },
                {
                    width: '100%',
                    targets: [2]
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [

                { "data": "reference" },
                { "data": "date" },
                { "data": "supplier" },
                { "data": "payee" },
                { "data": "branch" },
                { "data": "amount" },
                { "data": "action" },

            ],
            "stripeClasses": ['odd-row', 'even-row']

        });

        //table.column(0).visible(false);
        


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
    getPaymentVouchers();

    var reuqestID;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var reuqestID = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];

        if (action == 'edit') {
            $('#btnSave').text('Update');
        } else if (action == 'view') {
            $('#btnSave').hide();
        }


    }
});

function _delete(id, status) {
    bootbox.confirm({
        title: 'Delete confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i class="fa fa-times fa-5x text-danger" ></i></div><div class="d-flex justify-content-center align-items-center "><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-Danger'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-link'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                deleteGRN(id, status,);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
}

function edit(id, status) {

    url = "/cb/payment_voucher?id=" + id  + "&action=edit";
    window.location.href = url;

}

function view(id, status) {

    url = "/cb/payment_voucher_view?id=" + id  + "&action=view";
    window.location.href = url;
}

function printVoucher(id) {
    paymentVoucher_Receipt(id);
}


//load data to table
function getPaymentVouchers() {
    $.ajax({
        type: "GET",
        url: "/cb/getGRNdata",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            var data = [];
            var disabled = "";
            for (var i = 0; i < dt.length; i++) {
               
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + dt[i].payment_voucher_id + '" onclick="edit(' + dt[i].payment_voucher_id +')" ' + disabled + '"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].payment_voucher_id + ')"' + disabled + '><i class="fa fa-trash" aria-hidden="true"></i></button>';
                btnPrint = '<button class="btn btn-secondary btn-sm" onclick="printVoucher(' + dt[i].payment_voucher_id + ')"><i class="fa fa-print" aria-hidden="true"></i></button>'

                data.push({
                    "reference": dt[i].external_number,
                    "date": dt[i].transaction_date,
                    "supplier": dt[i].supplier_name,
                   "payee": dt[i].payee_name !== null ? dt[i].payee_name : dt[i].not_applicable_payee,
                    "branch": dt[i].branch_name,
                    "amount": '<div style="text-align:right;">' + parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }) + '</div>',
                    "action": btnEdit+ ' &#160<button class="btn btn-success btn-sm" onclick="view(' + dt[i].payment_voucher_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160' + btnDlt + '&#160' + btnPrint,
                });

            }

            var table = $('#payment_voucher').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { 

         }
    })
}



function deleteVOucher(id) {
    console.log(id);
    $.ajax({
        url: '/db/deleteVOucher/' + id,
        type: 'delete',
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response.success;
            if (status) {
                showSuccessMessage("Successfully deleted");

            } else {
                showErrorMessage("Something went wrong")
            }

            getGRNdata();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })

}


