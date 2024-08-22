

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
 Created on : Feb 02, 2020, 12:10:33 PM
 Author     : Sampath Perera
 */


class Page {

    static EVERY = true;
    static SINGLE = false;

    constructor() {
        this.PageSize = 'A4';
        this.PageOrientation = 'portrait';
        this.PageMargin = [40, 60, 40, 60];
        this.Title = null;
        this.Logo = null;
        this.Header = {
            content: null,
            flag: Page.EVERY,
        };
        this.Body = null;
        this.Footer = null;
    }

    setPageSize(size) {
        this.PageSize = size;
    }

    setPageOrientation(orientation) {
        this.PageOrientation = orientation;
    }

    setPageMargin(margin) {
        this.PageMargin = margin;
    }

    setTitle(title) {
        this.Title = title;
    }


    setHeader(header, Flag) {
        this.Logo = header[0].logo;
        this.Header.content = header[0].content;
        this.Header.flag = Flag;
    }

    setBody(body) {
        this.Body = body;
    }

    setFooter(footer) {
        this.Footer = footer;
    }

    setFlag(flag) {
        Page.STATUS = flag;
    }


    preview() {
        this.makePDF(this);
    }


    makePDF(page) {

        if (page.Logo != null) {
            const img = new Image();
            img.src = page.Logo.path;
            img.addEventListener('load', function (event) {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = event.currentTarget.width;
                canvas.height = event.currentTarget.height;
                ctx.drawImage(event.currentTarget, 0, 0);
                const dataurl = canvas.toDataURL('image/jpeg');
                PDFViewer(page.PageSize, page.PageOrientation, page.Title, page.Header, { "image": dataurl, 'width': page.Logo.width, 'height': page.Logo.height, 'alignment': page.Logo.alignment }, page.Body, page.Footer);

            });
        } else {
            PDFViewer(page.PageSize, page.PageOrientation, page.Title, page.Header, null, page.Body, page.Footer, page.PageMargin);
        }



    }


    export() {
        console.log(this.Body[0].table.body);

        const rows = [];

        for (var i = 0; i < this.Body[0].table.body.length; i++) {
            var row = [];
            for (var i2 = 0; i2 < this.Body[0].table.body[i].length; i2++) {
                if (this.Body[0].table.body[i][i2].text) {
                    var row_val = this.Body[0].table.body[i][i2].text;
                    // row_val = row_val.replace(/,/g, ' ');
                    var contains_comma = /,/.exec(row_val);
                    if (contains_comma) {
                        row_val = row_val.replace(/,/g, ' ');
                    }
                    var contains_n = /\n/.exec(row_val);
                    if (contains_n) {
                        row_val = row_val.replace(/\n/g, ' ');
                    }
                    var contains_r = /\r/.exec(row_val);
                    if (contains_r) {
                        row_val = row_val.replace(/\r/g, ' ');
                    }
                    row.push(row_val);
                } else {
                    row.push("");
                }
            }
            rows.push(row);
        }

        let csvContent = "data:text/csv;charset=utf-8,";

        rows.forEach(function (rowArray) {
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "my_data.csv");
        document.body.appendChild(link); // Required for FF

        link.click(); // This will download the data file named "my_data.csv".
    }







    export2() {
        console.log(this.Body);

        const rows = [];

        for (var r = 0; r < this.Body.length; r++) {
            console.log(this.Body[r][0].stack[0]);
            rows.push([""]);
            rows.push([""]);
            rows.push([this.Body[r][0].stack[0].text]);
            rows.push([this.Body[r][0].stack[1].text]);
            for (var i = 0; i < this.Body[r][0].stack[3].table.body.length; i++) {
                var row = [];
                for (var i2 = 0; i2 < this.Body[r][0].stack[3].table.body[i].length; i2++) {
                    if (this.Body[r][0].stack[3].table.body[i][i2].text) {
                        var row_val = this.Body[r][0].stack[3].table.body[i][i2].text;
                        // row_val = row_val.replace(/,/g, ' ');
                        var contains_comma = /,/.exec(row_val);
                        if (contains_comma) {
                            row_val = row_val.replace(/,/g, ' ');
                        }
                        var contains_n = /\n/.exec(row_val);
                        if (contains_n) {
                            row_val = row_val.replace(/\n/g, ' ');
                        }
                        var contains_r = /\r/.exec(row_val);
                        if (contains_r) {
                            row_val = row_val.replace(/\r/g, ' ');
                        }
                        row.push(row_val);
                    } else {
                        row.push("");
                    }
                }
                rows.push(row);
            }
        }

        let csvContent = "data:text/csv;charset=utf-8,";

        rows.forEach(function (rowArray) {
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });

        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "my_data.csv");
        document.body.appendChild(link); // Required for FF

        link.click(); // This will download the data file named "my_data.csv".

    }



    static getReportName() {

        return "Riococo  Lanka (Pvt) Ltd";
    }


}