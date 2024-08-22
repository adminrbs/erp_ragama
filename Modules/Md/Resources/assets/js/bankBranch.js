var formData = new FormData();
$(document).ready(function () {

    $('#btnbankBranch').on('click', function () {

        $('#btnSaveBranch').show();
        $('#btnUpdateBranch').hide();
        $('#id').val('');
        $("#txtbranchCode").val('');
        $("#txtbranchSearch").val('');
    });

    // close

    $("#btnCloseBranch").on("click", function (e) {
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
                $("#bankBranchmodal").modal("hide"); // This will close the modal
                var urlWithoutQuery = window.location.href.split('?')[0];

            },
            error: function (xhr, status, error) {

            }
        });
    });



    $('#btnSaveBranch').show();
    $('#btnUpdateBranch').hide();

    $('#btnSaveBranch').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        saveBranch();
    });

    //...Customer user App Update

    $('#btnUpdateBranch').on('click', function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }

        updateBranch();
    });



});



const DatatableFixedColumnsbranch = function () {


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
        var table = $('.datatable-fixed-both-branch').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2,

                },
                {
                    width: 200,
                    targets: 0,

                },
                {
                    width: '100%',
                    targets: 1,


                },
                {
                    width: 400,
                    targets: [2]
                },

            ],

            scrollX: true,
           // scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },

            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "bank_branch_id" },
                { "data": "bank_branch_code" },
                { "data": "bank_branch_name" },
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
    DatatableFixedColumnsbranch.init();
});








//..... Save.....
function saveBranch() {

    if(BANK_ID == undefined){
        $('#bankBranchmodal').modal('hide');
        showWarningMessage('Please select bank.');
        return;
    }

    formData.append('bank_id',BANK_ID);
    formData.append('txtbranchCode', $('#txtbranchCode').val());
    formData.append('txtbranchSearch', $('#txtbranchSearch').val());


    console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/saveBranch',
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
        },
        success: function (response) {
            branchlData(BANK_ID);
            BANK_ID = undefined;
            $('#bankBranchmodal').modal('hide');

            if (response.status) {
                showSuccessMessage('Successfully saved');
            } else {
                showErrorMessage("Something went worng");
            }

            console.log(response);
        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            $('#bankBranchmodal').modal('hide');
            console.log(error);
        }
    });

}

//.......edit......

$(document).on('click', '.branchEdit', function (e) {

    e.preventDefault();
    let bank_branch_id = $(this).attr('id');
    $.ajax({
        url: '/md/getbranchkEdit/' + bank_branch_id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            $('#btnSaveBranch').hide();
            $('#btnUpdateBranch').show();


            $('#id').val(response.bank_branch_id);
            $("#txtbranchCode").val(response.bank_branch_code);
            $("#txtbranchSearch").val(response.bank_branch_name);





        }
    });
});

//....Update


function updateBranch() {
    if(BANK_ID == undefined){
        $('#bankBranchmodal').modal('hide');
        showWarningMessage('Please select bank.');
        return;
    }

    formData.append('bank_id',BANK_ID);

    var id = $('#id').val();

    formData.append('txtbranchCode', $('#txtbranchCode').val());
    formData.append('txtbranchSearch', $('#txtbranchSearch').val());

    console.log(formData);
    $.ajax({
        type: 'POST',
        enctype: 'multipart/form-data',
        url: '/md/branchupdate/' + id,
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
            console.log(response);

            branchlData(BANK_ID);
            $('#bankBranchmodal').modal('hide');

            showSuccessMessage('Successfully updated');



        }, error: function (error) {
            showErrorMessage('Something went wrong');
            $('#bankBranchmodal').modal('hide');
            console.log(error);
        }
    });
}

function btnBranchDelete(id) {
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
                deleteBranch(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function deleteBranch(id) {

    $.ajax({
        type: 'DELETE',
        url: '/md/deletebranch/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {
            console.log(response);
            branchlData(BANK_ID);

            showSuccessMessage('Successfully deleted');
        }, error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}


function cbxBranchStatus(bank_branch_id) {
    var status = $('#cbxBranch').is(':checked') ? 1 : 0;
    $.ajax({
        url: '/md/branchstatus/' + bank_branch_id,
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


////..................baranch name  auto compleet..................


const AutocompleteInputsbranch = function () {


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
        const autocompleteData = loadbaranch();

        // Basic
        const autocompleteBasic = new autoComplete({
            selector: "#txtbranchSearch",
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
    AutocompleteInputsbranch.init();
});

$('#txtbranchSearch').on('onclick', function () {
    loadbaranch();
})

function loadbaranch() {

    var result = [];


    $.ajax({
        url: '/md/searchBranch',
        method: 'GET',
        cache: false,
        timeout: 800000,
        success: function (data) {
            $.each(data, function (index, value) {

                result.push(value.bank_branch_name);
            })
        }

    });
    console.log(result);
    return result;

}

function bankbranchTable() {
    var table = $('#bankbranchTable').DataTable();
    table.columns.adjust().draw();
}
