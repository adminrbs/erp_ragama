

/**data table */
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
            columnDefs: [{
                orderable: false,
                width: 100,
                targets: [2]
            }],
            dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span class="me-3">Search:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',

                lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': document.dir == "rtl" ? '&larr;' : '&rarr;', 'previous': document.dir == "rtl" ? '&rarr;' : '&larr;' }
            }
        });



        // Left and right fixed columns
        var table = $('#userRoleForpermission').DataTable({
            columnDefs: [

                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },

            ],
            scrollX: true,
            //scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0,
            },
            "pageLength": 10,
            "order": [],
            "columns": [
                { "data": "id" },
                { "data": "name" },


            ], "stripeClasses": ['odd-row', 'even-row'],
        });
        /*  table.column(0).visible(false); */


        /**Module */
        var table = $('#moduleTable').DataTable({
            columnDefs: [

                {
                    width: 200,
                    targets: 0
                },
                {
                    width: '100%',
                    targets: 1
                },

            ],
            scrollX: true,
            //scrollY: 350,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 0,
                rightColumns: 0,
            },
            "pageLength": 10,
            "order": [],
            "columns": [
                { "data": "id" },
                { "data": "name" },
                { "data": "action" }


            ], "stripeClasses": ['odd-row', 'even-row'],
        });
        /*  table.column(0).visible(false); */



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

var ROLE_ID;
var formData = new FormData();
var selectedModules = [];
$(document).ready(function () {
    getUserRole();
   // getModuleList();

    //tabs
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

 
    //tr click
    $('#userRoleForpermission').on('click', 'tr', function (e) {
        $('#userRoleForpermission tr').removeClass('selected');



        $(this).addClass('selected');
        

        var hiddenValue = $(this).find('td:eq(1)');
        var childElements = hiddenValue.children(); // or hiddenValue.find('*');
        childElements.each(function () {

            ROLE_ID = $(this).attr('data-id');


        });
        selectedModules = [];
        getModuleList(ROLE_ID);
    });


    //select modules
     $('#moduleTable').on('click', 'input[type="checkbox"]', function () {
        var value = $(this).val();
        if ($(this).is(':checked')) {
            if(!selectedModules.includes(value)){
                selectedModules.push(value);
            }
          
          addRoleModule(this,ROLE_ID);
        } else {
          var index = selectedModules.indexOf(value);
          if (index !== -1) {
            selectedModules.splice(index, 1);
          }
          deleteRoleModule(this,ROLE_ID);
        }
        console.log(selectedModules);
      });

      //
      $('#tabs a').click(function (e) {
        
        getSelectedModules(selectedModules);
    });



    $('#cmbModules').on('change',function(){
        var moduleID = $(this).val();
        allPermissions(ROLE_ID, moduleID)

    });


});

//ad role module
function addRoleModule(event,role_id){
    var _val = $(event).val();
    
        var moduleData = _val.split('|');
        var moduleId = moduleData[0];
        /* var moduleName = moduleData[1]; */
      
       
    $.ajax({
        type: "POST",
        url: '/st/addRoleModule/'+ moduleId +'/'+ role_id,
        async: false,
        data: {},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);

            if (response.success) {
               

            } else {
               
            }


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });

}
//delete role module (uncheck)
function deleteRoleModule(event,role_id){
    var _val = $(event).val();
    
        var moduleData = _val.split('|');
        var moduleId = moduleData[0];
        /* var moduleName = moduleData[1]; */
      
       
    $.ajax({
        type: "DELETE",
        url: '/st/deleteRoleModule/'+ moduleId +'/'+ role_id,
        async: false,
        data: {},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);

            if (response.success) {
               

            } else {
               
            }


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });

}



function getUserRole() {
    $.ajax({

        type: "GET",
        url: "/st/getRoleData",
        cache: false,
        timeout: 800000,
        beforeSend: function () { },
        success: function (response) {
            var dt = response.data;
            console.log(dt);
            var data = [];
            for (var i = 0; i < dt.length; i++) {

                data.push({
                    "id": dt[i].id,
                    "name": '<div data-id = "' + dt[i].id + '">' + dt[i].name + '</div>',


                });

            }

            var table = $('#userRoleForpermission').DataTable();
            table.clear();
            table.rows.add(data).draw();

        },
        error: function (error) {
            console.log(error);
        },
        complete: function () { }
    })
}

//load module
function getModuleList(role_id_) {
 // Array to store selected module_id and module_name combinations
  
    $.ajax({
      type: "GET",
      url: "/st/getModuleList/"+role_id_,
      cache: false,
      timeout: 800000,
      beforeSend: function () {},
      success: function (response) {
        var dt = response.data;
        console.log(dt);
        var data = [];
        for (var i = 0; i < dt.length; i++) {
            var checkBox = '<input class="form-check-input" type="checkbox" name="record[]" value="' + dt[i].module_id + '|' + dt[i].module_name + '">';
            if(dt[i].role_id == role_id_){
                checkBox = '<input class="form-check-input" type="checkbox" name="record[]" value="' + dt[i].module_id + '|' + dt[i].module_name + '" checked>';
                var value = dt[i].module_id + '|' + dt[i].module_name
                if(!selectedModules.includes(value)){
                    selectedModules.push(value);
                }
                
            }
            data.push({
            "id": dt[i].module_id,
            "name": dt[i].module_name,
            "action": checkBox,
          });
        }
  
        var table = $('#moduleTable').DataTable();
        table.clear();
        table.rows.add(data).draw();
  
       
      },
      error: function (error) {
        console.log(error);
      },
      complete: function () {}
    });
  }
  

/* function getSelectedModules(selectedModules) {
    var formData = new FormData();
    formData.append('selectedModules', JSON.stringify(selectedModules));
    $.ajax({
        url: '/st/getSelectedModules',
        method: 'GET',
        async: false,
        data: formData,
        datatype: 'json',
        success: function (data) {
            $.each(data, function (index, module) {
                $('#cmbModules').append('<option value="' + module.module_id + '">' + module.module_name + '</option>');
            });
        }
    });

} */

function getSelectedModules(selectedModules){
    
    $('#cmbModules').empty();
    for (var i = 0; i < selectedModules.length; i++) {
        var moduleData = selectedModules[i].split('|');
        var moduleId = moduleData[0];
        var moduleName = moduleData[1];
        $('#cmbModules').append('<option value="' + moduleId + '">' + moduleName + '</option>');
       
       
      }
      $('#cmbModules').change();
}

//load menus
function allPermissions(role_id, module_id) {
    $.ajax({
        type: "GET",
        url: "/st/allPermissions/" + role_id + '/' + module_id,
        processData: false,
        contentType: false,
        async: false,
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.success) {
                 appendTableRowPermission(response.result, role_id, module_id); 

            } else {
                 /* showErrorMessage(); */ 
            }

        },
        error: function (error) {
            console.log(error);
            /* showErrorMessage(); */

        },
        complete: function () {

        }

    });
}


function appendTableRowPermission(permissions, role_id, module_id) {

    $('#tblPermission').empty();
    for (i = 0; i < permissions.length; i++) {
        var id = permissions[i].permission_id;
        var count = permissions[i].sub_count;
        if (count != 0) {
            var name = permissions[i].name + ' (' + count + ')';
        } else {
            var name = permissions[i].name;
        }

        console.log(name);
        var slug = permissions[i].slug;
        var allow = permissions[i].allow;
        var permission_string_id = "'" + id + "'";
        var role_string_id = "'" + role_id + "'";
        var module_string_id = "'" + module_id + "'";

        var checked = '';
        var disabled = 'disabled';
        if (allow) {
            checked = 'checked';
            disabled = '';
        }

        var sub_level = '<table><tbody id="tbl_sub_permission_' + id + '"></tbody></table>';
        $('#tblPermission').append('<tr><td><div><input type="checkbox" id="allow_' + id + '"  onclick="allowPermission(' + permission_string_id + ')" ' + checked + '><button id="btn_permission_' + id + '" class="btn btn-link"  onclick="subPermission(' + role_string_id + ',' + module_string_id + ',' + permission_string_id + ')" ' + disabled + '>' + name + '</button></div><div>' + sub_level + '</div></td></tr>');

    }

}


function subPermission(role_id, module_id, permission_id) {

    $.ajax({
        type: "GET",
        url: "/st/allSubPermissions/" + role_id + '/' + module_id + '/' + permission_id,
        processData: false,
        contentType: false,
        async: false,
        cache: false,
        timeout: 800000,
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);
            if (response.success) {
                appendTableRowSubPermission(response.result, role_id, module_id, permission_id);
            } else {
                /* showErrorMessage(); */
            }

        },
        error: function (error) {
            console.log(error);
           /*  showErrorMessage(); */

        },
        complete: function () {

        }

    });
}

function allowPermission(id) {
    //alert($('#cmbModule').val());
    $.ajax({
        type: "POST",
        url: '/st/allowPermission',
        async: false,
        data: {
            _token: $('input[name=token]').val(),
            "user_id": "{{ Auth::user()->id }}",
            "role_id": ROLE_ID,
            "permission_id": id,
            "module_id": $('#cmbModules').val(),
            "allow": $('#allow_' + id).prop("checked"),
        }, headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function () {

        },
        success: function (response) {
            console.log(response);

            if (response.success) {
                if ($('#allow_' + id).prop("checked")) {
                    $('#btn_permission_' + id).prop('disabled', false);
                } else {
                    $('#btn_permission_' + id).prop('disabled', true);
                    $('#tbl_sub_permission_' + id).empty();
                }
                showSuccessMessage('Module has been allowed successfully...');

            } else {
                showErrorMessage();

            }


        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });
}


function appendTableRowSubPermission(permissions, role_id, module_id, permission_id) {
    $('#tbl_sub_permission_' + permission_id).empty();
    for (i = 0; i < permissions.length; i++) {
        var id = permissions[i].permission_id;
        var count = permissions[i].sub_count;
        if (count != 0) {
            var name = permissions[i].name + ' (' + count + ')';
        } else {
            var name = permissions[i].name;
        }

        console.log(name);
        var slug = permissions[i].slug;
        var allow = permissions[i].allow;
        var permission_string_id = "'" + id + "'";
        var role_string_id = "'" + role_id + "'";
        var module_string_id = "'" + module_id + "'";

        var checked = '';
        var disabled = 'disabled';
        if (allow) {
            checked = 'checked';
            disabled = '';
        }
        var sub_level = '<table><tbody id="tbl_sub_permission_' + id + '"></tbody></table>';
        $('#tbl_sub_permission_' + permission_id).append('<tr><td><div><input type="checkbox" id="allow_' + id + '"  onclick="allowPermission(' + permission_string_id + ')" ' + checked + '><button id="btn_permission_' + id + '" class="btn btn-link"  onclick="subPermission(' + role_string_id + ',' + module_string_id + ',' + permission_string_id + ')" ' + disabled + '>' + name + '</button></div><div>' + sub_level + '</div></td></tr>');

    }
}


