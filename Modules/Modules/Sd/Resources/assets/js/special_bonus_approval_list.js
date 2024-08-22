


const DatatableFixedColumns = function () {


    //
    // Setup module components
    //

    // Basic Datatable examples
    const _componentDatatableFixedColumns = function () {
        if (!$().DataTable) {
            console.warn('Warning - datatables.min.js is not loaded.');
            return;
        }

        // Setting datatable defaults
        $.extend($.fn.dataTable.defaults, {
            
            dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });



        // Left and right fixed columns
        var table = $('#special_bonus_table').DataTable({
            columnDefs: [

                {
                    width: 400,
                    targets: 0,
                    orderable: false,
                },
                {
                    width: 400,
                    targets: 1,
                    orderable: false,
                },
                {
                    width: 200,
                    targets: 2,
                    orderable: false,
                },
                {
                    width: 110,
                    targets: 3,
                    orderable: false,

                },
                {
                    width: 70,
                    targets: 4,
                    orderable: false,
                },
                {
                    width: 10,
                    targets: 5,
                    orderable: false,
                },
                {
                    width: 100,
                    targets: 6,
                    orderable: false,
                },
                {
                    width: 80,
                    targets: 7,
                    orderable: false,
                },
                {
                    "targets": '_all',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '5px');
                    }
                } 


            ],
            scrollX: true,
            //scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "date" },
                { "data": "customer" },
                { "data": "route" },
                { "data": "item_code" },
                { "data": "item_name" },  
                { "data": "pack_size" },
                { "data": "qty" },
                { "data": "bonus_qty" },
              /*   { "data": "valid_days" }, */
                { "data": "action" },


                

            ], "stripeClasses": ['odd-row', 'even-row'],
        });



        //
        // Fixed column with complex headers
        //

    };


    //
    // Return objects assigned to module
    //

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




var formData = new FormData();
$(document).ready(function () {
       //tool tips
       $(function () {
        $(".tooltip-target").tooltip();
    });
    $(".select2").select2({
        dropdownParent: $("#special_bonus")

    });

    //open bonus create model
    $('#btn_add_special_bonus').on('click', function () {
        $('#cmb_customer, #cmbItem, #txtQty, #txtFreeQty, #txtValidDays, #bonus_id').attr('disabled', false);
        $('#btnsave').show().text('Save');
    });


    //loading data
    getAllSpecialBonus(2);

    //add special bonus
    $('#btnsave').on('click', function () {
        if ($('#btnsave').text() == 'Save') {
            add_special_bonus();
        } else if ($('#btnsave').text() == 'Update') {
            update_special_bonus();
        }


    });

    //close module
    $('#btnClose').on('click', function () {
        $('#special_bonus').modal('hide');
    });


    //loading items
    get_items_special_bonus();
    //loading customers
    get_customer_special_bonus();


});

//approve reject confirmation
function approve_reject_(id, type) {
    bootbox.confirm({
        title: 'confirmation',
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
                approve_reject_bonus(id, type);
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



function getAllSpecialBonus(val) {
    $.ajax({
        type: 'GET',
        url: '/sd/getAllSpecialBonus/' + val,
        success: function (response) {

            var dt = response.data;
            console.log(dt);

            var data = [];
            if (dt.length < 1) {
                var table = $('#special_bonus_table').DataTable();
                table.clear().draw();
            }
            for (var i = 0; i < dt.length; i++) {
                var status_badge = '<label class="badge badge-pill bg-warning">Pending</label>';
                var edit_button = '<button title="Edit" class="btn btn-primary  btn-sm lonmodel tooltip-target" data-bs-toggle="modal" data-bs-target="#special_bonus" onclick="get_each_special_bonus_edit(' + dt[i].special_bonus_id + ')" style="height:37px;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>'
                var approve = '<button title="approve" class="btn btn-success btn-sm" onclick="approve_reject_(' + dt[i].special_bonus_id + ', 1)">Approve</button>';

                var reject = '<button class="btn btn-danger btn-sm"  onclick="approve_reject_(' + dt[i].special_bonus_id + ',2)" title="Reject">Reject</button>'


                if (dt[i].status != 0) {
                    continue;
                } else {

                    if (dt[i].status == 1) {
                        var status_badge = '<label class="badge badge-pill bg-success">Approved</label>';

                    } else if (dt[i].status == 2) {
                        var status_badge = '<label class="badge badge-pill bg-danger">Rejected</label>';

                    } else if (dt[i].status == 3) {
                        var status_badge = '<label class="badge badge-pill bg-secondary">Expired</label>';

                    }
                    var cus_name =dt[i].customer_name
                    data.push({
                        "date":  dt[i].created_at,
                        "customer": shortenString(cus_name,20),
                        "route":shortenString(dt[i].route_name,15),
                        "item_code": dt[i].Item_code,
                        "item_name": shortenString(dt[i].item_Name, 15),   
                        "pack_size": dt[i].package_unit,
                        "qty": dt[i].quantity,
                        "bonus_qty": dt[i].bonus_quantity,
                       /*  "valid_days": dt[i].valid_days, */
                        "action": edit_button + ' ' + approve + ' ' + reject,

                    });


                  /*   { "data": "cus_code" },
                    { "data": "customer" },
                    { "data": "route" },
                    { "data": "item_code" },
                    { "data": "item_name" },  
                    { "data": "pack_size" },
                    { "data": "qty" },
                    { "data": "bonus_qty" },
                    { "data": "valid_days" },
                    { "data": "action" }, */

                }

                if(data.length < 1){
                    table.clear();
                }
                var table = $('#special_bonus_table').DataTable();
                table.clear();
                table.rows.add(data).draw();

            }



        },
        error: function (data) {
            console.log(data);
        }, complete: function () {

        }
    });
}


function approve_reject_bonus(id, type) {
    if (type != 1) {
        $('#special_bonus_remark').modal('show');
        $('#btnsave_remark').on('click', function () {
            if($('#txtRemark').val().length < 1){
                $('#txtRemark').addClass('is-invalid');
            }else{
                formData.append('remark',$('#txtRemark').val());
                excute_approve_reject(id, type);
                $('#special_bonus_remark').modal('hide');
            }
           
        });
    } else {
        excute_approve_reject(id, type);
    }




}
//ajax
function excute_approve_reject(id, type) {
    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/sd/approve_reject/' + id + '/' + type,
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {

            var status = response.status;
            var msg = response.message;
            if (msg == "failed") {
                showWarningMessage('Unable to updated');
            } else if (msg == "approved") {
                showSuccessMessage('Successfully approved');

            } else if (msg == "rejected") {
                showSuccessMessage('Successfully rejected');
            }

            getAllSpecialBonus(2);

        },
        error: function (error) {
            showErrorMessage('Something went wrong');

            console.log(error);

        },
        complete: function () {

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


//get data to update
function get_each_special_bonus_edit(id) {
    $('#btnsave').text('Update').show();;
    $('#cmb_customer, #cmbItem, #txtQty, #txtFreeQty, #txtValidDays, #bonus_id').attr('disabled', false);

    $.ajax({
        url: '/sd/get_each_special_bonus_edit/' + id,
        method: 'get',
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {
            var dt = response.data

            $('#cmb_customer').val(dt.customer_id);
            $('#cmb_customer').trigger('change');
            $("#cmbItem").val(dt.item_id);
            $('#cmbItem').trigger('change');
            $('#txtQty').val(dt.quantity);
            $('#txtFreeQty').val(dt.bonus_quantity);
            $('#txtValidDays').val(dt.valid_days);
            $('#bonus_id').val(dt.special_bonus_id);
            $('#txtRemark').val(dt.remark);



        }
    });
}


//load items
function get_items_special_bonus() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/load_items_for_special_bonus",
        async: false,

        success: function (response) {
            var data = response.data

            $.each(data, function (index, value) {

                $('#cmbItem').append('<option value="' + value.item_id + '">' + value.item_Name + '|' + value.Item_code + '</option>');

            })

        },

    });
}

//load customer
function get_customer_special_bonus() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/get_customer_special_bonus",
        async: false,

        success: function (response) {
            var data = response.data

            $.each(data, function (index, value) {

                $('#cmb_customer').append('<option value="' + value.customer_id + '">' + value.customer_name + '</option>');

            })

        },

    });
}



//update bonus
function update_special_bonus() {
    if ($('#txtQty').val().length < 1 || $('#txtFreeQty').val().length < 1 || $('#txtValidDays').val().length < 1) {
        showWarningMessage('All fileds need to be filled');
        if ($('#txtQty').val().length < 1) {
            $('#txtQty').addClass('is-invalid');
        }

        if ($('#txtFreeQty').val().length < 1) {
            $('#txtFreeQty').addClass('is-invalid');
        }

        if ($('#txtValidDays').val().length < 1) {
            $('#txtValidDays').addClass('is-invalid');
        }
    } else if ($('#txtQty').val() <= 0 || $('#txtFreeQty').val() <= 0 || $('#txtValidDays').val() <= 0) {
        if ($('#txtQty').val() <= 0) {
            $('#txtQty').addClass('is-invalid');
            showWarningMessage('Qty must be greater than 0');
        }

        if ($('#txtFreeQty').val() <= 0) {
            $('#txtFreeQty').addClass('is-invalid');
            showWarningMessage('Bonus qty must be greater than 0');
        }

        if ($('#txtValidDays').val() <= 0) {
            $('#txtValidDays').addClass('is-invalid');
            showWarningMessage('Calid days must be greater than 0');
        }

    } else if (parseInt($('#txtQty').val()) < parseInt($('#txtFreeQty').val())) {
        showWarningMessage('Qty can not be less than bonus qty');
        $('#txtQty').addClass('is-invalid');
    } else {
        $('#txtQty, #txtFreeQty, #txtValidDays').removeClass('is-invalid');
        var id = $('#bonus_id').val();
        formData.append('customer_id', $('#cmb_customer').val());
        formData.append('item_id', $('#cmbItem').val());
        formData.append('quantity', $('#txtQty').val());
        formData.append('bonus_quantity', $('#txtFreeQty').val());
        formData.append('valid_days', $('#txtValidDays').val());
        formData.append('remark', $('#txtRemark').val());

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: '/sd/update_special_bonus/' + id,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            timeout: 800000,
            beforeSend: function () {
                $('#btnsave').prop('disabled', true);
            },
            success: function (response) {
                $('#btnsave').prop('disabled', false);
                var status = response.status;
                if (status) {
                    showSuccessMessage('Successfully updated');
                    getAllSpecialBonus(1);
                    $('#special_bonus').modal('hide');
                    return;
                } else {
                    showWarningMessage('Unable to updated');

                }

            },
            error: function (error) {
                showErrorMessage('Something went wrong');

                console.log(error);

            },
            complete: function () {

            }

        });

    }


}