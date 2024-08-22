var report = undefined;
var filters = {
    branch: null,
    customer: null,
    salesRep: null,
    fromDate: null,
    toDate: null,
    supplygroup: null
}
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




    $('#btn-collapse-search').on('click', function () {
        $('#row1').show();
    });

    $('input[type="checkbox"]').prop('checked', false);



    loadbranch();
    loadSupplyGroup();
    getCustomer();
    getSalesrep();

    $('#btn-collapse-search').on('click', function () {

        $('#pdfContainer').attr('src', '');
    });


    $('#chkCustomer').prop('checked', false);
    $('#chkdate').prop('checked', false);
    $('#Salesrep').prop('checked', false);
    $('#chkBranch').prop('checked', false);


    $('#chkBranch').on('change', function () {

        if (this.checked) {
            selecteBranch = $('#cmbBranch').val();
            $('#cmbBranch').change(function () {

                //$('#chkBranch').prop('checked', false);
                selecteBranch = $('#cmbBranch').val();

            })


            // getproductdata()

            //getselectproduct(selecteCustomer);
        } else {

            

            selecteBranch = null
            selected6 = null
        }

    })






    $('#chkCustomer').on('change', function () {

        if (this.checked) {
            selecteCustomer = $('#cmbCustomer').val();
            $('#cmbCustomer').change(function () {
                selecteCustomer = $('#cmbCustomer').val();

            })
        } else {

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

            selecteCustomer = null
            selected1 = null
        }

    })


    $('#chkdate').on('change', function () {

        if (this.checked) {
            fromdate = $('#txtFromDate').val();
            todate = $('#txtToDate').val();

            $('#txtFromDate').change(function () {

                fromdate = $('#txtFromDate').val();

            })
            $('#txtToDate').change(function () {
                todate = $('#txtToDate').val();

            })
        } else {
            /* $('#cmbselect').val("0");
                 selected = 0;*/
            fromdate = null;
            todate = null
            selected = null
        }


    });



    $('#chkSalesrep').on('change', function () {

        if (this.checked) {
            selectSalesrep = $('#cmbsalesRep').val();
            $('#cmbsalesRep').change(function () {
                selectSalesrep = $('#cmbsalesRep').val();
            })
        } else {
            selectSalesrep = null

        }

    })




    var report;
    let jsonData = {};
    $("#chequeAudit").prop("checked", true);
    var isChecked = $("#chequeAudit").prop("checked");
    if (isChecked == true) {
        $("#chequeAudit").prop("checked", false);
    }



    const currentDate = new Date().toISOString().slice(0, 10);


    document.getElementById("txtFromDate").value = currentDate;
    document.getElementById("txtToDate").value = currentDate;


    $("input[type='radio']").click(function () {

        report = this.id;

    });



    $('#viewReport').on('click', function () {
        console.log(filters.supplygroup);

        if (filters.fromDate) {
            filters.fromDate = $('#txtFromDate').val()
        }
        if (filters.toDate) {
            filters.toDate = $('#txtToDate').val()
        }
        if (filters.branch) {
            filters.branch = $('#cmbBranch').val();
        }
        if (filters.salesRep) {
            filters.salesRep = $('#cmbsalesRep').val();
        }
        if (filters.customer) {
            filters.customer = $('#cmbCustomer').val();
        }
        if (filters.supplygroup) {
            filters.supplygroup = $('#cmbSupplyGroup').val();
        }

        if (report == null || report == undefined) {
            showWarningMessage(" select Report");
            return;
        }

        /*if (!$('#chkdate').prop('checked')) {
            showWarningMessage(" Please select date range");
            return;
        }*/
        console.log(filters);
        $('#row1').hide();
        if (report == "poHelpReport") {


            $.ajax({
                url: '/prc/poHelpReport/' + JSON.stringify(filters),
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    showPoHelpReport(data, 'PRINT');
                }
            });



        }



        PRINT_STATUS = true;






    });


    $('#chkdate').on('change', function () {
        if ($(this).prop('checked')) {
            filters.fromDate = true;
            filters.toDate = true;
        } else {
            filters.fromDate = null;
            filters.toDate = null;
        }
    });


    $('#chkBranch').on('change', function () {
        if ($(this).prop('checked')) {
            filters.branch = true;
        } else {
            filters.branch = null;
        }
    });

    $('#chkSupplyGroup').on('change', function () {
        if ($(this).prop('checked')) {
            filters.supplygroup = true;
        } else {
            filters.supplygroup = null;
        }
    });

    $('#chkSalesrep').on('change', function () {
        if ($(this).prop('checked')) {
            filters.salesRep = true;
        } else {
            filters.salesRep = null;
        }
    });

    $('#chkCustomer').on('change', function () {
        if ($(this).prop('checked')) {
            filters.customer = true;
        } else {
            filters.customer = null;
        }
    });

   

});



function getCustomer() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getCustomer",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.customer_id + "'>" + value.customer_name + "<input type='checkbox'></option>";


            })

            $('#cmbCustomer').html(data);

        }

    });

}

function loadbranch() {
    $.ajax({
        url: '/sc/getbranch',
        method: 'GET',
        async: false,
        success: function (data) {

            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.branch_id + "'>" + value.branch_name + "<input type='checkbox'></option>";


            })

            $('#cmbBranch').html(data);

        }
    })
}


function loadSupplyGroup() {
    $.ajax({
        url: '/sc/getSupllyGroup',
        method: 'GET',
        async: false,
        success: function (data) {

            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.supply_group_id + "'>" + value.supply_group + "<input type='checkbox'></option>";


            })

            $('#cmbSupplyGroup').html(data);

        }
    })
}




function getSalesrep() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/getSalesrep",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.employee_id + "'>" + value.employee_name + "<input type='checkbox'></option>";


            })

            $('#cmbsalesRep').html(data);

        }

    });

}



function dataclear() {
    $('input[type="checkbox"]').prop('checked', false);
    $('input[type="number"]').val("");


}






function showPoHelpReport(result, flag) {
    // Assuming 'result' contains the JSON object
    console.log(result);


    var groupedData = {};

    // Iterate through the 'data' array in the JSON object
    $.each(result.data, function (index, element) {
        var supplyGroupId = element.supply_group_id;

        if (!groupedData[supplyGroupId]) {
            // If the key doesn't exist in groupedData, create an array for it
            groupedData[supplyGroupId] = [];
        }

        groupedData[supplyGroupId].push(element);
    });
    var all_data_array = []
    // Loop through each supply_group_id in groupedData
    for (var supplyGroupId in groupedData) {
        if (groupedData.hasOwnProperty(supplyGroupId)) {
            // Access the array of objects for the current supply_group_id
            var groupDataArray = groupedData[supplyGroupId];
            all_data_array.push(groupDataArray);
           

        }
    }
    generateTables(groupDataArray);
}


//create html table
function generateTables(Data){
    var townName = "Negombo";
    var suuply_group = "Test"

    var body = `<div id="sup_grp_data">
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inline CSS Table</title>
    </head>
    <body>
    <h4>Supply Group: ${suuply_group}</h4>
    <table style="border-collapse: collapse; width: 100%;">
        <tr>
            <th rowspan="2" style="border: 1px solid black; padding: 8px;"></th>
            <th rowspan="2" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">Pacs</th>
            <div class="town_div">
                <th colspan="2" style="border: 1px solid black; padding: 8px; background-color: #f2f2f2;">${townName}</th>
            </div>
            
        </tr>
        <tr>
            <div class="town_div">
                <td style="border: 1px solid black; padding: 8px;">RDQTY</td>
                <td style="border: 1px solid black; padding: 8px;">QQH</td>
            </div>
        </tr>
        <!-- Add rows for each test -->
        <tr>
            <td style="border: 1px solid black; padding: 8px;">TEST-01 30S</td>
            <td style="border: 1px solid black; padding: 8px;">1s</td>
            <td style="border: 1px solid black; padding: 8px;">11</td>
            <td style="border: 1px solid black; padding: 8px;">89</td>
        </tr>
        <tr>
            <td style="border: 1px solid black; padding: 8px;"></td>
            <td style="border: 1px solid black; padding: 8px;"></td>
        </tr>
    </table>
    
    </body>
    </html>
    
    </div>`;
    
    // Open a new window with the HTML content
    var newWindow = window.open();
    newWindow.document.write(body);
    
    // Wait for the content to be fully loaded
    newWindow.document.addEventListener("DOMContentLoaded", function () {
        // Use the browser's print feature to generate a PDF
        newWindow.print();
    });
    
    // Close the window after printing
    newWindow.onafterprint = function () {
        newWindow.close();
    };
    

    

    
    
}

    

















