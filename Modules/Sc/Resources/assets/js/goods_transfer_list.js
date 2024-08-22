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
        var table = $('#goods_transfer_list').DataTable({
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
                    width:'100%',
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
                { "data": "date" },
                { "data": "reference" },
                { "data": "from_branch" },
                { "data": "to_branch" },
                { "data": "from_location" },
                { "data": "to_location" },
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
    get_goods_transfer_details();

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

function _delete(id,status){
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

/* function edit(id, status) {

    url = "/prc/goodReciveNote?id=" + id + "&paramS=" + status + "&action=edit" + "&task=null";
    window.location.href = url;

} */

function view(id) {
    var status = null
    url = "/sc/goods_transfer_view?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.location.href = url;
}



//load data to table
function get_goods_transfer_details() {
    $.ajax({
        type: "GET",
        url: "/sc/get_goods_transfer_details",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
               /*  var str_id = "'" + dt[i].goods_received_Id + "'";
                var str_status = "'" + dt[i].status + "'";
                var str_primary = dt[i].goods_received_Id; // edit button id

                


                var label = '<label class="badge badge-pill bg-danger">' + dt[i].status + '</label>';
                if (dt[i].status == "Original") {
                    label = '<label class="badge badge-pill bg-success">' + dt[i].status + '</label>';

                }
                var label_approval = '<label class="badge badge-pill bg-warning">' + dt[i].approval_status + '</label>';
                var disabled = "";
                var disable_print = ""
                
                if(dt[i].approval_status == "Rejected" || dt[i].approval_status == "Pending"){
                    disable_print = "disabled";
                }
                
                if (dt[i].approval_status == "Approved") {
                    label_approval = '<label class="badge badge-pill bg-success">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";


                } else if (dt[i].approval_status == "Rejected") {
                    label_approval = '<label class="badge badge-pill bg-danger">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";
                   
                }
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_id + ',' + str_status + ')" ' + disabled + ' style="display:none;"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + str_primary + ',' + str_status + ')"'+disabled+' style="display:none;><i class="fa fa-trash" aria-hidden="true"></i></button>';
                btnPrint  = '<button class="btn btn-secondary btn-sm" onclick="printGoodResiveReportPdf(' + str_id + ')" ' + disable_print + '><i class="fa fa-print" aria-hidden="true"></i></button>' */
                data.push({
                    "date": dt[i].goods_transfer_date,
                    "reference": dt[i].external_number,
                    "from_branch": dt[i].from_branch,
                    "to_branch": dt[i].to_branch,
                    "from_location": dt[i].from_location,
                    "to_location":dt[i].to_location,
                    /* "action": btnEdit + '&#160<button class="btn btn-success btn-sm" onclick="view(' + str_id + ',' + str_status + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160'+btnDlt+'&#160'+btnPrint, */
                    "action": '<button class="btn btn-success btn-sm" onclick="view('+dt[i].goods_transfer_id+')"><i class="fa fa-eye" aria-hidden="true"></i></button>'
                });
                

            }

            var table = $('#goods_transfer_list').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}



function deleteGRN(id, status) {
    console.log(id);
    $.ajax({
        url: '/prc/deleteGRN/' + id + '/' + status,
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

            getGRNdata();
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })

}


