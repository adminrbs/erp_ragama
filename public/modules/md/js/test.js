

        var table = $('#supplerGroupTable').DataTable({
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
                { "data": "supplier_group_id" },
                { "data": "supplier_group_name" },
                { "data": "edit" },
                { "data": "delete" },
                { "data": "is_active" },

            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false);

  


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});

$(document).ready(function(){
   
alert('ooooo')


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
             }
 
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
             var dt = response.data;
             console.log(dt);
             var data = [];
 
             var isChecked = dt[i].is_active ? "checked" : "";
                  data.push({
                     "supplier_group_id": dt[i].supplier_group_id,
                     "supplier_group_name": dt[i].supplier_group_name,
                     "edit": '<button class="btn btn-primary" onclick="edit(' + dt[i].supplier_group_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                     "delete": '<button class="btn btn-danger" onclick="_delete(' + dt[i].supplier_group_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',
                     "is_active": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxGradeStatus" value="1" onclick="cbxGradeStatus(' + dt[i].supplier_group_id + ')" required ' + isChecked + '></lable>',
                 }); 
 
                 /* { "data": "supplier_group_id" },
                 { "data": "supplier_group_name" },
                 { "data": "edit" },
                 { "data": "delete" },
                 { "data": "is_active" }, */
 
             
 
             var table = $('#supplerGroupTable').DataTable();
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
 
 function deleteSupplierGroup(id){
     $.ajax({
         url:'/md/deleteSupplierGroup/'+id,
         type:'delete',
         data: {
             _token: $('input[name=_token]').val()
         },
         headers: {
             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         beforeSend: function () {
 
         }, success: function (response) {
             console.log(response);
             allData();
             showSuccessMessage('Successfully deleted')
         }, error: function (xhr, status, error) {
             console.log(xhr.responseText);
             showErrorMessage('Not deleted')
         }
     })
 }
 
 
 
 //add supplier payment method
 function addSupplierPaymentMethod(){
     $.ajax({
         url:'/md/addSupplierPaymentMethod',
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
             }
 
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