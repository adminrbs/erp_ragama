$(document).ready(function(){
    loadSupplyGroupsAsSalesAnalyst();
});

//load supply groups as sales analysts to sales invoice and sales return.
function loadSupplyGroupsAsSalesAnalyst(){
    $.ajax({
        url: '/loadSupplyGroupsAsSalesAnalyst',
        type: 'get',
        async: false,
        success: function (data) {
            $.each(data, function (index, value) {
                $('#cmbSalesAnalysist').append('<option value="' + value.supply_group_id + '">' + value.supply_group + '</option>');

            });
            $('#cmbSalesAnalysist').trigger('change');
        },
    })
}