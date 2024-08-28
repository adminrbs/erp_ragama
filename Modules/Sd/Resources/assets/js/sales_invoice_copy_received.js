

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
        $('#invoiceDataTable').DataTable({
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
                    width: 100,
                    targets: 3,
                    orderable: false
                },
                {
                    width: 120,
                    targets: 4,
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

                
                { "data": "ref_number" },
                { "data": "rep" },
                { "data": "customer" },
                { "data": "date" },
                { "data": "amount" },
                { "data": "paid" },
                { "data": "balance" },
                { "data": "check" }

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

$(document).ready(function () {

    load_invoice_details_for_invoie_copy_received();

    $('#btnSave').on('click',function(){
        var collection = [];
        $("#invoiceDataTable tbody tr").each(function () {
             existingSalesInvoiceId = $(this).find("td:first").data("id");
             collection.push(existingSalesInvoiceId);
        });
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
                    //newReferanceID('sales_invoice_copy_issueds', '2750');
                    saveInvoiceCopyIssued(collection);
                  
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





//load invoice details
/* function load_invoice_details_for_invoie_copy_received() {
    $("#invoiceDataTable tbody tr").removeClass("highlight");
    $.ajax({
        url: '/sd/load_invoice_details_for_invoie_copy_received/',
        method: 'GET',
        cache: false,
        timeout: 800000,
        success: function (data) {
            
                var header = data.header;
                $.each(header, function (index, value) {
                   
                        var newRow = $("<tr>");
                        newRow.append("<td data-id=" + value.sales_invoice_Id + ">" + value.external_number + "</td>");
                        newRow.append("<td>" + value.employee_name + "</td>");
                        newRow.append("<td data-id=" + value.customer_id + ">" + value.customer_name + "</td>");
                        newRow.append("<td>" + value.order_date_time + "</td>");
                        newRow.append("<td style='text-align: right;'>" + parseInt(value.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>");
                        newRow.append("<td style='text-align: right;'>" + parseInt(value.paidamount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>");
                        newRow.append("<td style='text-align: right;'>" + parseInt(value.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "</td>");
                        newRow.append(
                            $('<td>').append(
                                $('<input>', {
                                    type: 'checkbox',
                                    class: 'form-check-input',
                                    id: value.sales_invoice_copy_issued_id
                                }).on('change', function () {
                                    selectUnselect(this);
                                })
                            )
                        );
                        

                        $("#invoiceDataTable tbody").append(newRow);
                    
                });

                var data = [];
                for (var i = 0; i < dt.length; i++) {
                    console.log(dt[i].book);
                    btn_info = '<button class="btn btn-success btn-sm tooltip-target" title="Info" onclick="showModal(' + dt[i].direct_cheque_collection_id + ')"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                    chk_box = '<input type="checkbox" class="form-check-input row_checkbox" id="' + dt[i].direct_cheque_collection_id + '" onchange="singleSelect(this)">';
                    data.push({
                        "date": '<div data-id="' + dt[i].direct_cheque_collection_id + '">' + dt[i].trans_date + '</div>',
                        "ref_number": '<div data-id="' + dt[i].direct_cheque_collection_id + '">' + dt[i].external_number + '</div>',
                        "amount": parseFloat(dt[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "branch": dt[i].branch_name,
                        "action": btn_info,
                        "check": chk_box
    
                    });
    
    
    
                }

                var table = $('#direct_cheque_collection_list').DataTable();
                table.clear();
                table.rows.add(data).draw();
    
            
        }
    });
} */
    function load_invoice_details_for_invoie_copy_received() {
        $("#invoiceDataTable tbody tr").removeClass("highlight");
        $.ajax({
            url: '/sd/load_invoice_details_for_invoie_copy_received/',
            method: 'GET',
            cache: false,
            timeout: 800000,
            success: function (data) {
                var header = data.header;
                var data = [];
    
                $.each(header, function (index, value) {
                    var chk_box = '<input type="checkbox" class="form-check-input row_checkbox" id="' + value.sales_invoice_copy_issued_id + '" onchange="selectUnselect(this)">';
    
                    data.push({
                        "ref_number": '<div data-id="' + value.sales_invoice_Id + '">' + value.external_number + '</div>',
                        "rep": value.employee_name,
                        "customer": '<div data-id="' + value.customer_id + '">' + value.customer_name + '</div>',
                        "date": value.order_date_time,
                        "amount": parseInt(value.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "paid": parseInt(value.paidamount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "balance": parseInt(value.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                        "check": chk_box
                    });
                });
    
              
                var table = $('#invoiceDataTable').DataTable();
                table.clear();
                table.rows.add(data).draw();
            }
        });
    }
    

function selectUnselect(){

}


function SelectAll(event) {
    var isChecked = $(event).prop('checked');

    $('#invoiceDataTable tbody input.row_checkbox').each(function () {
        $(this).prop('checked', isChecked);
    });
}



function saveInvoiceCopyIssued(collection) {
    if($('#txtRemark').val().length < 1){
        showWarningMessage('Please add remark');
    }else if(collection.length === 0){
        showWarningMessage('Please select select invoice');
    }else{
        var formData = new FormData();
        //formData.append('LblexternalNumber', referanceID);
        formData.append('collection', JSON.stringify(collection));
        formData.append('txtRemark',$('#txtRemark').val());
        formData.append('emp',$('#cmbEmp').val());
        $.ajax({
            url: '/sd/saveInvoiceCopyIssued',
            method: 'post',
            enctype: 'multipart/form-data',
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
    
                if(response.status){
                    showSuccessMessage('Record Saved');
                    $('#txtRemark').val('');
                    $('#txtInv').val('');
                    $('#invoiceDataTable tbody').empty();
                }else{
                    showWarningMessage('Unable to save');
                }
    
    
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
    
            }
        })
    }

    
}

