const DataChooser = function () {

    var inputFiled = undefined;
    var parentRow = undefined;
    var old_id = undefined;
    var title = "title";
    var dtc_collection = [];
    var header_count = 0;
    var dtc_selected = { "hidden_id": "", "id": "", "value": "" };
    var COLLECTION_DATA = [];
    var KEY = "";

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
                search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });



        // Left and right fixed columns
        var data_chooser_table = $('.datatable-fixed-both').DataTable({
            columnDefs: [
                {
                    width: '100%',
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },
                {
                    width: '100%',
                    targets: 2
                },
                {
                    width: '100%',
                    targets: 3
                },
                {
                    width: 80,
                    targets: [4]
                },

            ],
            scrollX: false,
            scrollY: 200,
            scrollCollapse: true,
            autoWidth: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 1
            },
            "pageLength": 100,
            "order": [],
            "columns": [
                { "data": "id", "width": "25px" },
                { "data": "value", "width": "100%" },
                { "data": "value2", "width": "100%" },
                { "data": "value3", "width": "100%" },
                { "data": "action", "width": "90px" },


            ],
            "drawCallback": function () {
                //$(this.api().table().header()).hide();
            },
            "columnDefs": [{
                "targets": '_all',
                "createdCell": function (td, cellData, rowData, row, col) {
                    $(td).css('padding', '2px')
                }
            }],

        });

        data_chooser_table.on('click', 'tbody tr', function () {
            let data = data_chooser_table.row(this).data();
            $(data.action).trigger('click');

        });

        //var theadInnerHTML = $('.sorting').html('ABC');


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
        },

        showChooser: function (input, row, key) {

            if (KEY != key) {
                this.setHeader(COLLECTION_DATA[key].header);
                var table = $('#data-chooser-modal-tbl').DataTable();
                table.clear();
                table.rows.add(COLLECTION_DATA[key].data).draw();
                KEY = key;
            }
            if (row) {
                parentRow = $($($($(row).parent())[0]).parent())[0];
            }
            DataChooser.inputFiled = $(input);
            $('#data-chooser-modal').modal('show');
            $('.dataTables_length').remove();
            $(window).on('shown.bs.modal', function () {
                DataChooser.refresh();
                $("input[type=search]").val($(input).val());
                $("input[type=search]").focus();
                dataChooserShowEventListener(this);

            });

            $('.dataTables_filter input[type="search"]').css(
                { 'width': '480px', 'display': 'inline-block' }
            );


        },

        hideChooser: function () {
            $('#data-chooser-modal').modal('hide');
        },

        dispose: function () {
            $('#data-chooser-modal').modal('hide');
        },

        setTitle: function (title) {
            DataChooser.title = title;
            $('#data-chooser-modalLabel').text(title);
        },

        setValue: function (hidden_id, id, value) {
            DataChooser.old_id = DataChooser.inputFiled.attr('data-id');
            if (DataChooser.inputFiled != undefined) {
                DataChooser.inputFiled.val(value);
                DataChooser.inputFiled.attr('data-id', hidden_id);
                $('#data-chooser-modal').modal('hide');
                dtc_selected = { "hidden_id": hidden_id, "id": id, "value": value };
                dataChooserEventListener(this, id, value);
                // DataChooser.inputFiled.focus();
            }
            
        },
        getRowChilds: function () {


            var collection = [];
            var cells = $(parentRow).find('td');

            // Loop through the cells
            cells.each(function (index) {
                collection.push($(this).children()[0]);
            });


            return collection;
        },
        getCollction: function () {
            return dtc_collection;
        },
        getSelected: function () {
            return dtc_selected;
        },
        emptyTable: function () {
            var table = $('#data-chooser-modal-tbl').DataTable();
            table.clear();
        },
        refresh: function () {
            var table = $('#data-chooser-modal-tbl').DataTable();
            if (table != undefined) {
                table.columns.adjust();
            }
        },
        setHeader: function (header) {
            $('.datatable-fixed-both th').each(function () {
                var newTh = $('<th>');
                newTh.html(header[header_count++]);
                $(this).replaceWith(newTh);
            });
            header_count = 0;
        },
        addCollection: function (key, header, data) {
            var data_collection = [];
            for (var i = 0; i < data.length; i++) {
                var str_hidden_id = "'" + data[i].hidden_id + "'";
                var str_id = "'" + data[i].id + "'";
                var str_value = "'" + data[i].value + "'";
                var value2 = value2 = data[i].value2;;
                var value3 = value3 = data[i].value3;
                if (value2 == undefined) {
                    value2 = "";
                }
                if (value3 == undefined) {
                    value3 = "";
                }
               // console.log(data_collection);
                data_collection.push({
                    "id": '<label>' + data[i].id + '</label>',
                    "value": data[i].value,
                    "value2": value2,
                    "value3": value3,
                    "action": '<button type="button" class="btn btn-primary" style="float: right;" onclick="DataChooser.setValue(' + str_hidden_id + ',' + str_id + ',' + str_value + ')">Select</button>'
                });
            }
            COLLECTION_DATA[key] = { "header": header, "data": data_collection };
        },
        commit: function (key) {
            alert(key);
        }



    }
}();


// Initialize module
// ------------------------------

document.addEventListener('DOMContentLoaded', function () {
    DataChooser.init();
});




