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
                searchPlaceholder: 'Type to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });


        // Left and right fixed columns
        var table = $('.datatable-fixed-both').DataTable({
            "paging": true,
            "pageLength": 50,
            columnDefs: [
                
                {
                    width:80,
                    targets: 0,
                    orderable:false
                },
                {
                    width: 80,
                    targets: 1,
                    orderable:false
                },
                {
                    width: 280,
                    targets: 2,
                    orderable:false
                },
                {
                    targets: 3,
                    orderable:false
                },
                {
                    targets: 4,
                    orderable:false
                },
                {
                    targets: 5,
                    orderable:false
                },
                {
                    targets: 6,
                    orderable:false
                },
                {
                    targets: 7,
                    orderable:false
                },
                {
                    targets: 8,
                    orderable:false
                },
                {
                    targets: 9,
                    orderable:false
                }
                
         
            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            info:false,
             fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            }, 
            
           
          
            "columns": [
                { "data": "customer_id" },
                { "data": "customer_code" },
                { "data": "customer_name" },
                
                { "data": "non_town" },
                { "data": "route" },
                { "data": "primary_mobile_number" },
                { "data": "customer_group" },
                { "data": "primary_address" },
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



$(document).ready(function () {
    getCustomerDetails();

});



function edit(id) {

    var url = "/md/customer?id=" + id + "&action=edit";
    window.open(url, "_blank");

}

function view(id) {
    var url = "/md/customer?id=" + id + "&action=view";
    window.open(url, "_blank");
}

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
                deleteCustomer(id);
           }else{

           }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
 
}



//append customer details to the table
function getCustomerDetails() {
    $.ajax({
        type: "GET",
        url: "/md/getCustomerDetails",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            disabled = "disabled";

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var label =  '<label class="badge badge-pill bg-danger">'+dt[i].customer_status+'</label>';
                if(dt[i].customer_status == "Active"){
                    label =  '<label class="badge badge-pill bg-success">'+dt[i].customer_status+'</label>';
                }else if(dt[i].customer_status == "Suspend"){
                    label =  '<label class="badge badge-pill bg-warning">'+dt[i].customer_status+'</label>';
                    
                }
                var buttons = "";
                if(md_edit_customer == 1){
                    buttons += '<button class="btn btn-primary" onclick="edit(' + dt[i].customer_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160'
                }

                if(md_delete_customer == 1){
                    buttons += '<button class="btn btn-success" onclick="view(' + dt[i].customer_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160'
                }

                if(md_view_customer == 1){
                    buttons += '<button class="btn btn-danger" onclick="_delete(' + dt[i].customer_id + ')"' + disabled + '><i class="fa fa-trash" aria-hidden="true"></i></button>'
                }
                data.push({
                    "customer_id": dt[i].customer_id,
                    "customer_code": shortenString(dt[i].customer_code,15),
                    "customer_name": shortenString(dt[i].customer_name,40),
                 
                    "non_town": dt[i].townName,
                    "route": dt[i].route_name,
                    "primary_mobile_number": dt[i].primary_mobile_number,
                    "customer_group": dt[i].group,
                    "primary_address": dt[i].primary_address,
                    "status": label,
                    "action":buttons
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

//delete customer
function deleteCustomer(id){
    /* if(validateDeleteItem('customers', 'customer_id',id)){
        
        showWarningMessage('Customer already used');
        return;
     } */
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteCustomer/' + id,
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
            
            getCustomerDetails();
        },error:function(xhr,status,error){
            console.log(xhr.responseText);
        }
    });
}




function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}
