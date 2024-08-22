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
               /*  orderable: false,
                width: 100,
                targets: [2] */
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
        var table = $('.datatable-fixed-both').DataTable({
          
            
            columnDefs: [
                 {
                    width: 70,
                    targets: 2
                }, 
                 {
                    width: 100,
                    targets: 0
                }, 
                
                {
                    width: 100,
                    targets: 3,
                    
                },
                {
                    width: 700,
                    targets: 4
                },
                 {
                    width: 70,
                    targets: 1
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
                { "data": "offerName"},   
                { "data": "from" },
                { "data": "to" },
                { "data": "item_code" },
                { "data": "item_name" },
                { "data": "qty" },
                { "data": "foc" },
               /*  { "data": "value" }, */
               { "data": "type" },
                { "data": "action" }
       
            ],
            "stripeClasses": ['odd-row', 'even-row'],
            buttons: {
                dom: {
                    button: {
                        className: 'btn btn-light'
                    }
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export to Excel <i class="ph-file-xls ms-2"></i>',
                        customize: function( xlsx ) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
             
                            $('row c[r^="C"]', sheet).attr( 's', '2' );
                        }
                    }
                ]
            }

            

        });

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

var formData = new FormData();
$(document).ready(function(){
    
    $('input[name="from_date"]').daterangepicker();
    $('.select2').select2();
    loadSupplyGroup();
    filterOffers();

    $('#cmbSupplyGroup').on('change',function(){
        filterOffers();
    });

    $('#cmbAny').on('change',function(){
       
        filterOffers();
    });

    $('#cmbStatus').on('change',function(){
        filterOffers();
    });



});

//load supply group
function loadSupplyGroup(){
    $.ajax({
        url: '/sd/getSupllyGroupToOfferList',
        method: 'GET',
        async: false,
        success: function (data) {
            var dt = data;

            $.each(dt, function (index, value) {
                $('#cmbSupplyGroup').append('<option value="' + value.supply_group_id + '">' + value.supply_group + '</option>');

            })

        },
    })
}




//loading data to table (filtering)
function filterOffers(){
    var dateRange = $('#from_date').val();
    var dates_ = dateRange.split('-');
    var first_date = dates_[0];
    var second_date = dates_[1];
    
    
    formData.append('from_date', first_date);
    formData.append('to_date', second_date);
    formData.append('cmbAny', $('#cmbAny').val());
    formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());
    formData.append('cmbStatus', $('#cmbStatus').val());
 

     var table = $('#offer_list_table');
    var tableBody = $('#offer_list_table tbody');
    tableBody.empty(); 
    
    $.ajax({
        url: "/sd/filterOffers",
        method: 'POST',
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
        beforeSend: function () { },
        success: function (response) {
           var dt = response.data;
            var data = [];
            console.log(dt);
            for (var i = 0; i < dt.length; i++) {
                /* if (dt[i].approval_status == "Approved") {
                    label_approval = '<label class="badge badge-pill bg-success">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";


                } else if (dt[i].approval_status == "Rejected") {
                    label_approval = '<label class="badge badge-pill bg-danger">' + dt[i].approval_status + '</label>';
                    disabled = "disabled";
                } */
               /*  disabled = "disabled"; */
                disabled = "disabled";
                var offerId = dt[i].offer_id;
                var offerDataid = dt[i].offer_data_id;
                btnEdit = '<button class="btn btn-primary btn-sm" id="btnEdit_' + offerId + '" onclick="edit(' + offerId +','+offerDataid+ ')"><i class="fa fa-pencil-square-o" aria-hidden="true" ></i></button>';
                btnDlt = '<button class="btn btn-danger btn-sm" onclick="_delete(' + offerId + ')"'+ disabled +'><i class="fa fa-trash" aria-hidden="true"></i></button>';
                btnView = '<button class="btn btn-success btn-sm" onclick="view(' + offerId +','+offerDataid+ ')"><i class="fa fa-eye" aria-hidden="true"></i></button>';
                data.push({
                    "offerName": dt[i].name,  
                    "from": dt[i].start_date,
                    "to": dt[i].end_date,
                    "item_code": dt[i].Item_code,
                    "item_name":dt[i].item_Name,
                    "qty": dt[i].quantity,
                    "foc": dt[i].free_offer_quantity,
                   /*  "value":"", */
                   "type": dt[i].offer_type,
                    "action": btnEdit +' '+btnView+' '+ btnDlt,
                });  
 
               
            }

            var table = $('#offer_list_table').DataTable();
            table.clear(); 
            table.rows.add(data).draw();
            
        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    }); 
}


/* function edit(offe_id, offer_data_id) {

    url = "/sd/freeOfferView?id=" + offe_id +"&dataID="+offer_data_id+"&action=edit"+"&task=null";
    window.open(url, "_blank");

}

function view(offe_id, offer_data_id){
    url = "/sd/freeOfferView?id=" + offe_id +"&paramS="+offer_data_id+"&action=view"+"&task=null";
    window.open(url, "_blank");
} */

/* function edit(offe_id, offer_data_id) {

    url = "/sd/freeOfferCreateNewView?id=" + offe_id +"&dataID="+offer_data_id+"&action=edit"+"&task=null";
    window.open(url, "_blank");

} */

function edit(offe_id, offer_data_id) {

    url = "/sd/freeOfferCreateNewView?id=" + offe_id +"&dataID="+offer_data_id+"&action=edit"+"&task=null";
    window.location.href = url;
  
  }

function view(offe_id, offer_data_id){
    url = "/sd/freeOfferCreateNewView?id=" + offe_id +"&paramS="+offer_data_id+"&action=view"+"&task=null";
    window.open(url, "_blank");
}