let table;
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
                searchPlaceholder: 'Type to filter',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }


        });

        // Left and right fixed columns
         table = $('.datatable-fixed-both').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 50,
                    targets: 1
                },
                {
                    width: 700,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 3
                },
                {
                    width: 200,
                    targets: 4,
                },
                {
                    width: 100,
                    targets: 5,
                }


            ],
            scrollX: true,
            /*  scrollY: 350, */
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "info":false,
            "order": [],
            "columns": [
                { "data": "item_id" },
                { "data": "Item_code" },
                { "data": "item_Name" },
                { "data": "package_unit" },
              
                { "data": "supply_group" },
                { "data": "is_active" },
                { "data": "buttons" },
            ],
            "stripeClasses": ['odd-row', 'even-row'],
        });
        table.column(0).visible(false);
       


    };


    return {
        init: function () {
            _componentDatatableFixedColumns();
        }
    }
}();



document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});


var formData = new FormData();
$(document).ready(function () {
    $('.select2').select2();


    $('#cmbSupplyGroup, #cmbstatus, #cmbcategory1, #cmbcategory2, #cmbcategory3').on('change', function () {
        
        getItemDetails();
    });

    
    //extrcting parameter from url
    var Cusid;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        var Cusid = param[0].split('=')[1].split('&')[0];
        action = param[0].split('=')[2].split('&')[0];

        if (action == 'edit') {
            $('#btnSave').text('Update');
        } else if (action == 'view') {
            $('#btnSave').hide();
        }
        getEachCustomer(Cusid);
        getEachCustomerContact(Cusid);
        getEachDeliveryPoint(Cusid);

    }
    getItemDetails();
    getSupplyGroupId()
    getItemCategory1()
    getItemCategory2()
    getItemCategory3() 

});


//riderting to item edit form
function edit(id) {
    url = "/md/item?id=" + id + "&action=edit";
    window.open(url, "_blank");

}


// view function
function view(id) {
    url = "/md/item?id=" + id + "&action=view";
    window.open(url, "_blank");
}

//deleting item
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
                className: 'btn-link'
            }
        },
        callback: function (result) {
            console.log(result);
            if (result) {
                deleteItem(id);
            } else {

            }
        }
    });
    $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

}

//getting item details to item list
function getItemDetails() {

    formData.append('cmbSupplyGroup', $('#cmbSupplyGroup').val());
    formData.append('cmbstatus', $('#cmbstatus').val());
    formData.append('cmbcategory1', $('#cmbcategory1').val());
    formData.append('cmbcategory2', $('#cmbcategory2').val());
    formData.append('cmbcategory3', $('#cmbcategory3').val());
    console.log(formData);
    $.ajax({
        url: "/md/getItemDetails",
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

            disabled = "disabled";

            var whole_sale_price = 0;
            var retail_price = 0;
            for (var i = 0; i < dt.length; i++) {
                var label = '<label class="badge bg-danger">' + dt[i].is_active + '</label>';
                if (dt[i].is_active == "Yes") {
                    label = '<label class="badge bg-success">' + dt[i].is_active + '</label>';
                }

                if (!dt[i].whole_sale_price) {
                    whole_sale_price = 0
                } else {
                    whole_sale_price = dt[i].whole_sale_price;
                }

                if (!dt[i].retial_price) {
                    retail_price = 0
                } else {
                    retail_price = dt[i].retial_price;
                }
                var buttons = "";
                /* if(md_edit_item == 1){ */
                    buttons += '<button class="btn btn-primary" onclick="edit(' + dt[i].item_id + ')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&#160'
                /* } */

               /*  if(md_delete_item == 1){ */
                    buttons += '<button class="btn btn-success" onclick="view(' + dt[i].item_id + ')"><i class="fa fa-eye" aria-hidden="true"></i></button>&#160'
                /* } */

               /*  if(md_view_item == 1){ */
                    buttons += '<button class="btn btn-danger" onclick="_delete(' + dt[i].item_id + ')"' + disabled + '><i class="fa fa-trash" aria-hidden="true"></i></button>'
                /* } */

                data.push({
                    "item_id": dt[i].item_id,
                    "Item_code": dt[i].Item_code,
                    "item_Name": dt[i].item_Name,
                    "package_unit": dt[i].package_unit,
                    "wholesale_price": parseFloat(whole_sale_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                    "retail_price": parseFloat(retail_price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2, }).toString(),
                    "supply_group": dt[i].supply_group, 
                    "is_active": label,
                    "buttons": buttons
                });
            }

            var table = $('#itemTable').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

//delete item
function deleteItem(id) {
    $.ajax({
        type: 'DELETE',
        url: '/md/deleteItem/' + id,
        data: {
            _token: $('input[name=_token]').val()
        },
        beforeSend: function () {

        }, success: function (response) {
            var status = response
            if (status) {
                showSuccessMessage("Successfully deleted");
            } else {
                showErrorMessage("Something went wrong")
            }
            getItemDetails();
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

//.........................filter..............
function getSupplyGroupId() {
    $.ajax({
        url: '/md/getSupplyGroup',
        method: 'get',
        async: false,
        datatype: 'json',
        success: function (data) {



            var htmlContent = "";
            htmlContent += "<option value=''>Any</option>";

            $.each(data, function (key, value) {

                htmlContent += "<option value='" + value.supply_group_id + "'>" + value.supply_group + "</option>";
            });


            $('#cmbSupplyGroup').html(htmlContent);
        }

    })
}


//getting category_level_1 to select 2 tag
function getItemCategory1() {
    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/getCategoryLevelOne",

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
