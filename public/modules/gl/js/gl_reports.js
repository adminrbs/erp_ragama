

var selectfromdate = null
var selecttodate = null;
var selectAccount = null;
var report;
$(document).ready(function () {

    $('#crdReportSearch').hide();
    $('#pdfContainer').hide();

    $('#btn_advanced_search').on('click', function () {
        PRINT_STATUS = false;
        $('#crdReportSearch').show();
        $('#pdfContainer').hide();
    });

    $('#btnPrint').on('click', function () {
        if (!PRINT_STATUS) {
            showWarningMessage('Please preview report');
            return;
        }
        var iframe = document.getElementById('pdfContainer');

        // Wait for the iframe to fully load
        if (iframe.contentWindow) {
            iframe.contentWindow.print();
        }
    });

    $("input[type='radio']").click(function () {
        $('input[type="checkbox"]').prop('checked', false);
        report = this.id;

        ManageFilters(report);
    });
    $('#btnExport').on('click', function () {
        var iframe = document.getElementById('pdfContainer');
        var tables = iframe.contentWindow.document.getElementsByTagName("table");

        // Iterate through tables
        const table_rows = [];
        for (var i = 0; i < tables.length; i++) {
            var table = tables[i];

            // Access the content of the table
            for (var j = 0; j < table.rows.length; j++) {
                var row = table.rows[j];
                var row_data = [];
                for (var k = 0; k < row.cells.length; k++) {
                    var cell = row.cells[k];
                    var row_val = cell.textContent;
                    if (row_val) {

                        var contains_comma = /,/.exec(row_val);
                        if (contains_comma) {
                            row_val = row_val.replace(/,/g, ' ');
                        }
                        var contains_n = /\n/.exec(row_val);
                        if (contains_n) {
                            row_val = row_val.replace(/\n/g, ' ');
                        }
                        var contains_r = /\r/.exec(row_val);
                        if (contains_r) {
                            row_val = row_val.replace(/\r/g, ' ');
                        }
                        row_data.push(row_val);
                    } else {
                        row_data.push("");
                    }
                }
                table_rows.push(row_data);
            }
        }


        let csvContent = "data:text/csv;charset=utf-8,";

        table_rows.forEach(function (rowArray) {
            console.log(rowArray);
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "my_data.csv");
        document.body.appendChild(link); // Required for FF

        link.click(); // This will download the data file named "my_data.csv".



    });
    $('.select').select2();
    $('.select-multiple-search-disabled').select2();

    $('.select-multiple-search-disabled').on('select2:opening select2:closing', function (event) {
        const $searchfield = $(this).parent().find('.select2-search__field');
        $searchfield.prop('disabled', true);
    });
    getServerTime();
    loadGLAccounts();

    $('#chkdate').on('change', function () {
        if (this.checked) {
            selectfromdate = $('#txtFromDate').val();
            selecttodate = $('#txtToDate').val();
            $('#txtFromDate').change(function () {

                selectfromdate = $('#txtFromDate').val();
                selecttodate = $('#txtToDate').val();

            })
            $('#txtToDate').change(function () {
                selecttodate = $('#txtToDate').val();
                selectfromdate = $('#txtFromDate').val();

            })
        } else {

            selectfromdate = null;
            selecttodate = null;
            
        }


    });


    $('#chkAccount').on('change', function () {

        if (this.checked) {
            selectAccount = $('#cmbAccount').val();
            $('#cmbAccount').change(function () {

                selectAccount = $('#cmbAccount').val();
            })


        } else {



            selectAccount = null

        }

    });

    $('#viewReport').on('click', function () {

        PRINT_STATUS = true;

        if (report == "ledger") {

            var requestData = [
                
                { selectAccount: selectAccount },
                { selectfromdate: selectfromdate },
                { selecttodate: selecttodate },

            ];
            console.log("llr", requestData);
            

            console.log("llr", requestData);
           
            $('#pdfContainer').attr('src', '/gl/ledger/' + JSON.stringify(requestData));
            console.log("llr", JSON.stringify(requestData));
            if (report != "salesRepwiseMonthlySummary") {
                $('#crdReportSearch').hide();
                $('#pdfContainer').show();
            }


        }else if(report == "trail_balance"){
            var requestData = [
                
                { selectAccount: selectAccount },
                { selectfromdate: selectfromdate },
                { selecttodate: selecttodate },

            ];
            $('#pdfContainer').attr('src', '/gl/trial_balance/' + JSON.stringify(requestData));
            if (report != "salesRepwiseMonthlySummary") {
                $('#crdReportSearch').hide();
                $('#pdfContainer').show();
            }
        }
    });
});

// get time
function getServerTime() {

    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#txtFromDate').val(formattedDate);
            $('#txtToDate').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//load accounts
function loadGLAccounts() {
    $.ajax({
        url: '/gl/loadGLAccounts',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            $('#cmbAccount').empty();
            $('#cmbAccount').append('<option value="0">Select Account</option>');
            $.each(response, function (index, value) {
                $('#cmbAccount').append('<option value="' + value.account_id + '">' + value.account_title + '</option>');
            });
        },
        error: function (error) {
            console.log(error);
        }
    });
}


function ManageFilters(report){
    if(report == "trail_balance"){
        $('#txtFromDate').prop('disabled', true);
        $('#txtToDate').prop('disabled', false);
        $('#cmbAccount').prop('disabled', false);
    }
}