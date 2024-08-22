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
         table = $('#sales_return_transfer_table').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    width: 100,
                    targets: 1
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 0
                },
                {
                    orderable: false,
                    width: 180,
                    targets: 3
                },
                {
                    orderable: false,
                    width: 380,
                    targets: 2
                },
                {
                    orderable: false,
                    width: 50,
                    targets: 4
                },
                {
                    orderable: false,
                    width: 50,
                    targets: 5
                },
                {
                    orderable: false,
                    width: 70,
                    targets: 6
                },
                {
                    orderable: false,
                    width: 20,
                    targets: 7
                },
                {
                    orderable: false,
                    width: 170,
                    targets: 8
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 3
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "rtn_date" },
                { "data": "reference" },
                { "data": "customer" },
                { "data": "item_name" },
                { "data": "pack_size" },
                { "data": "total_qty" },
                { "data": "transfer_qty" },
                { "data": "chk" },
                { "data": "remark" }

            ],
            "stripeClasses": ['odd-row', 'even-row'],

        });


        // Left and right fixed columns
        var table_two = $('#get_table').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    width: 100,
                    targets: 0
                },
                {
                    orderable: false,
                    width: 100,
                    targets: 1
                },
                {
                    orderable: false,
                    width: 380,
                    targets: 2
                },
                
                {
                    orderable: false,
                    width: 70,
                    targets: 3
                },
                {
                    orderable: false,
                    width: 200,
                    targets: 4
                },
               
                {
                    orderable: false,
                    width: 40,
                    targets: 5
                },
                {
                    orderable: false,
                    width: 100,
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
            scrollY: 200,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 2
            },
            paging: false,
            info: false,
            "pageLength": 10,
            "order": [],
            "columns": [
                { "data": "rtn_date" },
                { "data": "reference" },
                { "data": "customer" },
                { "data": "code"},
                { "data": "item_name" },
                { "data": "pack_size" },
                { "data": "reason" },
                { "data": "total_qty" },
                { "data": "select" },


            ],
            "stripeClasses": ['odd-row', 'even-row']
        });

        table_two.column(3).visible(false);




    };

    // Return objects assigned to module

    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});
var action = undefined;
var table;
var referanceID;
$(document).ready(function () {
    
//tool tips
    $(function () {
        $(".tooltip-target").tooltip();
    });

    getServerTime(); // calling time function
    $('#btnBack').hide();
    $('#cmbBranch').on('change', function () {
        var id = $(this).val();
        getLocation(id);
    });
    getBranches();
    $('#cmbBranch').change();



    //show model
    $('#rtn_model_btn').on('click', function () {
        $('#rtn_model').modal('show');
        get_sales_retrun_details($('#cmbBranch').val(), $('#cmbLocation').val())
    });

    //loading model data to header table
    $('#bntLoadData').on('click', function () {
        getReturnItems('item');
        $('#rtn_model').modal('hide');
    });

    //loading model data to header table
    $('#btnGetAll').on('click', function () {
        getReturnItems('all');
        $('#rtn_model').modal('hide');
    });

    //save reutn transfer

    $('#btnSave').on('click', function () {
        var collection = []
        $('#sales_return_transfer_table tbody tr').each(function () {
            var row_checkbox = $(this).find('input[name="rowChk"]');
            if (row_checkbox.is(':checked')) {
                var rtn_item_id = $(this).find('td:eq(0)').find('div').data('id');
                var rtn_date = $(this).find('td:eq(0)').text();
                // var ref = $(this).find('td:eq(1)').text();
                var cus_id = $(this).find('td:eq(2)').find('div').data('id');
                var item_id = $(this).find('td:eq(3)').find('div').data('id');
                var package_unit = $(this).find('td:eq(4)').text();
                var total = $(this).find('td:eq(5)').text();
                var trnsf_qty = $(this).find('td:eq(6)').find('input[type="number"]').val();
                var remark = $(this).find('td:eq(8)').find('input[type="text"]').val();
                var reason = $(this).find('td:eq(4)').find('div').data('id');
                if (isNaN(parseInt(trnsf_qty)) || parseInt(trnsf_qty) == 0 || /[^0-9]/.test(trnsf_qty)) {
                    showWarningMessage('Please enter correct transfer quantity');
                    return;
                }
                if (isNaN(parseInt(reason))) {
                    showWarningMessage('Return reason has not set properly.');
                    return;
                }
                if (isNaN(parseInt(total)) || parseInt(total) == 0) {
                    showWarningMessage('Total quantitiy can not be 0 or empty');
                    return;
                }
                collection.push(JSON.stringify({
                    "rtn_item_id": rtn_item_id,
                    "rtn_date": rtn_date,
                    "cus_id": cus_id,
                    "item_id": item_id,
                    "package_unit": package_unit,
                    "total": total,
                    "trnsf_qty": trnsf_qty,
                    "remark": remark,
                    "reson": reason
                }));
            }
        });
        console.log(collection);
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

                if (result) {

                    //newReferanceID('return_transfers', '1100');
                    addReturnTransfer(collection);

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

    });


    //in view
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        Invoice_id = param[0].split('=')[1].split('&')[0];    
        action = param[0].split('=')[2].split('&')[0];
        task = param[0].split('=')[3].split('&')[0];
        if (action == 'view') {
            $('#btnSave').hide();
            $('#chkAll').prop('checked');
            $('#rtn_model_btn').hide();
            $('#btnBack').show();
            table.column(5).visible(false);
            table.column(7).visible(false);
            $('#cmbLocation').prop('disabled',true);
            $('#cmb_to_Location').prop('disabled',true);
            $('#cmbBranch').prop('disabled',true);
        }
        getEachReturnTransfer(Invoice_id);

    }


    //button back to list
    $('#btnBack').on('click', function () {

        var url = "/sd/retrun_trnasfer_list";
        window.location.href = url;


    });




});



//load data to model table
function get_sales_retrun_details(brnach_id, from_location) {
    $.ajax({
        url: '/sd/get_sales_retrun_details/' + brnach_id + '/' + from_location,
        type: 'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var remain_qty = parseInt(dt[i].total_qty) - parseInt(dt[i].return_qty_transfer);
                var select_chk = "<input type='checkbox' id='" + dt[i].sales_return_item_id + '|' + dt[i].sr_manual + "' name='select_record'>";
                data.push({
                    "rtn_date": dt[i].order_date,
                    "reference": dt[i].sr_manual,
                    "customer": shortenString(dt[i].customer_name,20),
                    "code":dt[i].Item_code,
                    "item_name": shortenString(dt[i].item_name,20),
                    "pack_size": dt[i].package_unit,
                    "reason":dt[i].sales_return_resons,
                    "total_qty": remain_qty,
                    "select": select_chk

                });

            }

            var table_two = $('#get_table').DataTable();
            table_two.clear();
            table_two.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}



//loading branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })

        },
    })
}

//get both location types (From / To)
function getLocation(id) {

    $('#cmbLocation').empty();
    $('#cmb_to_Location').empty();
    $.ajax({
        url: '/sd/getLocatiofor_return/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            var return_location = data.return_location;
            var location = data.location;
            $.each(return_location, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmbLocation').trigger('change');

            $.each(location, function (index, val) {
                $('#cmb_to_Location').append('<option value="' + val.location_id + '">' + val.location_name + '</option>');

            })
            $('#cmb_to_Location').trigger('change');
        },
    })
}


//get server time
function getServerTime() {
    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {

            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#rtn_date').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}

//get model data to the table
function getReturnItems(type) {
    var is_error = false;
   

    //appending ids to array
    var rtn_item_id_array = [];
    var rtn_item_ref_no_array = [];
    $('#get_table tbody tr').each(function () {
        var checkbox = $(this).find('input[type="checkbox"]');
        if (checkbox.is(':checked')) {
            
            var row = $(this).closest('tr');
            var ref_num = $(row).find('td').eq(1).text();
            if(type == 'all'){
                if (!rtn_item_ref_no_array.includes(ref_num)) {
                    rtn_item_ref_no_array.push(ref_num);
                }else{
                    showWarningMessage('Please select only one record from a return to select all items of the relevant sales return.');
                    is_error = true;
                    return;
                }
            }
               
          
            var rtn_tem_id = $(checkbox).attr('id')
            rtn_item_id_array.push(rtn_tem_id);

        }
    });


    if(!is_error){
        $.ajax({
            url: '/sd/getReturnItems/' + type,
            type: 'get',
            cache: false,
            data: {
                'id_array': rtn_item_id_array
            },
            timeout: 800000,
            beforeSend: function () { },
            success: function (response) {
                var dt = response.data;
                console.log(dt);
                var count = 0;
                var data = [];
                if (dt.length) {
                    $('#cmbBranch').prop('disabled', true);
                    $('#cmbLocation').prop('disabled', true);
                } else {
                    $('#cmbBranch').prop('disabled', false);
                    $('#cmbLocation').prop('disabled', false);
                }
                for (var i = 0; i < dt.length; i++) {
                    for (var j = 0; j < dt[i].length; j++) {
                        var txt_transfer_qty = "<input type='number' class='form-control' name='trn_qty_txt' id='' oninput='check_rtn_qty(this)'>";
                        var txt_remark = "<input type='text' class='form-control' name='' id=''>";
                        var chk = "<input type='checkbox' id=" + dt[i][j].sales_return_item_id + " name='rowChk' onchange='add_total_qty(this)''>"
                        var remain_qty = parseInt(dt[i][j].total_qty) - parseInt(dt[i][j].return_qty_transfer);
                        data.push({
                            "rtn_date": '<div data-id="' + dt[i][j].sales_return_item_id + '">' + dt[i][j].order_date + '</div>',
                            "reference": dt[i][j].sr_manual,
                            "customer": '<div data-id="' + dt[i][j].customer_id + '">' + dt[i][j].customer_name + '</div>',
                            "item_name": '<div data-id="' + dt[i][j].item_id + '">' + dt[i][j].item_name + '</div>',
                            "pack_size": '<div data-id="' + dt[i][j].return_reason_id + '">' + dt[i][j].package_unit + '</div>',
                            "total_qty": parseInt(remain_qty),
                            "transfer_qty": txt_transfer_qty,
                            "chk": chk,
                            "remark": txt_remark,
                        });
    
                    }
    
    
                }
    
    
                console.log(count);
    
    
    
                var table = $('#sales_return_transfer_table').DataTable();
                table.clear();
                table.rows.add(data).draw();
    
            },
            error: function (error) {
                console.log(error);
            },
            complete: function () { }
        });
    }

}

//save return trnasfer
function addReturnTransfer(collection) {
    if (collection.length < 1) {
        showWarningMessage('Please select at least one record');
        return;
    }else{
        var formData = new FormData();
        formData.append('collection', JSON.stringify(collection));
        formData.append('LblexternalNumber', referanceID);
        formData.append('rtn_date', $('#rtn_date').val());
        formData.append('cmbBranch', $('#cmbBranch').val());
        formData.append('cmbfromLocation', $('#cmbLocation').val());
        formData.append('cmb_to_location', $('#cmb_to_Location').val());
        console.log(formData);
    
        $.ajax({
            url: '/sd/addReturnTransfer',
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
                $('#btnSave').prop('disabled', true);
            }, success: function (response) {
                $('#btnSave').prop('disabled', false);
                var status = response.status;
                var msg = response.message;
                if (msg == 'used') {
                    showWarningMessage("Item already used");
                    return;
                }
                console.log(response);
                if (status) {
    
                    showSuccessMessage("Return trasnfer done");
                    var table = $('#sales_return_transfer_table').DataTable();
                    table.clear().draw();
                    $('#chkAll').prop('checked', false);
    
    
                } else {
    
                    showWarningMessage("Unable to insert");
                }
    
    
    
            }, error: function (data) {
                console.log(data.responseText)
            }, complete: function () {
    
            }
        });
    }
    
}


//reference id
function newReferanceID(table, doc_number) {
    referanceID = newID("../newReferenceNumber_return_transfer", table, doc_number);

}

//select all
function selectAll(event) {
    if ($(event).is(':checked')) {
        $('#sales_return_transfer_table tbody tr').each(function () {
            var checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', true);
            add_total_qty(checkbox);
        });
    } else {
        $('#sales_return_transfer_table tbody tr').each(function () {
            var checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', false);
            add_total_qty(checkbox);
        });
    }


}

//get total qty whwn chck box is checked
function add_total_qty(event) {
    var checkbox = $(event);
    var t_qty = checkbox.closest('tr').find('td:eq(5)').text();
    if ($(event).is(':checked')) {

        var tqty_textbox = checkbox.closest('tr').find('td:eq(6)').find('input[type="number"').val(parseInt(t_qty));

    } else {

        var tqty_textbox = checkbox.closest('tr').find('td:eq(6)').find('input[type="number"').val('');
        unset_select_all(event);
    }

}

//clear select all when one check box is unchecked
function unset_select_all(event) {
    if ($(event).is(':checked')) {



    } else {

        $('#chkAll').prop('checked', false);
    }
}

//validate return qty

function check_rtn_qty(event) {

    var total_qty = $(event).closest('tr').find('td:eq(5)').text();
    var entering_qty = $(event).val();
    if (isNaN(parseInt(entering_qty))) {
        showWarningMessage('Only numbers allowed')
        $(event).val('');
    } else if (/[^0-9]/.test(entering_qty)) {
        showWarningMessage('Only numbers allowed')
        $(event).val('');
    }



    if (parseInt(entering_qty) > parseInt(total_qty)) {

        showWarningMessage('Transfer quantity should be less than or equal to ' + total_qty);
        $(event).val(total_qty);
    }

}


//get each return transfer for view
function getEachReturnTransfer(id) {
    $.ajax({
        url: '/sd/getEachReturnTransfer/' + id,
        type: 'get',
        processData: false,
        async: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, timeout: 800000,
        beforeSend: function () {

        }, success: function (response) {

            var res = response.header;
            var dt = response.items;
            var data = [];
            console.log(dt);
            $('#LblexternalNumber').val(res[0].external_number);
            $('#rtn_date').val(res[0].transfer_date);
            $('#cmbBranch').val(res[0].branch_id);
            $('#cmbLocation').val(res[0].from_location_id);
            $('#cmb_to_Location').val(res[0].to_location_id);

            for (var i = 0; i < dt.length; i++) {
                console.log(dt[i][0]);
                
                    
                    var txt_transfer_qty = "<input type='number' class='form-control' name='trn_qty_txt' id='' oninput='check_rtn_qty(this)' disabled>";
                    var txt_remark = "<input type='text' class='form-control' name='' disabled value='"+dt[i].Remark+"'>";
                    var chk = "<input type='checkbox' name='rowChk' onchange='add_total_qty(this)' disabled checked>"; // Fixed 'disbaled' to 'disabled'
                    var remain_qty = parseInt(dt[i].total_qty) - parseInt(dt[i].return_qty_transfer);
                    data.push({
                        "rtn_date": '<div>' + dt[i].order_date + '</div>',
                        "reference": dt[i].sr_manual,
                        "customer": '<div data-id="' + dt[i].customer_id + '">' + dt[i].customer_name + '</div>',
                        "item_name": '<div data-id="' + dt[i].item_id + '">' + dt[i].item_Name + '</div>',
                        "pack_size": '<div data-id="' + dt[i].return_reason_id + '">' + dt[i].package_unit + '</div>',
                        "total_qty": parseInt(remain_qty),
                        "transfer_qty": dt[i].transfer_qty,
                        "chk": chk,
                        "remark": dt[i].Remark,
                    });

               
            }



            var table = $('#sales_return_transfer_table').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
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