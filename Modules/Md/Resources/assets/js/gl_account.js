


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
        var table = $('#glAccountTable').DataTable({
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
                    width: '25%',
                    targets: 1
                },
                {
                    width: 300,
                    targets: [2]
                },
                {
                    width: '100%',
                    targets: 3
                },


            ],
            scrollX: true,
            //scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "id" },
                { "data": "account_code" },
                { "data": "accounttitel" },
                { "data": "accounttype" },
                { "data": "action" },

            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false);



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







var formData = new FormData();


var formData = new FormData();
$(document).ready(function () {
    glaccountType();
    allglaccountdata();
    $('#btnglaccount').on('click', function () {
        $('input[type="text"]').val('');


    });
    $('#BtnGlAccountAnalysis').on('click', function () {
        //addAnalysis();
        addglAccountAnalysis();

    });

    $('#analysisModal').on('hide.bs.modal', function (e) {
       $('#txtGlAccountAnalysis').val("");
    });
    
    $('#analysisModal').on('show.bs.modal',function(e){
        $('.analysis').show();
    });

    $('#btnCloseANalysisModal').on('click',function(){
        $('#analysisModal').modal('hide');
    });
    /* 
        $('#txtGlAccountAnalysis').on('change', function () {
    
            searchTable($(this).val());
        }); */

    $('#btnglaccount').on('click', function () {
        $('.analysis').hide();
    });
    $('#btnsave').on('click', function () {

        if ($('#btnsave').text().trim() == 'Save') {
            save_glaccount();
        }
        else {
            updateglAccount();
        }

    });

    $('#btnCloseupdate').on('click', function () {
        $('#modalNonproprietary').modal('hide');

    });
});
function glaccountType() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/glaccountType",

        success: function (response) {
            var data = response

            $.each(data, function (index, value) {

                $('#cmdAccountType').append('<option value="' + value.gl_account_type_id + '">' + value.gl_account_type + '</option>');

            })

        },

    });
}

function save_glaccount() {

    formData.append('txtAccountCode', $('#txtAccountCode').val());
    formData.append('txtAccountTitle', $('#txtAccountTitle').val());
    formData.append('cmdAccountType', $('#cmdAccountType').val());

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/save_glaccount',
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
            $('#btnsave').prop('disabled', true);
        },
        success: function (response) {
            console.log(response)
            $('#btnsave').prop('disabled', false);
            //suplyGroupAllData();
            var msg = response.message;
            if (msg == "duplicated") {
                showWarningMessage('Account code can not be duplicated');
                $('#txtAccountCode').addClass('is-invalid');
                return;
            } else {
                $('#modalNonproprietary').modal('hide');
                allglaccountdata()
                showSuccessMessage("Successfully Sved");

            }




        },
        error: function (error) {
            //showErrorMessage('Something went wrong');
            showErrorMessage("Something went wrong");

        },
        complete: function () {

        }

    });


}

function allglaccountdata() {
    $.ajax({
        type: 'GET',
        url: '/md/allglaccountdata',
        success: function (response) {

            var dt = response;
            console.log(dt);
            var data = [];
            for (i = 0; i < response.length; i++) {

                var dt = response;



                var data = [];
                for (var i = 0; i < dt.length; i++) {


                    data.push({
                        "id": dt[i].account_id,
                        "account_code": dt[i].account_code,
                        "accounttitel": dt[i].account_title,
                        "accounttype": dt[i].gl_account_type,
                        "action": '<button class="btn btn-secondary btn-sm loneview" data-bs-toggle="modal" data-bs-target="#analysisModal" onclick="getAndUpdateGl_account_anlysis(' + dt[i].account_id + ', \'' + dt[i].account_title + '\')" title="GL Account Analysis"><i class="fa fa-cog" aria-hidden="true"></i></button>&nbsp' + 
          '<button title="Edit" class="btn btn-primary btn-sm lonmodel" data-bs-toggle="modal" data-bs-target="#modalNonproprietary" onclick="edit(' + dt[i].account_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160' + 
          '<button class="btn btn-success btn-sm loneview" data-bs-toggle="modal" data-bs-target="#modalNonproprietary" onclick="getcontributeview(' + dt[i].account_id + ')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160' + 
          '<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].account_id + ')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>'


                    });
                }

            }
            var table = $('#glAccountTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (data) {
            console.log(data);
        }, complete: function () {

        }
    });
}
function edit(id) {
    $('#btnsave').text('Update');
    $('.analysis').show().prop('disabled', false);
    $.ajax({
        url: '/md/getglaccount/' + id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {
            console.log(response);


            $('#id').val(response.account_id);
            $("#txtAccountCode").val(response.account_code);
            $('#txtAccountTitle').val(response.account_title);
            $('#cmdAccountType').val(response.account_type_id);

        }
    });
}

function updateglAccount() {
    var id = $('#id').val();




    formData.append('txtAccountCode', $('#txtAccountCode').val());
    formData.append('txtAccountTitle', $('#txtAccountTitle').val());
    formData.append('cmdAccountType', $('#cmdAccountType').val());
    // formData.append('analysisName',getFirstColumnTexts());

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/updateglAccount/' + id,
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
            $('#btnsave').prop('disabled', true);
        },
        success: function (response) {
            $('#btnsave').prop('disabled', false);
            var msg = response.message;
            if (msg == "duplicated") {
                showWarningMessage('Account code can not be duplicated');
                $('#txtAccountCode').addClass('is-invalid');
                return;
            } else {
                $('#modalNonproprietary').modal('hide');
                allglaccountdata()
                showSuccessMessage("Successfully Updated");

            }


        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            //$('#modalNonproprietary').modal('hide');
            console.log(error);

        },
        complete: function () {

        }

    });

}
function getAndUpdateGl_account_anlysis(id,title) {
    $('#id_').val(id);
   console.log(title);
   
    loadAnalysisAcc(id,title);
}

function addglAccountAnalysis() {
    var id = $('#id_').val();

    if ($('#txtGlAccountAnalysis').val().length < 0) {
        showWarningMessage("Please enter analysis name");
    } else {
        var analisys_name = $('#txtGlAccountAnalysis').val();


        if (!existRecord(analisys_name)) {

            formData.append('txtGlAccountAnalysis', $('#txtGlAccountAnalysis').val());
            console.log(id);

            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/md/addglAccountAnalysis/' + id,
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
                    $('#BtnGlAccountAnalysis').prop('disabled', true);
                },
                success: function (response) {
                    $('#BtnGlAccountAnalysis').prop('disabled', false);
                    if (response.status) {
                        loadAnalysisAcc(id)
                    }



                },
                error: function (error) {
                    showErrorMessage('Something went wrong');

                },
                complete: function () {

                }

            });
        } else {
            showWarningMessage("Record already exist");
        }
    }

}

function loadAnalysisAcc(id,title) {
    
    $.ajax({
        url: '/md/loadAnalysisAcc/' + id,
        method: 'GET',
        success: function (response) {
            console.log(response.data);

            // Clear existing rows
            $('#analysisTable tbody').empty();

            // Append rows dynamically
            $.each(response.data, function (index, item) {
                const row = $('<tr>', { style: 'border: none;' }).append(
                    $('<td>').text(item.gl_account_analyse_name),
                    $('<td>').append(
                        $('<button>', {
                            type: 'button',
                            class: 'remove-btn', // Optional for styling
                            style: 'border: none; background-color: transparent;',
                        })
                            .append(
                                $('<i>', {
                                    class: 'fa fa-times',
                                    'aria-hidden': 'true',
                                    style: 'color: red !important;',
                                })
                            )
                            .on('click', function () {
                                remove_line(this, item.gl_account_analyse_id);
                            })
                    )
                );

                // Append the row to the table
                $('#analysisTable tbody').append(row);
            });
        },
        error: function (xhr, status, error) {
            console.error('Failed to load analysis accounts:', error);
        }
    });
    $('#analysisModalLabel').html(title);
}


function getFirstColumnTexts() {
    var firstColumnTexts = []; // Initialize an empty array to store the texts

    // Iterate through each row of the table body
    $('#analysisTable tbody tr').each(function () {
        var firstColumnText = $(this).find('td').eq(0).text().trim(); // Get text from the first column
        if (firstColumnText != '') {
            firstColumnTexts.push(firstColumnText); // Push the text into the array
        }

    });

    return firstColumnTexts; // Return the array with first column texts
}



function getcontributeview(id) {
    $('#btnsave').hide();
    $('.analysis').show().prop('disabled', true);

    $('input[type="text"]').prop('disabled', true);
    $('select').prop('disabled', true);
    $.ajax({
        url: '/md/getglaccount/' + id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {
            console.log(response);


            $('#id').val(response.account_id);
            $("#txtAccountCode").val(response.account_code);
            $('#txtAccountTitle').val(response.account_title);
            $('#cmdAccountType').val(response.account_type_id);



        }
    });
}


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
                className: 'btn-info'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                deleteglaccount(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteglaccount(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/glAccounDelete/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            allglaccountdata()

            showSuccessMessage("Successfully Delete");



        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

function addAnalysis() {
    if ($('#txtGlAccountAnalysis').val().length < 1) {
        showWarningMessage('Please enter analysis name');
    } else {
        var analisys_name = $('#txtGlAccountAnalysis').val();


        if (!existRecord(analisys_name)) {
            // Append the row
            $('#analysisTable tbody').append(
                $('<tr>', { style: 'border: none;' }).append(
                    $('<td>').text(analisys_name),
                    $('<td>').append(
                        $('<button>', {
                            type: 'button',
                            style: 'border: none; background-color: transparent;',
                        })
                            .append('<i class="fa fa-times" aria-hidden="true" style="color: red !important;"></i>')
                            .on('click', function () {
                                remove_line(this);
                            })
                    )
                )


            );

            $('#txtGlAccountAnalysis').val("");

        } else {
            showWarningMessage("Record already exist");
        }


        // Check if row count exceeds 7
        if ($('#analysisTable tbody tr').length > 7) {
            $('#analysisTable').css({
                display: 'block',
                maxHeight: '250px',
                overflowY: 'scroll'
            });
        }




    }

}
function existRecord(analysis_name) {
    var found = false; // Initialize found as false

    // Loop through each row in the tbody
    $('#analysisTable tbody tr').each(function () {
        var row = $(this); // Current row

        // Check if the analysis_name is found in any cell of the row
        row.find('td').each(function () {
            if ($(this).text().toLowerCase().includes(analysis_name.toLowerCase())) {
                found = true; // Set found to true if match is found
                return false; // Break the inner loop once we find a match
            }
        });

        if (found) {
            return false; // Break the outer loop if a match is found
        }
    });

    // Return the result
    return found;
}


function searchTable(analysis_name) {
    // Clear any previous search highlights
    $('#analysisTable tbody tr').show(); // Show all rows initially

    // Iterate through each row in the tbody
    $('#analysisTable tbody tr').each(function () {
        var row = $(this); // Current row
        var found = false;

        // Check if the analysis_name is found in any cell of the row
        row.find('td').each(function () {
            if ($(this).text().toLowerCase().includes(analysis_name.toLowerCase())) {
                found = true; // Mark that we found a match
            }
        });


        if (found) {
            row.show();
        } else {
            row.hide();
        }
    });
}


function remove_line(button, id) {
    /* var row = button.closest('tr');
    row.remove(); */
    let isNotApplicable = false;
    if (id > 0) {
        $.ajax({
            type: 'DELETE',
            url: '/md/delete_analysys/' + id,
            data: {
                _token: $('input[name=_token]').val()
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },


            beforeSend: function () {

            }, success: function (response) {


                if(response.msg == "unabletoDelete"){
                    showWarningMessage("This record can not be deleted");
                    isNotApplicable = true;
                    return false;
                }else if(response.status){
                    showSuccessMessage("Record deleted successfully");
                    var row = button.closest('tr');
                    row.remove();
                }else{
                    showWarningMessage("Unable to delete record")
                }



            }, error: function (xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }


   
    

}