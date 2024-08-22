
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
 Created on : Feb 02, 2020, 12:10:33 AM
 Author     : Sampath Perera
 */

function PDFViewer(size, orientation, title, header, logo, body, footer) {

    pdfMake.fonts = {
        FM_Abhaya: {
            normal: '/fonts/FM_Abhaya.ttf',
            bold: '/fonts/FM_Abhaya.ttf',
            italics: '/fonts/FM_Abhaya.ttf',
            bolditalics: '/fonts/FM_Abhaya.ttf',
        },

        English_font: {
            normal: '/fonts/tahoma.ttf',
            bold: '/fonts/tahomabd.ttf',
            italics: '/fonts/tahoma.ttf',
            bolditalics: '/fonts/tahoma.ttf',
        },

        // download default Roboto font from cdnjs.com
        Roboto: {
            normal: 'Roboto-Regular.ttf',
            bold: 'Roboto-Medium.ttf',
            italics: '/fonts/Roboto-Italic.ttf',
            bolditalics: '/fonts/Roboto-MediumItalic.ttf'
        },

    }


    var pageContent = {
        pageSize: size,
        pageOrientation: orientation,
        header: title,
        /*defaultStyle: {
            font: 'FM_Abhaya'
        },Add custom font all elements*/
        footer: function (currentPage, pageCount) {
            return footerContext(footer, currentPage, pageCount);

        },
        content: [
            pageContext(logo, header, body),
        ],
        styles: {
            mainLayout: {}
        }
    };
    pdfMake.createPdf(pageContent).open();


}




function logoContext(logo) {
    if (logo != null && logo != undefined) {
        return {
            image: logo.image,
            width: logo.width,
            height: logo.height,
            alignment: logo.alignment,
        };
    }
    return {};
}


function headerContext(header) {
    if (header != null && header != undefined) {
        return header;
    }
    return [];
}



function bodyContext(body) {
    if (body != null && body != undefined) {
        return bodyContent(body);
    }
    return [];
}




function pageContext(logo, header, body) {

    var flag = 0;
    if (header.flag) {
        flag = 2;
    }

    return {
        style: 'mainLayout',
        layout: 'noBorders',
        table: {
            widths: ['100%'],
            headerRows: flag,
            body: [
                [logoContext(logo)],
                [{
                    stack: headerContext(header.content),

                }],
                [{
                    stack: bodyContext(body),
                }],

            ]
        },
        margin: [0, 0],
    }
}





function footerContext(footer, currentPage, pageCount) {
    console.log(footer);
    return [

        footer,
        { text: 'Page ' + currentPage.toString() + ' of ' + pageCount, alignment: 'center',color: 'red',fontSize: 8},


    ];
}





function bodyContent(body) {
    var context = [];

    for (i = 0; i < body.length; i++) {
        if (body[i].customTable != undefined) {
            context.push(tableGroup(body[i]));
        } else {
            context.push(body[i]);
        }
    }
    return context;
}





var totalAmount = 0.0;
function tableGroup(table) {

    var array = [];
    if (table.customTable != undefined) {

        for (i3 = 0; i3 < table.customTable.length; i3++) {

            if (table.customTable[i3].header != undefined && table.customTable[i3].body != undefined && table.customTable[i3].sum != undefined && table.customTable[i3].op != undefined) {
                array.push(createGroup(table.customTable[i3]));

            }

            if (table.customTable[i3].total != undefined) {
                array.push(groupTotal(table.customTable[i3].total, totalAmount));
                totalAmount = 0.0;
            }
        }
    }
    return array;
}







function createGroup(table) {

    var tableBody = [];
    for (i5 = 0; i5 < table.header.length; i5++) {
        tableBody.push(table.header[i5]);
    }
    for (i6 = 0; i6 < table.body.length; i6++) {
        tableBody.push(table.body[i6]);
    }
    tableBody.push(tableSum(table));
    return {
        table: {
            widths: [80, '*', '*', '*'],
            headerRows: table.header.length,
            body: tableBody
        },

        margin: [0, 20],
    };
}





function groupTotal(total, amount) {

    total[0].text = total[0].text + parseFloat(amount).toFixed(2);
    return {
        table: {
            widths: ['*', 'auto'],
            headerRows: 1,
            body: [[{
                text: '',
                border: [false, false, false, false]
            }, total]],
            alignment: 'right'
        },
    }
}















function tableSum(table) {

    if (table != undefined) {
        if (table.sum != undefined && table.body != undefined && table.op != undefined) {
            console.log(table.sum.length);
            for (i2 = 0; i2 < table.sum.length; i2++) {
                const name = table.sum[i2].name;
                const index = table.sum[i2].index;
                var total = 0.0;
                for (ii2 = 0; ii2 < table.body.length; ii2++) {
                    total += parseFloat(table.body[ii2][index].text);
                }
                for (ii3 = 0; ii3 < table.op.length; ii3++) {
                    if (table.op[ii3].name == name) {
                        table.op[ii3].text = parseFloat(total).toFixed(2);
                    }
                }
            }
            totalAmount += total;
            return table.op;
        }
    }


}

