$(document).ready(function () {
    $('#btnPrint').on('click', function () {

    });
});


function generatePurchaseRequestReport(id) {
    $.ajax({
        url: '/prc/getRequestDataForRPT/' + id,
        type: 'get',
        dataType: 'json',
        success: function (data) {
            var dt = data.data
            
            var page_body = [];
            /* for(var i=0;i < dt.length;i++){
               page_body.push(
                   {
                   "purchase_request_Id":dt[i].purchase_request_Id,
                   "internal_number":dt[i].internal_number,
                   "external_number":dt[i].external_number,
                   "purchase_request_date_time":dt[i].purchase_request_date_time,
                   "branch_id":dt[i].branch_id,
                   "location_id":dt[i].location_id,
                   "expected_date":dt[i].expected_date,
                   "approval_status":dt[i].approval_status,
                   "remarks":dt[i].remarks,
                   "prepaired_by":dt[i].prepaired_by,
                   "approved_by":dt[i].approved_by,
                   "item_id":dt[i].item_id,
                   "item_name":dt[i].item_name,
                   "quantity":dt[i].quantity,
                   "unit_of_measure":dt[i].unit_of_measure,
                   "Pack size":dt[i].package_unit,
                   "package_size":dt[i].package_size,
                   "description":dt[i].description,
                   "quantity":dt[i].quantity,
                   }
   
               );
   
            } */
            var purchase_request = dt.purchaseRequests;
            var purchase_request_item = dt.purchaseReqestItems;
            var purchase_request_other = dt.purchaseRequestOthers;


            reportHeader(purchase_request, purchase_request_item, purchase_request_other,'PRINT');
        }
    })





    /* $('#btnExport').on('click', function () {
    var page_body = [
        {"employee_code":"EMP01","employee_name":"Sampath Perera","email":"sampath@gmail.com","address":"Gampaha"},
        {"employee_code":"EMP01","employee_name":"Sampath Perera","email":"sampath@gmail.com","address":"Gampaha"},
        {"employee_code":"EMP01","employee_name":"Sampath Perera","email":"sampath@gmail.com","address":"Gampaha"},
        {"employee_code":"EMP01","employee_name":"Sampath Perera","email":"sampath@gmail.com","address":"Gampaha"},
        {"employee_code":"EMP01","employee_name":"Sampath Perera","email":"sampath@gmail.com","address":"Gampaha"},
        {"employee_code":"EMP01","employee_name":"Sampath Perera","email":"sampath@gmail.com","address":"Gampaha"},
        {"employee_code":"EMP01","employee_name":"Sampath Perera","email":"sampath@gmail.com","address":"Gampaha"},
    ];
    reportHeader(page_body, 'EXPORT');
}); */
}



function reportHeader(purchase_request, purchase_request_item, purchase_request_other, flag) {
    /* for(i=0;i<purchase_request.length;i++){
        console.log(purchase_request[i].external_number);
        console.log(purchase_request[i].purchase_request_date_time);
    }
 */

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
                            [{ text: 'KDF LANKA', fontSize: 20, bold: true, alignment: 'center', border: [false, false, false, false] }],
                            [{ text: 'INVOICE', fontSize: 14, bold: true, alignment: 'center', border: [false, false, false, false] }],
                            [{
                                table: {
                                    widths: ['*', 150],
                                    headerRows: 1,
                                    body: [
                                        
                                        [{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Referance No : ' + purchase_request[0].external_number, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        [{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Date : ' + purchase_request[0].purchase_request_date_time, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        [{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Branch : '+purchase_request[0].branch_name, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        [{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Location : '+ purchase_request[0].location_name, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        [{ text: '', fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }, { text: 'Expected Date : '+purchase_request[0].expected_date, fontSize: 9, bold: false, alignment: 'left', border: [false, false, false, false] }],
                                        
                                        


                                    ],

                                }, border: [false, false, false, false]
                            }],
                        ],


                    },
                    margin: [0, 0],
                },
                


            ]
        }
    ];
   



    var Body = [
      /*   {
            table: {
                widths: [100, '*', '*', '*', '*', '*', '*', '*', '*', '*', '*'],
                headerRows: 1,
                body: reportPurchaseRequestBody(purchase_request),
            },
            margin: [0, 10],
        }, */

        //item table
        {
            table: {
                widths: [100, '*', '*', '*', '*'],
                headerRows: 1,
                body: reportPurchaseRequestItemBody(purchase_request_item),
            },
            margin: [0, 0],
        },
        //{canvas: [{ type: 'line', x1: 0, y1: 0, x2: 761, y2: 0, lineWidth: 1 }]},

        //other table
      /*  {
            table: {
                widths: ['*',100],
                headerRows: 1,
                body: reportPurchaseRequestOtherBody(purchase_request_other),
            },
            margin: [0, 30],
        },*/
          

        //remarks
        {
            table: {
                widths: [100,'*'],
                headerRows: 1,
                body:[
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
        },
        {
            canvas: [{ type: 'line', x1: 0, y1: 0, x2: 650, y2: 0, lineWidth: 1, dash: { length: 5, space: 3 }, }],
        margin: [25, 0, 0, 0],
    },
    

        {/* 
            table: {
                widths: ['*', 60],
                headerRows: 0,

                body: [
                    [
                        { text: 'Total No of Items :', fontSize: 9, bold: false, alignment: 'right', border: [false, true, false, true], margin: [0, 0, 0, 0], colSpan: 1 },
                        { text: '', fontSize: 9, bold: false, alignment: 'center', border: [false, true, false, true], margin: [0, 0, 0, 0] },

                    ],


                ],
            }, */
        },

        { text: '', margin: [5, 2, 10, 20] },
        {
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


        },

      /*   { */
            // if you specify width, image will scale proportionally
            
           /*  image: 'data:/prc/Resources/assets/images/1665543373.png', */
          /*  image: 'data:image/jpeg;base64,https://example.com/images/my-image.jpg',
            width: 150
          }, */
        /* {
            table: {
                widths: [100, '*', '*', '*'],
                headerRows: 1,
                body: reportBody(body),
            },
        }, */
        

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
    
    { text: 'Item Code', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Item Name', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'QTY ', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'U.O.M', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'Pack Size', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] }]);
    
  

    for (i = 0; i < data.length; i++) {
        body.push([
            { text: data[i].Item_code, fontSize: font_size, alignment: 'left', border: [true, false, false, true] },
            { text: data[i].item_name, fontSize: font_size, alignment: 'left', border: [true, false, false, true] },
            { text: data[i].quantity, fontSize: font_size, alignment: 'center', border: [true, false, false, true] },
            { text: data[i].unit_of_measure, fontSize: font_size, alignment: 'center', border: [true, false, false, true] },
            { text: data[i].package_unit, fontSize: font_size, alignment: 'center', border: [true, false, true, true] },
           
  

        ]);
    }

    return body;
}
/*
function reportPurchaseRequestOtherBody(data) {
    var font_size = 13;
    var body = [];
    body.push([
    { text: 'Description', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] },
    { text: 'QTY', fontSize: font_size, bold: true, alignment: 'center', border: [true, true, true, true] }]);
    

    for (i = 0; i < data.length; i++) {
        body.push([
            { text: data[i].description, fontSize: font_size, alignment: 'center', border: [true, true, true, true] },
            { text: data[i].quantity, fontSize: font_size, alignment: 'center', border: [true, true, true, true] },
           
        ]);
    }

    return body;
}*/

/*   "purchase_request_Id":dt[i].purchase_request_Id,
                "internal_number":dt[i].internal_number,
                "external_number":dt[i].external_number,
                "purchase_request_date_time":dt[i].purchase_request_date_time,
                "branch_id":dt[i].branch_id,
                "location_id":dt[i].location_id,
                "expected_date":dt[i].expected_date,
                "approval_status":dt[i].approval_status,
                "remarks":dt[i].remarks,
                "prepaired_by":dt[i].prepaired_by,
                "approved_by":dt[i].approved_by */