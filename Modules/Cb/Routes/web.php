<?php

use App\Http\Controllers\ReferenceIdController;
use Illuminate\Support\Facades\Route;
use Modules\Cb\Http\Controllers\AuditController;
use Modules\Cb\Http\Controllers\BanktransferController;
use Modules\Cb\Http\Controllers\CardPaymentController;
use Modules\Cb\Http\Controllers\CashAuditReportController;
use Modules\Cb\Http\Controllers\CashBundleController;
use Modules\Cb\Http\Controllers\CashCollectionController;
use Modules\Cb\Http\Controllers\CashDashboardController;
use Modules\Cb\Http\Controllers\ChequeAuditReportController2;
use Modules\Cb\Http\Controllers\ChequecollectionByBranchcashierReportController;
use Modules\Cb\Http\Controllers\ChequeDepositController;
use Modules\Cb\Http\Controllers\ChequeDishonourController;
use Modules\Cb\Http\Controllers\ChequeRegisterController;
use Modules\Cb\Http\Controllers\ChequeReturnController;
use Modules\Cb\Http\Controllers\ChqueAuditReportController;
use Modules\Cb\Http\Controllers\CustomerReceiptController;
use Modules\Cb\Http\Controllers\CustomerReceiptListController;
use Modules\Cb\Http\Controllers\CustomerReceiptReportController;
use Modules\Cb\Http\Controllers\DirectReceiptController;
use Modules\Cb\Http\Controllers\ReportController;
use Modules\Cb\Http\Controllers\ChequesBankedController;
use Modules\Cb\Http\Controllers\ChquesToBeBankedController;
use Modules\Cb\Http\Controllers\DashboardDataController;
use Modules\Cb\Http\Controllers\DirectChequeController;
use Modules\Cb\Http\Controllers\PaymentVoucherController;
use Modules\Cb\Http\Controllers\ReturnChequesController;
use Modules\Cb\Http\Controllers\SfaReceiptsManageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('cb')->middleware(['is.logged'])->group(function () {



    /** Customer Receipt */
    Route::get('/customer_receipt', function () {
        return  view('cb::customer_receipt');
    });
    Route::get('/customer_receipt/new_referance_id/{table}/{doc_number}',[ReferenceIdController::class,'CustomerReceipt_referenceID']);
    Route::get('/customer_receipt/getCustomers', [CustomerReceiptController::class, 'getCustomers']);
    Route::get('/customer_receipt/getEmployees', [CustomerReceiptController::class, 'getEmployees']);
    Route::get('/customer_receipt/getBranch', [CustomerReceiptController::class, 'getBranch']);
    Route::get('/customer_receipt/getBank', [CustomerReceiptController::class, 'getBank']);
    Route::get('/customer_receipt/getReceiptMethod', [CustomerReceiptController::class, 'getReceiptMethod']);
    Route::get('/customer_receipt/getBankBranch/{bank_id}', [CustomerReceiptController::class, 'getBankBranch']);
    Route::get('/customer_receipt/getAutoSelectBankBranch/{bank_code}/{branch_code}', [CustomerReceiptController::class, 'getAutoSelectBankBranch']);
    Route::get('/customer_receipt/loadSetoffTable/{customer_id}', [CustomerReceiptController::class, 'loadSetoffTable']);
    Route::post('/customer_receipt/saveCustomerReceipt', [CustomerReceiptController::class, 'saveCustomerReceipt']);
    Route::get('/customer_receipt/getCustomerReceipt/{id}', [CustomerReceiptController::class, 'getCustomerReceipt']);
    Route::put('/customer_receipt/updateCustomerReceipt/{id}', [CustomerReceiptController::class, 'updateCustomerReceipt']);
    /** End of Customer Receipt */


    /** Customer Receipt List*/
    Route::get('/customer_receipt_list', function () {
        return  view('cb::customer_receipt_list');
    })->middleware(['is.logged','can:cb_customer_receipt']);
    Route::get('/customer_receipt_list/getReceiptList', [CustomerReceiptListController::class, 'getReceiptList']);

 //Customer Receipt Report
 Route::get('/printCustomerReceiptReport',[CustomerReceiptReportController::class,'printCustomerReceiptReport']);

    /** End of Customer Receipt List*/

    /**cash collection  */
    Route::get('/cash_collection_by_branch',function(){
        return view('cb::cash_collection_by_branch');
    })->middleware(['is.logged','can:cb_cash_collection_by_branch']);

    Route::get('/cash_collection_by_ho',function(){
        return view('cb::cash_collection_by_ho');
    })->middleware(['is.logged','can:cb_cash_collection_by_ho']);

    Route::get('/cheque_collection_by_branch',function(){
        return view('cb::cheque_collection_by_branch');
    })->middleware(['is.logged','can:cb_cheque_collection_by_branch']); //edit permison

    Route::get('/cheque_collection_by_branch_to_collect_sfa',function(){
        return view('cb::cheque_collected_by_branch_sfa_rcpt');
    })->middleware(['is.logged','can:cb_cheque_collected_by_branch_cashier_sfa']);

    Route::get('/cheque_collection_by_ho',function(){
        return view('cb::cheque_collection_by_ho');
    })->middleware(['is.logged','can:cb_cheque_collection_by_ho']);

    Route::get('/loadCustomerReceipts_cash_branch/{br_id}/{collector_id_}',[CashCollectionController::class,'loadCustomerReceipts_cash_branch']);
    Route::post('/update_status_calculation',[CashCollectionController::class,'update_status_calculation']);

    Route::get('/loadCustomerReceipts_cash_ho',[CashCollectionController::class,'loadCustomerReceipts_cash_ho']);
    Route::post('/update_status_calculation_cash_ho/{receipt_id}',[CashCollectionController::class,'update_status_calculation_cash_ho']);
    Route::post('/update_cash_ho',[CashCollectionController::class,'update_cash_ho']);


    Route::get('/loadCustomerReceipts_cheque_branch/{br_id}',[CashCollectionController::class,'loadCustomerReceipts_cheque_branch']);
    Route::get('/loadCustomerReceipts_cheque_branch_sfa_collect/{br_id}/{collector_id_}',[CashCollectionController::class,'loadCustomerReceipts_cheque_branch_for_collect']);
    Route::post('/update_status_calculation_cheque_branch/{receipt_id}',[CashCollectionController::class,'update_status_calculation_cheque_branch']);
    Route::post('/update_chq_branch',[CashCollectionController::class,'update_chq_branch']);
    
    Route::get('/loadCustomerReceipts_cheque_ho/{br_id}',[CashCollectionController::class,'loadCustomerReceipts_cheque_ho']);
    Route::post('/update_status_calculation_cheque_ho/{receipt_id}',[CashCollectionController::class,'update_status_calculation_cheque_ho']);
    Route::post('/update_chq_ho',[CashCollectionController::class,'update_chq_ho']);
    Route::get('/load_cash_BookNumber',[CashCollectionController::class,'load_cash_BookNumber']);
    Route::get('/load_cheque_BookNumber',[CashCollectionController::class,'load_cheque_BookNumber']);
    /** end of cash collection */

    /**cash bundle */
    Route::post('/add_cash_bundle',[CashBundleController::class,'add_cash_bundle']);
    Route::get('/loadInvoices_cash_ho/{id}',[CashBundleController::class,'loadInvoices_cash_ho']);
    
    //print cash table
    Route::get('/printTable/{br_id}/{collector_id}',[ReportController::class,'printreport']);
    Route::get('/printCashTable/{id_array}/{collectorId}',[ReportController::class,'printCashTable']);

     //print chq table
     Route::get('/print_chq_Table/{br_id}/{collector_id}/{book}/{page}',[ChequecollectionByBranchcashierReportController::class,'branch_cashier_report']);
     Route::get('/print_chq_rcpt_Table/{idArray}/{collectorId}',[ChequecollectionByBranchcashierReportController::class,'print_chq_rcpt_Table']);

    /**customer rectipts - cash bundle */
    Route::get('/cus_rcpt_cash_bundle',function(){
        return view('cb::customer_receipt_cash_bundle');
    });
    Route::get('/loadcashBundle_receipt',[CashBundleController::class,'getCashBundle_receipt']);
    Route::post('/create_rcpt',[CashBundleController::class,'create_rcpt']);
    Route::get('/loadInvoices_cus_rcpt/{id}',[CashBundleController::class,'loadInvoices_recipt']);

    /**customer receipts cheque */
    Route::post('/create_rcpt_cheque',[CashBundleController::class,'create_rcpt_cheque']);
    
    /**cheque deposit */
    Route::get('/cheque_deposit',function(){
        return view('cb::cheque_deposit_list');
    })->middleware(['is.logged','can:cb_cheque_deposit']);

    Route::get('/cheque_dishonour',function(){
        return view('cb::cheque_dishonure');
    })->middleware(['is.logged','can:cb_cheque_dishonour']);

    Route::get('/cheque_dishonour_list',function(){
        return view('cb::cheque_dishonored_list');
    })->middleware(['is.logged','can:cb_cheque_dishonour_list']);

    Route::get('/getAccount',[ChequeDepositController::class,'getAccount']);
    Route::get('/load_cheques_for_deposit',[ChequeDepositController::class,'load_cheques_for_deposit']);
    Route::post('/deposit_cheque/{account_id_}',[ChequeDepositController::class,'deposit_cheque']);
    Route::get('/load_deposited_cheques_for_dishonor',[ChequeDishonourController::class,'load_deposited_cheques_for_dishonor']);
 /*    Route::post('/dishonour_cheque_return', [ChequeDishonourController::class,'dishonour_cheque_return']); */
    
    Route::get('/dishonour_cheque_return_controller/{id}',[ChequeDishonourController::class,'dishonour_cheque_return']);
    Route::get('/load_dishonour_reasons',[ChequeDishonourController::class,'load_dishonour_reasons']);

    /**cash audit */
    Route::get('/cash_audit',function(){
        return view('cb::cash_audit');
    });
    Route::get('/load_cash_receipts_for_audit/{br_id}/{collector_id}',[AuditController::class,'load_cash_receipts_for_audit']);
    Route::post('/update_audit_cash',[AuditController::class,'update_audit_cash']);
    Route::get('/cash_audit_list',function(){
        return view('cb::cash_audited_list');
    });
    Route::get('/load_audited_cash_receipts/{br_id}/{collector_id}',[AuditController::class,'load_audited_cash_receipts']);

    /**cheque audit  */
    Route::get('/cheque_audit',function(){
        return view('cb::cheque_audit');
    });

    Route::get('/cheque_audit_list',function(){
        return view('cb::cheque_audieted_list');
    });
    Route::get('/load_cheque_for_audit/{branch_id}/{collector_id}',[AuditController::class,'load_cheque_receipt_for_audit']);
    Route::post('/update_audit_cheque',[AuditController::class,'update_audit_cheque']);
    Route::get('/load_audited_cheque/{br_id}/{collector_id}',[AuditController::class,'load_audited_cheque']);



      // Cash Bank Reports
    Route::get('/cash_bank_reports',function(){
        return view('cb::cashBankReports');
    });
    Route::get('/getbranch',[ChqueAuditReportController::class,'getbranch']);
    Route::get('/getSalesrep',[ChqueAuditReportController::class,'getSalesrep']);
    Route::get('/getCustomer',[ChqueAuditReportController::class,'getCustomer']);
    Route::get('/chequeAuditReport/{filters}',[ChequeAuditReportController2::class,'chequeAuditReport']);
    Route::get('/cashAuditReport/{filters}',[CashAuditReportController::class,'cashAuditReport']);
    Route::get('/chequeToBeBanked/{filters}',[ChquesToBeBankedController::class,'chequeToBeBanked']);
    Route::get('/chequeBanked/{filters}',[ChequesBankedController::class,'chequeBanked']);
    Route::get('/returnCheques/{filters}',[ReturnChequesController::class,'returnCheques']);
    Route::get('/chequeRegister/{filters}',[ChequeRegisterController::class,'chequeRegister']);
    Route::get('/getSalesrepandcollectors',[ChequeRegisterController::class,'getSalesrepandcollectors']);
    Route::get('/bankTransfer/{filters}',[BanktransferController::class,'bankTransfer']);
    Route::get('/cardPayment/{filters}',[CardPaymentController::class,'cardPayment']);

    //cheque return
    Route::get('/cheque_return',function(){
        return view('cb::cheque_return');
    });
    Route::get('/cheque_return_cancel_approval_list',function(){
        return view('cb::cheque_returned_cancel_list');
    });
    Route::get('/load_cheques/{customerID}',[ChequeReturnController::class,'load_cheques']);
    Route::post('/add_chq_return/{id}',[ChequeReturnController::class,'add_chq_return']);
    Route::get('/load_dishonoured_cheques',[ChequeDishonourController::class,'load_dishonoured_cheques']);
    Route::get('/load_data_through_chq_no/{chq_no}',[ChequeReturnController::class,'load_data_through_chq_no']);
    Route::post('/cancel_return/{id}',[ChequeReturnController::class,'cancel_return']);
    Route::get('/load_dishonoured_cheques_canceled',[ChequeReturnController::class,'load_dishonoured_cheques_canceled']);
    Route::post('/approve_return_cancelation/{id}/{type}',[ChequeReturnController::class,'approve_return_cancelation']);

   //cash with sales rep
   Route::get('/cash_with_sales_rep_list',function(){
    return view('cb::cash_with_sales_rep_list');
    
   })->middleware(['is.logged','can:cb_cash_with_rep']);
   
   Route::get('/load_cash_with_sales_rep',[DashboardDataController::class,'load_cash_with_sales_rep']);
   Route::get('/load_cash_with_rep_data/{id}',[DashboardDataController::class,'load_cash_with_rep_data']);

   //cheque with sales rep
   Route::get('/cheque_with_sales_rep_list',function(){
    return view('cb::cheque_with_sales_rep_list');
   })->middleware(['is.logged','can:cb_cheque_with_rep']);

   Route::get('/load_cheque_with_sales_rep',[DashboardDataController::class,'load_cheque_with_sales_rep']);
   Route::get('/load_cheque_with_rep_data/{id}',[DashboardDataController::class,'load_cheque_with_rep_data']);

   //direct customer cash collection by head office
   Route::get('/direct_cash_bundle_list',function(){
    return view('cb::direct_cash_bundle_list');
   });
   Route::get('/direct_cash_bundle',function(){
    return view('cb::direct_cash_bundle');
   });
   
   Route::get('/load_direct_cash_create_to_bundle/{id}',[DirectReceiptController::class,'load_direct_cash_create_to_bundle']);
   Route::post('/create_direct_cash_bundle',[DirectReceiptController::class,'create_direct_cash_bundle']);
   Route::get('/load_direct_cash_bundles/{branch}',[DirectReceiptController::class,'load_direct_cash_bundles']);
   Route::get('/loadDirectReciptsToModal/{id}',[DirectReceiptController::class,'loadDirectReciptsToModal']);
   Route::get('/direcet_cash_bundle_ho_recived_list',function(){
        return view('cb::direct_cash_bundle_ho_received');
   });
   Route::post('/received_direct_Bundle_head_office',[DirectReceiptController::class,'received_direct_Bundle_head_office']);

   //Direct cheque collection
   Route::get('/dirct_cheque_collection',function(){
        return view('cb::direct_cheque_collection');
   });
   Route::get('/dirct_cheque_collection_list',function(){
    return view('cb::direct_cheque_collection_list');
});
   Route::get('/load_direct_cheque_create_to_bundle/{id}',[DirectChequeController::class,'load_direct_cheque_create_to_bundle']);
   Route::post('/create_direct_cheque_bundle',[DirectChequeController::class,'create_direct_cheque_bundle']);
   Route::get('/load_direct_cheque_collection/{id}',[DirectChequeController::class,'load_direct_cheque_collection']);
   Route::get('/loadDirectChequeReciptsToModal/{id}',[DirectChequeController::class,'loadDirectChequeReciptsToModal']);
   Route::get('/direct_cheque_collection_ho_recived_list',function(){
        return view('cb::direct_cheque_collection_ho_received');
   });
   Route::get('/loadDirectChequeReciptsToModal/{id}',[DirectChequeController::class,'loadDirectChequeReciptsToModal']);
   Route::post('/received_direct_cheque_collection_head_office',[DirectChequeController::class,'received_direct_cheque_collection_head_office']);


   //Dashboard
   Route::get('/cashDashBoard',function(){
    return view('cb::dashboard');
   });
   Route::get('/loadDatatoDashboard',[CashDashboardController::class,'loadDatatoDashboard']);
   Route::get('/loadDataAccordingToRep/{id}',[CashDashboardController::class,'loadDataAccordingToRep']);
   Route::get('/loadEmployeesCashDashBoard',[CashDashboardController::class,'loadEmployeesCashDashBoard']);

   //SFA Receipts cancelaton and modification
   Route::get('/sfa_receipts_list',function(){
        return view('cb::sfa_receipts');
   });
   Route::get('/load_sfa_receipts/{br_id}/{rep_id}',[SfaReceiptsManageController::class,'load_sfa_receipts']);
   Route::get('/load_sfa_reciepts_for_change/{id}',[SfaReceiptsManageController::class,'load_sfa_reciepts_for_change']);
   Route::post('/changeType/{id}',[SfaReceiptsManageController::class,'changeType']);
   Route::post('/cancelReceipt/{id}',[SfaReceiptsManageController::class,'cancelReceipt']);

   /**Payment vouchers */
   Route::get('/payment_voucher',function(){
        return view("cb::payment_vouchers");
   });
   Route::get('/payment_voucher_list',function(){
    return view("cb::payment_vouchers_list");
   });
   Route::get('/loadPayee',[PaymentVoucherController::class,'loadPayee']);
   Route::get('/loadAccounts',[PaymentVoucherController::class,'loadAccounts']);
   Route::get('/loadAccountAnalysisData',[PaymentVoucherController::class,'loadAccountAnalysisData']);
   Route::post('/saveVoucher',[PaymentVoucherController::class,'saveVoucher']);
   Route::get('/getGRNdata',[PaymentVoucherController::class,'getGRNdata']);
});
