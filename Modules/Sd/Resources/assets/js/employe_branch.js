/* ----------------------------Data table------------------------------ */
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

        $('.datatable-fixed-both').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width:200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                
               
            ],
            scrollX: false,
            scrollY: 350,
            scrollCollapse: true,

            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "action"},
                { "data": "employee_name" },
                { "data": "customer_name" },
                //{ "data": "credit_allowed" },
                //{ "data": "credit_control_type"},
   

            ],
            "drawCallback": function (settings) {
                var table = settings.oInstance.api();
                var tableWrapper = $(table.table().container());
    
                // Add separating line under the headings and records
                tableWrapper.find('.dataTable thead th, .dataTable tbody tr').each(function () {
                    $(this).css('border-bottom', '1px solid #ccc');
                    $(this).css('height', '50px'); // Adjust the row height here
                });
            },
          
          
            "stripeClasses": [ 'odd-row', 'even-row' ],
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },

        });


       


        //
        // Fixed column with complex headers
        //

      

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


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});









/* ----------------------------------------------------------------------------- */
// Setup module dual list box
// ------------------------------

const DualListboxes = function () {

    var option_array = [];
    var listBox = undefined;
    // Dual listbox
    const _componentDualListbox = function () {
        if (typeof DualListbox == 'undefined') {
            console.warn('Warning - dual_listbox.min.js is not loaded.');
            return;
        }

        // Buttons text
        const listboxButtonsElement = document.querySelector(".listbox-buttons");
        const listboxButtons = new DualListbox(listboxButtonsElement, {
            options: option_array,

            addEvent: function (value) {

              
                var list = DualListboxes.getSelectedOptions();
                
               
            },
            removeEvent: function (value) {
                var selectedBranchId = $('#cmbEmployee').val();

               

                if (selectedBranchId && value) {
                   
                    $.ajax({
                        url: '/sd/selectdeletemployeeBranch', 
                        type: 'POST', 
                        data: {
                            branchId: selectedBranchId,
                            eventValue: value
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            getEmployeeCustomerDetails();
                            showSuccessMessage('Successfully deleted')
                        },
                        error: function (error) {
                           
                            
                        }
                    });
                } else {
                    console.error('selectedBranchId or value is missing.');
                }

                //alert(selectedBranchId);
            },


            addButtonText: "<i class='ph-caret-right'></i>",
            removeButtonText: "<i class='ph-caret-left'></i>",
            addAllButtonText: "<i class='ph-caret-double-right'></i>",
            removeAllButtonText: "<i class='ph-caret-double-left'></i>",

        });

        listBox = listboxButtons;

        /*   const selectedValues = listboxButtons.getSelected;
          console.log(selectedValues); */

        /* const selectedValues = listboxButtons.selected.map(option => option.value);
        console.log(selectedValues); */



    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentDualListbox();
        },

        geOptionArray: function () {

            return option_array;
        },

        getSelectedOptions: function () {
            var selected_options = [];
            if (listBox != undefined) {
                var list = listBox.selected;
                for (var i = 0; i < list.length; i++) {
                    selected_options.push($(list[i]).attr('data-id'));
                }
            }
            return selected_options;
        },

        clear: function () {
            option_array = [];
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DualListboxes.init();
});


$(document).ready(function () {

    $('#cmbFilterBy').val('0');
    $('#cmbEmployee').change(function () {
      
        //var selectedBranchId = $('#cmbBranch').val();
        getselectemployee();
       
    })
   
    var selectedValue;
    getEmployeeCustomerDetails();
    getEmployeeDetails();
    $('#cmbFilterBy').change(function () {
        selectedValue = $('#cmbFilterBy').val();
        getFilteredData(selectedValue)

    });




    $('#btnSave').on('click', function (e) {
        e.preventDefault();
        addEmployeeCustomer();
        var list = DualListboxes.getSelectedOptions();
        console.log(list);
    });

    $('#btnDlt').on('click',function (e){
       // deleteCustomerLocation();
       deleteConfirmaion();
    } )



});


//getting customers data from DB and appending to list box
function getFilteredData(id) {
    const dualListBox = document.getElementById('cmbFilterData');

    $.ajax({
        url: '/sd/getEmployeeDataTOlistbox/' + id,
        method: 'get',
        datatype: 'json',
        success: function (data) {

            //make an empty array if array has data 
            if (DualListboxes.geOptionArray().length > 0) {
                DualListboxes.clear();
            }

            $.each(data, function (index, item) {
                if (id == 1) {

                    DualListboxes.geOptionArray().push({ text: item.employee_name, value: item.employee_id });


                } else if (id == 2) {
                    DualListboxes.geOptionArray().push({ text: item.grade, value: item.customer_grade_id });

                }



            });
            $('.cmbFilterData').remove();
            DualListboxes.init();

        }, error: function (data) {
            console.log(data)
        }

    });


}

//get employees from db

function getEmployeeDetails() {
    $.ajax({
        url: '/sd/getBranch',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $('#cmbEmployee').append('<option value="">Select Branch</option>');
            $.each(data, function (index, item) {
               
                $('#cmbEmployee').append('<option value="' + item.branch_id  + '">' + item.branch_name + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    })
}



function getselectemployee(){
    
    var selectedemployeeId = $('#cmbEmployee').val();
   // alert(selectedBranchId)
   
    $.ajax({
        url: '/sd/getselectbranch/'+ selectedemployeeId,
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            var dt=data.data;
          console.log(dt);
            if (DualListboxes.geOptionArray().length > 0) {
                var listbox =DualListboxes.geOptionArray();
                for(var i = 0; i < listbox.length; i++){
                   //  console.log(listbox[i]);

                     if( listbox[i].selected != undefined){
                         listbox[i].selected = false;
                     }
                    

                }
            }
            for (var i = 0; i < DualListboxes.geOptionArray().length; i++) {

                if(dt !=undefined){
                    for (var j = 0; j < dt.length; j++) {
                        if(dt[j].employee_id == DualListboxes.geOptionArray()[i].value){
                            DualListboxes.geOptionArray()[i].selected = true;
                        }
    
                        
                    }
                
                }
                   
            }
           // console.log( DualListboxes.geOptionArray());

 $('.cmbFilterData').remove();
 DualListboxes.init();
          

        }, error: function (data) {
            console.log(data)
        }

    })
}


function addEmployeeCustomer() {

    var datatype;
    var value = $('#cmbFilterBy').val();
    if (value == 1) {
        datatype = 'customer'
    } else if (value == 2) {
        datatype = 'grade'
    }

    var employeeid = $('#cmbEmployee').val();

    console.log(DualListboxes.getSelectedOptions());

    if(employeeid !== ""){

    $.ajax({
        url: '/sd/addEmployeeBranch',
        method: 'post',
        enctype: 'multipart/form-data',
        data: {
            datatype: datatype,
            locationid: employeeid,
            option_array: JSON.stringify(DualListboxes.getSelectedOptions())

        },

        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            getEmployeeCustomerDetails();
            showSuccessMessage('Successfully saved');
            console.log(response);
            /*         $('#frmItem')[0].reset(); */
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    });
}else{
    showWarningMessage("Select Branch");
}

}


function getEmployeeCustomerDetails() {
   
    $.ajax({
        type: "GET",
        url: "/sd/getEmployeeBranchDetails",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var label =  '<label class="badge bg-danger">'+dt[i].credit_allowed+'</label>';
                if(dt[i].credit_allowed == "Yes"){
                    label =  '<label class="badge bg-primary">'+dt[i].credit_allowed+'</label>';
                }
                data.push({
                    "action": '<input class="form-check-input" type="checkbox" name="record[]" value="' + dt[i].employee_id  + '|' + dt[i].branch_id + '">',
                    "employee_name": dt[i].employee_name,
                    "customer_name": dt[i].branch_name,
                    //"credit_allowed":label,
                    //"credit_control_type": dt[i].credit_type_name,
                    
                });
   
            }

            var table = $('#employee_customer').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}


function deleteConfirmaion(){
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
                deleteEmployeeCustomer();
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
}





function deleteEmployeeCustomer() {
    var selectedRecords = [];
    $('input[name="record[]"]:checked').each(function () {
        selectedRecords.push($(this).val());

        
    });

    if (selectedRecords.length === 0) {
        alert('No records selected.');
        return;
    }
    
    var token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: '/sd/deleteEmployeeBranch', 
        type: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': token 
        },
        data: {
            records: selectedRecords
        },
        success: function (response) {
            console.log(response); 
            getEmployeeCustomerDetails(); 
            showSuccessMessage('Successfully deleted')
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}

function employee_customer_refresh() {
    var table = $('#employee_customer').DataTable();
    table.columns.adjust().draw();
}


