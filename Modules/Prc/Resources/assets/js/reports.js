var report = undefined;
var selectSupplygroup = null
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
       /*  if (report == "poHelpReport") {


            $.ajax({
                url: '/po_export_excell/' + JSON.stringify(filters),
                type: 'GET',
               
                success: function (data) {
                    downloadFile(data.url);
                  // alert("success");
                }
            });



        } */
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

    function downloadFile(url) {
        var link = document.createElement('a');
        link.href = url;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }



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

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

            selecteBranch = null
            selected6 = null
        }

    })

    $('#chkSupplyGroup').on('change', function () {

        if (this.checked) {
            selectSupplygroup = $('#cmbSupplyGroup').val();
            $('#cmbSupplyGroup').change(function () {

                //$('#chkBranch').prop('checked', false);
                selectSupplygroup = $('#cmbSupplyGroup').val();

            })


            // getproductdata()

            //getselectproduct(selecteCustomer);
        } else {

            /* $('#cmbselect1').val("0");
             selected1 = 0;*/

             selectSupplygroup = null
             selectSupplygroup = null
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
            filters.fromDate = $('#txtFromDate').val();
        }
        if (filters.toDate) {
            filters.toDate = $('#txtToDate').val();
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

       
        console.log(filters);
        $('#row1').hide();
        if (report == "poHelpReport") {


           /*  $.ajax({
                url: '/prc/poHelpReport/' + JSON.stringify(filters),
                type: 'GET',
                dataType: 'json',
                success: function (data) {

                    showPoHelpReport(data, 'PRINT');
                }
            }); */

            var requestData = [

               
                { fromdate: fromdate },
                { todate: todate },
                { selecteBranch: selecteBranch },
                //{ selectedproduct: selectedproduct },
                { selectSupplygroup: selectSupplygroup },

            ];
            $('#pdfContainer').attr('src', '/prc/poHelpReportNew/' + JSON.stringify(requestData));
            $('#pdfContainer').show();
            $('#crdReportSearch').hide();
            //$('#report_div').hide();



        }else if(report == "good_receive_summery_report"){

            var requestData = [
                
               
                { fromDate: $('#txtFromDate').val()},
                { toDate:  $('#txtToDate').val()},
                { supplygroup:$('#cmbSupplyGroup').val()},
                { branch: $('#cmbBranch').val()},
               

            ];
            console.log(requestData);


            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            if($('input[type=checkbox]:checked').length === 0){
                showWarningMessage('Please select a filter')
            }else{
                
                $('#pdfContainer').attr('src', '/prc/good_receive_summery_report/' + JSON.stringify(requestData));
                $('#crdReportSearch').hide();
                    $('#pdfContainer').show();
                    PRINT_STATUS = true;
                    $('#pdfContainer').show();
                    
                    
            }
           

        }else if(report == "goods_return_summery_report"){
            
           
            var requestData = [
                
               
                { fromDate: $('#txtFromDate').val()},
                { toDate:  $('#txtToDate').val()},
                { supplygroup:$('#cmbSupplyGroup').val()},
                { branch: $('#cmbBranch').val()},
               

            ];
            console.log(requestData);


            //const jsonArray = JSON.parse(decodeURIComponent(requestData));

            //getviewReport()
            if($('input[type=checkbox]:checked').length === 0){
                showWarningMessage('Please select a filter')
            }else{
                
                $('#pdfContainer').attr('src', '/prc/goods_return_summery_report/' + JSON.stringify(requestData));
                $('#crdReportSearch').hide();
                    $('#pdfContainer').show();
                    PRINT_STATUS = true;
                    $('#pdfContainer').show();
            }
           

        }



       





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

    /* $('#chkSupplyGroup').on('change', function () {

        if (this.checked) {
            supplygroup = $('#cmbSupplyGroup').val();
            $('#cmbSupplyGroup').change(function () {

                supplygroup = $('#cmbSupplyGroup').val();

            });



        } else {



            supplygroup = null

        }

    }); */

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

    /* var selectedTextArray = $("#cmbSupplyGroup option:selected").map(function () {
        return $(this).text();
    }).get();

    // Display selected text
    var groups = selectedTextArray.join("/ "); */
    var company_data = result.company_data;
    var Title = [
        { text: '' }
    ];

    var Header = [
        {
            content: [

                {
                    table: {
                        widths: ['*'],
                        headerRows: 1,
                        body: [
                            [{ text: company_data.company_name, fontSize: 14, bold: true, alignment: 'center', border: [false, false, false, false] }],
                            [{ text: 'PO Help Report ' + result.report_date, fontSize: 10, bold: true, alignment: 'center', border: [false, false, false, false] }],
                            [{ text: '' + '', fontSize: 10, bold: true, alignment: 'center', border: [false, false, false, false] }],

                        ],

                    },
                    margin: [0, 0],
                }

            ]
        }
    ];

    var font_size = 8;
    var col_width = 30;
    var Body = [
        {
            table: {
                widths: [75, 15,50, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width, col_width],
                headerRows: 2,

                body: showPoHelpReportBody(result.data),
            },


        },



    ];

    var Footer = [

        {
            style: 'tableExample',
            table: {
                widths: ['*'],

                body: [

                    [
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                    ],



                ]
            },
            layout: 'noBorders',
            alignment: 'right',
            margin: [0, 20, 0, 0]
        }

    ];



    var page = new Page();
    page.setPageSize('A4');
    page.setPageOrientation('landscape');
    page.setPageMargin([10, 10, 10, 10]);
    page.setTitle(Title);
    page.setHeader(Header, Page.EVERY);
    page.setBody(Body);
    page.setFooter(Footer);

    if (flag == 'EXPORT') {
        page.export();
    } else if (flag == 'PRINT') {
        page.preview();
    }
}



function showPoHelpReportBody(result,company_data) {
    var font_size = 8;
    var body = [];
    body.push([
        { text: '', bold: true, alignment: 'left', border: [false, false, false, false] },
        { text: '', bold: true, alignment: 'left', border: [false, false, false, false] },
        { text: '', bold: true, alignment: 'left', border: [false, false, false, false] },

        {
            colSpan: 2,
            text: 'NEGOMBO',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },




        {
            colSpan: 2,
            text: 'GAMPAHA',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },





        {
            colSpan: 2,
            text: 'COLOMBO',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },


        {
            colSpan: 2,
            text: 'KANDY',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },



        {
            colSpan: 2,
            text: 'KURUNAGALA',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },



        {
            colSpan: 2,
            text: 'GALLE',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },




        {
            colSpan: 2,
            text: 'ANURADAPURA',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },





        {
            colSpan: 2,
            text: 'Total',
            fontSize: font_size,
            alignment: 'center',
            border: [true, true, true, true],
        },
        {
            text: '',
            fontSize: font_size,
            border: [true, true, true, true],
        },



    ],




        [
            {
                text: '',
                fontSize: font_size,
                border: [false, false, false, false],
            },
            {
                text: '',
                fontSize: font_size,
                border: [false, false, false, false],
            },
            {
                text: '',
                fontSize: font_size,
                border: [false, false, false, false],
            },
            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },



            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },



            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },



            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },


            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },


            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },


            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },


            {
                text: 'RDQTY',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },
            {
                text: 'QOH',
                fontSize: font_size,
                alignment: 'right',
                border: [true, true, true, true],
            },

        ]);

    var rd_qty_branch_1 = 0;
    var qoh_qty_branch_1 = 0;
    var rd_qty_branch_2 = 0;
    var qoh_qty_branch_2 = 0;
    var rd_qty_branch_3 = 0;
    var qoh_qty_branch_3 = 0;
    var rd_qty_branch_4 = 0;
    var qoh_qty_branch_4 = 0;
    var rd_qty_branch_5 = 0;
    var qoh_qty_branch_5 = 0;
    var rd_qty_branch_6 = 0;
    var qoh_qty_branch_6 = 0;
    var rd_qty_branch_7 = 0;
    var qoh_qty_branch_7 = 0;
    var total_rd_qty = 0;
    var total_qoh_qty = 0;
    for (i = 0; i < result.length; i++) {
        rd_qty_branch_1 += parseFloat(result[i].rd_qty_branch_1);
        qoh_qty_branch_1 += parseFloat(result[i].qoh_qty_branch_1);
        rd_qty_branch_2 += parseFloat(result[i].rd_qty_branch_2);
        qoh_qty_branch_2 += parseFloat(result[i].qoh_qty_branch_2);
        rd_qty_branch_3 += parseFloat(result[i].rd_qty_branch_3);
        qoh_qty_branch_3 += parseFloat(result[i].qoh_qty_branch_3);
        rd_qty_branch_4 += parseFloat(result[i].rd_qty_branch_4);
        qoh_qty_branch_4 += parseFloat(result[i].qoh_qty_branch_4);
        rd_qty_branch_5 += parseFloat(result[i].rd_qty_branch_5);
        qoh_qty_branch_5 += parseFloat(result[i].qoh_qty_branch_5);
        rd_qty_branch_6 += parseFloat(result[i].rd_qty_branch_6);
        qoh_qty_branch_6 += parseFloat(result[i].qoh_qty_branch_6);
        rd_qty_branch_7 += parseFloat(result[i].rd_qty_branch_7);
        qoh_qty_branch_7 += parseFloat(result[i].qoh_qty_branch_7);


        var total_rd_qty = (parseFloat(result[i].rd_qty_branch_1) + parseFloat(result[i].rd_qty_branch_2) + parseFloat(result[i].rd_qty_branch_3));
        var total_qoh_qty = (parseFloat(result[i].qoh_qty_branch_1) + parseFloat(result[i].qoh_qty_branch_2) + parseFloat(result[i].qoh_qty_branch_3));
        body.push([
            { text: result[i].item_Name, fontSize: font_size, bold: false, alignment: 'left', border: [true, true, true, true] },
            { text: result[i].package_unit, fontSize: font_size, bold: false, alignment: 'left', border: [true, true, true, true] },
            { text: result[i].supply_group, fontSize: font_size, bold: false, alignment: 'left', border: [true, true, true, true] },
            { text: parseFloat(result[i].rd_qty_branch_1).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].qoh_qty_branch_1).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].rd_qty_branch_2).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].qoh_qty_branch_2).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].rd_qty_branch_3).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].qoh_qty_branch_3).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].rd_qty_branch_4).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].qoh_qty_branch_4).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },

            { text: parseFloat(result[i].rd_qty_branch_5).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].qoh_qty_branch_5).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },

            { text: parseFloat(result[i].rd_qty_branch_6).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].qoh_qty_branch_6).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },

            { text: parseFloat(result[i].rd_qty_branch_7).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(result[i].qoh_qty_branch_7).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },

            { text: parseFloat(total_rd_qty).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] },
            { text: parseFloat(total_qoh_qty).toFixed(0), fontSize: font_size, bold: false, alignment: 'right', border: [true, true, true, true] }
        ]);
    }
    total_rd_qty = (rd_qty_branch_1 + rd_qty_branch_2 + rd_qty_branch_3 + rd_qty_branch_4 + rd_qty_branch_5 + rd_qty_branch_6 + rd_qty_branch_7);
    total_qoh_qty = (qoh_qty_branch_1 + qoh_qty_branch_2 + qoh_qty_branch_3 + qoh_qty_branch_4 + qoh_qty_branch_5 + qoh_qty_branch_6 + qoh_qty_branch_7);

    /* body.push([
        { text: 'Total ', fontSize: font_size, bold: true, alignment: 'left', border: [true, true, false, true] },
        { text: '', fontSize: font_size, bold: false, alignment: 'left', border: [false, true, true, true] },
        { text: parseFloat(rd_qty_branch_1).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(qoh_qty_branch_1).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(rd_qty_branch_2).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(qoh_qty_branch_2).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(rd_qty_branch_3).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(qoh_qty_branch_3).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(rd_qty_branch_4).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(qoh_qty_branch_4).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },

        { text: parseFloat(rd_qty_branch_5).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(qoh_qty_branch_5).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },

        { text: parseFloat(rd_qty_branch_6).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(qoh_qty_branch_6).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },

        { text: parseFloat(rd_qty_branch_7).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(qoh_qty_branch_7).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },

        { text: parseFloat(total_rd_qty).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] },
        { text: parseFloat(total_qoh_qty).toFixed(0), fontSize: font_size, bold: true, alignment: 'right', border: [true, true, true, true] }
    ]); */

    return body;
}













