/* const { isNil, isNull } = require("lodash"); */




function paymentVoucher_Receipt(id) {



    $.ajax({
        url: '/cb/paymentVoucher_Receipt/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {




            var header = data.header;
            var items = data.item;

            reportHeader(header, items, data, 'PRINT');
        }
    })




}







function reportHeader(header, items, data, flag) {

    var wsp = 0;
    var qty = 0;
    var total = 0;
    for (var i = 0; i < items.length; i++) {
        var amount = parseFloat(items[i].amount);
        total += amount;
    }


    var totalAmount = total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });



    var Title = [
        { text: '' }
    ];

    var Header = [
        {
            content: [

                {
                    table: {
                       
                        body: [

                            [{
                                table: {
                                    widths: [130, 300, 94],
                                    headerRows: 1,

                                    body: [

                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: data.company, fontSize: 11, bold: true, alignment: 'center', border: [false, false, false, false], margin: [10, 10, 0, -12] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                                        ],

                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: data.adderess, fontSize: 10, bold: false, alignment: 'center', border: [false, false, false, false], margin: [10, 10, 0, -12] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                                        ],

                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: data.phoneNumber, fontSize: 10, bold: false, alignment: 'center', border: [false, false, false, false], margin: [10, 10, 0, -12] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: 'PAYMENT VOUCHER', fontSize: 10, bold: true, alignment: 'center', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                                        ],




                                        [
                                            { text: 'Branch', fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 10, 0, 0] },
                                            { text: ': ' + (header[0].branch_name || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 10, 0, 0] },
                                            { text: '' + (header[0].external_number || 'Voucher Number'), fontSize: 10, bold: false, alignment: 'center', border: [true, true, true, true], margin: [0, 0, 0, 0] },
                                        ],

                                        [
                                            { text: 'Payee', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: ': ' + (header[0].payee_name || header[0].supplier_name), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: 'Date', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: ': ' + (header[0].transaction_date || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                        ],


                                        [
                                            { text: 'Payment Type', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: ': ' + (header[0].customer_payment_method || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],


                                    ],





                                }, border: [true, true, true, false]
                            }],
                        ],
                    },
                    margin: [-30, -10],



                },

            ],


        }




    ];

    var Body = [


        {
            table: {
                widths: [100, '*', 100],
                headerRows: 1,
                body: reportitemBody(items),
            },
            margin: [-30, 5, 0, 0]
        },
        {
            table: {
                widths: [220, '*', '*'],

                body: [

                    [
                        { text: 'Total', fontSize: 9, bold: true, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 10, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: totalAmount, fontSize: 9, bold: true, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                    ],
                    [

                        { text: '__________________', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 20, 0, 0] },
                        { text: '__________________', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 20, 0, 0] },
                        { text: '__________________', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 20, 0, 0] },

                    ],
                    [

                        { text: 'Prepared By', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                        { text: 'Payment By', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                        { text: 'Approved By', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [85, 10, 0, 0] },

                    ],




                ]
            },
            margin: [0, 15, 0, 0]
        }





    ];


    var Footer = [


    ];


    var page = new Page();
    page.setPageSize('letter');
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
    var font_size = 9;
    var body = [];
    body.push([
        { text: 'Account No', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Description', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },





    ]);

    for (i = 0; i < data.length; i++) {
       
      
        body.push([

            { text: data[i].account_code, fontSize: font_size, alignment: 'left', border: [true, true, true, true] },
            { text: data[i].description, fontSize: font_size, alignment: 'left', border: [true, true, true, true] },
            { text: parseFloat(data[i].amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }), fontSize: font_size, alignment: 'right', border: [true, true, true, true] },

        ]);


    }

    return body;
}
