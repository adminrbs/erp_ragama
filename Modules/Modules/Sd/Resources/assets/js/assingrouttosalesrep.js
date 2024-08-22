


/* ----------------------------Data table------------------------------ */
const DatatableFixedColumns = function () {


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
        $('.datatable-fixed-both').DataTable({
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


            ],
            scrollX: false,
            scrollY: 350,
            scrollCollapse: true,

            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "action" },
                { "data": "route_name" },
                { "data": "name" },
                //{ "data": "credit_allowed" },
                //{"data": "credit_control_type"},



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


            "stripeClasses": ['odd-row', 'even-row'],
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

                //alert(value);
            },
            removeEvent: function (value) {
                var selectedSalesReps= $('#cmbSalesReps').val();

              

                if (selectedSalesReps && value) {

                    $.ajax({
                        url: '/sd/selectdeletroutesalesrep',
                        type: 'POST',
                        data: {
                            SalesReps: selectedSalesReps,
                            eventValue: value
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {

                            showSuccessMessage('Successfully deleted')
                            getroutetosalesrepDteails();
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
var selectedValue;
    $('.select2').select2();
    getempto()
    getempfrom()
    getroutetosalesrepDteails()
    getsalesrepSelect();

    $('#cmbFilterBy').val('0');

    $('#cmbSalesReps').on('click',function () {
        
        //$('.cmbFilterData').remove();
        
        getFilteredData(selectedValue)
        getselectroute();
      
        //DualListboxes.init();

    })

    
   
    $('#cmbFilterBy').change(function () {


        selectedValue = $('#cmbFilterBy').val();
  
        getFilteredData(selectedValue)
        getselectroute()

    });

    $('#btnSave').on('click', function (e) {
        e.preventDefault();
        addroutetoSalesrep();
        var list = DualListboxes.getSelectedOptions();
        console.log(list);
    });

    $('#btnDlt').on('click', function (e) {
        // deleteroutetosalesrep();
        deleteConfirmaion();
    })

    $('#btncopy').on('click', function (e) {
        // deleteroutetosalesrep();
        copyEmployee();
    })




});


//getting data from DB and appending to list box
function getFilteredData(id) {

   

   

    var selectedBranch = $('#cmbSalesReps').val();


    var formData = new FormData();
    formData.append('selectedselesrep', selectedBranch);

    const dualListBox = document.getElementById('cmbFilterData');

    $.ajax({
        url: '/sd/getroutelistbox/' + id,
        method: 'post',
        dataType: 'json', // Use 'dataType' instead of 'datatype'
        data: formData,
        processData: false, // Ensure data isn't processed
        contentType: false,
        async:false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            //console.log(data);
            var data = response.data;

            // Add default option with value "0" and text "Select"


            //make an empty array if array has data 
            if (DualListboxes.geOptionArray().length > 0) {
                DualListboxes.clear();
            }



            $.each(data, function (index, item) {
                if (id == 1) {


                    DualListboxes.geOptionArray().push({ text: item.route_name, value: item.route_id });


                }


            });



            $('.cmbFilterData').remove();
            DualListboxes.init();

        }, error: function (data) {
            //console.log(data)
        }

    });



}

//get location from db

function getsalesrepSelect() {

    $.ajax({
        url: '/sd/getsalesrep',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {
            $('#cmbSalesReps').append('<option value="">Select Sales Rep</option>');
            $.each(data, function (index, item) {
                $('#cmbSalesReps').append('<option value="' + item.employee_id + '">' + item.employee_name + '</option>');
            });

        }, error: function (data) {
            console.log(data)
        }

    })
}


function getselectroute() {

    var selectedBranchId = $('#cmbSalesReps').val();
    var selectuser = $('#cmbFilterBy').val();


    if (selectuser == "1") {

        $.ajax({
            url: '/sd/getselectroute/' + selectedBranchId,
            method: 'get',
            async: false,
            datatype: 'json',
            success: function (data) {


                var dt = data.data;

               // console.log("ll", dt);
                //console.log(dt);
                if (DualListboxes.geOptionArray().length > 0) {
                    // DualListboxes.clear();
                    var listbox = DualListboxes.geOptionArray();
                    for (var i = 0; i < listbox.length; i++) {
                        console.log(listbox[i]);

                        if (listbox[i].selected != undefined) {
                            listbox[i].selected = false;
                        }


                    }
                }

                if (dt != undefined) {

                    for (var i = 0; i < dt.length; i++) {

                        DualListboxes.geOptionArray().push({ text: dt[i].route_name, value: dt[i].route_id, selected: true });



                    }
                }/*else{
                    DualListboxes.clear();
                }*/


                $('.cmbFilterData').remove();
               DualListboxes.init();


            }, error: function (data) {
                console.log(data)
            }

        })
    }
}




function addroutetoSalesrep() {

    var datatype;
    var value = $('#cmbFilterBy').val();
    if (value == 1) {
        datatype = 'user'

    }

    var locationid = $('#cmbSalesReps').val();
    if (locationid !== "") {

        //console.log("hhhh",DualListboxes.getSelectedOptions()); 
        console.log(JSON.stringify(DualListboxes.getSelectedOptions()));



        $.ajax({
            url: '/sd/addroutetoSalesrep',
            method: 'post',
            enctype: 'multipart/form-data',
            data: {
                datatype: datatype,
                locationid: JSON.stringify(locationid),
                option_array: JSON.stringify(DualListboxes.getSelectedOptions())

            },

            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            }, success: function (status) {

                getroutetosalesrepDteails();



                showSuccessMessage('Successfully saved');



            }, error: function (response) {

            }, complete: function () {

            }
        });
    } else {
        showWarningMessage("Select Sales Rep");
    }

}

function getroutetosalesrepDteails() {

    $.ajax({
        type: "GET",
        url: "/sd/getroutetosalesrepDteails",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response;

            console.log(dt);


            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var label = '<label class="badge bg-danger">' + dt[i].credit_allowed + '</label>';
                if (dt[i].credit_allowed == "Yes") {
                    label = '<label class="badge bg-primary">' + dt[i].credit_allowed + '</label>';
                }
                data.push({
                    "action": '<input class="form-check-input" type="checkbox" name="record[]" value="' + dt[i].employee_id + '|' + dt[i].route_id + '">',
                    "route_name": dt[i].route_name,
                    "name": dt[i].employee_name,
                    //"credit_allowed":label,
                    //"credit_control_type": dt[i].credit_type_name,

                });
            }

            var table = $('#route_salesrep').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}


function deleteroutetosalesrep() {
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
        url: '/sd/deleteroutetosalesrep', // Replace with your Laravel route URL
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token // Include the CSRF token in the headers
        },
        data: {
            records: selectedRecords
        },
        success: function (response) {


            showSuccessMessage('Successfully deleted')
            getroutetosalesrepDteails();
        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}


function deleteConfirmaion() {
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
                deleteroutetosalesrep();
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
}


function getCustomerlocationDteails() {
    var table = $('#route_salesrep').DataTable();
    table.columns.adjust().draw();
}
function getempfrom() {
    $.ajax({
        url: '/sd/getsalesrep',
        method: 'get',
        async: false,
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbempfrom').append('<option value="' + item.employee_id + '">' + item.employee_name + '</option>');
            });
        },
        error: function (data) {
            console.log(data);
        }
    });
}

function getempto() {
    $.ajax({
        url: '/sd/getsalesrep',
        method: 'get',
        async: false,
        dataType: 'json',
        success: function (data) {
            $.each(data, function (index, item) {
                $('#cmbempto').append('<option value="' + item.employee_id + '">' + item.employee_name + '</option>');
            });
        },
        error: function (data) {
            console.log(data);
        }
    });
}


function copyEmployee() {
    var fromemp = $('#cmbempfrom').val()
    var toemp = $('#cmbempto').val()

    if (fromemp == toemp) {
        showWarningMessage("Don't enter the same Employee");
    } else {

        $.ajax({
            url: '/sd/genewtsalesrep',
            method: 'post',
            enctype: 'multipart/form-data',
            data: {
                fromemp: fromemp,
                toemp: toemp
                

            },

            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
             showSuccessMessage("New Employee save")
             getroutetosalesrepDteails();
            },
            error: function (data) {
                console.log(data);
                showErrorMessage("Something went wrong");
            }
        });
    }


}
