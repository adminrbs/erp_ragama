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
            processing: true,
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/sc/get_all_reverse_trasfers',
               
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

                { "data": "trans_date" },
                { "data": "external_number" },
                { "data": "branch_name" },
                { "data": "dispatch_ref" },
                
                { "data": "statusLabel" },
             
                { "data": "total_amount" },
                { "data": "buttons" },
       
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
   // getPendingapprovalsGRN();
});


function approve(id){
    
        url = "/sc/reverse_devision_transfer?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.open(url, "_blank");
       
}

function edit(id, status) {

    url = "/prc/goodReciveNote?id=" + id +"&paramS=Original"+"&action=edit"+"&task=null";
    window.open(url, "_blank");

}

function view(id, status) {
   
    status = "Original";
    url = "/sc/reverse_devision_transfer?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.location.href = url;
}



//load data to table
function getPendingapprovalsGRN(){
    $.ajax({
        url:'/prc/getPendingapprovalsGRN',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
               
                var label_approval = '<label class="badge badge-pill bg-warning">' + dt[i].approval_status + '</label>';
                if (dt[i].approval_status == "Approved") {
                    label_approval = '<label class="badge badge-pill bg-success">' + dt[i].approval_status + '</label>';
                } else if (dt[i].approval_status == "Rejected")
                    label_approval = '<label class="badge badge-pill bg-danger">' + dt[i].approval_status + '</label>';

                    var str_id = "'" + dt[i].goods_received_Id + "'";
                    var str_status = "'" + dt[i].Status + "'";
                    /* var str_id_new = dt[i].purchase_request_Id */
                data.push({
                    "reference": dt[i].external_number,
                    "branch": dt[i].branch_name,
                    "Amount": parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "Date": dt[i].goods_received_date_time,
                    "dueDate": dt[i].payment_due_date,
                    
                    "action": '<button class="btn btn-primary btn-sm" onclick="edit(' + str_id + ',' + str_status+ ')" style="display:none;><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success btn-sm" onclick="view(' + str_id + ',' + str_status+ ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger btn-sm" onclick="_delete(' + str_id + ',' + str_status+ ')" style="display:none;><i class="fa fa-trash" aria-hidden="true"></i></button>&#160<button class="btn btn-info btn-sm" onclick="Approval(' + str_id + ')"><i class="fa fa-check-square-o" aria-hidden="true"></i></button>',
                });
                
            }

            var table = $('#approval_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    
}