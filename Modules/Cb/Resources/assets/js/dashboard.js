$(document).ready(function(){
    loadDatatoDashboard();
    loadEmployeesCashDashBoard();

    $('#cmbEmp').on('change',function(){
        loadDataAccordingToRep($(this).val())
    });
    loadDataAccordingToRep(0);


    setInterval(loadDataAccordingToRep(0), 300000);
    setInterval(loadDatatoDashboard(), 300000);
});


function loadDatatoDashboard() {
    $('#all_cash tbody').empty();
    $.ajax({
        url: '/cb/loadDatatoDashboard',
        type: 'GET',
        async: false,
        success: function(response) {
            console.log(response.cash_with_rep);
            //cash with rep - all cash
            $.each(response.cash_with_rep, function(index, value) {
                var total_cash = value.total_cash ? parseFloat(value.total_cash).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_late = value.total_late ? parseFloat(value.total_late).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td>${'Cash With Rep'}</td>
                        <td style="text-align: right;">${total_cash}</td>
                        <td style="text-align: right;">${total_late}</td>
                    </tr>
                `;
                $('#all_cash tbody').append(row);
            });
            
            $.each(response.cheque_with_rep, function(index, value) {
                var total_cheque = value.total_cheque ? parseFloat(value.total_cheque).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_late = value.total_late ? parseFloat(value.total_late).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td>${'Cheques With Rep'}</td>
                        <td style="text-align: right;">${total_cheque}</td>
                        <td style="text-align: right;">${total_late}</td>
                    </tr>
                `;
                $('#all_cash tbody').append(row);
            });
            
            $.each(response.cash_with_cashier, function(index, value) {
                var total_rep_cash_with_cashier = value.total_rep_cash_with_cashier ? parseFloat(value.total_rep_cash_with_cashier).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_late_rep_cash_with_cashier = value.total_late_rep_cash_with_cashier ? parseFloat(value.total_late_rep_cash_with_cashier).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td>${'Cash with cashier (Rep)'}</td>
                        <td style="text-align: right;">${total_rep_cash_with_cashier}</td>
                        <td style="text-align: right;">${total_late_rep_cash_with_cashier}</td>
                    </tr>
                `;
                $('#all_cash tbody').append(row);
            });
            
            $.each(response.cheque_with_Cashier, function(index, value) {
                var total_rep_cheque_with_cashier = value.total_rep_cheque_with_cashier ? parseFloat(value.total_rep_cheque_with_cashier).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_late_rep_cheque_with_cashier = value.total_late_rep_cheque_with_cashier ? parseFloat(value.total_late_rep_cheque_with_cashier).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td>${'Cheque with cashier (Rep)'}</td>
                        <td style="text-align: right;">${total_rep_cheque_with_cashier}</td>
                        <td style="text-align: right;">${total_late_rep_cheque_with_cashier}</td>
                    </tr>
                `;
                $('#all_cash tbody').append(row);
            });
            
            $.each(response.direct_Cash_with_Cashier, function(index, value) {
                var total_direct_cash_with_cashier = value.total_direct_cash_with_cashier ? parseFloat(value.total_direct_cash_with_cashier).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var direct_late_cash_with_cashier = value.direct_late_cash_with_cashier ? parseFloat(value.direct_late_cash_with_cashier).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td>${'Direct cash with cashier (Office)'}</td>
                        <td style="text-align: right;">${total_direct_cash_with_cashier}</td>
                        <td style="text-align: right;">${direct_late_cash_with_cashier}</td>
                    </tr>
                `;
                $('#all_cash tbody').append(row);
            });

            $.each(response.direct_cheque_with_Cashier, function(index, value) {
                var total_direct_cheque_with_cashier = value.total_direct_cheque ? parseFloat(value.total_direct_cheque).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var direct_late_cheque_with_cashier = value.late_direct_cheque ? parseFloat(value.late_direct_cheque).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td>${'Direct Cheque with cashier (Office)'}</td>
                        <td style="text-align: right;">${total_direct_cheque_with_cashier}</td>
                        <td style="text-align: right;">${direct_late_cheque_with_cashier}</td>
                    </tr>
                `;
                $('#all_cash tbody').append(row);
            });
      
        },
        error: function(xhr, status, error) {
            console.error('Error loading data:', status, error);
        }
    });
}



function loadEmployeesCashDashBoard() {

    $.ajax({
        type: "GET",
        url: '/cb/loadEmployeesCashDashBoard',
        async: false,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function () {

        },
        success: function (response) {
            var employees = response.data;
               
            $('#cmbEmp').append('<option value="0">Any</option>');
                for (var i = 0; i < employees.length; i++) {
                    var id = employees[i].employee_id;
                    var name = employees[i].employee_name;
                    $('#cmbEmp').append('<option value="' + id + '">' + name + '</option>');
                  
                }

                $('#cmbEmp').trigger('change');

            

        },
        error: function (error) {
            console.log(error);

        },
        complete: function () {

        }

    });


}

function loadDataAccordingToRep(id){
    $('#collector_wise_cash tbody').empty();
    $.ajax({
        url: '/cb/loadDataAccordingToRep/'+id,
        type: 'GET',
        async: false,
        success: function(response) {
            console.log(response.rep_data);
            $.each(response.rep_data, function(index, value) {
                var total_cash = value.total_cash ? parseFloat(value.total_cash).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_late_cash = value.total_late ? parseFloat(value.total_late).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_cheque = value.total_cheque ? parseFloat(value.total_cheque).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_late_cheque = value.total_cheque_late ? parseFloat(value.total_cheque_late).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td style="text-align:left;">${value.employee_name}</td>
                        <td>${total_cash}</td>
                        <td>${total_cheque}</td>
                        <td>${total_late_cash}</td>
                        <td>${total_late_cheque}</td>
                    </tr>
                `;
                $('#collector_wise_cash tbody').append(row);
            });
            
          /*   $.each(response.cheque_with_rep, function(index, value) {
                var total_cheque = value.total_cheque ? parseFloat(value.total_cheque).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                var total_late = value.total_late ? parseFloat(value.total_late).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '-';
                
                var row = `
                    <tr>
                        <td>${'Cheques With Rep'}</td>
                        <td>${total_cheque}</td>
                        <td>${total_late}</td>
                    </tr>
                `;
                $('#collector_wise_cash tbody').append(row);
            });  */
      
        },
        error: function(xhr, status, error) {
            console.error('Error loading data:', status, error);
        }
    });

}