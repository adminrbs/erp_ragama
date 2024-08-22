


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
                    width: 50,
                    targets: 0,
                    orderable: false,
                },
                {
                    width: 200,
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
                    width: 80,
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
                rightColumns: 0
            },
            autoWidth: false,
            "pageLength": 100,
            "order": [],
            "columns": [
                /*  { "data": "id" }, */
                { "data": "date" },
                { "data": "customer" },
                { "data": "route" },
                { "data": "item_code" },
                { "data": "item_name" },
                { "data": "pack_size" },
                { "data": "qty" },
                { "data": "bonus_qty" },
               /*  { "data": "valid_days" }, */
                { "data": "reject_remark" },
                { "data": "action" },

            ], "stripeClasses": ['odd-row', 'even-row'],
        }); /* table.column(0).visible(false); */
        setTimeout(function () {
            $(window).on('resize', function () {
                table.columns.adjust();
            });
        }, 100);


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

    $("#cmbStatus").val(1).change();
    getAllSpecialBonus(1);
  
    $(".select2").select2({
        dropdownParent: $("#special_bonus")

    });

    $('#cmbStatus').on('change',function(){
        getAllSpecialBonus($(this).val());
    });

    //open bonus create model
    $('#btn_add_special_bonus').on('click', function () {
        $('#cmb_customer, #cmbItem, #txtQty, #txtFreeQty, #txtValidDays, #bonus_id').attr('disabled', false);
        $('#txtQty, #txtFreeQty, #txtValidDays').val('');
        $('#btnsave').show().text('Save');
    });

    //close module
    $('#btnClose').on('click', function () {
        $('#special_bonus').modal('hide');
    });


    //loading items
    get_items_special_bonus();
    //loading customers
    get_customer_special_bonus();
    

    //add special bonus
    $('#btnsave').on('click', function () {
        confirm();


    });

    //allwoing only numbers to textboxes
    $('input[type="number"]').on('input', function (e) {
        $(this).val(function (index, value) {
            return value.replace(/[^0-9]/g, '');
        });
    });

    //validate bonus quantity with qty
    $('#txtFreeQty').on('focusout', function (e) {
        var qty_val = $('#txtQty').val();
        if (parseInt(qty_val) < $('#txtFreeQty').val()) {
            showWarningMessage('Bonus qty can not be exceeded the total qty');

            $('#txtFreeQty').val('0');
        }
    });

    $('#special_bonus').on('hidden.bs.modal', function () {
     
        $('#special_bonus_form')[0].reset();
    });

    $('#special_bonus').on('show.bs.modal', function () {
      
        $("#cmb_customer").change();
        $("#cmb_customer").trigger('change');
        $("#cmbItem").trigger('change');
    });


    
});

//add update confirmation box
function confirm() {
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
                if ($('#btnsave').text() == 'Save') {
                    add_special_bonus();
                } else {
                    update_special_bonus();
                }
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

//load items
function get_items_special_bonus() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sd/load_items_for_special_bonus",
        async: false,

        success: function (response) {
            var data = response.data
                $('#cmbItem').append('<option value="0">Select Item</option>');
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
                $('#cmb_customer').append('<option value="0">Select Customer</option>');
            $.each(data, function (index, value) {
                
                $('#cmb_customer').append('<option value="' + value.customer_id + '">' + value.customer_name + '</option>');

            })
           // $('#cmb_customer').change();

        },

    });
}


//add special bonus
function add_special_bonus() {
    if ($('#cmbItem option:selected').text() === 'Select Item') {
        showWarningMessage('Please select a item');
        $('#cmbItem').addClass('is-invalid');

    }else if($('#cmb_customer option:selected').text() === 'Select Customer'){
        showWarningMessage('Please select a customer');
        $('#cmb_customer').addClass('is-invalid');

    }else{
        if ($('#txtQty').val().length < 1 || $('#txtFreeQty').val().length < 1 ) {
            showWarningMessage('All fileds need to be filled');
            if ($('#txtQty').val().length < 1) {
                $('#txtQty').addClass('is-invalid');
            }
    
            if ($('#txtFreeQty').val().length < 1) {
                $('#txtFreeQty').addClass('is-invalid');
            }
    
           
        } else if ($('#txtQty').val() <= 0 || $('#txtFreeQty').val() <= 0) {
            if ($('#txtQty').val() <= 0) {
                $('#txtQty').addClass('is-invalid');
                showWarningMessage('Qty must be greater than 0');
            }
    
            if ($('#txtFreeQty').val() <= 0) {
                $('#txtFreeQty').addClass('is-invalid');
                showWarningMessage('Bonus qty must be greater than 0');
            }
    
           
    
        } else if (parseInt($('#txtQty').val()) < parseInt($('#txtFreeQty').val())) {
            showWarningMessage('Qty can not be less than bonus qty');
            $('#txtQty').addClass('is-invalid');
        } else {
            
            $('#txtQty, #txtFreeQty').removeClass('is-invalid');
            formData.append('customer_id', $('#cmb_customer').val());
            formData.append('item_id', $('#cmbItem').val());
            formData.append('quantity', $('#txtQty').val());
            formData.append('bonus_quantity', $('#txtFreeQty').val());
            formData.append('valid_days', 1);
            formData.append('remark', $('#txtRemark').val());
    
            $.ajax({
                type: "POST",
                enctype: 'multipart/form-data',
                url: '/sd/add_special_bonus',
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
                    console.log(response)
                    $('#btnsave').prop('disabled', false);
                    var status = response.status;
                    var message = response.message;
                    if (status) {
                        showSuccessMessage('Saved succesfully');
                        getAllSpecialBonus(1);
                        $('#special_bonus').modal('hide');
                    } else if(message == "duplicated") {
                        showWarningMessage('Offer duplicated');
                    }else{
                        showWarningMessage("Unable to save");
                    }
    
                },
                error: function (error) {
                    //showErrorMessage('Something went wrong');
                    showErrorMessage("Something went wrong");
    
                },
                complete: function () {
    
                }
    
            });
    
        }

    }
    
   
   



}

//load all data to table
/* function getAllSpecialBonus(val) {
    $.ajax({
        type: 'GET',
        url: '/sd/getAllSpecialBonus/'+val,
        success: function (response) {

            var dt = response.data;
            console.log(dt);

            var data = [];
            var table = $('#special_bonus_table').DataTable();
            if(dt.length < 1){
                table.clear().draw();
            }

            for (var i = 0; i < dt.length; i++) {
                var status_badge = '<label class="badge badge-pill bg-warning">Pending</label>';
                var edit_button = '<button title="Edit" class="btn btn-primary  btn-sm lonmodel tooltip-target" data-bs-toggle="modal" data-bs-target="#special_bonus" onclick="get_each_special_bonus_edit(' + dt[i].special_bonus_id + ')" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>'
                var view_button = '<button class="btn btn-success btn-sm loneview tooltip-target" data-bs-toggle="modal" data-bs-target="#special_bonus"  onclick="get_each_special_bonus_view(' + dt[i].special_bonus_id + ')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>'
                var delete_button = '<button class="btn btn-danger btn-sm tooltip-target" onclick="_delete(' + dt[i].special_bonus_id + ')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>'

                if (dt[i].status != 0) {
                    var edit_button = '<button title="Edit" class="btn btn-primary  btn-sm lonmodel" data-bs-toggle="modal" data-bs-target="#special_bonus" onclick="edit(' + dt[i].special_bonus_id + ')" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>'

                    var delete_button = '<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].special_bonus_id + ')" title="Delete" disabled><i class="fa fa-trash" aria-hidden="true" ></i></button>'
                }

                
                
                    if (dt[i].status == 1) {
                        var status_badge = '<label class="badge badge-pill bg-success">Approved</label>';
    
                    } else if (dt[i].status == 2) {
                        var status_badge = '<label class="badge badge-pill bg-danger">Rejected</label>';
    
                    } else if (dt[i].status == 3) {
                        var status_badge = '<label class="badge badge-pill bg-secondary">Expired</label>';
    
                    }
                    var cus_name =dt[i].customer_name
                    var remark = dt[i].reject_remark;
                    if(remark == 'null' || remark == null){
                        remark = ""
                    }
                    data.push({
                       
                        "date": dt[i].created_at,
                        "customer": shortenString(cus_name,15),
                        "route":shortenString(dt[i].route_name,10),
                        "item_code": dt[i].Item_code,
                        "item_name": shortenString(dt[i].item_Name, 10),
                        "pack_size": dt[i].package_unit,
                        "qty": dt[i].quantity,
                        "bonus_qty": dt[i].bonus_quantity,
                     
                        "reject_remark":remark,
                        "action": edit_button + ' ' + view_button + ' ' + delete_button,
    
                    });

                  

                  
    
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
 */

function getAllSpecialBonus(val) {
    $.ajax({
        type: 'GET',
        url: '/sd/getAllSpecialBonus/' + val,
        success: function(response) {
            var dt = response.data;
            console.log(dt);

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                var cus_name = dt[i].customer_name;
                var remark = dt[i].reject_remark;
                if (remark == 'null' || remark == null) {
                    remark = "";
                }

                var edit_button = '<button title="Edit" class="btn btn-primary  btn-sm lonmodel tooltip-target" data-bs-toggle="modal" data-bs-target="#special_bonus" onclick="get_each_special_bonus_edit(' + dt[i].special_bonus_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
                var view_button = '<button class="btn btn-success btn-sm loneview tooltip-target" data-bs-toggle="modal" data-bs-target="#special_bonus"  onclick="get_each_special_bonus_view(' + dt[i].special_bonus_id + ')" title="View"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                var delete_button = '<button class="btn btn-danger btn-sm tooltip-target" onclick="_delete(' + dt[i].special_bonus_id + ')" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';

                if (dt[i].status != 0) {
                    edit_button = '<button title="Edit" class="btn btn-primary  btn-sm lonmodel" data-bs-toggle="modal" data-bs-target="#special_bonus" onclick="edit(' + dt[i].special_bonus_id + ')" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>';
                    delete_button = '<button class="btn btn-danger btn-sm" onclick="_delete(' + dt[i].special_bonus_id + ')" title="Delete" disabled><i class="fa fa-trash" aria-hidden="true" ></i></button>';
                }

                data.push([
                    dt[i].created_at,
                    shortenString(cus_name, 15),
                    (dt[i].route_name || ""), // Handle possible undefined value
                    dt[i].Item_code,
                    shortenString(dt[i].item_Name, 10),
                    dt[i].package_unit,
                    '<div style="text-align:right;">'+dt[i].quantity+'</div>',
                    '<div style="text-align:right;">'+dt[i].bonus_quantity+'</div>',
                    remark,
                    edit_button + ' ' + view_button + ' ' + delete_button
                ]);
            }

            var table = $('#special_bonus_table').DataTable({
                destroy: true, // Destroy previous instance before creating a new one
                data: data,
                columns: [
                    { title: "Date", width: "50px", targets: 0, orderable: false },
                    { title: "Customer", width: "200px", targets: 1, orderable: false },
                    { title: "Route", width: "200px", targets: 2, orderable: false },
                    { title: "Item Code", width: "110px", targets: 3, orderable: false },
                    { title: "Item Name", width: "70px", targets: 4, orderable: false },
                    { title: "Pack Size", width: "80px", targets: 5, orderable: false },
                    { title: "Qty", width: "100px", targets: 6, orderable: false, },
                    { title: "Bonus Qty", width: "80px", targets: 7, orderable: false },
                    { title: "Reject Remark", orderable: false },
                    { title: "Action", orderable: false }
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td').css('padding', '5px');
                }
            });
        },
        error: function(data) {
            console.log(data);
        },
        complete: function() {
            // Add any completion logic here
        }
    });
}
//update bonus
function update_special_bonus() {

    if ($('#cmbItem option:selected').text() === 'Select Item') {
        showWarningMessage('Please select a item');
        $('#cmbItem').addClass('is-invalid');

    }else if($('#cmb_customer option:selected').text() === 'Select Customer'){
        showWarningMessage('Please select a customer');
        $('#cmb_customer').addClass('is-invalid');

    }else{
    if ($('#txtQty').val().length < 1 || $('#txtFreeQty').val().length < 1 ) {
        showWarningMessage('All fileds need to be filled');
        if ($('#txtQty').val().length < 1) {
            $('#txtQty').addClass('is-invalid');
        }

        if ($('#txtFreeQty').val().length < 1) {
            $('#txtFreeQty').addClass('is-invalid');
        }

       
    } else if ($('#txtQty').val() <= 0 || $('#txtFreeQty').val() <= 0) {
        if ($('#txtQty').val() <= 0) {
            $('#txtQty').addClass('is-invalid');
            showWarningMessage('Qty must be greater than 0');
        }

        if ($('#txtFreeQty').val() <= 0) {
            $('#txtFreeQty').addClass('is-invalid');
            showWarningMessage('Bonus qty must be greater than 0');
        }

        /* if ($('#txtValidDays').val() <= 0) {
            $('#txtValidDays').addClass('is-invalid');
            showWarningMessage('Calid days must be greater than 0');
        } */

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
        formData.append('valid_days', 1);
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
                var message = response.message;
                if (status) {
                    showSuccessMessage('Successfully updated');
                    getAllSpecialBonus(1);
                    $('#special_bonus').modal('hide');
                    return;
                } else if(message == "duplicated") {
                    showWarningMessage('Offer duplicated');
                }else{
                    showWarningMessage("Unable to save");
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


}


//get data to update
function get_each_special_bonus_edit(id) {
    $('#btnsave').text('Update').show();;
    $('#cmb_customer, #cmbItem, #txtQty, #txtFreeQty, #txtValidDays, #bonus_id').attr('disabled', false);

    $.ajax({
        url: '/sd/get_each_special_bonus_edit/' + id,
        method: 'get',
        async:false,
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {
            var dt = response.data

            $('#cmb_customer').val(dt.customer_id);
            $('#cmb_customer').trigger('change');
            $("#cmbItem").val(dt.item_id);
            $("#cmbItem").change();
            $('#txtQty').val(dt.quantity);
            $('#txtFreeQty').val(dt.bonus_quantity);
           /*  $('#txtValidDays').val(dt.valid_days); */
            $('#bonus_id').val(dt.special_bonus_id);
            $('#txtRemark').val(dt.remark);



        }
    });
}

//view
function get_each_special_bonus_view(id) {
    $('#btnsave').hide();
    $.ajax({
        url: '/sd/get_each_special_bonus_edit/' + id,
        method: 'get',
        async:false,
        data: {
            //id: id,
            _token: '{{ csrf_token() }}'
        },

        success: function (response) {
            var dt = response.data

            $('#cmb_customer').val(dt.customer_id);
            $('#cmb_customer').trigger('change');
            $("#cmbItem").val(dt.item_id);
            $("#cmbItem").change();
            $('#txtQty').val(dt.quantity);
            $('#txtFreeQty').val(dt.bonus_quantity);
            /* $('#txtValidDays').val(dt.valid_days); */
            $('#bonus_id').val(dt.special_bonus_id);
            $('#txtRemark').val(dt.remark);

            $('#cmb_customer, #cmbItem, #txtQty, #txtFreeQty, #txtValidDays, #bonus_id').attr('disabled', true);


        }
    });
}

//delete
function _delete(id) {

    bootbox.confirm({
        title: 'Delete confirmation',
        message: '<div class="d-flex justify-content-center align-items-center mb-3"><i class="fa fa-times fa-5x text-danger" ></i></div><div class="d-flex justify-content-center align-items-center "><p class="h2">Are you sure?</p></div>',
        buttons: {
            confirm: {
                label: '<i class="fa fa-check"></i>&nbsp;Yes',
                className: 'btn-Danger'
            },
            cancel: {
                label: '<i class="fa fa-times"></i>&nbsp;No',
                className: 'btn-info'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                delete_bonus(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

function delete_bonus(id) {

    $.ajax({
        type: 'DELETE',
        url: '/sd/delete_bonus/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        }, success: function (response) {

            getAllSpecialBonus(1);
            var status = response.status;
            if (status) {
                showSuccessMessage("Successfully Deleted");
            } else {
                showWarningMessage("Unable to delete");
            }




        }, error: function (xhr, status, error) {
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


