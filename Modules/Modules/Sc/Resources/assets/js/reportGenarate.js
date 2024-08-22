var selected = null;
var selected1 = null;
var selected2 = null;
var selected3 = null;
var selected4 = null;
var selected5 = null;
var selected6 = null


var selectedproduct = null;
var selectecategory1 = null;
var selectecategory2 = null;
var selectecategory3 = null;
var selectSupplygroup = null;
var fromdate = null;
var todate = null;
var selecteBranch = null;
var selecteLocation = null;




var length;
var selectedCheckboxes = [];
var checkboxId;
var chechid;
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


    document.addEventListener("DOMContentLoaded", function () {
        const stockBalanceRadioButton = document.getElementById("stock-balance");
        if (stockBalanceRadioButton) {
            stockBalanceRadioButton.checked = false;
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        const stockBalanceRadioButton = document.getElementById("item-movement");
        if (stockBalanceRadioButton) {
            stockBalanceRadioButton.checked = false;
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        const stockBalanceRadioButton = document.getElementById("valuations");
        if (stockBalanceRadioButton) {
            stockBalanceRadioButton.checked = false;
        }
    });
    document.addEventListener("DOMContentLoaded", function () {
        const stockBalanceRadioButton = document.getElementById("rdStock");
        if (stockBalanceRadioButton) {
            stockBalanceRadioButton.checked = false;
        }
    });



    //valuation
    $('input[type="checkbox"]').prop('checked', false);

    $('input[type="checkbox"]').click(function () {

        checkboxId = $(this).attr('id');

        if (checkboxId === 'chkdate' || checkboxId === 'chkProduct' || checkboxId === 'chkitemCategory1'
            || checkboxId === 'chkitemCategory2' || checkboxId === 'chkitemCategory3' || checkboxId === 'chksuplygroup' || checkboxId === 'chkBranch') {
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



    $('#cmbBranch').change(function () {

       var branch_array = $('#cmbBranch').val();
        getlocation(branch_array);

    });
    $('#txtFromDate').change(function () {

        //$('#chkdate').prop('checked', false);

    });
    $('#txtToDate').change(function () {

        //$('#chkdate').prop('checked', false);
    });
    $('#cmbproduct').change(function () {

        //$('#chkProduct').prop('checked', false);

    })

    $('#cmbitemCategory1').change(function () {

        //$('#chkitemCategory1').prop('checked', false);

    })
    $('#cmbitemCategory2').change(function () {

        //$('#chkitemCategory2').prop('checked', false);

    })
    $('#cmbitemCategory3').change(function () {

        //$('#chkitemCategory3').prop('checked', false);

    })
    $('#cmbsuplygroup').change(function () {
        getproduct_sup_id($(this).val());
        //$('#chksuplygroup').prop('checked', false);

    })

    $('#cmbselect').prop('selectedIndex', 0);
    $('#cmbselect1').prop('selectedIndex', 0);
    $('#cmbselect2').prop('selectedIndex', 0);
    $('#cmbselect3').prop('selectedIndex', 0);
    $('#cmbselect4').prop('selectedIndex', 0);
    $('#cmbselect5').prop('selectedIndex', 0);
    //$('#cmbselect6').prop('selectedIndex', 0);

    loadbranch()
    //getproduct()
    getItemCategory1()
    getItemCategory2()
    getItemCategory3()
    getsuplygroup()
    //getlocation()


    /* $('#viewReport').on('click', function () {
 
         $('#pdfContainer').attr('src', '/sc/stockBalanceReport');
     });*/

    $('#btn-collapse-search').on('click', function () {

        $('#pdfContainer').attr('src', '');
    });
    // $('.select2').select2();

    $('#chkProduct').prop('checked', false);
    $('#chkdate').prop('checked', false);
    $('#chkitemCategory1').prop('checked', false);
    $('#chkitemCategory2').prop('checked', false);
    $('#chkitemCategory3').prop('checked', false);
    $('#chksuplygroup').prop('checked', false);
    $('#chkBranch').prop('checked', false);


    const currentDate = new Date().toISOString().slice(0, 10);


    document.getElementById("txtFromDate").value = currentDate;
    document.getElementById("txtToDate").value = currentDate;




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
                selecteBranch = $('#cmbBranch').val();
            })
        } else {

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

            selecteBranch = null
            selected6 = null
        }

    })

    $('#chklocation').on('change', function () {

        if (this.checked) {
            selecteLocation = $('#cmblocation').val();
            $('#cmblocation').change(function () {
                selecteLocation = $('#cmblocation').val();
            })
        } else {

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

            selecteLocation = null

        }

    })



    $('#chkProduct').on('change', function () {

        if (this.checked) {
            selectedproduct = $('#cmbproduct').val();
            $('#cmbproduct').change(function () {
                selectedproduct = $('#cmbproduct').val();
            })
        } else {

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

            selectedproduct = null
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
            //getcurrontDate(); 
        } else {
            /* $('#cmbselect').val("0");
                 selected = 0;*/
            fromdate = null;
            todate = null
            selected = null
        }


    });

    $('#chkitemCategory1').on('change', function () {

        if (this.checked) {
            selectecategory1 = $('#cmbitemCategory1').val();
            $('#cmbitemCategory1').change(function () {
                selectecategory1 = $('#cmbitemCategory1').val();
            })
        } else {
            selectecategory1 = null
            selected2 = null
            /* $('#cmbselect1').val("0");
             selected1 = 0;*/
        }

    })

    $('#chkitemCategory2').on('change', function () {

        if (this.checked) {
            selectecategory2 = $('#cmbitemCategory2').val();
            $('#cmbitemCategory2').change(function () {
                selectecategory2 = $('#cmbitemCategory2').val();
            })
        } else {
            selectecategory2 = null
            selected3 = null
        }

    })

    $('#chkitemCategory3').on('change', function () {

        if (this.checked) {
            selectecategory3 = $('#cmbitemCategory3').val();
            $('#cmbitemCategory3').change(function () {
                selectecategory3 = $('#cmbitemCategory3').val();
            })
        } else {
            selectecategory3 = null
            selected4 = null
        }

    })
    $('#chksuplygroup').on('change', function () {

        if (this.checked) {
            selectSupplygroup = $('#cmbsuplygroup').val();
            $('#cmbsuplygroup').change(function () {
                selectSupplygroup = $('#cmbsuplygroup').val();
            })
        } else {
            selectSupplygroup = null
            selected5 = null
        }

    })


});



$(document).ready(function () {
    var report;
    $("#stock-balance").prop("checked", true);
    var isChecked = $("#stock-balance").prop("checked");
    if (isChecked == true) {
        $("#stock-balance").prop("checked", false);
    }


    $("#item-movement").prop("checked", true);
    var isChecked = $("#item-movement").prop("checked");
    if (isChecked == true) {

        $("#item-movement").prop("checked", false);
    }



    $("#valuations").prop("checked", true);
    var isChecked = $("#valuations").prop("checked");
    if (isChecked == true) {
        $("#valuations").prop("checked", false);
    }

    $("#rdStock").prop("checked", true);
    var isChecked = $("#rdStock").prop("checked");
    if (isChecked == true) {
        $("#rdStock").prop("checked", false);
    }

    $("#rdStockWithFree").prop("checked", true);
    var isChecked = $("#rdStockWithFree").prop("checked");
    if (isChecked == true) {
        $("#rdStockWithFree").prop("checked", false);
    }


    $("input[type='radio']").click(function () {
        $('input[type="checkbox"]').prop('checked', false);
        report = this.id;

        jsonData = {
            branch: "1",
            product: "2",
            caregory1: "3",
            caregory2: "4",
            caregory3: "5",
            supplygroup: "6",
            frodate: "7",
            todate: "8",
            location: "9",

        };
        console.log(jsonData);
        $.ajax({
            type: "post",
            dataType: 'json',
            url: "/sc/stockcontrol/" + report,
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

                if (typeof response.product === 'undefined') {

                    $('#cmbproduct').prop('disabled', false);
                    $('#chkProduct').prop('disabled', false);
                } else {

                    $('#cmbproduct').prop('disabled', true);
                    $('#chkProduct').prop('disabled', true);
                }

                if (typeof response.caregory1 === 'undefined') {

                    $('#cmbitemCategory1').prop('disabled', false);
                    $('#chkitemCategory1').prop('disabled', false);
                } else {

                    $('#cmbitemCategory1').prop('disabled', true);
                    $('#chkitemCategory1').prop('disabled', true);
                }

                if (typeof response.caregory2 === 'undefined') {

                    $('#cmbitemCategory2').prop('disabled', false);
                    $('#chkitemCategory2').prop('disabled', false);
                } else {

                    $('#cmbitemCategory2').prop('disabled', true);
                    $('#chkitemCategory2').prop('disabled', true);
                }


                if (typeof response.caregory3 === 'undefined') {

                    $('#cmbitemCategory3').prop('disabled', false);
                    $('#chkitemCategory3').prop('disabled', false);
                } else {

                    $('#cmbitemCategory3').prop('disabled', true);
                    $('#chkitemCategory3').prop('disabled', true);
                }

                if (typeof response.supplygroup === 'undefined') {

                    $('#cmbsuplygroup').prop('disabled', false);
                    $('#chksuplygroup').prop('disabled', false);
                } else {

                    $('#cmbsuplygroup').prop('disabled', true);
                    $('#chksuplygroup').prop('disabled', true);
                }
                if (typeof response.location === 'undefined') {

                    $('#cmblocation').prop('disabled', false);
                    $('#chklocation').prop('disabled', false);
                } else {

                    $('#cmblocation').prop('disabled', true);
                    $('#chklocation').prop('disabled', true);
                }



            },
            error: function (error) {
                // Handle any errors that occur during the AJAX request
            }
        });

    });





    $('#viewReport').on('click', function () {

        if ((!$('#chkdate').prop('checked')) && (report == "rdStock" || report == "rdStockWithFree" || report == "branchwiseStockReport")) {
            showWarningMessage('Please select date range..!');
            return;
        }

        if (report == 'stock-balance') {


            if(!$('#chksuplygroup').prop('checked')){
                showWarningMessage('Please select a supply group');
                return false;
            }
           



            /*if (selectedproduct === null && selectecategory1 === null && selectecategory2 === null && selectecategory3 === null && selectSupplygroup === null && fromdate === null && todate === null) {
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
                { selectedproduct: selectedproduct },
                { selectecategory1: selectecategory1 },
                { selectecategory2: selectecategory2 },
                { selectecategory3: selectecategory3 },
                { selectSupplygroup: selectSupplygroup },
                { fromdate: fromdate },
                { todate: todate },
                { selecteBranch: selecteBranch },
                { selecteLocation: selecteLocation }

            ];
            console.log(requestData);


            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/stockBalanceReport/' + JSON.stringify(requestData));

            //}





            //$('#pdfContainer').attr('src', '/sc/stockBalanceReport');



        }

        if (report == "item-movement") {


            if(!$('#chkdate').prop('checked')){
                showWarningMessage('Please select date range...!');
                return;
            }

            if(!$('#chkProduct').prop('checked')){
                showWarningMessage('Please select item...!');
                return;
            }

           


            /* if (selectedproduct === null && selectecategory1 === null && selectecategory2 === null && selectecategory3 === null && selectSupplygroup === null && fromdate === null && todate === null) {
                 showWarningMessage(" select Filter Option");
             } else {*/

            var requestData = [
                { selected: selected },
                { selected1: selected1 },
                { selected2: selected2 },
                { selected3: selected3 },
                { selected4: selected4 },
                { selected5: selected5 },
                // { selected6: selected6 },
                { selectedproduct: selectedproduct },
                { selectecategory1: selectecategory1 },
                { selectecategory2: selectecategory2 },
                { selectecategory3: selectecategory3 },
                { selectSupplygroup: selectSupplygroup },
                { fromdate: fromdate },
                { todate: todate },
                { selecteBranch: selecteBranch },
                { selecteLocation: selecteLocation }
            ];

            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/printItemMovementHistoryReport/' + JSON.stringify(requestData));
            // }



        }
        if (report == "valuations") {

            var requestData = [
                { selected: selected },
                { selected1: selected1 },
                { selected2: selected2 },
                { selected3: selected3 },
                { selected4: selected4 },
                { selected5: selected5 },
                // { selected6: selected6 },
                { selectedproduct: selectedproduct },
                { selectecategory1: selectecategory1 },
                { selectecategory2: selectecategory2 },
                { selectecategory3: selectecategory3 },
                { selectSupplygroup: selectSupplygroup },
                { fromdate: fromdate },
                { todate: todate },
                { selecteBranch: selecteBranch },
                { selecteLocation: selecteLocation }
            ];

            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/printvaluationReport/' + JSON.stringify(requestData));
            // }

        }
        if (report == "rdStock") {

            var requestData = [

                { selectSupplygroup: selectSupplygroup },
                { fromdate: fromdate },
                { todate: todate },
                { selecteBranch: selecteBranch },
                { selecteLocation: selecteLocation },

            ];

            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/rdStockreport/' + JSON.stringify(requestData));

        }
        if (report == "branchwiseStockReport") {
            var requestData = [
                { selected: selected },
                { selected1: selected1 },
                { selected2: selected2 },
                { selected3: selected3 },
                { selected4: selected4 },
                { selected5: selected5 },
                //{ selected6: selected6 },
                { selectedproduct: selectedproduct },
                { selectecategory1: selectecategory1 },
                { selectecategory2: selectecategory2 },
                { selectecategory3: selectecategory3 },
                { selectSupplygroup: selectSupplygroup },
                { fromdate: fromdate },
                { todate: todate },
                { selecteBranch: selecteBranch },
                { selecteLocation: selecteLocation }

            ];
            $('#pdfContainer').attr('src', '/sc/branchwiseStockReport/' + JSON.stringify(requestData));

        }
        if (report == "rdStockWithFree") {

            var requestData = [

                { selectSupplygroup: selectSupplygroup },
                { fromdate: fromdate },
                { todate: todate },
                { selecteBranch: selecteBranch },
                { selecteLocation: selecteLocation },

            ];

            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            $('#pdfContainer').attr('src', '/sc/rdStockreportWithFree/' + JSON.stringify(requestData));

        }
        if (report == "sales-summary") {


            $('#pdfContainer').attr('src', '/sc/printoutsalseinvoiseAndRetirnReport');

        }
        if (report == null) {


            showWarningMessage(" select Report");

        }
        PRINT_STATUS = true;
        $('#crdReportSearch').hide();
        $('#pdfContainer').show();

    });
});

//get item accroding to supply group
function getproduct_sup_id(sup_ids) {
    $('#cmbproduct').empty();
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getproduct_sup_id",
        data:{sup_ids:sup_ids},
        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.item_id + "'>" + value.item_Name + "<input type='checkbox'></option>";


            })

            $('#cmbproduct').html(data);

        }

    });

}

function loadbranch() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })
            $('#cmbBranch').change();
        },
    })
}
//loading Distributor
/* function getDistributor() {
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
} */

    function getDistributor() {
        $.ajax({
            url: '/getBranches',
            type: 'get',
            async: false,
            success: function (data) {
                
                $.each(data, function (index, value) {
                    $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
    
                })
                $('#cmbBranch').change();
            },
        })
    }   



function getItemCategory1() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getItemCategory1",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.item_category_level_1_id + "'>" + value.category_level_1 + "<input type='checkbox'></option>";


            })

            $('#cmbitemCategory1').html(data);

        }

    });

}


function getItemCategory2() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getItemCategory2",

        success: function (data) {
            console.log(data);

            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.Item_category_level_2_id + "'>" + value.category_level_2 + "<input type='checkbox'></option>";


            })

            $('#cmbitemCategory2').html(data);

        }

    });

}


function getItemCategory3() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getItemCategory3",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.Item_category_level_3_id + "'>" + value.category_level_3 + "<input type='checkbox'></option>";


            })

            $('#cmbitemCategory3').html(data);

        }

    });

}

function getsuplygroup() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getsuplygroup",

        success: function (data) {


            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.supply_group_id + "'>" + value.supply_group + "<input type='checkbox'></option>";


            })

            $('#cmbsuplygroup').html(data);

        }

    });

}

function getlocation(branch_ids) {
   // console.log(branch_ids);
    $.ajax({
        type: "get",
        dataType: 'json',
        data:{
            branch_ids : branch_ids
        },
        url: "/sc/getlocationForBranch",

        success: function (data) {

            console.log(data);
            $.each(data, function (key, value) {

                data = data + "<option id='' value='" + value.location_id + "'>" + value.location_name + "<input type='checkbox'></option>";


            })

            $('#cmblocation').html(data);

        }

    });

}





function getselect(id) {
    alert(id)
}


function getviewReport() {

    /*
    var selectecategory1;
    var selectecategory2;
    var selectecategory3;
    var selectSupplygroup;
    
    */


    console.log(selected);
    console.log(selected1);
    console.log(selected2);
    console.log(selected3);
    console.log(selected4);
    console.log(selected5);
    console.log(selectedproduct);
    console.log(fromdate);
    console.log(todate);

    console.log(selectecategory1);
    console.log(selectecategory2);
    console.log(selectecategory3);
    console.log(selectSupplygroup);

    var requestData = {
        selected: selected,
        selected2: selected2,
        selected3: selected3,
        selected4: selected4,
        selected5: selected5,
        selectedproduct: selectedproduct,
        selectecategory1: selectecategory1,
        selectecategory2: selectecategory2,
        selectecategory3: selectecategory3,
        fromdate: fromdate,
        todate: todate,
        selecteBranch: selecteBranch
    };


    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/sc/printItemMovementHistoryReport',
        data: requestData,
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
        },


        error: function (error) {

        },
        complete: function () {

        }

    });

}


function getproductdata() {

    if (length > 1) {
        console.log("kk", selected);
        console.log(checkboxId);

        if (checkboxId === 'chkProduct') {
            $('#cmbselect').val("1");
            selected = 1;
        }


    } else {

    }
}

function getcurrontDate() {
    if (length > 1) {

        if (checkboxId === 'chkdate') {
            $('#cmbselect').val("1");
            selected = 1;
        }


    } else {

    }
}

function itemCategory1() {
    if (length > 1) {

        if (checkboxId === 'chkitemCategory1') {
            $('#cmbselect1').val("1");
            selected1 = 1;
        }


    } else {

    }
}
function dataclear() {

    $('input[type="checkbox"]').prop('checked', false);
    selectedproduct = null;
    selectecategory1 = null;
    selectecategory2 = null;
    selectecategory3 = null;
    selectSupplygroup = null;
    fromdate = null;
    todate = null;
    selecteBranch = null;
    selecteLocation = null;
}