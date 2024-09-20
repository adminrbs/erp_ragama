var selected = null;
var selected1 = null;
var selected2 = null;
var selected3 = null;
var selected4 = null;
var selected5 = null;
var selected6 = null;
var selected7 = null;


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
var selectSupplyGroup = null;
var selectCollector = null;
//var cmbSalesrep = null;



var length;
var selectedCheckboxes = [];
var checkboxId;
var chechid;
var PRINT_STATUS = false;

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

    // $('#customerOutstanding').change(function () {

    /*$('#cmbBranch').empty();
    $('#cmbCustomer').empty();
    $('#cmbcustomergroup').empty();
    $('#cmbCustomerGrade').empty();
    $('#cmbRoute').empty();
    $('input[type="number"]').val("");
    
    loadbranch()
    getCustomer()
    getCustomergroup()
    getcustomergrade()
    getRoute()
    getSalesrep()*/


    //  })
    /*   $('#debtorleger').change(function () {
          
           var branch = $('#cmbBranch')[0]; 
           if (branch.selectedIndex !== -1) {
               branch.selectedIndex = -1; 
             
           }
           $('.access-destroy').on('click', function () { $('.select-access-create').select2('destroy'); });
     
    */
    /* $('#cmbCustomer').empty();
     $('#cmbcustomergroup').empty();
     $('#cmbCustomerGrade').empty();
     $('#cmbRoute').empty();
     $('input[type="number"]').val("");
    
     loadbranch()
     getCustomer()
     getCustomergroup()
     getcustomergrade()
     getRoute()
     getSalesrep()*/

    //})
    /*$('#Customer_Ledger').change(function () {
        $('#cmbBranch').empty();
        $('#cmbCustomer').empty();
        $('#cmbcustomergroup').empty();
        $('#cmbCustomerGrade').empty();
        $('#cmbRoute').empty();
        $('input[type="number"]').val("");
    
        loadbranch()
        getCustomer()
        getCustomergroup()
        getcustomergrade()
        getRoute()
        getSalesrep()
     
    
    })*/
    $('#txttxtToDate').change(function () {

        // $('#chkdate').prop('checked', false);
    })

    $('#cmbBranch').change(function () {

        //$('#chkBranch').prop('checked', false);

    })
    $('#txtFromDate').change(function () {

        //$('#chkdate').prop('checked', false);

    })
    $('#txttxtToDate').change(function () {

        //$('#chkdate').prop('checked', false);
    })
    $('#cmbCustomer').change(function () {

        // $('#chkCustomer').prop('checked', false);

    })

    $('#cmbcustomergroup').change(function () {

        //$('#chkcustomergroup').prop('checked', false);

    })
    $('#cmbCustomerGrade').change(function () {

        // $('#chkCustomerGrade').prop('checked', false);

    })
    $('#cmbRoute').change(function () {

        //$('#chkRoute').prop('checked', false);

    })
    $('#cmbSalesrep').change(function () {

        // $('#Salesrep').prop('checked', false);

    })


    loadbranch();
    getCustomer();
    getCustomergroup();
    getcustomergrade();
    getRoute();
    getSalesrep();
    getSupplyGroup();
    getCollectors();



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








    //const currentDate = new Date().toISOString().slice(0, 10);


    //document.getElementById("txtFromDate").value = currentDate;
    //document.getElementById("txtToDate").value = currentDate;


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


    $('#cmbselect').change(function () {

        selected = $('#cmbselect').val();
        //alert(se)
        //getselect(selected);

    })
    $('#cmbselect1').change(function () {


        selected1 = $('#cmbselect1').val();
        //getselect(selected);

    })

    $('#cmbselect2').change(function () {

        selected2 = $('#cmbselect2').val();

        //getselect(selected1);
    })
    $('#cmbselect3').change(function () {

        selected3 = $('#cmbselect3').val();

        //getselect(selected1);
    })
    $('#cmbselect4').change(function () {

        selected4 = $('#cmbselect4').val();

        //getselect(selected1);
    })
    $('#cmbselect5').change(function () {

        selected5 = $('#cmbselect5').val();

        //getselect(selected1);
    })

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

    });

    $('#chkSupplyGroup').on('change', function () {
        
        if (this.checked) {
            selectSupplyGroup = $('#cmbSupplyGroup').val();
            $('#cmbSupplyGroup').change(function () {
                selectSupplyGroup = $('#cmbSupplyGroup').val();

            })

        } else {

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

             selectSupplyGroup = null
            //selected1 = null
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
            /* $('#cmbselect').val("0");
                 selected = 0;*/
           
                 selectCollector = null
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

    })
    $('#chkSalesrep').on('change', function () {

        if (this.checked) {
            selectSalesrep = $('#cmbSalesrep').val();
            $('#cmbSalesrep').change(function () {
                selectSalesrep = $('#cmbSalesrep').val();
            })
        } else {
            selectSalesrep = null
            selected5 = null
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
    $('#chkfromtoAge').on('change', function () {
       
        var fromage = $('#txtfromAge').val();
        var toage = $('#txtToAge').val();

     
            if (fromage.length > 0 && toage.length <= 0) {
                showWarningMessage("Enter To(Age)");
                $('#chkfromtoAge').prop('checked', false);
            } else if (fromage.length <= 0 && toage.length > 0) {
                //alert("to from")
                showWarningMessage("Enter From(Age)");
                $('#chkfromtoAge').prop('checked', false);
            }

         else  if(fromage.length > 0 && toage.length> 0){

            if (this.checked) {

                fromAge = $('#txtfromAge').val();
                toAge = $('#txtToAge').val();

                $('#txtfromAge').change(function () {

                    fromAge = $('#txtfromAge').val();


                })
                $('#txtToAge').change(function () {
                    toAge = $('#txtToAge').val();

                })
            } else {
                /* $('#cmbselect').val("0");
                     selected = 0;*/
                fromAge = null;
                toAge = null

            }
         }


    });


});



$(document).ready(function () {
    var elementIds = ["cmbgreaterthan", "txtfromAge", "txtToAge"];

    // Attach the same event listener to each element
    elementIds.forEach(function (id) {
        var element = document.getElementById(id);
        element.addEventListener("input", function () {
            var inputValue = this.value;
            var numericValue = inputValue.replace(/[^0-9]/g, '');
            this.value = numericValue;
        });
    });

    var report;
    let jsonData = {};
    $("#debtorleger").prop("checked", true);

    var isChecked = $("#debtorleger").prop("checked");
    if (isChecked == true) {
        $("#debtorleger").prop("checked", false);
    }
    $("#Customer_Ledger").prop("checked", true);
    var isChecked = $("#Customer_Ledger").prop("checked");
    if (isChecked == true) {
        $("#Customer_Ledger").prop("checked", false);
    }
    $("#customerOutstanding").prop("checked", true);
    var isChecked = $("#customerOutstanding").prop("checked");
    if (isChecked == true) {
        $("#customerOutstanding").prop("checked", false);
    }



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
            salesrep: "11"


        };
        console.log(jsonData);
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "/sc/hidefilter/" + report,
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

                //from to age
                if (typeof response.froage === 'undefined') {

                    $('#txtfromAge').prop('disabled', false);
                    $('#chkfromtoAge').prop('disabled', false);
                } else {

                    $('#txtfromAge').prop('disabled', true);
                    $('#chkfromtoAge').prop('disabled', true);
                }
                if (typeof response.toage === 'undefined') {

                    $('#txtToAge').prop('disabled', false);
                    $('#chkfromtoAge').prop('disabled', false);
                } else {

                    $('#txtToAge').prop('disabled', true);
                    $('#chkfromtoAge').prop('disabled', true);
                }
                if (typeof response.salesrep === 'undefined') {
                    $('#cmbSalesrep').prop('disabled', false);
                    $('#chkSalesrep').prop('disabled', false);
                } else {
                    $('#cmbSalesrep').prop('disabled', true);
                    $('#chkSalesrep').prop('disabled', true);
                }



            },
            error: function (error) {
                // Handle any errors that occur during the AJAX request
            }
        });





    });

    $('#viewReport').on('click', function () {



        if (report == "debtorleger") {

            var requestData = [
                { selected: selected },
                { selected1: selected1 },
                { selected2: selected2 },
                { selected3: selected3 },
                { selected4: selected4 },
                { selected5: selected5 },
                //{ selected6: selected6 },
                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },
                { selectSalesrep: selectSalesrep },
                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { fromAge: fromAge },
                { toAge: toAge },
                { cmbgreaterthan: cmbgreaterthan },

            ];


            console.log("llr", requestData);
            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/debtor_reports/' + JSON.stringify(requestData));


        }

        if (report == "Customer_Ledger") {


            /*if (selecteCustomer === null && selectecustomergroup === null && selecteCustomerGrade === null && selecteRoute === null && selectSalesrep === null && fromdate === null && todate === null) {
                showWarningMessage(" select Filter Option");
            } else {*/
            var requestData = [
                { selected: selected },
                { selected1: selected1 },
                { selected2: selected2 },
                { selected3: selected3 },
                { selected4: selected4 },
                { selected5: selected5 },
                //{ selected6: selected6 },
                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },
                { selectSalesrep: selectSalesrep },

                { selecteBranch: selecteBranch },

                { fromdate: fromdate },
                { todate: todate },

                { fromAge: fromAge },
                { toAge: toAge },
                { cmbgreaterthan: cmbgreaterthan },
            ];
            console.log("cusL", requestData);


            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/Customer_Ledger_reports/' + JSON.stringify(requestData));


        }
        if (report === "customerOutstanding") {



            if (report == null || report == undefined || report == "") {
                showWarningMessage("Select Filter Option");
                console.log(report);
            }

            /*if (selecteCustomer === null && selectecustomergroup === null && selecteCustomerGrade === null && selecteRoute === null && selectSalesrep === null && fromdate === null && todate === null) {
                showWarningMessage(" select Filter Option");
            } else {*/
            var requestData = [
                { selected: selected },
                { selected1: selected1 },
                { selected2: selected2 },
                { selected3: selected3 },
                { selected4: selected4 },
                { selected5: selected5 },
                //{ selected6: selected6 },
                { selecteCustomer: selecteCustomer },
                { selectecustomergroup: selectecustomergroup },
                { selecteCustomerGrade: selecteCustomerGrade },
                { selecteRoute: selecteRoute },
                { selectSalesrep: selectSalesrep },
                { selecteBranch: selecteBranch },
                { cmbgreaterthan: cmbgreaterthan },
                { fromdate: fromdate },
                { todate: todate },
                { fromAge: fromAge },
                { toAge: toAge },
                { supplyGroup:selectSupplyGroup }

            ];



            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/printoutstandinReport/' + JSON.stringify(requestData));

        }

        if (report == "outstandingInvoiceWise") {

            if(!$('#chkdate').prop('checked')){
                showWarningMessage("Please select a date range");
                return false;
            }

            var requestData = [
              
                { selecteCustomer: selecteCustomer },
                { selecteRoute: selecteRoute },
                { selectSalesrep: selectSalesrep },
                { selecteBranch: selecteBranch },
                { fromdate: fromdate },
                { todate: todate },
                { fromAge: fromAge },
                { toAge: toAge },
                { cmbgreaterthan: cmbgreaterthan },
                { selectCollector: selectCollector }

            ];


            console.log("llr", requestData);
            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/debtor_reports_invoiceWise/' + JSON.stringify(requestData));


        }
        if (report == null || report == undefined) {


            showWarningMessage(" select Report");


        }
        PRINT_STATUS = true;
        $('#crdReportSearch').hide();
        $('#pdfContainer').show();

    });
});

function getCustomer() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getCustomer",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.customer_id + "'>" + value.customer_name +'-'+value.customer_code+ "<input type='checkbox'></option>";


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
        url: "/sc/getSalesrepfor_report",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.employee_id + "'>" + value.employee_name + "<input type='checkbox'></option>";


            })

            $('#cmbSalesrep').html(data);
            

        }

    });

}

function getCollectors() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getCollectorsfor_report",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.employee_id + "'>" + value.employee_name + "<input type='checkbox'></option>";


            })

            $('#cmbCollector').html(data);
            

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
    fromAge = null;
    toAge = null;
    selecteBranch = null;
    cmbgreaterthan = null;

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
