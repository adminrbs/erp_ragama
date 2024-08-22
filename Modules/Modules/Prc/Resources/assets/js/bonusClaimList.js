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
        var table = $('.datatable-button-html5-name').DataTable({
            buttons: {            
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: 'Bonus Claim',
                        exportOptions: {
                            columns: [ 0,1,2,3]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'Bonus Claim',
                        exportOptions: {
                            columns: [ 0,1,2,3]
                        }
                    }
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
                //{ "data": "supplier" },
                { "data": "supplier_invoice" },
                
                { "data": "status" },
               /*  { "data": "approvalStatus" }, */
                { "data": "action" },

            ],
            "stripeClasses": ['odd-row', 'even-row']

        });

        table.column(0).visible(false);


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

var distributor=0;
$(document).ready(function () {
    $('.select').select2();
    getBonusdata(distributor);
    getBranches();
   // getdistributor()
   // getdistributor()
    getadminroll();
    $('#cmbdistributor').change(function () {
        distributor= $(this).val();
 
        getBonusdata(distributor);
        
     });

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
        url: "/sd/getdistributor",
        method: "GET",
        async: false,
        caches: false,
        success: function (response) {
            var dt = response
            console.log(dt);
            var htmlContent = ""
            htmlContent += "<option value='0'>Select Distributor</option>";
            $.each(dt, function (key, value) {

                htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
            })
            $('#cmbdistributor').html(htmlContent);

        }

    })
}

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
                $('#cmbdistributor').html(htmlContent);
                $('#cmbdistributor').prop('disabled', true);
                
                

            } else if (data.length > 1) {
                htmlContent += "<option value=''>Select branch</option>";

                $.each(data, function (key, value) {

                    htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
                });

                $('#cmbdistributor').html(htmlContent);
            }


            $('#cmbdistributor').change();
        },
    })
}
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

function edit(id, status) {

    url = "/prc/bonus_claim?id=" + id + "&paramS=" + status + "&action=edit" + "&task=null";
    window.location.href = url;

}

function view(id, status) {
    
    url = "/prc/bonus_claim?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.location.href = url;
}



//load data to table
function getBonusdata(distributor) {
    $.ajax({
        type: "GET",
        url: "/prc/getBonusdata/" + distributor,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var str_id = "'" + dt[i].bonus_claim_Id + "'";
                var str_status = "'" + dt[i].status + "'";
                var str_primary = dt[i].bonus_claim_Id; // edit button id

                


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
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_id + ',' + str_status + ')" ' + disabled + ' style="display:none;"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>'
                btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + str_primary + ',' + str_status + ')"'+disabled+' style="display:none;><i class="fa fa-trash" aria-hidden="true"></i></button>'
                data.push({
                    "date": dt[i].external_number,
                    "reference": dt[i].external_number,
                    //"supplier": dt[i].supplier_name,
                    "supplier_invoice": dt[i].supppier_invoice_number,
                    "status": dt[i].bonus_claim_date_time,
                   /*  "approvalStatus": label_approval, */
                    "action": btnEdit + '&#160<button class="btn btn-success btn-sm" onclick="view(' + str_id + ',' + str_status + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-secondary btn-sm" onclick="printbonusClaimReportPdf(' + str_id + ')"><i class="fa fa-print" aria-hidden="true"></i></button>',
                });

                

            }

            var table = $('#purchasing_request').DataTable();
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

            getBonusdata(distributor);
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    })

}


