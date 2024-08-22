
function printoutStandinReport() {

    /*$.ajax({
        url: '/sc/printoutstandinReport',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var dt = data.data



            reportHeader(dt, 'PRINT');
        }
    })*/


    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/sc/printoutstandinReport");
    xhr.send();
    xhr.responseType = "json";
    xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const data = xhr.response;
            console.log(data);
            //reportHeader(data.data, 'PRINT');
        } else {
            console.log(`Error: ${xhr.status}`);
        }
    };




}
/*
function reportHeader(sales_order_item, flag, dt) {

    

    var allsum = 0;
    for (var i = 0; i < sales_order_item.length; i++) {
        
        var balance_Amount = parseFloat(sales_order_item[i].balance_Amount);
        if (!isNaN(balance_Amount)) {
            allsum += balance_Amount;
        }
    }

   
    var sum = allsum.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    //console.log("Sum of all values:", sum);



    const currentDate = new Date();

    const formattedDate = currentDate.toLocaleDateString();
    const formattedTime = currentDate.toLocaleTimeString();



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

                            [{
                                table: {
                                    widths: ['*'],
                                    headerRows: 1,
                                    body: [
                                        [{ text: 'Customer Outstanding Report ', fontSize: 16, bold: true, alignment: 'center', border: [false, false, false, false] },],
                                        // [{ text: 'No. 61 D, Peiris Avenue, Kalubowila  ', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        // [{ text: 'TelNo :', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Date    :"2" ', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        // [{ text: 'Supplier  Name :', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'GRN No  : ' + sales_order[0].sales_order_Id  , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        //[{ text: 'Address :', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Sup Inv No : ' + sales_order[0].external_number, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        // [{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Expected Date : ' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],

                                    ],




                                }, border: [false, false, false, false]
                            }],
                        ],
                    },
                    margin: [0, 10],
                },

            ]
        }
    ];

    var Body = [
        /* {
             table: {
                 widths: [100, '*', '*', '*','*', '*', '*', '*', '*'],
                 headerRows: 1,

                 body: reportBody(sales_order),

             },

             margin: [0, 20],
         },*/

        //grn_item table

       /* {
            table: {
                widths: ['*', 100, '*', 80, '*', '*', 30],
                headerRows: 1,
                body: reportitemBody(sales_order_item),
            },
            margin: [0, 0]
        },
        //{ canvas: [{ type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 1 }] },




        {
            table: {
                widths: ['*', '*'],
                headerRows: 0,


                body: [
                    [
                        //{ text: 'Total No of Items :', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, true], margin: [0, 0, 0, 0], colSpan: 1 },
                        //{ text: +sales_order_item.length, fontSize: 9, bold: false, alignment: 'center', border: [false, true, false, true], margin: [0, 0, 0, 0] },

                        { text: 'TOTAL Amount      :', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, true], margin: [0, 0, 0, 0], colSpan: 0 },
                        { text: sum, fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, true], margin: [0, 0, 38, 0], colSpan: 0 },


                    ],



                ],
            }, margin: [0, 0]
        },





    ];

    var Footer = [

        {


            color: 'red',
            fontSize: 8,
            alignment: 'center',
            margin: [0, 0]

        }

    ];




    var page = new Page();
    page.setPageSize('A4');
    page.setPageOrientation('portrait');
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


function reportitemBody(data) {



    var font_size = 8;
    var body = [];
    body.push([{ text: 'Customer Code', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Customer Name', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Invoice Date', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Invoice Number', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Invoiced Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Balance Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Age', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },

    ]);


    for (i = 0; i < data.length; i++) {
        var transDate = new Date(data[i].trans_date); // Convert the trans_date string to a Date object
        var currentDate = new Date();
        var timeDiff = currentDate - transDate;
        var age = Math.floor(timeDiff / (1000 * 60 * 60 * 24));

        var balance_Amount = parseFloat(data[i].balance_Amount);
        var Balance = balance_Amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        var amount = parseFloat(data[i].amount);
        var newAmount = amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });


        body.push([
            { text: data[i].customer_code, fontSize: font_size, alignment: 'center', border: [false, false, false, true] },
            { text: data[i].customer_name, fontSize: font_size, alignment: 'center', border: [false, false, false, true] },
            { text: data[i].trans_date, fontSize: font_size, alignment: 'center', border: [false, false, false, true] },
            { text: data[i].external_number, fontSize: font_size, alignment: 'center', border: [false, false, false, true] },
            { text: newAmount, fontSize: font_size, alignment: 'right', border: [false, false, false, true] },
            { text: Balance, fontSize: font_size, alignment: 'right', border: [false, false, false, true] },
            { text: age.toString(), fontSize: font_size, alignment: 'center', border: [false, false, false, true] },

        ]);
    }

    return body;
}
*/