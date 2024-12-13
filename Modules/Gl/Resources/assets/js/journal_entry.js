var ItemList = [];
var analysisTableArray = [];
var tableData = undefined;
$(document).ready(function () {

    $('.approval').hide();
    if (action == 'edit') {
        $('#btnSave').text('Update');
    } else if (action == 'approval') {
        $('.action').hide();
        $('.approval').show();
        $('#journal_date').addClass("disabled");
        $('#cmbBranch').addClass("disabled");
        $('#tblData').addClass("disabled");
        $('#txtDescription').addClass("disabled");
    }

    ItemList = loadAccounts();
    DataChooser.addCollection("Accounts", ['', '', '', '', ''], ItemList);

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });


    getBranches();
    $('.select2').select2();
    $('#cmbBranch').change();

    if (action == 'view') {
        initTableView();
    } else {
        initTable();
    }

    $('#btnSave').on('click', function () {

        bootbox.confirm({
            title: 'Save confirmation',
            message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>&nbsp;Yes',
                    className: 'btn-warning'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i>&nbsp;No',
                    className: 'btn-link'
                }
            },
            callback: function (result) {
                console.log(result);
                if (result) {
                    if (action == 'edit') {
                        updateJournal(journal_id);
                    } else {
                        saveJournal();
                    }

                } else {

                }
            },
            onShow: function () {
                $('#question-icon').addClass('swipe-question');
            },
            onHide: function () {
                $('#question-icon').removeClass('swipe-question');
            }
        });

        $('.bootbox').find('.modal-header').addClass('bg-warning text-white');

    });


    $('#btnApprove').on('click', function () {
        bootbox.confirm({
            title: 'Approval confirmation',
            message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>&nbsp;Yes',
                    className: 'btn-warning'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i>&nbsp;No',
                    className: 'btn-link'
                }
            },
            callback: function (result) {
                console.log(result);
                if (result) {
                    approvalJournal(journal_id, 1);

                } else {

                }
            },
            onShow: function () {
                $('#question-icon').addClass('swipe-question');
            },
            onHide: function () {
                $('#question-icon').removeClass('swipe-question');
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-warning text-white');
    });


    $('#btnReject').on('click', function () {
        bootbox.confirm({
            title: 'Reject confirmation',
            message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
            buttons: {
                confirm: {
                    label: '<i class="fa fa-check"></i>&nbsp;Yes',
                    className: 'btn-warning'
                },
                cancel: {
                    label: '<i class="fa fa-times"></i>&nbsp;No',
                    className: 'btn-link'
                }
            },
            callback: function (result) {
                console.log(result);
                if (result) {
                    approvalJournal(journal_id, 2);

                } else {

                }
            },
            onShow: function () {
                $('#question-icon').addClass('swipe-question');
            },
            onHide: function () {
                $('#question-icon').removeClass('swipe-question');
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-warning text-white');
    });


    getJournalEntry(journal_id);

});


function initTable() {
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:100px;", "event": "", "valuefrom": "datachooser", "thousand_seperator": false, "disabled": "" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:200;" },
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "width:370px;" },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": "", "style": "width:120px;text-align:right;", "event": "calTotal(this)", },
            { "type": "select", "class": "transaction-inputs", "value": "", "style": "width:150px;", "event": "", },

            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);", "width": 30 },

        ],
        "auto_focus": 0,
        "hidden_col": []
    });
    tableData.addRow();
}


function initTableView() {
    var table = $('#tblData').DataTable({
        columnDefs: [

            {
                orderable: false,
                targets: 2
            },
            {
                width: 100,
                targets: 0
            },
            {
                width: 300,
                targets: 1
            },
            {
                width: 50,
                targets: [2]
            },
            {
                width: 50,
                targets: 3
            }



        ],
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: {
            leftColumns: 0,
            rightColumns: 0
        },
        autoWidth: false,
        "pageLength": 100,
        "order": [],
        "columns": [
            { "data": "glAccount" },
            { "data": "name" },
            { "data": "description" },
            { "data": "amount" },
            { "data": "analysis" },




        ],
        columnDefs: [
            {
                targets: 3, // The 'amount' column
                className: 'amount'
            }
        ],
        destroy: true, // Ensures reinitialization doesn't conflict
        "stripeClasses": ['odd-row', 'even-row'],
    });
}


//loading branches
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


function loadAccounts() {
    var list = [];
    $.ajax({
        url: '/gl/loadAccounts',
        type: 'get',
        async: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                list = response.data;

            }
            console.log(response.data);
            $.each(response.data, function (index, value) {
                if (value.type_id == 8) {
                    $('#cmbGlAccount').append('<option value="' + value.hidden_id + '">' + value.id + '</option>');
                }


            })
        },
        error: function (error) {
            console.log(error);
        },

    })
    return list;
}


function showTransactionDataChooser(event, visible) {
    if (visible) {
        DataChooser.showChooser(event, event, "Accounts");
        $('#data-chooser-modalLabel').text('Accounts');
    }
}


function dataChooserEventListener(event, id, value) {

    console.log(event.inputFiled);
    var selected = event.getSelected();
    var item_id = selected.hidden_id;
    var row_childs = event.getRowChilds();
    var hash_map = [];
    var arr = tableData.getDataSource();
    for (var i = 0; i < arr.length - 1; i++) {
        hash_map.push(arr[i][0]);
    }

    console.log(hash_map);
    $.ajax({
        url: '/gl/get_gl_account_name/' + item_id,
        type: 'get',
        success: function (response) {



            var dt = response.data;
            console.log(dt);

            $(row_childs[1]).val(dt);



        }
    })
    console.log(row_childs[4]);
    loadAccountAnalysisData(event, item_id)
    $(row_childs[2]).val($('#txtDescription').val());


}



function loadAccountAnalysisData(event, id) {
    var row_childs = event.getRowChilds();
    $(row_childs[4]).empty();
    $.ajax({
        url: '/gl/loadAccountAnalysisData/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            var analysis = data.data;
            console.log(analysis);

            $.each(analysis, function (index, value) {


                $(row_childs[4]).append('<option value="' + value.gl_account_analyse_id + '">' + value.gl_account_analyse_name + '</option>');
            });
        },
    })
    return analysisTableArray;
}



function transactionTableKeyEnterEvent(event, id) {
    if (id == 'tblData') {
        tableData.addRow();

    }
}


function saveJournal() {

    $.ajax({
        url: '/gl/saveJournal',
        method: 'post',
        enctype: 'multipart/form-data',
        data: getFormData(),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnSave').prop('disabled', true);
        }, success: function (response) {

            if (response.success) {
                showSuccessMessage("Successfuly saved");
                window.location.href = "/gl/journal_entries";
            } else {
                showWarningMessage("Unable to save");
            }
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }
    })
}

function getFormData() {
    var collection = [];
    if (tableData != undefined) {
        var arr = tableData.getDataSourceObject();
        for (var i = 0; i < arr.length; i++) {
            if (!arr[i][4].val()) return showWarningMessage("Analysis is required"), false;
            collection.push(JSON.stringify({
                "account_id": parseInt(arr[i][0].attr('data-id')),
                "description": arr[i][2].val(),
                "amount": parseFloat(arr[i][3].val()),
                "analysis": parseInt(arr[i][4].val()),

            }));
        }
    }
    console.log(collection);

    var formData = new FormData();
    formData.append("date", $('#journal_date').val());
    formData.append("branch", $('#cmbBranch').val());
    formData.append("description", $('#txtDescription').val());
    formData.append("created_by", 0);
    formData.append("approved_by", 0);
    formData.append("approval_status", 0);
    formData.append('collection', JSON.stringify(collection));
    return formData;
}


function calTotal(event) {
    var total_Sum = 0;
    var arr = tableData.getDataSourceObject();
    for (var i = 0; i < arr.length; i++) {
        console.log(arr[i][3].val());

        // total_Sum += parseFloat(arr[i][2].val().replace(/,/g, ''));
        if (!isNaN(parseFloat(arr[i][3].val()))) {
            total_Sum += parseFloat(arr[i][3].val());
        }

    }


    $('#lblGrossTotal').text(parseFloat(total_Sum).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblNetTotal').text(parseFloat(total_Sum).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());

}



function getJournalEntry(id) {
    $.ajax({
        url: '/gl/getJournalEntry/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            console.log(data);
            var header = data.header;
            $('#journal_date').val(header.transaction_date);
            $('#cmbBranch').val(header.branch_id);
            $('#txtDescription').val(header.description);


            var items = data.items;
            if (action == 'view') {
                appendTableItemsView(items);
                calculateViewTotal();
            } else {
                appendTableItems(items);
                calTotal(null);
            }

        },
    })
}


function appendTableItemsView(items) {
    var dataArray = [];
    $.each(items, function (index, value) {
        dataArray.push({
            "glAccount": value.account_code,
            "name": value.account_name,
            "description": value.descriptions,
            "amount": value.amount,
            "analysis": value.gl_account_analyse_name
        });

        var table = $('#tblData').DataTable();
        table.clear();
        table.rows.add(dataArray).draw();

    });
}



function appendTableItems(items) {
    var dataSource = [];
    $.each(items, function (index, value) {

        console.log(tableData);

        dataSource.push([
            { "type": "text", "class": "transaction-inputs", "value": value.account_code, "data_id": value.account_id, "style": "width:100px;", "event": "", "valuefrom": "datachooser", "thousand_seperator": false, "disabled": "" },
            { "type": "text", "class": "transaction-inputs", "value": value.account_name, "style": "width:200;" },
            { "type": "text", "class": "transaction-inputs", "value": value.descriptions, "style": "width:370px;" },
            { "type": "number", "class": "transaction-inputs math-abs math-round", "value": value.amount, "style": "width:120px;text-align:right;", "event": "calTotal(this)", },
            { "type": "select", "class": "transaction-inputs", "value": value.analysisTableArray, "selected_option": value.gl_account_analysis_id, "style": "width:150px;", "event": "", },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;margin-left:10px;", "event": "removeRow(this);", "width": 30 },

        ]);

    });

    tableData.setDataSource(dataSource);

    if(items.length == 0){
        tableData.addRow();
    }
}



function updateJournal(id) {

    $.ajax({
        url: '/gl/updateJournal/' + id,
        method: 'post',
        enctype: 'multipart/form-data',
        data: getFormData(),
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnSave').prop('disabled', true);
        }, success: function (response) {

            if (response.success) {
                showSuccessMessage("Successfuly updated");
                window.location.href = "/gl/journal_entries";
            } else {
                showWarningMessage("Unable to update");
            }
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }
    })
}



function approvalJournal(id, status) {

    $.ajax({
        url: '/gl/approvalJournal/' + id,
        method: 'put',
        enctype: 'multipart/form-data',
        data: { status: status },
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            $('#btnSave').prop('disabled', true);
        }, success: function (response) {

            if (response.success) {
                showSuccessMessage("Successfuly updated");
                window.location.href = "/gl/journal_entries";
            } else {
                showWarningMessage("Unable to update");
            }
        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
            $('#btnSave').prop('disabled', false);
        }
    })
}

function calculateViewTotal() {
    let total = 0;
    const amounts = document.querySelectorAll('#tblData .amount');
    amounts.forEach(cell => {
        const amount = parseFloat(cell.textContent) || 0;
        total += amount;
    });
    $('#lblGrossTotal').text(parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
    $('#lblNetTotal').text(parseFloat(total).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString());
}














