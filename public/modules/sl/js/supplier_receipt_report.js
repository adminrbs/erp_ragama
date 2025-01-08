
function supplierReceiptReport(id) {

    $.ajax({
        url: '/sl/supplierReceiptReport/'+id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
           
         
           
            
            var supplierData = data.supplierData;
            var receiptData = data.recptData;
            var branch = data.branch;
            var companyName = data.companyName;
            reportHeader(supplierData, receiptData,branch,data, 'PRINT');
        }
    })
    
    return;
}
var total_amount = 0;
var total_paid = 0;
var total_receipt = 0;
var total_balance = 0;

function reportHeader(supplierData, receiptData,branch,data, flag) {
   

    for(var i = 0; i < receiptData.length; i++){
        total_amount += receiptData[0].amount;
        total_paid += receiptData[0].paid_amount;
        total_receipt += receiptData[0].receipt_amount;
        total_balance += receiptData[0].balance;
        
    }

 
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
                        
                         body: [
 
                             [{
                                 table: {
                                    widths: [200,'*', 120],
                                     headerRows: 1,
 
                                     body: [
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: data.companyName, fontSize: 11, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                           
                                        ],
                                        [
                                            { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            { text: data.address, fontSize: 9, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            
                                           
                                        ],
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             { text: "Supplier Payment Receipt", fontSize: 9, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             
                                            
                                         ],
                                         
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false] },
                                            
                                            
                                         ],
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 15, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            
                                         ],
 
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            
                                         ],
                                         [
                                             { text: 'Supplier : '+supplierData[0].supplier_name, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: 'Branch : '+ (branch[0].branch_name || ''), fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             
                                         ],
 
                                         [
                                             { text: 'Address: '+supplierData[0].primary_address || '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                            
                                             { text: 'Receipt : '+receiptData[0].external_number, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: 'Receipt Date: '+receiptData[0].receipt_date, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                           
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] },
                                         ],
 
                                         [
                                             { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             { text: '', fontSize: 12, bold: true, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                                             
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
                 widths: [80, 80,80, 80, 80, 80],
                 headerRows: 1,
                 body: reportitemBody(receiptData),
             },
             margin: [0,0,0, 0]
         },
       
 
 
         {
             table: {
                 widths: [215,'*','*','*',"*"],
                 headerRows: 0,
                /*  total_amount += receiptData[0].amount;
                 total_paid += receiptData[0].paidamount;
                 total_receipt += receiptData[0].receipt_amount;
                 total_balance += receiptData[0].balance; */
 
                 body: [
 
                    [
                       
                        { text: 'Total', fontSize: 10, bold: true, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text:'', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: parseFloat(total_receipt).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }), fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, true], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],
                    [
                      
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],
                    [
                        
                        { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],

                    [
                        
                        { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                         { text: '', fontSize: 8, bold: false, alignment: 'right', border: [false, false, false, false], margin: [18, 0, 0, 0] },
                    ],
                    [
                        
                        { text: '' , fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
                    ],
                    [
                        
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false], margin: [0, 0, 0, 0] },
                        { text: '', fontSize: 9, bold: false, alignment: 'right', border: [false, false, false, false] },
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
                    ],
                    [
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, false, false, false], margin: [0, 0, 0, 15] },
                       
                    ],
                 ],
             },
 
 
         },
 
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
     body.push([
        { text: 'Invoice No', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Invoice Date', underline: true, fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Invoice Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Paid Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Receipt Amount', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
        { text: 'Balance', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
     ]);
 
 
     for (i = 0; i < data.length; i++) {
       

         body.push([
             { text: data[i].reference_external_number, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: data[i].trans_date, fontSize: font_size, alignment: 'left', border: [false, false, false, false] },
             { text: parseFloat(data[i].amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }), fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: parseFloat(data[i].paid_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }), fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text: parseFloat(data[i].receipt_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }), fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             { text:  parseFloat(data[i].balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }), fontSize: font_size, alignment: 'right', border: [false, false, false, false] },
             
         ]);
        
        
         
     }
 
     return body;
 }
 