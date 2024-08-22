




var formData = new FormData();
$(document).ready(function () {


    $('#frmCustomer').submit(function (e) {
        e.preventDefault();
        //  dropzoneSingle.processQueue();
    });




    load_blocked_orders();
    $('#tabs').on('click', 'a.nav-link', function (e) {
        e.preventDefault();
        var anchorHref = $(this).attr('href');
        var activeDiv = $('#tab_content').find('.active');
        if (activeDiv.length > 0) {
            activeDiv.removeClass('active');
        }
        var correspondingDiv = $('#tab_content').find('#' + anchorHref);
        if (correspondingDiv.length > 0) {

            correspondingDiv.addClass('active');
        }

        $(this).tab('show');

    });

});


function load_blocked_orders() {
    $('#tab_container').empty();
    $.ajax({
        url: '/sd/load_blocked_orders',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            var dt = data.data;
            var count = data.count;
            $.each(count, function (index, result) {
                createCardComponent(index, result,dt);
            });


        },
        error: function (error) {
            console.error('Error fetching data:', error);
        }
    });


}


function createCardComponent(record_no, result,dt) {

    var order_id = result.sales_order_Id;
    var external_number = result.external_number;
    var branchName = result.branch_name;
    var branch_id = result.branch_id;
    var record_count = 0;
    console.log(dt.length);

    for(var i = 0; i < dt.length; i++){
        if(branch_id == dt[i].branch_id){
            record_count++;
        }
    }

    // Create HTML for the card dynamically
    var tabs = undefined;
    
    if (record_no == 0) {
        tabs = `<li class="nav-item rbs-nav-item" onclick="TableRefresh()">
        <a href="${branch_id}" class="nav-link active" aria-selected="true">
            ${branchName}
            &nbsp<span class="badge bg-yellow text-white translate-middle-middle  rounded-pill" style="padding:4px;background-color: #E02D2D !important;">${record_count}</span>
        </a>
    </li>`;



        //card body
        var body = `<div class="tab-pane fade show active" id="${branch_id}" role="tabpanel">
<div class="row">
        <h4>${branchName}</h4>
    <div class="col-md-12">
        <table class="table datatable-fixed-both table-striped" id="sales_oderTable_${branch_id}">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Town</th>
                    <th>Sales Rep</th>
                    
                    <th>Deliver on</th>
                    <th style="text-align: right;">Amount</th>
                    <th>Action</th>
                   
                   
                </tr>
            </thead>
            <tbody>


            </tbody>
        </table>
    </div>

</div>
</div>`;


    } else {
        tabs = `<li class="nav-item rbs-nav-item" onclick="TableRefresh()">
        <a href="${branch_id}" class="nav-link" aria-selected="true">
            ${branchName}
            &nbsp<span class="badge bg-yellow text-white translate-middle-middle  rounded-pill" style="padding:4px;background-color: #E02D2D !important;">${record_count}</span>
        </a>
    </li>`;


        //card body
        var body = `<div class="tab-pane fade show" id="${branch_id}" role="tabpanel">
<div class="row">
    <h4>${branchName}</h4>
    <div class="col-md-12">
        <table class="table datatable-fixed-both table-striped" id="sales_oderTable_${branch_id}">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Route</th>
                    <th>Town</th>
                    <th>Sales Rep</th>
                    
                    <th>Deliver on</th>
                    <th style="text-align: right;">Amount</th>
                    <th>Action</th>       
                </tr>
            </thead>
            <tbody>


            </tbody>
        </table>
    </div>

</div>
</div>`;
    }

    // Append the dynamically created card to a container
    $('#tabs').append(tabs);
    $('#tab_content').append(body);
    init_data_table(branch_id,dt)

}



//init data table
function init_data_table(table_id,result) {
    var t_id = "#sales_oderTable_" + table_id;
    console.log(t_id);

    const DatatableFixedColumns = function () {
       
        const _componentDatatableFixedColumns = function () {
            if (!$().DataTable) {
                console.warn('Warning - datatables.min.js is not loaded.');
                return;
            }

            
            $.extend($.fn.dataTable.defaults, {
                columnDefs: [
                    {
                        orderable: false,
                        width: 100,
                        targets: [2]
                    }
                ],
                dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                    searchPlaceholder: 'Press enter to filter',
                    lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
                }
            });

            // Left and right fixed columns
            var currentTable = $(t_id).DataTable({
                "createdRow": function (row, data, dataIndex) {
                    $(row).css("height", "55px");
                },
                columnDefs: [
                    {
                        orderable: false,
                        width: 70,
                        targets: 7,
                        render: function (data, type, row, meta) {
                            return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                        }
                    },
                    {
                        width: 50,
                        targets: 0,
                        
                    },
                    {
                        width: 50,
                        targets: 1
                    },
                    {
                        orderable: false,
                        width: 400,
                        targets: 2
                    },
                    {
                        orderable: false,
                        width: 150,
                        targets: 3
                    },
                    {
                        orderable: false,
                        width: 80,
                        targets: 4
                    }
                ],
                scrollX: true,
                scrollCollapse: true,
                "info": false,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 0
                },
                "pageLength": 100,
                "order": [],
                "columns": [
                    { "data": "external_number" },
                    { "data": "order_date_time" },
                    { "data": "customer_name" },
                    { "data": "route" },
                    { "data": "townName" },
                    { "data": "employee_name" },
                    
                    { "data": "expected_date_time" },
                    { "data": "total_amount" },
                    { "data": "action" }
                ],
                "stripeClasses": ['odd-row', 'even-row'],
            });

            return currentTable; // Return the current DataTable instance
        };

        return {
            init: function () {
                _componentDatatableFixedColumns();
            }
        }
    }();

    // Initialize module
    DatatableFixedColumns.init();
    
    appendData(t_id,result,table_id)
    TableRefresh();
}
//block_rqst
function block_rqst(empid,cus_id_,order_id,button){
    bootbox.confirm({
        title: 'Save confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i id="question-icon" class="fa fa-question fa-5x text-warning animate-question"></i></div><div class="d-flex justify-content-center align-items-center"><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-warning'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-link'
            }
        },
        callback: function (result) {
            //console.log('Confirmation result:', result);
            if (result) {
                block_(empid,cus_id_,order_id,button)
            } else {

            }
        },
        onShow: function () {
            $('#question-icon').addClass('swipe-question');
        },
        onHide: function () {
            $('#question-icon').removeClass('swipe-question');
        }
    });

    $('.bootbox').find('.modal-header').addClass('bg-warning text-white');
}

//block order
function block_(empid,cus_id_,order_id,button) {
    var block_status = false;
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
          var blk_id = response.block_id;
          console.log(blk_id);
          if(block_status){
            if(parseInt(blk_id) > 0){
                update_order_block_status(order_id,button,blk_id);
            }
          }
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
           
        }
       
    });
    //return block_status;

}

//update order block status in header to disbale the unblock button
function update_order_block_status(id,button,blockID){
    var formData = new FormData();
    var order_ids = [];
    var row = $(button).closest('tr');
    var cus_id = row.find('td:eq(2) div').attr('data-id');

    var table = row.closest('table');
    var matchingRows = $('table tr:has(td:eq(2) div[data-id="'+cus_id+'"])');

    matchingRows.each(function() {
        // Find the div in the first cell of the current row
        var divInCell0 = $(this).find('td:first-child div');
    
        // Retrieve the data-id value from the div and push it into the array
        order_ids.push(divInCell0.attr('data-id'));
    });
formData.append('order_ids',JSON.stringify(order_ids));
console.log(order_ids);
    $.ajax({
        url: '/sd/update_order_block_status/'+id+'/'+blockID,
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
           if(status){
                showSuccessMessage("Request Sent");
                $(button).prop('disabled',true);
                location.reload();
                
                
           }else{
                showWarningMessage("Unable to send the request");
           }
           

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {
           
        }
       
    });
}




//append data to the table
function appendData(table,result,branch_id){ //table_id = branch_id
    var data = [];
    for (var i = 0; i < result.length; i++) {
        var order_id = result[i].sales_order_Id;
    var external_number = result[i].external_number;
    var branchName = result[i].branch_name;
    var townName = result[i].townName;
    var employee_name = result[i].employee_name;
    var customer_name = result[i].customer_name;
    var order_type = result[i].order_type;
    var deliver_on = result[i].expected_date_time;
    var amount = parseFloat(result[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, });
    var order_Date = result[i].order_date_time;
    var emp_id = result[i].employee_id;
    var cus_id = result[i].customer_id;
    var block_request_sent = result[i].block_request_sent;
    var route_name = result[i].route_name;
    var button = '<input type="button" value="Unblock" class="btn btn-success" onclick="block_rqst(\'' + emp_id + '\', \'' + cus_id + '\', \'' + order_id + '\', this)">';
    if(block_request_sent == 1){
         button = '<input type="button" value="Unblock" class="btn btn-success" onclick="block_rqst(\'' + emp_id + '\', \'' + cus_id + '\', \'' + order_id + '\', this)" disabled>';
    }
    
    if(result[i].branch_id == branch_id){
        data.push({
            "external_number": '<div data-id="'+order_id+'">'+external_number+'</div>',
            "order_date_time": order_Date,
            "customer_name": '<div data-id="'+cus_id+'" title="'+customer_name+'">'+shortenString(customer_name,27)+'</div>',
            "route":'<div title="'+route_name+'">'+shortenString(route_name,10)+'</div>',
            "townName": '<div title="'+townName+'">'+shortenString(townName,10)+'</div>',
           
            "employee_name": employee_name,
            
            "expected_date_time": deliver_on,
            "total_amount": amount,
            "action":button
           
        });
    }
   
        
    }
    
    var table = $(table).DataTable();
    table.clear();
    table.rows.add(data).draw();

   
}


function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}

function TableRefresh() {
    var table = $('.datatable-fixed-both').DataTable();
    table.columns.adjust().draw();
}
