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
        $('#missed_sales_orders').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            columnDefs: [
                {
                    width: 80,
                    targets: 0,
                    orderable: false,
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 1
                },
                {
                    orderable: false,
                    width: 80,
                    targets: 2
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 3
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 4
                },
                {
                    orderable: false,
                    width: 120,
                    targets: 5
                },
                {
                    orderable: false,
                    width: 40,
                    targets: 6
                },
                {
                    orderable: false,
                    width: 60,
                    targets: 7,
                    "className": "custom-text-right"
                },
                {
                    orderable: false,
                    width: 60,
                    targets: 8,
                    "className": "custom-text-right"
                },
                {
                    orderable: false,
                    width: 40,
                    targets: 9
                },

            ],
            scrollX: true,
             scrollY: 600,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 3
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "order_date" },
                { "data": "external_number" },
                { "data": "invoice_date" },
                { "data": "invoice_number" },
                { "data": "Item_code" },
                { "data": "item_name" },
                { "data": "pacs" },
                { "data": "o_qty" },
                { "data": "m_qty" },
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

$(document).ready(function () {
    get_missed_order_sales();

    $('#btnSave').on('click', function () {

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
                    update_status_();
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



/* function Approval(id){
    
        url = "/sd/salesOrder?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.open(url, "_blank");
       
} */
/* 
function edit(id, status) {

    url = "/sd/salesOrder?id=" + id + "&paramS=" + status + "&action=edit" + "&task=null";
    window.location.href = url;

}

function view(id, status) {
    url = "/sd/salesOrderview?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.location.href = url;
} */





var id_array = [];
function update_status_() {
    var table = $('#missed_sales_orders');
    table.find('tr').each(function () {
        var checkbox = $(this).find('td:eq(9) input[type="checkbox"]:checked');
        if (checkbox.length > 0) {
            var checkboxId = checkbox.attr('id');
            id_array.push(checkboxId);
        }

    });
console.log(id_array);

if(id_array.length < 1){
    showWarningMessage('Please select a record');
}else{
    var formData = new FormData();
formData.append('id_array',JSON.stringify(id_array));
    $.ajax({
        type: "POST",
        url: "/sd/update_missed_order_sales_status",
        cache: false,
        data:formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnSave').prop('disabled', true);
        }, success: function (response) {
            //console.log(response);
            $('#btnSave').prop('disabled', false);

                var status = response.status;
                if(status){
                    showSuccessMessage("Record updated");
                    get_missed_order_sales();
                }else{
                    showWarningMessage("Unable to update");
                }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });

}





}


function get_missed_order_sales() {
    $.ajax({
        type: "GET",
        url: "/sd/get_missed_order_sales",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            var disabled = "";

            for (var i = 0; i < dt.length; i++) {

                var _check_box = '<input class="form-check-input" type="checkbox" id="' + dt[i].sales_order_item_id + '">';
                data.push({
                    "order_date": dt[i].ordered_date,
                    "external_number": dt[i].order_number,
                    "invoice_date": dt[i].invoiced_date,
                    "invoice_number": dt[i].invoice_number,
                    "Item_code": dt[i].Item_code,
                    "item_name": shortenString(dt[i].item_Name,25),
                    "pacs": dt[i].pack_size,
                    "o_qty": Math.abs(dt[i].order_qty),
                    "m_qty": Math.abs(dt[i].missed_oreder_qty),
                    "action": _check_box
                });



            }


            var table = $('#missed_sales_orders').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}




function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}