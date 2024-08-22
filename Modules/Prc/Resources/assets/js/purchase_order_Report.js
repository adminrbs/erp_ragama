



function generatePOreport(id) {



    /* const xhr = new XMLHttpRequest();
     xhr.open("GET", "/sd/printsalesinvoicePdf");
     xhr.send();
     xhr.responseType = "json";
     xhr.onload = () => {
         if (xhr.readyState == 4 && xhr.status == 200) {
             const data = xhr.response;
             console.log(data);
             reportHeader(data.data, 'PRINT');
         } else {
             console.log(`Error: ${xhr.status}`);
         }
     };
 */

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    //const { grosstotal } = require("lodash");

    /*
     Created on : Feb 02, 2020, 12:10:33 AM
     Author     : Sampath Perera
     */



    $.ajax({
        url: '/prc/printpurchaseOrderReportPdf/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var dt = data.data
            var page_body = [];



            var purchaseorder = dt.purchaseorder;
            var purchaseorder_item = dt.purchaseorder_item;

            reportHeader(purchaseorder, purchaseorder_item, dt, 'PRINT');
        }
    })




}


function reportHeader(purchaseorder, purchaseorder_item, dt, flag) {
    //console.log(dt.company_name);
    var wsp = 0;
    var qty = 0;
    var sub_Total = 0;

    var total_Amount=0;

    var Dis_Total = 0;
    var disprecentage = 0;
    for (var i = 0; i < purchaseorder_item.length; i++) {
        var wsp = parseFloat(purchaseorder_item[i].price);
        var qty = parseFloat(purchaseorder_item[i].quantity);

        var disprecentage = parseFloat(purchaseorder_item[i].discount_percentage);

        sub_Total += wsp * qty;
        Dis_Total += ((wsp * qty)*disprecentage)/100;

        total_Amount += (wsp * qty) - ((wsp * qty)*disprecentage)/100
    }
    var subTotal = sub_Total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    var DisTotal = Dis_Total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    var formattotalAmount = total_Amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
   
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
                        //widths: ['*','*','*','*','*','*','*','*','*',],
                        // headerRows: 1,
                        body: [

                            [{
                                table: {
                                    widths: ['*',280, '*'],
                                    headerRows: 1,

                                    body: [
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            { text: dt.company_name, fontSize: 15, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            //{ text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },

                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: dt.company_address + '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            { text: dt.contact_details, fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            //{ text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },

                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: 'Purchase Order', fontSize: 11, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        [
                                            { text: 'Reference No :' + purchaseorder[0].external_number, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: 'Date :' + (purchaseorder[0].purchase_order_date_time || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: 'Branch :' + ( purchaseorder[0].branch_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        [
                                            { text: 'Manual No :' + ( purchaseorder[0].internal_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: 'Currency :'+ 'LKR 1.0000' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: 'PO User :'+(purchaseorder[0].userName || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        [
                                            { text: 'PO No :'+ (purchaseorder[0].external_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: 'App. User :'+ 'THARAKA', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        [
                                            { text: 'Supplier :' + (purchaseorder[0].supplier_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: 'Add. User :'+ '6/6/2023', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        [
                                            { text: 'Address:' + (purchaseorder[0].primary_address || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: 'App. User :'+ 'Tharaka', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

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
                widths: ['*', 110, '*', '*', '*', 40, '*', '*'],
                headerRows: 1,
                body: reportitemBody(purchaseorder_item),
               
               
            },
            
            margin: [0, 0, 0, 0]
        },
        //{ canvas: [{ type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 1 }] },


        {
            table: {
                widths: [200, '*', '*', '*', '*'],
                headerRows: 0,


                body: [

                    [
                        ///{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: 'Sub Total', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: subTotal, fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],
                    [
                        // { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: 'Total Discount', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: DisTotal, fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],
                    [
                        //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],

                    [
                        //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: 'Total Amount', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: formattotalAmount, fontSize: 8, bold: false, alignment: 'right', border: [false, true, false, false] },
                    ],
                    [
                        //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: true, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: true, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, false] },
                    ],
                    [
                        //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: 'Remarks :' + (purchaseorder[0].remarks || ''), fontSize: 8, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, false] },
                    ],



                ],
            }, margin: [0, 10, 0, 0]
        },




        { text: '', margin: [5, 60, 10, 20] },
        {
            table: {
                widths: [90, "*", 90,],
                headerRows: 1,

                body: [
                    [
                        { text: '--------------------------', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '---------------------------------', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '--------------------------------', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        //{ text: '', fontSize: 0, bold: true, alignment: 'center', border: [false, false, false, false] },
                        //{ text: '..................................', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                    ],
                    [
                        { text: 'Prepared By', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: 'Checked By', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: 'Authorized By', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        //{ text: '30 days Credit Only.', fontSize: 20, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        //{ text: 'Authorized By', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                    ],

                ],
            },


        },

        /* {
             table: {
                 widths: [230, '*'],
                 headerRows: 0,
 
 
                 body: [
                     [
                         //{ text: 'Total No of Items :', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, true], margin: [0, 0, 0, 0], colSpan: 1 },
                         //{ text: +purchaseorder_item.length, fontSize: 9, bold: false, alignment: 'center', border: [false, true, false, true], margin: [0, 0, 0, 0] },
 
                         { text: formattedDate, fontSize: 9, bold: true, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0], colSpan: 1 },
                         { text: formattedTime, fontSize: 9, bold: true, alignment: 'left', border: [false, false, false, false], margin: [10, 0, 0, 0] },
 
 
                     ],
 
 
 
                 ],
             }, margin: [0, 30]
         },*/

    ];

    var Footer = [

         
        {
            style: 'tableExample',
            table: {
                widths: ['*', '*', '*', 80, 36],

                body: [

                    [
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        //{ text: 'Sub Total', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                    ],
                    
                    

                ]
            },
            layout: 'noBorders', 
            alignment: 'right',
            margin:[0,-10,0,0]
        }
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

    
    
    var font_size = 8;
    var body = [];
    body.push([{ text: 'Item Code', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Item Name', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Pack Size', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Quantity', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Free Qty', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Pur.Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Dis %', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        //{ text: 'WS price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        // { text: 'Retial Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        //{ text: 'Dis.value', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        //{ text: '', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },

    ]);

    var customBorderStyle = {
        vLineColor: 'dark' // Set the border color to 'dark' for the vertical line (right side)
      };
    for (i = 0; i < data.length; i++) {
        var quantity = parseFloat(data[i].quantity);
        var price = parseFloat(data[i].price);
        var free_quantity = parseFloat(data[i].free_quantity);


        if (!isNaN(price) && !isNaN(quantity)) {
            var amount = quantity * price;
           
            var formattedAmount = amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
           
            var formattedPrice = price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            var formattedquantity = quantity.toLocaleString();
            var formattedfree_quantity = free_quantity.toLocaleString();

            body.push([
                { text: data[i].Item_code, fontSize: font_size, alignment: 'left', border: [false, false, false, false]},
                { text: data[i].item_name, fontSize: font_size, alignment: 'left', border: [false, false, false, false]},
                { text: data[i].unit_of_measure, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
                { text: formattedquantity, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },

                { text: formattedfree_quantity, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                { text: formattedPrice, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                { text: data[i].discount_percentage, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                { text: formattedAmount, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                //{ text: data[i].Item_code, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                //{ text: parseFloat(data[i].value).toFixed(2), fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                //{ text: data[i].price, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                //{ text: data[i].Item_code, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },

            ]);
            
        }


    }
    

    return body;
}
