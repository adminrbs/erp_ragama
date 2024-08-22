
var requestID;

$(document).ready(function () {
    if (window.location.search.length > 0) {
        var params = new URLSearchParams(window.location.search);
        requestID = params.get('id');
    }

    if (requestID) {
        salesinvoiceReportiframe(requestID);
    }

    $('#btnPrint').on('click', function () {
        // Check if print button has already been clicked
        salesinvoiceReportsd(requestID);

    });
});
function salesinvoiceReportiframe(id) {
   //alert();
    var status = 0;
    $.ajax({
        url: '/sd/printsalesinvoicePdf/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
        console.log(data);



            var dt = data.data;
            //$('#pdfContainer').attr('src', dt);
            console.log(dt);
            var iframe = document.getElementById('pdfContainer');
            var htmlContent = '';
            var headerData = dt.salesInvoiceRequests;
            var item_count = dt.salesInvoiceReqestItems.length;
            // Construct HTML content for items
            var duplicate = 'Original';
            var report_status = headerData[0].is_printed;
            console.log(headerData);
            if (report_status > 0) {
               
                duplicate = 'Duplicate';
            }
            var total_Amount = 0;
            var Dis_Total = 0;
            console.log(dt.sup_group[0].supply_group);
            var credit_days = 30;
            if(dt.sup_group[0].supply_group_id == 151 || dt.sup_group[0].supply_group_id == 155 || dt.sup_group[0].supply_group_id == 159){
                
                credit_days = 60;
            }
            htmlContent += '<style>body { margin: 0; padding: 0; width: 100%;color:blace; }</style>'; // Reset margin, padding, and set width to 100%
            htmlContent += '<h2 style="text-align: right;font-size: 15px !important;margin-right:5px;">' + duplicate + '</h2>';
           // htmlContent += '<h2  style="text-align: right; font-size: 16px !important; height: 15px">INVOICE</h2>';
            htmlContent += '<div id="header" style="text-align: center;">';
            htmlContent += '<p style="font-size: 30px !important; height: 5px; margin: 20px;"><b>' + dt.companyName + '</b></p>';
            htmlContent += '<p style="font-size: 20px !important; height: 5px; margin: 15px;">' + 
                        (dt.companyAddress || ' ') + (dt.companyContactDetails || '') + '</p>';
            htmlContent += '<p style="font-size: 24px !important; height: 5px; margin: 15px;"><b>Distributor For ' + 
                        (dt.branch[0].branch_name || ' ') + '</b></p>';
            htmlContent += '<br>';
            htmlContent += '</div>';

            htmlContent += '<table border="0" style="width: 100%;" >';

            htmlContent += '<tr><th ></th><td  colspan="6" style="text-align: left;font-size:20px;">Customer Name :- ' + headerData[0].customer_name + '</td><td colspan="1" style="text-align: left;font-size:18px;"><b>Invoice No :- </b>' + headerData[0].external_number + '</td></tr>';
            htmlContent += '<tr><th ></th><td colspan="6" style="text-align: left;font-size:20px;">Customer Address :- ' + headerData[0].primary_address + '</td><td colspan="1" style="text-align: left;font-size:18px;"><b>Date :- </b>' + headerData[0].order_date_time + '</td></tr>';
            htmlContent += '<tr><th ></th><td colspan="6" style="text-align: left;font-size:20px;">Contact No :- ' + headerData[0].primary_fixed_number + '</td><td colspan="1" style="text-align: left;font-size:18px;"></td></tr>';
            htmlContent += '<tr><th ></th><td colspan="6" style="text-align: left;font-size:20px;">Sales Rep :- ' + headerData[0].employee_name + '</td><th colspan="1" style="text-align: left;font-size:18px;"></th</tr>';
            htmlContent += '<tr style="height: 20px;"></tr>';
            htmlContent += '</table>'

            
            htmlContent += '<table border="1" style="width: 100%;">';
            htmlContent += '<tr style="font-size:18px;">';
            htmlContent += '<td style="text-align: center; width: 100px; border: 1px dotted black;">Code</td>';
            htmlContent += '<td style="text-align: center; width: 270px; border: 1px dotted black;">Item Name</td>';
            htmlContent += '<td style="text-align: center; width: 60px; border: 1px dotted black;">Size</td>';
            htmlContent += '<td style="text-align: center; width: 50px; border: 1px dotted black;">Qty</td>';
            htmlContent += '<td style="text-align: center; width: 50px; border: 1px dotted black;">F.QTY</td>';
            htmlContent += '<td style="text-align: right; width: 100px; border: 1px dotted black;">Price</td>';
          
            htmlContent += '<td style="text-align: right; width: 10px; border: 1px dotted black;">Amount</td>';
            htmlContent += '<td style="text-align: right; width: 110px; border: 1px dotted black;">Retail Price</td>';
            htmlContent += '</tr>';
            htmlContent += '</table>'
            htmlContent += '<table border="0" style="width: 100%;">';
            dt.salesInvoiceReqestItems.forEach(function (item) {
                var retial_price = parseFloat(item.inv_rt_price.replace(/,/g, ''));
                console.log(retial_price);
                var price = parseFloat(item.price.replace(/,/g, ''));
                var quantity = Math.abs(parseFloat(item.quantity));
                var package_size = parseFloat(item.package_size);
                var free_quantity = Math.abs(parseFloat(item.free_quantity));
                var package_unit = item.package_unit;
                var amount = price * quantity;
                var disAmount = parseFloat(item.discount_amount);
                Dis_Total += disAmount;

                total_Amount += price * quantity;
                var serverDateTime = new Date().toLocaleString();

                var formattedamount = parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                var formatretial_price = parseFloat(retial_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                console.log(formatretial_price);    
                if(isNaN(parseFloat(formatretial_price.replace(/,/g, '')))){
                    
                        formatretial_price = '0.00';
                    }
                var formattedPrice = price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                var formattedpackage_size = package_size.toLocaleString();
                var formattedquantity = quantity.toLocaleString();
                var formattedfreequantity = free_quantity.toLocaleString();
                var formattedpackage_unit = package_unit;
               
                //htmlContent += '<tr style="font-size: 18px; font-family: \'Times New Roman\', Times, serif;">';
                htmlContent += '<tr style="font-size: 18px; font-family: \'Courier\', \'Courier New\', monospace;">';
               // htmlContent += '<tr style="font-size: 16px; font-family: \'Draft\', sans-serif;">';
                htmlContent += '<td style="text-align: left; width: 100px;">' + item.Item_code + '</td>';
                htmlContent += '<td style="text-align: left; width: 270px;">' + item.item_name + '</td>';
                htmlContent += '<td style="text-align: center;  width: 60px;" >' + item.package_unit + '</td>';
                htmlContent += '<td style="text-align: center;  width: 50px;">' + formattedquantity + '</td>';
                htmlContent += '<td style="text-align: center;  width: 50px;">' + formattedfreequantity + '</td>';
                htmlContent += '<td style="text-align: right;  width: 100px;">' + formattedPrice + '</td>';
              
                htmlContent += '<td style="text-align: right;  width: 100px;">' + formattedamount + '</td>';
                htmlContent += '<td style="text-align: right; width: 110px;">' + formatretial_price + '</td>';
               
                htmlContent += '</tr>';
            });

            var totalAmount = total_Amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            var netTotal = total_Amount - Dis_Total;
            htmlContent += '<tr style="height: 30px;"></tr>';
            htmlContent += '</table>';
            htmlContent += '<div style="">'
            htmlContent += '<table border="0" style="width: 100%;font-size:16px;font-family: \'Draft\', sans-serif;">';
            htmlContent += '<tr><th colspan="2" style="text-align: left;">Total No of Items  ' + item_count + ' </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: right; font-size:15px;font-family: \'Draft\', sans-serif;">Gross Amount</th><th colspan="2" style="text-align: left;"> </th><th colspan="3" style="text-align: right; font-size:15px;font-family: \'Draft\', sans-serif;">  ' + 'RS. ' + totalAmount + '</th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th> </th><th > </th><th > </th><th > </th><th > </th><th > </th><th > </th><th > </th><th > </th><th > </th></tr>';
            htmlContent += '<tr><th colspan="2" style="text-align: left;"></th><th colspan="2" style="text-align: left;"> </th>';
            htmlContent += '<tr><th colspan="2" style="text-align: left;"></th><th colspan="2" style="text-align: left;"> </th>';
            htmlContent += '<tr><th colspan="2" style="text-align: left;"></th><th colspan="2" style="text-align: left;"> </th>';
            htmlContent += '<tr><th colspan="2" style="text-align: left;"></th><th colspan="2" style="text-align: left;"> </th>';
            htmlContent += '<tr><th colspan="2" style="text-align: left;"></th><th colspan="2" style="text-align: left;"> </th>';
            htmlContent += '<tr><th colspan="2" style="text-align: left;"></th><th colspan="2" style="text-align: left;"> </th>';
            
            htmlContent += '<tr><th colspan="2" style="text-align: left;"></th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: right; font-size:15px;font-family: \'Draft\', sans-serif;">Net Discount</th><th colspan="2" style="text-align: left;"> </th><u><th colspan="3" style="text-align: right; font-size:15px;font-family: \'Draft\', sans-serif;"> ' + Dis_Total + '</th><u/><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th><th colspan="2" style="text-align: left;"> </th> </th><th colspan="2" style="text-align: left;"> </th><th > </th><th > </th></tr>';
            htmlContent += '<tr>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: right; font-size:18px;font-family: \'Draft\', sans-serif; ">Total</th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="4" style="text-align: right;"><u><span style="border-bottom: 1px solid; padding-bottom: 1px; font-size:18px;font-family: \'Draft\', sans-serif; ">RS. ' + parseFloat(netTotal).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</span></u></th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"></th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th colspan="2" style="text-align: left;"> </th>';
htmlContent += '<th> </th>';
htmlContent += '<th> </th>';
htmlContent += '</tr>';


            htmlContent += '</table>';
            
            htmlContent +='</div>';
           
           
            var htmloutsanding = '';

            var grp_id = dt.outstanding[0].customer_group_id; // avoiding printing outstnading for selected customers

            if(grp_id != 30){
                htmloutsanding += '<table style="margin-top:10px;font-size: 15px">'
                // htmloutsanding += '<tr><td><h1 style="margin-left:5px;">30 Days Credit Only</h1><hr style="width: 700px; border: none; border-top: 2px dotted black; margin: 0 auto;></td></tr>'
                htmloutsanding += '<tr><td><h2 style="margin-left:5px;">'+credit_days+' '+'Days Credit Only</h2><hr style="width: 900px;border-top: 2px dotted black;"></td></tr>';
                htmloutsanding += '<tr><td style="font-size: 16px;font-family: \'Draft\', sans-serif;">Outstanding</td></tr>';
                  htmloutsanding += '</table>'
                    /* htmloutsanding += '<table style="margin-top:10px;font-size: 15px">'
                htmloutsanding += '<tr>'
                
                 
                  var number_ = 0;
                  console.log(dt.outstanding);
                   htmloutsanding += '<td style="font-size: 16px;font-family: \'Draft\', sans-serif;">'
                 dt.outstanding.forEach(function (item) {
                    
                    
                         htmloutsanding += item.external_number +" ";
                         htmloutsanding +=item.age +"Days"+" ";
                         htmloutsanding += parseFloat(item.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) +"  ";
                         number_++;
                     
                 });
                 htmloutsanding += '</td>';  
                 htmloutsanding += '</tr>'
                 htmloutsanding += '</table>' */
                 htmloutsanding += '<table style="margin-top:10px;font-size: 15px">'
                 htmloutsanding += '<tr>'
                  htmloutsanding += '<td>'
                 htmloutsanding += '<ul style="list-style-type: none; padding: 0; margin: 0; display: flex; flex-wrap: wrap;font-size: 16px;font-family: \'Draft\', sans-serif;">'
                 dt.outstanding.forEach(function (item) {
                     htmloutsanding += '<li style="flex: 0 0 50%; box-sizing: border-box; padding: 0 10px;">';
                     if(credit_days <= item.age){
                         htmloutsanding += '#' + "&nbsp&nbsp";
                     }else{
                         htmloutsanding += ' ' + "&nbsp&nbsp&nbsp&nbsp";
                     }
                    
                     htmloutsanding += item.trans_date + "&nbsp&nbsp";
                     htmloutsanding += item.external_number + "&nbsp&nbsp;"; // Add &nbsp; here
                     htmloutsanding += item.age + "Days" + "&nbsp&nbsp;"; // Add &nbsp; here
                     htmloutsanding += parseFloat(item.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + "&nbsp&nbsp;"; // Add &nbsp; here
                     htmloutsanding += '</li>';
                 });
                 
                 htmloutsanding += '</ul>'
                 htmloutsanding += '</td>'
                 htmloutsanding += '</tr>'
                 htmloutsanding += '</table>'
            }
           
            
         
            var htmlpayment = '';
            htmlpayment += "<h4>Payment Details</h4>"
            htmlpayment += '<table>';
            htmlpayment += '<tr><td style="font-size: 20px;">ACCOUNT NAME - Ragama Pharmacy / BANK - SAMPATH BANK / A/C - 1073 1500 0849</td></tr>';
            htmlpayment += '<tr><td>Please be informed that we will not accept responsibility if a deposit is made to any account number other thaan the one specified</td></tr>'
            htmlpayment += '</table>';
           
            var footerContent = '<table border="0" style="width: 100%;">';
            footerContent += '<br><br><br><br>';
            footerContent += '</table>';

            footerContent += '<table border="0" style="width: 100%;font-size:20px;">';
            footerContent += '<tr><th colspan="2" style="text-align: center;">.....................</th><th colspan="2" style="text-align: center;">..................... </th><th colspan="2" style="text-align: center;">..................... </th></tr>'
            footerContent += '<tr><th colspan="2" style="text-align: center;"><b>Invoiced By</b></th><th colspan="2" style="text-align: center;"><b>Authorized By </b></th><th colspan="2" style="text-align: center;"><b>Customer Signature </b></th></tr>'
            footerContent += '<tr><th colspan="2" style="text-align: center;"></th><th colspan="2" style="text-align: center;"> </th><th colspan="2" style="text-align: center;"> (rubber seal) </th></tr>'
            footerContent += '</table>';

            var serverDateTime = new Date().toLocaleString();
            footerContent += '<br><br><br>';
            footerContent += '<table border="0" style="width: 100%; ">';

           
           
            footerContent += '</table>';
           
            var footerDate = '<table style="width: 100%; text-align: center; border: 0;font-size:20px;"><tr><td colspan="4" style="border: 0;">Printed On ' + headerData[0].current_date_time + '</td></tr></table>';

            var combinedContent = htmlContent + htmloutsanding + htmlpayment + footerContent + footerDate;
            console.log(combinedContent);
            iframe.srcdoc = combinedContent;

           

        },
        error: function (xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function salesinvoiceReportsd(requestID) {

    var iframe = document.getElementById('pdfContainer');
    if (iframe.contentWindow) {
        var ob = iframe.contentWindow.print();

        let called = false;
        function callAjaxOnce() {
            if (!called) {
                report = 1;
                $.ajax({
                    url: '/sd/printsalesinvoicePdf/' + requestID + '/' + report,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        called = true;
                        url = "/sd/salesInvoiceList";
                        window.location.href = url;
                        salesinvoiceReportiframe(requestID);
                    }
                });
            }
        }

        setTimeout(callAjaxOnce, 1000);
    }
}