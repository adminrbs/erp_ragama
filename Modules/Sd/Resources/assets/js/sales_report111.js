

var selecteCustomer = null;
var selectecustomergroup = null;
var selecteCustomerGrade = null;
var selecteRoute = null;
var selectSalesrep = null;
var fromdate = null;
var todate = null;
var fromAge = null;
var toAge = null;
var selecteBranch = null;
var cmbgreaterthan = null;
var cmbMarketingRoute = null;
var cmbSupplyGroup = null;
var cmbProduct = null;



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




    $('#btn-collapse-search').on('click', function () {
        $('#row1').show();
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


    loadbranch()
    getCustomer()
    getCustomergroup()
    getcustomergrade()
    getRoute()
    getSalesrep()
    getMarketingRoute();
    getSupplyGroup();
    getproduct();

    $('#btn-collapse-search').on('click', function () {

        $('#pdfContainer').attr('src', '');
    });


    $('#chkCustomer').prop('checked', false);
    $('#chkdate').prop('checked', false);
    $('#chkcustomergroup').prop('checked', false);
    $('#chkCustomerGrade').prop('checked', false);
    $('#chkRoute').prop('checked', false);
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

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

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

    $('#chkcustomergroup').on('change', function () {

        if (this.checked) {
            selectecustomergroup = $('#cmbcustomergroup').val();
            $('#cmbcustomergroup').change(function () {
                selectecustomergroup = $('#cmbcustomergroup').val();
            })
        } else {
            selectecustomergroup = null
            selected2 = null
            /* $('#cmbselect1').val("0");
             selected1 = 0;*/
        }

    })

    $('#chkCustomerGrade').on('change', function () {

        if (this.checked) {
            selecteCustomerGrade = $('#cmbCustomerGrade').val();

            $('#cmbCustomerGrade').change(function () {
                selecteCustomerGrade = $('#cmbCustomerGrade').val();
            })
        } else {
            selecteCustomerGrade = null
            selected3 = null
        }

    })

    $('#chkRoute').on('change', function () {

        if (this.checked) {
            selecteRoute = $('#cmbRoute').val();
            $('#cmbRoute').change(function () {
                selecteRoute = $('#cmbRoute').val();
            })
        } else {
            selecteRoute = null
            selected4 = null
        }

    });
    $('#chkProduct').on('change', function () {

        if (this.checked) {
            cmbProduct = $('#cmbproduct').val();
            $('#cmbproduct').change(function () {
                cmbProduct = $('#cmbproduct').val();
            })
        } else {
            cmbProduct = null
            //selected4 = null
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

    $('#chkreaterthan').on('change', function () {

        if (this.checked) {
            cmbgreaterthan = $('#cmbgreaterthan').val();
            $('#cmbgreaterthan').on('input', function () {
                cmbgreaterthan = $(this).val();
                //alert(cmbgreaterthan);
            });
        } else {
            cmbgreaterthan = null

        }

    })


    $('#chkMarketingRoute').on('change', function () {

        if (this.checked) {
            cmbMarketingRoute = $('#cmbMarketingRoute').val();
            $('#cmbMarketingRoute').on('input', function () {
                cmbMarketingRoute = $(this).val();
                //alert(cmbgreaterthan);
            });
        } else {
            cmbMarketingRoute = null

        }

    });



    $('#chkSupplyGroup').on('change', function () {

        if (this.checked) {
            cmbSupplyGroup = $('#cmbSupplyGroup').val();
            $('#cmbSupplyGroup').on('input', function () {
                cmbSupplyGroup = $(this).val();
            });
        } else {
            cmbSupplyGroup = null

        }

    });


    var report;
    let jsonData = {};
    $("#salesReport").prop("checked", true);
    var isChecked = $("#salesReport").prop("checked");
    if (isChecked == true) {
        $("#salesReport").prop("checked", false);
    }
    $("#salesreturnReport").prop("checked", true);
    var isChecked = $("#salesreturnReport").prop("checked");
    if (isChecked == true) {
        $("#salesreturnReport").prop("checked", false);
    }

    $('#itemCustomerReport').on('change', function () {
        if ($(this).prop('checked')) {
            // $('#cmbsalesRep').prop("disabled",true);
        }
    });
    /*$("#customerOutstanding").prop("checked", true);
    var isChecked = $("#customerOutstanding").prop("checked");
    if (isChecked == true) {
        $("#customerOutstanding").prop("checked", false);
    }*/

    const currentDate = new Date().toISOString().slice(0, 10);


    document.getElementById("txtFromDate").value = currentDate;
    document.getElementById("txtToDate").value = currentDate;


    $("input[type='radio']").click(function () {
        $('input[type="checkbox"]').prop('checked', false);

        report = this.id;

        jsonData = {
            branch: "1",
            customer: "2",
            customergroup: "3",
            customerGrade: "4",
            route: "5",
            graetertahan: "6",
            frodate: "7",
            todate: "8",
            froage: "9",
            toage: "10",
            salesrep: "11",
            item: "12",

        };
        console.log(jsonData);
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "/sd/saleshidefilter/" + report,
            data: JSON.stringify(jsonData),
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                if (typeof response.branch === 'undefined') {

                    $('#cmbBranch').prop('disabled', false);
                    $('#chkBranch').prop('disabled', false);
                } else {

                    $('#cmbBranch').prop('disabled', true);
                    $('#chkBranch').prop('disabled', true);
                }

                if (typeof response.customer === 'undefined') {

                    $('#cmbCustomer').prop('disabled', false);
                    $('#chkCustomer').prop('disabled', false);
                } else {

                    $('#cmbCustomer').prop('disabled', true);
                    $('#chkCustomer').prop('disabled', true);
                }

                if (typeof response.customergroup === 'undefined') {

                    $('#cmbcustomergroup').prop('disabled', false);
                    $('#chkcustomergroup').prop('disabled', false);
                } else {

                    $('#cmbcustomergroup').prop('disabled', true);
                    $('#chkcustomergroup').prop('disabled', true);
                }

                if (typeof response.customerGrade === 'undefined') {

                    $('#cmbCustomerGrade').prop('disabled', false);
                    $('#chkCustomerGrade').prop('disabled', false);
                } else {

                    $('#cmbCustomerGrade').prop('disabled', true);
                    $('#chkCustomerGrade').prop('disabled', true);
                }

                if (typeof response.route === 'undefined') {

                    $('#cmbRoute').prop('disabled', false);
                    $('#chkRoute').prop('disabled', false);
                } else {

                    $('#cmbRoute').prop('disabled', true);
                    $('#chkRoute').prop('disabled', true);
                }
                if (typeof response.graetertahan === 'undefined') {

                    $('#cmbgreaterthan').prop('disabled', false);
                    $('#chkreaterthan').prop('disabled', false);
                } else {

                    $('#cmbgreaterthan').prop('disabled', true);
                    $('#chkreaterthan').prop('disabled', true);
                }
                if (typeof response.frodate === 'undefined') {

                    $('#txtFromDate').prop('disabled', false);
                    $('#chkdate').prop('disabled', false);
                } else {

                    $('#txtFromDate').prop('disabled', true);
                    $('#chkdate').prop('disabled', true);
                }
                if (typeof response.todate === 'undefined') {

                    $('#txtToDate').prop('disabled', false);
                    $('#chkdate').prop('disabled', false);
                } else {

                    $('#txtToDate').prop('disabled', true);
                    $('#chkdate').prop('disabled', true);
                }
                if (typeof response.salesrep === 'undefined') {

                    $('#cmbsalesRep').prop('disabled', false);
                    $('#chkSalesrep').prop('disabled', false);
                } else {

                    $('#cmbsalesRep').prop('disabled', true);
                    $('#chkSalesrep').prop('disabled', true);
                }

                if (typeof response.item === 'undefined') {

                    $('#cmbproduct').prop('disabled', false);
                    $('#chkProduct').prop('disabled', false);
                } else {

                    $('#cmbproduct').prop('disabled', true);
                    $('#chkProduct').prop('disabled', true);
                }

                if (typeof response.marketing_route === 'undefined') {
                    $('#cmbMarketingRoute').prop('disabled', false);
                    $('#chkMarketingRoute').prop('disabled', false);
                } else {

                    $('#cmbMarketingRoute').prop('disabled', true);
                    $('#chkMarketingRoute').prop('disabled', true);
                }


                if (typeof response.supply_group === 'undefined') {
                    $('#cmbSupplyGroup').prop('disabled', false);
                    $('#chkSupplyGroup').prop('disabled', false);
                } else {

                    $('#cmbSupplyGroup').prop('disabled', true);
                    $('#chkSupplyGroup').prop('disabled', true);
                }

            },
            error: function (error) {
                // Handle any errors that occur during the AJAX request
            }
        });





    });



    $('#viewReport').on('click', function () {


        $('#row1').hide();
        if (report == "salesReport") {

            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },
                /* { fromAge: fromAge },
                 { toAge: toAge },
                 { cmbgreaterthan: cmbgreaterthan },*/

            ];


            console.log("llr", requestData);
            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sd/sales_summaryReport/' + JSON.stringify(requestData));



        } if (report == "salesreturnReport") {

            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },
                /* { fromAge: fromAge },
                 { toAge: toAge },
                 { cmbgreaterthan: cmbgreaterthan },*/

            ];


            console.log("llr", requestData);
            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sd/salesreturnReport/' + JSON.stringify(requestData));



        }
        if (report == "salesdetailsReport") {

            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },


            ];

            $('#pdfContainer').attr('src', '/sd/salesdetailsReport/' + JSON.stringify(requestData));



        }
        if (report == "salesreturndetailsReport") {

            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },

            ];

            $('#pdfContainer').attr('src', '/sd/salesreturndetailsReport/' + JSON.stringify(requestData));


        }
        if (report == "productwisequantitysalestype") {

            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },

            ];

            $('#pdfContainer').attr('src', '/sd/productwisequantitysalestype/' + JSON.stringify(requestData));

        }

        if (report == null || report == undefined) {
            showWarningMessage(" select Report");
        }

        PRINT_STATUS = true;

        if (report != "salesRepwiseMonthlySummary") {
            $('#crdReportSearch').hide();
            $('#pdfContainer').show();
        }


        if (report == "salesRepwiseMonthlySummary") {


            if (!$('#chkdate').prop('checked')) {
                showWarningMessage('Please select date range..!');
                return;
            }
            var requestData = [
                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
               
            ];

            $.ajax({
                type: "GET",
                url: "/sd/salesRepwiseMonthlySummary/" + JSON.stringify(requestData),
                cache: false,
                timeout: 800000,
                beforeSend: function () { },
                success: function (response) {
                    console.log(response);

                    saleRepwiseMonthlySummaryReport(response);
                },
                error: function (error) {
                    console.log(error);
                },
                complete: function () { }
            });
        }

        if (report == "itemCustomerReport") {


            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },

            ];

            $('#pdfContainer').attr('src', '/sd/itemCustomerReport/' + JSON.stringify(requestData));
        }
        if (report == "freeSummaryReport") {

            if (!$('#chkdate').prop('checked')) {
                showWarningMessage('Please select date range..!');
                $('#pdfContainer').hide();
                return;
            }
            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },

            ];

            $('#pdfContainer').attr('src', '/sd/freeSummaryReport/' + JSON.stringify(requestData));
        }
        if (report == "supplierWiseFreeIssueDetailsReport") {

            if (!$('#chkdate').prop('checked')) {
                showWarningMessage('Please select date range..!');
                $('#pdfContainer').hide();
                return;
            }
            var requestData = [

                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },

                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { selectSalesrep: selectSalesrep },
                { cmbMarketingRoute: cmbMarketingRoute },
                { cmbSupplyGroup: cmbSupplyGroup },
                { cmbProduct: cmbProduct },

            ];

            $('#pdfContainer').attr('src', '/sd/supplierWiseFreeIssueDetailsReport/' + JSON.stringify(requestData));
        }

    });
});


//create Sales Rep Wise Monthly Summery Report
function saleRepwiseMonthlySummaryReport(data){
    
    var iframe = document.getElementById('pdfContainer');
    var company_data = data.company_data;
    var bodyData = data.body;
    var htmlBody = ''
    console.log(bodyData[4]);
    var branch_list = data.branch_list;

    var htmlReport = '<!DOCTYPE html><html lang="en">';
        htmlReport += '<head></head>';
        htmlReport += '<body>';

    var htmlHeader = '<div style="text-align: center;"><h2>'+company_data.company_name+'</h2>';
        htmlHeader += '<h4>Sales Rep Wise Monthly Summery From :' + data.report_date + '</h4>';
        htmlHeader += '</div>';

     htmlBody += createTable(branch_list,bodyData);

        htmlReport += htmlHeader + htmlBody;
        htmlReport+='</body>';

    iframe.srcdoc = htmlReport;

    $('#crdReportSearch').hide();
    $('#pdfContainer').show();
    
    
    
    
}

function createTable(branch_list,body_data){
    console.log(branch_list);
    console.log(body_data);
    var htmlBranchName = '';

    for(var i = 0; i < branch_list.length; i++){
        var data = body_data[branch_list[i]];
        var branch_name = '';

        var htmlDataTable = '<table><tr><th><td>Rep Code</td><td>Rep Name</td>';
        
        for(var j = 0; j < data.length; j++){
            console.log(data[j]);
            branch_name = data[j].branch_name
            htmlDataTable

        }
        htmlBranchName += branch_name;





    }
}





















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
//loading Distributor
function getDistributor() {
    $.ajax({
        url: '/getDistributor',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.distributor_id + '">' + value.distributor_name + '</option>');

            })

        },
    })
}


function getCustomergroup() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getCustomergroup",

        success: function (data) {



            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.customer_group_id + "'>" + value.group + "<input type='checkbox'></option>";


            })

            $('#cmbcustomergroup').html(data);

        }

    });

}


function getcustomergrade() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getcustomergrade",

        success: function (data) {
            console.log(data);

            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.customer_grade_id + "'>" + value.grade + "<input type='checkbox'></option>";


            })

            $('#cmbCustomerGrade').html(data);

        }

    });

}


function getRoute() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getRoute",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.route_id + "'>" + value.route_name + "<input type='checkbox'></option>";


            })

            $('#cmbRoute').html(data);

        }

    });

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


function getMarketingRoute() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/getMarketingRoute",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.marketing_route_id + "'>" + value.route_name + "<input type='checkbox'></option>";


            })

            $('#cmbMarketingRoute').html(data);

        }

    });

}



function getSupplyGroup() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/getSupplyGroup",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.supply_group_id + "'>" + value.supply_group + "<input type='checkbox'></option>";


            })

            $('#cmbSupplyGroup').html(data);

        }

    });

}


function getproduct() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getproduct",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.item_id + "'>" + value.item_Name + "<input type='checkbox'></option>";


            })

            $('#cmbproduct').html(data);

        }

    });

}


function dataclear() {
    $('input[type="checkbox"]').prop('checked', false);
    $('input[type="number"]').val("");

    selecteCustomer = null;
    selectecustomergroup = null;
    selecteCustomerGrade = null;
    selecteRoute = null;
    selectSalesrep = null;
    fromdate = null;
    todate = null;
    selecteBranch = null;
    cmbgreaterthan = null;
    cmbMarketingRoute = null;
    cmbSupplyGroup = null;
    cmbProduct = null;

}










