const DatatableFixedColumns = function () {

    // Setup module components

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
        var table = $('#stock_blance_table').DataTable({
            columnDefs: [

                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 150,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 280,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 3
                },
                {
                    width: 200,
                    targets: 4
                },

            ],

            fixedColumns: true,
            scrollX: true,
            scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0
            },
            /*  "autoWidth": false, */
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "itemcode" },
                { "data": "itemname" },
                { "data": "qty" },
                { "data": "Reorderlevel" },
                { "data": "uom" }


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

var formData = new FormData();
$(document).ready(function () {

    getStockBlance();
    $('#txtDateofTo').on('change', function () {

        $('#cmbAny').val(1);
    });

    $('#cmbAny').on('change', function () {
        getStockBlance();
    });
    $('#cmbproduct').on('change', function () {
        getStockBlance();
    });
    $('#cmbSupplyGroup').on('change', function () {
        getStockBlance();
    });
    $('#cmbcategory1').on('change', function () {
        getStockBlance();
    });
    $('#cmbcategory2').on('change', function () {
        getStockBlance();
    });
    $('#cmbcategory3').on('change', function () {
        getStockBlance();
    });
    $('#cmbBranch').on('change', function () {
        getStockBlance();
    });
    $('#cmbLocation').on('change', function () {
        getStockBlance();
    });






    $('.select2').select2();

    $('.daterange-single').daterangepicker({
        parentEl: '.content-inner',
        singleDatePicker: true,
        locale: {
            format: 'YYYY-MM-DD',
        },
        //startDate: 'YYYY-MM-DD',

    });

    var dateInput = document.getElementById("txtDateofTo");

    dateInput.placeholder = "YYYY-MM-DD";

    dateInput.addEventListener("input", function () {
        var inputValue = dateInput.value;

        if (/^\d{4}-\d{2}-\d{2}$/.test(inputValue)) {

        } else {

            dateInput.value = "";
        }
    });

    $('#cmbBranch').on('change',function(){
        getLocation($(this).val());
    });

    
    loadbranch()
    loadSupplyGroup()
    getproduct()
    getItemCategory1()
    getItemCategory2()
    getItemCategory3()

    /* $('input[name="from_date"]').daterangepicker();
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
 */


    /*$('#btncreate').on('click',function(){
       getStockBlance()
    });*/


});

// loard branch
function loadbranch() {
    $.ajax({
        url: '/sc/getbranch',
        method: 'GET',
        async: false,
        success: function (data) {

            var htmlContent = "";
            // htmlContent += "<option value=''>Any</option>";

            $.each(data, function (key, value) {

                htmlContent += "<option value='" + value.branch_id + "'>" + value.branch_name + "</option>";
            });


            $('#cmbBranch').html(htmlContent);
            $('#cmbBranch').trigger('change');
        },
        
    })
}

//load supply group
function loadSupplyGroup() {
    $.ajax({
        url: '/sc/getSupllyGroup',
        method: 'GET',
        async: false,
        success: function (data) {

            var htmlContent = "";
            htmlContent += "<option value=''>Any</option>";

            $.each(data, function (key, value) {

                htmlContent += "<option value='" + value.supply_group_id + "'>" + value.supply_group + "</option>";
            });


            $('#cmbSupplyGroup').html(htmlContent);
        },
    })
}
//loard item
function getproduct() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getproduct",

        success: function (data) {
            var htmlContent = "";
            htmlContent += "<option value=''>Any</option>";

            $.each(data, function (key, value) {
                htmlContent += "<option value='" + value.item_id + "'>" + value.item_Name + "</option>";

            })

            $('#cmbproduct').html(htmlContent);





        }

    });

}
function getItemCategory1() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getItemCategory1",

        success: function (data) {
            var htmlContent = "";
            htmlContent += "<option value=''>Any</option>";

            $.each(data, function (key, value) {

                htmlContent += "<option value='" + value.item_category_level_1_id + "'>" + value.category_level_1 + "</option>";

            })

            $('#cmbcategory1').html(htmlContent);

        }

    });

}

function getItemCategory2() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getItemCategory2",

        success: function (data) {
            var htmlContent = "";
            htmlContent += "<option value=''>Any</option>";

            $.each(data, function (key, value) {
                htmlContent += "<option value='" + value.Item_category_level_2_id + "'>" + value.category_level_2 + "</option>";


            })

            $('#cmbcategory2').html(htmlContent);

        }

    });

}


function getItemCategory3() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/sc/getItemCategory3",

        success: function (data) {

            var htmlContent3 = "";
            htmlContent3 += "<option value=''>Any</option>";

            $.each(data, function (key, value) {
                htmlContent3 += "<option value='" + value.Item_category_level_3_id + "'>" + value.category_level_3 + "</option>";
            });

            // Set the HTML content of the select element
            $('#cmbcategory3').html(htmlContent3);
        }
    });

}


function getStockBlance() {



    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());
    formData.append('txtDateofTo', $('#txtDateofTo').val());
    formData.append('cmbAny', $('#cmbAny').val());

    formData.append('cmbproduct', $('#cmbproduct').val());
    formData.append('cmbcategory1', $('#cmbcategory1').val());

    formData.append('cmbcategory2', $('#cmbcategory2').val());
    formData.append('cmbcategory3', $('#cmbcategory3').val());
    formData.append('cmbLocation',$('#cmbLocation').val())

    console.log(formData);
    $.ajax({
        url: "/sc/getStockBlance",
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
        success: function (response) {
            var dt = response.data;
            console.log(dt);
            var data = [];

            for (var i = 0; i < dt.length; i++) {
                var qty = parseInt(dt[i].quantity); // Use parseInt to convert to an integer
                var reorderLevel = dt[i].reorder_level !== null ? Math.floor(dt[i].reorder_level) : '';
                data.push({
                    "itemcode": dt[i].Item_code,
                    "itemname": dt[i].item_Name,
                    "qty": qty,
                    "Reorderlevel": reorderLevel,
                    "uom": dt[i].unit_of_measure,
                });
            }

            var table = $('#stock_blance_table').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

function getLocation(id) {

    $('#cmbLocation').empty();
    $.ajax({
        url: '/sc/getLocation_stock_balance/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $('#cmbLocation').append('<option value="0">Any</option>');
            var mainStore_id = 0
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');
                if(value.location_type_id == 3){
                    mainStore_id = value.location_id
                }
            })
            $('#cmbLocation').trigger('change');
            if(mainStore_id != 0){
                $('#cmbLocation').val(mainStore_id);
                $('#cmbLocation').trigger('change');
            }
            
        },
    })
}

