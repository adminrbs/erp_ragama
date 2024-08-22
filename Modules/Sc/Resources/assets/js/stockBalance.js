var itemHistoryData = []
/* $(document).ready(function () {
    //stockBalanceReport();
    $('#btnPrint').on('click', function () {

    });
}); */

//stock balance report
function stockBalanceReport() {

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "/sc/stockBalanceReport");
    xhr.send();
    xhr.responseType = "json";
    xhr.onload = () => {
        if (xhr.readyState == 4 && xhr.status == 200) {
            const data = xhr.response;
            console.log(data);
            //stockBalanceReportHeader(data.data, 'PRINT');
        } else {
            console.log(`Error: ${xhr.status}`);
        }
    };

    /*$.ajax({
        url: '/sc/stockBalanceReport',
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var dt = data.data
            
            /* for (var i = 0; i < dt.length; i++) {
                itemHistoryData.push({
                    
                    "item_history_id": dt[i].item_history_id,
                    "item_id": dt[i].item_id,
                    "item_name": dt[i].item_Name,
                    "quantity": dt[i].quantity,
                    "unit_of_measure": dt[i].unit_of_measure,
                    "document_no": dt[i].document_number,

                });

            } 

            reportHeader(dt, 'PRINT');
        }
    })*/
}


/*
function stockBalanceReportHeader(dt, flag) {


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
                                        [{ text: 'Stock Balance Report', fontSize: 16, bold: true, alignment: 'center', border: [false, false, false, false] },],
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
        {
            table: {
                widths: [100, '*', '*', '*'],
                headerRows: 1,
                body: reportPurchaseRequestItemBody(dt),
            },
            margin: [0, 0],
        },
         //{ canvas: [{ type: 'line', x1: 0, y1: 0, x2: 600, y2: 0, lineWidth: 1 }] },

    

        //remarks
       /*  {
            table: {
                widths: [100, '*'],
                headerRows: 1,
                body: [
                    [
                        {
                            text: 'Remarks:-',
                            fontSize: 12,
                            bold: true,
                            alignment: 'center',
                            margin: [0, 0, 0, 15],
                            border: [false, false, false, false]
                        },

                    ],


                ]
            },

            margin: [0, 10],
        }, */
       
       /* {
        },

        { text: '', margin: [5, 2, 10, 20] },
     /*    {
             table: {
                widths: [100, 100, 100, 100, '*'],
                headerRows: 1,

                body: [
                    [
                        { text: 'Prepared By', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: 'Checked By', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: 'Received By', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: 'Authorized By', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                    ],
                    [
                        { text: purchase_request[0].prepared_by_name, fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '..............................', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '...............................', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: purchase_request[0].approved_by_name, fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false] },
                    ],


                ],
            }, 


        }, */
 

   /* ];

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


function reportPurchaseRequestItemBody(data) {
    var font_size = 13;
    var body = [];
    body.push([

        
        { text: 'Item ID', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Item Name', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'QTY ', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'U.O.M', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    ]);

    for (i = 0; i < data.length; i++) {

        

        var quantity = parseFloat(data[i].quantity);
        var newquantity = quantity.toLocaleString();

        body.push([
            { text: data[i].Item_code, fontSize: font_size, alignment: 'center', border: [true, false, true, true] },
            { text: data[i].item_Name, fontSize: font_size, alignment: 'center', border: [true, false, true, true] },
            { text: newquantity, fontSize: font_size, alignment: 'center', border: [true, false, true, true] },
            { text: data[i].unit_of_measure, fontSize: font_size, alignment: 'center', border: [true, false, true, true] },
           

        ]);

    }

    return body;
}
*/

