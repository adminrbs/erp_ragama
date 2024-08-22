



function report(br_id,collector_id,collector_name) {

   
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
        url: '/cb/printTable/'+br_id+'/'+collector_id,
         type: 'GET',
         dataType: 'json',
         success: function (data) {
             var dt = data.data
             var page_body = [];
             
 
 
             var goodrecive = dt.goodrecive;
             var sfaReceipts = dt.sfaReceipts;
 
 console.log(sfaReceipts);
 
             reportHeader(goodrecive, sfaReceipts, dt, 'PRINT',collector_name);
         }
     })
     
 
 
 
 }
 
 
 
 
 
 
 
 function reportHeader(goodrecive, sfaReceipts, dt, flag,empName) {


   
   var total=0
    for (var i = 0; i < sfaReceipts.length; i++) {
        var amount = parseFloat(sfaReceipts[i].set_off_amount);
        
        amount = amount;
        total += amount

       
    }
    var netTotal = total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
 
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
                                    widths: [110,'*', 10],
                                     headerRows: 1,
 
                                     body: [
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             { text: 'KANDANA FOOD AND DRUGS (PVT) LTD', fontSize: 15, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            
                                         ],
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'Collection Handover Report - Cash Pending As At 26/10/2023', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
                                        [
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             { text: empName, fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            
                                         ],
                                       /*  [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'Goods Received  Note', fontSize: 11, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: 'Reference No :'+ (goodrecive[0].external_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
                                         [
                                             { text: 'Date :'+goodrecive[0].payment_due_date, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'Branch :'+  (goodrecive[0].branch_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: 'Manual No :'+(goodrecive[0].internal_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: 'Currency :'+ 'LKR 1.0000' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'PO User :'+ (goodrecive[0].userName || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: 'PO No :'+ (goodrecive[0].external_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: 'App. User :'+ 'THARAKA', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: 'Supplier :'+ (goodrecive[0].supplier_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: 'Add. User :'+ '6/6/2023', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: 'Address:'+ (goodrecive[0].primary_address || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: 'App. User :'+ 'Tharaka', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
*/
 
 
                                     ],
                                    
 
 
 
 
                                 }, border: [false, false, false, false]
                             }],
                         ],
                     },
                     margin: [0, 30],
 
 
 
                 },
 
             ]
         }
     ];
 
     var Body = [
         
 
         {
            
             table: {
                 widths: ['*', 50,'*', '*', 100, 40, '*','*'],
                 headerRows: 1,
                 body: reportitemBody(sfaReceipts),
             },
             margin: [0,0,0, 0]
         },
         //{ canvas: [{ type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 1 }] },
 
 
         {
             table: {
                 widths: [200,'*','*','*','*'],
                 headerRows: 0,
 
 
                 body: [
 
                     [
                         ///{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                     ],
                     [
                        // { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                     ],
                     [
                         //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                     ],
 
                     [
                         //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: 'Total for Rep', fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false], margin: [18, 0, 0, 0] },
                         { text: '', fontSize: 12, bold: true, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: netTotal, fontSize: 12, bold: true, alignment: 'right', border: [false, false, false, false] },
                     ],
                    
                   
                     
                     
                 ],
             }, margin: [0, 10,0,0]
         },
 
 
 
 
         { text: '', margin: [5, 60, 10, 20] },
         {
             table: {
                 widths: [90, "*", 90,],
                 headerRows: 1,
 
                 body: [
                    [
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        //{ text: '', fontSize: 0, bold: true, alignment: 'center', border: [false, false, false, false] },
                        //{ text: '..................................', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                    ],
                    [
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: ' ', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: ' ', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
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
                         //{ text: +sfaReceipts.length, fontSize: 9, bold: false, alignment: 'center', border: [false, true, false, true], margin: [0, 0, 0, 0] },
 
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
            margin:[0,-20,0,0]
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
     //console.log(data);
     var body = [];
     body.push([{ text: 'Reference No', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Date', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Invoice No', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Invoice Date', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Customer Name', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Date Gap', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     //{ text: 'Remarks', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Town Name', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     //{ text: 'Retial Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     //{ text: 'Dis.value', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     //{ text: '', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
   
    
 
     ]);
 
 
     for (i = 0; i < data.length; i++) {
        var amount = parseFloat(data[i].set_off_amount);
        var formattedAmount = amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         body.push([
             { text: data[i].external_number, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: data[i].receipt_date, fontSize: font_size, alignment: 'center', border: [false, false, false, false] },
             { text: data[i].EX_num, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: data[i].trans_date, fontSize: font_size, alignment: 'center', border: [false, false, false, false] },
 
             { text: data[i].customer_name, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: data[i].Gap, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: data[i].townName, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: formattedAmount, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             //{ text: data[i].external_number, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             //{ text: parseFloat(data[i].value).toFixed(2), fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             //{ text: data[i].price, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             //{ text: formattedretial_price, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
            
         ]);
        }
        
         
     
 
     return body;
 }
 