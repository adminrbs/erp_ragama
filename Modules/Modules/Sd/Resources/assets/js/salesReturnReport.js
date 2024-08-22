



function generateSalesReturnReport(id) {


     $.ajax({
         url: '/sd/printsalesReturnPdf/'+id,
         type: 'GET',
         dataType: 'json',
         success: function (data) {
             var dt = data.data
             var page_body = [];
             
 
 
             var sales_Return = dt.salesReturnRequests;
             var sales_Return_item = dt.salesReturnReqestItems;
 
 
 
             reportHeader(sales_Return, sales_Return_item, dt, 'PRINT');
         }
     })
     
 
 
 
 }
 
 
 
 
 
 
 
 function reportHeader(sales_Return, sales_Return_item, dt, flag) {
 
     var wsp = 0;
     var qty = 0;
     var subTotal = 0;
     for (var i = 0; i < sales_Return_item.length; i++) {
         var wsp = parseFloat(sales_Return_item[i].whole_sale_price);
         var qty = parseFloat(sales_Return_item[i].quantity);
        
            subTotal += wsp * qty;
 
            //totalAmount += subTotal
         
     }
 
     var disAmount = 0;
     var freeqty = 0;
     var Dis_Total = 0;
     for (var i = 0; i < sales_Return_item.length; i++) {
         var disAmount = parseFloat(sales_Return_item[i].discount_amount);
         var freeqty = parseFloat(sales_Return_item[i].free_quantity);
        
         Dis_Total += disAmount;
   
     }
    
     var DisTotal = Dis_Total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
     //alert(DisTotal);
     var total_Amount=0;
     var gross_total = 0;
     for (var i = 0; i < sales_Return_item.length; i++) {
        var price = (sales_Return_item[i].price);
        var qty = (sales_Return_item[i].quantity);
        console.log(price);
        console.log(qty);
 
           // total_Amount +=( price * qty)-Dis_Total; // Calculate and accumulate totalAmount
            total_Amount +=( price * qty); // Calculate and accumulate totalAmount

            gross_total += ( price * qty);
    }
    total_Amount = total_Amount - Dis_Total;
    var formattotalAmount = total_Amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    var formatGrosAmount = gross_total.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
 
 
    
 
   
    
   
 
     
   
     
 
 
 
 
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
                                     widths: ['*',220, '*'],
                                     headerRows: 1,
 
                                     body: [
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             { text: dt.company_name, fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            
                                         ],
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: dt.company_address  +'', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
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
                                             { text: 'SALES RETURN', fontSize: 15, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                        /*  [
                                             { text: 'Reference No :'+ (sales_Return[0].manual_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ], */
                                         [
                                             { text: 'Date :'+sales_Return[0].order_date, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'Branch :'+  (sales_Return[0].branch_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
                                         
                                        /*  [
                                             { text: 'Manual No :'+ (sales_Return[0].internal_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'Currency :'+'LKR', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            
                                         ], */
 
                                         [
                                             { text: 'Return No :'+ (sales_Return[0].external_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'User :'+  (sales_Return[0].userName || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                        /* [
                                             { text: 'Supplier :'+sales_Return[0].supplier_name, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'Add. User :'+ '6/6/2023', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],*/
                                         [
                                            { text: 'Customer:'+ (sales_Return[0].customer_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                        ],
 
                                         [
                                             { text: 'Address:'+ (sales_Return[0].primary_address || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
                                         /* [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             
                                         ], */
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
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
                 widths: ['*', 90,'*', '*', 40, '*', '*', '*',70],
                 headerRows: 1,
                 body: reportitemBody(sales_Return_item),
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
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        //{ text: 'Sub Total', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text:'', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],
                    [
                       // { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: 'Gross Amount', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: formatGrosAmount, fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],
 
                     [
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         //{ text: 'Sub Total', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text:'', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                     ],
                     [
                        // { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: 'Total Discount', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: DisTotal, fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false] },
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
                         { text: 'Total Amount', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: formattotalAmount, fontSize: 8, bold: false, alignment: 'right', border: [false, true, false, false] },
                     ],
                     [
                         //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, false] },
                     ],
                     [
                         //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: 'Remarks :'+ (sales_Return[0].remarks || ''), fontSize: 8, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, true, false, false] },
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
                         //{ text: +sales_Return_item.length, fontSize: 9, bold: false, alignment: 'center', border: [false, true, false, true], margin: [0, 0, 0, 0] },
 
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
     var body = [];
     body.push([{ text: 'Item Code', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Item Name', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Pack', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Pack Size', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Quantity', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Free Qty', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     //{ text: 'Pur.Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    // { text: 'New WS Price', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Cur. Sell', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Dis %', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     //{ text: 'Dis.value', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
   
    
 
     ]);
 
 
     for (i = 0; i < data.length; i++) {
         var price = parseFloat(data[i].price);
         var quantity = parseFloat(data[i].quantity);
         var package_size = parseFloat(data[i].package_size);
         var free_quantity = parseFloat(data[i].free_quantity);
         var package_unit = parseFloat(data[i].package_unit);
         var unit_of_measure = parseFloat(data[i].unit_of_measure);
     
         if (!isNaN(price) && !isNaN(quantity)) {
             var amount = price * quantity;
 
             var formattedamount = amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
           
             var formattedPrice = price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
             var formattedpackage_size = package_size.toLocaleString();
             var formattedquantity = quantity.toLocaleString();
             var formattedfreequantity = free_quantity.toLocaleString();
             var formattedpackage_unit = package_unit.toLocaleString();
             //var formattedunit_of_measure = unit_of_measure.toLocaleString();
         body.push([
             { text: data[i].Item_code, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: data[i].item_name, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: formattedpackage_size, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: formattedpackage_unit, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
 
             { text: formattedquantity, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: formattedfreequantity, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
            // { text: formattedunit_of_measure, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: formattedPrice, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: data[i].discount_percentage, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             //{ text: parseFloat(data[i].value).toFixed(2), fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             //{ text: data[i].price, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: formattedamount, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
            
         ]);
     }
         
     }
 
     return body;
 }
 