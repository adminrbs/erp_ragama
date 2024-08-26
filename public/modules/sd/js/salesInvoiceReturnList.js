const DatatableFixedColumns = function () {

    // Basic Datatable examples
    const _componentDatatableFixedColumns = function () {
        let table;
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        if ($.fn.DataTable.isDataTable('.datatable-fixed-both')) {
            table.destroy();
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
                searchPlaceholder: 'Press enter to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });


        // Left and right fixed columns
         table = $('.datatable-fixed-both').DataTable({
            search: {
                return: true
            },
            serverSide: true,
            ajax: {
                url : '/sd/getSalesInvoiceReturnData/'+$('cmbBranch').val(),
               
            },
            columnDefs: [
                
                {
                    width: 150,
                    targets: 0
                },
                {
                    width: 50,
                    targets: 1
                },
                {
                    width: 200,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 3
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "info":false,
            "pageLength": 100,
            "order": [],
            "columns": [
                /* { "data": "manual_number" }, */
                { "data": "external_number" },
                { "data": "order_date" },
                { "data": "info" },
                { "data": "customer_name" },
                { "data": "employee_name" },
                { "data": "total_amount" },
                { "data": "your_reference_number" },
                { "data": "statusLabel" },
                 { "data": "buttons" } 
       
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
   // getSalesInvoiceReturnData();

   getBranches();
   $('#cmbBranch').on('change',function(){
        DatatableFixedColumns.init();
   });

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
                deleteSI(id, status,);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
    
}

function Approval(id){
    
        url = "/sd/salesReturn?id=" + id +"&paramS=Original"+"&action=edit"+"&task=approval";
        window.location.href = url;
       
}

function edit(id, status) {

    url = "/sd/salesReturn?id=" + id +"&paramS="+status+"&action=edit"+"&task=null";
    window.location.href = url;

}

function view(id,status){
    url = "/sd/salesReturnview?id=" + id +"&paramS="+status+"&action=view"+"&task=null";
    window.location.href = url;
}

//load data to table
function getSalesInvoiceReturnData(){
    $.ajax({
        url:'/sd/getSalesInvoiceReturnData',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var str_id = "'" + dt[i].sales_return_Id + "'";
               
                var str_primary = dt[i].sales_invoice_Id; // edit button id

                var label = '<label class="badge badge-pill bg-danger">' + dt[i].status + '</label>';
                if (dt[i].status == "Original") {
                    label = '<label class="badge badge-pill bg-success">' + dt[i].status + '</label>';

                }
                
                var disabled = "";
                if (dt[i].approval_status == "Approved") {
                  
                    disabled = "disabled";


                } else if (dt[i].approval_status == "Rejected") {
                  
                    disabled = "disabled";
                }
                var info = ''
                if(dt[i].si_manual_number){
                    var encodedManualNumber = base64Encode(dt[i].si_manual_number);
                    info = '<a href="../sd/invoice_nfo?manual_number=' + encodedManualNumber+ '&action=inquery" onclick="updateTotal()" target="_blank">' + dt[i].si_manual_number +'&nbsp;&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';

                }
                
              
               
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + str_primary + '" onclick="edit(' + str_id + ')" ' + disabled + ' style="display:none;"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>'
                btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + str_id + ')"'+disabled+'><i class="fa fa-trash" aria-hidden="true"></i></button>'
                data.push({
                   
                    "reference": dt[i].manual_number,
                    "date": dt[i].order_date,
                    "invoice":info,
                    "customer": shortenString(dt[i].customer_name,18),
                    "sales_rep": shortenString(dt[i].employee_name,10),
                    "Amount": parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                    "status":label,
                   
                    "action": btnEdit + '&#160<button class="btn btn-success btn-sm" onclick="view(' + str_id +')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160'+'&#160<button class="btn btn-secondary btn-sm" onclick="generateSalesReturnReport(' + dt[i].sales_return_Id + ')"><i class="fa fa-print" aria-hidden="true"></i></button>', 
                });       
               
            }

            var table = $('#sales_return_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    
}


function base64Encode(str) {
    return btoa(encodeURIComponent(str));
}


//delete PO
function deleteSI(id, status) {
    console.log(id);
    console.log(status);
    $.ajax({
        url: '/sd/deleteSI/' + id + '/' + status,
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

            getSalesInvoiceReturnData();
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

//open info model (use when sales return created without direct invoice)
function viewInfo(return_id){
    $('#exampleModal').modal('show');
    
    loadReturnSetoffData(return_id);
}

//load return set off data
function loadReturnSetoffData(return_id){
    var table = $('#gettable');
    
    var tableBody = $('#gettable tbody');
    tableBody.empty();
    $.ajax({
        url:'/sd/loadReturnSetoffData/'+return_id,
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

           
          

            $.each(dt, function (index, item) {
                var info = ''
                if(item.manual_number){
                    var encodedManualNumber = base64Encode(item.manual_number);
                    info = '<a href="../sd/invoice_nfo?manual_number=' + encodedManualNumber+ '&action=inquery" onclick="updateTotal()" target="_blank">' + item.manual_number +'&nbsp;&nbsp;<i class="fa fa-info-circle text-info fa-lg" aria-hidden="true"></i></a>';

                }
                var row = $('<tr>');
                row.append($('<td>').append(info));
                row.append($('<td>').append($('<label>').attr('data-id', item.manual_number).text(item.setoff_amount)));
                table.append(row);
            });

          
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}


function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
           
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })

        },
    })
}