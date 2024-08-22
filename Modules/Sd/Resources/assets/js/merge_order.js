var referanceID;
var action;
$(document).ready(function () {
    $('#cmbBranch').on('change', function () {
        load_duplicate_orders($(this).val());
    });
    getBranches();

});

//load duplicated orders
function load_duplicate_orders(branch_id) {
    $('#colapse_container').empty();
    $.ajax({
        url: '/sd/load_duplicate_orders/' + branch_id,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var dt = data.data;
            $.each(dt, function (index, result) {
                createCardComponent(result);
            });
        },
        error: function (error) {
            console.error('Error fetching data:', error);
        }
    });
}

function createCardComponent(result) {

    var code = result.customer_code;
    var rep = result.employee_name;
    var route = result.route_name;
    var num = result.order_count;
    var id = result.code;
    var primary_key = result.customer_id+'_'+result.employee_id;
    var merge = '<button class="btn btn-success" id="'+primary_key+'" onclick="external_number_merger_order_save(this)">Merge</button>';
   
    console.log(primary_key);

    // Create HTML for the card dynamically
    var cardHtml = `
    <div class="card" id="">
    <div class="card-header" id="headingDesignation">
        <h5 class="mb-0">
            <button id="" class="btn btn-link" data-bs-toggle="collapse" href="#item_${id}" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="loadOrders('${primary_key}')">
                <table style="width:100%">
                    <tr>
                        <td style="width:500px";>
                            <div style="min-width: 120px; margin-right: 10px;">Customer: ${code}</div>
                        </td>
                        <td style="width:350px;";>
                            <div style="min-width: 120px; margin-right: 10px;">Rep: ${rep}</div>
                         </td>
                        <td style="width:200px";>
                            <div style="min-width: 120px; margin-right: 10px;">Route: ${route}</div>
                        </td>
                        <td style="width:50px";>
                             <div style="min-width: 120px;">Orders: ${num}</div>
                        </td>
                        <td style="width: 50px;">
                            <div style="min-width: 120px;">${merge}</div>
                        </td>
                    
                    </tr>
                
                </table>    
            
            
                 <div style="display: flex;">
                    
                </div>
            </button>
        </h5>
    </div>
    <div id="item_${id}" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
        <div class="card-body">
            <div>
            <table id="table_${primary_key}" style="width:50%" class="table" name="order_table">
            <thead>        
            <tr>
                        <th style="width: 5px;">
                            <input type="checkbox" id="selectAll_${primary_key}" name="selectAll" class="form-check-input" onchange="selectAll(this)">
                        </th>
                        <th width:50px;>Reference</th>
                        <th width:100px>Amount</th>
                        <th width:10px>Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
               </table>
            </div>
            <div class="table-responsive">
                <!-- Additional content here... -->
            </div>
        </div>
    </div>
</div>
`

    // Append the dynamically created card to a container
    $('#colapse_container').append(cardHtml);
}

//load orders
function loadOrders(id){
    var branch = $('#cmbBranch').val();
    var table = $('#table_' + id);
    var tbody = table.find('tbody');
    tbody.empty();
    
    $.ajax({
        url: '/sd/loadOrders/'+branch+'/'+id,
        type: 'get',
        async: false,
        success: function (data) {
            var dt = data.data
            var table = $('#table_' + id);
            
            
            $.each(dt, function (index, item) {
            var row = $('<tr>');
            row.append(
                $('<td>').append(
                    $('<div>').addClass('form-check').append(
                        $('<input>').addClass('form-check-input')
                                     .attr('type', 'checkbox')
                                     .attr('id',item.sales_order_Id)
                                     .val(item.sales_order_Id)
                                     .prop('checked', false)
                                     .on('change', function() {
                                        selectRecord(this)
                                     })
                    )
                )
                
            );
            row.append($('<td>').append($('<label>').attr('data-id', item.sales_order_Id).text(item.external_number)));
            row.append($('<td>').css('width', '150px').append('<div>' + item.total_amount + '</div>'));
            var button = $('<button>').addClass('btn btn-success btn-sm')
    .click(function() {
        view(item.sales_order_Id);
    })
    .append($('<i>').addClass('fa fa-eye').attr('aria-hidden', 'true'));

row.append($('<td>').append(button));
            /* tbody.find('td').empty();
            tbody.empty(); */
            table.append(row);
            })

        },
    })
}


//view
function view(id) {
    var status = "Original"
    url = "/sd/salesOrderview?id=" + id + "&paramS=" + status + "&action=view" + "&task=null";
    window.open(url, '_blank');
}
//select all
function selectAll(event){
   
    var headerCheckbox = $(event)
    var table = $(event).closest('table');
    var checkboxes = table.find('tbody input[type="checkbox"]');
    if($(event).prop('checked')){
        checkboxes.prop('checked',true)
    }else{
        checkboxes.prop('checked',false) 
    }
}

//select record
function selectRecord(event) {
    var table = $(event).closest('table');
    var header = table.find('thead input[type="checkbox"]');
    
    if (!$(event).prop('checked')) {
        console.log(header);
        header.prop('checked', false);
    } else {
        // Your logic for the case where the checkbox is checked
    }
}

//to call both functions
function external_number_merger_order_save(event){

    

    newReferanceID('sales_orders', '200');
    merger_order_save(event)
    
   
}

//merger order
function merger_order_save(event){
    var branch = $('#cmbBranch').val();
    var btn_id = event.id;
    var table_id = "table_"+btn_id;
    var table = $("#" + table_id);

    var checkedCheckboxes = [];
    table.find('tbody td:first-child :checkbox:checked').each(function () {
       
        checkedCheckboxes.push(this.id);
    });

   
    $.ajax({
        url: '/sd/merger_order_save',
        type: 'post',
        async: false,
        data:{
           checkedCheckboxes:checkedCheckboxes,
           referanceID:referanceID,
           branch_id:branch
        },
            cache: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
                //$('#btnSave').prop('disabled', true);
            },
            success: function (data) {
                var status = data.status;
                var msg = data.message;
                var extr = data.order;
                if(msg == "used"){
                    showWarningMessage("Unable to merge")
                }else if(status){
                    showSuccessMessage('Successfully merged :'+extr);
                    load_duplicate_orders(branch);
               }else{
                    showWarningMessage('Unable to merge');
               }
    
    
            },
    })
    
    
}


//load branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })

            $('#cmbBranch').trigger('change')

        },
    })
}


function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_SalesOrder", table, doc_number);
   
}