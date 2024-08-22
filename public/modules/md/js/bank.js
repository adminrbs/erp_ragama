var formData = new FormData();
var BANK_ID = undefined;
$(document).ready(function () {


    $('#bankTable').on('click', 'tr', function (e) {
        $('#bankTable tr').removeClass('selected');

        $(this).addClass('selected');
        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children(); // or hiddenValue.find('*');
        childElements.each(function () {

            BANK_ID = $(this).attr('data-id');
            branchlData(BANK_ID);


        });
    });


    $('#btnbankModel').on('click', function () {
        $('#btnSaveBank').show();
        $('#btnUpdateBank').hide();
        $('#id').val('');
        $("#txtBankCode").val('');
        $("#txtbankSearch").val('');

    });

    // close

    $("#btnBankclose").on("click", function (e) {
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
            success: function (response) {
                $("#bankModel").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];

            },
            error: function (xhr, status, error) {

            }
        });
    });



    $('#btnSaveBank').show();
    $('#btnUpdateBank').hide();

    $('#btnSaveBank').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveBank();
    });

    //...Customer user App Update

    $('#btnUpdateBank').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateBank();
    });

});





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
        var table = $('.datatable-fixed-both-bank').DataTable({
            columnDefs: [

                    {
                        orderable: false,
                        targets: 2
                    },
                    {
                        width: '100%',
                        targets: 0
                    },
                    {
                        width: 300,
                        targets: 1
                    },
                    {
                        width: 500,
                        targets: [2]
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
                { "data": "bank_id" },
                { "data": "bank_code" },
                { "data": "bank_name" },
                { "data": "active" },
                { "data": "edit" },
                { "data": "delete" },




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





function bankllData() {


    $.ajax({
        type: "GET",
        url: "/md/getBankAlldata",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";

                data.push({

                    "bank_id": dt[i].bank_id,
                    "bank_code":'<div data-id = "' + dt[i].bank_id + '">' + dt[i].bank_code + '</div>',
                    "bank_name": dt[i].bank_name,
                    "active": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxBank" value="1" onclick="cbxBankStatus(' + dt[i].bank_id + ')" required ' + isChecked + '></lable>',
                    "edit": '<button class="btn btn-primary bankEdit" data-bs-toggle="modal" data-bs-target="#bankModel" id="' + dt[i].bank_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                    "delete": '&#160<button class="btn btn-danger" onclick="btnBankDelete(' + dt[i].bank_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',

                });
            }


            var table = $('#bankTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

bankllData();



//..... Save.....
function saveBank() {

    formData.append('txtBankCode', $('#txtBankCode').val());
    formData.append('txtbankSearch', $('#txtbankSearch').val());


    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/savebank',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            // Perform any tasks before sending the request
            $('#btnSaveBank').prop('disabled',true);
        },
        success: function (response) {
            $('#btnSaveBank').prop('disabled',false);
            bankllData();
            $('#bankModel').modal('hide');
            if(response.message == "exist"){
                showWarningMessage("Bank code can't be duplicated");
                return;
            }

            if (response.status) {
                showSuccessMessage('Successfully saved');
            }else{
                showErrorMessage("Something went worng");
            }

            console.log(response);
        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            console.log(error);
        }
    });

}

//.......edit......

$(document).on('click', '.bankEdit', function (e) {

    e.preventDefault();
    let vehicle_id = $(this).attr('id');
    $.ajax({
        url: '/md/getbannkEdit/' + vehicle_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            $('#btnSaveBank').hide();
            $('#btnUpdateBank').show();


            $('#id').val(response.bank_id);
            $("#txtBankCode").val(response.bank_code);
            $("#txtbankSearch").val(response.bank_name);





        }
    });
});


//....Update


function updateBank() {

    var id = $('#id').val();

    formData.append('txtBankCode', $('#txtBankCode').val());
    formData.append('txtbankSearch', $('#txtbankSearch').val());

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/bankupdate/' + id,
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
            $('#btnUpdateBank').prop('disabled',true);
        },
        success: function (response) {
            $('#btnUpdateBank').prop('disabled',false);
            console.log(response);

            bankllData();
            $('#bankModel').modal('hide');

            showSuccessMessage('Successfully updated');



        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#bankModel').modal('hide');
            console.log(error);
        }
    });
}

function btnBankDelete(id) {
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
                deleteBank(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteBank(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deletebank/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            bankllData();

            showSuccessMessage('Successfully deleted');
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}




function cbxBankStatus(bank_id) {
    var status = $('#cbxBank').is(':checked') ? 1 : 0;
    $.ajax({
        url: '/md/bankStatus/' + bank_id,
        type: 'POST',
        data: {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'status': status
        },
        success: function (response) {
            console.log("data save");
            showSuccessMessage('saved');
        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

const AutocompleteInputs = function () {


    //
    // Setup module components
    //

    // Autocomplete
    const _componentAutocomplete = function () {
        if (typeof autoComplete == 'undefined') {
            console.warn('Warning - autocomplete.min.js is not loaded.');
            return;
        }

        // Demo data
        const autocompleteData =  loadNames();

        // Basic
        const autocompleteBasic = new autoComplete({
            selector: "#txtbankSearch",
            data: {
                src: autocompleteData
            },
            resultItem: {
                highlight: true
            },
            events: {
                input: {
                    selection: function (event) {
                        const selection = event.detail.selection.value;
                        autocompleteBasic.input.value = selection;
                    }
                }
            }
        });



        // External empty array to save search results
        let history = [];
        const autocompleteRecent = new autoComplete({
            selector: "#autocomplete_recent",
            data: {
                src: autocompleteData
            },
            resultItem: {
                highlight: true
            },
            resultsList: {
                element: (list) => {
                    const recentSearch = history.reverse();
                    const historyLength = recentSearch.length;

                    // Check if there are recent searches
                    if (historyLength) {
                        const historyBlock = document.createElement("li");
                        historyBlock.classList.add('pe-none', 'border-bottom', 'pt-0', 'pb-2', 'mb-2');
                        historyBlock.innerHTML = '<div class="fw-semibold">Recent Searches</div>';
                        // Limit displayed searched to only last "2"
                        recentSearch.slice(0, 2).forEach((item) => {
                            const recentItem = document.createElement("div");
                            recentItem.classList.add('text-muted', 'mt-2')
                            recentItem.innerHTML = item;
                            historyBlock.append(recentItem);
                        });

                        // const separator = document.createElement("li");
                        // separator.classList.add('border-top')
                        // list.insertBefore(separator, list.firstElementChild);

                        list.prepend(historyBlock);
                    }
                }
            },
            events: {
                input: {
                    selection(event) {
                        const feedback = event.detail;
                        const input = autocompleteRecent.input;
                        // Get selected Value
                        const selection = feedback.selection.value;
                        // Add selected value to "history" array
                        history.push(selection);

                        autocompleteRecent.input.value = selection;
                    }
                }
            }
        });

        // Start with

    };


    //
    // Return objects assigned to module
    //

    return {
        init: function () {
            _componentAutocomplete();
        }
    }
}();


// Initialize module

document.addEventListener('DOMContentLoaded', function () {
    AutocompleteInputs.init();
});

    $('#txtbankSearch').on('onclick', function () {
           loadNames();
    })

    function loadNames() {

        var result = [];


        $.ajax({
            url: '/md/searchBank',
            method: 'GET',
            cache: false,
            timeout: 800000,
            success: function (data) {
                $.each(data, function (index, value) {

                    result.push(value.bank_name);
                })


            }

        });
        console.log(result);
        return result;

    }
//............................


function branchlData(id) {

    $.ajax({
        type: "GET",
        url: "/md/getBranchAlldata/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            console.log(response);

            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var isChecked = dt[i].is_active ? "checked" : "";

                data.push({

                    "bank_branch_id": dt[i].bank_branch_id,
                    "bank_branch_code": dt[i].bank_branch_code,
                    "bank_branch_name": dt[i].bank_branch_name,
                    "active": '<label class="form-check form-switch"><input type="checkbox"  class="form-check-input" name="switch_single" id="cbxBranch" value="1" onclick="cbxBranchStatus(' + dt[i].bank_branch_id + ')" required ' + isChecked + '></lable>',
                    "edit": '<button class="btn btn-primary branchEdit" data-bs-toggle="modal" data-bs-target="#bankBranchmodal" id="' + dt[i].bank_branch_id + '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
                    "delete": '&#160<button class="btn btn-danger" onclick="btnBranchDelete(' + dt[i].bank_branch_id + ')"><i class="fa fa-trash" aria-hidden="true"></i></button>',

                });
            }


            var table = $('#bankbranchTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

function bankTable() {
    var table = $('#bankTable').DataTable();
    table.columns.adjust().draw();
}


