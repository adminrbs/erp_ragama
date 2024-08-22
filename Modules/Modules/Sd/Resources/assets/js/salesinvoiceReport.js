/* const { isNil, isNull } = require("lodash"); */




function salesinvoiceReport(id) {


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
        url: '/sd/printsalesinvoicePdf/' + id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var dt = data.data
            var page_body = [];



            var sales_invoice = dt.salesInvoiceRequests;
            var sales_invoice_item = dt.salesInvoiceReqestItems;



            reportHeader(sales_invoice, sales_invoice_item, dt, 'PRINT');
        }
    })




}







function reportHeader(sales_invoice, sales_invoice_item, dt, flag) {

    var wsp = 0;
    var qty = 0;
    var subTotal = 0;
    for (var i = 0; i < sales_invoice_item.length; i++) {
        var wsp = parseFloat(sales_invoice_item[i].whole_sale_price);
        var qty = parseFloat(sales_invoice_item[i].quantity);

        subTotal += wsp * qty;

        //totalAmount += subTotal

    }

    var disAmount = 0;
    var freeqty = 0;
    var Dis_Total = 0;
    for (var i = 0; i < sales_invoice_item.length; i++) {
        var disAmount = parseFloat(sales_invoice_item[i].discount_amount);
        var freeqty = parseFloat(sales_invoice_item[i].free_quantity);

        Dis_Total += disAmount;

    }

    var DisTotal = Dis_Total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    var total_Amount = 0;
    for (var i = 0; i < sales_invoice_item.length; i++) {
        var price = Math.abs((sales_invoice_item[i].price));
        var qty = Math.abs((sales_invoice_item[i].quantity));
        console.log(price);
        console.log(qty);

        total_Amount += price * qty; // Calculate and accumulate totalAmount

    }
    var totalAmount =total_Amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });


    var netTotal = total_Amount - Dis_Total;
    var formattotalAmount = netTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });






    /* var grosstotal = 0;
     var length = sales_invoice_item.length;*/

    /* for (var i = 0; i < sales_invoice_item.length; i++) {
       var value = parseFloat(sales_invoice_item[i].value);
       if (!isNaN(value)) {
         grosstotal += value;
       }
     }*/




    /* var formatter = new Intl.NumberFormat(); // Create a number formatter
     var formattedTotal = formatter.format(grosstotal.toFixed(2)); // Set 2 decimal places for grosstotal
     
     var headerDiscountAmount = 0;
     var tableDiscountAmount = 0;
     var netValue = 0;
     for (var i = 0; i < sales_invoice.length; i++) {
         var headerDisa = parseFloat(sales_invoice[i].discount_amount);
         if (!isNaN(value)) {
             headerDiscountAmount += headerDisa;
         }
     }*/

    /* for (var i = 0; i < sales_invoice_item.length; i++) {
         var tableDisa = parseFloat(sales_invoice_item[i].price);
         if (!isNaN(value)) {
             tableDiscountAmount += tableDisa;
         }
     }
     var totaldisAmount = tableDiscountAmount + headerDiscountAmount;
     
     netValue = (grosstotal - totaldisAmount).toFixed(2); // Set 2 decimal places for netValue
     
     var formatter = new Intl.NumberFormat(); // Create a number formatter
     var NewnetValue = formatter.format(netValue);
     
     console.log("Sum of all values:", formattedTotal);
     console.log("Net value:", NewnetValue);
     
 
 
     console.log("Sum of all values:", grosstotal);*/


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
                                    widths: [290, 80,90],
                                    headerRows: 1,

                                    body: [

                                        [
                                            { text: '' + ( sales_invoice[0] .customer_name || ''), fontSize: 11, bold: true, alignment: 'left', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: 'Add. User :'+ '6/6/2023', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '' + sales_invoice[0].order_date_time, fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        [
                                            { text: '' +( sales_invoice[0].primary_address || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            // { text: 'App. User :'+ 'Tharaka', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '' + (sales_invoice[0].branch_name || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        [
                                            { text: '' + (sales_invoice[0].route_name || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            // { text: 'App. User :'+ 'Tharaka', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '' + (sales_invoice[0].primary_fixed_number || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: '' + (sales_invoice[0].townName || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            // { text: 'App. User :'+ 'Tharaka', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '' + (sales_invoice[0].external_number || ''), fontSize: 11, bold: true, alignment: 'left', border: [false, false, false, false] },
                                        ],

                                        /*   [
                                              { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                              { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                              { text: 'Tax Reg. No:'+'11111111-500', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                              { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                          ], */


                                        [
                                            { text: '' +( sales_invoice[0].your_reference_number || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            /*  { text: 'Currency :'+ 'LKR 1.0000' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] }, */
                                            // { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                        ],

                                        [
                                            { text: '' +( sales_invoice[0].id || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 10, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            /* { text: 'App. User :'+ 'THARAKA', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] }, */
                                            { text: '' + (sales_invoice[0].employee_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            /*  { text: 'Currency :'+ 'LKR 1.0000' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] }, */
                                            // { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                        ],


                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            // { text: 'App. Date :'+ '06/06/2023', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [10, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],


                                    ],





                                }, border: [false, false, false, false]
                            }],
                        ],
                    },
                    margin: [50, 38],



                },

            ],
            

        }




    ];

    var Body = [


        {
            table: {
                widths: [250, 50, 30, 35, 40, 25, 60],
                headerRows: 1,
                body: reportitemBody(sales_invoice_item),
            },
            margin: [-30, -35, 0, 0]
        },
        {
            table: {
                widths: [220, '*', '*', '*', '*'],

                body: [

                    [
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        //{ text: 'Sub Total', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: totalAmount, fontSize: 10, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                    ],
                    [
                        // { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: 'Discount', fontSize: 10, bold: false, alignment: 'right', border: [false, false, false, false], margin: [50, 0, 0, 0] },
                        { text: DisTotal, fontSize: 10, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 8, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                    ],
                    [
                        //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                    ],

                    [
                        //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: formattotalAmount, fontSize: 10, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                    ],
                    

                ]
            },
            margin:[0,15,0,0]
        }


        


    ];


    var Footer = [
       
       /*  {
            style: 'tableExample',
            
            layout: 'noBorders', 
            alignment: 'right',
           
        } */
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
    body.push([/* { text: 'Item Code', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] }, */
        { text: 'Item Name', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
      /*   { text: '', fontSize: font_size, bold: true, alignment: 'center', border: [false, false, false, false] }, */
        { text: 'Package Unit', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Qty', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'FOC', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        //{ text: 'Pur.Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        // { text: 'New WS Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Disct%', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        //{ text: 'Dis.value', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },



    ]);





    for (i = 0; i < data.length; i++) {
        var price = parseFloat(data[i].price);
        var quantity = Math.abs(parseFloat(data[i].quantity));
        var package_size = parseFloat(data[i].package_size);
        var free_quantity = Math.abs(parseFloat(data[i].free_quantity));
        var package_unit = data[i].package_unit;
        var unit_of_measure = parseFloat(data[i].unit_of_measure);

        if (!isNaN(price) && !isNaN(quantity)) {
            var amount = price * quantity;

            var formattedamount = amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            var formattedPrice = price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            var formattedpackage_size = package_size.toLocaleString();
            var formattedquantity = quantity.toLocaleString();
            var formattedfreequantity = free_quantity.toLocaleString();
            var formattedpackage_unit = package_unit;
           if(!!formattedpackage_unit){
            formattedpackage_unit = '';
           }
            
            //var formattedunit_of_measure = unit_of_measure.toLocaleString();
            
           
            if(((i % 28) == 1) && i > 28){
                body.push([
                    { text: '', pageBreak: 'after',border: [false, false, false, false] },
                 /*    { text: '', pageBreak: 'after',border: [false, false, false, false] }, */
                    { text: '', pageBreak: 'after',border: [false, false, false, false] },
                    { text: '', pageBreak: 'after',border: [false, false, false, false] },
                    { text: '', pageBreak: 'after',border: [false, false, false, false] },
                    { text: '', pageBreak: 'after' ,border: [false, false, false, false]},
                    { text: '', pageBreak: 'after',border: [false, false, false, false] },
                    { text: '', pageBreak: 'after',border: [false, false, false, false] },
                ]) 
               }else{
                body.push([
                    /* { text: data[i].Item_code, fontSize: font_size, alignment: 'center', border: [false, false, false, true] }, */
                    { text: data[i].item_name, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
                   /*  { text: "", fontSize: font_size, alignment: 'right', border: [false, false, false, false] }, */
                    { text: formattedpackage_unit, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
    
                    { text: formattedquantity, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                    { text: formattedfreequantity, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                    // { text: formattedunit_of_measure, fontSize: font_size, alignment: 'right', border: [false, false, false, true] },
                    { text: formattedPrice, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                    { text: data[i].discount_percentage, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
                    //{ text: parseFloat(data[i].value).toFixed(2), fontSize: font_size, alignment: 'right', border: [false, false, false, true] },
                    //{ text: data[i].price, fontSize: font_size, alignment: 'right', border: [false, false, false, true] },
                    { text: formattedamount, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
    
                ]);
               
               }
           
                
            
        }

    }

    return body;
}
