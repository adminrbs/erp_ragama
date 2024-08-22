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
        var table = $('.datatable-fixed-both').DataTable({
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
                { "data": "reference" },
                { "data": "date" },
                { "data": "customer" },
                { "data":"route" },
                { "data": "action" }
       
            ],
            "stripeClasses": ['odd-row', 'even-row']

            

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

$(document).ready(function(){
    $(function () {
        $(".tooltip-target").tooltip();
    });
    get_sample_dispatch();
    

});








function view(id){
    var status = "Original"
    var encodedNumber = base64Encode(id);
    url = "/sc/sample_dispatch_view?id=" + encodedNumber +"&paramS="+status+"&action=view"+"&task=null";
    window.location.href = url;
}

//load data to table
function get_sample_dispatch(){
    $.ajax({
        url:'/sc/get_sample_dispatch',
        type:'get',
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {
                
                var str_primary = dt[i].sample_dispatch_id; 
                data.push({
                    "reference": dt[i].external_number,
                    "date": dt[i].order_date_time,
                    "customer": dt[i].customer_name,
                    "route": dt[i].route_name,
                    "action": '<button class="btn btn-success btn-sm " onclick="view(' + str_primary + ')" title="view"><i class="fa fa-eye" aria-hidden="true"></i></button>',
                });       
               
            }

            var table = $('#sample_dispatch_table').DataTable();
            table.clear(); 
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
    
}




function shortenString(inputString, maxLength) {
    if (inputString.length <= maxLength) {
        return inputString;
    } else {
        return inputString.substring(0, maxLength) + '...';
    }
}





function base64Encode(str) {
    return btoa(encodeURIComponent(str));
}

// Function to decode a Base64-encoded string
function base64Decode(str) {
    return decodeURIComponent(escape(atob(str)));
}
