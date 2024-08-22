var formData = new FormData();
var ITEM_ID = undefined;
$(document).ready(function () {
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    $(".select2").select2({
        dropdownParent: $("#supplieritemCode")

    });

    $('#supplieritemCodeTable').on('click', 'tr', function (e) {


        var hiddenValue = $(this).find('td:eq(0)');
        var childElements = hiddenValue.children(); // or hiddenValue.find('*');
        childElements.each(function () {

            ITEM_ID = $(this).attr('data-id');





        });
    });

});


const DatatableFixedColumns = function () {

    //$('#frm').trigger("reset");
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
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [2]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<div class="form-control-feedback form-control-feedback-end flex-fill mt-2" style="display:none;">_INPUT_<div  class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3 mt-2">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });



        // Left and right fixed columns
        var table = $('.datatable-fixed-both').DataTable({
            columnDefs: [
                {
                    orderable: false,
                    targets: 2
                },
                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: 700,
                    targets: [2]
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
                { "data": "item_id" },
                { "data": "Item_code" },
                { "data": "item_Name" },
                { "data": "textbox" },


            ], "stripeClasses": ['odd-row', 'even-row'],
        }); table.column(0).visible(false);



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
///.................... Item Loard..........................--------------------------

document.addEventListener('DOMContentLoaded', function () {
    DatatableFixedColumns.init();
});


function itemAllData() {


    $.ajax({
        type: "GET",
        url: "/md/getItemdata",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {

            var dt = response.data;

            var data = [];
            for (var i = 0; i < dt.length; i++) {

                var input_id = 'textsupplieritem' + i;
                //var str_input_id = "'"+input_id+"'";
                data.push({

                    "item_id": dt[i].item_id,
                    "Item_code": '<div data-id = "' + dt[i].item_id + '">' + dt[i].Item_code + '</div>',
                    "item_Name": dt[i].item_Name,
                    "textbox": '<input type="text"   class="form form-control" name="supplieritem" id="' + input_id + '" onclick="supplierItemForcusOut(this)" required>',
                });
            }


            var table = $('#supplieritemCodeTable').DataTable();
            table.clear();
            table.rows.add(data).draw();


        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })

}

itemAllData();

//..............supliers loard..........


function suppliers() {

    $.ajax({
        type: "get",
        dataType: 'json',
        url: "/md/suppliersname",

        success: function (data) {


            $.each(data, function (key, value) {


                data = data + "<option  id='' value=" + value.supplier_id + ">" + value.supplier_name + "</option>"


            })

            $('#cmbSupplieritemCode').html(data);

        }

    });

}
suppliers();



//.....saveCategoryLevel2 Save.....
function savesuppliers(supplier_item_code) {


    formData.append('item_id', ITEM_ID);
    formData.append('supplier_item_code', supplier_item_code);
    formData.append('cmbSupplieritemCode', $('#cmbSupplieritemCode').val());
    formData.append('textsupplieritem', $('#textsupplieritem').val());


    //console.log(formData);

    $.ajax({
        type: "POST",
        enctype: 'multipart/form-data',
        url: '/md/savesavesuppliers',
        async:false,
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        timeout: 800000,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {
            // Perform any tasks before sending the request
        },
        success: function (response) {
            console.log(response);
            itemAllData();


            if (response.status) {
                showSuccessMessage('Successfully saved');
            } else {
                showErrorMessage("Something went worng");
            }

            console.log(response);
        },
        error: function (error) {
            showErrorMessage('Something went wrong');
            console.log(error);
        }
    });

}


function supplierItemForcusOut(event) {


   $(event).focusout(function(evt){
    evt.preventDefault();
    if($(this).val())
    savesuppliers($(this).val());
   });

}
