

$(document).ready(function(){
    
    $('#btnSupplygroup').show();
    $('#btnUpdateSupplygroup').hide();

    
    $('#btnsupplierGroup').on('click', function () {
        
        $('#btnSupplygroup').show();
        $('#btnUpdateSupplygroup').hide();
        $('#id').val('');
        $("#txtSupplierGroup").val('');


    });

    $("#btnClose").on("click", function(e) {
       
        // Prevent the default form submission behavior
        e.preventDefault();
        var formData = $("form").serialize();
        $.ajax({
          type: "POST",
          url: '/md/close',
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
          data: formData,
          success: function(response) {
            $("#supplierGroupModel").modal("hide"); // This will close the modal
            var urlWithoutQuery = window.location.href.split('?')[0];
        },
          error: function(xhr, status, error) {

          }
        });
      });



    getSupplierGroupDetails();
       $('#btnSupplygroup').on('click',function(){
         addSupplierGroup(); 
        
    });

    $('#btnUpdateSupplygroup').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateSuplyGroup();
    });
    

    $(document).on('click', '.suplyGroup', function(e) {
        e.preventDefault();
        let supply_group_id  = $(this).attr('id');

        //alert(supply_group_id)
        $.ajax({
            url: '/md/supplierGroupEdite/'+supply_group_id,
            method: 'get',
            data: {
                //id: id,
                _token: '{{ csrf_token() }}'
            },

            success: function(response) {
                console.log(response);
                $('#btnSupplygroup').hide();
                $('#btnUpdateSupplygroup').show();
    
                $('#id').val(response.supplier_group_id);
                $("#txtSupplierGroup").val(response.supplier_group_name);
    
    
            }
        });
    });
    
    
    DatatableFixedColumnsxx.init();

 
    
    
    // Initialize module
    // ------------------------------
    
  
});



const DatatableFixedColumnsxx = function () {


    //
    // Setup module components
    //

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
          var table = $('.datatable-fixed-both-supplierGRP').DataTable({
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
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "supplier_group_id"},
                { "data": "supplier_group_name"},
                { "data": "edit"},
                { "data": "delete"},
                { "data": "is_active"},


            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false); 


        //payment mode
           
           var table = $('.datatable-fixed-both-supPaymentMethod').DataTable({
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
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "supplier_payment_id"},
                { "data": "supplier_payment_name"},
                { "data": "edit"},
                { "data": "delete"},
                { "data": "is_active"},


            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false); 


  

    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();


document.addEventListener('DOMContentLoaded', function () {

   
}); 



function addSupplierGroup(){
   
    /* formData.append('txtSupplierGroup', $('#txtSupplierGroup').val()); */
    var data = $('#supplier_group').serialize();
     $.ajax({
         url:'/md/addSupplierGroup',
         method:'POST',
         data:data,
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function () {
             console.log(data);
         },
         success: function (response) {
             
             if (response.status) {
             $('#supplierGroupModel').modal('hide');
             showSuccessMessage('Successfully save');
             }else{
                 showErrorMessage('Something went wrong');
                 $('#supplierGroupModel').modal('hide');
                 getSupplierGroupDetails();
             }
             getSupplierGroupDetails();
 
         },
         error: function (error) {
 
             showErrorMessage('Something went wrong');
             $('#supplierGroupModel').modal('hide');
             console.log(error);
 
         },
         complete: function () {
 
         }
 
     })
 }
 
 function getSupplierGroupDetails(){
  
     $.ajax({
         type: "GET",
         url: "/md/getSupplierGroupDetails",
         cache: false,
         timeout: 800000,
         beforeSend: function () { },
         success: function (response) {
             var dt = response;
             console.log(dt);
             var data = [];
             for (var i = 0; i < dt.length; i++) {
             var isChecked = dt[i].is_active ? "checked" : "";
                  data.push({
                     "supplier_group_id": dt[i].supplier_group_id,
                     "supplier_group_name": dt[i].supplier_group_name,
                     "edit": '<button class="btn btn-primary suplyGroup" data-bs-toggle="modal" data-bs-target="#supplierGroupModel" id="' + dt[i].supplier_group_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                     "delete": '<button class="btn btn-danger" onclick="_deletesuGroup(' + dt[i].supplier_group_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                     "is_active": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxsupplyGroupStatus" value="1" onclick="cbxSupplyGrouptatus(' + dt[i].supplier_group_id + ')" required ' + isChecked + '></lable>',
                 }); 
             }
                 /* { "data": "supplier_group_id" },
                 { "data": "supplier_group_name" },
                 { "data": "edit" },
                 { "data": "delete" },
                 { "data": "is_active" }, */
 
             
 
             var table = $('#supplierGRPtable').DataTable();
             table.clear();
             table.rows.add(data).draw();
 
         },
         error: function (error) {
             console.log(error);
         },
         complete: function () { }
     })
 }
 
 /* function updateSupplierGroup(){
 
 } */

 
// suply Group Update

function updateSuplyGroup(){

    var id = $('#id').val();
    formData.append('txtSupplierGroup', $('#txtSupplierGroup').val());

    var cat1 = $('#txtSupplierGroup').val();
    if(cat1 == ""){
        showErrorMessage('Something went wrong');
    }else{

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/supplierGroupUpdate/'+id,
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {

            getSupplierGroupDetails();
            $('#supplierGroupModel').modal('hide');

            showSuccessMessage('Successfully updated')

        }, error: function (error) {
            console.log(error);
            showErrorMessage('Something went wrong')
            $('#supplierGroupModel').modal('hide');
        }
    });
}
}


function _deletesuGroup(id) {

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
                className: 'btn-info'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                deletesupplyGroup(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deletesupplyGroup(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deleteSupplierGroup/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            if(response.success){
                getSupplierGroupDetails();
           
               showSuccessMessage('Successfully deleted');
           }else{
               showWarningMessage('Uneble to Delete')
           }
           
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
            showErrorMessage('Not deleted')
        }
    });
}



function cbxSupplyGrouptatus(supplier_group_id) {
    var status = $('#cbxsupplyGroupStatus').is(':checked') ? 1 : 0;


    $.ajax({
        url: '/md/supplierGroupStatus/' + supplier_group_id,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'status': status
        },
        success: function (response) {
            showSuccessMessage('saved')
            console.log("data save");
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
            showErrorMessage('Not saved')
        }
    });
}

 
 /**supplier payment list */
function getSupplierPaymentMethod(){
    
    $.ajax({
        type: "GET",
        url: "/md/getSupplierPaymentMethod",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response;
            console.log(dt);
            var data = [];
            for (var i = 0; i < dt.length; i++) {
            var isChecked = dt[i].is_active ? "checked" : "";
                 data.push({
                    "supplier_payment_id": dt[i].supplier_payment_method_id,
                    "supplier_payment_name": dt[i].supplier_payment_method,
                    "edit": '<button class="btn btn-primary" onclick="edit(' + dt[i].supplier_payment_method_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                    "delete": '<button class="btn btn-danger" onclick="_delete(' + dt[i].supplier_payment_method_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                    "is_active": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxGradeStatus" value="1" onclick="cbxGradeStatus(' + dt[i].supplier_payment_method_id + ')" required ' + isChecked + '></lable>',
                }); 
            }
               
            

            var table = $('#supplierPaymentModeTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}


