



function generateReceiptList(id) {

    
 
 
     $.ajax({
         url: '/cb/customer_receipt/generateReceiptReport/' + id,
         type: 'GET',
         dataType: 'json',
         success: function (response) {
            var dt = response.data;

             var page_body = [];
             
 
 
             var customerRecipthedder = dt.customerRecipthedder;
             var customerRecipt = dt.customerRecipt;
           
 
 
 
             reportHeader(customerRecipthedder, customerRecipt, dt, 'PRINT');
         }
     })
     
 
 
 
 }
 
 
 
 
 
 
 
 function reportHeader(customerRecipthedder, customerRecipt, dt, flag) {
 
 
 
    var total_Amount = 0;
    for (var i = 0; i < customerRecipt.length; i++) {
        var price = parseFloat(customerRecipt[i].set_off_amount);
        total_Amount += price; // Calculate and accumulate totalAmount
    }
    
    // Format total_Amount with two decimal places
    var formattotalAmount = total_Amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    
    
 
 
 
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
                            [
                                { text: dt.company || '', fontSize: 14, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                
                            ],
                            [
                                { text: dt.adderess || '', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                
                            ],
                            [
                                { text: dt.phoneNumber || '', fontSize: 12, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                
                            ],
                            [
                                { text: 'Customer Receipt', fontSize: 14, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                
                            ],
                           
                           
                        ],
                    }, margin: [0, -6],
        
        
                },
 
                 {
                     table: {
                         //widths: ['*','*','*','*','*','*','*','*','*',],
                         // headerRows: 1,
                         body: [
 
                             [{
                                 table: {
                                     widths: [250,120,120],
                                     headerRows: 1,
 
                                     body: [
                                        [
                                            { text: (customerRecipthedder[0].branch_name || ''), fontSize: 16, bold: true, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', fontSize: 14, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: 'Receipt No :'+(customerRecipthedder[0].external_number || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                           
                                        ],
                                        [
                                            { text: (customerRecipthedder[0].Baddress || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false] },
                                            { text: '', fontSize: 16, bold: true, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: 'Date :'+(customerRecipthedder[0].receipt_date || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                           
                                         ],
                                       
                                     ],
                                    
 
 
 
 
                                 }, border: [false, false, false, false]
                             }],
                         ],
                     },
                     margin: [0, 8],
 
 
 
                 },
                 {
                    table: {
                        widths: [300, "*", 150,],
                        headerRows: 1,
        
                        body: [
                            [
                                { text: 'Customer :'+(customerRecipthedder[0].customer_name || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [5, 0, 0, 0] },
                                { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                { text: ''   , fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                            ],
                            [
                                { text: 'Address:'+(customerRecipthedder[0].primary_address || ''), fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [5, 0, 0, 0] },
                                { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                { text: '', fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                            ],
                            [
                                { text: '', fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                { text: '', fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                            ],
                            [
                                { text: 'Settlement Details', fontSize: 12, bold: true, alignment: 'left', border: [false, false, false, false], margin: [5, 0, 0, 0] },
                                { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                { text: '', fontSize: 10, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                            ],
        
                        ],
                    }, margin: [0, 10],
        
        
                },
 
             ]
         }
     ];
 
     var Body = [
         
 
         {
             table: {
                 widths: [70, 80,120, '*', 100, ],
                 headerRows: 1,
                 body: reportitemBody(customerRecipt),
             },
             margin: [0,0,0, 0]
         },
         //{ canvas: [{ type: 'line', x1: 0, y1: 0, x2: 515, y2: 0, lineWidth: 1 }] },
 
 
         {
             table: {
                 widths: [50,'*','*','*','*',100],
                 headerRows: 0,
 
 
                 body: [
 
                     
                     [
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
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
                         { text: 'TOTAL', fontSize: 10, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: formattotalAmount, fontSize: 10, bold: false, alignment: 'right', border: [false, true, false, false] },
                         { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                     ],
                     [
                         //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, false] },
                         { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                     ],
                     [
                         //{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 8, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                         { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, true, false, false] },
                         { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },

                     ],
                     
                     
                     
                 ],
             }, margin: [0, 10,0,0]
         },
 
 
 
 
         { text: '', margin: [5, 60, 10, 20] },
         {
             table: {
                 widths: [350, "*", 50,],
                 headerRows: 1,
 
                 body: [
                    [
                        { text: '(Payment is valid subject to the realization of the cheque only)', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        { text: '-------------------', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                        
                    ],
                    [
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: 'Signature', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
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
     var font_size = 10;
     var body = [];
     body.push([{ text: 'Invoice No', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Invoice Date', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Invoice Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Paid Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     { text: 'Balance', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     
    
 
     ]);
 
 
     for (i = 0; i < data.length; i++) {
         var balance = parseFloat(data[i].balance);
         var amount = parseFloat(data[i].amount);
         var set_off_amount = parseFloat(data[i].set_off_amount);
        
        
     
         if (!isNaN(amount)) {
             var balance = balance - set_off_amount;
 
             var invoiceamount = amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
           
             var paid_amount = set_off_amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
             var net_balance = balance.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

             //var formattedunit_of_measure = unit_of_measure.toLocaleString();
         body.push([
             { text: data[i].reference_external_number, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: data[i].date, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: invoiceamount, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: paid_amount, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
 
             { text: net_balance, fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
            
            
         ]);
    }
         
     }
 
     return body;
 }
 