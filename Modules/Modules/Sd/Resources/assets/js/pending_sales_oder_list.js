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
            autoWidth: false,
            dom: '<"datatable-header justify-content-center"f<"ms-sm-auto"l><"ms-sm-3"B>><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }

        });

        // Left and right fixed columns
        $('.datatable-button-html5-name').DataTable({
            buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Sales Order',
                        exportOptions: {
                            columns: [ 0,1,2,3,4,5,6,7]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Sales Order',
                        exportOptions: {
                            columns: [ 0,1,2,3,4,5,6,7]
                        }
                    }
                ]
            },
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
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
                    width: 80,
                    targets: 1
                },
                {
                    width: 280,
                    targets:2
                },
               /*  {
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-amount-right">' + data + '</div>' : data;
                    },
                    width: 100,
                    targets: 6
                }, */

            ],
            scrollX: true,
            /*  scrollY: 600, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "external_number" },
                { "data": "order_date_time" },
                { "data": "customer_name" },
                { "data": "employee_name" },
                { "data": "order_type" },
                { "data": "deliver_date_time" },
                { "data":"amount",className: "text-amount-right", },
                { "data": "Status" },
                { "data": "ActionMenu" }

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
var distributor=0;
$(document).ready(function () {
    getSalesOrderDetails(distributor);
    getdistributor()
    getadminroll();
    $('#cmbBranch').change(function () {
        distributor= $(this).val();
 
        getSalesOrderDetails(distributor);
        
     });

});
function getadminroll(){
    $.ajax({
        url: "/getadminrole",
        type: 'get',
        async: false,
        success: function (response) {
            var dt = response
            if (dt.length > 0 && dt[0].role_id === 1) {

                $('#adDistributor').show()
            } else {
                $('#adDistributor').hide()
            }

        }

    })
}
function getdistributor() {
    $.ajax({
        url: "/getBranches",
        method: "GET",
        async: false,
        caches: false,
        success: function (response) {
            var dt = response
            console.log(dt);
            var htmlContent = ""
            htmlContent += "<option value='0'>Select Branch</option>";
            $.each(dt, function (key, value) {

                htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
            })
            $('#cmbBranch').html(htmlContent);
            $('#cmbBranch').trigger('change');

        }

    })
}
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
                deleteSalesOrder(id, status,);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

/* function Approval(id){
    
        url = "/sd/salesOrder?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.open(url, "_blank");
       
} */

function edit(id, status) {

    url = "/sd/salesOrder?id=" + id + "&paramS=" + status + "&action=edit" + "&task=null";
    window.location.href = url;

}

function view(id, status) {
    url = "/sd/salesOrderview?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.location.href = url;
}


function getSalesOrderDetails(distributor) {
    $.ajax({
        type: "GET",
        url: "/sd/getSalesOrderPendingDetails/"+ distributor,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            var disabled = "";

            for (var i = 0; i < dt.length; i++) {
                var str_id = "'" + dt[i].sales_order_Id + "'";
                var str_status = "'" + "Original" + "'";
                var str_primary = dt[i].sales_order_Id; // edit button id

                if (dt[i].order_status_id > 1) {
                    disabled = "disabled";
                }

                label = '<label class="badge badge-pill bg-success">' + str_status + '</label>';


                var label_approval = '<label class="badge badge-pill bg-warning">' + dt[i].approval_status + '</label>';

                if (dt[i].approval_status == "Approved") {
                    label_approval = '<label class="badge badge-pill bg-success">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";


                } else if (dt[i].Sales_order_type != "ERP") {
                    disabled = "disabled";

                } else if (dt[i].approval_status == "Rejected") {
                    label_approval = '<label class="badge badge-pill bg-danger">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";
                }
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_primary + ',' + str_status + ')" ' + disabled + '><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>'
                data.push({
                    "external_number": dt[i].external_number,
                    "order_date_time": dt[i].order_date_time,
                    "customer_name": "<div title='"+dt[i].customer_name+"'>"+shortenString(dt[i].customer_name, 16)+"</div>",
                    "employee_name": "<div title='"+dt[i].employee_name+"'>"+shortenString(dt[i].employee_name,15)+"</div>",
                    "order_type": dt[i].Sales_order_type,
                    "deliver_date_time": dt[i].expected_date_time,
                    "amount":'<div style="text-align:right;">'+parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, })+'</div>',
                    "Status": label,
                    "ActionMenu": btnEdit + '&#160<button class="btn btn-success btn-sm" onclick="view(' + str_id + ',' + str_status + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-secondary btn-sm" onclick="salesorderReport(' + str_primary + ')" disabled><i class="fa fa-print" aria-hidden="true"></i></button>',
                });
            }


            var table = $('#sales_oderTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function deleteSalesOrder(id, status) {
    console.log(id);
    console.log(status);
    $.ajax({
        url: '/sd/deleteSO/' + id + '/' + status,
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

            getSalesOrderDetails(distributor);
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })
}


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}