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
        var table = $('#approve_table').DataTable({
            columnDefs: [
              
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 380,
                    targets: 2
                },

            ],
            scrollX: true,
            /* scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "item_code" },
                { "data": "item_name" },
                { "data": "qty" },
                { "data":"pacs" },
                { "data": "price" },
              /*   { "data": "batch" }, */
                /* { "data": "action" } */
       
            ],
            "stripeClasses": ['odd-row', 'even-row']

            

        });

        


    };

   

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
$(document).ready(function(){
    getBranches();




     //loading locations
     $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    });

    $('#cmb_to_Branch').change(function () {
        var id = $(this).val();
        get_to_Location(id);
    });



    var sPageURL = window.location.search.substring(1);
    var param = sPageURL.split('?');
  
    GRNID = param[0].split('=')[1].split('&')[0];
    var status = param[0].split('=')[2].split('&')[0];
    action = param[0].split('=')[3].split('&')[0];
    task = param[0].split('=')[4].split('&')[0];
    if(action == 'view'){
       
    }
    else if (action == 'approve') {
       
    } 
   
    get_each_transfer(GRNID);



    $('#btnApprove').on('click',function(){
        bootbox.confirm({
            title: 'Approval confirmation',
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
                   // alert();
                   approve_goods_transfer(GRNID);
                    
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

    })


        //reject
        $('#btnReject').on('click',function(){
            bootbox.confirm({
                title: 'Reject confirmation',
                message: '<div class="d-flex justify-content-center align-items-center mb-3"><i class="fa fa-times fa-5x text-danger" ></i></div><div class="d-flex justify-content-center align-items-center "><p class="h2">Are you sure?</p></div>',
                buttons: {
                    confirm: {
                        label: '<i class="fa fa-check"></i>&nbsp;Yes',
                        className: 'btn-Danger'
                    },
                    cancel: {
                        label: '<i class="fa fa-times"></i>&nbsp;No',
                        className: 'btn-link'
                    }
                },
                callback: function (result) {
                   console.log(result);
                   if(result){
                    reject_goods_transfer(GRNID);
                   }else{
        
                   }
                }
            });
            $('.bootbox').find('.modal-header').addClass('bg-danger text-white');
    
            
        })




});

//loading branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                $('#cmb_to_Branch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');
                

            })

        },
    })
}


//loading from location
function getLocation(id) {
    $('#cmbLocation').empty();
    $.ajax({
        url: '/sc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })
            $('#cmbLocation').change();
           // alert($('#cmbLocation').val());
        },
    })
}
//get to location
function get_to_Location(id) {
    $('#cmb_to_Location').empty();
    $.ajax({
        url: '/sc/loadAllLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmb_to_Location').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })

        },
    })
}


/* getEachGR_rtn */
function get_each_transfer(id) {
   
    /* formData.append('status', status); */
    $.ajax({
        url: '/sc/get_each_transfer/' + id,
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
            
        }, success: function (data) {
            console.log(data);
            var GTR = data.gtr;
            var GTR_item = data.gtr_item;
            console.log(GTR_item);
            $('#LblexternalNumber').val(GTR.external_number);
            $('#goods_received_date_time').val(GTR.goods_transfer_date);
            $('#txtYourReference').val(GTR.your_reference_number);
            $('#cmbBranch').val(GTR.from_branch_id);
            $('#cmbBranch').change();
            $('#cmbLocation').val(GTR.from_location_id);
            $("#cmb_to_Branch").val(GTR.to_branch_id);
            $("#cmb_to_Branch").change()
            $('#cmb_to_Location').val(GTR.to_location_id);
            $('#lblNetTotal').text(parseFloat(GTR.total_amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

            var data = [];
            $.each(GTR_item, function (index, value) {
             
                data.push({
                   
                    "item_code": value.Item_code,
                    "item_name": value.item_Name,
                    "qty": Math.abs(value.quantity),
                    "pacs": value.package_size,
                    "price":parseFloat(value.price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }),
                   
                });


            });

            var table = $('#approve_table').DataTable();
            table.clear();
            table.rows.add(data).draw();
            
        

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}


function approve_goods_transfer(id){
    $.ajax({
        url: '/sc/approve_goods_transfer/' + id,
        type: 'post',
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
            var msg = response.msg;

            if (msg == "no") {
                showWarningMessage('Unable to approve');
                return;
            }

            if (status) {
                showSuccessMessage("Record approved");
               
                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
                var url = "/sc/goods_transfer_approve_list"; 
                window.location.href = url; 

                
            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })
}



function reject_goods_transfer(id){
    $.ajax({
        url: '/sc/reject_goods_transfer/' + id,
        type: 'post',
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
            var msg = response.msg;

            if (msg == "no") {
                showWarningMessage('Unable to reject');
                return;
            }

            if (status) {
                showSuccessMessage("Record rejected");
               
                $('#btnApprove').prop('disabled', true);
                $('#btnReject').prop('disabled', true);
                var url = "/sc/goods_transfer_approve_list"; 
                    window.location.href = url; 
               

                
            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })
}

