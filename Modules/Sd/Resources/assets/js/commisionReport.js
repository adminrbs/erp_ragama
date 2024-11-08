
var selectCollector = null;
var fromdate = null;
var todate = null;
var selecteBranch = null;
var length;
var selectedCheckboxes = [];
var checkboxId;
var chechid;
var PRINT_CONTENT = '';
$(document).ready(function () {
    $('#crdReportSearch').hide();
    $('#pdfContainer').hide();

    $('#btn_advanced_search').on('click', function () {
        PRINT_STATUS = false;
        $('#crdReportSearch').show();
        $('#pdfContainer').hide();
    });

    var iframe = document.getElementById('pdfContainer');
    iframe.onload = function () {
        // Access the contentDocument or contentWindow.document
        var iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

        // Get the inner HTML of the body inside the iframe
        PRINT_CONTENT = iframeDocument.body.innerHTML;

    };

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




    
    $('input[type="checkbox"]').prop('checked', false);

    $('input[type="checkbox"]').click(function () {

        checkboxId = $(this).attr('id');

        if (checkboxId === 'chkdate' || checkboxId === 'chkCustomer' || checkboxId === 'chkcustomergroup'
            || checkboxId === 'chkCustomerGrade' || checkboxId === 'chkRoute' || checkboxId === 'Salesrep' || checkboxId === 'chkBranch') {
            if ($(this).prop('checked')) {

                selectedCheckboxes.push(checkboxId);
                length = selectedCheckboxes.length;
                console.log(length);





            } else {

                selectedCheckboxes = selectedCheckboxes.filter(id => id !== checkboxId);
            }

            console.log(selectedCheckboxes);
        }

    });


    loadbranch();
    getSalesrep();

    $('#btn-collapse-search').on('click', function () {

        $('#pdfContainer').attr('src', '');
    });


   
    $('#chkdate').prop('checked', false);
    $('#Salesrep').prop('checked', false);
    $('#chkBranch').prop('checked', false);


    $('#chkBranch').on('change', function () {

        if (this.checked) {
            selecteBranch = $('#cmbBranch').val();
            $('#cmbBranch').change(function () {

               
                selecteBranch = $('#cmbBranch').val();

            })


           
        } else {

          

            selecteBranch = null
            selected6 = null
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

   

    

   
  
    $('#chkCollector').on('change', function () {

        if (this.checked) {
            selectCollector = $('#cmbCollector').val();
            $('#cmbCollector').change(function () {
                selectCollector = $('#cmbCollector').val();
            })
        } else {
            selectCollector = null

        }

    })

    var report;
    let jsonData = {};
    $("#salesReport").prop("checked", true);
    var isChecked = $("#salesReport").prop("checked");
    if (isChecked == true) {
        $("#salesReport").prop("checked", false);
    }
  

  
   
    

    const currentDate = new Date().toISOString().slice(0, 10);


    document.getElementById("txtFromDate").value = currentDate;
    document.getElementById("txtToDate").value = currentDate;


    $("input[type='radio']").click(function () {
        report = $(this).attr('id');
    });



    $('#viewReport').on('click', function () {

        //alert(report);
        if(!$('#chkdate').prop('checked')){
            showWarningMessage("Please select the date range")
        }else{
        
        if (report == "commisionReport") {
            
            var requestData = [

               

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectCollector: selectCollector },
              

            ];


      
            $('#pdfContainer').attr('src', '/sd/generatecommisionReport/' + JSON.stringify(requestData));



        }
    }

        if (report == null || report == undefined) {
            showWarningMessage(" select Report");
        }

        PRINT_STATUS = true;

        if (report != "salesRepwiseMonthlySummary") {
            $('#crdReportSearch').hide();
            $('#pdfContainer').show();
        }

    });
});




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



function getSalesrep() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/getSalesrep",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.employee_id + "'>" + value.employee_name + "<input type='checkbox'></option>";


            })

            $('#cmbCollector').html(data);

        }

    });

}








