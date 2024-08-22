$(document).ready(function(){
    loadOrderData();

    var intervalId = setInterval(loadOrderData, 3 * 60 * 1000);

    
    $(window).on('unload', function() {
        clearInterval(intervalId);
    });
});

//load data
function loadOrderData(){
    $.ajax({
        type:"Get",
        url:"/loadOrderData",
        
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            var order_count = response.order_count;
            var pending_to_deliver = response.pending_to_deliver;
            var late_orders = response.late_orders;
            var missed_orders = response.missed_order;
            $('#lbl_order_count').text(order_count[0].count);
            //$('#order_val').text("Rs."+order_count[0].order_total_amount+"/=");
            var orderamount = order_count[0].order_total_amount !== null ? "Rs." + order_count[0].order_total_amount + "/=" : "";
            $('#order_val').text(orderamount);
            

            $('#lbl_pending_to_deliver').text(pending_to_deliver[0].count);
           // $('#pending_val').text("Rs."+pending_to_deliver[0].pending_total_amount+"/=");
var pendingamount=pending_to_deliver[0].pending_total_amount !== null ? "Rs."+pending_to_deliver[0].pending_total_amount+"/=": "";
$('#pending_val').text(pendingamount);

            $('#lbl_late_orders').text(late_orders[0].count);
           // $('#late_val').text("Rs."+late_orders[0].late_total_amount+"/=");
var lateamount = late_orders[0].late_total_amount !== null ? "Rs."+late_orders[0].late_total_amount+"/=" : "";
$('#late_val').text(lateamount);

            $('#lbl_missed_orders').text(missed_orders[0].count);
            
            
        },
        error: function (error) {
           
        },
        complete: function () {

        }

    })
}