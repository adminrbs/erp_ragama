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
                    width:'40%',
                    targets: 2
                },
                {
                    width: '10%',
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
                { "data": "supplier_id" },
                { "data": "supplier_code" },
                { "data": "supplier_name" },
                { "data": "primary_address" },
                { "data": "primary_mobile_number" },
                { "data": "status" },
                { "data": "action" },
   

            ],
            "stripeClasses": [ 'odd-row', 'even-row' ]
            
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

$(document).ready(function(){
    getSupplierDetails();



});

//calling delete function with bootbox
function _delete(id) {
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
           if(result){
            deleteSupplier(id);
           
           }else{

           }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
 
}


function edit(id) {

    var url = "/md/supplier?id=" + id + "&action=edit";
    window.open(url, "_blank");

}

function view(id) {
    var url = "/md/supplier?id=" + id + "&action=view";
    window.open(url, "_blank");
}

function getSupplierDetails() {
    
    $.ajax({
        type: "GET",
        url: "/md/getSupplierDetails",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            disabled = "disabled";
            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var label =  '<label class="badge badge-pill bg-danger">'+dt[i].supplier_status+'</label>';
                if(dt[i].supplier_status == "Active"){
                    label =  '<label class="badge badge-pill bg-success">'+dt[i].supplier_status+'</label>';
                }else if(dt[i].supplier_status == "Suspend"){
                    label =  '<label class="badge badge-pill bg-warning">'+dt[i].supplier_status+'</label>';
                    
                }
                data.push({
                    "supplier_id": dt[i].supplier_id,
                    "supplier_code": dt[i].supplier_code,
                    "supplier_name": dt[i].supplier_name,
                    "primary_address": dt[i].primary_address,
                    "primary_mobile_number": dt[i].primary_mobile_number,
                    "status": label,
                    "action":'<button class="btn btn-primary btn-sm" onclick="edit(' + dt[i].supplier_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160<button class="btn btn-success btn-sm" onclick="view(' + dt[i].supplier_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].supplier_id + ')"'+disabled+'><i class="fa fa-trash" aria-hidden="true"></i></button>',
                });

           
   
            }

            var table = $('#customerListTable').DataTable();
                table.clear();
                table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}


function deleteSupplier(id){
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteSupplier/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        },success:function(response){
            var status = response.message;
            if(status == "Deleted"){
                showSuccessMessage("Successfully deleted");
                
            }else{
                showErrorMessage("Something went wrong")
            }
            
            
        },error:function(xhr,status,error){
            console.log(xhr.responseText);
        },complete: function () {
            getSupplierDetails();
        }
    });

}