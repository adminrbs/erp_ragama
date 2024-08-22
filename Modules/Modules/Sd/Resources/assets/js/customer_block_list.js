/* ----------data table---------------- */
const DatatableFixedColumns = function () {

    // Basic Datatable examples
    const _componentDatatableFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [2]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }

        });

        // Left and right fixed columns
        $('.datatable-fixed-both').DataTable({
            "createdRow": function (row, data, dataIndex) {
                $(row).css("height", "55px");
            },
            columnDefs: [
                {
                    orderable: false,
                    width: 100,
                    targets: 2
                },
                {
                    orderable: false,
                    width: 400,
                    targets: 0
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 1
                },

                {
                    orderable: false,
                    width: 150,
                    targets: 3
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 4,
                    render: function (data, type, row, meta) {
                        return type === 'display' ? '<div class="text-right">' + data + '</div>' : data;
                    }
                },
                {
                    orderable: false,
                    width: 80,
                    targets: 5
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 6
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 7
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 8
                },

            ],
            scrollX: true,
            /*  scrollY: 600, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "customer_name" },
                { "data": "town" },
                { "data": "route_name" },
                { "data": "employee_name" },
                { "data": "value" },
                { "data": "status" },

                { "data": "info" },
                { "data": "orders" },
                { "data": "action" },


            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });

    };

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();

// Initialize module
document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});

$(document).ready(function () {
    $('.select2').select2();

    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#cmbBranch').on('change',function(){
        loadOutstandingDataToTable($('#hidden_cus_lbl').val(),$('#cmbBranch').val());
    });

    getBranches();
    loademployees();
    $('#cmbEmp').change();
    loadCustomerBlockList(0);

    $('#cmbEmp').on('change', function () {
        loadCustomerBlockList($(this).val());
    })

    $('#btnRelease').on('click', function () {
        block_release($('#hiddnLBLforID').val(), 0);
    });

    $('#btnBlock').on('click', function () {
        block_release($('#hiddnLBLforID').val(), 1);
    });


    $('#txtRemark, #txtnumOfOrders, #txtValue').on('input', function () {
        $(this).removeClass('is-invalid').addClass('is-valid');
    });

    $('#block_customer_model').on('hidden.bs.modal', function () {
        $('#txtRemark, #txtnumOfOrders, #txtValue').val('');
    });

 
    $('#orderModel').on('shown.bs.modal',function(){
       var bl_id =  $('#hiddenItem').val();
        load_block_order_info(bl_id);
        
    });


});

//load customer block list
function loadCustomerBlockList(id) {
    $.ajax({
        type: "GET",
        url: "/sd/loadCustomerBlockList/" + id,
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            console.log(response);
            var dt = response.data;

            var data = [];
            var disabled = "";

            for (var i = 0; i < dt.length; i++) {
                var value = dt[i].total;
                if(value == null){
                    value = '-'
                }
                label = '<label class="badge badge-pill bg-Danger" data-id="' + dt[i].is_blocked + '">Blocked</label>';
                if (dt[i].is_blocked == 0) {
                    label = '<label class="badge badge-pill bg-success" data-id="' + dt[i].is_blocked + '">Released</label>';
                }
                btnRelease = '<button class="btn btn-success btn-sm tooltip-target" title="Release" onclick="release_model(' + dt[i].customer_block_id + ',this)"><i class="fa fa-tasks" aria-hidden="true"></i></button>';
                btn_info = '<button class="btn btn-success btn-sm tooltip-target" title="Info" onclick="showInfoModel(' + dt[i].customer_block_id + ','+dt[i].customer_id+')"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
            orders = '<button class="btn btn-success btn-sm tooltip-target" title="Orders" onclick="showOrderInfoModel(' + dt[i].customer_block_id + ')"><i class="fa fa-info-circle" aria-hidden="true"></i></button>';
                data.push({
                    "customer_name": '<div data-id="' + dt[i].customer_id + '">' + dt[i].customer_name + '</div>',
                    "town":dt[i].townName,
                    "route_name":'<div title="'+dt[i].route_name+'">'+shortenString(dt[i].route_name,10)+'</div>',
                    "employee_name": dt[i].employee_name,
                    "value":value,
                    "status": label,
                    "info": btn_info,
                    "orders": orders,
                    "action": btnRelease
                });

            }

            var table = $('#customer_block_list_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    });
}

//load collectors
function loademployees() {
    $.ajax({
        url: '/sd/loademployees',
        type: 'get',
        dataType: 'json',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbEmp').append('<option value="' + value.employee_id + '">' + value.employee_name + '</option>');

            })

        },
        error: function (error) {
            console.log(error);
        },

    });
}

//show relase / block model
function release_model(id, event) {
    var note = undefined;
    var remark = undefined;
    var numOreders = undefined;
    var value_ = undefined;
    //customer id
    var row = $(event).closest('tr');
    var cus_id = row.find('td:eq(0)').find('div').data('id');
    //block status

    var block_status_ = row.find('td:eq(5)').find('label').data('id');


    $.ajax({
        url: '/sd/get_customer_remark/' + cus_id + '/' + id,
        method: 'get',
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
            console.log(response);
            $.each(response, function (index, value) {
                note = value.note;
                remark = value.remark;
                numOreders = value.number_of_rders;
                value_ = value.value;
            });



        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    });

    $('#block_customer_model').modal('show');
    $('#hiddnLBLforID').val(id);
    $('#hiddnLBLforblockStatus').val(block_status_);
    //alert(block_status_);
    $('#customer_remark').text(note);
    if ($('#hiddnLBLforblockStatus').val() == 1) {
        $('#btnBlock').hide();
        $('#btnRelease').show();
        $('#txtRemark, #txtnumOfOrders, #txtValue').prop('disabled', false);
    } else if ($('#hiddnLBLforblockStatus').val() == 0) {
        $('#btnBlock').show();
        $('#btnRelease').hide();
        $('#txtRemark, #txtnumOfOrders, #txtValue').prop('disabled', true);
        $('#txtRemark').val(remark);
        $('#txtnumOfOrders').val(numOreders);
        $('#txtValue').val(value_);
    }

}

//get customer remark
function block_release(id, action) {
    if (action == 0) {
        if ($('#txtRemark').val().length < 1) {
            showWarningMessage('Remark is required');
            $('#txtRemark').addClass('is-invalid');
            return;
        } else if ($('#txtnumOfOrders').val().length < 1) {
            showWarningMessage('Number of order is required');
            $('#txtnumOfOrders').addClass('is-invalid');
            return;
        } else if ($('#txtValue').val().length < 1) {
            showWarningMessage('Value is required');
            $('#txtValue').addClass('is-invalid');
            return;
        }
    }
    var formData = new FormData();
    formData.append('txtRemark', $('#txtRemark').val());
    formData.append('txtnumOfOrders', $('#txtnumOfOrders').val());
    formData.append('txtValue', $('#txtValue').val());
    formData.append('customer_remark', $('#customer_remark').text());
    $.ajax({
        url: '/sd/block_release/' + id + '/' + action,
        method: 'post',
        enctype: 'multipart/form-data',
        data: formData,
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
            if (response.status) {
                showSuccessMessage("Successfully saved");
                $('#block_customer_model').modal('hide');
                loadCustomerBlockList($('#cmbEmp').val());


            } else {
                showWarningMessage("Unable to update");

            }


        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    });

}


function showInfoModel(id,cus) {
    $('#block_id_hidden_lbl').val(id);
    $('#hidden_cus_lbl').val(cus);
    $('#block_customer_model_info').modal('show');
    load_block_info(cus); // past id
    loadOutstandingDataToTable(cus,$('#cmbBranch').val());
   
}

function showOrderInfoModel(id) {
    $('#hiddenItem').val(id);
    $('#orderModel').modal('show');
   // load_block_order_info(id);
   

}

//load info model
function load_block_info(id){
    $.ajax({
        url: '/sd/load_block_info/' + id,
        method: 'get',
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
            var dt = response.data;
            var header_data = dt[0];
            var no_of_blocked = dt[1];
            var dis_chq = dt[2];
            var avg = dt[3];
            var lst_inv_date = dt[4];
            var lst_data = dt[5];
            var cus_outsanding = dt[6];
            var rep_outstanding =dt[7];
            /* console.log(rep_outstanding[0].rep_oustanding); */
            
            

            
            
            
            //header
            for(var i = 0; i<header_data.length;i++){
            
                
                    
                   $('#lbl_cus_name').text(header_data[i].customer_name);
                   $('#lbl_sales_rep_name').text(header_data[i].employee_name);
                   $('#lbl_cus_code').text(header_data[i].customer_code);

                   //Credit limit ( Alert )
                   
                   
                   if(header_data[i].credit_amount_alert_limit){
                    $('#lbl_cus_cl_st').text(parseFloat(header_data[i].credit_amount_alert_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_cus_cl_st').text('');
                   }

                   if(cus_outsanding[0].cus_oustanding){
                    $('#lbl_cus_cl_vl').text(parseFloat(cus_outsanding[0].cus_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_cus_cl_vl').text('-');
                   }
                   
                   if(header_data[i].e_credit_amount_alert_limit){
                    $('#lbl_sr_cl_st').text(parseFloat(header_data[i].e_credit_amount_alert_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_sr_cl_st').text('');
                   }

                   if(rep_outstanding[0].rep_oustanding){
                    $('#lbl_sr_cl_vl').text(parseFloat(rep_outstanding[0].rep_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_sr_cl_vl').text('');
                   }


                   /* parseFloat(dt[i].total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }), */
                   

                  
                   

                   //Credit preiod ( Altert )
                   if(header_data[i].credit_period_alert_limit){
                    $('#lbl_cus_cp_st').text(parseFloat(header_data[i].credit_period_alert_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_cus_cp_st').text('');
                   }
                  
                   if(cus_outsanding[0].cus_oustanding){
                    $('#lbl_cus_cp_vl').text(parseFloat(cus_outsanding[0].cus_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_cus_cp_vl').text('');
                   }
                   

                   if(header_data[i].e_credit_period_alert_limit){
                    $('#lbl_sr_cp_st').text(parseFloat(header_data[i].e_credit_period_alert_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_sr_cp_st').text('');
                   }
                   
                   if(rep_outstanding[0].rep_oustanding){
                    $('#lbl_sr_cp_vl').text(parseFloat(rep_outstanding[0].rep_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                   }else{
                    $('#lbl_sr_cp_vl').text('');
                   }
                   


                  //Credit limit ( Hold )
                  if(header_data[i].credit_amount_hold_limit){
                    $('#lbl_cus_cl_hold_st').text(parseFloat(header_data[i].credit_amount_hold_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_cus_cl_hold_st').text('');
                  }
                  
                  if(cus_outsanding[0].cus_oustanding){
                    $('#lbl_cus_cl_hold_vl').text(parseFloat(cus_outsanding[0].cus_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_cus_cl_hold_vl').text('');
                  }
                  

                  if(header_data[i].e_credit_amount_hold_limit){
                    $('#lbl_sr_cl_hold_st').text(parseFloat(header_data[i].e_credit_amount_hold_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_sr_cl_hold_st').text('');
                  }
                  
                  if(rep_outstanding[0].rep_oustanding){
                    $('#lbl_sr_cl_hold_vl').text(parseFloat(rep_outstanding[0].rep_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_sr_cl_hold_vl').text('');
                }
                  


                  //Credit preiod ( Hold )
                  if(header_data[i].credit_period_hold_limit){
                    $('#lbl_cus_cp_hold_st').text(parseFloat(header_data[i].credit_period_hold_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_cus_cp_hold_st').text('');
                  }
                  
                  if(cus_outsanding[0].cus_oustanding){
                    $('#lbl_cus_cp_hold_vl').text(parseFloat(cus_outsanding[0].cus_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_cus_cp_hold_vl').text('');
                  }
                  

                  if(header_data[i].e_credit_period_hold_limit){
                    $('#lbl_sr_cp_hold_st').text(parseFloat(header_data[i].e_credit_period_hold_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_sr_cp_hold_st').text('');
                  }

                  if(rep_outstanding[0].rep_oustanding){
                    $('#lbl_sr_cp_hold_vl').text(parseFloat(rep_outstanding[0].rep_oustanding).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_sr_cp_hold_vl').text('');
                }
                  
                  


                  //PD cheque period
                  if(header_data[i].pd_cheque_max_period){
                    $('#lbl_cus_pd_pr_st').text(parseFloat(header_data[i].pd_cheque_max_period).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_cus_pd_pr_st').text('');
                  }
                  
                  /* $('#lbl_cus_pd_pr_vl').text(header_data[i][j].pd_cheque_max_period); */

                  if(header_data[i].e_pd_cheque_max_period){
                    $('#lbl_sr_pd_pr_st').text(parseFloat(header_data[i].e_pd_cheque_max_period).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_sr_pd_pr_st').text('');
                  }
                  
                  /* $('#lbl_sr_pd_pr_vl').text(header_data[i][j].e_pd_cheque_max_period); */


                  //PD cheque amount
                  if(header_data[i].pd_cheque_limit){
                    $('#lbl_cus_pd_am_st').text(parseFloat(header_data[i].pd_cheque_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_cus_pd_am_st').text('');
                  }
                  
                  /* $('#lbl_cus_pd_am_vl').text(header_data[i][j].pd_cheque_limit); */

                  if(header_data[i].e_pd_cheque_limit){
                    $('#lbl_sr_pd_am_st').text(parseFloat(header_data[i].e_pd_cheque_limit).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                  }else{
                    $('#lbl_sr_pd_am_st').text('');
                  }
                  
                  /* $('#lbl_sr_pd_am_st').text(header_data[i][j].e_pd_cheque_limit); */

                

                
            }

            //credit score
            
            //no of blocked
            $('#lbl_no_of_blocks').text(no_of_blocked[0].number_of_blocks);

            //no of cheque returned
            $('#lbl_no_of_chq_rtn').text(dis_chq[0].no_of_chqs_returned);

            //nonpaid
            $('#lbl_no_of_rtn_chq_non_paid').text(parseFloat(avg[0].nonpaid).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            //avg
            if(lst_inv_date[0].average_value){
                $('#lbl_avgsales_last_three_months').text(parseFloat(lst_inv_date[0].average_value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }else{
                $('#lbl_avgsales_last_three_months').text('');
            }
            

            //last invoice date
            $('#lbl_last_invoice_date').text(lst_data[0].latest_order_date_time);

            //last receipt date
            $('#lbl_last_rcpt_date').text(lst_data[0].latest_recpt_date);

            //last recpt
            $('#lbl_last_rcpt').text(lst_data[0].latest_external_number);




        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    });


}

function load_block_order_info(id){
    $('body').css('cursor', 'wait');
    var table = $('#orders_table');
    var tableBody = $('#orders_table tbody');
    tableBody.empty();
    $.ajax({
        url: '/sd/load_block_order_info/' + id,
        method: 'get',
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
            console.log(response);
            var dt = response.data;
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').text(item.external_number));
                row.append($('<td>').text(item.order_date_time));
                row.append($('<td>').text(item.employee_name));
                row.append($('<td>').text(parseFloat(item.total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                table.append(row);
            });
            $('body').css('cursor', 'default');



        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    });
}

function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}

function loadOutstandingDataToTable(id,br){
    var table = $('#outstandingTable');
    var tableBody = $('#outstandingTable tbody');
    tableBody.empty();
    $.ajax({
        url: '/sd/loadOutstandingDataToTable/' + id +'/' + br,
        method: 'get',
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
            console.log(response);
            var dt = response.data;
            $.each(dt, function (index, item) {
                var row = $('<tr>');
                row.append($('<td>').text(item.trans_date));
                row.append($('<td>').text(item.external_number));
                row.append($('<td>').text(parseFloat(item.amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })));
                row.append($('<td>').text(item.age));      
                table.append(row);
            });
            $('body').css('cursor', 'default');



        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })

}


//loading branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            if(data.length > 1){
                $('#cmbBranch').append('<option value="0">Any</option>');
            }
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })
            $('#cmbBranch').change();
        },
    })
}