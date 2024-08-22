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
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 200,
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
                { "data": "suplier" },
                { "data": "delivery_date" },
                { "data": "status" },
                { "data": "approvalStatus" },
                { "data": "amount" },
                { "data": "action" },
                
       
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
    getPOData();

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
                deletePurchaseOder(id, status,);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
    
}

function Approval(id){
    
        url = "/prc/purchaseOrderNote?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.location.href = url;
       
}

function edit(id, status) {

    url = "/prc/purchaseOrderNote?id=" + id +"&paramS="+status+"&action=edit"+"&task=null";
    window.location.href = url;

}

function view(id,status){
    url = "/prc/purchaseOrderView?id=" + id +"&paramS="+status+"&action=view"+"&task=null";
    window.location.href = url;
}

//load data to table
function getPOData(){
    $.ajax({
        url:'/prc/getPOData',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var str_id = "'" + dt[i].purchase_order_Id + "'";
                var str_status = "'" + dt[i].status + "'";
                var str_primary = dt[i].purchase_order_Id; // edit button id
                


                var label = '<label class="badge badge-pill bg-danger">' + dt[i].status + '</label>';
                if (dt[i].status == "Original") {
                    label = '<label class="badge badge-pill bg-success">' + dt[i].status + '</label>';

                }
                var label_approval = '<label class="badge badge-pill bg-warning">' + dt[i].approval_status + '</label>';
                var disabled = "";
                if (dt[i].approval_status == "Approved") {
                    label_approval = '<label class="badge badge-pill bg-success">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";


                } else if (dt[i].approval_status == "Rejected") {
                    label_approval = '<label class="badge badge-pill bg-danger">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";
                }
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_id + ',' + str_status + ')" ' + disabled + '><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + str_primary + ',' + str_status + ')"'+disabled+'><i class="fa fa-trash" aria-hidden="true"></i></button>';
                btnPrint = '<button class="btn btn-secondary btn-sm" onclick="generatePOreport(' + str_id + ')" disabled><i class="fa fa-print" aria-hidden="true"></i></button>';
                if(dt[i].approval_status == "Approved"){
                    btnPrint = '<button class="btn btn-secondary btn-sm" onclick="generatePOreport(' + str_id + ')"><i class="fa fa-print" aria-hidden="true"></i></button>';
                }

                var disc = dt[i].discount_percentage;
                if(isNaN(parseFloat(disc))){
                    disc = 0;
                }

                var disc_amount = 0;
                if(disc == 0){
                    disc_amount = 0
                }else{
                    disc_amount = parseFloat((dt[i].total_sum).replace(/,/g, '')) * (disc / 100);
                }
               
                var total_amount = parseFloat((dt[i].total_sum).replace(/,/g, '')) - disc_amount;
                data.push({
                   
                    "reference": dt[i].external_number,
                    "date": dt[i].purchase_order_date_time,
                    "suplier": dt[i].supplier_name,
                    "delivery_date": dt[i].deliver_date_time,
                    "status":label,
                    "approvalStatus": label_approval,
                    "action": btnEdit + '&#160<button class="btn btn-success btn-sm" onclick="view(' + str_id + ',' + str_status + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160'+btnDlt+''+btnPrint,
                    "amount":'<div style="text-align:right;">'+parseFloat(total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, })+'</div>'
                });
                

               
            }

            var table = $('#purchase_order_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    
}


//delete PO
function deletePurchaseOder(id, status) {
    console.log(id);
    console.log(status);
    $.ajax({
        url: '/prc/deletePo/' + id + '/' + status,
        type: 'delete',
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response.message;
            if (status == "Deleted") {
                showSuccessMessage("Successfully deleted");

            } else {
                showErrorMessage("Something went wrong")
            }

            getPOData();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })

}
