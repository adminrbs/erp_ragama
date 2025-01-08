<?php

use App\Http\Controllers\accountSettingsController;
use App\Http\Controllers\dashBoardController;
use App\Http\Controllers\deleteValidationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LimitlessController;
use App\Http\Controllers\ReferenceIdController;
use App\Http\Controllers\salesAnalyst;
use App\Http\Controllers\sessionBranchController;
use App\Http\Controllers\UtilityController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('is.logged');

/** Login */
Route::get('/', function () {
    return view('login.login');
});

Route::post('/submitForm', [LoginController::class,'loginForm']);
Route::get('/dashboardlogin', [LoginController::class,'dashboardlogin']); // dashboard loggin

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
/** End Of Login */


Route::get('/readNotification/{id}', function ($id) {
    $notification = auth()->user()->notifications()->where('id', $id)->first();

    if ($notification) {
        $notification->markAsRead();
        return redirect($notification->data['link']);
    }
    return redirect('/');
});

Auth::routes();


 /**reference numbers */
 Route::get('/newReferenceNumber/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceId']);
 Route::get('/newReferenceNumber_PO_rferenceId/{table}/{doc_number}',[ReferenceIdController::class,'PO_rferenceId']);
 Route::get('/newReferenceNumber_GRN_referenceId/{table}/{doc_number}',[ReferenceIdController::class,'GRN_referenceId']);
 Route::get('/newReferenceNumber_SalesOrder/{table}/{doc_number}',[ReferenceIdController::class,'SO_REF_id']);
 Route::get('/newReferenceNumber_SalesInvoice/{table}/{doc_number}',[ReferenceIdController::class,'SI_referenceId']);
 Route::get('/newReferenceNumber_SalesReturn/{table}/{doc_number}',[ReferenceIdController::class,'SR_referenceId']);
 Route::get('/newReferenceNumber_GoodsReturn/{table}/{doc_number}',[ReferenceIdController::class,'GRN_return_referenceId']);
 Route::get('/newReferenceNumber_cash_bundles/{table}/{doc_number}',[ReferenceIdController::class,'cash_bundle_referenceID']);
 Route::get('/newReferenceNumber_return_transfer/{table}/{doc_number}',[ReferenceIdController::class,'SR_trans_referenceId']);
 Route::get('/newReferenceNumber_Goods_transfer/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_Goods_transfer']);
 Route::get('/newReferenceNumber_cheque_collection/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_cheque_collection']);
 Route::get('/newReferenceNumber_sample_dispatch/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_sample_dispatch']);
 Route::get('/newReferenceNumber_customer_transaction_allocation/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_customer_transaction_allocation']);
 Route::get('/newReferenceNumber_stockadjustment/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_stockadjustment']);
 Route::get('/newReferenceNumber_debit_note/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_debit_note']);
 Route::get('/newReferenceNumber_credit_note/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_credit_note']);
 Route::get('/newReferenceNumber_dispatch_to_branch/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_dispatch_to_branch']);
 Route::get('/newReferenceNumber_chqReturn/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_chqReturn']);
 Route::get('/newReferenceNumber_dispatch_receive/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_dispatch_receive']);
 Route::get('/newReferenceNumber_reverse_dispatch/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_reverse_dispatch']);
 Route::get('/newReferenceNumber_creditor_debit_note/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_creditor_debit_note']);
 Route::get('/newReferenceNumber_creditor_credit_note/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_creditor_credit_note']);
 Route::get('/newReferenceNumber_InternalOrders/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_InternalOrders']);
 Route::get('/newReferenceNumber_supplierPayment/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_supplierPayment']);
 Route::get('/newReferenceNumber_supplier_transaction_allocation/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_supplier_transaction_allocation']);
 Route::get('/newReferenceNumber_BonusClaim_referenceId/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_BonusClaim_referenceId']);
 Route::get('/newReferenceNumber_direct_cash_bundles/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_direct_cash_bundles']);
 Route::get('/newReferenceNumber_direct_cheque_bundles/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_direct_cheque_bundles']);
 Route::get('/newReferenceNumber_sales_invoice_copy_issued/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_sales_invoice_copy_issued']);
 Route::get('/newReferenceNumber_paymentVoucher_referenceId/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_paymentVoucher_referenceId']);
 Route::get('/newReferenceNumber_JournalEntry/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_JournalEntry']);
 Route::get('/newReferenceNumber_FundTransfer/{table}/{doc_number}',[ReferenceIdController::class,'newReferenceNumber_FundTransfer']);


/** Utility */
Route::get('/contains_value/{table}/{column}/{value}',[UtilityController::class,'containsValue']);

/**delete validation */
Route::get('/value_exist/{table}/{columnName}/{id}',[deleteValidationController::class,'checkDeleteItem']);

Route::get('/pro',function(){
    return view('progress');
});

Route::get('/getBranches',[sessionBranchController::class,'getBranches']);

/**Account settings */
Route::post('/updatePassword',[accountSettingsController::class,'updatePassword']);

/**get sales order data to dashboard */
Route::get('/loadOrderData',[dashBoardController::class,'loadOrderData']);

/** export po data to excel */
Route::get('/po_export_excell/{filters}',[ExportController::class,'exportToExcel']);

/**load logo to login page */
Route::get('/get_logo_path',[LoginController::class,'getLogoPpath']);

/**load sales analysist */
Route::get('/loadSupplyGroupsAsSalesAnalyst',[salesAnalyst::class,'loadSupplyGroupsAsSalesAnalyst']);

