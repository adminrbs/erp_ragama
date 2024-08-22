
$(document).ready(function(){
    

});

//check block status and insert block record
function checkBlockStatus(empid,cus_id_,order){
    var block_status = false;
    var order_id = order;
    
    $.ajax({
        url: '/sd/checkBlockStatus/'+empid+'/'+cus_id_,
        method: 'post',
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            
        }, success: function (response) {
          block_status = response.status;
          var blk_id_ = response.block_id;
          if(block_status){
            updateSalesOrderBlickID(order_id,blk_id_);
          }
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
           
        }
       
    });
    return block_status;
}


//update sales order block id
function updateSalesOrderBlickID(order_id,bl_id){
    var order_ids = [];
    var formData = new FormData();
    formData.append('order_ids',JSON.stringify(order_ids));
;
    $.ajax({
        url: '/sd/update_order_block_status/'+order_id+'/'+bl_id,
        method: 'post',
        enctype: 'multipart/form-data',
        data:formData,
        processData: false,
        contentType: false,
        cache: false,
        async: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            
        }, success: function (response) {
            var status = response.success;
           /* if(status){
                showSuccessMessage("Request Sent");
                $(button).prop('disabled',true);
                location.reload();
                
                
           } */
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
           
        }
       
    });
}