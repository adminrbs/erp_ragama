var tableData = undefined;
var tableDataOther = undefined;
var formData = new FormData;
var task;
var action = undefined;
var referanceID;
var ItemList;
$(document).ready(function () {
    $('#btnApprove').hide();
    $('#btnReject').hide();
    $('#tabs a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#btnSaveDraft').hide();
   
   
    getServerTime();
    ItemList =  loadItems();


    var reuqestID = null;
    if (window.location.search.length > 0) {
        var sPageURL = window.location.search.substring(1);
        var param = sPageURL.split('?');
        reuqestID = param[0].split('=')[1].split('&')[0];
        var status = param[0].split('=')[2].split('&')[0];
        action = param[0].split('=')[3].split('&')[0];
        task = param[0].split('=')[4].split('&')[0];
        if(action == 'edit' && status == 'Original' && task == 'approval'){
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').show();
            $('#btnReject').show();
            $('#chk').hide();
        }
        else if (action == 'edit' && status == 'Original') {
            $('#btnSave').text('Update');
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
        
        } else if(action == 'edit' && status == 'Draft' ){
            $('#btnSave').text('Save and Send');
            $('#btnSaveDraft').text('Update Draft');
           /*  $('#btnSaveDraft').show(); */
            $('#btnApprove').hide();
            $('#btnReject').hide();
            
        }else if (action == 'view') {
            $('#btnSave').hide();
            $('#btnSaveDraft').hide();
            $('#btnApprove').hide();
            $('#btnReject').hide();
            
        }
        getEachPurchasingOrder(reuqestID, status);
        getEachproduct(reuqestID, status);
        getEachOther(reuqestID, status);  

    }

    //saving purchase request
    $('#btnSave').on('click', function (e) {
        //product table
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {
            if(arr[i][0].attr('data-id') != 'undefined'){
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "uom": arr[i][3].val(),
                    "PackUnit": arr[i][4].val(),
                    "PackSize": arr[i][5].val(),
    
                }));
            }
            
        }

        //other table
        var arrOther = tableDataOther.getDataSourceObject();
        var collectionOther = [];
        for (var i = 0; i < arrOther.length; i++) {
            if(arrOther[i][0].val() != '' &&  arrOther[i][1].val() !=''){
                collectionOther.push(JSON.stringify({
                    "description": arrOther[i][0].val(),
                    "qty": arrOther[i][1].val(),
    
                }));

            }
           
        }

        console.log(collection);
        bootbox.confirm({
            title: 'Save confirmation',
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
                    if ($('#btnSave').text() == 'Save and Send') {
                        newReferanceID('purchase_requests',100);
                        addPurchaseRequest(collection, collectionOther,reuqestID)
                    } else if ($('#btnSave').text() == 'Update') {
                        updatePurchaserequestPermenet(reuqestID,collection,collectionOther)

                    }
                    getServerTime();
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





    //savings purchase request draft
    $('#btnSaveDraft').on('click', function (e) {
        var arr = tableData.getDataSourceObject();
        var collection = [];
        for (var i = 0; i < arr.length; i++) {

            /* alert(arr[i][0].attr('data-id')== 'undefined'); */
            if(arr[i][0].attr('data-id') != 'undefined'){
                collection.push(JSON.stringify({
                    "item_id": arr[i][0].attr('data-id'),
                    "item_name": arr[i][1].val(),
                    "qty": arr[i][2].val(),
                    "uom": arr[i][3].val(),
                    "PackUnit": arr[i][4].val(),
                    "PackSize": arr[i][5].val(),
    
                }));

            }
           
        }
        console.log(collection);

        //other table
        var arrOther = tableDataOther.getDataSourceObject();
        var collectionOther = [];
        for (var i = 0; i < arrOther.length; i++) {
            if(arrOther[i][0].val() != '' &&  arrOther[i][1].val() !=''){
                collectionOther.push(JSON.stringify({
                    "description": arrOther[i][0].val(),
                    "qty": arrOther[i][1].val(),
    
                }));
            }
            
        }

        console.log(collection);
        bootbox.confirm({
            title: 'Save confirmation',
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
                    if ($('#btnSaveDraft').text() == 'Save Draft') {
                        newReferanceID('purchase_request_drafts',100);
                        addPurchaseRequestDraft(collection, collectionOther)

                    } else if ($('#btnSaveDraft').text() == 'Update Draft') {

                        updatePurchaserequestDraft(reuqestID,collection,collectionOther);

                    }
                    getServerTime();
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

    //loading locations
    $('#cmbBranch').change(function () {
        var id = $(this).val();
        getLocation(id);
    })
    getBranches();
    $('#cmbBranch').change();

    DataChooser.setTitle('Item');



    var select_option = [{ "value": 1, "text": "Abc" }, { "value": 2, "text": "xx" }];
    tableData = $('#tblData').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:150px;", "event": "DataChooser.showChooser(this,this)","valuefrom": "datachooser" },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "max-height:30px;", "event": "clickx(1)", "style": "width:600px","disabled":"disabled" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;width:60px;text-align:right;","compulsory":true },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "max-height:30px;text-align:right;width:150px;", "event": "clickx(1)", "width": "","disabled":"disabled" },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "max-height:30px;text-align:right;width:150px;", "event": "clickx(1)", "width": "*","disabled":"disabled" },
            { "type": "text", "class": "transaction-inputs","value": "", "style": "max-height:30px;text-align:right;width:150px;", "event": "clickx(1)", "width": "*","disabled":"disabled" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this)", "width": 30 }
        ],
        "auto_focus": 0,
        "hidden_col":[5]
       
        
    });

    tableData.addRow();

    //alowing numbers
    $('#tblData').on('input', 'input[type="text"]', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        var dotCount = (this.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            this.value = this.value.replace(/\.+$/, '');
        }
    });

    $('#tblDataOther').on('input', 'input[type="text"]', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        var dotCount = (this.value.match(/\./g) || []).length;
        if (dotCount > 1) {
            this.value = this.value.replace(/\.+$/, '');
        }
    });


    //other table
    tableDataOther = $('#tblDataOther').transactionTable({
        "columns": [
            { "type": "text", "class": "transaction-inputs", "value": "", "style": "max-height:30px;width:700px;" },
            { "type": "text", "class": "transaction-inputs math-abs", "value": "", "style": "max-height:30px;width:100px;text-align:right;" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this)", "width": 30 }
        ],
        "auto_focus": 0,
        "hidden_col":[]
    });

    tableDataOther.addRow();


    /*for (var i = 0; i < 10; i++) {
        var row = [
            { "type": "label", "value": "", "style": "max-height:30px;", "event": "clickx(1)", "width": "*" },
            { "type": "text", "class": "form-control form-control-sm", "value": "", "style": "max-height:30px;", "event": "abc(event)" },
            { "type": "select", "class": "form-control form-control-sm", "value": select_option, "style": "max-height:30px;", "width": "*" },
            { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "clickx(0)", "width": 30 }
        ];
        tableData.appendRow(row);
    }*/

    $('#form').submit(function (e) {
        e.preventDefault();

        // check if the input is valid using a 'valid' property
        if (!$(this).valid) {
            return;
        }
    });

    $('#btnSave').on('click', function () {
        //DatatableFixedColumns.setDataSourse();
        //console.log(tableData.getDataSourceObject()[0][1].attr('data-id'));
    });

    //approve
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
                    approveRequest(reuqestID);
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
                rejectRequest(reuqestID);
               }else{
    
               }
            }
        });
        $('.bootbox').find('.modal-header').addClass('bg-danger text-white');

        
    })



});


/* function abc(event) {

}
 */

function itemEventListner(event){

    console.log(ItemList);
    DataChooser.setDataSourse(ItemList);
    DataChooser.showChooser(event,event);
}
function clickx(id) {
    tableData.clear();
}


function transactionTableKeyEnterEvent(event, id) {

    if (id == 'tblData') {
        tableData.addRow();

    } else if (id == 'tblDataOther') {
        tableDataOther.addRow();
    }

}

//loading branches
function getBranches() {
    $.ajax({
        url: '/getBranches',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbBranch').append('<option value="' + value.branch_id + '">' + value.branch_name + '</option>');

            })

        },
    })
}


//loading location
function getLocation(id) {
    $('#cmbLocation').empty();
    $.ajax({
        url: '/prc/getLocation/' + id,
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbLocation').append('<option value="' + value.location_id + '">' + value.location_name + '</option>');

            })

        },
    })
}

//add purchase request
function addPurchaseRequest(collection, collectionOther,id) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('collectionOther', JSON.stringify(collectionOther));
    formData.append('LblexternalNumber', referanceID);
    formData.append('purchasee_request_date', $('#purchasee_request_date').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('DtexpectedDate', $('#DtexpectedDate').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtYourReference',$('#txtYourReference').val());
    $.ajax({
        url: '/prc/addPurchaseRequest/'+id,
        method: 'post',
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
            console.log(response);
            $('#btnSave').prop('disabled', false);
            var status = response.status
            var primaryKey = response.primaryKey;
            if (status) {
                showSuccessMessage("Successfully saved");
                resetForm();
                clearTableData();
                tableData.addRow();
                tableDataOther.addRow();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
    getServerTime();
}

//add purchase request draft
function addPurchaseRequestDraft(collection, collectionOther) {
    formData.append('collection', JSON.stringify(collection));
    formData.append('collectionOther', JSON.stringify(collectionOther));
    formData.append('LblexternalNumber', referanceID);
    formData.append('purchasee_request_date', $('#purchasee_request_date').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('DtexpectedDate', $('#DtexpectedDate').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtYourReference',$('#txtYourReference').val());

    $.ajax({
        url: '/prc/addPurchaseRequestDraft',
        method: 'post',
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
            var status = response.status
            var primaryKey = response.primaryKey;
            if (status) {
                showSuccessMessage("Saved as draft");
                resetForm();
                clearTableData();
                tableData.addRow();
               
                tableDataOther.addRow();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }
    })
}

//load item
function loadItems() {
    $.ajax({
        url: '/prc/loadItems',
        type: 'get',
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                var itemData = response.data;
                DataChooser.setDataSourse(itemData);
            }

        },
        error: function (error) {
            console.log(error);
        },

    })
}


function datachooserSetValue(id) {
    // alert(id);
}

function dataChooserEventListener(event,id,value) {

    var selected = event.getSelected();
    var item_id = selected.hidden_id;
    var row_childs = event.getRowChilds();
    var hash_map =[];
    var arr = tableData.getDataSource();
    for(var i = 0; i < arr.length-1; i++){
        hash_map.push(arr[i][0]);
    }

    console.log(hash_map);
    if(hash_map.includes(value)){

        showErrorMessage('Already exist '+value);
        /* alert('Already exist '+value); */
        event.inputFiled.val('');
        return;
    }

  

    $.ajax({
        url: '/prc/getItemInfo/' + item_id,
        type: 'get',
        success: function (response) {
            console.log(response);
          
            $(row_childs[1]).val(response[0].item_Name);
            $(row_childs[3]).val(response[0].unit_of_measure);
            $(row_childs[4]).val(response[0].package_unit);
            $(row_childs[5]).val(response[0].package_size);
            $(row_childs[2]).focus();
        }
    })
}


//getting purchase reuqest to update
function getEachPurchasingOrder(id, status) {
    formData.append('status', status);
    $.ajax({
        url: '/prc/getEachPurchasingOrder/' + id + '/' + status,
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
            console.log(status);
        }, success: function (PurchaseRequestData) {
            console.log(PurchaseRequestData);
            var res = PurchaseRequestData.data;
            getLocation(res.branch_id);
            $('#LblexternalNumber').val(res.external_number);
            $('#purchasee_request_date').val(res.purchase_request_date_time);
            $('#cmbBranch').val(res.branch_id);
            $('#cmbLocation').val(res.location_id);
            $('#DtexpectedDate').val(res.expected_date);
            $('#txtRemarks').val(res.remarks)
            $('#txtYourReference').val(res.your_reference_number);
           

        },
        error: function (xhr, status, error) {
            console.log(xhr.responseText);
        }

    });


}

//get each product of purchase request
function getEachproduct(id, status) {
    $.ajax({
        url: '/prc/getEachproduct/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);

            var dataSource = [];
            $.each(data, function (index, value) {


                dataSource.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.Item_code, "data_id":value.item_id, "style": "max-height:30px;width:150px;", "event": "DataChooser.showChooser(this,this)","valuefrom": "datachooser" },
                    { "type": "text", "class": "transaction-inputs","value": value.item_name, "style": "max-height:30px;", "event": "clickx(1)", "style": "width:200px;","disabled":"disabled" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.quantity, "style": "max-height:30px;width:100px;text-align:right;","compulsory":true },
                    { "type": "text", "class": "transaction-inputs","value": value.unit_of_measure, "style": "max-height:30px;text-align:right;", "event": "clickx(1)", "width": "*","disabled":"disabled" },
                    { "type": "text", "class": "transaction-inputs","value": value.package_unit, "style": "max-height:30px;text-align:right;", "event": "clickx(1)", "width": "*","disabled":"disabled" },
                    { "type": "text", "class": "transaction-inputs","value": value.package_size, "style": "max-height:30px;text-align:right;", "event": "clickx(1)", "width": "*","disabled":"disabled" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this)", "width": 30 }
                ]);


            });
            tableData.setDataSource(dataSource);
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}


//get each product of purchase request
function getEachOther(id, status) {
    $.ajax({
        url: '/prc/getEachOther/' + id + '/' + status,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            console.log(data);

            var dataSource_other = [];
            $.each(data.data, function (index, value) {
                dataSource_other.push([
                    { "type": "text", "class": "transaction-inputs", "value": value.description, "style": "max-height:30px;width:700px;" },
                    { "type": "text", "class": "transaction-inputs math-abs", "value": value.quantity, "style": "max-height:30px;width:100px;" },
                    { "type": "button", "class": "btn btn-danger", "value": "Remove", "style": "max-height:30px;", "event": "removeRow(this)", "width": 30 }
                ]);


            });
            tableDataOther.setDataSource(dataSource_other);
        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(xhr.responseText);
        }
    });
}

//permenet update
function updatePurchaserequestPermenet(id,collection,collectionOther){
    formData.append('collection', JSON.stringify(collection));
    formData.append('collectionOther', JSON.stringify(collectionOther));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('purchasee_request_date', $('#purchasee_request_date').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('DtexpectedDate', $('#DtexpectedDate').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtYourReference',$('#txtYourReference').val());

    $.ajax({
        url: '/prc/updatePurchaserequestPermenet/'+id,
        method: 'post',
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
            var status = response.status
           console.log(status);
            if (status) {
                showSuccessMessage("Saved as draft");
                closeCurrentTab();
                window.opener.location.reload();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

})
getServerTime();

}

//draft update
function updatePurchaserequestDraft(id,collection,collectionOther){
    formData.append('collection', JSON.stringify(collection));
    formData.append('collectionOther', JSON.stringify(collectionOther));
    formData.append('LblexternalNumber', $('#LblexternalNumber').val());
    formData.append('purchasee_request_date', $('#purchasee_request_date').val());
    formData.append('cmbBranch', $('#cmbBranch').val());
    formData.append('cmbLocation', $('#cmbLocation').val());
    formData.append('DtexpectedDate', $('#DtexpectedDate').val());
    formData.append('txtRemarks', $('#txtRemarks').val());
    formData.append('txtYourReference',$('#txtYourReference').val());

    $.ajax({
        url: '/prc/updatePurchaserequestDraft/'+id,
        method: 'post',
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
            var status = response.status
           console.log(status);
            if (status) {
                showSuccessMessage("Saved as draft");
                closeCurrentTab();
                window.opener.location.reload();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

})
}


//approve
function approveRequest(id){
    $.ajax({
        url:'/prc/approveRequest/'+id,
        type:'post',
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
            /* $('#btnSave').prop('disabled', true); */
        }, success: function (response) {
          /*   $('#btnSave').prop('disabled', false);*/
            var status = response.status 
           console.log(status);
            if (status) {
                showSuccessMessage("Request approved");

                $('#btnApprove').prop('disabled',true);
                $('#btnReject').prop('disabled',true);
                closeCurrentTab();
                window.opener.location.reload();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })
}

//reject
function rejectRequest(id){
    $.ajax({
        url:'/prc/rejectRequest/'+id,
        type:'post',
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
            /* $('#btnSave').prop('disabled', true); */
        }, success: function (response) {
          /*   $('#btnSave').prop('disabled', false);*/
            var status = response.status 
           console.log(status);
            if (status) {
                showSuccessMessage("Request rejected");

                $('#btnApprove').prop('disabled',true);
                $('#btnReject').prop('disabled',true);
                closeCurrentTab();
                window.opener.location.reload();

            } else {

                showErrorMessage("Something went wrong");
            }

        }, error: function (data) {
            console.log(data.responseText)
        }, complete: function () {

        }

    })
}

//reset form
function resetForm() {
    $('.validation-invalid-label').empty();
    $('#form').trigger('reset');

}

// clear table
function clearTableData() {
    dataSource = [];
    tableData.setDataSource(dataSource);
    tableDataOther.setDataSource(dataSource);
  }
   

//get serve date
  function getServerTime(){
    $.ajax({
        url: '/prc/getServerTime',
        type: 'get',
        dataType: 'json',
        success: function (response) {
          
            var serverDate = response.date;
            var parts = serverDate.split('/');
            var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];
            $('#purchasee_request_date').val(formattedDate);
            $('#DtexpectedDate').val(formattedDate);

        },
        error: function (error) {
            console.log(error);
        },

    })
}

function newReferanceID(table,doc_number) {
    referanceID = newID("../newReferenceNumber", table,doc_number);
 //  $('#LblexternalNumber').val(referanceID);
}

